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
        Schema::create('outlet_promo', function (Blueprint $table) {
            // Menggunakan composite primary key adalah praktik terbaik untuk tabel pivot
            // untuk memastikan setiap pasangan outlet-promo unik.
            $table->primary(['outlet_id', 'promo_id']);

            // Foreign key ke tabel 'outlets'
            $table->foreignId('outlet_id')->constrained('outlets')->onDelete('cascade');

            // Foreign key ke tabel 'promos'
            $table->foreignId('promo_id')->constrained('promos')->onDelete('cascade');

            // Timestamps bisa berguna untuk melacak kapan sebuah promo di-assign ke outlet
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::dropIfExists('outlet_promo');
    }
};
