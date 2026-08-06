<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('hasil_tes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('sesi_tes_id')->constrained('sesi_tes')->cascadeOnDelete();
            $table->foreignId('siswa_id')->constrained('siswas')->cascadeOnDelete();
            $table->decimal('nilai_hasil', 8, 2); // waktu (detik) atau jarak (meter), sesuai jenis olahraga
            $table->string('grade')->nullable();
            $table->timestamps();

            // satu siswa cuma boleh submit sekali per sesi
            $table->unique(['sesi_tes_id', 'siswa_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('hasil_tes');
    }
};