<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * Admin dashboard sums interest across millions of stock_transactions rows and
 * P2P volume from transactions by remark. Covering indexes let MySQL answer
 * "today's interest" with a short range scan and the totals from the index only.
 */
return new class extends Migration
{
    private array $indexes = [
        'stock_transactions' => ['stock_tx_type_created_amount_idx' => ['type', 'created_at', 'amount']],
        'transactions'       => ['trx_remark_amount_idx' => ['remark', 'amount']],
    ];

    public function up(): void
    {
        foreach ($this->indexes as $table => $list) {
            foreach ($list as $name => $cols) {
                if (!$this->exists($table, $name)) {
                    Schema::table($table, fn ($t) => $t->index($cols, $name));
                }
            }
        }
    }

    public function down(): void
    {
        foreach ($this->indexes as $table => $list) {
            foreach (array_keys($list) as $name) {
                if ($this->exists($table, $name)) {
                    Schema::table($table, fn ($t) => $t->dropIndex($name));
                }
            }
        }
    }

    private function exists(string $table, string $name): bool
    {
        return DB::table('information_schema.statistics')->where('table_schema', DB::getDatabaseName())
            ->where('table_name', $table)->where('index_name', $name)->exists();
    }
};
