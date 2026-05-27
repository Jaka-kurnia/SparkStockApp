<!DOCTYPE html>
<html>

<head>
    <title>Laporan Data Supplier</title>
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
            border: 1px solid #cbd5e1; /* Border abu-abu halus ala Tabler */
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
        <h2>Laporan Data Sparepart</h2>
        <p>Aplikasi SparkStock &bull; Tanggal Cetak: {{ date('d F Y') }}</p>
    </div>

    <table>
        <thead>
            <tr>
                <th style="width: 5%" class="text-center">No</th>
                <th style="width: 20%">SKU</th>
                <th style="width: 25%">Nama Sparepart</th>
                <th style="width: 20%">Merek</th>
                <th style="width: 15%">Stok</th>
                <th style="width: 15%">Harga Beli</th>
                <th style="width: 15%">Harga Jual</th>
                <th style="width: 15%">Lokasi</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($sparepart as $item)
                <tr>
                    <td class="text-center">{{ $loop->iteration }}</td>
                    <td style="font-weight: bold; color: #1e293b;">{{ $item->sku }}</td>
                    <td>{{ $item->name }}</td>
                    <td>{{ $item->brand }}</td>
                    <td>{{ $item->stock }}</td>
                    <td>{{$item->purchase_price}}</td>
                    <td>{{$item->selling_price}}</td>
                    <td>{{ $item->location }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>

</html>