@extends('layouts.app')

@section('title','Edit Produk')

@section('content')

<x-page-header
    title="Edit Produk"
    subtitle="Perbarui informasi produk"
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

<form
     id="formProduk" 
    method="POST"
    action="/products/{{ $product->id }}"
>

@csrf
@method('PUT')

<div class="grid grid-cols-2 gap-6">

    <x-input
        label="Kode Barang"
        name="kode_barang"
        icon="ri-barcode-line"
        :value="$product->kode_barang"
        readonly
    />

    <x-input
        label="Barcode"
        name="barcode"
        icon="ri-qr-code-line"
        :value="$product->barcode"
    />

    <x-input
        label="Nama Barang"
        name="nama_barang"
        icon="ri-box-3-line"
        :value="$product->nama_barang"
        required
    />

    <x-select
        label="Kategori"
        name="category_id"
        icon="ri-price-tag-3-line"
    >

        <option value="">

            -- Pilih Kategori --

        </option>

        @foreach($categories as $category)

            <option
                value="{{ $category->id }}"
                @selected(old('category_id',$product->category_id)==$category->id)
            >

                {{ $category->name }}

            </option>

        @endforeach

    </x-select>

    <x-input
        label="Harga Beli"
        name="harga_beli"
        type="number"
        icon="ri-money-dollar-circle-line"
        :value="$product->harga_beli"
    />

    <x-input
        label="Harga Jual"
        name="harga"
        type="number"
        icon="ri-coins-line"
        :value="$product->harga"
        required
    />
    {{-- GROSIRAN --}}
    <div class="col-span-2">
        <div class="border border-slate-200 rounded-xl p-4 bg-white shadow-sm">
            <div class="font-semibold text-slate-700 mb-3">
                Potongan Grosir
            </div>

            <div id="grosir-container">
                @php
                    $oldPrices = $product->prices;
                @endphp

                @if($oldPrices->count() > 0)
                    @foreach($oldPrices as $index => $price)
                        <div class="grid grid-cols-12 gap-2 mb-2 grosir-row">
                            <div class="col-span-5">
                                <input
                                    type="number"
                                    name="min_qty[]"
                                    value="{{ $price->min_qty }}"
                                    placeholder="Min Qty"
                                    class="w-full rounded-xl border border-slate-300 p-2.5 text-sm focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 outline-none transition"
                                >
                            </div>

                            <div class="col-span-5">
                                <input
                                    type="number"
                                    name="potongan[]" {{-- Sinkronisasi nama input agar terbaca di controller update --}}
                                    value="{{ $price->potongan }}"
                                    placeholder="Potongan / pcs"
                                    class="w-full rounded-xl border border-slate-300 p-2.5 text-sm focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 outline-none transition"
                                >
                            </div>

                            <div class="col-span-2">
                                @if($index === 0)
                                    <button type="button" onclick="addGrosirRow()" class="w-full bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl h-full flex items-center justify-center font-bold text-lg transition duration-200">+</button>
                                @else
                                    <button type="button" onclick="this.closest('.grosir-row').remove()" class="w-full bg-red-500 hover:bg-red-600 text-white rounded-xl h-full flex items-center justify-center font-bold text-lg transition duration-200">✕</button>
                                @endif
                            </div>
                        </div>
                    @endforeach
                @else
                    <!-- Tampilan default kosong apabila produk belum memiliki setup grosir -->
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
                                name="potongan[]" {{-- Sinkronisasi nama input --}}
                                placeholder="Potongan / pcs"
                                class="w-full rounded-xl border border-slate-300 p-2.5 text-sm focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 outline-none transition"
                            >
                        </div>

                        <div class="col-span-2">
                            <button type="button" onclick="addGrosirRow()" class="w-full bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl h-full flex items-center justify-center font-bold text-lg transition duration-200">+</button>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
    {{-- <div class="mt-6">
        <div class="border border-slate-200 rounded-xl p-4 bg-white shadow-sm">
            <div class="font-semibold text-slate-700 mb-3">
                Harga Grosir
            </div>

            <div id="grosir-container">
                @php
                    // Ambil data grosir lama dari database milik produk ini
                    $oldPrices = $product->prices;
                @endphp

                @if($oldPrices->count() > 0)
                    @foreach($oldPrices as $index => $price)
                        <div class="grid grid-cols-12 gap-2 mb-2 grosir-row">
                            <div class="col-span-5">
                                <input
                                    type="number"
                                    name="min_qty[]"
                                    value="{{ $price->min_qty }}"
                                    placeholder="Min Qty"
                                    class="w-full rounded-xl border border-slate-300 p-2.5 text-sm focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 outline-none transition"
                                >
                            </div>

                            <div class="col-span-5">
                                <input
                                    type="number"
                                    name="potongan[]"
                                    value="{{ $price->potongan }}"
                                    placeholder="Potongan / pcs"
                                    class="w-full rounded-xl border border-slate-300 p-2.5 text-sm focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 outline-none transition"
                                >
                            </div>

                            <div class="col-span-2">
                                @if($index === 0)
                                    <button type="button" onclick="addGrosirRow()" class="w-full bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl h-full flex items-center justify-center font-bold text-lg transition duration-200">+</button>
                                @else
                                    <button type="button" onclick="this.closest('.grosir-row').remove()" class="w-full bg-red-500 hover:bg-red-600 text-white rounded-xl h-full flex items-center justify-center font-bold text-lg transition duration-200">✕</button>
                                @endif
                            </div>
                        </div>
                    @endforeach
                @else
                    <!-- Tampilan default baris pertama kosong jika produk belum punya harga grosir -->
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
                            <button type="button" onclick="addGrosirRow()" class="w-full bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl h-full flex items-center justify-center font-bold text-lg transition duration-200">+</button>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div> --}}
    {{-- GROSIRAN end --}}

    {{-- <x-input
        label="Harga Diskon"
        name="harga_diskon"
        type="number"
        icon="ri-discount-percent-line"
        :value="$product->harga_diskon"
    /> --}}
    <x-select
    label="Supplier"
    name="supplier_id"
    icon="ri-truck-line"
    required
