<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StandarNilai extends Model
{
    use HasFactory;

    protected $table = 'standar_nilais';

    protected $fillable = [
        'guru_id',
        'jenis_olahraga_id',
        'jenis_kelamin',
        'waktu',
        'jarak',
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
     * berdasarkan Guru, Jenis Olahraga, Jenis Kelamin, dan Nilai Hasil Tes
     *
     * @param int $guruId
     * @param int|string $jenisOlahragaId
     * @param string $jenisKelamin
     * @param float|int $nilaiHasil
     * @return string
     */
    public static function hitungGrade($guruId, $jenisOlahragaId, $jenisKelamin, $nilaiHasil)
    {
        $standar = self::where('guru_id', $guruId)
            ->where('jenis_olahraga_id', $jenisOlahragaId)
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

    /**
     * Cek apakah standar nilai untuk kombinasi kelas + jenis olahraga ini
     * sudah lengkap (ada untuk semua jenis kelamin siswa yang ada di kelas itu).
     * Return null kalau lengkap, atau string peringatan kalau ada yang kurang.
     */
    public static function cekKelengkapan(int $guruId, int $kelasId, int $jenisOlahragaId): ?string
    {
        $genderDiKelas = Siswa::where('kelas_id', $kelasId)
            ->distinct()
            ->pluck('jenis_kelamin');

        $kurang = [];
        foreach ($genderDiKelas as $gender) {
            $ada = self::where('guru_id', $guruId)
                ->where('jenis_olahraga_id', $jenisOlahragaId)
                ->where('jenis_kelamin', $gender)
                ->exists();

            if (!$ada) {
                $kurang[] = $gender;
            }
        }

        if (empty($kurang)) {
            return null;
        }

        return 'Standar nilai belum dibuat untuk: ' . implode(', ', $kurang) . '. Siswa dengan jenis kelamin ini akan mendapat grade "-".';
    }
}