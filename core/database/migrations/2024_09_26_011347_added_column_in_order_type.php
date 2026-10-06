<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('wallets', function (Blueprint $table) {
            $table->tinyInteger('wallet_type')->unsigned()->comment('1=SPOT, 2=FUNDING, 3=FUTURE')->change();
        });

        Schema::table('orders', function (Blueprint $table) {
            $table->enum('type',['spot','future'])->default('spot');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('wallets', function (Blueprint $table) {
            $table->tinyInteger('wallet_type')->unsigned()->comment('1=SPOT, 2=FUNDING')->change();
        });

        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn('type');
        });
    }
};
