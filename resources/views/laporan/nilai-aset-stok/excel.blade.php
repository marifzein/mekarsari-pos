<!DOCTYPE html>
<html>
<head>
    <title>Laporan Nilai Aset Stok</title>
</head>
<body>
    <h2>LAPORAN NILAI ASET STOK BARANG</h2>
    <p>Tanggal Cetak: {{ now()->format('d-m-Y H:i') }}</p>

    <table border="1">
        <thead>
            <tr style="background-color: #f2f2f2; font-weight: bold;">
                <th>No</th>
                <th>Kode Barang</th>
                <th>Nama Barang</th>
                <th>Stok Aktif</th>
                <th>HPP Average (Rp)</th>
                <th>Harga Jual (Rp)</th>
                <th>Total Nilai Aset (Rp)</th>
                <th>Potensi Jual (Rp)</th>
            </tr>
        </thead>
        <tbody>
            @foreach($reportData as $index => $row)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>'{{ $row->kode_barang }}</td> <!-- Tanda kutip tunggal mencegah Excel memotong zero-leading barcode -->
                <td>{{ $row->nama_barang }}</td>
                <td align="center">{{ $row->stok }}</td>
                <td align="right">{{ $row->hpp_average }}</td>
                <td align="right">{{ $row->harga_jual }}</td>
                <td align="right" style="background-color: #eef2ff;">{{ $row->total_nilai_aset }}</td>
                <td align="right">{{ $row->total_potensi_omset }}</td>
            </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr style="font-weight: bold; background-color: #f9f9f9;">
                <td colspan="6" align="right">TOTAL KESELURUHAN:</td>
                <td align="right" style="background-color: #eef2ff;">{{ $totalAsetToko->grand_total_aset ?? 0 }}</td>
                <td align="right">{{ $totalAsetToko->grand_total_jual ?? 0 }}</td>
            </tr>
        </tfoot>
    </table>
</body>
</html>