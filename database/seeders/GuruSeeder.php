<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class GuruSeeder extends Seeder
{
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'guru@test.com'],
            [
                'name' => 'Guru Contoh',
                'password' => Hash::make('password123'),
                'role' => 'guru',
            ]
        );
    }
}