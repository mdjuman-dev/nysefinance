<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * USDT-margined, isolated-margin perpetual-style futures.
 * Margin lives in a separate futures wallet (wallets.wallet_type = 3).
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('coin_pairs', function (Blueprint $table) {
            if (!Schema::hasColumn('coin_pairs', 'futures_enabled')) {
                $table->boolean('futures_enabled')->default(false);
                $table->unsignedSmallInteger('max_leverage')->default(20);
                $table->decimal('futures_fee_percent', 8, 4)->default(0.05);        // per side, on notional
                $table->decimal('maintenance_margin_percent', 8, 4)->default(0.5);  // of notional
                $table->decimal('futures_min_margin', 28, 8)->default(1);
            }
        });

        if (!Schema::hasTable('future_positions')) {
            Schema::create('future_positions', function (Blueprint $table) {
                $table->id();
                $table->unsignedInteger('user_id');
                $table->unsignedInteger('pair_id');
                $table->string('trx', 40);
                $table->enum('side', ['long', 'short']);
                $table->unsignedSmallInteger('leverage');
                $table->decimal('margin', 28, 8);            // isolated margin locked
                $table->decimal('size', 28, 8);              // contract size in coin
                $table->decimal('entry_price', 28, 8);
                $table->decimal('liquidation_price', 28, 8);
                $table->decimal('maintenance_rate', 8, 4);   // snapshot at open
                $table->decimal('fee_rate', 8, 4);           // snapshot at open
                $table->decimal('take_profit', 28, 8)->nullable();
                $table->decimal('stop_loss', 28, 8)->nullable();
                $table->decimal('open_fee', 28, 8)->default(0);
                $table->decimal('close_fee', 28, 8)->default(0);
                $table->decimal('close_price', 28, 8)->nullable();
                $table->decimal('realized_pnl', 28, 8)->nullable();  // price PnL, before fees
                $table->decimal('payout', 28, 8)->nullable();         // credited back to the wallet
                $table->enum('status', ['open', 'closed', 'liquidated'])->default('open');
                $table->enum('close_reason', ['manual', 'take_profit', 'stop_loss', 'liquidation'])->nullable();
                $table->timestamp('closed_at')->nullable();
                $table->timestamps();

                $table->index(['user_id', 'status']);
                $table->index(['status', 'pair_id']);
                $table->unique('trx');
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('future_positions');
        Schema::table('coin_pairs', function (Blueprint $table) {
            foreach (['futures_enabled', 'max_leverage', 'futures_fee_percent', 'maintenance_margin_percent', 'futures_min_margin'] as $col) {
                if (Schema::hasColumn('coin_pairs', $col)) $table->dropColumn($col);
            }
        });
    }
};
