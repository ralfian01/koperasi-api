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
        Schema::create('payment_method', function (Blueprint $table) {
            $table->id();

            $table->enum('method', ['CASH', 'QRIS', 'EDC', 'TF']);
            $table->string('name');

            $table->timestamps();
        });

        Schema::create('payment_method__outlet', function (Blueprint $table) {
            $table->id();

            // # Relation to table Payment 
            $table->unsignedBigInteger('payment_method_id')->nullable(false);
            $table->foreign('payment_method_id')->references('id')->on('payment_method')
                ->onDelete('cascade')
                ->onUpdate('no action');

            // # Relation to table Outlet
            $table->unsignedBigInteger('outlet_id')->nullable(false);
            $table->foreign('outlet_id')->references('id')->on('outlet')
                ->onDelete('cascade')
                ->onUpdate('no action');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payment_method');
    }
};
