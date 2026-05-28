<?php

namespace App\Exports;

use App\Models\Service;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class ServiceExport implements FromCollection, WithHeadings, WithMapping, WithColumnWidths, WithStyles
{
    private $rowNumber = 0;

    public function collection()
    {
        return Service::all();
    }

    public function headings(): array
    {
        return [
            'No',
            'Kode Service',
            'Nama Service',
            'Harga',
            'Status',
            'Deskripsi',
            'Tanggal Dibuat'
        ];
    }

    /**
     * Memetakan data yang akan dimasukkan ke setiap kolom.
     * @param mixed $service
     * @return array
     */
    public function map($service): array
    {
        $this->rowNumber++;

        return [
            $this->rowNumber,
            $service->code,
            $service->complaint_name,
            'Rp ' . number_format($service->price, 0, ',', '.'),
            $service->is_service ? 'Aktif' : 'Tidak Aktif',
            $service->description ?? '-',
            $service->created_at ? $service->created_at->format('d-m-Y H:i') : '-'
        ];
    }

   
    public function columnWidths(): array
    {
        return [
            'A' => 6,
            'B' => 18,
            'C' => 30,
            'D' => 22,
            'E' => 15,
            'F' => 40,
            'G' => 22,
        ];
    }

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

            // Membuat teks No (Kolom A), Kode (Kolom B), Status (Kolom E) dan Tanggal (Kolom G) rata tengah
            'A2:A' . $totalRows => [
                'alignment' => ['horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER],
            ],
            'B2:B' . $totalRows => [
                'alignment' => ['horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER],
            ],
            'E2:E' . $totalRows => [
                'alignment' => ['horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER],
            ],
            'G2:G' . $totalRows => [
                'alignment' => ['horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER],
            ],

            // Memberikan border tipis ke seluruh tabel (dari A1 sampai G terakhir)
            'A1:G' . $totalRows => [
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
