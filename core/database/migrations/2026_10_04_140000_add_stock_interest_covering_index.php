<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * Wallet pages sum a user's interest per wallet type. Long-time holders have
 * tens of thousands of daily-interest rows; covering (user_id, type, use_for, amount)
 * lets MySQL answer the SUM from the index alone instead of reading every row.
 */
return new class extends Migration
{
    private string $name = 'stock_tx_user_type_usefor_amount_idx';

    public function up(): void
    {
        if (!$this->exists()) {
            Schema::table('stock_transactions', fn ($t) => $t->index(['user_id', 'type', 'use_for', 'amount'], $this->name));
        }
    }

    public function down(): void
    {
        if ($this->exists()) {
            Schema::table('stock_transactions', fn ($t) => $t->dropIndex($this->name));
        }
    }

    private function exists(): bool
    {
        return DB::table('information_schema.statistics')->where('table_schema', DB::getDatabaseName())
            ->where('table_name', 'stock_transactions')->where('index_name', $this->name)->exists();
    }
};
