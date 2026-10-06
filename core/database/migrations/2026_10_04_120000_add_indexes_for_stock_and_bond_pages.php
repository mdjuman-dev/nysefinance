<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * The stock/bond pages filter these tables by user (and type/use_for) but the
 * tables only had primary keys, so stock_transactions (~6.6M rows) was fully
 * scanned on every page load. Plain ADD INDEX runs in place on MariaDB/MySQL
 * InnoDB without blocking reads or writes.
 */
return new class extends Migration
{
    private array $indexes = [
        'stock_transactions' => [
            'stock_tx_user_type_created_idx' => ['user_id', 'type', 'created_at'],
            'stock_tx_stock_type_idx'        => ['stock_id', 'type'],
        ],
        'user_stocks' => [
            'user_stocks_user_usefor_status_idx' => ['user_id', 'use_for', 'status'],
        ],
        'stock_exchanges' => [
            'stock_exchanges_user_idx' => ['user_id'],
        ],
        'products' => [
            'products_usefor_bondtype_idx' => ['use_for', 'bond_type'],
        ],
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
