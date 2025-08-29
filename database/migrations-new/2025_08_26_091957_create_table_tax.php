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
        Schema::create('tax', function (Blueprint $table) {
            $table->id();

            $table->string('name', 50);
            $table->integer('amount');

            $table->timestamps();
        });

        Schema::create('tax__outlet', function (Blueprint $table) {
            $table->id();

            // # Relation to table Tax 
            $table->unsignedBigInteger('tax_id')->nullable(false);
            $table->foreign('tax_id')->references('id')->on('tax')
                ->onDelete('cascade')
                ->onUpdate('no action');

            // # Relation to table Outlet
            $table->unsignedBigInteger('outlet_id')->nullable(false);
            $table->foreign('outlet_id')->references('id')->on('outlet')
                ->onDelete('cascade')
                ->onUpdate('no action');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tax');
    }
};
