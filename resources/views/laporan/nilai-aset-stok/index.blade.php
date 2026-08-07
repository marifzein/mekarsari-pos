@extends('layouts.app')

@section('title', 'Laporan Nilai Aset Stok')

@section('content')
<div class="max-w-7xl mx-auto p-6">
    <!-- Ringkasan Nilai Aset Utama -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
        <div class="bg-white rounded-xl shadow p-6 border-l-4 border-indigo-600">
            <div class="text-sm font-medium text-gray-500 uppercase tracking-wider">Total Modal Aset Toko (HPP)</div>
            <div class="mt-2 text-3xl font-bold text-gray-900">Rp {{ number_format($totalAsetToko->grand_total_aset ?? 0, 0, ',', '.') }}</div>
            <p class="text-xs text-gray-400 mt-1">*Nilai real uang modal yang tertanam di dalam barang di rak.</p>
        </div>
        <div class="bg-white rounded-xl shadow p-6 border-l-4 border-emerald-600">
            <div class="text-sm font-medium text-gray-500 uppercase tracking-wider">Potensi Nilai Jual (Omset)</div>
            <div class="mt-2 text-3xl font-bold text-emerald-600">Rp {{ number_format($totalAsetToko->grand_total_jual ?? 0, 0, ',', '.') }}</div>
            <p class="text-xs text-gray-400 mt-1">*Estimasi omset kotor jika semua barang saat ini lunas terjual.</p>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow p-6">
        <h1 class="text-2xl font-bold mb-6">Laporan Nilai Aset Stok</h1>

        <!-- Form Filter & Aksi -->
        <form method="GET" action="/laporan/nilai-aset-stok" class="flex flex-wrap gap-4 items-end mb-6">
            <!-- 💡 Hidden input agar sorting tidak hilang saat melakukan pencarian kata -->
            <input type="hidden" name="sort_by" value="{{ $sortBy }}">
            <input type="hidden" name="sort_dir" value="{{ $sortDir }}">

            <div class="flex-1 min-w-[300px]">
                <label class="block text-sm font-medium text-gray-700">Cari Produk</label>
                <input type="text" name="search" value="{{ $search }}" placeholder="Nama barang atau kode barcode..." class="mt-1 block w-full rounded-xl border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
            </div>

            <div class="flex gap-2 items-center">
                <x-button type="submit" color="blue" size="sm" class="items-center">
                    Cari Data
                </x-button>
                
                <a href="{{ request()->fullUrlWithQuery(['export' => 'excel']) }}">
                    <x-button type="button" color="green" size="sm" class="items-center">
                        <i class="ri-file-excel-2-line"></i> Export XLS
                    </x-button>
                </a>
                
                <a href="{{ request()->fullUrlWithQuery(['export' => 'pdf']) }}" target="_blank">
                    <x-button type="button" color="red" size="sm" class="items-center">
                        <i class="ri-file-pdf-line"></i> Cetak PDF
                    </x-button>
                </a>
            </div>
        </form>

        <!-- 💡 Helper Generator Link Sort Dinamis (Sama Seperti Menu Sales Produk) -->
        @php
            $getSortLink = function($column) use ($sortBy, $sortDir, $search) {
                $nextDir = ($sortBy === $column && $sortDir === 'asc') ? 'desc' : 'asc';
                return request()->fullUrlWithQuery([
                    'sort_by' => $column,
                    'sort_dir' => $nextDir,
                    'search'  => $search,
                    'page'    => 1
                ]);
            };
            
            $renderArrow = function($column) use ($sortBy, $sortDir) {
                if ($sortBy !== $column) return '';
                return $sortDir === 'asc' ? ' ▲' : ' ▼';
            };
        @endphp

        <!-- Tabel Laporan dengan Link Sorting -->
        <table class="w-full border-collapse border border-gray-200">
            <thead>
                <tr class="bg-slate-100 border-b border-gray-200 text-gray-700 text-sm select-none">
                    <th class="p-3 text-left border border-gray-200 w-12 font-bold">No</th>
                    
                    <th class="p-0 border border-gray-200 hover:bg-slate-200 transition">
                        <a href="{{ $getSortLink('kode_barang') }}" class="block p-3 text-left w-full h-full font-bold">
                            Kode Barang<span class="text-blue-600 text-xs">{{ $renderArrow('kode_barang') }}</span>
                        </a>
                    </th>
                    <th class="p-0 border border-gray-200 hover:bg-slate-200 transition">
                        <a href="{{ $getSortLink('nama_barang') }}" class="block p-3 text-left w-full h-full font-bold">
                            Nama Barang<span class="text-blue-600 text-xs">{{ $renderArrow('nama_barang') }}</span>
                        </a>
                    </th>
                    <th class="p-0 border border-gray-200 hover:bg-slate-200 transition">
                        <a href="{{ $getSortLink('stok') }}" class="block p-3 text-center w-full h-full font-bold">
                            Stok Aktif<span class="text-blue-600 text-xs">{{ $renderArrow('stok') }}</span>
                        </a>
                    </th>
                    <th class="p-0 border border-gray-200 hover:bg-slate-200 transition">
                        <a href="{{ $getSortLink('hpp_average') }}" class="block p-3 text-right w-full h-full font-bold">
                            HPP Avg<span class="text-blue-600 text-xs">{{ $renderArrow('hpp_average') }}</span>
                        </a>
                    </th>
                    <th class="p-0 border border-gray-200 hover:bg-slate-200 transition">
                        <a href="{{ $getSortLink('harga_jual') }}" class="block p-3 text-right w-full h-full font-bold">
                            Harga Jual<span class="text-blue-600 text-xs">{{ $renderArrow('harga_jual') }}</span>
                        </a>
                    </th>
                    <th class="p-0 border border-gray-200 hover:bg-slate-200 transition bg-indigo-50">
                        <a href="{{ $getSortLink('total_nilai_aset') }}" class="block p-3 text-right w-full h-full font-bold text-indigo-900">
                            Total Nilai Aset<span class="text-blue-600 text-xs">{{ $renderArrow('total_nilai_aset') }}</span>
                        </a>
                    </th>
                    <th class="p-0 border border-gray-200 hover:bg-slate-200 transition">
                        <a href="{{ $getSortLink('total_potensi_omset') }}" class="block p-3 text-right w-full h-full font-bold">
                            Potensi Jual<span class="text-blue-600 text-xs">{{ $renderArrow('total_potensi_omset') }}</span>
                        </a>
                    </th>
                </tr>
            </thead>
            <tbody class="text-sm text-gray-600">
                @forelse($reportData as $index => $row)
                    <tr class="hover:bg-gray-50 border-b border-gray-200">
                        <td class="p-3 border border-gray-200 text-center">
                            {{ $reportData->firstItem() + $index }}
                        </td>
                        <td class="p-3 border border-gray-200 font-mono text-xs">{{ $row->kode_barang }}</td>
                        <td class="p-3 border border-gray-200 font-medium text-gray-900">{{ $row->nama_barang }}</td>
                        <td class="p-3 border border-gray-200 text-center font-bold text-slate-800">{{ number_format($row->stok, 0, ',', '.') }}</td>
                        <td class="p-3 border border-gray-200 text-right">Rp {{ number_format($row->hpp_average, 0, ',', '.') }}</td>
                        <td class="p-3 border border-gray-200 text-right">Rp {{ number_format($row->harga_jual, 0, ',', '.') }}</td>
                        <td class="p-3 border border-gray-200 text-right bg-indigo-50/50 font-semibold text-indigo-700">Rp {{ number_format($row->total_nilai_aset, 0, ',', '.') }}</td>
                        <td class="p-3 border border-gray-200 text-right font-medium text-slate-900">Rp {{ number_format($row->total_potensi_omset, 0, ',', '.') }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="p-4 text-center text-gray-500">Tidak ada persediaan barang terdeteksi.</td>
                    </tr>
                @endforelse
            </tbody>
            
            @if($reportData->count() > 0)
            <tfoot class="bg-slate-50 font-bold text-sm text-gray-800">
                <tr>
                    <td colspan="6" class="p-3 border border-gray-200 text-right">TOTAL KESELURUHAN STOK TOKO:</td>
                    <td class="p-3 border border-gray-200 text-right text-indigo-700 text-base bg-indigo-50">
                        Rp {{ number_format($totalAsetToko->grand_total_aset ?? 0, 0, ',', '.') }}
                    </td>
                    <td class="p-3 border border-gray-200 text-right text-emerald-600 text-base">
                        Rp {{ number_format($totalAsetToko->grand_total_jual ?? 0, 0, ',', '.') }}
                    </td>
                </tr>
            </tfoot>
            @endif
        </table>

        <div class="mt-4">
            {{ $reportData->links() }}
        </div>
    </div>
</div>
@endsection