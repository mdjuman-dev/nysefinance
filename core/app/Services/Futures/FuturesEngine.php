<?php

namespace App\Services\Futures;

use App\Constants\Status;
use App\Models\CoinPair;
use App\Models\Currency;
use App\Models\FuturePosition;
use App\Models\Transaction;
use App\Models\User;
use App\Models\Wallet;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use RuntimeException;

/**
 * USDT-margined, isolated-margin futures (house is the counterparty).
 *
 *   notional   = margin × leverage            size = notional / entry
 *   fee        = notional × fee%  (charged on open and on close)
 *   liq price  = long:  entry × (1 − 1/lev + mm%)
 *                short: entry × (1 + 1/lev − mm%)
 *   payout     = max(0, margin + pnl − close fee); liquidation pays 0.
 *
 * Every balance change happens inside a DB transaction with the wallet
 * (and position) rows locked, and is recorded in `transactions`.
 */
class FuturesEngine
{
    public const SETTLEMENT = 'USDT';

    /* ---------------------------------------------------------------- wallets */

    public static function settlementCurrency(): Currency
    {
        return Currency::where('symbol', self::SETTLEMENT)->firstOrFail();
    }

    /** The user's futures wallet, created on first use. */
    public static function futuresWallet(int $userId, bool $lock = false): Wallet
    {
        $currencyId = self::settlementCurrency()->id;
        $query = Wallet::where('user_id', $userId)->where('currency_id', $currencyId)->where('wallet_type', Status::WALLET_TYPE_FUTURE);
        $wallet = $lock ? $query->lockForUpdate()->first() : $query->first();

        if (!$wallet) {
            $wallet = new Wallet();
            $wallet->user_id = $userId;
            $wallet->currency_id = $currencyId;
            $wallet->wallet_type = Status::WALLET_TYPE_FUTURE;
            $wallet->balance = 0;
            $wallet->save();
            if ($lock) $wallet = Wallet::whereKey($wallet->id)->lockForUpdate()->first();
        }
        return $wallet;
    }

    public static function spotWallet(int $userId, bool $lock = false): ?Wallet
    {
        $query = Wallet::where('user_id', $userId)->where('currency_id', self::settlementCurrency()->id)->where('wallet_type', Status::WALLET_TYPE_SPOT);
        return $lock ? $query->lockForUpdate()->first() : $query->first();
    }

    /** Move USDT between spot and futures wallets. $direction: 'to_futures' | 'to_spot' */
    public static function transfer(User $user, string $direction, float $amount): array
    {
        if ($amount <= 0) throw new RuntimeException('Enter a valid amount');

        return DB::transaction(function () use ($user, $direction, $amount) {
            $spot = self::spotWallet($user->id, true);
            if (!$spot) throw new RuntimeException('USDT spot wallet not found');
            $futures = self::futuresWallet($user->id, true);

            [$from, $to] = $direction === 'to_spot' ? [$futures, $spot] : [$spot, $futures];
            if ($amount > (float) $from->balance) throw new RuntimeException('Insufficient balance');

            $trx = getTrx();
            $label = $direction === 'to_spot' ? 'Futures → Spot' : 'Spot → Futures';
            self::move($from, -$amount, $user->id, $trx, 'futures_transfer', "Transfer $label");
            self::move($to, $amount, $user->id, $trx, 'futures_transfer', "Transfer $label");

            return ['spot' => (float) $spot->balance, 'futures' => (float) $futures->balance];
        });
    }

    /* -------------------------------------------------------------- positions */

    public static function liquidationPrice(string $side, float $entry, int $leverage, float $mmPercent): float
    {
        $mm = $mmPercent / 100;
        $price = $side === 'long' ? $entry * (1 - 1 / $leverage + $mm) : $entry * (1 + 1 / $leverage - $mm);
        return max($price, 0);
    }

