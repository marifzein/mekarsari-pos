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
    action="/products"
    class="bg-white p-6 rounded-xl shadow"
>

    @csrf

    <div class="mb-4">

        <label>
            Kode Barang
        </label>

        <input
            type="text"
            name="kode_barang"
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
            value="0"
            min="0"
            required
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

        Simpan Produk

    </button>

</form>

@endsection