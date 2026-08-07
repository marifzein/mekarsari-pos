<!DOCTYPE html>
<html>
<head>
    <title>Cetak Nilai Aset Stok</title>
    <style>
        body { 
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; 
            font-size: 11px; 
            color: #333; 
            margin: 10px;
        }
        .header { 
            text-align: center; 
            margin-bottom: 25px; 
        }
        .header h2 { 
            margin: 0 0 5px 0; 
            padding: 0; 
            font-size: 18px;
            color: #1e293b;
        }
        .header p {
            margin: 0;
            color: #64748b;
            font-size: 11px;
        }
        .summary-box { 
            margin-bottom: 20px; 
            background: #f8fafc; 
            padding: 12px; 
            border: 1px solid #e2e8f0; 
            border-radius: 6px;
            line-height: 1.6;
        }
        .table-report { 
            width: 100%; 
            border-collapse: collapse; /* 💡 FIX: Sekarang sudah normal & menyatu */
            margin-top: 10px; 
        }
        .table-report th, .table-report td { 
            border: 1px solid #cbd5e1; 
            padding: 8px 10px; 
        }
        .table-report th { 
            background-color: #f1f5f9; 
            font-weight: bold; 
            color: #334155;
        }
        .table-report tr:nth-child(even) {
            background-color: #f8fafc; /* Efek zebra halus biar mata tidak pusing baca data banyak */
        }
        .text-right { text-align: right; }
        .text-center { text-align: center; }
        .bg-indigo { 
            background-color: #e0e7ff !important; 
            font-weight: bold;
            color: #4338ca;
        }
    </style>
</head>
<body onload="window.print()">

    <div class="header">
        <h2>LAPORAN NILAI ASET STOK PERSEDIAAN</h2>
        <p>Dicetak pada: {{ now()->format('d F Y H:i') }}</p>
    </div>

    <div class="summary-box">
        <strong>RINGKASAN ASET TOKO:</strong><br>
        Total Investasi Modal Barang (HPP) : <strong>Rp {{ number_format($totalAsetToko->grand_total_aset ?? 0, 0, ',', '.') }}</strong><br>
        Total Potensi Nilai Penjualan (Omset) : <strong>Rp {{ number_format($totalAsetToko->grand_total_jual ?? 0, 0, ',', '.') }}</strong>
    </div>

    <table class="table-report">
        <thead>
            <tr>
                <th width="5%" class="text-center">No</th>
                <th width="15%">Kode</th>
                <th width="35%">Nama Barang</th>
                <th width="10%" class="text-center">Stok</th>
                <th width="10%" class="text-right">HPP Avg</th>
                <th width="10%" class="text-right">Harga Jual</th>
                <th width="15%" class="text-right bg-indigo">Nilai Aset</th>
            </tr>
        </thead>
        <tbody>
            @foreach($reportData as $index => $row)
            <tr>
                <td class="text-center">{{ $index + 1 }}</td>
                <td>{{ $row->kode_barang }}</td>
                <td>{{ $row->nama_barang }}</td>
                <td class="text-center">{{ number_format($row->stok, 0, ',', '.') }}</td>
                <td class="text-right">Rp {{ number_format($row->hpp_average, 0, ',', '.') }}</td>
                <td class="text-right">Rp {{ number_format($row->harga_jual, 0, ',', '.') }}</td>
                <td class="text-right bg-indigo" style="font-weight: 600;">Rp {{ number_format($row->total_nilai_aset, 0, ',', '.') }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

</body>
</html>