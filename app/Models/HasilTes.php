<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HasilTes extends Model
{
    use HasFactory;

    protected $table = 'hasil_tes';

    protected $fillable = [
        'sesi_tes_id',
        'siswa_id',
        'nilai_hasil',
        'grade',
    ];

    public function sesiTes()
    {
        return $this->belongsTo(SesiTes::class, 'sesi_tes_id');
    }

    public function siswa()
    {
        return $this->belongsTo(Siswa::class, 'siswa_id');
    }

    /**
     * Simpan hasil tes sekaligus otomatis hitung grade-nya
     * berdasarkan StandarNilai (jenis olahraga + jenis kelamin siswa).
     */
    public static function simpanHasil(SesiTes $sesiTes, Siswa $siswa, float $nilaiHasil): self
    {
        $grade = StandarNilai::hitungGrade(
            $sesiTes->jenis_olahraga_id,
            $siswa->jenis_kelamin,
            $nilaiHasil
        );

        return self::updateOrCreate(
            [
                'sesi_tes_id' => $sesiTes->id,
                'siswa_id'    => $siswa->id,
            ],
            [
                'nilai_hasil' => $nilaiHasil,
                'grade'       => $grade,
            ]
        );
    }
}