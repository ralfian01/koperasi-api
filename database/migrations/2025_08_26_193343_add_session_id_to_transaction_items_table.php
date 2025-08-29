<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('transaction_items', function (Blueprint $table) {
            $table->foreignId('session_id')
                ->nullable()
                ->after('variant_id') // Atur posisi kolom
                ->constrained('active_sessions', 'session_id')
                ->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::table('transaction_items', function (Blueprint $table) {
            // Drop foreign key dulu, lalu drop kolomnya
            $table->dropForeign(['session_id']);
            $table->dropColumn('session_id');
        });
    }
};
