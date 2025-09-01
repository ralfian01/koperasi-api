<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // // Tabel utama untuk sesi keranjang
        // Schema::create('carts', function (Blueprint $table) {
        //     $table->id();
        //     $table->foreignId('outlet_id')->constrained('outlets');
        //     $table->foreignId('employee_id')->constrained('employees');
        //     $table->foreignId('customer_id')->nullable()->constrained('customers');
        //     $table->foreignId('member_id')->nullable()->constrained('members');
        //     $table->enum('status', ['ACTIVE', 'COMPLETED', 'CANCELLED'])->default('ACTIVE');

        //     // Kolom finansial yang terus di-update
        //     $table->decimal('subtotal', 15, 2)->default(0);
        //     $table->decimal('total_discount', 15, 2)->default(0);
        //     $table->decimal('grand_total', 15, 2)->default(0);

        //     $table->timestamps();
        // });

        // // Tabel untuk item di dalam keranjang
        // Schema::create('cart_items', function (Blueprint $table) {
        //     $table->id();
        //     $table->foreignId('cart_id')->constrained('carts')->onDelete('cascade');

        //     // Informasi item yang "di-quote"
        //     $table->string('type'); // 'BOOKING_TIMESLOT', 'GOODS', dll.
        //     $table->string('name'); // Nama deskriptif dari hasil kalkulasi
        //     $table->unsignedInteger('quantity');
        //     $table->decimal('unit_price', 15, 2);
        //     $table->decimal('subtotal', 15, 2);

        //     // ID Jejak untuk eksekusi
        //     $table->unsignedBigInteger('product_id');
        //     $table->unsignedBigInteger('resource_id')->nullable();
        //     $table->unsignedBigInteger('variant_id')->nullable();
        //     $table->unsignedBigInteger('stock_id')->nullable(); // service_stock_id

        //     // Informasi booking spesifik
        //     $table->timestamp('start_datetime')->nullable();
        //     $table->timestamp('end_datetime')->nullable();

        //     $table->timestamps();
        // });
    }

    public function down(): void
    {
        // Schema::dropIfExists('cart_items');
        // Schema::dropIfExists('carts');
    }
};
