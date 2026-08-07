@extends('layouts.app')

@section('title','Master Produk')

@section('content')

<x-page-header

    title="Master Produk"

    subtitle="Kelola seluruh data produk minimarket"

>

@php
    // Helper untuk mempertahankan seluruh filter yang sedang aktif saat sort diklik
    $getSortLink = function($column) use ($sortBy, $sortDir) {
        $nextDir = ($sortBy === $column && $sortDir === 'asc') ? 'desc' : 'asc';
        return request()->fullUrlWithQuery([
            'sort_by'  => $column,
            'sort_dir' => $nextDir,
            'page'     => 1 // Reset ke halaman 1 tiap ganti sort
        ]);
    };
    
    // Helper render panah indikator
    $renderArrow = function($column) use ($sortBy, $sortDir) {
        if ($sortBy !== $column) return '';
        return $sortDir === 'asc' ? ' ▲' : ' ▼';
    };
@endphp


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

    <form
        method="GET"
        action="{{ url('/products') }}"
        class="flex gap-3 items-center"
    >

        {{-- Search --}}
        <x-search-box

            name="search"

            :value="request('search')"

            placeholder="Cari produk..."

        />
       

        {{-- Kategori --}}
        <x-select
            name="category"
            class="w-40"
        >

            <option value="">

                Semua Kategori

            </option>

            @foreach($categories as $category)

                <option

                    value="{{ $category->id }}"

                    @selected(request('category')==$category->id)

                >

                    {{ $category->name }}

                </option>

            @endforeach

        </x-select>

        {{-- Stock --}}

        <x-select
            name="stock"
            class="w-40"
        >

            <option value="">

                Semua Stok

            </option>

            <option
                value="available"
                @selected(request('stock')=='available')
            >
                Tersedia
            </option>

            <option
                value="low"
                @selected(request('stock')=='low')
            >
                Menipis
            </option>

            <option
                value="empty"
                @selected(request('stock')=='empty')
            >
                Habis
            </option>

        </x-select>

        <x-button
            color="gray"
            type="submit"
        >

            <i class="ri-filter-3-line"></i>

            Filter

        </x-button>

    </form>

</div>


    <x-table >

       
        <x-table-header>
            <tr>
                <!-- Kolom Produk -->
                <x-table-head class="p-0 text-left hover:bg-slate-200 transition">
                    <a href="{{ $getSortLink('nama_barang') }}" class="block p-0 w-full h-full font-bold">
                        Kode Barang<span class="text-blue-600 text-xs">{{ $renderArrow('kode_barang') }}</span>
                    </a>
                </x-table-head>

                <x-table-head class="p-0 text-left hover:bg-slate-200 transition">
                    <a href="{{ $getSortLink('barcode') }}" class="block p-0 w-full h-full font-bold">
                        Nama<span class="text-blue-600 text-xs">{{ $renderArrow('barcode') }}</span>
                    </a>
                </x-table-head>

                <!-- Kolom Barcode -->
                <x-table-head class="p-0 text-left hover:bg-slate-200 transition">
                    <a href="{{ $getSortLink('barcode') }}" class="block p-0 w-full h-full font-bold">
                        Barcode<span class="text-blue-600 text-xs">{{ $renderArrow('barcode') }}</span>
                    </a>
                </x-table-head>

                @can('akses-owner-admin')
                <!-- Kolom Harga Beli -->
                <x-table-head class="p-0 text-right hover:bg-slate-200 transition">
                    <a href="{{ $getSortLink('harga_beli') }}" class="block p-0 w-full h-full font-bold">
                        Harga Beli<span class="text-blue-600 text-xs">{{ $renderArrow('harga_beli') }}</span>
                    </a>
                </x-table-head>
                @endcan

                <!-- Kolom Harga Jual -->
                <x-table-head class="p-0 text-right hover:bg-slate-200 transition">
                    <a href="{{ $getSortLink('harga') }}" class="block p-0 w-full h-full font-bold">
                        Harga Jual<span class="text-blue-600 text-xs">{{ $renderArrow('harga') }}</span>
                    </a>
                </x-table-head>

                <!-- Kolom Stok -->
                <x-table-head class="p-0 text-right hover:bg-slate-200 transition">
                    <a href="{{ $getSortLink('stok') }}" class="block p-0 w-full h-full font-bold">
                        Stock<span class="text-blue-600 text-xs">{{ $renderArrow('stok') }}</span>
                    </a>
                </x-table-head>

                <!-- Kolom Non-Sortable (Status & Aksi) -->
                <x-table-head class="text-center p-3 font-bold">Status</x-table-head>
                <x-table-head class="text-center p-3 font-bold">Aksi</x-table-head>
            </tr>
        </x-table-header>  
        

        <tbody>

        @forelse($products as $product)

            <tr>
                <x-table-cell class="text-left">

                    {{-- <div class="font-semibold"> --}}

                        {{ $product->kode_barang }} 

                    {{-- </div> --}}

                </x-table-cell>

                <x-table-cell class="text-left">

                    {{-- <div class="font-semibold"> --}}

                        {{ $product->nama_barang }} 

                    {{-- </div> --}}

                </x-table-cell>

                <x-table-cell class="text-left">

                    {{ $product->barcode }}

                </x-table-cell>

                @can('akses-owner-admin')
                <x-table-cell class="text-right">
                    @if(!$product->harga_beli || $product->harga_beli == 0)
                        <span class="text-red-600 font-bold bg-red-50 px-2 py-1 rounded border border-red-200 text-xs inline-block animate-pulse">
                            ⚠️ Rp 0
                        </span>
                    @else
                        Rp {{ number_format($product->harga_beli, 0, ',', '.') }}
                    @endif
                </x-table-cell>
                @endcan

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

                        {{-- <a
                            href="/products/{{ $product->id }}/stock-card"
                        > --}}
                        <a href="{{ url('/stock-cards/' . $product->id) }}">

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