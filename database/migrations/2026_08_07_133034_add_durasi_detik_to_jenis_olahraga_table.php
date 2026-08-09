<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('jenis_olahraga', function (Blueprint $table) {
            $table->unsignedInteger('durasi_detik')->nullable()->after('protokol_tes');
        });
    }

    public function down(): void
    {
        Schema::table('jenis_olahraga', function (Blueprint $table) {
            $table->dropColumn('durasi_detik');
        });
    }
};