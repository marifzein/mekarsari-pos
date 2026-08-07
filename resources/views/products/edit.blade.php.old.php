@extends('layouts.app')

@section('title','Tambah Produk')

@section('content')

<h2
    class="text-2xl font-bold mb-6"
>
    Tambah Produk
</h2>

<form
    method="POST"
    action="/products/{{ $product->id }}"
    class="bg-white p-6 rounded-xl shadow"
>

    @csrf
    @method('PUT')

    <div class="mb-4">

        <label>
            Kode Barang
        </label>

        <input
            type="text"
            name="kode_barang"
            value="{{ $product->kode_barang }}"
            required
            class="w-full border rounded p-2"
        >

    </div>

    <div class="mb-4">

        <label>
            Barcode
        </label>

        <input
            type="text"
            name="barcode"
            value="{{ $product->barcode }}"
            class="w-full border rounded p-2"
        >

    </div>

    <div class="mb-4">

        <label>
            Nama Barang
        </label>

        <input
            type="text"
            name="nama_barang"
            value="{{ $product->nama_barang }}"
            required
            class="w-full border rounded p-2"
        >

    </div>

    <div class="mb-4">

        <label>
            Harga
        </label>

        <input
            type="number"
            name="harga"
            value="{{ $product->harga }}"
            required
            min="0"
            class="w-full border rounded p-2"
        >

    </div>

    <div class="mb-4">

        <label>
            Harga Diskon
        </label>

        <input
            type="number"
            name="harga_diskon"
            value="{{ $product->harga_diskon }}"
            min="0"
            class="w-full border rounded p-2"
        >

    </div>

    <div class="mb-4">

        <label>
            Stok Awal
        </label>

        <input
            type="number"
            name="stok"
            value="{{ $product->stok }}"
            readonly
            class="w-full border rounded p-2"
        >

    </div>

    <button
        class="bg-indigo-600
              text-white
              px-5
              py-2
              rounded"
    >

        Update Produk

    </button>

</form>

@endsection