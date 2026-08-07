    <!DOCTYPE html>
    <html>
    <head>
        <meta charset="utf-8">
        <title>Cetak Retur Barang - {{ $retur->no_retur }}</title>
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
                border: 1px solid #000;
                padding: 6px;
                font-size: 9.5pt;
                font-weight: bold;
                background-color: #fff;
            }
            .items-table td {
                border: 1px solid #000;
                padding: 6px;
                font-size: 9.5pt;
            }
            
            /* Bagian Total */
            .total-row td {
                border: none !important;
                padding: 6px;
                font-weight: bold;
            }

            /* Tombol Aksi */
            .no-print-zone {
                background: #f3f4f6;
                padding: 12px;
                margin-bottom: 20px;
                border-radius: 8px;
                display: flex;
                gap: 10px;
            }
            .btn {
                padding: 8px 16px;
                border: none;
                border-radius: 4px;
                cursor: pointer;
                font-weight: bold;
                font-size: 9pt;
            }
            
            /* Pengaturan Khusus Ketika Kertas di-Print */
            @media print {
                .no-print-zone {
                    display: none !important; /* Hilangkan tombol cetak dari kertas */
                }
                body {
                    margin: 0; /* Maksimalkan area cetak kertas */
                }
            }
        </style>
    </head>
    <body>
        <div style="width: 100%;">
            <!-- Header Dokumen -->
            <table class="header-table">
                <tr>
                    <td>
                        <h2 style="margin:0;">POS MINIMARKET</h2>
                        <p style="margin:0; font-size:9pt;">Sistem Manajemen Inventori Toko</p>
                    </td>
                    <td style="text-align: right; vertical-align: top;">
                        <h2 style="margin:0;">RETUR BARANG</h2>
                        <p style="margin:0;"><strong>{{ $retur->no_retur }}</strong></p>
                    </td>
                </tr>
            </table>

            <hr style="border: 0; border-top: 1px solid #000; margin-bottom: 20px;">

            <!-- Informasi Supplier & Petugas -->
            <table class="info-table">
                <tr>
                    <td>
                        <strong>SUPPLIER TUJUAN:</strong><br>
                        {{ $retur->supplier_name }}<br>
                        Status: Keluar (Stok Berkurang)
                    </td>
                    <td>
                        <strong>DETAIL RETUR:</strong><br>
                        Tanggal Retur: {{ \Carbon\Carbon::parse($retur->tanggal_retur)->format('d-m-Y') }}<br>
                        Petugas: {{ $retur->kasir_name }}
                    </td>
                </tr>
            </table>

            <!-- Daftar Item Pesanan (Garis Kotak Sempurna) -->
            <table class="items-table">
                <thead>
                    <tr>
                        <th style="text-align: center; width: 8%;">No</th>
                        <th style="text-align: left;">Nama Barang / Kode</th>
                        <th style="text-align: center; width: 12%;">Qty Retur</th>
                        <th style="text-align: right; width: 20%;">Harga Beli</th>
                        <th style="text-align: right; width: 20%;">Subtotal</th>
                    </tr>
                </thead>
                <tbody>
                    @php $grandTotal = 0; @endphp
                    @foreach($items as $index => $item)
                        @php 
                            $subtotal = $item->qty_retur * $item->harga_beli; 
                            $grandTotal += $subtotal;
                        @endphp
                        <tr>
                            <td class="text-center">{{ $index + 1 }}</td>
                            <td>{{ $item->nama_barang }}<br><small style="color:#555;">{{ $item->kode_barang }}</small></td>
                            <td class="text-center">{{ $item->qty_retur }}</td>
                            <td class="text-right">Rp {{ number_format($item->harga_beli, 0, ',', '.') }}</td>
                            <td class="text-right">Rp {{ number_format($subtotal, 0, ',', '.') }}</td>
                        </tr>
                    @endforeach
                    
                    <!-- Baris Total Bersih -->
                    <tr class="total-row">
                        <td colspan="4" class="text-right">TOTAL NILAI RETUR</td>
                        <td class="text-right" style="border: 1px double #000 !important;">
                            Rp {{ number_format($grandTotal, 0, ',', '.') }}
                        </td>
                    </tr>
                </tbody>
            </table>

            <!-- Catatan / Alasan Retur -->
            @if($retur->catatan)
                <div style="font-size: 9pt; margin-bottom: 40px;">
                    <strong>Alasan Pengembalian:</strong> {{ $retur->catatan }}
                </div>
            @endif

            <!-- Tanda Tangan Saksi Masuk/Keluar Barang -->
            <table style="width: 100%; margin-top: 50px; text-align: center;">
                <tr>
                    <td>
                        <p>Petugas Gudang,</p>
                        <div style="height: 60px;"></div>
                        <p>__________________</p>
                        <p style="font-size: 8pt; color: #555;">{{ $retur->kasir_name }}</p>
                    </td>
                    <td>
                        <p>Saksi Pihak Supplier,</p>
                        <div style="height: 60px;"></div>
                        <p>__________________</p>
                        <p style="font-size: 8pt; color: #555;">Driver / Sales</p>
                    </td>
                </tr>
            </table>
        </div>

        <!-- Script Native Print Otomatis saat Halaman Load -->
    <script>
        window.addEventListener('DOMContentLoaded', () => {
            // Berikan jeda super singkat agar CSS ter-load sempurna sebelum dialog muncul
            setTimeout(() => {
                window.print();
            }, 300);
        });
    </script>
    
    </body>
    </html>