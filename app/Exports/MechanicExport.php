<?php

namespace App\Exports;

use App\Models\Mechanic;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class MechanicExport implements FromCollection, WithHeadings, WithMapping, WithColumnWidths, WithStyles
{
    private $rowNumber = 0; // Properti internal penomoran baris data agar aman dari memory leak

    public function collection()
    {
        // Gunakan Eager Loading (with) untuk mengambil data user terkait tanpa boros query (N+1 Problem)
        return Mechanic::with('user')->get();
    }

    // 1. Mengatur Judul Kolom (Header)
    public function headings(): array
    {
        return [
            'No',
            'Nama Mekanik',
            'No. Telepon',
            'Status Kerja',
        ];
    }

    // 2. Memetakan Data Database ke Kolom Excel (Menghubungkan ke tabel Users)
    public function map($mechanic): array
    {
        $this->rowNumber++;
        return [
            $this->rowNumber,
            $mechanic->name_mechanic,
            $mechanic->phone ?? '-',
            $mechanic->is_active == 1 ? 'Aktif' : 'Tidak Aktif',
        ];
    }

    // 3. Mengatur Lebar Kolom secara presisi
    public function columnWidths(): array
    {
        return [
            'A' => 6,   // Kolom No
            'B' => 25,  // Kolom Nama Mekanik
            'C' => 18,  // Kolom No. Telepon
            'D' => 16,  // Kolom Status Kerja
        ];
    }

    // 4. Mewarnai Header Biru Royal Blue, Font Putih, Bold, dan Border Tipis
    public function styles(Worksheet $sheet)
    {
        // Mendapatkan total baris data asli (jumlah data + 1 baris header)
        $totalRows = $this->rowNumber + 1; 

        return [
            // Style header: baris 1
            1 => [
                'font' => [
                    'bold' => true,
                    'color' => ['argb' => 'FFFFFFFF'], // Warna Putih
                    'size' => 11
                ],
                'fill' => [
                    'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                    'startColor' => ['argb' => 'FF1E40AF'], // Royal Blue khas Tabler
                ],
                'alignment' => [
                    'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                    'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
                ],
            ],

            // Membuat teks No (A), Target Harian (E), dan Status (F) rata tengah
            'A2:A' . $totalRows => [
                'alignment' => ['horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER],
            ],
            'E2:E' . $totalRows => [
                'alignment' => ['horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER],
            ],
            'F2:F' . $totalRows => [
                'alignment' => ['horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER],
            ],

            // Style border tabel penuh (Dari kolom A sampai F)
            'A1:F' . $totalRows => [
                'borders' => [
                    'allBorders' => [
                        'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                        'color' => ['argb' => 'A0A0A0A0'], // Abu-abu halus ala Tabler
                    ],
                ],
            ],
        ];
    }
}
