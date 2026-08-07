@extends('layouts.app')

@section('title', 'Edit Stock Adjustment')

@section('content')

<x-page-header
    title="{{ $stockAdjustment->status === 'closed' ? 'Detail Stock Adjustment' : 'Edit Stock Adjustment' }}"
    subtitle="{{ $stockAdjustment->status === 'closed' ? 'Melihat dokumen penyesuaian yang telah dikunci' : 'Ubah draft atau teruskan posting penyesuaian stok' }}"
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

@if ($errors->any())
    <div class="mb-4 p-4 bg-red-100 border border-red-400 text-red-700 rounded-xl">
        <ul class="list-disc pl-5 text-sm">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<form method="POST" id="form-sa" action="{{ route('stock-adjustments.update', $stockAdjustment->id) }}">
    @csrf
    @method('PUT')

    <x-card>
        <div class="grid md:grid-cols-3 gap-6">
            <x-input
                label="Nomor SA"
                name="nomor_sa"
                readonly
                :value="$stockAdjustment->nomor_sa"
                icon="ri-file-list-3-line"
            />

            <x-input
                label="Tanggal Penyesuaian"
                name="tgl_sa"
                type="date"
                :value="date('Y-m-d', strtotime($stockAdjustment->tgl_sa))"
                required
                :readonly="$stockAdjustment->status === 'closed'"
            />

            <x-input
                label="Catatan Umum Dokumen"
                name="catatan"
                :value="$stockAdjustment->catatan"
                placeholder="Contoh: Pembuangan barang rusak rak depan"
                icon="ri-chat-4-line"
                :readonly="$stockAdjustment->status === 'closed'"
            />
        </div>
    </x-card>

    <div class="grid lg:grid-cols-3 gap-6 mt-6">
      @if($stockAdjustment->status !== 'closed')
        <div class="lg:col-span-1">
            <x-card>
                <div class="font-semibold mb-4">F2 - Cari Produk Terganggu</div>
                <x-input
                    id="search-product"
                    name="search"
                    placeholder="Barcode / Kode / Nama Produk"
                    icon="ri-search-line"
                    autocomplete="off"
                />
                <div id="product-result" class="mt-4 rounded-xl border bg-white max-h-96 overflow-y-auto">
                    <div class="p-8 text-center text-slate-400">
                        Ketik nama / barcode produk minimarket
                    </div>
                </div>
            </x-card>
        </div>
      @endif

        <div class="{{ $stockAdjustment->status === 'closed' ? 'lg:col-span-3' : 'lg:col-span-2' }}">    
            <x-card>
                <div class="mb-4">
                    <h3 class="font-semibold">Daftar Item Dibuang / Dikurangi</h3>
                    <p class="text-sm text-slate-500">Kuantitas di bawah ini akan memotong stok di komputer</p>
                </div>

                <x-table>
                    <x-table-header>
                        <tr>
                            <x-table-head>Produk</x-table-head>
                            <x-table-head class="text-center w-32">Qty Dikurangi</x-table-head>
                            <x-table-head>Alasan / Keterangan Spesifik</x-table-head>
                            <x-table-head class="text-center w-20">Aksi</x-table-head>
                        </tr>
                    </x-table-header>
                    <tbody id="sa-items"></tbody>
                </x-table>
            </x-card>
        </div>
    </div>

    <div class="flex justify-end gap-3 mt-6">
        @if($stockAdjustment->status !== 'closed')
          <x-button color="orange" type="button" onclick="submitWithAction('draft')" name="action" value="draft" full>
              <i class="ri-save-line"></i>
              F7 Perbarui Draft SA
          </x-button>

          <x-button color="green" type="button" onclick="submitWithAction('closed')" name="action" value="closed" full>
              <i class="ri-checkbox-circle-line"></i>
              F10 Posting & Kunci Stok
          </x-button>
        @endif
    </div>
</form>

@push('scripts')
<script>
const search = document.getElementById('search-product');
const result = document.getElementById('product-result');
const saItems = document.getElementById('sa-items');
const form = document.getElementById('form-sa');

// Status dokumen dari sisi backend
const isDocumentClosed = "{{ $stockAdjustment->status }}" === 'closed';

// DI SINI KUNCINYA: Load data lama dari database ke dalam variabel array JavaScript
let cart = @json($cartData); 

