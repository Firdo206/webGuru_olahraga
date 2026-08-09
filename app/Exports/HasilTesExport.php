<?php

namespace App\Exports;

use App\Models\SesiTes;
use App\Models\Siswa;
use App\Models\HasilTes;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class HasilTesExport implements FromCollection, WithHeadings, WithMapping, WithTitle, ShouldAutoSize, WithStyles
{
    protected SesiTes $sesiTes;

    public function __construct(SesiTes $sesiTes)
    {
        $this->sesiTes = $sesiTes;
    }

    /**
     * Ambil semua siswa di kelas sesi ini, ditempeli hasil tes kalau ada.
     * Siswa yang belum tes tetap ikut, kolom nilai & grade-nya kosong.
     */
    public function collection()
    {
        $hasilMap = HasilTes::where('sesi_tes_id', $this->sesiTes->id)
            ->get()
            ->keyBy('siswa_id');

        return Siswa::where('kelas_id', $this->sesiTes->kelas_id)
            ->orderByRaw('CAST(nomor_absen AS UNSIGNED) ASC')
            ->get()
            ->map(function ($siswa) use ($hasilMap) {
                $h = $hasilMap->get($siswa->id);
                $siswa->nilai_hasil = $h->nilai_hasil ?? null;
                $siswa->grade = $h->grade ?? null;
                return $siswa;
            });
    }

    public function headings(): array
    {
        return ['No Absen', 'Nama', 'Jenis Kelamin', 'Nilai Hasil', 'Grade'];
    }

    public function map($siswa): array
    {
        return [
            $siswa->nomor_absen,
            $siswa->nama,
            $siswa->jenis_kelamin,
            $siswa->nilai_hasil ?? '-',
            $siswa->grade ?? 'Belum Tes',
        ];
    }

    public function title(): string
    {
        return 'Hasil Tes';
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true]],
        ];
    }
}   