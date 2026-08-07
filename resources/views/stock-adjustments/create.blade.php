@extends('layouts.app')

@section('title', 'Buat Stock Adjustment')

@section('content')

<x-page-header
    title="Buat Stock Adjustment"
    subtitle="Input penyesuaian barang hilang, rusak, atau expired"
>
    <x-slot:action>
        <a href="{{ route('stock-adjustments.index') }}">
            <x-button color="gray" type="button">
                <i class="ri-arrow-left-line"></i>
                Kembali
            </x-button>
        </a>
    </x-slot:action>
</x-page-header>

<form
    method="POST"
    action="{{ route('stock-adjustments.store') }}"
>
@csrf

    <x-card>
        <div class="grid md:grid-cols-3 gap-6">
            <x-input
                label="Nomor SA"
                name="nomor_sa"
                readonly
                :value="$nomor_sa"
                icon="ri-file-list-3-line"
            />

            <x-input
                label="Tanggal Penyesuaian"
                name="tgl_sa"
                type="date"
                :value="date('Y-m-d')"
            />

            <x-input
                label="Catatan Umum Dokumen"
                name="catatan"
                placeholder="Contoh: Pembuangan barang rusak rak depan"
                icon="ri-chat-4-line"
            />
        </div>
    </x-card>

    <div class="grid lg:grid-cols-3 gap-6 mt-6">
        {{-- Kiri --}}
        <div class="lg:col-span-1">
            <x-card>
                <div class="font-semibold mb-4">
                    F2 - Cari Produk
                </div>
                
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

        {{-- Kanan --}}
        <div class="lg:col-span-2">
            <x-card>
                <div class="flex justify-between items-center mb-4">
                    <div>
                        <h3 class="font-semibold">
                            Daftar Item Dibuang / Dikurangi
                        </h3>
                        <p class="text-sm text-slate-500">
                            Kuantitas di bawah ini akan memotong stok di komputer
                        </p>
                    </div>
                </div>

                <x-table>
                    <x-table-header>
                        <tr>
                            <x-table-head>Produk</x-table-head>
                            <x-table-head class="text-center">Qty</x-table-head>
                            <x-table-head>Alasan / Keterangan</x-table-head>
                            <x-table-head class="text-center">Aksi</x-table-head>
                        </tr>
                    </x-table-header>
                    <tbody id="sa-items">
                        <tr>
                            <td colspan="4">
                                <x-empty-state
                                    icon="ri-delete-bin-5-line"
                                    title="Belum ada produk"
                                    description="Cari produk di sebelah kiri."
                                />
                            </td>
                        </tr>
                    </tbody>
                </x-table>
            </x-card>
        </div>
    </div>

    <div class="flex justify-end gap-3 mt-6">
        <a href="{{ route('stock-adjustments.index') }}">
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
            F7 Simpan Draft SA
        </x-button>

        <x-button
            color="green"
            type="submit"
            name="action"
            value="closed"
            full
        >
            <i class="ri-checkbox-circle-line"></i>
            F10 Posting & Kunci Stok
        </x-button>
    </div>
</form>

@push('scripts')
<script>
const search = document.getElementById('search-product');
const result = document.getElementById('product-result');
const saItems = document.getElementById('sa-items');

let cart = [];
let timer;
let selectedIndex = -1;

// Render awal
renderTable();

document.addEventListener('DOMContentLoaded', () => search.focus());

function clearSearch() { 
    result.innerHTML = `<div class="p-8 text-center text-slate-400">Ketik nama / barcode produk</div>`; 
    search.value = ''; 
    selectedIndex = -1; 
    search.focus(); 
}

function addToCart(id, name, code) {
    const existing = cart.find(x => x.id == id);
    if (existing) { 
        existing.qty++; 
    } else { 
        cart.push({ id: id, name: name, code: code, qty: 1, notes: '' }); 
    }
    renderTable(); 
    clearSearch();
}

