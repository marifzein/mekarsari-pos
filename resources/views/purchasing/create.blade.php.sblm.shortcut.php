@extends('layouts.app')

@section('title','Buat Purchase Order')

@section('content')

<x-page-header
    title="Buat Purchase Order"
    subtitle="Buat Purchase Order baru"
>
    <x-slot:action>

        <a href="{{ route('purchasing.index') }}">

            <x-button color="gray" type="button">

                <i class="ri-arrow-left-line"></i>

                Kembali

            </x-button>

        </a>

    </x-slot:action>
</x-page-header>

<form
    method="POST"
    action="{{ route('purchasing.store') }}"
>

@csrf

    <x-card>

        <div class="grid md:grid-cols-3 gap-6">

            <x-input
                label="Nomor PO"
                name="po_number"
                readonly
                :value="$poNumber"
                icon="ri-file-list-3-line"
            />

            <x-input
                label="Tanggal"
                name="po_date"
                type="date"
                :value="date('Y-m-d')"
            />

            <x-select
                label="Supplier"
                name="supplier_id"
                required
                icon="ri-truck-line"
            >

                <option value="">-- Pilih Supplier --</option>

                @foreach($suppliers as $supplier)

                    <option value="{{ $supplier->id }}">

                        {{ $supplier->nama }}

                    </option>

                @endforeach

            </x-select>

        </div>

    </x-card>



    <div class="grid lg:grid-cols-3 gap-6 mt-6">

        {{-- kiri --}}
        <div class="lg:col-span-1">

            <x-card>

                <div class="font-semibold mb-4">

                    Cari Produk

                </div>

                {{-- <x-input

                    name="search"

                    placeholder="Barcode / Kode / Nama Produk"

                    icon="ri-search-line"

                    autocomplete="off"

                /> --}}
                <x-input

                    id="search-product"

                    name="search"

                    placeholder="Barcode / Kode / Nama Produk"

                    icon="ri-search-line"

                    autocomplete="off"

                />

                <div
                    id="product-result"
                    class="mt-4 rounded-xl border bg-white"
                >

                    <div class="p-8 text-center text-slate-400">

                        Ketik nama / barcode produk

                    </div>

                </div>

            </x-card>

        </div>


        {{-- kanan --}}
        <div class="lg:col-span-2">

            <x-card>

                <div class="flex justify-between items-center mb-4">

                    <div>

                        <h3 class="font-semibold">

                            Item Purchase Order

                        </h3>

                        <p class="text-sm text-slate-500">

                            Produk yang akan dipesan

                        </p>

                    </div>

                </div>


                <x-table>

                    <x-table-header>

                        <tr>

                            <x-table-head>Produk</x-table-head>

                            <x-table-head class="text-center">

                                Qty

                            </x-table-head>

                            <x-table-head class="text-right">

                                Harga

                            </x-table-head>

                            <x-table-head class="text-right">

                                Subtotal

                            </x-table-head>

                            <x-table-head class="text-center">

                                Aksi

                            </x-table-head>

                        </tr>

                    </x-table-header>

                    <tbody id="po-items">

                        <tr>

                            <td colspan="5">

                                <x-empty-state

                                    icon="ri-shopping-basket-line"

                                    title="Belum ada produk"

                                    description="Cari produk di sebelah kiri."

                                />

                            </td>

                        </tr>

                    </tbody>

                </x-table>


                <div class="border-t mt-6 pt-6">

                    <div class="flex justify-end">

                        <table class="text-sm">

                            <tr>

                                <td class="pr-10 py-2">

                                    Total

                                </td>

                                <td

                                    class="font-bold text-xl text-right"

                                    id="grand-total"

                                >

                                    Rp 0

                                </td>

                            </tr>

                        </table>

                    </div>

                </div>

            </x-card>

        </div>

    </div>


    <div class="flex justify-end gap-3 mt-6">

        <a href="{{ route('purchasing.index') }}">
            <x-button color="secondary" type="button" full>
                <i class="ri-close-circle-line text-red-500 text-base"></i>
                Batal
            </x-button>
        </a>

        <x-button
            color="orange"
            type="submit"
            name="action"
            value="draft"
            full
        >
            <i class="ri-save-line"></i>
            Simpan Draft
        </x-button>

        <x-button
            color="green"
            type="submit"
            name="action"
            value="ordered"
            full
        >
            <i class="ri-printer-line"></i>
            Order & Cetak
        </x-button>

    </div>

