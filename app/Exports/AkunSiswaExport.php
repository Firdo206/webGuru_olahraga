<?php

namespace App\Exports;

use App\Models\Siswa;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class AkunSiswaExport implements FromCollection, WithHeadings, WithMapping, WithStyles, WithColumnWidths, WithEvents, WithTitle
{
    protected $kelasId;
    protected $guruId;
    protected $namaKelas;

    public function __construct($kelasId, $guruId, $namaKelas = 'Kelas')
    {
        $this->kelasId = $kelasId;
        $this->guruId = $guruId;
        $this->namaKelas = $namaKelas;
    }

    public function collection()
    {
        return Siswa::with(['akun', 'kelas'])
            ->where('kelas_id', $this->kelasId)
            ->whereHas('kelas', function ($q) {
                $q->where('guru_id', $this->guruId);
            })
            ->orderByRaw('CAST(nomor_absen AS UNSIGNED) ASC')
            ->get();
    }

    public function headings(): array
    {
        return ['No Absen', 'Nama Siswa', 'Kelas', 'Username', 'Password'];
    }

    public function map($siswa): array
    {
        return [
            $siswa->nomor_absen,
            $siswa->nama,
            $siswa->kelas->nama_kelas ?? '-',
            $siswa->akun->username ?? '(belum ada akun)',
            $siswa->akun->password_plain ?? '-',
        ];
    }

    public function title(): string
    {
        return $this->namaKelas;
    }

    public function columnWidths(): array
    {
        return [
            'A' => 10,  // No Absen
            'B' => 28,  // Nama Siswa
            'C' => 14,  // Kelas
            'D' => 22,  // Username
            'E' => 16,  // Password
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => [
                'font' => ['bold' => true, 'size' => 12, 'color' => ['rgb' => 'FFFFFF']],
                'fill' => [
                    'fillType'   => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                    'startColor' => ['rgb' => '10B981'],
                ],
                'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER, 'vertical' => Alignment::VERTICAL_CENTER],
            ],
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();
                $highestRow = $sheet->getHighestRow();
                $highestColumn = $sheet->getHighestColumn();

                // Tinggi baris header
                $sheet->getRowDimension(1)->setRowHeight(24);

                // Border tipis buat semua sel yang ada data
                $sheet->getStyle("A1:{$highestColumn}{$highestRow}")->applyFromArray([
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => Border::BORDER_THIN,
                            'color' => ['rgb' => 'D1D5DB'],
                        ],
                    ],
                ]);

                // Rata tengah buat kolom No Absen
                $sheet->getStyle("A2:A{$highestRow}")
                    ->getAlignment()
                    ->setHorizontal(Alignment::HORIZONTAL_CENTER);

                // Freeze header row biar tetap kelihatan pas scroll
                $sheet->freezePane('A2');

                // Selang-seling warna baris biar gampang dibaca
                for ($row = 2; $row <= $highestRow; $row++) {
                    if ($row % 2 === 0) {
                        $sheet->getStyle("A{$row}:{$highestColumn}{$row}")->applyFromArray([
                            'fill' => [
                                'fillType'   => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                                'startColor' => ['rgb' => 'F3F4F6'],
                            ],
                        ]);
                    }
                }
            },
        ];
    }
}