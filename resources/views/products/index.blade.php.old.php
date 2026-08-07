@extends('layouts.app')

@section('title','Master Produk')

@section('content')

<x-page-header

    title="Master Produk"

    subtitle="Kelola seluruh data produk minimarket"

>

    <x-slot:action>

        <a href="/products/create">

            <x-button color="primary">

                <i class="ri-add-line"></i>

                Tambah Produk

            </x-button>

        </a>

    </x-slot:action>

</x-page-header>


<x-card>

    {{-- Toolbar --}}
    <div class="flex justify-between items-center mb-6">

        {{-- <div class="relative w-80"> --}}
        <form
            method="GET"
            action="{{ url('/products') }}"
            class="relative w-80"
        >
            <i
                class="ri-search-line
                absolute left-4 top-1/2
                -translate-y-1/2
                text-slate-400"
            ></i>

            <input

                type="text"

                name="search"

                value="{{ request('search') }}"

                placeholder="Cari produk..."

                class="w-full
                rounded-xl
                border
                border-slate-300
                pl-11
                pr-4
                py-3
                focus:border-indigo-500
                focus:ring-4
                focus:ring-indigo-100"

            >

        </form>

    </div>


    <x-table >

        {{-- <thead> --}}
        <x-table-header >

            <tr>
                 
                {{-- <th class="text-left  py-4 px-3">Produk</th> --}}
                <x-table-head class="text-left">Produk</x-table-head>
                <x-table-head class="text-left">Barcode</x-table-head>
                <x-table-head class="text-right">Harga</x-table-head>
                <x-table-head class="text-right">Stock</x-table-head>
                <x-table-head class="text-center">Status</x-table-head>
                <x-table-head class="text-center">Aksi</x-table-head>

                {{-- <th class="text-left  py-4">Barcode</th> --}}

                {{-- <th class="text-right  py-4">Harga</th> --}}

                {{-- <th class="text-right  py-4">Stock</th> --}}

                {{-- <th class="text-center  py-4">Status</th>

                <th class="text-center  py-4">Aksi</th> --}}

            </tr>
        </x-table-header >    
        {{-- </thead> --}}

        <tbody>

        @forelse($products as $product)

            <tr>

                <x-table-cell class="text-left">

                    {{-- <div class="font-semibold"> --}}

                        {{ $product->nama_barang }} 

                    {{-- </div> --}}

                </x-table-cell>

                <x-table-cell class="text-left">

                    {{ $product->barcode }}

                </x-table-cell>

                

                <x-table-cell class="text-right">

                    Rp {{ number_format($product->harga,0,',','.') }}

                </x-table-cell>

                <x-table-cell class="text-right">

                    @if($product->stok <= 5)

                        <x-badge color="red">

                            {{ $product->stok }}

                        </x-badge>

                    @elseif($product->stok <= 15)

                        <x-badge color="yellow">

                            {{ $product->stok }}

                        </x-badge>

                    @else

                        <x-badge color="green">

                            {{ $product->stok }}

                        </x-badge>

                    @endif

                </x-table-cell>

                <x-table-cell class="text-center">

                    @if($product->stok > 0)

                        <x-badge color="green">

                            Tersedia

                        </x-badge>

                    @else

                        <x-badge color="red">

                            Habis

                        </x-badge>

                    @endif

                </x-table-cell>

                <x-table-cell class="text-center">

                    <div class="flex justify-center gap-2">

                        <a
                            href="/products/{{ $product->id }}/stock-card"
                        >

                            <x-button

                                color="gray"

                                size="sm"

                            >

                                <i class="ri-file-chart-line"></i>

                            </x-button>

                        </a>

                        <a
                            href="/products/{{ $product->id }}/edit"
                        >

                            <x-button

                                color="blue"

                                size="sm"

                            >

                                <i class="ri-edit-line"></i>

                            </x-button>

                        </a>

                    </div>

                </x-table-cell>

            </tr>

        @empty

            <tr>

                <td colspan="7">

                    <x-empty-state

                        icon="ri-box-3-line"

                        title="Belum ada produk"

                        description="Klik tombol Tambah Produk untuk membuat data baru."

                    />

                </td>

            </tr>

        @endforelse

        </tbody>

    </x-table>

    <div class="mt-6">

        {{ $products->links() }}

    </div>

</x-card>

@endsection