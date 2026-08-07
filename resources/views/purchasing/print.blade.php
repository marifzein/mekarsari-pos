<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Cetak PO - {{ $po->po_number }}</title>
    <style>
        body {
            font-family: sans-serif;
            font-size: 10pt;
            color: #000;
            line-height: 1.4;
            margin: 0;
            padding: 0;
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
        
        /* Items Table dengan Garis Penuh */
        .items-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        .items-table th {
            border: 1px solid #000; /* Garis penuh untuk header */
            padding: 6px;
            font-size: 9.5pt;
            font-weight: bold;
            background-color: #fff; /* Menghapus warna background */
        }
        .items-table td {
            border: 1px solid #000; /* Garis penuh untuk sisi kanan, kiri, atas, bawah */
            padding: 6px;
            font-size: 9.5pt;
        }
        
        /* Bagian Total */
        .total-row td {
            border: none !important;
            padding: 6px;
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
        }
    </style>
</head>
{{-- <body> --}}
<body onload="window.print();"> <!-- Otomatis pemicu print modal window browser
    <div style="width: 100%;">
        <!-- Header Dokumen -->
        <table class="header-table">
            <tr>
                <td>
                    <h2 style="margin:0;">POS MINIMARKET</h2>
                    <p style="margin:0; font-size:9pt;">Jl. Raya Utama No. 123, Kota Administrasi</p>
                </td>
                <td style="text-align: right; vertical-align: top;">
                    <h2 style="margin:0;">PURCHASE ORDER</h2>
                    <p style="margin:0;"><strong>{{ $po->po_number }}</strong></p>
                </td>
            </tr>
        </table>

        <hr style="border: 0; border-top: 1px solid #000; margin-bottom: 20px;">

        <!-- Informasi Supplier & Pesanan -->
        <table class="info-table">
            <tr>
                <td>
                    <strong>INFO SUPPLIER:</strong><br>
                    {{ $po->supplier->nama }}<br>
                    Telp: {{ $po->supplier->telepon ?? '-' }}
                </td>
                <td>
                    <strong>DETAIL PESANAN:</strong><br>
                    Tanggal PO: {{ \Carbon\Carbon::parse($po->po_date)->format('d-m-Y') }}<br>
                    Status: {{ $po->status }}
                </td>
            </tr>
        </table>

        <!-- Daftar Item Pesanan (Garis Kotak Sempurna) -->
        <table class="items-table">
            <thead>
                <tr>
                    <th style="text-align: left;">Nama Barang</th>
                    <th style="text-align: center; width: 10%;">Qty</th>
                    <th style="text-align: right; width: 20%;">Harga</th>
                    <th style="text-align: right; width: 20%;">Subtotal</th>
                </tr>
            </thead>
            <tbody>
                @foreach($po->items as $item)
                    <tr>
                        <td>{{ $item->product->nama_barang ?? 'Produk Tanpa Nama' }}</td>
                        <td class="text-center">{{ $item->qty }}</td>
                        <td class="text-right">Rp {{ number_format($item->price, 0, ',', '.') }}</td>
                        <td class="text-right">Rp {{ number_format($item->subtotal, 0, ',', '.') }}</td>
                    </tr>
                @endforeach
                
                <!-- Baris Total Bersih Tanpa Border Kotak Tabel Utama -->
                <tr class="total-row">
                    <td colspan="3" class="text-right ">TOTAL</td>
                    <td class="text-right" style="border: 1px double #000 !important;" >
                        {{-- --}}
                        Rp {{ number_format($po->total, 0, ',', '.') }}
                    </td>
                </tr>
            </tbody>
        </table>

        <!-- Catatan Opsional -->
        @if($po->notes)
            <div style="font-size: 9pt; margin-bottom: 40px;">
                <strong>Catatan:</strong> {{ $po->notes }}
            </div>
        @endif

        <!-- Tanda Tangan -->
        <table style="width: 100%; margin-top: 50px; text-align: center;">
            <tr>
                <td>
                    <p>Disiapkan Oleh,</p>
                    <div style="height: 60px;"></div>
                    <p>__________________</p>
                    <p style="font-size: 8pt; color: #555;">Purchasing Staff</p>
                </td>
                <td>
                    <p>Disetujui Oleh,</p>
                    <div style="height: 60px;"></div>
                    <p><strong>{{ $po->user->name ?? 'Manager' }}</strong></p>
                    <p style="font-size: 8pt; color: #555;">Operational Manager</p>
                </td>
            </tr>
        </table>
    </div>
</body>
</html>