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
        Schema::create('stock_transactions', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id');
            $table->enum('type',['sell','buy','interest'])->nullable();
            $table->enum('stock_type',['fix','unfix'])->nullable();
            $table->decimal('amount',8,2);
            $table->decimal('charge', 8, 2)->nullable();
            $table->integer('stock_id')->nullable();
            $table->text('remark')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stock_transactions');
    }
};
