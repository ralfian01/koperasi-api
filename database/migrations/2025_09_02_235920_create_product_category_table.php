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
        // Ubah nama tabel menjadi 'product_categories'
        Schema::create('product_categories', function (Blueprint $table) {
            $table->id();
            // Tambahkan foreign key yang diperlukan
            $table->foreignId('business_id')->constrained('business')->onDelete('cascade');
            $table->foreignId('outlet_id')->constrained('outlets')->onDelete('cascade');

            $table->string('name', 100);
            $table->timestamps();

            // Kategori harus unik per outlet
            $table->unique(['outlet_id', 'name']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::dropIfExists('product_categories');
    }
};
