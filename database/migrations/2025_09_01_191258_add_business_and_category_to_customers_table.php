<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('customers', function (Blueprint $table) {
            $table->foreignId('business_id')->after('id')->constrained('business')->onDelete('cascade');
            $table->foreignId('customer_category_id')->after('business_id')->nullable()->constrained('customer_categories')->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::table('customers', function (Blueprint $table) {
            $table->dropForeign(['business_id']);
            $table->dropForeign(['customer_category_id']);
            $table->dropColumn(['business_id', 'customer_category_id']);
        });
    }
};
