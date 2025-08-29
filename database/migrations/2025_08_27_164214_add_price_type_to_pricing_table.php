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
        Schema::table('pricing', function (Blueprint $table) {
            $table->enum('price_type', ['REGULAR', 'MEMBER'])->default('REGULAR')->after('price');
            // Tambahkan unique constraint agar tidak ada harga duplikat untuk item dan tipe yang sama
            $table->unique(['product_id', 'variant_id', 'unit_id', 'price_type'], 'unique_pricing_rule');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pricing', function (Blueprint $table) {
            //
        });
    }
};
