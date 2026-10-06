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
        Schema::create('coin_stacks', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('user_id');
            $table->string('pool_id');
            $table->string('price');
            $table->string('invest_amount');
            $table->string('interest');
            $table->timestamp('payment_date')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('coin_stacks');
    }
};
