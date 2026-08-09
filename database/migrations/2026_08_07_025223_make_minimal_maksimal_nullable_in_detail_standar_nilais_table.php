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
        Schema::table('detail_standar_nilais', function (Blueprint $table) {
            $table->decimal('minimal', 8, 2)->nullable()->change();
            $table->decimal('maksimal', 8, 2)->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('detail_standar_nilais', function (Blueprint $table) {
            $table->decimal('minimal', 8, 2)->nullable(false)->change();
            $table->decimal('maksimal', 8, 2)->nullable(false)->change();
        });
    }
};