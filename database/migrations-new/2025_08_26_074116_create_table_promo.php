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
        Schema::create('promo', function (Blueprint $table) {
            $table->id();

            $table->string('name', 100)->nullable();
            $table->enum('type', ['DISCOUNT', 'FREEBIES']);
            $table->integer('reward');
            $table->enum('reward_unit', ['PERCENT', 'FIX']);
            $table->boolean('multiple')->default(false);
            $table->date('start_date');
            $table->date('end_date');
            $table->time('start_time');
            $table->time('end_time');
            $table->json('day');

            $table->timestamps();
        });

        Schema::create('promo__outlet', function (Blueprint $table) {
            $table->id();

            // # Relation to table Outlet
            $table->unsignedBigInteger('outlet_id')->nullable(false);
            $table->foreign('outlet_id')->references('id')->on('outlet')
                ->onDelete('no action')
                ->onUpdate('no action');

            // # Relation to table Promo
            $table->unsignedBigInteger('promo_id')->nullable(true);
            $table->foreign('promo_id')->references('id')->on('promo')
                ->onDelete('no action')
                ->onUpdate('no action');
        });

        Schema::create('promo__target', function (Blueprint $table) {
            $table->id();

            // # Relation to table Promo
            $table->unsignedBigInteger('promo_id')->nullable(true);
            $table->foreign('promo_id')->references('id')->on('promo')
                ->onDelete('no action')
                ->onUpdate('no action');

            $table->integer('quantity')->nullable(false);

            // # Relation to table Category
            $table->unsignedBigInteger('category_id')->nullable(false);
            $table->foreign('category_id')->references('id')->on('category')
                ->onDelete('no action')
                ->onUpdate('no action');

            // # Relation to table Item
            $table->unsignedBigInteger('item_id')->nullable(false);
            $table->foreign('item_id')->references('id')->on('item')
                ->onDelete('no action')
                ->onUpdate('no action');

            // # Relation to table Resource
            $table->unsignedBigInteger('resource_id')->nullable(true);
            $table->foreign('resource_id')->references('id')->on('resource')
                ->onDelete('no action')
                ->onUpdate('no action');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('promo');
        Schema::dropIfExists('promo__outlet');
        Schema::dropIfExists('promo__target');
    }
};
