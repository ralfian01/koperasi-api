<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pricing', function (Blueprint $table) {
            $table->id('price_id');
            $table->foreignId('product_id')->constrained('products', 'product_id')->onDelete('cascade');
            $table->foreignId('unit_id')->constrained('units', 'unit_id');

            // KUNCI UTAMA ADA DI SINI:
            // Pastikan Anda mereferensikan ke tabel 'product_variants' dan kolom 'variant_id'
            // `foreignId()` membuat kolom BIGINT UNSIGNED, yang cocok dengan `$table->id()`
            $table->foreignId('variant_id')
                ->nullable() // Kolom bisa kosong
                ->constrained('product_variants', 'variant_id') // Nama tabel & kolom tujuan
                ->onDelete('cascade');

            $table->string('name')->nullable();
            $table->decimal('price', 15, 2);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pricing');
    }
};
