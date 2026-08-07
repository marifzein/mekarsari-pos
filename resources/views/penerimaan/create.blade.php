@extends('layouts.app')

@section('title', 'Buat Penerimaan Barang')

@section('content')

<x-page-header title="Buat Penerimaan Barang" subtitle="Input penyesuaian barang masuk dari PO atau kulakan mandiri">
    <x-slot:action>
        <a href="{{ route('penerimaan.index') }}">
            <x-button color="gray" type="button">
                <i class="ri-arrow-left-line"></i> Kembali
            </x-button>
        </a>
    </x-slot:action>
</x-page-header>

{{-- Notifikasi Validasi Server --}}
@if ($errors->any())
    <div class="mb-4 p-4 bg-red-100 border border-red-400 text-red-700 rounded-xl">
        <ul class="list-disc pl-5 text-sm">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<form method="POST" action="{{ route('penerimaan.store') }}" id="main-form" onsubmit="return validateForm(event)">
    @csrf

    {{-- 1. Card Informasi Dokumen Penerimaan (2 Kolom Kiri Kanan & Catatan Bentang Penuh) --}}
    <x-card class="mb-6">
        <div class="grid md:grid-cols-2 gap-6">
            {{-- SISI KIRI: REFERENSI DOKUMEN --}}
            <div class="space-y-4">
                <x-input
                    label="No. Rujukan PO"
                    name="no_po"
                    readonly
                    :value="$selectedPo->po_number ?? ''"
                    icon="ri-file-list-3-line"
                    class="bg-slate-50 font-bold text-blue-600"
                />

                <x-input
                    label="No. Dokumen / Nota Supplier"
                    name="no_dokumen_supplier"
                    placeholder="No Nota / Srt Jalan Grosir"
                    icon="ri-file-paper-2-line"
                />
            </div>

            {{-- SISI KANAN: SELEKTOR SUPPLIER & TANGGAL (MENGGUNAKAN X-SELECT) --}}
            <div class="space-y-4">
                <x-select
                    label="Supplier"
                    name="supplier_id"
                    required
                    icon="ri-store-3-line"
                >
                    <option value="">-- Pilih Supplier --</option>
                    @foreach($suppliers as $sup)
                        <option value="{{ $sup->id }}" {{ isset($selectedPo) && $selectedPo->supplier_id == $sup->id ? 'selected' : '' }}>
                            {{ $sup->nama }}
                        </option>
                    @endforeach
                </x-select>

                <x-input
                    label="Tanggal Terima"
                    name="tanggal_terima"
                    type="date"
                    :value="date('Y-m-d')"
                    required
                />
            </div>

            {{-- CATATAN PENERIMAAN: BENTANG PENUH 100% (MENGGUNAKAN X-TEXTAREA) --}}
            <div class="md:col-span-2">
                <x-textarea
                    label="Catatan Penerimaan"
                    name="catatan"
                    rows="2"
                    placeholder="Keterangan logistik, kiriman parsial, barang bonus sales, dll..."
                />
            </div>
        </div>
    </x-card>

    {{-- 2. Card Transaksi Utama --}}
    <x-card>
        {{-- Input Cari Produk Masuk --}}
        <div class="mb-6">
            <div class="font-semibold mb-2 flex items-center justify-between">
                <label for="search-product" class="text-sm font-semibold text-slate-700">Cari Produk Masuk</label>
                <span class="text-xs px-2 py-0.5 bg-slate-900 text-emerald-400 font-mono rounded-md"><i class="ri-scan-2-line"></i> Scanner Active</span>
            </div>
            
            <x-input
                id="search-product"
                name="search"
                placeholder="Ketik kode / nama barang atau langsung scan barcodenya di sini..."
                icon="ri-search-line"
                autocomplete="off"
            />

            {{-- Dropdown Hasil Pencarian --}}
            <div id="product-result" class="mt-2 rounded-xl border bg-white max-h-60 overflow-y-auto hidden"></div>
        </div>

        {{-- Tabel Item --}}
        <div class="mb-4">
            <h3 class="font-semibold text-slate-800 mb-1">Daftar Item Hasil Penerimaan</h3>
            <p class="text-sm text-slate-500">Sesuaikan jumlah qty datang dan harga beli riil di nota supplier</p>
        </div>

        <x-table>
            <x-table-header>
                <tr>
                    <x-table-head class="text-left">Produk</x-table-head>
                    <x-table-head class="text-center w-24">Qty PO</x-table-head>
                    <x-table-head class="text-center w-32">Qty Terima <span class="text-red-500">*</span></x-table-head>
                    <x-table-head class="text-right w-40">Harga Beli Riil (Rp) <span class="text-red-500">*</span></x-table-head>
                    <x-table-head class="text-right w-36">Subtotal</x-table-head>
                    <x-table-head class="text-center w-16">Hapus</x-table-head>
                </tr>
            </x-table-header>

            <tbody id="penerimaan-items">
                <tr>
                    <td colspan="6">
                        <x-empty-state
                            icon="ri-download-2-line"
                            title="Belum ada item masuk"
                            description="Cari atau scan produk di atas untuk mulai memasukkan data."
                        />
                    </td>
                </tr>
            </tbody>
        </x-table>

        {{-- Area Total Saja --}}
        <div class="mt-6 pt-4 border-t border-slate-100 flex justify-end items-center">
            <div class="text-right">
                <span class="text-slate-800 text-base font-bold mr-2">GRAND TOTAL NOTA:</span>
                <div class="text-4xl font-black text-blue-600 tracking-tight" id="grand-total-label">Rp0</div>
            </div>
        </div>
    </x-card>

    {{-- Tombol Submit Form --}}
    <div class="flex justify-end gap-3 mt-6">
        <a href="{{ route('penerimaan.index') }}">
            <x-button color="secondary" type="button">
                <i class="ri-close-circle-line text-red-500"></i> Batal
            </x-button>
        </a>

        <x-button color="blue" type="submit">
            <i class="ri-save-3-line"></i> F10 - Simpan Penerimaan
        </x-button>
    </div>
