<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Pastikan nama tabel di sini adalah 'product_variants'
        // Laravel akan otomatis menambahkan prefix 'tbl_' jika dikonfigurasi.
        Schema::create('product_variants', function (Blueprint $table) {
            // $table->id() membuat kolom BIGINT UNSIGNED PRIMARY KEY
            $table->id('variant_id');

            $table->foreignId('product_id')->constrained('products', 'product_id')->onDelete('cascade');
            $table->string('name');
            $table->string('sku')->nullable()->unique();
            $table->unsignedInteger('stock_quantity')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('product_variants');
    }
};
