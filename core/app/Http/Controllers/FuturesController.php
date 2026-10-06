<?php

namespace App\Http\Controllers;

use App\Constants\Status;
use App\Models\CoinPair;
use App\Models\FuturePosition;
use App\Services\Futures\FuturesEngine;
use App\Support\InertiaData;
use Illuminate\Http\Request;
use Inertia\Inertia;
use RuntimeException;

class FuturesController extends Controller
{
    /** Futures terminal (public; trading needs login). */
    public function index($symbol = null)
    {
        $pairs = CoinPair::active()->activeMarket()->activeCoin()->where('futures_enabled', true)
            ->with('coin:id,name,symbol,image', 'market.currency:id,symbol', 'marketData:id,pair_id,price,percent_change_24h')
            ->orderBy('id')->get();

        if ($pairs->isEmpty()) {
            $notify[] = ['error', 'Futures trading is not available right now'];
            return to_route(auth()->check() ? 'user.home' : 'home')->withNotify($notify);
        }

        $pair = $symbol ? $pairs->firstWhere('symbol', $symbol) : null;
        $pair ??= $pairs->firstWhere('is_default', Status::YES) ?? $pairs->first();

        $user = auth()->user();
        if ($user) {
            FuturesEngine::sweep($user->id); // settle anything that hit TP/SL/liquidation while away
        }

        return Inertia::render('Trade/Futures', [
            'pair'  => $this->pairData($pair),
            'pairs' => $pairs->map(fn ($p) => [
                'symbol'    => $p->symbol,
                'coin'      => $p->coin->symbol,
                'market'    => $p->market->currency->symbol ?? 'USDT',
                'image'     => $p->coin->image_url,
                'price'     => (float) ($p->marketData->price ?? 0),
                'change24h' => (float) ($p->marketData->percent_change_24h ?? 0),
                'maxLeverage' => (int) $p->max_leverage,
            ])->values(),
            'balances' => $user ? $this->balances($user->id) : null,
            'urls' => [
                'page'      => route('futures', ['symbol' => '__SYMBOL__']),
                'open'      => $user ? route('user.futures.open') : null,
                'close'     => $user ? route('user.futures.close', '__ID__') : null,
                'tpsl'      => $user ? route('user.futures.tpsl', '__ID__') : null,
                'transfer'  => $user ? route('user.futures.transfer') : null,
                'positions' => $user ? route('user.futures.positions') : null,
                'deposit'   => $user ? route('user.wallet.overview', ['sc' => 'tr']) : null,
                'login'     => route('user.login'),
                'register'  => route('user.register'),
            ],
        ]);
    }

    /** Open + recent closed positions, after a sweep (polled by the page). */
    public function positions(Request $request)
    {
        $userId = auth()->id();
        FuturesEngine::sweep($userId);

        $open = FuturePosition::where('user_id', $userId)->open()->with('pair.coin:id,symbol')->latest('id')->get();
        $history = FuturePosition::where('user_id', $userId)->where('status', '!=', 'open')->with('pair.coin:id,symbol')->latest('closed_at')->take(30)->get();

        return response()->json([
            'success'  => true,
            'open'     => $open->map(fn ($p) => $this->positionData($p))->values(),
            'history'  => $history->map(fn ($p) => $this->positionData($p))->values(),
            'balances' => $this->balances($userId),
        ]);
    }

    public function open(Request $request)
    {
        $data = $request->validate([
            'pair'        => 'required|string',
            'side'        => 'required|in:long,short',
            'margin'      => 'required|numeric|gt:0',
            'leverage'    => 'required|integer|min:1',
            'take_profit' => 'nullable|numeric|gt:0',
            'stop_loss'   => 'nullable|numeric|gt:0',
        ]);
        $pair = CoinPair::where('symbol', $data['pair'])->first();
        if (!$pair) return $this->fail('Pair not found');

        try {
            $position = FuturesEngine::open(auth()->user(), [
                'pair_id'     => $pair->id,
                'side'        => $data['side'],
                'margin'      => (float) $data['margin'],
                'leverage'    => (int) $data['leverage'],
                'take_profit' => $data['take_profit'] ?? null,
                'stop_loss'   => $data['stop_loss'] ?? null,
            ]);
        } catch (RuntimeException $e) {
            return $this->fail($e->getMessage());
        }

        return response()->json([
            'success'  => true,
            'message'  => ucfirst($position->side) . ' position opened at ' . showAmount($position->entry_price, currencyFormat: false),
            'balances' => $this->balances(auth()->id()),
        ]);
    }