>
    <option value="">-- Pilih Supplier --</option>
    @foreach($suppliers as $supplier)
        <option
            value="{{ $supplier->id }}"
            @selected(old('supplier_id', $product->supplier_id) == $supplier->id) 
        >
            {{ $supplier->nama }} ({{ $supplier->kode }})
        </option>
    @endforeach
</x-select>
        
    <x-input
        label="Minimum Stok"
        name="min_stok"
        type="number"
        icon="ri-alarm-warning-line"
        :value="$product->min_stok"
        required
    />

    <x-input
        label="Satuan"
        name="satuan"
        icon="ri-ruler-line"
        :value="$product->satuan"
        required
    />

    <x-input
        label="Stok Saat Ini"
        name="stok"
        icon="ri-archive-line"
        :value="$product->stok"
        readonly
    />

    <x-textarea
        label="Catatan"
        name="catatan"
        icon="ri-todo-line"
        :value="$product->catatan"
    />

    <x-checkbox
        label="Aktif ( Status Produk )"
        name="is_active"
        :checked="$product->is_active"
        
    />

    

    

</div>

<div class="flex justify-end gap-3 mt-8">

    <a href="/products">

        <x-button color="gray">

            <i class="ri-close-line"></i>

            Batal

        </x-button>

    </a>

    <x-button
        color="primary"
        type="submit"
    >

        <i class="ri-save-line"></i>

        Simpan Perubahan

    </x-button>

</div>

</form>

