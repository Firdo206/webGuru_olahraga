<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Cell\DataValidation;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;

class TemplateSiswaExport implements FromArray, WithHeadings, WithStyles, WithColumnWidths, WithEvents
{
    /**
     * Baris jumlah data contoh (dipakai lagi untuk styling & validasi)
     */
    private int $totalContohBaris = 2;

    public function array(): array
    {
        return [
            [1, 'Contoh Nama Siswa', 'Laki-Laki'],
            [2, 'Contoh Nama Siswa Lain', 'Perempuan'],
        ];
    }

    public function headings(): array
    {
        return ['nomor_absen', 'nama', 'jenis_kelamin'];
    }

    public function columnWidths(): array
    {
        return [
            'A' => 14, // nomor_absen
            'B' => 32, // nama
            'C' => 18, // jenis_kelamin
        ];
    }

    public function styles(Worksheet $sheet)
    {
        // Styling baris header
        $sheet->getStyle('A1:C1')->applyFromArray([
            'font' => [
                'bold' => true,
                'color' => ['rgb' => 'FFFFFF'],
                'size' => 12,
            ],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => ['rgb' => '2F5597'],
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical' => Alignment::VERTICAL_CENTER,
            ],
        ]);

        $sheet->getRowDimension(1)->setRowHeight(22);

        return [];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();
                $lastRow = $this->totalContohBaris + 1; // +1 karena baris 1 = header

                // Border tipis untuk area data contoh
                $sheet->getStyle("A1:C{$lastRow}")->applyFromArray([
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => Border::BORDER_THIN,
                            'color' => ['rgb' => 'BFBFBF'],
                        ],
                    ],
                ]);

                // Rata tengah untuk kolom nomor_absen & jenis_kelamin
                $sheet->getStyle("A2:A{$lastRow}")
                    ->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                $sheet->getStyle("C2:C{$lastRow}")
                    ->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

                // Freeze header agar tetap terlihat saat scroll
                $sheet->freezePane('A2');

                // Dropdown validasi untuk kolom jenis_kelamin (sampai baris 1000 agar user bisa tambah data)
                for ($row = 2; $row <= 1000; $row++) {
                    $validation = $sheet->getCell("C{$row}")->getDataValidation();
                    $validation->setType(DataValidation::TYPE_LIST);
                    $validation->setErrorStyle(DataValidation::STYLE_STOP);
                    $validation->setAllowBlank(false);
                    $validation->setShowInputMessage(true);
                    $validation->setShowErrorMessage(true);
                    $validation->setShowDropDown(true);
                    $validation->setErrorTitle('Input salah');
                    $validation->setError('Pilih "Laki-Laki" atau "Perempuan" dari daftar.');
                    $validation->setPromptTitle('Pilih jenis kelamin');
                    $validation->setPrompt('Pilih dari daftar dropdown.');
                    $validation->setFormula1('"Laki-Laki,Perempuan"');
                }
            },
        ];
    }
}