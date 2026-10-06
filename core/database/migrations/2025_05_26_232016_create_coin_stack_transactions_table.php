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
        Schema::create('coin_stack_transactions', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('user_id');
            $table->integer('stack_id');
            $table->integer('pool_id');
            $table->string('amount');
            $table->enum('type',['sell', 'buy','interest'])->default('buy');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('coin_stack_transactions');
    }
};