</x-card>
<!-- SCRIPT JAVASCRIPT UNTUK TAMBAH BARIS DINAMIS -->
    <script>
        function addGrosirRow() {
            const container = document.getElementById('grosir-container');
            const newRow = document.createElement('div');
            newRow.className = 'grid grid-cols-12 gap-2 mb-2 grosir-row';
            newRow.innerHTML = `
                <div class="col-span-5">
                    <input type="number" name="min_qty[]" placeholder="Min Qty" class="w-full rounded-xl border border-slate-300 p-2.5 text-sm focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 outline-none transition">
                </div>
                <div class="col-span-5">
                    <input type="number" name="potongan[]" placeholder="Potongan / pcs" class="w-full rounded-xl border border-slate-300 p-2.5 text-sm focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 outline-none transition">
                </div>
                <div class="col-span-2">
                    <button type="button" onclick="this.closest('.grosir-row').remove()" class="w-full bg-red-500 hover:bg-red-600 text-white rounded-xl h-full flex items-center justify-center font-bold text-lg transition duration-200">✕</button>
                </div>
            `;
            container.appendChild(newRow);
        }

        document.addEventListener('DOMContentLoaded', function() {
    
           
            // 3. VALIDASI KETAT PADA FORM TARGET ID
            const formProduk = document.getElementById('formProduk');
            if (formProduk) {
                formProduk.addEventListener('submit', function(e) {
                    // KUNCI GERBANG UTAMA SECARA INSTAN!
                    e.preventDefault();
                    e.stopPropagation(); 

                    const formElement = this;

                    // Ambil element input berdasarkan attribute name komponen blade secara akurat
                    const elBeli = formElement.querySelector('input[name="harga_beli"]');
                    const elJual = formElement.querySelector('input[name="harga"]');
                    
                    let numericHargaBeli = elBeli ? Number(elBeli.value || 0) : 0;
                    let numericHargaJual = elJual ? Number(elJual.value || 0) : 0;

                    // ==========================================
                    // SELEKSI 1: VALIDASI HARGA BELI & HARGA JUAL
                    // ==========================================
                    if (numericHargaBeli <= 0 || numericHargaJual <= 0) {
                        Swal.fire({ 
                            icon: 'error', 
                            title: 'Harga Tidak Valid!',
                            text: 'Harga Beli (HPP) dan Harga Jual wajib diisi dan nilainya tidak boleh Rp 0!',
                            confirmButtonText: 'Perbaiki Data',
                            confirmButtonColor: '#4f46e5',
                            returnFocus: false
                        }).then(() => {
                            if (numericHargaBeli <= 0 && elBeli) {
                                elBeli.focus();
                            } else if (elJual) {
                                elJual.focus();
                            }
                        });
                        return false; 
                    }

                    // ==========================================
                    // SELEKSI 2: VALIDASI DATA GROSIR 
                    // ==========================================
                    const minQtyInputs = formElement.querySelectorAll('input[name="min_qty[]"]');
                    const potonganInputs = formElement.querySelectorAll('input[name="potongan[]"]');

                    for (let i = 0; i < minQtyInputs.length; i++) {
                        let qtyVal = minQtyInputs[i].value.trim();
                        let potonganVal = potonganInputs[i].value.trim();

                        let numericQty = Number(qtyVal || 0);
                        let numericPotongan = Number(potonganVal || 0);

                        if (qtyVal !== "" || potonganVal !== "" || numericQty > 0 || numericPotongan > 0) {
                            if (numericQty <= 0 || numericPotongan <= 0) {
                                Swal.fire({
                                    icon: 'warning',
                                    title: 'Data Grosir Belum Lengkap',
                                    text: `Pada level grosir baris ke-${i + 1}, nilai 'Min Qty' dan 'Potongan' harus diisi lebih dari 0!`,
                                    confirmButtonText: 'Perbaiki',
                                    confirmButtonColor: '#4f46e5',
                                    returnFocus: false
                                }).then(() => {
                                    if (numericQty <= 0) {
                                        minQtyInputs[i].focus();
                                    } else {
                                        potonganInputs[i].focus();
                                    }
                                });
                                return false; 
                            }
                        }
                    }

                    // ==========================================
                    // KELULUSAN FINAL: SUBMIT SECARA MANUAL
                    // ==========================================
                    formElement.submit();
                });
            }
        });
    </script>
@endsection