<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Tambah index biasa dulu di kelas_id, biar foreign key ada index pengganti
        Schema::table('siswas', function (Blueprint $table) {
            $table->index('kelas_id');
        });

        // 2. Baru drop unique lama & bikin unique baru
        Schema::table('siswas', function (Blueprint $table) {
            $table->dropUnique(['kelas_id', 'nomor_absen']);
            $table->unique(['kelas_id', 'nama']);
        });
    }

    public function down(): void
    {
        Schema::table('siswas', function (Blueprint $table) {
            $table->dropUnique(['kelas_id', 'nama']);
            $table->unique(['kelas_id', 'nomor_absen']);
        });

        Schema::table('siswas', function (Blueprint $table) {
            $table->dropIndex(['kelas_id']);
        });
    }
};