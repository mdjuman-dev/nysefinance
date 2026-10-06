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

        Schema::create('manual_stocks', function (Blueprint $table) {
            $table->id();
            $table->string('cusip_id');
            $table->string('name');
            $table->string('amount');
            $table->string('holder');
            $table->string('buy_date');
            $table->string('sell_date')->nullable();
            $table->string('transfer_date')->nullable();
            $table->string('status');
            $table->string('broker');
            $table->string('buyer_email')->nullable();
            $table->string('buyer_number')->nullable();
            $table->longText('description')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('manual_stocks');
    }
};
