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
        Schema::create('detail_standar_nilais', function (Blueprint $table) {
            $table->id();

            $table->foreignId('standar_nilai_id')
                ->constrained('standar_nilais')
                ->cascadeOnDelete();

            $table->string('grade', 5);

            $table->decimal('minimal', 8, 2);

            $table->decimal('maksimal', 8, 2);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('detail_standar_nilais');
    }
};