<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

/**
 * PvPay (Binance Pay hosted checkout) as an automatic deposit gateway, code 130.
 * It starts disabled: add the API keys in Admin → Payment Gateways → Automatic → PvPay,
 * then enable it. Deposits are credited by the signed webhook (ipn/pvpay).
 */
return new class extends Migration
{
    public function up(): void
    {
        if (DB::table('gateways')->where('code', 130)->exists()) {
            return;
        }

        $now = now();
        DB::table('gateways')->insert([
            'form_id'              => 0,
            'code'                 => 130,
            'name'                 => 'PvPay',
            'alias'                => 'PvPay', // must match the Gateway\PvPay controller folder
            'status'               => 0,
            'crypto'               => 1,
            'gateway_parameters'   => json_encode([
                'public_key' => ['title' => 'Public Key (pk_live_…)', 'global' => true, 'value' => ''],
                'secret_key' => ['title' => 'Secret Key (sk_live_…)', 'global' => true, 'value' => ''],
            ]),
            'supported_currencies' => json_encode(['USDT' => 'USDT']),
            'extra'                => json_encode(['webhook' => ['title' => 'Webhook URL', 'value' => 'ipn.PvPay']]),
            'description'          => 'Pay with Binance Pay through the PvPay hosted checkout. Funds are credited automatically once the payment is confirmed.',
            'created_at'           => $now,
            'updated_at'           => $now,
        ]);

        DB::table('gateway_currencies')->insert([
            'name'              => 'Binance Pay (PvPay)',
            'currency'          => 'USDT',
            'symbol'            => 'USDT',
            'method_code'       => 130,
            'gateway_alias'     => 'PvPay',
            'min_amount'        => 1,
            'max_amount'        => 100000,
            'percent_charge'    => 0,
            'fixed_charge'      => 0,
            'rate'              => 1,
            'gateway_parameter' => json_encode(['public_key' => '', 'secret_key' => '']),
            'created_at'        => $now,
            'updated_at'        => $now,
        ]);
    }

    public function down(): void
    {
        DB::table('gateway_currencies')->where('method_code', 130)->delete();
        DB::table('gateways')->where('code', 130)->delete();
    }
};
