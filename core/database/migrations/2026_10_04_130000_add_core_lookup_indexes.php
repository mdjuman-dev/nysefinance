<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * Core tables only had primary keys, so every wallet/order/transaction lookup
 * by user scanned the whole table (wallets alone is ~650k rows). Plain
 * ADD INDEX runs in place on InnoDB without blocking reads or writes.
 */
return new class extends Migration
{
    private array $indexes = [
        'wallets'      => ['wallets_user_currency_type_idx' => ['user_id', 'currency_id', 'wallet_type']],
        'transactions' => ['transactions_user_created_idx' => ['user_id', 'created_at']],
        'orders'       => ['orders_user_status_idx' => ['user_id', 'status'], 'orders_pair_status_idx' => ['pair_id', 'status']],
        'trades'       => ['trades_trader_idx' => ['trader_id'], 'trades_pair_idx' => ['pair_id']],
        'market_data'  => ['market_data_pair_idx' => ['pair_id'], 'market_data_currency_idx' => ['currency_id']],
    ];

    public function up(): void
    {
        foreach ($this->indexes as $table => $indexes) {
            foreach ($indexes as $name => $columns) {
                if (!$this->exists($table, $name)) {
                    Schema::table($table, fn ($t) => $t->index($columns, $name));
                }
            }
        }
    }

    public function down(): void
    {
        foreach ($this->indexes as $table => $indexes) {
            foreach (array_keys($indexes) as $name) {
                if ($this->exists($table, $name)) {
                    Schema::table($table, fn ($t) => $t->dropIndex($name));
                }
            }
        }
    }

    private function exists(string $table, string $name): bool
    {
        return DB::table('information_schema.statistics')
            ->where('table_schema', DB::getDatabaseName())
            ->where('table_name', $table)
            ->where('index_name', $name)
            ->exists();
    }
};
