<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Cetak Laporan Penjualan Produk</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @media print {
            body { background: white !important; color: black; padding: 0 !important; }
            @page { margin: 1.5cm; }
        }
    </style>
</head>
<body class="bg-slate-50 p-8" onload="window.print();">

    <div class="max-w-5xl mx-auto bg-white p-6 rounded shadow border print:shadow-none print:border-none print:p-0 print:mx-0 print:max-w-full">
        <div class="text-center mb-6">
            <h1 class="text-2xl font-bold uppercase">Laporan Penjualan Per Produk</h1>
            <p class="text-sm text-gray-600 mt-1">Periode Tanggal: <span class="font-semibold">{{ $startDate }}</span> s/d <span class="font-semibold">{{ $endDate }}</span></p>
        </div>

        <table class="w-full border-collapse border border-gray-300">
            <thead>
                <tr class="bg-gray-100 text-sm font-semibold text-gray-700">
                    <th class="p-2 border border-gray-300 text-center w-12">No</th>
                    <th class="p-2 border border-gray-300 text-left">Kode</th>
                    <th class="p-2 border border-gray-300 text-left">Nama Barang</th>
                    <th class="p-2 border border-gray-300 text-right">Harga Jual</th>
                    <th class="p-2 border border-gray-300 text-center">Terjual</th>
                    <th class="p-2 border border-gray-300 text-right">Total Pendapatan</th>
                </tr>
            </thead>
            <tbody class="text-xs text-gray-700">
                @foreach($reportData as $index => $row)
                    <tr class="border-b border-gray-300">
                        <td class="p-2 border border-gray-300 text-center">{{ $index + 1 }}</td>
                        <td class="p-2 border border-gray-300 font-mono">{{ $row->kode_barang }}</td>
                        <td class="p-2 border border-gray-300 font-medium">{{ $row->nama_barang }}</td>
                        <td class="p-2 border border-gray-300 text-right">Rp {{ number_format($row->harga, 0, ',', '.') }}</td>
                        <td class="p-2 border border-gray-300 text-center font-bold">{{ $row->total_terjual }}</td>
                        <td class="p-2 border border-gray-300 text-right font-medium">Rp {{ number_format($row->total_pendapatan, 0, ',', '.') }}</td>
                    </tr>
                @endforeach
            </tbody>
            <tfoot class="bg-gray-50 font-bold text-xs text-gray-800">
                <tr>
                    <td colspan="4" class="p-2 border border-gray-300 text-right">TOTAL KESELURUHAN:</td>
                    <td class="p-2 border border-gray-300 text-center text-blue-600">{{ $totals->grand_qty ?? 0 }}</td>
                    <td class="p-2 border border-gray-300 text-right text-emerald-600">Rp {{ number_format($totals->grand_revenue ?? 0, 0, ',', '.') }}</td>
                </tr>
            </tfoot>
        </table>
    </div>

</body>
</html>