</form>
    @push('scripts')

    <script>
const search = document.getElementById('search-product');
const result = document.getElementById('product-result');
const poItems = document.getElementById('po-items');
const grandTotalLabel = document.getElementById('grand-total');

let cart = []; 
let timer; 
let selectedIndex = -1;


// Fokus otomatis saat halaman dimuat
document.addEventListener('DOMContentLoaded', () => search.focus());

function clearSearch() { 
    result.innerHTML = `<div class="p-8 text-center text-slate-400">Ketik nama / barcode produk</div>`; 
    search.value = ''; 
    selectedIndex = -1; 
    search.focus(); 
}

function addToCart(id, name, price) {
    const existing = cart.find(x => x.id == id);
    if (existing) { 
        existing.qty++; 
    } else { 
        cart.push({ id: id, name: name, price: Number(price), qty: 1 }); 
    }
    renderTable(); 
    clearSearch();
}

// 1. PENCARIAN & NAVIGASI KEYBOARD (ARROW UP, DOWN, ENTER)
search.addEventListener('keyup', function(e) {
    if (['ArrowUp', 'ArrowDown', 'Enter'].includes(e.key)) return;
    clearTimeout(timer);
    const q = this.value.trim();
    if (q.length < 2) { 
        result.innerHTML = `<div class="p-8 text-center text-slate-400">Ketik minimal 2 karakter</div>`; 
        return; 
    }

    timer = setTimeout(() => {
        fetch(`/api/products/search?q=${encodeURIComponent(q)}`)
        .then(r => r.json())
        .then(data => {
            if (data.length === 0) {
                result.innerHTML = `<div class="p-6 text-center text-slate-400">Produk tidak ditemukan</div>`;
                return;
            }
            let html = '';
            data.forEach((item, index) => {
                html += `
                <div class="border-b p-3 hover:bg-indigo-50 cursor-pointer product-row" 
                     id="product-row-${index}" 
                     data-id="${item.id}" 
                     data-name="${item.nama_barang}" 
                     data-price="${item.harga}">
                    <div class="font-semibold">${item.nama_barang}</div>
                    <div class="text-sm text-slate-500">${item.kode_barang}</div>
                </div>`;
            });
            result.innerHTML = html; 
            selectedIndex = -1;

            document.querySelectorAll('.product-row').forEach(row => {
                row.onclick = function() { 
                    addToCart(this.dataset.id, this.dataset.name, parseFloat(this.dataset.price)); 
                }
            });
        });
    }, 250);
});

search.addEventListener('keydown', function(e) {
    const rows = document.querySelectorAll('.product-row'); 
    if (rows.length === 0) return;
    
    if (e.key === 'ArrowDown') { 
        e.preventDefault(); 
        selectedIndex++; 
        if (selectedIndex >= rows.length) selectedIndex = 0; 
        updateRowHighlight(rows); 
    } 
    else if (e.key === 'ArrowUp') { 
        e.preventDefault(); 
        selectedIndex--; 
        if (selectedIndex < 0) selectedIndex = rows.length - 1; 
        updateRowHighlight(rows); 
    } 
    else if (e.key === 'Enter') { 
        e.preventDefault(); 
        if (selectedIndex >= 0 && selectedIndex < rows.length) { 
            const r = rows[selectedIndex]; 
            addToCart(r.dataset.id, r.dataset.name, parseFloat(r.dataset.price)); 
        } 
    }
});

function updateRowHighlight(rows) {
    rows.forEach((row, i) => { 
        if (i === selectedIndex) { 
            row.classList.add('bg-indigo-100'); 
            row.scrollIntoView({ block: 'nearest' }); 
        } else { 
            row.classList.remove('bg-indigo-100'); 
        } 
    });
}

