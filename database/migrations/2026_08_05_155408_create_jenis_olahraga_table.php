<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('jenis_olahraga', function (Blueprint $table) {
            $table->id();
            $table->string('nama_olahraga');
            $table->enum('tipe', ['waktu', 'poin'])->default('poin'); // <-- Tambahkan baris ini
            $table->text('deskripsi')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('jenis_olahraga');
    }
};