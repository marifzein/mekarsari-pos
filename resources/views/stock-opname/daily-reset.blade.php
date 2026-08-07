@extends('layouts.app')

@section('title', 'Reset Stok Harian (Resto)')

@section('content')
<x-page-header
    title="Reset Stok Harian Resto"
    subtitle="Modul pembersihan sisa bahan & produk untuk dipaksa menjadi NOL di akhir shift."
/>

@if(session('success'))
    <x-alert type="success">{{ session('success') }}</x-alert>
@endif

@if(session('error'))
    <x-alert type="error">{{ session('error') }}</x-alert>
@endif

@if($errors->any())
    <x-alert type="error">
        <ul class="list-disc ml-5">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </x-alert>
@endif

<!-- Bungkus Form Utama menggunakan state Alpine.js -->
<div x-data="{ 
    checkedItems: [],
    initData() {
        // Ambil semua ID produk yang dirender di halaman awal untuk keperluan select all
        this.allIds = [ @foreach($products as $p) '{{ $p->id }}', @endforeach ];
    },
    allIds: [],
    toggleAll() {
        if (this.checkedItems.length === this.allIds.length) {
            this.checkedItems = [];
        } else {
            this.checkedItems = [...this.allIds];
        }
    }
}" x-init="initData()">

    <form method="POST" action="{{ route('daily-reset.store') }}">
        @csrf

        <!-- Bagian Input Catatan Harian -->
        <x-card class="mb-6">
            <div class="max-w-xl space-y-4">
                <x-input 
                    label="Alasan / Catatan Reset" 
                    name="notes" 
                    placeholder="Contoh: Pembersihan sisa bahan makanan akhir hari / produk rusak" 
                    value="{{ old('notes') }}"
                    required
                />
                
                <div class="flex items-center gap-2">
                    <x-button color="primary" type="submit" ::disabled="checkedItems.length === 0">
                        <i class="ri-refresh-line"></i> Mulai Proses Reset (<span x-text="checkedItems.length">0</span> Produk)
                    </x-button>
                </div>
            </div>
        </x-card>

        <!-- Tabel Daftar Produk -->
        <x-card>
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-slate-50 border-b border-slate-100 text-slate-600 font-semibold text-sm">
                            <th class="p-4 w-12">No</th>
                            <th class="p-4 w-16 text-center">
                                <!-- Aksi Check All / Uncheck All -->
                                <button type="button" @click="toggleAll()" class="px-2 py-1 text-xs bg-indigo-50 text-indigo-600 hover:bg-indigo-100 rounded border border-indigo-200 font-medium">
                                    <span x-text="checkedItems.length === allIds.length ? 'Uncheck All' : 'Check All'">Check All</span>
                                </button>
                            </th>
                            <th class="p-4">Kode Barang</th>
                            <th class="p-4">Nama Barang</th>
                            <th class="p-4 text-center">Stok Saat Ini</th>
                            <th class="p-4">Satuan</th>
                        </tr>
                    </thead>
                    <tbody class="text-slate-700 text-sm divide-y divide-slate-50">
                        @forelse($products as $index => $product)
                            <tr class="hover:bg-slate-50/50 transition">
                                <td class="p-4 text-slate-400">{{ $index + 1 }}</td>
                                <td class="p-4 text-center">
                                    <input 
                                        type="checkbox" 
                                        name="product_ids[]" 
                                        value="{{ $product->id }}"
                                        x-model="checkedItems"
                                        class="w-4 h-4 rounded text-indigo-600 border-slate-300 focus:ring-indigo-500 cursor-pointer"
                                    />
                                </td>
                                <td class="p-4 font-mono font-medium text-slate-900">{{ $product->kode_barang }}</td>
                                <td class="p-4 font-medium">{{ $product->nama_barang }}</td>
                                <td class="p-4 text-center font-semibold text-amber-600 bg-amber-50/50">{{ $product->stok }}</td>
                                <td class="p-4 text-slate-500">{{ $product->satuan }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="p-8 text-center text-slate-400">
                                    <i class="ri-inbox-line text-3xl block mb-2"></i>
                                    Tidak ada produk aktif dengan stok di atas 0 malam ini.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </x-card>
    </form>
</div>
@endsection