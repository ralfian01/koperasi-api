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
        Schema::create('promo_conditions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('promo_id')->constrained('promos')->onDelete('cascade');
            $table->enum('condition_type', ['PRODUCT', 'CATEGORY']);
            $table->unsignedBigInteger('target_id'); // Merujuk ke products.product_id atau categories.id
            $table->unsignedInteger('min_quantity');
            $table->timestamps();

            // Index untuk mempercepat query pencarian syarat
            $table->index(['condition_type', 'target_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('promo_conditions');
    }
};
