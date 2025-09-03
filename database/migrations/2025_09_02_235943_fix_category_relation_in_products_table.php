<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table) {
            // 1. Hapus foreign key yang lama
            $table->dropForeign(['category_id']);

            // 2. Arahkan foreign key ke tabel baru 'product_categories'
            $table->foreign('category_id')
                ->references('id')
                ->on('product_categories')
                ->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            // Lakukan kebalikannya
            $table->dropForeign(['category_id']);
            $table->foreign('category_id')
                ->references('id')
                ->on('categories') // Arahkan kembali ke tabel 'categories' yang lama
                ->onDelete('set null');
        });
    }
};