    /**
     * @param array{pair_id:int, side:string, margin:float, leverage:int, take_profit:?float, stop_loss:?float} $in
     */
    public static function open(User $user, array $in): FuturePosition
    {
        $pair = CoinPair::active()->activeMarket()->activeCoin()->where('futures_enabled', true)->find($in['pair_id']);
        if (!$pair) throw new RuntimeException('Futures trading is not available for this pair');

        $side = $in['side'];
        $leverage = (int) $in['leverage'];
        $margin = round((float) $in['margin'], 8);

        if (!in_array($side, ['long', 'short'], true)) throw new RuntimeException('Invalid side');
        if ($leverage < 1 || $leverage > (int) $pair->max_leverage) throw new RuntimeException("Leverage must be between 1x and {$pair->max_leverage}x");
        if ($margin < (float) $pair->futures_min_margin) throw new RuntimeException('Minimum margin is ' . getAmount($pair->futures_min_margin) . ' ' . self::SETTLEMENT);

        $price = MarkPrice::forPair($pair);
        if (!$price) throw new RuntimeException('Live price unavailable, please try again');

        $notional = $margin * $leverage;
        $size = $notional / $price;
        $feeRate = (float) $pair->futures_fee_percent;
        $mmRate = (float) $pair->maintenance_margin_percent;
        $fee = round($notional * $feeRate / 100, 8);
        $liq = self::liquidationPrice($side, $price, $leverage, $mmRate);

        [$tp, $sl] = self::validateTpSl($side, $price, $liq, $in['take_profit'] ?? null, $in['stop_loss'] ?? null);

        return DB::transaction(function () use ($user, $pair, $side, $leverage, $margin, $price, $size, $fee, $liq, $tp, $sl, $feeRate, $mmRate) {
            $wallet = self::futuresWallet($user->id, true);
            if ($margin + $fee > (float) $wallet->balance) {
                throw new RuntimeException('Insufficient futures balance (margin + fee ' . getAmount($margin + $fee) . ' ' . self::SETTLEMENT . ')');
            }

            $position = FuturePosition::create([
                'user_id'           => $user->id,
                'pair_id'           => $pair->id,
                'trx'               => getTrx(),
                'side'              => $side,
                'leverage'          => $leverage,
                'margin'            => $margin,
                'size'              => $size,
                'entry_price'       => $price,
                'liquidation_price' => $liq,
                'maintenance_rate'  => $mmRate,
                'fee_rate'          => $feeRate,
                'take_profit'       => $tp,
                'stop_loss'         => $sl,
                'open_fee'          => $fee,
                'status'            => 'open',
            ]);

            $symbol = str_replace('_', '/', $pair->symbol);
            self::move($wallet, -$margin, $user->id, $position->trx, 'futures_open', "Open {$leverage}x " . ucfirst($side) . " $symbol (margin)");
            if ($fee > 0) self::move($wallet, -$fee, $user->id, $position->trx, 'futures_fee', "Opening fee $symbol", $fee);

            return $position;
        });
    }

    public static function updateTpSl(User $user, int $positionId, ?float $tp, ?float $sl): FuturePosition
    {
        $position = FuturePosition::where('user_id', $user->id)->open()->with('pair')->findOrFail($positionId);
        $price = MarkPrice::forPair($position->pair) ?? $position->entry_price;
        [$tp, $sl] = self::validateTpSl($position->side, $price, $position->liquidation_price, $tp, $sl);
        $position->update(['take_profit' => $tp, 'stop_loss' => $sl]);
        return $position;
    }

    /** Close at the current mark price (manual, TP or SL). */
    public static function close(int $positionId, string $reason = 'manual', ?int $userId = null, ?float $price = null): ?FuturePosition
    {
        return DB::transaction(function () use ($positionId, $reason, $userId, $price) {
            $q = FuturePosition::whereKey($positionId)->open()->lockForUpdate();
            if ($userId) $q->where('user_id', $userId);
            $position = $q->with('pair')->first();
            if (!$position) return null; // already closed by another request/cron

            $price ??= MarkPrice::forPair($position->pair);
            if (!$price) throw new RuntimeException('Live price unavailable, please try again');

            $wallet = self::futuresWallet($position->user_id, true);
            $symbol = str_replace('_', '/', $position->pair->symbol ?? '');

            // Price beyond the liquidation level: settle as a liquidation instead.
            $liquidated = $position->isLong() ? $price <= $position->liquidation_price : $price >= $position->liquidation_price;
            if ($liquidated) {
                $position->update([
                    'status' => 'liquidated', 'close_reason' => 'liquidation', 'close_price' => $price,
                    'realized_pnl' => -$position->margin, 'close_fee' => 0, 'payout' => 0, 'closed_at' => now(),
                ]);
                self::move($wallet, 0, $position->user_id, $position->trx, 'futures_liquidation', "Liquidated {$position->leverage}x " . ucfirst($position->side) . " $symbol");
                return $position;
            }

            $pnl = $position->pnlAt($price);
            $closeFee = round($price * $position->size * $position->fee_rate / 100, 8);
            $payout = max(0, round($position->margin + $pnl - $closeFee, 8));

            $position->update([
                'status' => 'closed', 'close_reason' => $reason, 'close_price' => $price,
                'realized_pnl' => $pnl, 'close_fee' => $closeFee, 'payout' => $payout, 'closed_at' => now(),
            ]);

            $label = ['manual' => 'Closed', 'take_profit' => 'Take-profit', 'stop_loss' => 'Stop-loss'][$reason] ?? 'Closed';
            self::move($wallet, $payout, $position->user_id, $position->trx, 'futures_close',
                "$label {$position->leverage}x " . ucfirst($position->side) . " $symbol (PnL " . ($pnl >= 0 ? '+' : '') . getAmount($pnl) . ', fee ' . getAmount($closeFee) . ')', $closeFee);

            return $position;
        });
    }

