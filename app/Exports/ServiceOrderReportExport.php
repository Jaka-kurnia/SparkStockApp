<?php

namespace App\Exports;

use App\Models\ServiceOrder;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class ServiceOrderReportExport implements FromCollection, WithHeadings, WithMapping, WithColumnWidths, WithStyles
{
    private $rowNumber = 0;
    protected $startDate;
    protected $endDate;

    public function __construct($startDate, $endDate)
    {
        $this->startDate = $startDate;
        $this->endDate = $endDate;
    }

    public function collection()
    {
        return ServiceOrder::with(['customer', 'vehicle', 'mechanic'])
            ->whereBetween('service_date', [$this->startDate, $this->endDate])
            ->orderBy('service_date', 'asc')
            ->get();
    }

    public function headings(): array
    {
        return [
            'No',
            'Kode Order',
            'Tanggal Servis',
            'Pelanggan',
            'Kendaraan',
            'Mekanik',
            'Metode Pembayaran',
            'Status Pembayaran',
            'Grand Total',
        ];
    }

    public function map($order): array
    {
        $this->rowNumber++;
        return [
            $this->rowNumber,
            $order->kode_order,
            $order->service_date ? $order->service_date->format('d M Y') : '-',
            $order->customer ? $order->customer->name : '-',
            $order->vehicle ? $order->vehicle->plate_number : '-',
            $order->mechanic ? $order->mechanic->name_mechanic : '-',
            ucfirst($order->payment_method),
            ucfirst($order->payment_status),
            $order->grand_total,
        ];
    }

    public function columnWidths(): array
    {
        return [
            'A' => 6,
            'B' => 20,
            'C' => 18,
            'D' => 25,
            'E' => 15,
            'F' => 25,
            'G' => 20,
            'H' => 20,
            'I' => 18,
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
                    'startColor' => ['argb' => 'FF1E40AF'],
                ],
                'alignment' => [
                    'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                    'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
                ],
            ],
            'A2:A' . $totalRows => [
                'alignment' => [
                    'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                ],
            ],
            'B2:B' . $totalRows => [
                'alignment' => [
                    'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                ],
            ],
            'C2:C' . $totalRows => [
                'alignment' => [
                    'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                ],
            ],
            'E2:E' . $totalRows => [
                'alignment' => [
                    'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                ],
            ],
            'G2:G' . $totalRows => [
                'alignment' => [
                    'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                ],
            ],
            'H2:H' . $totalRows => [
                'alignment' => [
                    'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                ],
            ],
            'I2:I' . $totalRows => [
                'alignment' => [
                    'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT,
                ],
                'numberFormat' => [
                    'formatCode' => '#,##0.00',
                ],
            ],
            'A1:I' . $totalRows => [
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
