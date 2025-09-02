<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('taxes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('outlet_id')->constrained('outlets')->onDelete('cascade');
            $table->string('name', 100);
            $table->decimal('rate', 8, 4)->comment('e.g., 11% is stored as 11.0000 or 0.11'); // Presisi tinggi untuk persentase
            $table->enum('type', ['PERCENTAGE', 'FIXED'])->default('PERCENTAGE');
            $table->text('description')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            // Nama pajak harus unik per outlet
            $table->unique(['outlet_id', 'name']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('taxes');
    }
};
