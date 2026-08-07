@extends('layouts.app')

@section('title','Tambah Produk')

@section('content')

<x-page-header
    title="Tambah Produk"
    subtitle="Tambahkan produk baru ke dalam sistem"
>

    <x-slot:action>

        <a href="{{ url('/products') }}">

            <x-button color="gray">

                <i class="ri-arrow-left-line"></i>

                Kembali

            </x-button>

        </a>

    </x-slot:action>

</x-page-header>

@if($errors->any())

<x-alert type="error">

    <div class="font-semibold mb-2">

        Terdapat kesalahan :

    </div>

    <ul class="list-disc ml-5">

        @foreach($errors->all() as $error)

            <li>{{ $error }}</li>

        @endforeach

    </ul>

</x-alert>

@endif


<x-card>

<!-- <form method="POST" action="{{ url('/products') }}"> -->
<form id="productForm">

@csrf

<div class="grid grid-cols-2 gap-6">

    <x-input
        label="Kode Barang"
        name="kode_barang"
        icon="ri-qr-code-line"
        required
    />

    <x-input
        label="Barcode"
        name="barcode"
        icon="ri-barcode-line"
    />

    <x-input
        label="Nama Barang"
        name="nama_barang"
        icon="ri-box-3-line"
        required
    />

    <x-select
        label="Kategori"
        name="category_id"
        icon="ri-price-tag-3-line"
        required
    >

        <option value="">-- Pilih Kategori --</option>

        @foreach($categories as $category)

            <option
                value="{{ $category->id }}"
                @selected(old('category_id')==$category->id)
            >

                {{ $category->name }}

            </option>

        @endforeach

    </x-select>

    <x-input
        label="Harga Beli"
        name="harga_beli"
        type="number"
        icon="ri-hand-coin-line"
    />

    <x-input
        label="Harga Jual"
        name="harga"
        type="number"
        icon="ri-money-dollar-circle-line"
        required
    />
{{-- harga grosiran --}}

<div class="col-span-2">
    <div class="border border-slate-200 rounded-xl p-4 bg-white shadow-sm">
        <div class="font-semibold text-slate-700 mb-3">
            Potongan Grosir
        </div>

        <div id="grosir-container">
            <div class="grid grid-cols-12 gap-2 mb-2 grosir-row">
                <div class="col-span-5">
                    <input
                        type="number"
                        name="min_qty[]"
                        placeholder="Min Qty"
                        class="w-full rounded-xl border border-slate-300 p-2.5 text-sm focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 outline-none transition"
                    >
                </div>

                <div class="col-span-5">
                    <input
                        type="number"
                        name="potongan[]"
                        placeholder="Potongan / pcs"
                        class="w-full rounded-xl border border-slate-300 p-2.5 text-sm focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 outline-none transition"
                    >
                </div>

                <div class="col-span-2">
                    <button
                        type="button"
                        id="btnTambahGrosir"
                        class="w-full bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl h-full flex items-center justify-center font-bold text-lg transition duration-200"
                    >
                        +
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
{{-- harga grosiran end--}}
    <x-input
        label="Harga Diskon"
        name="harga_diskon"
        type="number"
        icon="ri-discount-percent-line"
    />

    <x-input
        label="Stok Awal"
        name="stok"
        type="number"
        icon="ri-archive-line"
        required
    />

    <x-input
        label="Minimum Stok"
        name="min_stok"
        type="number"
        icon="ri-alarm-warning-line"
        value="5"
        required
    />

    <x-select
        label="Satuan"
        name="satuan"
        icon="ri-ruler-line"
        required
    >
        <option value="pcs">PCS</option>
        <option value="pack">Pack</option>
        <option value="box">Box</option>
        <option value="dus">Dus</option>
        <option value="lusin">Lusin</option>

    </x-select>

    <x-textarea
        label="Catatan"
        name="catatan"
        icon="ri-todo-line"
    />

    <x-checkbox
        label="Aktif ( Status Produk )"
        name="is_active"
        checked="true"
    />
    

</div>

<div class="flex justify-end gap-3 mt-8">

    <a href="{{ url('/products') }}">

        <x-button color="gray">

            <i class="ri-close-line"></i>

            Batal

        </x-button>

    </a>

    <x-button
        id="btnSimpan"
        color="primary"
        type="button"
    >

        <i class="ri-save-line"></i>

        Simpan Produk

    </x-button>

</div>

</form>

</x-card>

@push('scripts')
<script>
// 1. FUNGSI UTK TAMBAH BARIS GROSIR BARU (Layout disamakan grid-12)
document.getElementById('btnTambahGrosir').addEventListener('click', function() {
    let html = `
    <div class="grid grid-cols-12 gap-2 mb-2 grosir-row">
        <div class="col-span-5">
            <input
                type="number"
                name="min_qty[]"
                placeholder="Min Qty"
                class="w-full rounded-xl border border-slate-300 p-2.5 text-sm focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 outline-none transition"
            >
        </div>

        <div class="col-span-5">
            <input
                type="number"
                name="potongan[]"
                placeholder="Potongan / pcs"
                class="w-full rounded-xl border border-slate-300 p-2.5 text-sm focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 outline-none transition"
            >
        </div>

        <div class="col-span-2">
            <button
                type="button"
                class="hapus w-full bg-red-500 hover:bg-red-600 text-white rounded-xl h-full flex items-center justify-center font-bold text-base transition duration-200"
            >
                ✕
            </button>
        </div>
    </div>
    `;

    document.getElementById('grosir-container').insertAdjacentHTML('beforeend', html);
});

// 2. FUNGSI HAPUS BARIS
document.addEventListener('click', function(e) {
    if (e.target.classList.contains('hapus')) {
        e.target.closest('.grosir-row').remove();
    }
});

// 3. VALIDASI SEBELUM SUBMIT FORM PRODUK

    document.getElementById('btnSimpan').addEventListener('click', async function () {

        alert('BUTTON CLICK');

    });
    

    // ==========================================
    // VALIDASI DATA GROSIR 
    // ==========================================
    const minQtyInputs = document.querySelectorAll('input[name="min_qty[]"]');
    const potonganInputs = document.querySelectorAll('input[name="potongan[]"]');

    for (let i = 0; i < minQtyInputs.length; i++) {
        let qtyVal = minQtyInputs[i].value.trim();
        let potonganVal = potonganInputs[i].value.trim();

        let numericQty = Number(qtyVal || 0);
        let numericPotongan = Number(potonganVal || 0);

        // Jika salah satu kolom diisi, pasangannya wajib diisi juga & tidak boleh 0
        if (qtyVal !== "" || potonganVal !== "" || numericQty > 0 || numericPotongan > 0) {
            if (numericQty <= 0 || numericPotongan <= 0) {
                // Stop/Gagalkan submit form ke backend
                e.preventDefault();

                await Swal.fire({
                    icon: 'warning',
                    title: 'Data Grosir Belum Lengkap',
                    text: `Pada level grosir baris ke-${i + 1}, nilai 'Min Qty' dan 'Potongan' harus diisi lebih dari 0!`,
                    confirmButtonText: 'Perbaiki',
                    confirmButtonColor: '#4f46e5',
                    returnFocus: false
                });

                // Fokuskan otomatis kursor ke field yang bermasalah
                if (numericQty <= 0) {
                    minQtyInputs[i].focus();
                } else {
                    potonganInputs[i].focus();
                }
                return;
            }
        }
    }
});
</script>
@endpush
@endsection