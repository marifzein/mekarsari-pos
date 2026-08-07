<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <title>Cetak Penerimaan Barang - {{ $penerimaan->no_penerimaan }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 10pt;
            color: #000;
            line-height: 1.4;
            margin: 0;
            padding: 10px;
            width: 100%;
        }
        .text-right { text-align: right; }
        .text-center { text-align: center; }
        
        /* Header */
        .header-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        
        /* Info Area */
        .info-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
            border: 1px solid #000;
        }
        .info-table td {
            width: 50%;
            vertical-align: top;
            padding: 10px;
        }
        .info-table td:first-child {
            border-right: 1px solid #000;
        }
        
        /* Items Table dengan Garis Kotak Penuh */
        .items-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        .items-table th {
            border: 1px solid #000;
            padding: 8px 6px;
            font-size: 9.5pt;
            font-weight: bold;
            background-color: #f4f4f4;
        }
        .items-table td {
            border: 1px solid #000;
            padding: 8px 6px;
            font-size: 9.5pt;
        }
        
        /* Bagian Total */
        .total-row td {
            border: none !important;
            padding: 8px 6px;
            font-weight: bold;
        }

        /* Mengunci Kertas ke Mode Portrait A4 saat cetak/save PDF */
        @media print {
            @page { 
                size: A4 portrait; 
                margin: 15mm; 
            }
            .no-print { display: none; }
            body { padding: 0; }
            .print-container { max-width: 100% !important; padding: 0 !important; margin: 0 !important; }
        }
    </style>
</head>
<body onload="window.print();"> <!-- Otomatis pemicu print modal window browser[cite: 5] -->
    
    <!-- Wrapper pembatas layar biar ga melar pas ditutup -->
    <div style="max-width: 800px; margin: 0 auto; padding: 20px;" class="print-container">
        
        <!-- Header Dokumen[cite: 10] -->
        <table class="header-table">
            <tr>
                <td>
                    <h2 style="margin:0; font-size: 16pt;">POS MINIMARKET</h2>
                    <p style="margin:0; font-size:9pt; color: #444;">Jl. Raya Utama No. 123, Kota Administrasi</p>
                </td>
                <td style="text-align: right; vertical-align: top;">
                    <h2 style="margin:0; font-size: 14pt;">BUKTI PENERIMAAN BARANG</h2>
                    <p style="margin:0; font-size: 11pt;"><strong>{{ $penerimaan->no_penerimaan }}</strong></p>
                </td>
            </tr>
        </table>

        <hr style="border: 0; border-top: 1px solid #000; margin-bottom: 20px;">

        <!-- Informasi Supplier & Penerimaan[cite: 10] -->
        <table class="info-table">
            <tr>
                <td>
                    <strong>INFO SUPPLIER:</strong><br>
                    <span style="font-size: 11pt; font-weight: bold;">{{ $penerimaan->supplier_name }}</span><br>
                    No. Dokumen / Nota Supplier: {{ $penerimaan->no_dokumen_supplier ?? '-' }}
                </td>
                <td>
                    <strong>DETAIL PENERIMAAN:</strong><br>
                    No. Rujukan PO: {{ $penerimaan->no_po ?? 'Penerimaan Bebas (Non-PO)' }}<br>
                    Tanggal Terima: {{ \Carbon\Carbon::parse($penerimaan->tanggal_terima)->format('d-m-Y') }}<br>
                    Petugas Penerima: {{ $penerimaan->kasir_name }}
                </td>
            </tr>
        </table>

        <!-- Daftar Item Pesanan[cite: 10] -->
        <table class="items-table">
            <thead>
                <tr>
                    <th style="text-align: center; width: 5%;">No</th>
                    <th style="text-align: left;">Nama Barang</th>
                    <th style="text-align: center; width: 12%;">Qty PO</th>
                    <th style="text-align: center; width: 12%;">Qty Terima</th>
                    <th style="text-align: right; width: 18%;">Harga Beli</th>
                    <th style="text-align: right; width: 20%;">Subtotal</th>
                </tr>
            </thead>
            <tbody>
                @php $grandTotal = 0; @endphp
                @foreach($items as $index => $item)
                    @php 
                        $subtotal = $item->qty_terima * $item->harga_beli; 
                        $grandTotal += $subtotal;
                    @endphp
                    <tr>
                        <td class="text-center">{{ $index + 1 }}</td>
                        <td>
                            <span style="font-weight: bold;">{{ $item->nama_barang }}</span><br>
                            <small style="color: #555;">{{ $item->kode_barang }}</small>
                            @if($item->qty_po == 0)
                                <span style="font-size: 8pt; font-weight: bold; color: #7c3aed;">(Item Luar PO)</span>
                            @endif
                        </td>
                        <td class="text-center">{{ $item->qty_po }}</td>
                        <td class="text-center" style="font-weight: bold;">{{ $item->qty_terima }}</td>
                        <td class="text-right">Rp {{ number_format($item->harga_beli, 0, ',', '.') }}</td>
                        <td class="text-right">Rp {{ number_format($subtotal, 0, ',', '.') }}</td>
                    </tr>
                @endforeach
                
                <!-- Baris Total Bersih[cite: 10] -->
                <tr class="total-row">
                    <td colspan="5" class="text-right">GRAND TOTAL NOTA</td>
                    <td class="text-right" style="border: 1px double #000 !important; font-size: 11pt;">
                        Rp {{ number_format($grandTotal, 0, ',', '.') }}
                    </td>
                </tr>
            </tbody>
        </table>

        <!-- Catatan Opsional[cite: 10] -->
        @if($penerimaan->catatan)
            <div style="font-size: 9pt; margin-bottom: 40px; border: 1px dashed #999; padding: 8px; border-radius: 4px;">
                <strong>Catatan Penerimaan:</strong> {{ $penerimaan->catatan }}
            </div>
        @endif

        <!-- Tanda Tangan[cite: 10] -->
        <table style="width: 100%; margin-top: 50px; text-align: center;">
            <tr>
                <td>
                    <p>Diterima Oleh,</p>
                    <div style="height: 50px;"></div>
                    <p><strong>{{ $penerimaan->kasir_name }}</strong></p>
                    <p style="font-size: 8pt; color: #555;">Gudang / Logistik Staff</p>
                </td>
                <td>
                    <p>Diserahkan Oleh,</p>
                    <div style="height: 50px;"></div>
                    <p>__________________</p>
                    <p style="font-size: 8pt; color: #555;">Driver / Sales Supplier</p>
                </td>
            </tr>
        </table>
    </div>
</body>
</html>