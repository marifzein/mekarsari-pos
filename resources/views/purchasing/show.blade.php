@extends('layouts.app')

@section('title', 'Detail Purchase Order')

@section('content')

<x-page-header
    title="View Purchase Order"
    subtitle="Informasi detail data PO"
>
    <x-slot:action>
        <a href="{{ route('purchasing.index') }}">
            <x-button color="gray" type="button">
                <i class="ri-arrow-left-line"></i> Kembali
            </x-button>
        </a>
    </x-slot:action>
</x-page-header>

<x-card>
    <div class="grid md:grid-cols-3 gap-6">
        <x-input
            label="Nomor PO"
            name="po_number"
            readonly
            :value="$po->po_number"
            icon="ri-file-list-3-line"
        />

        <x-input
            label="Tanggal"
            name="po_date"
            type="date"
            readonly
            :value="$po->po_date"
        />

        <x-input
            label="Supplier"
            name="supplier_nama"
            readonly
            :value="$po->supplier->nama"
            icon="ri-truck-line"
        />
    </div>
</x-card>

<div class="grid grid-cols-1 gap-6 mt-6">
    <x-card>
        <div class="flex justify-between items-center mb-4">
            <div>
                <h3 class="font-semibold">Item Purchase Order</h3>
                <p class="text-sm text-slate-500">Produk terdaftar dalam pesanan</p>
            </div>
            <div>
                <span class="px-4 py-2 rounded-xl text-sm font-semibold 
                    @if($po->status === 'DRAFT') bg-slate-100 text-slate-700 
                    @elseif($po->status === 'ORDERED') bg-blue-100 text-blue-700 
                    @elseif($po->status === 'RECEIVED') bg-green-100 text-green-700 
                    @else bg-red-100 text-red-700 @endif">
                    Status: {{ $po->status }}
                </span>
            </div>
        </div>

        <x-table>
            <x-table-header>
                <tr>
                    <x-table-head>Produk</x-table-head>
                    <x-table-head class="text-center">Qty</x-table-head>
                    <x-table-head class="text-right">Harga</x-table-head>
                    <x-table-head class="text-right">Subtotal</x-table-head>
                </tr>
            </x-table-header>

            <tbody>
                @foreach($po->items as $item)
                    <tr>
                        <x-table-cell>{{ $item->product->nama_barang }}</x-table-cell>
                        <x-table-cell class="text-center">{{ $item->qty }}</x-table-cell>
                        <x-table-cell class="text-right">Rp {{ number_format($item->price, 0, ',', '.') }}</x-table-cell>
                        <x-table-cell class="text-right">Rp {{ number_format($item->subtotal, 0, ',', '.') }}</x-table-cell>
                    </tr>
                @endforeach
            </tbody>
        </x-table>

        <div class="border-t mt-6 pt-6">
            @if($po->notes)
                <div class="mb-4 text-sm text-slate-600">
                    <strong>Catatan:</strong> {{ $po->notes }}
                </div>
            @endif

            <div class="flex justify-end">
                <table class="text-sm">
                    <tr>
                        <td class="pr-10 py-2">Total Keseluruhan</td>
                        <td class="font-bold text-xl text-right text-indigo-600">
                            Rp {{ number_format($po->total, 0, ',', '.') }}
                        </td>
                    </tr>
                </table>
            </div>
        </div>
    </x-card>
</div>

{{-- Toolbar Aksi Bawah --}}
<div class="flex justify-end gap-3 mt-6">
    <a href="{{ route('purchasing.index') }}">
        <x-button color="secondary" type="button">
            <i class="ri-close-circle-line"></i> Tutup
        </x-button>
    </a>

    {{-- Logika Cetak: Hanya Status "ORDERED" yang Bisa --}}
    @if($po->status === 'ORDERED')
        {{-- <a href="{{ route('purchasing.print-pdf', $po) }}" target="_blank" onclick="window.print(); return false;"> --}}
        <a href="{{ route('purchasing.print-pdf', $po) }}" target="_blank">    
            <x-button color="primary" type="button">
                <i class="ri-printer-line"></i> Cetak PO
            </x-button>
        </a>
    @else
        {{-- <x-button color="gray" type="button" class="opacity-50 cursor-not-allowed" title="Cetak hanya tersedia jika status ORDERED">
            <i class="ri-printer-line"></i> Cetak PO (Hanya Status Ordered)
        </x-button> --}}
    @endif
</div>

@endsection