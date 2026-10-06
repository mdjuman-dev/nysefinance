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
        Schema::create('stock_exchanges', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id');
            $table->integer('user_stock_id');
            $table->integer('product_id');
            $table->string('broker');
            $table->enum('status',['approved','rejected','pending'])->default('pending');
            $table->string('charge')->nullable();
            $table->string('stock_type')->nullable();
            $table->text('reason')->nullable();
            $table->text('others')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stock_exchanges');
    }
};