    /**
     * Liquidation / TP / SL sweep. Scope to one user for page polling, or all
     * users for the cron. Returns how many positions were settled.
     */
    public static function sweep(?int $userId = null): int
    {
        $query = FuturePosition::open()->with('pair.marketData');
        if ($userId) $query->where('user_id', $userId);
        $positions = $query->get();
        if ($positions->isEmpty()) return 0;

        $prices = MarkPrice::forPairs($positions->pluck('pair'));
        $settled = 0;

        foreach ($positions as $p) {
            $price = $prices[$p->pair_id] ?? null;
            if (!$price) continue;

            $long = $p->isLong();
            $reason = null;
            if ($long ? $price <= $p->liquidation_price : $price >= $p->liquidation_price) $reason = 'liquidation';
            elseif ($p->take_profit && ($long ? $price >= $p->take_profit : $price <= $p->take_profit)) $reason = 'take_profit';
            elseif ($p->stop_loss && ($long ? $price <= $p->stop_loss : $price >= $p->stop_loss)) $reason = 'stop_loss';

            if (!$reason) continue;
            try {
                if (self::close($p->id, $reason === 'liquidation' ? 'manual' : $reason, null, $price)) $settled++;
            } catch (\Throwable $e) {
                Log::error('Futures sweep failed', ['position' => $p->id, 'error' => $e->getMessage()]);
            }
        }

        return $settled;
    }

    /* ---------------------------------------------------------------- helpers */

    /** @return array{0:?float,1:?float} */
    private static function validateTpSl(string $side, float $price, float $liq, $tp, $sl): array
    {
        $tp = $tp !== null && $tp !== '' && (float) $tp > 0 ? (float) $tp : null;
        $sl = $sl !== null && $sl !== '' && (float) $sl > 0 ? (float) $sl : null;
        $fmt = fn ($v) => showAmount($v, currencyFormat: false);

        if ($side === 'long') {
            if ($tp !== null && $tp <= $price) throw new RuntimeException('Take-profit must be above the current price (' . $fmt($price) . ')');
            if ($sl !== null && $sl >= $price) throw new RuntimeException('Stop-loss must be below the current price (' . $fmt($price) . ')');
            if ($sl !== null && $sl <= $liq) throw new RuntimeException('Stop-loss must be above the liquidation price (' . $fmt($liq) . ')');
        } else {
            if ($tp !== null && $tp >= $price) throw new RuntimeException('Take-profit must be below the current price (' . $fmt($price) . ')');
            if ($sl !== null && $sl <= $price) throw new RuntimeException('Stop-loss must be above the current price (' . $fmt($price) . ')');
            if ($sl !== null && $sl >= $liq) throw new RuntimeException('Stop-loss must be below the liquidation price (' . $fmt($liq) . ')');
        }
        return [$tp, $sl];
    }

    /** Apply a signed change to a locked wallet and log it. */
    private static function move(Wallet $wallet, float $delta, int $userId, string $trx, string $remark, string $details, float $charge = 0): void
    {
        $wallet->balance = round((float) $wallet->balance + $delta, 8);
        $wallet->save();

        $t = new Transaction();
        $t->user_id = $userId;
        $t->wallet_id = $wallet->id;
        $t->amount = abs($delta);
        $t->charge = $charge;
        $t->post_balance = $wallet->balance;
        $t->trx_type = $delta >= 0 ? '+' : '-';
        $t->trx = $trx;
        $t->details = $details;
        $t->remark = $remark;
        $t->save();
    }
}
