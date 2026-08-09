<?php

namespace App\Imports;

use App\Models\Siswa;
use Illuminate\Support\Collection as BaseCollection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithCalculatedFormulas;

class SiswaImport implements ToCollection, WithCalculatedFormulas
{
    protected int $kelasId;
    protected array $importedNames = [];

    /** Pesan baris yang dilewati (duplikat, kosong, dsb) */
    public array $skipped = [];

    /** Jumlah siswa yang berhasil masuk */
    public int $importedCount = 0;

    // Variasi nama kolom yang dikenali (sudah dinormalisasi: huruf kecil, tanpa spasi/simbol)
    private const NO_HEADERS = ['no', 'nomor', 'noabsen', 'nomorabsen', 'nourut', 'absen'];
    private const NAMA_HEADERS = ['nama', 'namasiswa', 'namalengkap', 'name'];
    private const GENDER_HEADERS = ['jeniskelamin', 'jk', 'gender', 'lp', 'kelamin'];

    public function __construct(int $kelasId)
    {
        $this->kelasId = $kelasId;
    }

    public function collection(BaseCollection $rows)
    {
        $headerRowIndex = null;
        $columnMap = [];

        // 1. Cari baris header di antara 20 baris pertama file
        foreach ($rows->take(20) as $i => $row) {
            $map = $this->detectColumns($row);
            if (isset($map['nama']) && isset($map['jenis_kelamin'])) {
                $headerRowIndex = $i;
                $columnMap = $map;
                break;
            }
        }

        if ($headerRowIndex === null) {
            $this->skipped[] = 'Header kolom (No, Nama, Jenis Kelamin) tidak ditemukan di file ini. Pastikan ada baris judul kolom yang jelas.';
            return;
        }

        $autoAbsen = 1;

        // 2. Proses semua baris setelah baris header sebagai data siswa
        foreach ($rows->slice($headerRowIndex + 1) as $rowIndex => $row) {
            $excelRowNumber = $rowIndex + 1; // baris asli di file excel (1-based)

            $nama = isset($columnMap['nama']) ? trim((string) ($row[$columnMap['nama']] ?? '')) : '';

            if ($nama === '') {
                continue; // baris kosong / sub-header, lewati diam-diam
            }

            $namaNormalized = strtolower($nama);

            // Cegah nama kembar di dalam 1 file yang sama
            if (in_array($namaNormalized, $this->importedNames, true)) {
                $this->skipped[] = "Baris {$excelRowNumber}: \"{$nama}\" dilewati, duplikat di dalam file.";
                continue;
            }

            // Cegah bentrok dengan siswa yang sudah ada di kelas ini
            $sudahAda = Siswa::where('kelas_id', $this->kelasId)
                ->whereRaw('LOWER(nama) = ?', [$namaNormalized])
                ->exists();

            if ($sudahAda) {
                $this->skipped[] = "Baris {$excelRowNumber}: \"{$nama}\" dilewati, sudah terdaftar di kelas ini.";
                continue;
            }

            // Nomor absen: pakai dari file kalau ada dan valid angka, kalau tidak auto urut
            $nomorAbsenRaw = isset($columnMap['nomor_absen'])
                ? trim((string) ($row[$columnMap['nomor_absen']] ?? ''))
                : '';

            $nomorAbsen = ($nomorAbsenRaw !== '' && is_numeric($nomorAbsenRaw))
                ? (int) $nomorAbsenRaw
                : $autoAbsen;

            $jenisKelaminRaw = isset($columnMap['jenis_kelamin'])
                ? ($row[$columnMap['jenis_kelamin']] ?? '')
                : '';

            $this->importedNames[] = $namaNormalized;
            $autoAbsen++;

            Siswa::create([
                'kelas_id'      => $this->kelasId,
                'nama'          => $nama,
                'nomor_absen'   => $nomorAbsen,
                'jenis_kelamin' => $this->normalizeGender($jenisKelaminRaw),
            ]);

            $this->importedCount++;
        }
    }

    /**
     * Cari kolom "no", "nama", dan "jenis kelamin" di satu baris,
     * berapa pun urutan/posisinya, dengan berbagai variasi penulisan.
     */
    private function detectColumns($row): array
    {
        $map = [];

        foreach ($row as $index => $value) {
            $key = $this->normalizeHeader($value);
            if ($key === '') {
                continue;
            }

            if (!isset($map['nomor_absen']) && in_array($key, self::NO_HEADERS, true)) {
                $map['nomor_absen'] = $index;
            } elseif (!isset($map['nama']) && in_array($key, self::NAMA_HEADERS, true)) {
                $map['nama'] = $index;
            } elseif (!isset($map['jenis_kelamin']) && in_array($key, self::GENDER_HEADERS, true)) {
                $map['jenis_kelamin'] = $index;
            }
        }

        return $map;
    }

    /** Normalisasi teks header: huruf kecil, buang spasi/newline/simbol */
    private function normalizeHeader($value): string
    {
        $value = strtolower(trim((string) $value));
        $value = str_replace(["\n", "\r"], ' ', $value);
        $value = preg_replace('/[^a-z]/', '', $value);

        return $value ?? '';
    }

    protected function normalizeGender($value): string
    {
        $value = strtolower(trim((string) $value));
        $value = str_replace(["\n", "\r"], '', $value);

        if (in_array($value, ['l', 'laki-laki', 'lakilaki', 'laki laki', 'pria', 'putra', 'm', 'male'], true)) {
            return 'Laki-Laki';
        }

        return 'Perempuan';
    }
}