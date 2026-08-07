@extends('layouts.app')
@section('title', 'Detail Retur Barang')
@section('content')

<x-page-header title="Rincian Retur Barang" :subtitle="'No. Bukti Retur: ' . $retur->no_retur">
    <x-slot:action>
        <div class="flex gap-2">
            
                <a href="{{ route('retur.index') }}">
                    <x-button color="gray" type="button"><i class="ri-arrow-left-line"></i> Kembali</x-button>
                </a>
            
            <a href="{{ route('retur.print', $retur->id) }}" target="_blank">
                <x-button color="purple" type="button">
                    <i class="ri-printer-line"></i> Cetak Dokumen PDF
                </x-button>
            </a>
        </div>
    </x-slot:action>
</x-page-header>

<x-card class="mb-6 print:shadow-none print:border-none">
    <div class="grid md:grid-cols-2 gap-6">
        <div class="space-y-4">
            <div>
                <span class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-1">No. Bukti Retur</span>
                <div class="text-base font-black text-purple-600 flex items-center gap-2">
                    <i class="ri-file-shield-2-line text-lg"></i> {{ $retur->no_retur }}
                </div>
            </div>
            <div>
                <span class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-1">Supplier Tujuan</span>
                <div class="text-sm font-bold text-slate-800"><i class="ri-store-3-line text-indigo-600 mr-1"></i>{{ $retur->supplier_name }}</div>
            </div>
        </div>
        <div class="space-y-4">
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <span class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-1">Tanggal Keluar</span>
                    <div class="text-sm font-semibold text-slate-700">{{ \Carbon\Carbon::parse($retur->tanggal_retur)->format('d F Y') }}</div>
                </div>
                <div>
                    <span class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-1">Petugas Verifikasi</span>
                    <div class="text-sm font-semibold text-slate-700"><i class="ri-user-smile-line text-emerald-600 mr-1"></i>{{ $retur->kasir_name }}</div>
                </div>
            </div>
            <div>
                <span class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-1">Alasan Pengembalian</span>
                <div class="text-sm text-slate-600 bg-slate-50 p-2.5 rounded-xl border border-slate-100 min-h-[48px]">{{ $retur->catatan ?? 'Tidak ada catatan.' }}</div>
            </div>
        </div>
    </div>
</x-card>

<x-card class="print:shadow-none print:border-none">
    <div class="mb-4">
        <h3 class="font-bold text-slate-800 text-lg flex items-center gap-2"><i class="ri-clipboard-line text-purple-600"></i> Daftar Barang Keluar Retur</h3>
    </div>

    <x-table>
        <x-table-header>
            <tr>
                <x-table-head class="text-left w-12">No</x-table-head>
                <x-table-head class="text-left">Nama Produk</x-table-head>
                <x-table-head class="text-center w-36">Jumlah Diretur</x-table-head>
                <x-table-head class="text-right w-44">Harga Satuan Retur</x-table-head>
                <x-table-head class="text-right w-44">Subtotal</x-table-head>
            </tr>
        </x-table-header>
        <tbody>
            @php $grandTotal = 0; @endphp
            @foreach($items as $index => $item)
                @php $subtotal = $item->qty_retur * $item->harga_beli; $grandTotal += $subtotal; @endphp
                <tr class="hover:bg-slate-50 border-b">
                    <td class="p-3 text-sm text-slate-500 font-semibold">{{ $index + 1 }}</td>
                    <td class="p-3 text-sm">
                        <div class="font-bold text-slate-800">{{ $item->nama_barang }}</div>
                        <div class="text-xs text-slate-400 font-medium">{{ $item->kode_barang }}</div>
                    </td>
                    <td class="p-3 text-center text-red-600 font-black text-base">{{ $item->qty_retur }}</td>
                    <td class="p-3 text-right text-slate-700 font-semibold">Rp{{ number_format($item->harga_beli, 0, ',', '.') }}</td>
                    <td class="p-3 text-right text-slate-900 font-extrabold">Rp{{ number_format($subtotal, 0, ',', '.') }}</td>
                </tr>
            @endforeach
        </tbody>
    </x-table>

    <div class="mt-6 pt-4 border-t border-slate-100 flex justify-end items-center">
        <div class="text-right">
            <span class="text-slate-800 text-sm font-bold mr-2">TOTAL NILAI RETUR:</span>
            <div class="text-3xl font-black text-purple-600">Rp{{ number_format($grandTotal, 0, ',', '.') }}</div>
        </div>
    </div>
</x-card>
@endsection