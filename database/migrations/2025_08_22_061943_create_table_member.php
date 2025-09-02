<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        // Kita akan menggunakan nama tabel plural 'members' sesuai konvensi Laravel
        Schema::create('members', function (Blueprint $table) {
            $table->id(); // Primary key auto-increment standar

            // ID Anggota Koperasi yang unik (misal: nomor registrasi)
            $table->string('member_code')->unique();

            // Nama anggota (yang merupakan nama Koperasi lain)
            $table->string('name', 100);

            // Status keanggotaan
            $table->boolean('is_active')->default(true);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::dropIfExists('members');
    }
};
