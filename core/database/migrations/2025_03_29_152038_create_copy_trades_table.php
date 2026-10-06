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
        Schema::create('copy_trades', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug');
            $table->enum('trade_type',['classic','token_splash','gold_fx','by_votes','puzzle_hunt','spot_x'])->nullable();
            $table->string('type');
            $table->timestamp('start_date');
            $table->timestamp('end_date');
            $table->string('amount')->default('0.0000');
            $table->string('interest')->default('0');
            $table->enum('profit_type',['loss','profit'])->default('profit');
            $table->enum('status',['active','inactive'])->default('active');
            $table->enum('stared',['yes','no'])->default('no');
            $table->text('short_details')->nullable();
            $table->longText('details')->nullable();
            $table->longText('image')->nullable();
            $table->string('count_day_one')->default('7');
            $table->string('count_profit_one')->default('100');
            $table->string('count_day_two')->default('14');
            $table->string('count_profit_two')->default('14');
            $table->string('dw_day')->default('30');
            $table->string('dw_profit')->default('30');
            $table->string('total_prize')->default('0');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('copy_trades');
    }
};
