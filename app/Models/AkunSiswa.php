<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AkunSiswa extends Model
{
    use HasFactory;

    protected $table = 'akun_siswas';

    protected $fillable = [
        'siswa_id',
        'username',
        'password',
        'password_plain', // Sesuaikan dengan nama kolom phpMyAdmin Anda
    ];

    // Relasi balik ke Model Siswa
    public function siswa()
    {
        return $this->belongsTo(Siswa::class, 'siswa_id');
    }
}