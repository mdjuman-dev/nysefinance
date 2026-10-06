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
        Schema::create('stock_wallets', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('user_id');
            $table->enum('status', ['active','frozen'])->default('active');
            $table->decimal('amount',8,2)->default('00.00000000');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stock_wallets');
    }
};
