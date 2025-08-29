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
        Schema::create('transaction', function (Blueprint $table) {
            $table->id();

            // # Relation to table Outlet
            $table->unsignedBigInteger('outlet_id')->nullable(false);
            $table->foreign('outlet_id')->references('id')->on('outlet')
                ->onDelete('no action')
                ->onUpdate('no action');

            // # Relation to table Customer
            $table->unsignedBigInteger('customer_id')->nullable(true);
            $table->foreign('customer_id')->references('id')->on('customer')
                ->onDelete('no action')
                ->onUpdate('no action');

            // # Relation to table Member
            $table->unsignedBigInteger('member_id')->nullable(true);
            $table->foreign('member_id')->references('id')->on('member')
                ->onDelete('no action')
                ->onUpdate('no action');

            $table->int('total');

            $table->timestamps();
        });

        Schema::create('transaction__detail', function (Blueprint $table) {
            $table->id();

            // # Relation to table Transaction
            $table->unsignedBigInteger('transaction_id')->nullable(false);
            $table->foreign('transaction_id')->references('id')->on('transaction')
                ->onDelete('no action')
                ->onUpdate('no action');

            // # Relation to table Item
            $table->unsignedBigInteger('item_id')->nullable(true)->comment('from item__variant');
            $table->foreign('item_id')->references('id')->on('item__variant')
                ->onDelete('no action')
                ->onUpdate('no action');

            // # Relation to table Resource
            $table->unsignedBigInteger('resource_id')->nullable(true);
            $table->foreign('resource_id')->references('id')->on('resource')
                ->onDelete('no action')
                ->onUpdate('no action');


            $table->int('total');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transaction');
    }
};
