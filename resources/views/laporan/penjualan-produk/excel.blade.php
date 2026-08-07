<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<h3>Laporan Penjualan Per Produk</h3>
<p>Periode Tanggal: {{ $startDate }} s/d {{ $endDate }}</p>

<table border="1">
    <thead>
        <tr style="background-color: #f1f5f9; font-weight: bold;">
            <th>No</th>
            <th>Kode Barang</th>
            <th>Nama Barang</th>
            <th>Harga Jual (Rp)</th>
            <th>Terjual</th>
            <th>Total Pendapatan (Rp)</th>
        </tr>
    </thead>
    <tbody>
        @foreach($reportData as $index => $row)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>'{{ $row->kode_barang }}</td> <!-- Tanda kutip satu mencegah Excel memotong angka nol di depan -->
                <td>{{ $row->nama_barang }}</td>
                <td>{{ $row->harga }}</td>
                <td>{{ $row->total_terjual }}</td>
                <td>{{ $row->total_pendapatan }}</td>
            </tr>
        @endforeach
    </tbody>
    <tfoot>
        <tr style="font-weight: bold; background-color: #f8fafc;">
            <td colspan="4" align="right">TOTAL KESELURUHAN:</td>
            <td align="center">{{ $totals->grand_qty ?? 0 }}</td>
            <td align="right">{{ $totals->grand_revenue ?? 0 }}</td>
        </tr>
    </tfoot>
</table>