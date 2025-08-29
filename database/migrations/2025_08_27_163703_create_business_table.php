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
        Schema::create('business', function (Blueprint $table) {
            $table->id();
            $table->string('logo')->nullable();
            $table->string('name', 100);
            $table->string('email')->nullable()->unique();
            $table->string('contact')->nullable();
            $table->text('description')->nullable();
            $table->string('website')->nullable();
            $table->string('instagram')->nullable();
            $table->string('tiktok')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('business');
    }
};
