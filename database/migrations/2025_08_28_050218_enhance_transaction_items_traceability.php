<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('transaction_items', function (Blueprint $table) {
            // Kolom ini akan diisi jika item adalah layanan berbasis stok (Golf)
            $table->foreignId('service_stock_id')
                ->nullable()
                ->after('booking_id')
                ->constrained('service_stock', 'stock_id')
                ->onDelete('set null');

            // Kolom ini bisa diisi untuk semua layanan sewa untuk kejelasan
            $table->foreignId('resource_id')
                ->nullable()
                ->after('service_stock_id')
                ->constrained('resources', 'resource_id')
                ->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::table('transaction_items', function (Blueprint $table) {
            $table->dropForeign(['service_stock_id']);
            $table->dropForeign(['resource_id']);
            $table->dropColumn(['service_stock_id', 'resource_id']);
        });
    }
};