    public function close($id)
    {
        try {
            $position = FuturesEngine::close((int) $id, 'manual', auth()->id());
        } catch (RuntimeException $e) {
            return $this->fail($e->getMessage());
        }
        if (!$position) return $this->fail('Position is already closed');

        $msg = $position->status === 'liquidated'
            ? 'Position was liquidated'
            : 'Position closed. PnL ' . ($position->realized_pnl >= 0 ? '+' : '') . showAmount($position->realized_pnl, currencyFormat: false) . ' USDT';

        return response()->json(['success' => true, 'message' => $msg, 'balances' => $this->balances(auth()->id())]);
    }

    public function tpsl(Request $request, $id)
    {
        $request->validate(['take_profit' => 'nullable|numeric|gt:0', 'stop_loss' => 'nullable|numeric|gt:0']);
        try {
            FuturesEngine::updateTpSl(auth()->user(), (int) $id, $request->take_profit, $request->stop_loss);
        } catch (RuntimeException $e) {
            return $this->fail($e->getMessage());
        }
        return response()->json(['success' => true, 'message' => 'TP/SL updated']);
    }

    public function transfer(Request $request)
    {
        $request->validate(['direction' => 'required|in:to_futures,to_spot', 'amount' => 'required|numeric|gt:0']);
        try {
            $balances = FuturesEngine::transfer(auth()->user(), $request->direction, (float) $request->amount);
        } catch (RuntimeException $e) {
            return $this->fail($e->getMessage());
        }
        return response()->json(['success' => true, 'message' => 'Transfer completed', 'balances' => $balances]);
    }

    /** Cron: settle liquidations / TP / SL for everyone. */
    public function cron()
    {
        $settled = FuturesEngine::sweep();
        return response()->json(['success' => true, 'settled' => $settled]);
    }

    /* ---------------------------------------------------------------- */

    private function balances(int $userId): array
    {
        $spot = FuturesEngine::spotWallet($userId);
        $futures = FuturesEngine::futuresWallet($userId);
        $inMargin = (float) FuturePosition::where('user_id', $userId)->open()->sum('margin');

        return ['spot' => (float) ($spot->balance ?? 0), 'futures' => (float) $futures->balance, 'inMargin' => $inMargin];
    }

    private function pairData(CoinPair $p): array
    {
        return [
            'symbol'       => $p->symbol,
            'listedMarket' => $p->listed_market_name ?: 'BINANCE',
            'coin'         => ['symbol' => $p->coin->symbol, 'name' => $p->coin->name, 'image' => $p->coin->image_url],
            'market'       => $p->market->currency->symbol ?? 'USDT',
            'price'        => (float) ($p->marketData->price ?? 0),
            'change24h'    => (float) ($p->marketData->percent_change_24h ?? 0),
            'maxLeverage'  => (int) $p->max_leverage,
            'feePercent'   => (float) $p->futures_fee_percent,
            'mmPercent'    => (float) $p->maintenance_margin_percent,
            'minMargin'    => (float) $p->futures_min_margin,
        ];
    }

    private function positionData(FuturePosition $p): array
    {
        return [
            'id'          => $p->id,
            'trx'         => $p->trx,
            'symbol'      => $p->pair->symbol ?? '',
            'coin'        => $p->pair->coin->symbol ?? '',
            'side'        => $p->side,
            'leverage'    => $p->leverage,
            'margin'      => $p->margin,
            'size'        => $p->size,
            'entry'       => $p->entry_price,
            'liquidation' => $p->liquidation_price,
            'feeRate'     => $p->fee_rate,
            'takeProfit'  => $p->take_profit,
            'stopLoss'    => $p->stop_loss,
            'openFee'     => $p->open_fee,
            'closeFee'    => $p->close_fee,
            'closePrice'  => $p->close_price,
            'pnl'         => $p->realized_pnl,
            'payout'      => $p->payout,
            'status'      => $p->status,
            'reason'      => $p->close_reason,
            'openedAt'    => InertiaData::date($p->created_at),
            'closedAt'    => InertiaData::date($p->closed_at),
        ];
    }

    private function fail(string $message)
    {
        return response()->json(['success' => false, 'message' => $message]);
    }
}
