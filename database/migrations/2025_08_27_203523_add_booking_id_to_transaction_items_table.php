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
        // Schema::table('transaction_items', function (Blueprint $table) {
        //     $table->foreignId('booking_id')->nullable()->after('session_id')->constrained('bookings')->onDelete('set null');
        // });
    }

    public function down(): void
    {
        // Schema::table('transaction_items', function (Blueprint $table) {
        //     $table->dropForeign(['booking_id']);
        //     $table->dropColumn('booking_id');
        // });
    }
};