let timer;
let selectedIndex = -1;

// Langsung render data item lama saat halaman pertama kali dibuka
renderTable();

// Fokuskan input pencarian jika statusnya belum closed
if (!isDocumentClosed && search) {
    search.focus();
}

function clearSearch() {
    if (!search || !result) return;
    result.innerHTML = `<div class="p-8 text-center text-slate-400">Ketik nama / barcode produk minimarket</div>`;
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

if (!isDocumentClosed && search) {
    search.addEventListener('keyup', function(e) {
        if (['ArrowUp', 'ArrowDown', 'Enter'].includes(e.key)) return;
        clearTimeout(timer);
        const q = this.value.trim();

        if (q.length < 2) {
            result.innerHTML = `<div class="p-8 text-center text-slate-400">Ketik minimal 2 karakter</div>`;
            selectedIndex = -1;
            return;
        }

        timer = setTimeout(() => {
            fetch(`/api/products/search?q=${encodeURIComponent(q)}`)
            .then(r => r.json())
            .then(data => {
                if (data.length === 0) {
                    result.innerHTML = `<div class="p-6 text-center text-slate-400">Produk tidak ditemukan</div>`;
                    selectedIndex = -1;
                    return;
                }

                let html = '';
                data.forEach((item, index) => {
                    html += `
                    <div class="border-b p-3 hover:bg-indigo-50 cursor-pointer product-row"
                         id="product-row-${index}" data-id="${item.id}" data-name="${item.nama_barang}" data-code="${item.kode_barang}">
                        <div class="font-semibold">${item.nama_barang}</div>
                        <div class="text-sm text-slate-500">${item.kode_barang} | Stok: ${item.stok}</div>
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
        }, 300);
    });

    search.addEventListener('keydown', function(e) {
        const rows = document.querySelectorAll('.product-row');
        if (rows.length === 0) return;

        if (e.key === 'ArrowDown') {
            e.preventDefault(); selectedIndex++;
            if (selectedIndex >= rows.length) selectedIndex = 0;
            updateRowHighlight(rows);
        } else if (e.key === 'ArrowUp') {
            e.preventDefault(); selectedIndex--;
            if (selectedIndex < 0) selectedIndex = rows.length - 1;
            updateRowHighlight(rows);
        } else if (e.key === 'Enter') {
            e.preventDefault();
            if (selectedIndex >= 0 && selectedIndex < rows.length) {
                const activeRow = rows[selectedIndex];
                addToCart(activeRow.dataset.id, activeRow.dataset.name, activeRow.dataset.code);
            }
        }
    });
}

function updateRowHighlight(rows) {
    rows.forEach((row, index) => {
        if (index === selectedIndex) {
            row.classList.add('bg-indigo-100');
            row.scrollIntoView({ block: 'nearest' });
        } else {
            row.classList.remove('bg-indigo-100');
        }
    });
}

function renderTable() {
    if (cart.length === 0) {
        saItems.innerHTML = `<tr><td colspan="4"><div class="text-center py-10 text-slate-400">Belum ada produk dipilih</div></td></tr>`;
        return;
    }

    let html = '';
    cart.forEach((item, index) => {
        html += `
        <tr class="hover:bg-slate-50 transition-colors duration-150">
            <td class="border-b border-slate-200 py-3 px-2 text-slate-700">
                <div class="font-semibold">${item.name}</div>
                <div class="text-xs text-slate-400">${item.code}</div>
                <input type="hidden" name="product_id[]" value="${item.id}">
            </td>
            <td class="text-center">
                <input type="number" name="qty[]" min="1" value="${item.qty}" data-index="${index}" 
                class="qty border border-slate-300 rounded-xl w-24 px-3 py-2 text-center font-bold text-indigo-600 outline-none transition-all duration-200 focus:border-indigo-500 focus:ring-4 focus:ring-indigo-100 hover:border-slate-400 ${isDocumentClosed ? 'bg-slate-100 opacity-70 cursor-not-allowed' : 'bg-white'}" 
                ${isDocumentClosed ? 'disabled' : ''}>
            </td>
            <td>
                <input type="text" name="notes[]" value="${item.notes || ''}" data-index="${index}" placeholder="Alasan (Misal: Bocor, Expired)" 
                class="item-notes border border-slate-300 rounded-xl w-full px-4 py-2 text-sm text-slate-700 placeholder:text-slate-400 outline-none transition-all duration-200 focus:border-indigo-500 focus:ring-4 focus:ring-indigo-100 hover:border-slate-400 ${isDocumentClosed ? 'bg-slate-100 opacity-70 cursor-not-allowed' : 'bg-white'}" 
                ${isDocumentClosed ? 'disabled' : ''}>
            </td>
            <td class="text-center">
                ${isDocumentClosed ? '<span class="text-slate-400">-</span>' : `
                <button type="button" class="delete text-red-600 hover:text-red-800 p-1" data-index="${index}">
                    <i class="ri-delete-bin-line text-lg"></i>
                </button>
                `}
            </td>
        </tr>`;
    });
    saItems.innerHTML = html;
}

document.addEventListener('input', function(e) {
    if (e.target.classList.contains('qty')) {
        const index = e.target.dataset.index;
        let val = parseInt(e.target.value);
        if (isNaN(val) || val <= 0) val = 1;
        cart[index].qty = val;
    }
    if (e.target.classList.contains('item-notes')) {
        const index = e.target.dataset.index;
        cart[index].notes = e.target.value;
    }
});

document.addEventListener('click', function(e) {
    if (isDocumentClosed) return;
    const btn = e.target.closest('.delete');
    if (!btn) return;
    cart.splice(btn.dataset.index, 1);
    renderTable();
});

// ==========================================
// VALIDASI CLIENT SIDE
// ==========================================
function validateForm() {
    if (cart.length === 0) {
        Swal.fire({
            icon: 'warning',
            title: 'Cart Masih Kosong!',
            text: 'Silakan cari dan pilih minimal 1 produk terlebih dahulu.',
            confirmButtonColor: '#4F46E5'
        });
        return false;
    }

    for (let i = 0; i < cart.length; i++) {
        if (!cart[i].qty || cart[i].qty <= 0) {
            Swal.fire({
                icon: 'error',
                title: 'Qty Tidak Valid!',
                text: `Kuantitas untuk barang "${cart[i].name}" harus lebih dari 0!`,
                confirmButtonColor: '#EF4444'
            });
            return false;
        }

        if (!cart[i].notes || cart[i].notes.trim() === '') {
            Swal.fire({
                icon: 'error',
                title: 'Alasan Belum Diisi!',
                text: `Alasan penyesuaian untuk barang "${cart[i].name}" wajib diisi!`,
                confirmButtonColor: '#EF4444'
            });
            return false;
        }
    }
    return true; 
}

// ==========================================
// INTERSEPSI SHORTCUT KEYBOARD GLOBAL
// ==========================================
document.addEventListener('keydown', function(e) {
    if (isDocumentClosed || !form) return;

    // F2 -> Fokus Input Cari
    if (e.key === 'F2') {
        e.preventDefault();
        if (search) {
            search.focus();
            search.select();
        }
    }
    
    // F7 -> Perbarui Draft
    if (e.key === 'F7') {
        e.preventDefault();
        if (!validateForm()) return;
        
        Swal.fire({
            title: 'Simpan perubahan Draft?',
            text: 'Data SA akan diperbarui dengan status Draft dan masih bisa diedit kembali.',
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#f97316',
            cancelButtonColor: '#64748b',
            confirmButtonText: 'Ya, Perbarui!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                submitWithAction('draft');
            }
        });
    }
    
    // F10 -> Posting & Kunci
    if (e.key === 'F10') {
        e.preventDefault();
        if (!validateForm()) return;
        
        Swal.fire({
            title: 'Posting & Kunci Stok?',
            text: 'Stok akan langsung disesuaikan secara permanen dan dokumen ini tidak akan bisa diubah lagi!',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#22c55e',
            cancelButtonColor: '#64748b',
            confirmButtonText: 'Ya, Posting Sekarang!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                submitWithAction('closed');
            }
        });
    }
});

function submitWithAction(actionValue) {
    const oldAction = form.querySelector('input[name="action"]');
    if (oldAction) oldAction.remove();

    const inputAction = document.createElement('input');
    inputAction.type = 'hidden';
    inputAction.name = 'action';
    inputAction.value = actionValue;
    form.appendChild(inputAction);

    form.submit();
}
</script>
@endpush

@endsection