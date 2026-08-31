@extends('layouts.app')

@section('title', 'Stock Opname')

@section('content')

{{-- Header Page --}}
<x-page-header 
    title="Stock Opname" 
    subtitle="No SO: {{ $stockOpname->opname_no }} | Tanggal: {{ \Carbon\Carbon::parse($stockOpname->opname_date)->format('d-m-Y H:i') }}"
>
    <x-slot:action>
        <div class="flex items-center gap-3">
            @if($stockOpname->status == 'OPEN')
                <span class="bg-amber-100 text-amber-800 px-3 py-1.5 rounded-full text-xs font-bold tracking-wider">OPEN</span>
            @else
                <span class="bg-emerald-100 text-emerald-800 px-3 py-1.5 rounded-full text-xs font-bold tracking-wider">POSTED</span>
            @endif

            <a href="{{ url('/stock-opname') }}">
                <x-button color="gray">
                    <i class="ri-arrow-left-line"></i> Kembali
                </x-button>
            </a>
        </div>
    </x-slot:action>
</x-page-header>

{{-- Alert Notification --}}
@if(session('success'))
    <x-alert type="success" class="mb-4">
        {{ session('success') }}
    </x-alert>
@endif

@if(session('warning'))
    <x-alert type="warning" class="mb-4">
        {{ session('warning') }}
    </x-alert>
@endif

