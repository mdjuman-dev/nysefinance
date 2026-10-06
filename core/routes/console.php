<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote')->hourly();

// Settle futures positions that hit liquidation / take-profit / stop-loss.
// Run every minute (or hit GET /futures/cron from the hosting cron like the other jobs).
Artisan::command('futures:sweep', function () {
    $settled = \App\Services\Futures\FuturesEngine::sweep();
    $this->info("Settled {$settled} futures position(s).");
})->purpose('Settle futures liquidations and TP/SL')->everyMinute();

// PvPay fallback: credit paid deposits whose webhook was late or lost, and close
// expired ones. Run every 5 minutes (hosting cron: php artisan pvpay:sync).
Artisan::command('pvpay:sync', function () {
    $open = \App\Models\Deposit::where('method_code', 130)->where('status', \App\Constants\Status::PAYMENT_INITIATE)
        ->where('btc_wallet', '!=', '')->where('created_at', '>=', now()->subDay())->limit(100)->get();
    foreach ($open as $deposit) {
        $status = \App\Http\Controllers\Gateway\PvPay\ProcessController::sync($deposit);
        $this->line("{$deposit->trx}: " . ($status ?? 'unreachable'));
    }
    $this->info("Checked {$open->count()} PvPay deposit(s).");
})->purpose('Sync open PvPay deposits with the PvPay status API')->everyFiveMinutes();
