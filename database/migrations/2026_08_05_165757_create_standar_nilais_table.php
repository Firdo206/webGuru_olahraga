<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('standar_nilais', function (Blueprint $table) {
            $table->id();

            $table->foreignId('jenis_olahraga_id')
                ->constrained('jenis_olahraga')
                ->cascadeOnDelete();

            $table->enum('jenis_kelamin', [
                'Putra',
                'Putri'
            ]);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('standar_nilais');
    }
};