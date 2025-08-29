<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('active_sessions', function (Blueprint $table) {
            $table->id('session_id');
            $table->foreignId('resource_id')->constrained('resources', 'resource_id');
            $table->timestamp('start_time')->useCurrent();
            $table->timestamp('end_time')->nullable();
            $table->enum('status', ['ACTIVE', 'COMPLETED', 'CANCELLED'])->default('ACTIVE');
            $table->unsignedInteger('final_duration_minutes')->nullable();
            $table->decimal('final_price', 15, 2)->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('active_sessions');
    }
};
