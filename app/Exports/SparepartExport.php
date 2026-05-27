<?php

namespace App\Exports;

use App\Models\Sparepart;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class SparepartExport implements FromCollection, WithHeadings, WithMapping, WithColumnWidths, WithStyles
{
    private $rowNumber = 0;

    public function collection()
    {
        return Sparepart::all();
    }

    // Mengatur judul kolom di Excel
    public function headings(): array
    {
        return [
            'No',
            'SKU',
            'Nama Sparepart',
            'Merek',
            'Stok',
            'Harga Beli',
            'Harga Jual',
            'Lokasi',
        ];
    }

    // Memetakan data dari database ke kolom Excel
    public function map($sparepart): array
    {
        $this->rowNumber++;
        return [
            $this->rowNumber,
            $sparepart->sku,
            $sparepart->name,
            $sparepart->brand,
            $sparepart->stock,
            // Format angka menjadi format Rupiah agar rapi di excel
            'Rp ' . number_format($sparepart->purchase_price, 0, ',', '.'),
            'Rp ' . number_format($sparepart->selling_price, 0, ',', '.'),
            $sparepart->location,
        ];
    }

    // 3. Mengatur Lebar Kolom (Disesuaikan proporsinya agar tidak terlalu lebar)
    public function columnWidths(): array
    {
        return [
            'A' => 6,   // Kolom No
            'B' => 15,  // Kolom SKU
            'C' => 30,  // Kolom Nama Sparepart
            'D' => 20,  // Kolom Merek
            'E' => 12,  // Kolom Stok
            'F' => 22,  // Kolom Harga Beli
            'G' => 22,  // Kolom Harga Jual
            'H' => 20,  // Kolom Lokasi
        ];
    }

    // 4. Mewarnai Header Biru, Font Putih, Bold, dan Menambahkan Border Data
    public function styles(Worksheet $sheet)
    {
        // Mendapatkan total baris data untuk menentukan jangkauan border
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

            // Membuat text data di kolom A (No) & Kolom E (Stok) menjadi rata tengah
            'A2:A' . $totalRows => [
                'alignment' => [
                    'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                ],
            ],
            'E2:E' . $totalRows => [
                'alignment' => [
                    'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                ],
            ],

            // 2. PERBAIKAN: Jangkauan border diubah dari E menjadi H (A1:H) agar membungkus semua kolom data
            'A1:H' . $totalRows => [
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
