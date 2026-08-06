<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Hapus kolom password di tabel siswas
        Schema::table('siswas', function (Blueprint $table) {
            $table->dropColumn(['password', 'password_plain']);
        });

        // 2. Buat tabel baru akun_siswas
        Schema::create('akun_siswas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('siswa_id')->constrained('siswas')->onDelete('cascade');
            $table->string('username')->nullable();
            $table->string('password');
            $table->string('password_plain')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('akun_siswas');

        Schema::table('siswas', function (Blueprint $table) {
            $table->string('password')->nullable();
            $table->string('password_plain')->nullable();
        });
    }
};
