<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StandarNilai extends Model
{
    use HasFactory;

    protected $table = 'standar_nilais';

    protected $fillable = [
        'jenis_olahraga_id',
        'jenis_kelamin',
        'waktu',       // <-- Tambahkan ini
        'jarak',       // <-- Tambahkan ini
    ];

    /**
     * Relasi ke JenisOlahraga
     */
    public function jenisOlahraga()
    {
        return $this->belongsTo(JenisOlahraga::class, 'jenis_olahraga_id');
    }

    /**
     * Relasi ke DetailStandarNilai (Rentang Nilai & Grade)
     */
    public function details()
    {
        return $this->hasMany(DetailStandarNilai::class, 'standar_nilai_id');
    }

    /**
     * Helper Static Method untuk menghitung Grade secara otomatis
     * berdasarkan Jenis Olahraga, Jenis Kelamin, dan Nilai Hasil Tes
     *
     * @param int|string $jenisOlahragaId
     * @param string $jenisKelamin
     * @param float|int $nilaiHasil
     * @return string
     */
    public static function hitungGrade($jenisOlahragaId, $jenisKelamin, $nilaiHasil)
{
    $standar = self::where('jenis_olahraga_id', $jenisOlahragaId)
        ->where('jenis_kelamin', $jenisKelamin)
        ->first();

    if (!$standar) {
        return '-';
    }

    $detail = DetailStandarNilai::where('standar_nilai_id', $standar->id)
        ->where(function ($q) use ($nilaiHasil) {
            $q->whereNull('minimal')->orWhere('minimal', '<=', $nilaiHasil);
        })
        ->where(function ($q) use ($nilaiHasil) {
            $q->whereNull('maksimal')->orWhere('maksimal', '>=', $nilaiHasil);
        })
        ->first();

    return $detail ? $detail->grade : '-';
}
}