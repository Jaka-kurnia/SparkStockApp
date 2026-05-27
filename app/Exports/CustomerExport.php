<?php

namespace App\Exports;

use App\Models\Customer;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class CustomerExport implements FromCollection, WithHeadings, WithMapping, WithColumnWidths, WithStyles
{
    private $rowNumber = 0;

    public function collection()
    {
        return Customer::orderBy('name', 'asc')->get();
    }

    // 1. Mengatur Judul Kolom (Header)
    public function headings(): array
    {
        return [
            'No',
            'Nama Customer',
            'Email',
            'No. Telepon',
            'Alamat',
            'Tanggal Register',
        ];
    }

    // 2. Memetakan Data Database ke Kolom Excel (Perbaikan Parameter)
    public function map($customer): array
    {
        $this->rowNumber++;
        return [
            $this->rowNumber,
            $customer->name,
            $customer->email,
            $customer->phone,
            $customer->address,
            $customer->created_at ? $customer->created_at->format('d M Y H:i') : '-',
        ];
    }

    public function columnWidths(): array
    {
        return [
            'A' => 6,
            'B' => 25,
            'C' => 30,
            'D' => 20,
            'E' => 40,
            'F' => 22,
        ];
    }

    // 4. Mewarnai Header Biru, Font Putih, Bold, dan Menambahkan Border Data
    public function styles(Worksheet $sheet)
    {
        $totalRows = $this->rowNumber + 1;

        return [
            // Gaya untuk Baris 1 (Header)
            1 => [
                'font' => [
                    'bold' => true,
                    'color' => ['argb' => 'FFFFFFFF'], // Warna Putih
                    'size' => 11
                ],
                'fill' => [
                    'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                    'startColor' => ['argb' => 'FF1E40AF'], // Warna Biru Khas Tabler (Royal Blue)
                ],
                'alignment' => [
                    'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                    'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
                ],
            ],

            // Membuat teks No (Kolom A) dan Tanggal (Kolom F) menjadi rata tengah
            'A2:A' . $totalRows => [
                'alignment' => [
                    'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                ],
            ],
            'F2:F' . $totalRows => [
                'alignment' => [
                    'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                ],
            ],

            // Memberikan border tipis ke seluruh tabel (dari A1 sampai F terakhir)
            'A1:F' . $totalRows => [
                'borders' => [
                    'allBorders' => [
                        'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                        'color' => ['argb' => 'A0A0A0A0'],
                    ],
                ],
            ],
        ];
    }
    
}
