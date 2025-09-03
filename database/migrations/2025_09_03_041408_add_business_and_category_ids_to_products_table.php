<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table) {
            // 1. Menambahkan kolom business_id dan foreign key-nya
            $table->foreignId('business_id')
                ->after('product_id') // Atur posisi agar rapi
                ->constrained('business')
                ->onDelete('cascade');

            // 2. Menambahkan kolom category_id dan foreign key-nya
            //    yang mengarah ke tabel 'product_categories'
            $table->foreignId('category_id')
                ->after('business_id') // Atur posisi setelah business_id
                ->nullable()
                ->constrained('product_categories') // Mengarah ke tabel yang benar
                ->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            // Hapus dalam urutan yang aman (tidak ada dependensi antar kolom ini)
            $table->dropForeign(['business_id']);
            $table->dropForeign(['category_id']);
            $table->dropColumn(['business_id', 'category_id']);
        });
    }
};