</form>

@push('scripts')

<script>
const search = document.getElementById('search-product');
const result = document.getElementById('product-result');
const tableItems = document.getElementById('penerimaan-items');
const grandTotalLabel = document.getElementById('grand-total-label');
let cart = [];
let timer;
let selectedIndex = -1;


// 4. SHORTCUT KEYBOARD (F2: FOKUS INPUT, F10: SIMPAN)
document.addEventListener('keydown', function(e) {
    // Tombol F2 -> Fokus ke Input Pencarian / Barcode Scan
    if (e.key === 'F2') {
        e.preventDefault(); // Mencegah fungsi bawaan browser jika ada
        search.focus();
        search.select(); // Otomatis memblok teks di dalam agar langsung tertimpa saat diketik
    }

    // Tombol F10 -> Triger Simpan Form (Memicu validasi SweetAlert)
    if (e.key === 'F10') {
        e.preventDefault();
        // Memanggil fungsi validateForm bawaan yang sudah kita buat di atas
        validateForm(e);
    }
});


document.addEventListener('DOMContentLoaded', function() {
    @if(isset($selectedPo))
        @foreach($selectedPo->purchaseOrderItems as $poItem)
        cart.push({
            id: {{ $poItem->product_id }},
            code: '{{ $poItem->product->kode_barang }}',
            name: '{{ $poItem->product->nama_barang }}',
            qty_po: {{ $poItem->qty }},
            qty_terima: {{ $poItem->qty }},
            harga_beli: {{ $poItem->price }}
        });
        @endforeach
        renderTable();
    @endif
    search.focus();
});

function clearSearch() {
    result.innerHTML = '';
    result.classList.add('hidden');
    search.value = '';
    selectedIndex = -1;
    search.focus();
}

function addToCart(id, name, code, price) {
    const existing = cart.find(x => x.id == id);
    if (existing) {
        existing.qty_terima++;
    } else {
        cart.push({ id: id, code: code, name: name, qty_po: 0, qty_terima: 1, harga_beli: price });
    }
    renderTable();
    clearSearch();
}

