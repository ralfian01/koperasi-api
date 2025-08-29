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
        Schema::create('item', function (Blueprint $table) {
            $table->id();

            // # Relation to table Bisnis
            $table->unsignedBigInteger('business_id')->nullable(false);
            $table->foreign('business_id')->references('id')->on('business')
                ->onDelete('cascade')
                ->onUpdate('no action');

            // # Relation to table Category
            $table->unsignedBigInteger('category_id')->nullable(true);
            $table->foreign('category_id')->references('id')->on('category')
                ->onDelete('cascade')
                ->onUpdate('no action');

            $table->string('image')->nullable();
            $table->string('name', 100)->nullable();
            $table->string('description')->nullable();
            $table->boolean('status_active')->default(true);

            $table->timestamps();
        });

        Schema::create('item__outlet', function (Blueprint $table) {
            $table->id();

            // # Relation to table Outlet
            $table->unsignedBigInteger('outlet_id')->nullable(false);
            $table->foreign('outlet_id')->references('id')->on('outlet')
                ->onDelete('no action')
                ->onUpdate('no action');

            // # Relation to table Item
            $table->unsignedBigInteger('item_id')->nullable(true);
            $table->foreign('item_id')->references('id')->on('item')
                ->onDelete('no action')
                ->onUpdate('no action');
        });

        Schema::create('item__variant', function (Blueprint $table) {
            $table->id();

            // # Relation to table Item
            $table->unsignedBigInteger('item_id')->nullable(true);
            $table->foreign('item_id')->references('id')->on('item')
                ->onDelete('cascade')
                ->onUpdate('no action');

            $table->string('sku', 50)->nullable();
            $table->string('name', 100)->nullable();
            $table->int('price')->nullable();
            $table->int('member_price')->nullable();

            $table->timestamps();
        });

        Schema::create('item__stock', function (Blueprint $table) {
            $table->id();

            // # Relation to table Item Variant
            $table->unsignedBigInteger('item_variant_id')->nullable(true);
            $table->foreign('item_variant_id')->references('id')->on('item__variant')
                ->onDelete('cascade')
                ->onUpdate('no action');

            // # Relation to table Outlet
            $table->unsignedBigInteger('outlet_id')->nullable(true);
            $table->foreign('outlet_id')->references('id')->on('outlet')
                ->onDelete('cascade')
                ->onUpdate('no action');

            $table->int('stock')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('item');
    }
};
