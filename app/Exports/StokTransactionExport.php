<?php

namespace App\Exports;

use App\Models\StokTransaction;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles; // Tambahkan concern untuk styling
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet; // Diperlukan untuk mapping style

class StockTransactionExport implements FromCollection, WithHeadings, WithMapping, WithStyles
{
    /**
     * 1. Ambil data dari database beserta relasinya
     */
    public function collection()
    {
        return StokTransaction::with(['sparepart', 'supplier', 'user'])->latest()->get();
    }

    /**
     * 2. Membuat Judul Kolom (Header) di baris pertama Excel
     */
    public function headings(): array
    {
        return [
            'No',
            'Tanggal Transaksi',
            'Tipe Mutasi',
            'Nama Sparepart',
            'Supplier / Pemasok',
            'Jumlah (Qty)',
            'Harga Satuan',
            'Total Biaya',
            'Operator (Admin)',
            'Catatan'
        ];
    }

    /**
     * 3. Memetakan properti model ke baris Excel secara rapi
     * @param mixed $row
     */
    public function map($row): array
    {
        $typeLabel = '';
        if ($row->type === 'in') {
            $typeLabel = 'Barang Masuk';
        } elseif ($row->type === 'out') {
            $typeLabel = 'Barang Keluar';
        } else {
            $typeLabel = 'Adjustment';
        }

        return [
            $row->id,
            $row->created_at->format('d-m-Y H:i'),
            $typeLabel,
            $row->sparepart->name ?? '-',
            $row->supplier ? $row->supplier->name : 'Non-Supplier',
            $row->qty . ' Pcs',
            'Rp ' . number_format($row->price_per_unit, 0, ',', '.'),
            'Rp ' . number_format($row->total_amount, 0, ',', '.'),
            $row->user->name ?? '-',
            $row->notes ?? '-'
        ];
    }

    /**
     * 4. Mengatur Desain Layouting (Header Biru, Text Putih)
     */
    public function styles(Worksheet $sheet)
    {
        return [
            // Target baris ke-1 (Header)
            1 => [
                'font' => [
                    'bold' => true,
                    'color' => ['argb' => 'FFFFFFFF'], // Warna Putih (Format ARGB)
                    'size' => 11,
                ],
                'fill' => [
                    'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                    'startColor' => [
                        'argb' => 'FF206BC4', // Warna Biru Khas Tabler UI
                    ],
                ],
                'alignment' => [
                    'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                    'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
                ],
            ],
        ];
    }
}
