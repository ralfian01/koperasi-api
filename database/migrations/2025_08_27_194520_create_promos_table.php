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
        Schema::create('promos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('business_id')->constrained('business')->onDelete('cascade');
            $table->string('name');
            $table->text('description')->nullable();

            // --- PERUBAHAN DI SINI ---
            $table->enum('promo_type', ['DISCOUNT', 'FREE_ITEM', 'DISCOUNT_AND_FREE_ITEM']);
            // ------------------------

            $table->date('start_date');
            $table->date('end_date');
            $table->boolean('is_active')->default(true);
            $table->boolean('is_cumulative')->default(false);

            // --- Kolom Diskon tetap di sini ---
            $table->enum('discount_type', ['PERCENTAGE', 'FIXED'])->nullable();
            $table->decimal('discount_value', 15, 2)->nullable();
            // ---------------------------------

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('promos');
    }
};
