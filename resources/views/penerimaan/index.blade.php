@extends('layouts.app')

@section('title', 'Penerimaan Barang')

@section('content')

<div x-data="{ openModalPo: false }">
    {{-- Header Halaman --}}
    <x-page-header title="Penerimaan Barang" subtitle="Riwayat pembelian dan penerimaan PO supplier">
        <x-slot:action>
            <div class="flex gap-2">
                <x-button color="blue" type="button" @click="openModalPo = true">
                    <i class="ri-file-list-3-line"></i> Ambil Dari PO Supplier
                </x-button>

                <a href="{{ route('penerimaan.create') }}">
                    <x-button color="green" type="button">
                        <i class="ri-add-line"></i> Penerimaan Langsung (Non-PO)
                    </x-button>
                </a>
            </div>
        </x-slot:action>
    </x-page-header>

    {{-- Filter Pencarian --}}
    <x-card class="mb-6">
        <form method="GET" action="{{ route('penerimaan.index') }}" class="flex gap-3 items-center">
            <div class="flex-1 max-w-md">
                <x-search-box name="search" :value="request('search')" placeholder="Cari No. Penerimaan, No. PO, atau Ref Supplier..." />
            </div>
            <x-button color="gray" type="submit"><i class="ri-filter-3-line"></i> Filter</x-button>
            @if(request('search'))
                <a href="{{ route('penerimaan.index') }}"><x-button color="red" type="button">Reset</x-button></a>
            @endif
        </form>
    </x-card>

    {{-- Tabel Riwayat --}}
    <x-card>
        <x-table>
            <x-table-header>
                <tr>
                    <x-table-head class="text-left">No. Penerimaan</x-table-head>
                    <x-table-head class="text-left">Tanggal Terima</x-table-head>
                    <x-table-head class="text-left">Supplier</x-table-head>
                    <x-table-head class="text-left">Rujukan / Dokumen</x-table-head>
                    <x-table-head class="text-center">Total Item</x-table-head>
                    <x-table-head class="text-left">Petugas/Kasir</x-table-head>
                    <x-table-head class="text-center w-24">Aksi</x-table-head>
                </tr>
            </x-table-header>
            <tbody>
            @forelse($penerimaan as $row)
                <tr class="hover:bg-slate-50 transition-colors duration-150">
                    <x-table-cell class="text-left font-semibold text-slate-700">{{ $row->no_penerimaan }}</x-table-cell>
                    <x-table-cell class="text-left text-slate-600">{{ \Carbon\Carbon::parse($row->tanggal_terima)->format('d M Y') }}</x-table-cell>
                    <x-table-cell class="text-left text-slate-700 font-medium">{{ $row->supplier_name }}</x-table-cell>
                    <x-table-cell class="text-left">
                        <div class="text-xs text-slate-600"><span class="font-semibold text-slate-400">PO:</span> {{ $row->no_po ?? '-' }}</div>
                        <div class="text-xs text-slate-600 mt-0.5"><span class="font-semibold text-slate-400">Ref:</span> {{ $row->no_dokumen_supplier ?? '-' }}</div>
                    </x-table-cell>
                    <x-table-cell class="text-center"><x-badge color="blue">{{ $row->total_item }} Item</x-badge></x-table-cell>
                    <x-table-cell class="text-left text-slate-600 font-medium"><i class="ri-user-smile-line text-xs text-slate-400 mr-1"></i>{{ $row->kasir_name }}</x-table-cell>
                    
                    {{-- KOLOM AKSI TERBARU: HANYA LIHAT DETAIL --}}
                    <x-table-cell class="text-center">
                        <a href="{{ route('penerimaan.show', $row->id) }}" title="Lihat Rincian Barang">
                            <button type="button" class="inline-flex items-center justify-center w-8 h-8 rounded-lg bg-slate-100 text-slate-600 hover:bg-blue-600 hover:text-white transition duration-150">
                                <i class="ri-eye-line text-base"></i>
                            </button>
                        </a>
                    </x-table-cell>
                </tr>
            @empty
                <tr>
                    <td colspan="7">
                        <x-empty-state icon="ri-download-2-line" title="Belum ada riwayat penerimaan" description="Seluruh transaksi pembelian atau penerimaan PO dari supplier akan muncul di sini." />
                    </td>
                </tr>
            @endforelse
            </tbody>
        </x-table>
        
        @if($penerimaan->hasPages())
            <div class="mt-4 px-2">
                {{ $penerimaan->links() }}
            </div>
        @endif
    </x-card>

    {{-- MODAL POPUP: PILIH PO SUPPLIER --}}
    <div x-show="openModalPo" class="fixed inset-0 z-50 overflow-y-auto" style="display: none;" x-transition>
        <div class="flex items-center justify-center min-h-screen p-4 text-center">
            <div class="fixed inset-0 bg-slate-900 bg-opacity-50 transition-opacity" @click="openModalPo = false"></div>

            <div class="relative bg-white rounded-2xl max-w-4xl w-full p-6 text-left shadow-xl transform transition-all overflow-hidden">
                <div class="flex justify-between items-center mb-4 pb-2 border-b">
                    <h3 class="text-lg font-bold text-slate-800"><i class="ri-file-list-3-line text-blue-600 mr-1"></i> Ambil Rujukan Purchase Order (PO)</h3>
                    <button @click="openModalPo = false" class="text-slate-400 hover:text-slate-600"><i class="ri-close-line text-2xl"></i></button>
                </div>

                <div class="max-h-[450px] overflow-y-auto">
                    <table class="w-full text-sm text-left text-slate-600">
                        <thead class="text-xs uppercase bg-slate-100 text-slate-700 sticky top-0">
                            <tr>
                                <th class="p-3">No. PO</th>
                                <th class="p-3">Tanggal PO</th>
                                <th class="p-3">Supplier</th>
                                <th class="p-3 text-right">Total Estimasi</th>
                                <th class="p-3 text-center">Status</th>
                                <th class="p-3 text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($purchaseOrders as $po)
                            <tr class="border-b hover:bg-slate-50">
                                <td class="p-3 font-bold text-slate-800">{{ $po->po_number }}</td>
                                <td class="p-3">{{ \Carbon\Carbon::parse($po->po_date)->format('d/m/Y') }}</td>
                                <td class="p-3 font-medium">{{ $po->supplier->nama ?? '-' }}</td>
                                <td class="p-3 text-right font-semibold">Rp{{ number_format($po->total, 0) }}</td>
                                <td class="p-3 text-center">
                                    <x-badge color="blue">Ordered</x-badge>
                                </td>
                                <td class="p-3 text-center">
                                    <a href="{{ route('penerimaan.create', ['po_number' => $po->po_number]) }}" class="inline-block px-3 py-1 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-lg text-xs transition">
                                        <i class="ri-file-add-line"></i> Buat Penerimaan
                                    </a>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="p-8 text-center italic text-slate-400">
                                    <div class="text-base font-semibold text-slate-500 mb-1">Tidak ada PO berstatus ORDERED</div>
                                    <span class="text-xs">Seluruh Purchase Order aktif sudah diproses atau dibatalkan.</span>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection