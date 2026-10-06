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
        Schema::create('copy_transactions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->integer('trade_id');
            $table->integer('user_trade_id');
            $table->string('amount');
            $table->enum('type',['buy','sell','interest'])->default('buy');
            $table->string('reason')->nullable();
            $table->text('transaction_id');
            $table->string('remarks')->nullable();
            $table->string('interest')->nullable();
            $table->enum('trade_type',['classic','token_splash','gold_fx','by_votes','puzzle_hunt'])->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('copy_transactions');
    }
};