// 2. RENDER TABLE & INPUT FIX (ANTI-LOST FOCUS & ANTI-NaN)
function renderTable() {
    if (cart.length === 0) {
        poItems.innerHTML = `
        <tr>
            <td colspan="5">
                <div class="text-center py-10 text-slate-400">
                    Belum ada produk
                </div>
            </td>
        </tr>`;
        grandTotalLabel.innerHTML = 'Rp 0'; 
        return;
    }

    let total = 0;
    let html = '';

    cart.forEach((item, index) => {
        item.subtotal = item.qty * item.price;
        total += item.subtotal;

        html += `
        <tr>
            <td>
                ${item.name}
                <input type="hidden" name="product_id[]" value="${item.id}">
            </td>
            <td class="text-center">
                <input type="number" 
                       min="1" 
                       value="${item.qty}" 
                       data-index="${index}" 
                       class="qty border rounded w-20 text-center">
                <input type="hidden" 
                       name="qty[]" 
                       value="${item.qty}" 
                       id="qty-hidden-${index}">
            </td>
            <td class="text-right">
                <input type="number" 
                       min="0" 
                       value="${item.price}" 
                       data-index="${index}" 
                       class="price border rounded w-24 text-right">
                <input type="hidden" 
                       name="price[]" 
                       value="${item.price}" 
                       id="price-hidden-${index}">
            </td>
            <td class="text-right">
                Rp ${Number(item.subtotal).toLocaleString('id-ID')}
            </td>
            <td class="text-center">
                <button type="button" class="delete text-red-600" data-index="${index}">
                    <i class="ri-delete-bin-line"></i>
                </button>
            </td>
        </tr>`;
    });

    poItems.innerHTML = html;
    grandTotalLabel.innerHTML = 'Rp ' + Number(total).toLocaleString('id-ID');
}

// Handler Input: Hanya update value di backend data & hidden input (TIDAK renderTable penuh agar tak lost focus)
document.addEventListener('input', function(e) {
    if (e.target.classList.contains('qty')) {
        const index = e.target.dataset.index;
        let val = parseInt(e.target.value);
        
        // Membiarkan input kosong sementara saat user menghapus (agar tidak langsung divalidasi NaN)
        if (isNaN(val)) return; 
        if (val < 1) val = 1;

        cart[index].qty = val;
        
        // Update hidden input pendamping
        document.getElementById(`qty-hidden-${index}`).value = val;
        
        // Update kalkulasi subtotal baris & grand total tanpa re-render row input
        updateTotalsWithoutRerender();
    }

    if (e.target.classList.contains('price')) {
        const index = e.target.dataset.index;
        let val = parseInt(e.target.value);
        
        if (isNaN(val)) return;
        if (val < 0) val = 0;

        cart[index].price = val;
        
        // Update hidden input pendamping
        document.getElementById(`price-hidden-${index}`).value = val;
        
        updateTotalsWithoutRerender();
    }
});

// Update total real-time saat user mengetik (tanpa me-replace DOM element yang sedang aktif ketik)
function updateTotalsWithoutRerender() {
    let grandTotal = 0;
    const rows = poItems.querySelectorAll('tr');
    
    cart.forEach((item, index) => {
        const subtotal = item.qty * item.price;
        grandTotal += subtotal;
        
        // Update teks subtotal di baris tabel terkait
        if (rows[index]) {
            const cells = rows[index].querySelectorAll('td');
            if (cells.length >= 4) {
                cells[3].innerHTML = 'Rp ' + Number(subtotal).toLocaleString('id-ID');
            }
        }
    });
    
    grandTotalLabel.innerHTML = 'Rp ' + Number(grandTotal).toLocaleString('id-ID');
}

// Tombol hapus item
document.addEventListener('click', function(e) {
    const btn = e.target.closest('.delete');
    if (!btn) return;
    cart.splice(btn.dataset.index, 1);
    renderTable();
    search.focus();
});

// Tutup drop-down search ketika klik di luar area
document.addEventListener('click', (e) => { 
    if (!search.contains(e.target) && !result.contains(e.target)) {
        result.innerHTML = `<div class="p-8 text-center text-slate-400">Ketik nama / barcode produk</div>`;
    } 
});
</script>

    @endpush
@endsection