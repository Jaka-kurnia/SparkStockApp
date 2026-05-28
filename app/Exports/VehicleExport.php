<?php

namespace App\Exports;

use App\Models\Vehicle;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles; 
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class VehicleExport implements FromCollection, WithHeadings, WithMapping, WithColumnWidths, WithStyles
{
    private $rowNumber = 0;

    public function collection()
    {
        return Vehicle::select(
            'plate_number',
            'type',
            'year',
            'brand',
            'color'
        )->get();
    }

    // 1. Mengatur Judul Kolom (Header)
    public function headings(): array
    {
        return [
            'No',
            'No. Plat / Polisi',
            'Tipe Kendaraan',
            'Tahun',
            'Merek',
            'Warna',
        ];
    }

    // 2. Memetakan Data Database ke Kolom Excel (Menambahkan No Urut)
    public function map($vehicle): array
    {
        $this->rowNumber++;
        return [
            $this->rowNumber,
            $vehicle->plate_number,
            $vehicle->type,
            $vehicle->year ?? '-',
            $vehicle->brand,
            $vehicle->color,
        ];
    }

    // 3. Mengatur Lebar Kolom secara presisi
    public function columnWidths(): array
    {
        return [
            'A' => 6,   // Kolom No
            'B' => 20,  // Kolom No. Plat
            'C' => 25,  // Kolom Tipe Kendaraan
            'D' => 12,  // Kolom Tahun
            'E' => 20,  // Kolom Merek
            'F' => 15,  // Kolom Warna
        ];
    }

    public function styles(Worksheet $sheet)
    {
        $totalRows = $this->rowNumber + 1; 

        return [
            1 => [
                'font' => [
                    'bold' => true,
                    'color' => ['argb' => 'FFFFFFFF'], 
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

            // Membuat teks No (Kolom A), Plat (Kolom B), dan Tahun (Kolom D) rata tengah
            'A2:A' . $totalRows => [
                'alignment' => ['horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER],
            ],
            'B2:B' . $totalRows => [
                'alignment' => ['horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER],
            ],
            'D2:D' . $totalRows => [
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
