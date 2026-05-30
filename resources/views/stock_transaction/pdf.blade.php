<!DOCTYPE html>
<html>

<head>
    <title>Laporan Data Transaksi Stok</title>
    <style>
        /* Mengatur Margin Kertas */
        @page {
            margin: 1.5cm;
        }

        body {
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
            font-size: 11px;
            color: #333333;
            line-height: 1.4;
        }

        /* Desain Header Laporan / Kop */
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

        /* Desain Tabel Utama */
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        th,
        td {
            border: 1px solid #cbd5e1;
            /* Border abu-abu halus ala Tabler */
            padding: 8px 10px;
            text-align: left;
            vertical-align: middle;
        }

        /* Header Tabel: Biru Royal Blue, Tulisan Putih, Bold */
        th {
            background-color: #1e40af;
            color: #ffffff;
            font-weight: bold;
            text-transform: uppercase;
            font-size: 10px;
            letter-spacing: 0.5px;
        }

        /* Striping / Baris Belang-belang halus agar mudah dibaca */
        tr:nth-child(even) {
            background-color: #f8fafc;
        }

        .text-center {
            text-align: center;
        }
    </style>
</head>

<body>
    <div class="header-laporan">
        <h2>Laporan Data Transaksi Stok</h2>
        <p>Aplikasi SparkStock &bull; Tanggal Cetak: {{ date('d F Y') }}</p>
    </div>

    <table>
        <thead>
            <tr>
                <th style="width: 5%" class="text-center">No</th>
                <th style="width: 20%">Tipe Transaksi</th>
                <th style="width: 25%">Nama Supplier</th>
                <th style="width: 20%">Nama Sparepart</th>
                <th style="width: 15%">Jumlah</th>
                <th style="width: 15%">Harga Satuan</th>
                <th style="width: 15%">Total Harga</th>
                <th style="width: 15%">Catatan</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($stockTransactions as $item)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>
                        @if ($item->type == 'in')
                            <span class="badge bg-success-lt">Barang Masuk</span>
                        @elseif($item->type == 'out')
                            <span class="badge bg-danger-lt">Barang Keluar</span>
                        @else
                            <span class="badge bg-warning-lt">Adjustment</span>
                        @endif
                    </td>
                    <td>
                        {{ $item->supplier ? $item->supplier->name : 'Non-Supplier' }}
                    </td>
                    <td>{{ $item->sparepart->name ?? '-' }}</td>
                    <td>{{ $item->qty }} Pcs</td>
                    <td>Rp {{ number_format($item->price_per_unit, 0, ',', '.') }}</td>
                    <td>Rp {{ number_format($item->total_amount, 0, ',', '.') }}</td>
                    <td >
                        {{ $item->notes ? $item->notes : '-' }}
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>

</html>