<div x-data="stockAdjustment()">
    
    {{-- Form Area Menggunakan Komponen Card --}}
    @if($stockOpname->status == 'OPEN')
        <x-card class="mb-6">
            <form id="formScanSO" method="POST" action="{{ url('/stock-opname/' . $stockOpname->id) }}">
                @csrf

                {{-- 1. Input Scan / Cari Barang (Full Width) --}}
                <div class="relative mb-6">
                    <label class="block text-sm font-semibold text-slate-700 mb-2">
                        <i class="ri-scan-2-line text-indigo-600 mr-1"></i> Scan Barcode / Cari Barang
                    </label>
                    
                    <div class="relative group">
                        <i class="ri-search-line absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 group-focus-within:text-indigo-600 transition-all text-lg"></i>
                        <input
                            type="text"
                            x-model="keyword"
                            x-ref="barcodeInput"
                            @if($stockOpname->status == 'POSTED') disabled @endif
                            @input="searchProduct"
                            @keydown.arrow-down.prevent="if (results.length > 0) selectedIndex = (selectedIndex + 1) % results.length"
                            @keydown.arrow-up.prevent="if (results.length > 0) selectedIndex = (selectedIndex - 1 + results.length) % results.length"
                            @keydown.enter.prevent="
                                if (selectedIndex >= 0 && results[selectedIndex]) { 
                                    selectProduct(results[selectedIndex]); 
                                } else { 
                                    searchProduct(); 
                                }
                            "
                            @keydown.escape="results = []; selectedIndex = -1;"
                            placeholder="Scan barcode atau ketik nama barang..."
                            class="w-full rounded-xl border border-slate-300 pl-12 pr-4 py-3 text-slate-700 placeholder:text-slate-400 focus:border-indigo-500 focus:ring-4 focus:ring-indigo-100 outline-none transition-all duration-200"
                        >
                    </div>

                    {{-- Dropdown Result --}}
                    <div
                        x-show="results.length"
                        class="absolute z-30 left-0 right-0 border border-slate-200 rounded-xl mt-2 bg-white shadow-xl max-h-60 overflow-y-auto divide-y divide-slate-100"
                    >
                        <template x-for="(item, index) in results" :key="item.id">
                            <div
                                class="p-3 hover:bg-indigo-50/50 cursor-pointer transition-colors"
                                :class="{ 'bg-indigo-50 font-semibold': selectedIndex === index }"
                                @click="selectProduct(item)"
                                @mouseenter="selectedIndex = index"
                            >
                                <div class="text-sm font-bold text-slate-800" x-text="item.nama_barang"></div>
                                <small class="text-slate-500 text-xs">
                                    Kode: <span class="font-mono" x-text="item.kode_barang"></span> | 
                                    Stok Sistem: <span class="font-bold text-slate-700" x-text="item.stok"></span>
                                </small>
                            </div>
                        </template>
                    </div>
                </div>

                <input type="hidden" name="product_id" :value="selected.id">

                {{-- 2. Grid 2 Kolom (Kiri: Info Produk, Kanan: Input Fisik) --}}
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 pt-4 border-t border-slate-200">
                    
                    {{-- Kolom Kiri: Readonly Informasi Produk --}}
                    <div class="space-y-4 bg-slate-50/70 p-4 rounded-xl border border-slate-200/80">
                        <div class="font-semibold text-xs uppercase tracking-wider text-slate-400">Informasi Produk</div>
                        
                        <x-input
                            label="Kode Barang"
                            name="readonly_kode"
                            icon="ri-qr-code-line"
                            x-bind:value="selected.kode_barang ?? '-'"
                            readonly
                        />

                        <x-input
                            label="Nama Barang"
                            name="readonly_nama"
                            icon="ri-box-3-line"
                            x-bind:value="selected.nama_barang ?? '-'"
                            readonly
                        />

                        <x-input
                            label="Stok Sistem"
                            name="readonly_stok"
                            icon="ri-archive-line"
                            x-bind:value="selected.stok ?? 0"
                            readonly
                        />
                    </div>

                    {{-- Kolom Kanan: Penyesuaian Fisik --}}
                    <div class="space-y-4 bg-slate-50/70 p-4 rounded-xl border border-slate-200/80">
                        <div class="font-semibold text-xs uppercase tracking-wider text-slate-400">Penyesuaian Fisik</div>

                        <div>
                            <label class="block text-sm font-semibold text-slate-700 mb-2">
                                <span x-text="getScannedItem(selected.id) ? 'Jumlah Ditemukan' : 'Stok Fisik'"></span>
                                <span class="text-red-500">*</span>
                            </label>
                            <div class="relative group">
                                <i class="ri-hashtag absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 group-focus-within:text-indigo-600 text-lg"></i>
                                <input
                                    type="number"
                                    name="stok_fisik"
                                    x-model="stokFisik"
                                    x-ref="stokFisikInput"
                                    @keydown.enter.prevent="document.getElementById('formScanSO').requestSubmit()"
                                    @if($stockOpname->status == 'POSTED') disabled @endif
                                    required
                                    class="w-full rounded-xl border pl-12 pr-4 py-3 font-bold text-slate-800 focus:border-indigo-500 focus:ring-4 focus:ring-indigo-100 outline-none transition-all duration-200"
                                    :class="getScannedItem(selected.id) ? 'border-purple-300 bg-purple-50/50' : 'border-slate-300 bg-white'"
                                >
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-slate-700 mb-2">Selisih Real</label>
                            <input
                                readonly
                                class="w-full rounded-xl border border-slate-300 px-4 py-3 font-extrabold text-base outline-none transition-all duration-200"
                                :class="{
                                    'text-emerald-600 bg-emerald-50 border-emerald-300': getComputedSelisih() > 0,
                                    'text-rose-600 bg-rose-50 border-rose-300': getComputedSelisih() < 0,
                                    'text-slate-600 bg-slate-100': getComputedSelisih() == 0
                                }"
                                :value="getComputedSelisih()"
                            >
                        </div>

                        <x-input
                            label="Catatan / Keterangan"
                            name="notes"
                            icon="ri-file-text-line"
                            placeholder="Opsional (misal: Kemasan rusak, dll)"
                            :readonly="$stockOpname->status == 'POSTED'"
                        />
                    </div>

                </div>

                {{-- Action Submit --}}
                @if($stockOpname->status == 'OPEN')
                    <div class="flex justify-end mt-6 pt-4 border-t border-slate-200">
                        <x-button color="primary" type="submit">
                            <i class="ri-save-line"></i> Simpan Stok Opname
                        </x-button>
                    </div>
                @endif
            </form>
        </x-card>
    @endif
    
    {{-- Data Tabel Riwayat Scan --}}
    <x-card>
        <div class="font-semibold text-slate-700 mb-4 flex items-center gap-2">
            <i class="ri-history-line text-indigo-600 text-lg"></i> Barang yang Sudah Di-Scan
        </div>

        <div class="overflow-x-auto rounded-xl border border-slate-200">
            <table class="w-full text-left text-sm text-slate-600">
                <thead class="bg-slate-50 text-slate-700 font-semibold border-b border-slate-200">
                    <tr>
                        <th class="p-3">Kode</th>
                        <th class="p-3">Nama Barang</th>
                        <th class="p-3 text-center">Sistem</th>
                        <th class="p-3 text-center">Fisik</th>
                        <th class="p-3 text-center">Selisih</th>
                    </tr>
                </thead>
                <tbody id="tbodySO" class="divide-y divide-slate-100">
                    @forelse($details as $item)
                        <tr class="hover:bg-slate-50/50 transition">
                            <td class="p-3 font-mono text-slate-500">{{ $item->product->kode_barang }}</td>
                            <td class="p-3 font-semibold text-slate-800">{{ $item->product->nama_barang }}</td>
                            <td class="p-3 text-center font-medium">{{ $item->stock_system }}</td>
                            <td class="p-3 text-center font-bold text-indigo-600">{{ $item->stock_physical }}</td>
                            <td class="p-3 text-center font-bold">
                                <span class="{{ $item->difference > 0 ? 'text-emerald-600' : ($item->difference < 0 ? 'text-rose-600' : 'text-slate-500') }}">
                                    {{ $item->difference }}
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center py-8 text-slate-400">
                                <i class="ri-inbox-line text-2xl block mb-1"></i>
                                Belum ada barang yang di-scan.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Footer Action Bar --}}
        <div class="mt-6 pt-4 border-t border-slate-200 flex items-center justify-end gap-3">
            <div>
                @if($stockOpname->status == 'OPEN')    
                    <form id="formFinishSO" method="POST" action="{{ url('/stock-opname/' . $stockOpname->id . '/finish') }}">
                        @csrf
                        <x-button color="primary" type="submit">
                            <i class="ri-check-double-line"></i> Posting Stock Opname
                        </x-button>
                    </form>
                @endif
            </div>

            @if($stockOpname->status == 'POSTED')  
            <a href="{{ url('/stock-opname/' . $stockOpname->id . '/print') }}" target="_blank">
                <x-button color="orange">
                    <i class="ri-printer-line"></i> Cetak
                </x-button>
            </a>
            @endif
        </div>
    </x-card>
