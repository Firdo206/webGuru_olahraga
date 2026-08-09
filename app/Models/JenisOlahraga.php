<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class JenisOlahraga extends Model
{
    protected $table = 'jenis_olahraga';

    protected $fillable = ['guru_id', 'nama_olahraga', 'tipe', 'protokol_tes', 'durasi_detik', 'deskripsi'];

    public function guru()
    {
        return $this->belongsTo(User::class, 'guru_id');
    }
}