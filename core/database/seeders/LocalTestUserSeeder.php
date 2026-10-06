<?php

namespace Database\Seeders;

use App\Constants\Status;
use App\Models\CoinPair;
use App\Models\Currency;
use App\Models\Order;
use App\Models\Transaction;
use App\Models\User;
use App\Models\Wallet;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

/**
 * LOCAL DEVELOPMENT ONLY — creates a verified demo account for previewing the
 * user dashboard. Never run against production.
 *
 *   php artisan db:seed --class=LocalTestUserSeeder
 *
 * Login: localtester / local-9fb34f015c7a
 */
class LocalTestUserSeeder extends Seeder
{
    public function run(): void
    {
        if (app()->environment('production') || config('database.connections.mysql.database') !== 'nysefinance') {
            $this->command->error('Refusing to run: only for the local "nysefinance" database.');
            return;
        }

        $user = User::firstOrNew(['username' => 'localtester']);
        $user->firstname = 'Local';
        $user->lastname = 'Tester';
        $user->email = 'localtester@example.test';
        $user->password = Hash::make('local-9fb34f015c7a');
        $user->uid = $user->uid ?: 'LT' . random_int(100000, 999999);
        $user->status = Status::USER_ACTIVE;
        $user->ev = Status::VERIFIED;
        $user->sv = Status::VERIFIED;
        $user->ts = Status::DISABLE;
        $user->tv = Status::VERIFIED;
        $user->kv = Status::KYC_UNVERIFIED;
        $user->profile_complete = Status::YES;
        $user->save();

        // A few demo balances so the portfolio widgets have something to show.
        $demo = ['USDT' => 2450.75, 'BTC' => 0.0425, 'ETH' => 0.85, 'SOL' => 6.4];
        foreach ($demo as $symbol => $balance) {
            $currency = Currency::where('symbol', $symbol)->first();
            if (!$currency) continue;
            Wallet::updateOrCreate(
                ['user_id' => $user->id, 'currency_id' => $currency->id, 'wallet_type' => Status::WALLET_TYPE_SPOT],
                ['balance' => $balance]
            );
        }

        // Demo activity so the orders / transactions lists render.
        if (!Transaction::where('user_id', $user->id)->exists()) {
            $usdt = Wallet::where('user_id', $user->id)->spot()->whereHas('currency', fn ($q) => $q->where('symbol', 'USDT'))->first();
            foreach ([['+', 3000, 'Deposit via TRC20'], ['-', 549.25, 'Bought 0.0425 BTC'], ['+', 0, 'Daily interest']] as $i => [$type, $amount, $details]) {
                $trx = new Transaction();
                $trx->user_id = $user->id;
                $trx->wallet_id = $usdt?->id ?? 0;
                $trx->amount = $amount;
                $trx->charge = 0;
                $trx->post_balance = 2450.75;
                $trx->trx_type = $type;
                $trx->trx = getTrx();
                $trx->details = $details;
                $trx->remark = 'demo';
                $trx->created_at = now()->subHours(($i + 1) * 5);
                $trx->save();
            }
        }

        $pair = CoinPair::where('symbol', 'BTC_USDT')->first();
        if ($pair && !Order::where('user_id', $user->id)->exists()) {
            foreach ([[Status::BUY_SIDE_ORDER, Status::ORDER_COMPLETED, 100], [Status::SELL_SIDE_ORDER, Status::ORDER_OPEN, 35]] as [$side, $status, $filled]) {
                $order = new Order();
                $order->user_id = $user->id;
                $order->pair_id = $pair->id;
                $order->coin_id = $pair->coin_id;
                $order->market_currency_id = $pair->market->currency_id ?? 0;
                $order->trx = getTrx();
                $order->order_side = $side;
                $order->order_type = Status::ORDER_TYPE_LIMIT;
                $order->rate = 84800;
                $order->price = 84800;
                $order->amount = 0.0215;
                $order->total = 1823.2;
                $order->filled_amount = 0.0215 * $filled / 100;
                $order->filed_percentage = $filled;
                $order->charge = 0;
                $order->status = $status;
                $order->save();
            }
        }

        $this->command->info("Local test user ready: localtester (id {$user->id})");
    }
}
