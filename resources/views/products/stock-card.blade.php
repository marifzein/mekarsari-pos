@extends('layouts.app')

@section('title','Kartu Stok')

@section('content')

<h2
    class="text-2xl font-bold mb-4"
>
    Kartu Stok
</h2>

<div
    class="bg-white p-4 rounded-xl shadow mb-4"
>

    <div>

        <b>Kode :</b>

        {{ $product->kode_barang }}

    </div>

    <div>

        <b>Nama :</b>

        {{ $product->nama_barang }}

    </div>

    <div>

        <b>Stok Saat Ini :</b>

        {{ $product->stok }}

    </div>

</div>

<div
    class="bg-white rounded-xl shadow"
>

<table class="w-full">

    <thead>

    <tr
        class="bg-slate-100"
    >

        <th class="p-3 text-left">
            Tanggal
        </th>

        <th class="p-3 text-left">
            Tipe
        </th>

        <th class="p-3 text-right">
            Qty
        </th>

        <th class="p-3 text-right">
            Sebelum
        </th>

        <th class="p-3 text-right">
            Sesudah
        </th>

        <th class="p-3 text-left">
            Referensi
        </th>

    </tr>

    </thead>

    <tbody>

    @forelse($movements as $row)

    <tr class="border-b">

        <td class="p-3">

            {{ $row->created_at }}

        </td>

        <td class="p-3">

            {{ $row->type }}

        </td>

        <td
            class="p-3 text-right
            {{ $row->qty > 0 ? 'text-green-600' : 'text-red-600' }}"
        >

            {{ $row->qty }}

        </td>

        <td class="p-3 text-right">

            {{ $row->stock_before }}

        </td>

        <td class="p-3 text-right">

            {{ $row->stock_after }}

        </td>

        <td class="p-3">

            {{ $row->reference_no }}

        </td>

    </tr>

    @empty

    <tr>

        <td
            colspan="6"
            class="p-6 text-center"
        >

            Belum ada pergerakan stok

        </td>

    </tr>

    @endforelse

    </tbody>

</table>

</div>

@endsection