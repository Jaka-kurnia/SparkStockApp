<!DOCTYPE html>
<html>

<head>
    <title>Laporan Dashboard & Analitik</title>
    <style>
        @page {
            margin: 1.5cm;
        }

        body {
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
            font-size: 11px;
            color: #333333;
            line-height: 1.4;
        }

        .header-laporan {
            text-align: center;
            margin-bottom: 25px;
            border-bottom: 2px solid #1e40af;
            padding-bottom: 10px;
        }

        .header-laporan h2 {
            margin: 0 0 5px 0;
            color: #1e40af;
            font-size: 18px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .header-laporan p {
            margin: 0;
            color: #666666;
            font-size: 12px;
        }

        /* Stats Grid */
        .stats-grid {
            width: 100%;
            margin-bottom: 25px;
        }
        
        .stats-card {
            background-color: #f8fafc;
            border: 1px solid #cbd5e1;
            border-radius: 6px;
            padding: 12px;
            text-align: center;
        }

        .stats-card .title {
            font-size: 9px;
            color: #64748b;
            text-transform: uppercase;
            margin-bottom: 5px;
            font-weight: bold;
        }

        .stats-card .value {
            font-size: 16px;
            color: #1e293b;
            font-weight: bold;
        }

        /* Desain Tabel Utama */
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        th,
        td {
            border: 1px solid #cbd5e1;
            padding: 8px 10px;
            text-align: left;
            vertical-align: middle;
        }

        th {
            background-color: #1e40af;
            color: #ffffff;
            font-weight: bold;
            text-transform: uppercase;
            font-size: 9px;
            letter-spacing: 0.5px;
        }

        tr:nth-child(even) {
            background-color: #f8fafc;
        }

        .text-center {
            text-align: center;
        }

        .text-right {
            text-align: right;
        }

        .badge {
            padding: 2px 6px;
            border-radius: 4px;
            font-size: 8px;
            font-weight: bold;
            text-transform: uppercase;
        }

        .badge-success {
            background-color: #dcfce7;
            color: #15803d;
        }

        .badge-warning {
            background-color: #fef9c3;
            color: #a16207;
        }
    </style>
</head>

<body>
    <div class="header-laporan">
        <h2>Laporan Analitik Transaksi</h2>
        <p>
            Periode: 
            <strong>
                @if($period == 'today')
                    Hari Ini ({{ $startDate->format('d M Y') }})
                @elseif($period == 'week')
                    Minggu Ini ({{ $startDate->format('d M Y') }} - {{ $endDate->format('d M Y') }})
                @elseif($period == 'month')
                    Bulan Ini ({{ $startDate->format('F Y') }})
                @elseif($period == 'year')
                    Tahun Ini ({{ $startDate->format('Y') }})
                @else
                    {{ $startDate->format('d M Y') }} - {{ $endDate->format('d M Y') }}
                @endif
            </strong>
        </p>
        <p>Aplikasi SparkStock &bull; Tanggal Cetak: {{ date('d F Y H:i') }}</p>
    </div>

    <!-- Ringkasan Statistik -->
    <table class="stats-grid" style="border: none; margin-bottom: 20px;">
        <tr style="background: none;">
            <td style="border: none; width: 25%; padding: 0 5px 0 0;">
                <div class="stats-card">
                    <div class="title">Total Pendapatan</div>
                    <div class="value" style="color: #16a34a;">Rp {{ number_format($stats['revenue'], 2, ',', '.') }}</div>
                </div>
            </td>
            <td style="border: none; width: 25%; padding: 0 5px;">
                <div class="stats-card">
                    <div class="title">Total Order Servis</div>
                    <div class="value">{{ $stats['total_orders'] }}</div>
                </div>
            </td>
            <td style="border: none; width: 25%; padding: 0 5px;">
                <div class="stats-card">
                    <div class="title">Servis Selesai</div>
                    <div class="value">{{ $stats['completed_orders'] }}</div>
                </div>
            </td>
            <td style="border: none; width: 25%; padding: 0 0 0 5px;">
                <div class="stats-card">
                    <div class="title">Sparepart Terjual</div>
                    <div class="value">{{ $stats['parts_sold'] }} unit</div>
                </div>
            </td>
        </tr>
    </table>

    <h3>Detail Transaksi Servis</h3>
    <table>
        <thead>
            <tr>
                <th style="width: 5%" class="text-center">No</th>
                <th style="width: 15%">Kode Order</th>
                <th style="width: 12%">Tanggal</th>
                <th style="width: 18%">Pelanggan</th>
                <th style="width: 15%">Kendaraan</th>
                <th style="width: 15%">Mekanik</th>
                <th style="width: 10%" class="text-center">Status Bayar</th>
                <th style="width: 10%" class="text-right">Total</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($serviceOrders as $item)
                <tr>
                    <td class="text-center">{{ $loop->iteration }}</td>
                    <td style="font-weight: bold; color: #1e293b;">{{ $item->kode_order }}</td>
                    <td>{{ $item->service_date ? $item->service_date->format('d-m-Y') : '-' }}</td>
                    <td>{{ $item->customer ? $item->customer->name : '-' }}</td>
                    <td>{{ $item->vehicle ? $item->vehicle->plate_number : '-' }}</td>
                    <td>{{ $item->mechanic ? $item->mechanic->name_mechanic : '-' }}</td>
                    <td class="text-center">
                        @if($item->payment_status == 'paid')
                            <span class="badge badge-success">Lunas</span>
                        @else
                            <span class="badge badge-warning">Belum Lunas</span>
                        @endif
                    </td>
                    <td class="text-right">Rp {{ number_format($item->grand_total, 2, ',', '.') }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="8" class="text-center" style="color: #64748b;">Tidak ada data transaksi pada periode ini.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</body>

</html>
