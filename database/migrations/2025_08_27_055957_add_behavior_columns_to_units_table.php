<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('units', function (Blueprint $table) {
            $table->enum('behavior_type', ['DURATION', 'ACTIVITY', 'CONSUMABLE'])
                ->after('name');
            $table->unsignedInteger('value_in_minutes')
                ->nullable()
                ->after('behavior_type')
                ->comment('For DURATION: exact minutes. For ACTIVITY/CONSUMABLE: estimated minutes for conflict checking.');
        });
    }

    public function down(): void
    {
        Schema::table('units', function (Blueprint $table) {
            $table->dropColumn(['behavior_type', 'value_in_minutes']);
        });
    }
};