// 1. NAVIGASI KEYBOARD PENCARIAN PRODUK
search.addEventListener('keyup', function(e) {
    if (['ArrowUp', 'ArrowDown', 'Enter'].includes(e.key)) return;
    clearTimeout(timer);
    const q = this.value.trim();
    if (q.length < 2) { result.innerHTML = ''; result.classList.add('hidden'); selectedIndex = -1; return; }

    timer = setTimeout(() => {
        fetch(`/api/penerimaan/search-products?q=${encodeURIComponent(q)}`)
        .then(r => r.json())
        .then(data => {
            if (data.length === 0) {
                result.innerHTML = `<div class="p-4 text-center text-slate-400 text-sm">Produk tidak ditemukan</div>`;
                result.classList.remove('hidden');
                selectedIndex = -1;
                return;
            }
            if (data.length === 1 && data[0].kode_barang.toLowerCase() === q.toLowerCase()) {
                addToCart(data[0].id, data[0].nama_barang, data[0].kode_barang, data[0].harga_beli);
                return;
            }
            let html = '';
            data.forEach((item, index) => {
                html += `
                <div class="border-b p-3 hover:bg-blue-50 cursor-pointer product-row" id="product-row-${index}" data-id="${item.id}" data-name="${item.nama_barang}" data-code="${item.kode_barang}" data-price="${item.harga_beli}">
                    <div class="font-semibold text-slate-800">${item.nama_barang}</div>
                    <div class="text-xs text-slate-500">${item.kode_barang} | HPP: Rp${new Intl.NumberFormat('id-ID').format(item.harga_beli)}</div>
                </div>`;
            });
            result.innerHTML = html;
            result.classList.remove('hidden');
            selectedIndex = -1;

            document.querySelectorAll('.product-row').forEach(row => {
                row.onclick = function() { addToCart(this.dataset.id, this.dataset.name, this.dataset.code, parseFloat(this.dataset.price)); }
            });
        });
    }, 250);
});

search.addEventListener('keydown', function(e) {
    const rows = document.querySelectorAll('.product-row');
    if (rows.length === 0) return;
    if (e.key === 'ArrowDown') { e.preventDefault(); selectedIndex++; if (selectedIndex >= rows.length) selectedIndex = 0; updateRowHighlight(rows); } 
    else if (e.key === 'ArrowUp') { e.preventDefault(); selectedIndex--; if (selectedIndex < 0) selectedIndex = rows.length - 1; updateRowHighlight(rows); } 
    else if (e.key === 'Enter') { e.preventDefault(); if (selectedIndex >= 0 && selectedIndex < rows.length) { const activeRow = rows[selectedIndex]; addToCart(activeRow.dataset.id, activeRow.dataset.name, activeRow.dataset.code, parseFloat(activeRow.dataset.price)); } }
});

function updateRowHighlight(rows) {
    rows.forEach((row, index) => { if (index === selectedIndex) { row.classList.add('bg-blue-100'); row.scrollIntoView({ block: 'nearest' }); } else { row.classList.remove('bg-blue-100'); } });
}

// 2. RENDER TABLE (HANYA SAAT AWAL ATAU TAMBAH/HAPUS ITEM)
function renderTable() {
    if (cart.length === 0) {
        tableItems.innerHTML = `<tr><td colspan="6"><div class="text-center py-10 text-slate-400 text-sm">Belum ada item masuk. Scan/cari produk di atas.</div></td></tr>`;
        grandTotalLabel.innerText = 'Rp0';
        return;
    }
    let html = ''; let grandTotal = 0;
    cart.forEach((item, index) => {
        const subtotal = item.qty_terima * item.harga_beli; grandTotal += subtotal;
        html += `
        <tr class="hover:bg-slate-50 border-b item-row">
            <td class="p-3 text-sm">
                <div class="font-semibold text-slate-800">${item.name}</div>
                <div class="text-xs text-slate-400">${item.code}</div>
                <input type="hidden" name="items[${index}][product_id]" value="${item.id}">
                <input type="hidden" name="items[${index}][qty_po]" value="${item.qty_po}">
                ${item.qty_po === 0 ? '<span class="inline-block mt-1 px-2 py-0.5 text-[9px] bg-purple-100 text-purple-700 font-bold rounded">Item Luar PO</span>' : ''}
            </td>
            <td class="p-3 text-center text-slate-500 font-bold">${item.qty_po}</td>
            <td class="p-3 text-center">
                <input type="number" name="items[${index}][qty_terima]" min="1" value="${item.qty_terima}" data-index="${index}" class="qty-input border rounded-lg w-24 px-2 py-1 text-center font-bold text-blue-600 focus:border-blue-500 focus:ring-1 focus:ring-blue-500">
            </td>
            <td class="p-3 text-right">
                <input type="number" name="items[${index}][harga_beli]" min="0" value="${item.harga_beli}" data-index="${index}" class="price-input border rounded-lg w-36 px-3 py-1 text-right font-semibold text-slate-700 focus:border-blue-500 focus:ring-1 focus:ring-blue-500">
            </td>
            <td class="p-3 text-sm text-right text-slate-800 font-bold subtotal-cell">
                Rp${new Intl.NumberFormat('id-ID').format(subtotal)}
            </td>
            <td class="p-3 text-center">
                <button type="button" class="delete-btn text-red-500 hover:text-red-700 transition" data-index="${index}">
                    <i class="ri-delete-bin-line text-lg"></i>
                </button>
            </td>
        </tr>`;
    });
    tableItems.innerHTML = html;
    grandTotalLabel.innerText = 'Rp' + new Intl.NumberFormat('id-ID').format(grandTotal);
}

