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
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('stock_code');
            $table->string('slug');
            $table->text('short_description')->nullable();
            $table->longText('description');
            $table->string('price')->default('0.00');
            $table->string('fix_rate')->default('0.00');
            $table->string('unfix_rate')->default('0.00');
            $table->longText('image')->nullable();
            $table->longText('certificate_image')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
