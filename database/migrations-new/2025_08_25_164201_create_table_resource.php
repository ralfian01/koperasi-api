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
        Schema::create('resource', function (Blueprint $table) {
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
            $table->int('price')->nullable();
            $table->int('member_price')->nullable();
            $table->boolean('status_active')->default(true);

            $table->timestamps();
        });

        Schema::create('resource__outlet', function (Blueprint $table) {
            $table->id();

            // # Relation to table Outlet
            $table->unsignedBigInteger('outlet_id')->nullable(false);
            $table->foreign('outlet_id')->references('id')->on('outlet')
                ->onDelete('no action')
                ->onUpdate('no action');

            // # Relation to table Resource
            $table->unsignedBigInteger('resource_id')->nullable(true);
            $table->foreign('resource_id')->references('id')->on('resource')
                ->onDelete('no action')
                ->onUpdate('no action');
        });

        Schema::create('resource__slot', function (Blueprint $table) {
            $table->id();

            // # Relation to table Resource
            $table->unsignedBigInteger('resource_id')->nullable(false);
            $table->foreign('resource_id')->references('id')->on('resource')
                ->onDelete('cascade')
                ->onUpdate('no action');

            $table->enum('unit_type', ['TIME', 'PACKAGE', 'CONSUMABLE']);

            $table->string('slot_day')->nullable();
            $table->time('slot_start')->nullable();
            $table->time('slot_end')->nullable();

            $table->timestamps();
        });

        Schema::create('resource__book', function (Blueprint $table) {
            $table->id();

            // # Relation to table Resource
            $table->unsignedBigInteger('resource_id')->nullable(false);
            $table->foreign('resource_id')->references('id')->on('resource')
                ->onDelete('cascade')
                ->onUpdate('no action');

            $table->date('book_date')->nullable();
            $table->time('book_start')->nullable();
            $table->time('book_end')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('resource');
        Schema::dropIfExists('resource__outlet');
        Schema::dropIfExists('resource__slot');
        // Schema::dropIfExists('resource__book');
    }
};
