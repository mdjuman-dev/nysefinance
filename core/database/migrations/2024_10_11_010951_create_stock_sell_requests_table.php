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
        Schema::create('stock_sell_requests', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id');
            $table->integer('user_stock_id');
            $table->integer('product_id');
            $table->enum('type',['fix','unfix'])->nullable();
            $table->enum('status',['pending','approved','rejected'])->default('pending');
            $table->string('charge')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stock_sell_requests');
    }
};
