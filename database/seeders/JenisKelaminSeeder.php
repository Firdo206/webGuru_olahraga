<?php

namespace Database\Seeders;

use App\Models\JenisKelamin;
use Illuminate\Database\Seeder;

class JenisKelaminSeeder extends Seeder
{
    public function run(): void
    {
        JenisKelamin::updateOrCreate(
            ['kode' => 'L'],
            ['nama' => 'Laki-laki']
        );

        JenisKelamin::updateOrCreate(
            ['kode' => 'P'],
            ['nama' => 'Perempuan']
        );
    }
}