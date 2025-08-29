<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->enum('booking_mechanism', [
                'TIME_SLOT',
                'TIME_SLOT_CAPACITY',
                'CONSUMABLE_STOCK',
                'INVENTORY_STOCK'
            ])->after('product_type')->nullable()->comment('Defines how booking and availability are handled.');
        });
    }

    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn('booking_mechanism');
        });
    }
};
