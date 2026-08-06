<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('jenis_olahraga', function (Blueprint $table) {
            $table->string('protokol_tes')->nullable()->after('deskripsi');
        });
    }

    public function down(): void
    {
        Schema::table('jenis_olahraga', function (Blueprint $table) {
            $table->dropColumn('protokol_tes');
        });
    }
};