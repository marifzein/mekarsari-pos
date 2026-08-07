<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Cetak Laporan Laba Rugi</title>
    <style>
        body { font-family: Arial, sans-serif; font-size: 12px; color: #333; padding: 20px; }
        .header { text-align: center; margin-bottom: 25px; }
        .header h2 { margin: 0; padding-bottom: 5px; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th, td { border: 1px solid #ddd; padding: 10px; text-align: left; }
        th { background-color: #f4f6f9; font-weight: bold; text-align: center; }
        .text-right { text-align: right; }
        .text-center { text-align: center; }
        .font-bold { font-weight: bold; }
        .bg-total { background-color: #f9f9f9; }
        
        /* Mengunci Kertas ke Mode Landscape saat cetak/save PDF */
        @media print {
            @page { size: landscape; margin: 15mm; }
            .no-print { display: none; }
        }
    </style>
</head>
<body onload="window.print();">
    <div class="header">
        <h2>POS MINIMARKET</h2>
        <h3>Laporan Laba Rugi Kotor Penjualan</h3>
        <p>Periode: {{ \Carbon\Carbon::parse($dari_tanggal)->translatedFormat('d F Y') }} s/d {{ \Carbon\Carbon::parse($sampai_tanggal)->translatedFormat('d F Y') }}</p>
    </div>

    <table>
        <thead>
            <tr>
                <th width="5%">No</th>
                <th>Tanggal</th>
                <th width="23%">Pendapatan Penjualan</th>
                <th width="23%">Harga Pokok Penjualan (HPP)</th>
                <th width="23%">Laba Kotor</th>
                <th width="13%">Margin</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($reports as $key => $report)
                @php
                    $margin = $report->total_pendapatan > 0 ? ($report->laba_kotor / $report->total_pendapatan) * 100 : 0;
                @endphp
                <tr>
                    <td class="text-center">{{ $key + 1 }}</td>
                    <td>{{ \Carbon\Carbon::parse($report->tanggal)->translatedFormat('d F Y') }}</td>
                    <td class="text-right">Rp {{ number_format($report->total_pendapatan, 0, ',', '.') }}</td>
                    <td class="text-right">Rp {{ number_format($report->total_hpp, 0, ',', '.') }}</td>
                    <td class="text-right font-bold" style="color: #059669;">Rp {{ number_format($report->laba_kotor, 0, ',', '.') }}</td>
                    <td class="text-center">{{ number_format($margin, 2, ',', '.') }}%</td>
                </tr>
            @endforeach
        </tbody>
        <tfoot>
            @php
                $total_margin = $totals->total_pendapatan > 0 ? ($totals->laba_kotor / $totals->total_pendapatan) * 100 : 0;
            @endphp
            <tr class="font-bold bg-total">
                <td colspan="2" class="text-center">TOTAL PERIODE INI</td>
                <td class="text-right">Rp {{ number_format($totals->total_pendapatan, 0, ',', '.') }}</td>
                <td class="text-right">Rp {{ number_format($totals->total_hpp, 0, ',', '.') }}</td>
                <td class="text-right" style="color: #047857; font-size: 13px;">Rp {{ number_format($totals->laba_kotor, 0, ',', '.') }}</td>
                <td class="text-center">{{ number_format($total_margin, 2, ',', '.') }}%</td>
            </tr>
        </tfoot>
    </table>
</body>
</html>