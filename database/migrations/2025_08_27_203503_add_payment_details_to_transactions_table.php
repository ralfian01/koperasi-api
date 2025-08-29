<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('transactions', function (Blueprint $table) {
            // Mengganti user_id menjadi member_id agar konsisten dengan tabel Anda
            $table->renameColumn('user_id', 'member_id');

            // Foreign key ke metode pembayaran
            $table->foreignId('payment_method_id')->after('total_amount')->constrained('payment_methods');

            // Kolom untuk menangani pembayaran tunai
            $table->decimal('cash_received', 15, 2)->nullable()->after('payment_method_id');
            $table->decimal('change_due', 15, 2)->default(0)->after('cash_received');
        });
    }

    public function down(): void
    {
        Schema::table('transactions', function (Blueprint $table) {
            $table->dropForeign(['payment_method_id']);
            $table->dropColumn(['payment_method_id', 'cash_received', 'change_due']);
            $table->renameColumn('member_id', 'user_id');
        });
    }
};
