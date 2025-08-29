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
        Schema::create('transaction_items', function (Blueprint $table) {
            $table->id('item_id');
            $table->foreignId('transaction_id')->constrained('transactions', 'transaction_id')->onDelete('cascade');
            $table->foreignId('product_id')->constrained('products', 'product_id');
            $table->foreignId('price_id')->constrained('pricing', 'price_id');

            // variant_id bisa NULL jika produk yang dibeli adalah JASA
            $table->foreignId('variant_id')->nullable()->constrained('product_variants', 'variant_id');

            $table->integer('quantity');
            $table->decimal('unit_price', 15, 2); // Harga satuan saat transaksi
            $table->decimal('subtotal', 15, 2);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transaction_items');
    }
};
