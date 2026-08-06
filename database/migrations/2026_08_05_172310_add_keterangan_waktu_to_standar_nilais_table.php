<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Jalankan migration untuk mengubah tabel.
     */
    public function up(): void
    {
        Schema::table('standar_nilais', function (Blueprint $table) {
            $table->string('keterangan_waktu')->nullable()->after('jenis_kelamin');
        });
    }

    /**
     * Balikkan migration jika terjadi rollback.
     */
    public function down(): void
    {
        Schema::table('standar_nilais', function (Blueprint $table) {
            $table->dropColumn('keterangan_waktu');
        });
    }
};