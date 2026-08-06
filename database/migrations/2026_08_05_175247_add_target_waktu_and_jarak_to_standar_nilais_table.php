<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('standar_nilais', function (Blueprint $table) {
            // Menambahkan kolom waktu dan jarak (boleh kosong / nullable)
            $table->string('waktu')->nullable()->after('jenis_kelamin');
            $table->string('jarak')->nullable()->after('waktu');
        });
    }

    public function down(): void
    {
        Schema::table('standar_nilais', function (Blueprint $table) {
            $table->dropColumn(['waktu', 'jarak']);
        });
    }
};