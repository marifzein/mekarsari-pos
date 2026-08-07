@extends('layouts.app')
@section('title', 'Retur Barang ke Supplier')
@section('content')

<x-page-header title="Retur Barang" subtitle="Daftar pengembalian barang rusak/overstock ke pihak rekanan supplier">
    <x-slot:action>
        <a href="{{ route('retur.create') }}">
            <x-button color="green" type="button">
                <i class="ri-add-line"></i> Buat Retur Baru
            </x-button>
        </a>
    </x-slot:action>
</x-page-header>

<x-card class="mb-6">
    <form method="GET" action="{{ route('retur.index') }}" class="flex gap-3 items-center">
        <div class="flex-1 max-w-md">
            <x-search-box name="search" :value="request('search')" placeholder="Cari No. Retur atau Nama Supplier..." />
        </div>
        <x-button color="gray" type="submit"><i class="ri-filter-3-line"></i> Filter</x-button>
        @if(request('search'))
            <a href="{{ route('retur.index') }}"><x-button color="red" type="button">Reset</x-button></a>
        @endif
    </form>
</x-card>

<x-card>
    <x-table>
        <x-table-header>
            <tr>
                <x-table-head class="text-left">No. Retur</x-table-head>
                <x-table-head class="text-left">Tanggal Retur</x-table-head>
                <x-table-head class="text-left">Supplier</x-table-head>
                <x-table-head class="text-center">Total Macam Item</x-table-head>
                <x-table-head class="text-left">Petugas</x-table-head>
                <x-table-head class="text-center w-24">Aksi</x-table-head>
            </tr>
        </x-table-header>
        <tbody>
        @forelse($retur as $row)
            <tr class="hover:bg-slate-50 transition-colors duration-150">
                <x-table-cell class="text-left font-semibold text-slate-700">{{ $row->no_retur }}</x-table-cell>
                <x-table-cell class="text-left text-slate-600">{{ \Carbon\Carbon::parse($row->tanggal_retur)->format('d M Y') }}</x-table-cell>
                <x-table-cell class="text-left text-slate-700 font-medium">{{ $row->supplier_name }}</x-table-cell>
                <x-table-cell class="text-center"><x-badge color="purple">{{ $row->total_item }} Produk</x-badge></x-table-cell>
                <x-table-cell class="text-left text-slate-600"></i>{{ $row->kasir_name }}</x-table-cell>
                <x-table-cell class="text-center">
                    <a href="{{ route('retur.show', $row->id) }}">
                        <button type="button" class="inline-flex items-center justify-center w-8 h-8 rounded-lg bg-slate-100 text-slate-600 hover:bg-blue-600 hover:text-white transition">
                            <i class="ri-eye-line text-base"></i>
                        </button>
                    </a>
                </x-table-cell>
            </tr>
        @empty
            <tr>
                <td colspan="6">
                    <x-empty-state icon="ri-arrow-go-back-line" title="Belum ada transaksi retur" description="Data pengembalian barang ke supplier akan tercatat di sini secara permanen." />
                </td>
            </tr>
        @endforelse
        </tbody>
    </x-table>
    
    @if($retur->hasPages())
        <div class="mt-4 px-2">{{ $retur->links() }}</div>
    @endif
</x-card>
@endsection