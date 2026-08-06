<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('siswas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('kelas_id')->constrained('kelas')->cascadeOnDelete();
            $table->string('nama');
            $table->string('nomor_absen');
            $table->string('password');
            $table->timestamps();

            $table->unique(['kelas_id', 'nomor_absen']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('siswas');
    }
};