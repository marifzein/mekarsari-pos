@extends('layouts.app')

@section('title', 'Rincian Penerimaan Barang')

@section('content')

<x-page-header title="Rincian Penerimaan Barang" :subtitle="'No. Bukti: ' . $penerimaan->no_penerimaan">
    <x-slot:action>
        <div class="flex gap-2">
            <a href="{{ route('penerimaan.index') }}">
                <x-button color="gray" type="button">
                    <i class="ri-arrow-left-line"></i> Kembali
                </x-button>
            </a>
            <a href="{{ route('penerimaan.print', $penerimaan->id) }}" target="_blank">
                <x-button color="blue" type="button">
                    <i class="ri-printer-line"></i> Cetak Bukti PDF
                </x-button>
            </a>
        </div>
    </x-slot:action>
</x-page-header>

{{-- 1. Card Informasi Dokumen Penerimaan --}}
<x-card class="mb-6 print:shadow-none print:border-none">
    <div class="grid md:grid-cols-2 gap-6">
        {{-- SISI KIRI: DATA RUJUKAN --}}
        <div class="space-y-4">
            <div>
                <span class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-1">No. Penerimaan</span>
                <div class="text-base font-black text-slate-800 flex items-center gap-2">
                    <i class="ri-barcode-box-line text-blue-600 text-lg"></i>
                    {{ $penerimaan->no_penerimaan }}
                </div>
            </div>

            <div>
                <span class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-1">No. Rujukan PO</span>
                <div class="text-sm font-semibold text-slate-700">
                    {{ $penerimaan->no_po ?? 'Penerimaan Bebas (Non-PO)' }}
                </div>
            </div>

            <div>
                <span class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-1">No. Dokumen / Nota Supplier</span>
                <div class="text-sm font-semibold text-slate-700">
                    {{ $penerimaan->no_dokumen_supplier ?? '-' }}
                </div>
            </div>
        </div>

        {{-- SISI KANAN: SUPPLIER & LOGISTIK --}}
        <div class="space-y-4">
            <div>
                <span class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-1">Supplier</span>
                <div class="text-sm font-bold text-slate-800 flex items-center gap-1.5">
                    <i class="ri-store-3-line text-indigo-600"></i>
                    {{ $penerimaan->supplier_name }}
                </div>
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <span class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-1">Tanggal Terima</span>
                    <div class="text-sm font-semibold text-slate-700">
                        {{ \Carbon\Carbon::parse($penerimaan->tanggal_terima)->format('d F Y') }}
                    </div>
                </div>
                <div>
                    <span class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-1">Petugas Penerima</span>
                    <div class="text-sm font-semibold text-slate-700 flex items-center gap-1">
                        <i class="ri-user-smile-line text-emerald-600"></i>
                        {{ $penerimaan->kasir_name }}
                    </div>
                </div>
            </div>

            <div>
                <span class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-1">Catatan Penerimaan</span>
                <div class="text-sm text-slate-600 bg-slate-50 p-2.5 rounded-xl border border-slate-100 min-h-[48px]">
                    {{ $penerimaan->catatan ?? 'Tidak ada catatan tambahan.' }}
                </div>
            </div>
        </div>
    </div>
</x-card>

{{-- 2. Card Rincian Barang --}}
<x-card class="print:shadow-none print:border-none">
    <div class="mb-4">
        <h3 class="font-bold text-slate-800 text-lg flex items-center gap-2">
            <i class="ri-clipboard-line text-blue-600"></i>
            Daftar Barang Masuk Gudang
        </h3>
    </div>

    <x-table>
        <x-table-header>
            <tr>
                <x-table-head class="text-left w-12">No</x-table-head>
                <x-table-head class="text-left">Nama Produk</x-table-head>
                <x-table-head class="text-center w-28">Qty PO</x-table-head>
                <x-table-head class="text-center w-28">Qty Terima</x-table-head>
                <x-table-head class="text-right w-40">Harga Beli</x-table-head>
                <x-table-head class="text-right w-44">Subtotal</x-table-head>
            </tr>
        </x-table-header>

        <tbody>
            @php $grandTotal = 0; @endphp
            @foreach($items as $index => $item)
                @php 
                    $subtotal = $item->qty_terima * $item->harga_beli; 
                    $grandTotal += $subtotal;
                @endphp
                <tr class="hover:bg-slate-50 border-b">
                    <td class="p-3 text-sm text-slate-500 font-semibold">{{ $index + 1 }}</td>
                    <td class="p-3 text-sm">
                        <div class="font-bold text-slate-800">{{ $item->nama_barang }}</div>
                        <div class="text-xs text-slate-400 font-medium">{{ $item->kode_barang }}</div>
                        @if($item->qty_po == 0)
                            <x-badge color="purple" class="mt-1 text-[10px] px-1.5 py-0.5">Item Luar PO</x-badge>
                        @endif
                    </td>
                    <td class="p-3 text-center text-slate-500 font-bold">{{ $item->qty_po }}</td>
                    <td class="p-3 text-center text-blue-600 font-black text-base">{{ $item->qty_terima }}</td>
                    <td class="p-3 text-right text-slate-700 font-semibold">Rp{{ number_format($item->harga_beli, 0, ',', '.') }}</td>
                    <td class="p-3 text-right text-slate-900 font-extrabold">Rp{{ number_format($subtotal, 0, ',', '.') }}</td>
                </tr>
            @endforeach
        </tbody>
    </x-table>

    {{-- Grand Total Label --}}
    <div class="mt-6 pt-4 border-t border-slate-100 flex justify-end items-center">
        <div class="text-right">
            <span class="text-slate-800 text-sm font-bold mr-2">GRAND TOTAL NOTA:</span>
            <div class="text-3xl font-black text-blue-600 font-bold ">
                Rp{{ number_format($grandTotal, 0, ',', '.') }}
            </div>
        </div>
    </div>
</x-card>

@endsection