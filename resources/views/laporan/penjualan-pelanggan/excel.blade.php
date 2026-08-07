<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<h3>Laporan Penjualan Per Pelanggan</h3>
<p>Periode Tanggal: {{ $startDate }} s/d {{ $endDate }}</p>

<table border="1">
    <thead>
        <tr style="background-color: #f1f5f9; font-weight: bold;">
            <th>No</th>
            <th>Kode Pelanggan</th>
            <th>Nama Pelanggan</th>
            <th>Jumlah Transaksi</th>
            <th>Total Kontribusi Omset (Rp)</th>
        </tr>
    </thead>
    <tbody>
        @foreach($reportData as $index => $row)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ $row->kode_pelanggan ? "'".$row->kode_pelanggan : '-' }}</td>
                <td>{{ $row->nama_pelanggan }}</td>
                <td>{{ $row->total_transaksi }}</td>
                <td>{{ $row->total_belanja }}</td>
            </tr>
        @endforeach
    </tbody>
    <tfoot>
        <tr style="font-weight: bold; background-color: #f8fafc;">
            <td colspan="3" align="right">TOTAL KESELURUHAN:</td>
            <td align="center">{{ $totals->grand_qty_transaksi ?? 0 }}</td>
            <td align="right">{{ $totals->grand_omset ?? 0 }}</td>
        </tr>
    </tfoot>
</table>