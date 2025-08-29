<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('service_stock', function (Blueprint $table) {
            $table->id('stock_id');
            $table->foreignId('product_id')->constrained('products', 'product_id')->onDelete('cascade');
            $table->foreignId('unit_id')->constrained('units', 'unit_id')->onDelete('cascade');
            $table->string('name')->comment('e.g., Weekday 9 Holes, Weekend 18 Holes');
            $table->unsignedInteger('available_quantity')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('service_stock');
    }
};
