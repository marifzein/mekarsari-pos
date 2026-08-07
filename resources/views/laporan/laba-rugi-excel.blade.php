<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Export Laba Rugi</title>
</head>
<body>
    <h3>LAPORAN LABA RUGI KOTOR PENJUALAN</h3>
    <p>Periode: {{ \Carbon\Carbon::parse($dari_tanggal)->format('d/m/Y') }} s/d {{ \Carbon\Carbon::parse($sampai_tanggal)->format('d/m/Y') }}</p>
    
    <table border="1" cellpadding="5" cellspacing="0">
        <thead>
            <tr style="background-color: #f2f2f2; font-weight: bold;">
                <th>No</th>
                <th>Tanggal</th>
                <th>Pendapatan Penjualan</th>
                <th>Harga Pokok Penjualan (HPP)</th>
                <th>Laba Kotor</th>
                <th>Margin (%)</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($reports as $key => $report)
                @php
                    $margin = $report->total_pendapatan > 0 ? ($report->laba_kotor / $report->total_pendapatan) * 100 : 0;
                @endphp
                <tr>
                    <td align="center">{{ $key + 1 }}</td>
                    <td>{{ \Carbon\Carbon::parse($report->tanggal)->format('d-m-Y') }}</td>
                    <td align="right">{{ $report->total_pendapatan }}</td>
                    <td align="right">{{ $report->total_hpp }}</td>
                    <td align="right">{{ $report->laba_kotor }}</td>
                    <td align="center">{{ number_format($margin, 2, '.', '') }}%</td>
                </tr>
            @endforeach
        </tbody>
        <tfoot>
            @php
                $total_margin = $totals->total_pendapatan > 0 ? ($totals->laba_kotor / $totals->total_pendapatan) * 100 : 0;
            @endphp
            <tr style="background-color: #e6e6e6; font-weight: bold;">
                <td colspan="2" align="center">TOTAL PERIODE INI</td>
                <td align="right">{{ $totals->total_pendapatan }}</td>
                <td align="right">{{ $totals->total_hpp }}</td>
                <td align="right">{{ $totals->laba_kotor }}</td>
                <td align="center">{{ number_format($total_margin, 2, '.', '') }}%</td>
            </tr>
        </tfoot>
    </table>
</body>
</html>