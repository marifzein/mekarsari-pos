@extends('layouts.app')

@section('title', 'Laporan Penjualan Per Produk')

@section('content')
<div class="max-w-7xl mx-auto p-6">
    <div class="bg-white rounded-xl shadow p-6">
        <h1 class="text-2xl font-bold mb-6">Laporan Penjualan Per Produk</h1>

        <!-- Form Filter Tanggal (Sertakan parameter sort tersembunyi agar tidak hilang saat ganti tanggal) -->
        <form method="GET" action="/laporan/penjualan-produk" class="flex flex-wrap gap-4 items-end mb-6">
            <input type="hidden" name="sort_by" value="{{ $sortBy }}">
            <input type="hidden" name="sort_dir" value="{{ $sortDir }}">
            
            <div>
                <label class="block text-sm font-medium text-gray-700">Tanggal Mulai</label>
                <input type="date" name="start_date" value="{{ $startDate }}" class="mt-1 block w-full rounded-xl border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-700">Tanggal Selesai</label>
                <input type="date" name="end_date" value="{{ $endDate }}" class="mt-1 block w-full rounded-xl border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
            </div>

            <!-- Container Tombol: Rapi, Sejajar, & Presisi Mengikuti Tinggi Input -->
            <div class="flex gap-2 items-center">
                <!-- Filter Data Component -->
                <x-button type="submit" color="blue" size="sm" class="items-center">
                    Filter Data
                </x-button>
                
                <!-- Export XLS Component -->
                <a href="{{ request()->fullUrlWithQuery(['export' => 'excel']) }}">
                    <x-button type="button" color="green" size="sm" class="items-center">
                        <i class="ri-file-excel-2-line"></i> Export XLS
                    </x-button>
                </a>
                
                <!-- Cetak PDF Component -->
                <a href="{{ request()->fullUrlWithQuery(['export' => 'pdf']) }}" target="_blank">
                    <x-button type="button" color="red" size="sm" class="items-center">
                        <i class="ri-file-pdf-line"></i> Cetak PDF
                    </x-button>
                </a>
            </div>
        </form>

        <!-- Helper Generator Link Sort Dinamis -->
        @php
            $getSortLink = function($column) use ($sortBy, $sortDir, $startDate, $endDate) {
                $nextDir = ($sortBy === $column && $sortDir === 'asc') ? 'desc' : 'asc';
                return request()->fullUrlWithQuery([
                    'sort_by' => $column,
                    'sort_dir' => $nextDir,
                    'start_date' => $startDate,
                    'end_date' => $endDate,
                    'page' => 1 // Reset kembali ke halaman 1 tiap kali ganti sort
                ]);
            };
            
            $renderArrow = function($column) use ($sortBy, $sortDir) {
                if ($sortBy !== $column) return '';
                return $sortDir === 'asc' ? ' ▲' : ' ▼';
            };
        @endphp

        <!-- Tabel Laporan -->
        <!-- Tabel Laporan -->
        <table class="w-full border-collapse border border-gray-200">
            <thead>
                <tr class="bg-slate-100 border-b border-gray-200 text-gray-700 text-sm select-none">
                    <th class="p-3 text-left border border-gray-200 w-12">No</th>
                    <th class="p-0 border border-gray-200 hover:bg-slate-200 transition">
                        <a href="{{ $getSortLink('kode_barang') }}" class="block p-3 text-left w-full h-full font-bold">
                            Kode<span class="text-blue-600 text-xs">{{ $renderArrow('kode_barang') }}</span>
                        </a>
                    </th>
                    <th class="p-0 border border-gray-200 hover:bg-slate-200 transition">
                        <a href="{{ $getSortLink('nama_barang') }}" class="block p-3 text-left w-full h-full font-bold">
                            Nama Barang<span class="text-blue-600 text-xs">{{ $renderArrow('nama_barang') }}</span>
                        </a>
                    </th>
                    <th class="p-0 border border-gray-200 hover:bg-slate-200 transition">
                        <a href="{{ $getSortLink('harga') }}" class="block p-3 text-right w-full h-full font-bold">
                            Harga Jual<span class="text-blue-600 text-xs">{{ $renderArrow('harga') }}</span>
                        </a>
                    </th>
                    <th class="p-0 border border-gray-200 hover:bg-slate-200 transition">
                        <a href="{{ $getSortLink('total_terjual') }}" class="block p-3 text-center w-full h-full font-bold">
                            Terjual<span class="text-blue-600 text-xs">{{ $renderArrow('total_terjual') }}</span>
                        </a>
                    </th>
                    <th class="p-0 border border-gray-200 hover:bg-slate-200 transition">
                        <a href="{{ $getSortLink('total_pendapatan') }}" class="block p-3 text-right w-full h-full font-bold">
                            Pendapatan<span class="text-blue-600 text-xs">{{ $renderArrow('total_pendapatan') }}</span>
                        </a>
                    </th>
                    <!-- Kolom Tambahan profit -->
                    <th class="p-3 text-right border border-gray-200 font-bold">HPP</th>
                    <th class="p-0 border border-gray-200 hover:bg-slate-200 transition">
                        <a href="{{ $getSortLink('laba_kotor') }}" class="block p-3 text-right w-full h-full font-bold">
                            Laba Kotor<span class="text-blue-600 text-xs">{{ $renderArrow('laba_kotor') }}</span>
                        </a>
                    </th>
                    <th class="p-3 text-center border border-gray-200 font-bold">Margin</th>
                </tr>
            </thead>
            <tbody class="text-sm text-gray-600">
                @forelse($reportData as $index => $row)
                    @php 
                        $margin = $row->total_pendapatan > 0 ? ($row->laba_kotor / $row->total_pendapatan) * 100 : 0;
                    @endphp
                    <tr class="hover:bg-gray-50 border-b border-gray-200">
                        <td class="p-3 border border-gray-200 text-center">
                            {{ $reportData->firstItem() + $index }}
                        </td>
                        <td class="p-3 border border-gray-200 font-mono">{{ $row->kode_barang }}</td>
                        <td class="p-3 border border-gray-200 font-medium text-gray-900">{{ $row->nama_barang }}</td>
                        <td class="p-3 border border-gray-200 text-right">Rp {{ number_format($row->harga, 0, ',', '.') }}</td>
                        <td class="p-3 border border-gray-200 text-center font-bold">{{ $row->total_terjual }}</td>
                        <td class="p-3 border border-gray-200 text-right font-medium text-slate-900">Rp {{ number_format($row->total_pendapatan, 0, ',', '.') }}</td>
                        <td class="p-3 border border-gray-200 text-right">Rp {{ number_format($row->total_hpp, 0, ',', '.') }}</td>
                        <td class="p-3 border border-gray-200 text-right text-emerald-600 font-medium">Rp {{ number_format($row->laba_kotor, 0, ',', '.') }}</td>
                        <td class="p-3 border border-gray-200 text-center">{{ number_format($margin, 2, ',', '.') }}%</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="9" class="p-4 text-center text-gray-500">Tidak ada data penjualan pada rentang tanggal ini.</td>
                    </tr>
                @endforelse
            </tbody>
            
            <!-- Footer Total Keseluruhan (Menampilkan Akumulasi Semua Page) -->
            @if($reportData->count() > 0)
            @php 
                $grandMargin = ($totals->grand_revenue ?? 0) > 0 ? ($totals->grand_laba_kotor / $totals->grand_revenue) * 100 : 0;
            @endphp
            <tfoot class="bg-slate-50 font-bold text-sm text-gray-800">
                <tr>
                    <td colspan="4" class="p-3 border border-gray-200 text-right">TOTAL PERIODE INI:</td>
                    <td class="p-3 border border-gray-200 text-center text-blue-600">
                        {{ $totals->grand_qty ?? 0 }}
                    </td>
                    <td class="p-3 border border-gray-200 text-right text-slate-900">
                        Rp {{ number_format($totals->grand_revenue ?? 0, 0, ',', '.') }}
                    </td>
                    <td class="p-3 border border-gray-200 text-right text-slate-600">
                        Rp {{ number_format($totals->grand_hpp ?? 0, 0, ',', '.') }}
                    </td>
                    <td class="p-3 border border-gray-200 text-right text-emerald-600 text-base">
                        Rp {{ number_format($totals->grand_laba_kotor ?? 0, 0, ',', '.') }}
                    </td>
                    <td class="p-3 border border-gray-200 text-center text-blue-600">
                        {{ number_format($grandMargin, 2, ',', '.') }}%
                    </td>
                </tr>
            </tfoot>
            @endif
        </table>

        <!-- Link Navigasi Pagination Elemen Paging Laravel -->
        <div class="mt-4">
            {{ $reportData->links() }}
        </div>
    </div>
</div>
@endsection