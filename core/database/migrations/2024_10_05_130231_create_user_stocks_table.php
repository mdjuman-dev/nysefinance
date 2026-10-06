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
        Schema::create('user_stocks', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('user_id');
            $table->integer('product_id');
            $table->longText('certificate_id');
            $table->enum('type',['fix','unfix'])->default('fix');
            $table->string('product_price')->default('0.00');
            $table->enum('invest_percent',['25','50','100'])->nullable();
            $table->decimal('invest_amount', 8,2);
            $table->enum('status',['buy','sell'])->default('buy');
            $table->date('interest_date')->nullable();
            $table->date('invest_date')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_stocks');
    }
};
