<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Cetak Laporan Penjualan Pelanggan</title>
    <!-- Memanggil Tailwind CSS agar styling presisi -->
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
        <!-- Header Laporan -->
        <div class="text-center mb-6">
            <h1 class="text-2xl font-bold uppercase tracking-wide text-slate-800">Laporan Penjualan Per Pelanggan</h1>
            <p class="text-sm text-gray-600 mt-1">
                Periode Tanggal: <span class="font-semibold text-slate-900">{{ $startDate }}</span> s/d <span class="font-semibold text-slate-900">{{ $endDate }}</span>
            </p>
        </div>

        <!-- Tabel Cetak Modern -->
        <table class="w-full border-collapse border border-gray-300">
            <thead>
                <tr class="bg-slate-100 text-sm font-semibold text-slate-700">
                    <th class="p-3 border border-gray-300 text-center w-12">No</th>
                    <th class="p-3 border border-gray-300 text-left">Kode Pelanggan</th>
                    <th class="p-3 border border-gray-300 text-left">Nama Pelanggan</th>
                    <th class="p-3 border border-gray-300 text-center">Jumlah Transaksi</th>
                    <th class="p-3 border border-gray-300 text-right">Total Kontribusi Omset</th>
                </tr>
            </thead>
            <tbody class="text-xs text-slate-700">
                @foreach($reportData as $index => $row)
                    <tr class="border-b border-gray-300 hover:bg-slate-50">
                        <td class="p-2 border border-gray-300 text-center">{{ $index + 1 }}</td>
                        <td class="p-2 border border-gray-300 font-mono text-gray-500">
                            {{ $row->kode_pelanggan ?? '-' }}
                        </td>
                        <td class="p-2 border border-gray-300 font-medium text-slate-900">
                            {{ $row->nama_pelanggan }}
                        </td>
                        <td class="p-2 border border-gray-300 text-center font-semibold">
                            {{ $row->total_transaksi }}x
                        </td>
                        <td class="p-2 border border-gray-300 text-right font-bold text-emerald-600">
                            Rp {{ number_format($row->total_belanja, 0, ',', '.') }}
                        </td>
                    </tr>
                @endforeach
            </tbody>
            <!-- Footer Total Keseluruhan -->
            <tfoot class="bg-slate-50 font-bold text-xs text-slate-800">
                <tr>
                    <td colspan="3" class="p-3 border border-gray-300 text-right uppercase">TOTAL KESELURUHAN:</td>
                    <td class="p-3 border border-gray-300 text-center text-blue-600 text-sm">
                        {{ $totals->grand_qty_transaksi ?? 0 }}x
                    </td>
                    <td class="p-3 border border-gray-300 text-right text-emerald-600 text-sm">
                        Rp {{ number_format($totals->grand_omset ?? 0, 0, ',', '.') }}
                    </td>
                </tr>
            </tfoot>
        </table>
    </div>

</body>
</html>