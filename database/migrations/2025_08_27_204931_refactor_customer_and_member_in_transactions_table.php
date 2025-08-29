<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('transactions', function (Blueprint $table) {
            // Drop foreign key lama jika ada (berdasarkan migrasi sebelumnya)
            $table->dropForeign(['member_id']);

            // Ganti nama kolom menjadi customer_id dan buat foreign key baru ke tabel customers
            $table->renameColumn('member_id', 'customer_id');
            $table->foreign('customer_id')->references('id')->on('customers')->onDelete('set null');

            // Tambahkan kolom member_id yang baru, nullable, dan terhubung ke tabel members
            $table->foreignId('member_id')->nullable()->after('customer_id')->constrained('members')->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::table('transactions', function (Blueprint $table) {
            // Lakukan kebalikannya
            $table->dropForeign(['member_id']);
            $table->dropColumn('member_id');

            $table->dropForeign(['customer_id']);
            $table->renameColumn('customer_id', 'member_id');
            // Tambahkan kembali foreign key lama ke tabel members
            $table->foreign('member_id')->references('id')->on('members')->onDelete('set null');
        });
    }
};
