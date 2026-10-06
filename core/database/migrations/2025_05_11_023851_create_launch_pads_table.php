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
        Schema::create('launch_pads', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->decimal('price', 18, 8); // e.g., 0.0312
            $table->string('price_currency', 10); // e.g., MNT
            $table->bigInteger('total_allocation'); // e.g., 3,750,000
            $table->bigInteger('cap_per_subscriber'); // e.g., 7,500
            $table->bigInteger('total_committed_amount'); // e.g., 79,309,468
            $table->string('status')->default('active'); // e.g., active, upcoming, closed
            $table->longText('image')->nullable();
            $table->longText('details_url')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('launch_pads');
    }
};