// 3. EDIT QTY & HARGA AMAN REAL-TIME TANPA RE-RENDER DOM INPUT
document.addEventListener('input', function(e) {
    if (e.target.classList.contains('qty-input')) { 
        const index = e.target.dataset.index; 
        let val = parseInt(e.target.value); 
        
        if (isNaN(val)) return; // Blokir jika kosong pas di-backspace biar gak merusak array
        if (val < 1) val = 1; 

        cart[index].qty_terima = val; 
        updateTotalsWithoutRerender();
    }
    
    if (e.target.classList.contains('price-input')) { 
        const index = e.target.dataset.index; 
        let val = parseFloat(e.target.value); 
        
        if (isNaN(val)) return; 
        if (val < 0) val = 0; 

        cart[index].harga_beli = val; 
        updateTotalsWithoutRerender();
    }
});

// Fungsi kalkulasi visual instan penangkal 'Lost Focus'
function updateTotalsWithoutRerender() {
    let grandTotal = 0;
    const rows = tableItems.querySelectorAll('.item-row');
    
    cart.forEach((item, index) => {
        const subtotal = item.qty_terima * item.harga_beli;
        grandTotal += subtotal;
        
        if (rows[index]) {
            const subtotalCell = rows[index].querySelector('.subtotal-cell');
            if (subtotalCell) {
                subtotalCell.innerText = 'Rp' + new Intl.NumberFormat('id-ID').format(subtotal);
            }
        }
    });
    grandTotalLabel.innerText = 'Rp' + new Intl.NumberFormat('id-ID').format(grandTotal);
}

document.addEventListener('click', function(e) {
    const btn = e.target.closest('.delete-btn'); if (!btn) return;
    cart.splice(btn.dataset.index, 1); renderTable(); search.focus();
});

document.addEventListener('click', function(e) { if (!search.contains(e.target) && !result.contains(e.target)) { result.classList.add('hidden'); } });

function validateForm(e) {
    e.preventDefault();
    if (cart.length === 0) { Swal.fire({ title: 'Peringatan', text: 'Daftar item penerimaan tidak boleh kosong!', icon: 'warning', confirmButtonColor: '#ef4444' }); return false; }
    if (document.getElementsByName('supplier_id')[0].value === "") { Swal.fire({ title: 'Peringatan', text: 'Harap pilih supplier terlebih dahulu!', icon: 'warning', confirmButtonColor: '#ef4444' }); return false; }
    Swal.fire({ title: 'Posting Penerimaan?', text: 'Data penerimaan langsung masuk stok komputer dan tidak dapat diubah kembali.', icon: 'question', showCancelButton: true, confirmButtonColor: '#2563eb', cancelButtonColor: '#64748b', confirmButtonText: 'YA, Simpan!', cancelButtonText: 'Batal' }).then((result) => { if (result.isConfirmed) { document.getElementById('main-form').submit(); } });
}
</script>
@endpush

@endsection