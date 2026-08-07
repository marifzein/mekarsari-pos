{{-- @extends('layouts.app') --}}

@extends(
    preg_match('/(android|bb\d+|meego).+mobile|avantgo|bada\/|blackberry|blazer|compal|elaine|fennec|hiptop|iemobile|ip(hone|od)|iris|kindle|lge |maemo|midp|mmp|mobile.+firefox|netfront|opera m(ob|in)i|palm( os)?|phone|p(ixi|re)\/|plucker|pocket|psp|series(4|6)0|symbian|treo|up\.(browser|link)|vodafone|wap|windows ce|xda|xiino/i', request()->header('User-Agent')) 
    || preg_match('/(ipad|tablet|(android(?!.*mobile)))/i', request()->header('User-Agent')) 
    ? 'layouts.mobile-app' 
    : 'layouts.app'
)

@section('title','Master Produk')

@section('content')

<x-page-header

    title="Kartu Stok"

    subtitle="Monitoring mutasi stok"

>

    

</x-page-header>


<x-card>

    {{-- Toolbar --}}
    {{-- <div class="flex justify-between items-center mb-6"> --}}
    <div class="mb-6">    
        <form
            method="GET"
            action="{{ route('stock-cards.index') }}"
            class="flex flex-col md:flex-row gap-3 md:items-center"
        >

            
            {{-- Search --}}
            <div class="w-full md:flex-1">
                <x-search-box-mobile

                    name="search"

                    :value="request('search')"

                    placeholder="Cari produk..."

                />

                
                
            </div>
            
            
            {{-- Kategori & Stock Filter --}}
            <div class="flex flex-col sm:flex-row gap-2 w-full md:w-auto">
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
            </div>    
        </form>

    </div>

    <!-- TAMPILAN DESKTOP (TABEL) -->
    <div class="hidden md:block">
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
                    <x-table-head class="text-center">View</x-table-head>
                    
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
                                href="/stock-cards/{{ $product->id }}"
                            >

                                <x-button

                                    color="blue"

                                    size="sm"

                                >

                                    <i class="ri-file-chart-line"></i>

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

                            description="Tambah Produk untuk membuat data baru."

                        />

                    </td>

                </tr>

            @endforelse

            </tbody>

        </x-table>
    </div>
    
    <!-- TAMPILAN MOBILE & TABLET (CARD LIST) -->
    <div class="block md:hidden space-y-3">
        @forelse($products as $product)
        <div class="bg-slate-50 border border-slate-200/80 rounded-2xl p-4 shadow-3xs flex flex-col justify-between gap-3">
            <div class="flex justify-between items-start">
                <div>
                    <h4 class="font-bold text-slate-800 text-base">{{ $product->nama_barang }}</h4>
                    <p class="text-xs font-mono text-slate-500 mt-0.5"><i class="ri-barcode-line"></i> {{ $product->barcode ?: '-' }}</p>
                </div>
                <div>
                    @if($product->stok > 0)
                        <x-badge color="green">Tersedia</x-badge>
                    @else
                        <x-badge color="red">Habis</x-badge>
                    @endif
                </div>
            </div>

            <div class="flex justify-between items-center text-xs bg-white p-2.5 rounded-xl border border-slate-100">
                <span class="text-slate-500">Harga: <strong class="text-slate-700">Rp {{ number_format($product->harga, 0, ',', '.') }}</strong></span>
                <span class="text-slate-500">Sisa Stok: 
                    @if($product->stok <= 5)
                        <x-badge color="red">{{ $product->stok }}</x-badge>
                    @elseif($product->stok <= 15)
                        <x-badge color="yellow">{{ $product->stok }}</x-badge>
                    @else
                        <x-badge color="green">{{ $product->stok }}</x-badge>
                    @endif
                </span>
            </div>

            <div class="flex items-center justify-end pt-2 border-t border-slate-200/60">
                <a href="/stock-cards/{{ $product->id }}" class="w-full">
                    <x-button color="blue" size="sm" class="w-full justify-center">
                        <i class="ri-file-chart-line"></i> Lihat Riwayat Stok
                    </x-button>
                </a>
            </div>
        </div>
        @empty
        <x-empty-state
            icon="ri-box-3-line"
            title="Belum ada produk"
            description="Tambah Produk untuk membuat data baru."
        />
        @endforelse
    </div>

    {{-- end mobile view --}}




    <div class="mt-6">

        {{ $products->links() }}

    </div>

</x-card>

@endsection