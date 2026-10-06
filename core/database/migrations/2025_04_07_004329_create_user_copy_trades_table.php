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
        Schema::create('user_copy_trades', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('user_id');
            $table->integer('trade_id');
            $table->string('price');
            $table->string('interest');
            $table->string('interest_type')->default('daily');
            $table->timestamp('expired_at')->nullable();
            $table->timestamp('interest_date');
            $table->timestamp('next_interest_date');
            $table->enum('status',['buy','sell'])->default('buy');
            $table->enum('trade_type',['classic','token_splash','gold_fx','by_votes','puzzle_hunt'])->nullable();
            $table->enum('profit_type',['loss','profit'])->default('profit');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_copy_trades');
    }
};
