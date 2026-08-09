<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Sanctum\HasApiTokens;

class AkunSiswa extends Authenticatable
{
    use HasFactory, HasApiTokens;

    protected $table = 'akun_siswas';

    protected $fillable = [
        'siswa_id',
        'username',
        'password',
        'password_plain', // Sesuaikan dengan nama kolom phpMyAdmin Anda
    ];

    protected $hidden = [
        'password',
        'password_plain',
    ];

    // Relasi balik ke Model Siswa
    public function siswa()
    {
        return $this->belongsTo(Siswa::class, 'siswa_id');
    }
}