<?php

namespace App\Exports;

use App\Models\Supplier;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class SupplierExport implements FromCollection, WithHeadings, WithMapping, WithColumnWidths, WithStyles
{
    private $rowNumber = 0;

    public function collection()
    {
        return Supplier::all();
    }

    // Mengatur judul kolom di Excel
    public function headings(): array
    {
        return [
            'No',
            'Nama Supplier',
            'Email',
            'No. Telepon',
            'Alamat',
        ];
    }

    // Memetakan data dari database ke kolom Excel
    public function map($supplier): array
    {
        $this->rowNumber++;
        return [
            $this->rowNumber,
            $supplier->name,
            $supplier->email,
            $supplier->phone,
            $supplier->address,
        ];
    }

    // 3. Mengatur Lebar Kolom agar Rapi (Tidak Terpotong)
    public function columnWidths(): array
    {
        return [
            'A' => 6,   // Kolom No
            'B' => 30,  // Kolom Nama Supplier
            'C' => 30,  // Kolom Email
            'D' => 20,  // Kolom No Telepon
            'E' => 45,  // Kolom Alamat
        ];
    }

    // 4. Mewarnai Header Biru, Font Putih, Bold, dan Menambahkan Border Data
    public function styles(Worksheet $sheet)
    {
        // Mendapatkan total baris data untuk menentukan jangkauan border
        $totalRows = $this->rowNumber + 1; // ditambah 1 karena baris pertama adalah header

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

            // Membuat text data di kolom A (No) menjadi rata tengah secara otomatis
            'A2:A' . $totalRows => [
                'alignment' => [
                    'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                ],
            ],

            // Memberikan border tipis ke seluruh tabel (dari A1 sampai E terakhir)
            'A1:E' . $totalRows => [
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