// 1. PENCARIAN & NAVIGASI KEYBOARD
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
                    data-code="${item.kode_barang}">
                    <div class="font-semibold">${item.nama_barang}</div>
                    <div class="text-sm text-slate-500">${item.kode_barang}</div>
                </div>`;
            });
            result.innerHTML = html; 
            selectedIndex = -1;

            document.querySelectorAll('.product-row').forEach(row => {
                row.onclick = function() { 
                    addToCart(this.dataset.id, this.dataset.name, this.dataset.code); 
                }
            });
        });
    }, 250);
});

search.addEventListener('keydown', function(e) {
    const rows = document.querySelectorAll('.product-row'); 

    if (e.key === 'Enter') {
        e.preventDefault(); 
        if (selectedIndex >= 0 && rows.length > 0 && selectedIndex < rows.length) { 
            const r = rows[selectedIndex]; 
            addToCart(r.dataset.id, r.dataset.name, r.dataset.code); 
        } 
        else if (rows.length === 1) {
            const r = rows[0];
            addToCart(r.dataset.id, r.dataset.name, r.dataset.code);
        }
        return;
    }
    
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

// 2. RENDER TABLE
function renderTable() {
    if (cart.length === 0) {
        saItems.innerHTML = `
        <tr>
            <td colspan="4">
                <div class="text-center py-10 text-slate-400">
                    Belum ada produk
                </div>
            </td>
        </tr>`;
        return;
    }

    let html = '';
    cart.forEach((item, index) => {
        html += `
        <tr>
            <td>
                <div class="font-semibold">${item.name}</div>
                <div class="text-xs text-slate-400">${item.code}</div>
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
            <td>
                <input type="text" 
                    value="${item.notes}" 
                    data-index="${index}" 
                    placeholder="Alasan (contoh: Expired / Rusak)"
                    class="notes border rounded w-full px-2 py-1 text-sm">
                <input type="hidden" 
                    name="notes[]" 
                    value="${item.notes}" 
                    id="notes-hidden-${index}">
            </td>
            <td class="text-center">
                <button type="button" class="delete text-red-600" data-index="${index}">
                    <i class="ri-delete-bin-line"></i>
                </button>
            </td>
        </tr>`;
    });

    saItems.innerHTML = html;
}

// 3. EVENT INPUT REAL-TIME (Qty & Alasan)
document.addEventListener('input', function(e) {
    if (e.target.classList.contains('qty')) {
        const index = e.target.dataset.index;
        let val = parseInt(e.target.value);
        
        if (isNaN(val)) return; 
        if (val < 1) val = 1;

        cart[index].qty = val;
        document.getElementById(`qty-hidden-${index}`).value = val;
    }

    if (e.target.classList.contains('notes')) {
        const index = e.target.dataset.index;
        let val = e.target.value;

        cart[index].notes = val;
        document.getElementById(`notes-hidden-${index}`).value = val;
    }
});

// Tombol hapus item
document.addEventListener('click', function(e) {
    const btn = e.target.closest('.delete');
    if (!btn) return;
    cart.splice(btn.dataset.index, 1);
    renderTable();
    search.focus();
});

document.addEventListener('click', (e) => { 
    if (!search.contains(e.target) && !result.contains(e.target)) {
        result.innerHTML = `<div class="p-8 text-center text-slate-400">Ketik nama / barcode produk</div>`;
    } 
});

// 4. SHORTCUT KEYBOARD PREMIUM (F2, F7, F10) - SAMA PERSIS ARSITEKTUR PO
document.addEventListener('keydown', function(e) {
    const form = document.querySelector('form[action="{{ route("stock-adjustments.store") }}"]');
    if (!form) return;

    // F2 -> Fokus Kolom Barcode
    if (e.key === 'F2') {
        e.preventDefault();
        search.focus();
        search.select();
    }

    // F7 -> Simpan Sebagai Draft (Normal Form Submit)
    if (e.key === 'F7') {
        e.preventDefault();
        
        if (cart.length === 0) { 
            Swal.fire({ title: 'Peringatan', text: 'Daftar item adjustment masih kosong!', icon: 'warning', confirmButtonColor: '#f97316' }); 
            return; 
        }

        // Validasi Alasan wajib diisi bahkan saat draft
        const emptyNotes = cart.some(item => !item.notes || item.notes.trim() === '');
        if (emptyNotes) {
            Swal.fire({ title: 'Peringatan', text: 'Semua item wajib diisi alasan penyesuaiannya!', icon: 'warning', confirmButtonColor: '#f97316' });
            return;
        }
        
        Swal.fire({
            title: 'Simpan sebagai Draft?',
            text: 'Data SA akan disimpan dengan status Draft dan bisa diubah kembali nanti.',
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#f97316',
            cancelButtonColor: '#64748b',
            confirmButtonText: 'Ya, Simpan Draft!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                const oldAction = form.querySelector('input[name="action"]');
                if (oldAction) oldAction.remove();

                const inputDraft = document.createElement('input');
                inputDraft.type = 'hidden';
                inputDraft.name = 'action';
                inputDraft.value = 'draft';
                form.appendChild(inputDraft);

                form.submit();
            }
        });
    }

    // F10 -> Posting & Kunci Stok Langsung via AJAX (Fetch)
    if (e.key === 'F10') {
        e.preventDefault();
        
        if (cart.length === 0) { 
            Swal.fire({ title: 'Peringatan', text: 'Daftar item adjustment masih kosong!', icon: 'warning', confirmButtonColor: '#22c55e' }); 
            return; 
        }

        const emptyNotes = cart.some(item => !item.notes || item.notes.trim() === '');
        if (emptyNotes) {
            Swal.fire({ title: 'Peringatan', text: 'Semua item wajib diisi alasan penyesuaiannya!', icon: 'warning', confirmButtonColor: '#22c55e' });
            return;
        }

        Swal.fire({
            title: 'Posting & Kunci Stok?',
            text: 'Status SA akan diubah menjadi CLOSED dan langsung memotong stok gudang secara permanen.',
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#22c55e',
            cancelButtonColor: '#64748b',
            confirmButtonText: 'Ya, Posting!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                Swal.fire({
                    title: 'Memproses Data...',
                    text: 'Mohon tunggu sebentar, sistem sedang menyesuaikan stok.',
                    allowOutsideClick: false,
                    didOpen: () => { Swal.showLoading(); }
                });

                const oldAction = form.querySelector('input[name="action"]');
                if (oldAction) oldAction.remove();

                const formData = new FormData(form);
                formData.append('action', 'closed'); 

                fetch(form.getAttribute('action'), {
                    method: 'POST', 
                    body: formData,
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json' 
                    }
                })
                .then(response => {
                    if (!response.ok) throw new Error('Validasi server gagal');
                    return response.json();
                })
                .then(data => {
                    if (data.success) {
                        Swal.fire({
                            title: 'Berhasil!',
                            text: data.message,
                            icon: 'success',
                            confirmButtonColor: '#22c55e'
                        }).then(() => {
                            window.location.href = "{{ route('stock-adjustments.index') }}";
                        });
                    } else {
                        Swal.fire({ title: 'Gagal', text: data.message, icon: 'error', confirmButtonColor: '#ef4444' });
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    Swal.fire({ title: 'Error!', text: 'Terjadi kesalahan internal server.', icon: 'error', confirmButtonColor: '#ef4444' });
                });
            }
        });
    }
});
</script>
@endpush

@endsection