</div>

@push('scripts')
<script>
    function stockAdjustment() {
        return {
            keyword: '',
            results: [],
            selected: {},
            stokFisik: 0,
            selectedIndex: -1,
            
            scannedItems: @json($details->map(function($d) {
                return [
                    'product_id' => $d->product_id,
                    'stock_system' => $d->stock_system,
                    'stock_physical' => $d->stock_physical
                ];
            })),

            init() {
                this.$watch('selected.id', (id) => {
                    if (!id) return;
                    requestAnimationFrame(() => {
                        this.$refs.stokFisikInput?.focus();
                        this.$refs.stokFisikInput?.select();
                    });
                });

                this.$nextTick(() => {
                    this.$refs.barcodeInput?.focus();
                });
            },
            
            async searchProduct() {
                if(this.keyword.length < 1) {
                    this.results = [];
                    this.selectedIndex = -1;
                    return;
                }

                let r = await fetch('/api/products/search?q=' + encodeURIComponent(this.keyword));
                this.results = await r.json();
                this.selectedIndex = -1;

                if(this.results.length == 1) {
                    this.selectProduct(this.results[0]);
                }
            },

            getScannedItem(productId) {
                return this.scannedItems.find(item => item.product_id === productId);
            },

            selectProduct(item) {
                this.selected = item;
                let history = this.getScannedItem(item.id);
                if (history) {
                    this.stokFisik = 0; 
                } else {
                    this.stokFisik = item.stok;
                }

                this.results = [];
                this.keyword = item.nama_barang;
                this.selectedIndex = -1;
            },

            getComputedSelisih() {
                if (!this.selected.id) return 0;
                
                let history = this.getScannedItem(this.selected.id);
                let qtyDitemukanSekarang = parseInt(this.stokFisik || 0);
                
                if (history) {
                    let totalFisikBaru = parseInt(history.stock_physical) + qtyDitemukanSekarang;
                    let stokSistemAwal = parseInt(history.stock_system);
                    return totalFisikBaru - stokSistemAwal; 
                } else {
                    let systemAwal = parseInt(this.selected.stok ?? 0);
                    return qtyDitemukanSekarang - systemAwal;
                }
            },

            resetForm() {
                this.keyword = '';
                this.results = [];
                this.selected = {};
                this.stokFisik = 0;
                this.selectedIndex = -1;
                this.$nextTick(() => {
                    this.$refs.barcodeInput?.focus();
                });
            }
        }
    }

    document.getElementById('formScanSO')?.addEventListener('submit', async function(e) {
        e.preventDefault();
        let form = this;
        let url = form.action;
        let formData = new FormData(form);

        try {
            let response = await fetch(url, {
                method: 'POST',
                headers: { 'Accept': 'application/json' },
                body: formData
            });

            let result = await response.json();

            if(result.success) {
                Swal.fire({
                    toast: true,
                    position: 'top-end',
                    icon: 'success',
                    title: result.message,
                    showConfirmButton: false,
                    timer: 1200
                });

                if (result.is_update) {
                    setTimeout(() => { window.location.reload(); }, 800);
                } else {
                    tambahBaris(result.detail);
                    let alpineElement = document.querySelector('[x-data="stockAdjustment()"]');
                    if (alpineElement && window.Alpine) {
                        Alpine.$data(alpineElement).resetForm();
                    }
                    form.querySelector('input[name="notes"]').value = '';
                }
            }
        } catch(error) {
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'Terjadi kesalahan sistem: ' + error.message
            });
        }
    });

    function tambahBaris(item) {
        let tbody = document.getElementById('tbodySO');
        if (tbody.rows.length === 1 && tbody.rows[0].cells.length === 1) {
            tbody.innerHTML = '';
        }

        let diffClass = item.difference > 0 ? 'text-emerald-600' : (item.difference < 0 ? 'text-rose-600' : 'text-slate-500');

        tbody.insertAdjacentHTML(
            'beforeend',
            `
            <tr class="hover:bg-slate-50/50 transition border-t border-slate-100">
                <td class="p-3 font-mono text-slate-500">${item.kode_barang}</td>
                <td class="p-3 font-semibold text-slate-800">${item.nama_barang}</td>
                <td class="p-3 text-center font-medium">${item.stock_system}</td>
                <td class="p-3 text-center font-bold text-indigo-600">${item.stock_physical}</td>
                <td class="p-3 text-center font-bold ${diffClass}">${item.difference}</td>
            </tr>
            `
        );
    }

    document.getElementById('formFinishSO')?.addEventListener('submit', function(e) {
        e.preventDefault();
        let form = this;
        Swal.fire({
            icon: 'question',
            title: 'Posting Stock Opname?',
            html: 'Setelah diposting, <b>Stock Opname tidak dapat diedit lagi.</b>',
            showCancelButton: true,
            confirmButtonText: 'Ya, Posting',
            cancelButtonText: 'Batal',
            confirmButtonColor: '#059669'
        }).then((result) => {
            if(result.isConfirmed) form.submit();
        });
    });
</script>
@endpush
@endsection