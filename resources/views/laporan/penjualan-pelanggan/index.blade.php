@extends('layouts.app')

@section('title', 'Laporan Penjualan Per Pelanggan')

@section('content')
<div class="max-w-7xl mx-auto p-6">
    <div class="bg-white rounded-xl shadow p-6">
        <h1 class="text-2xl font-bold mb-6">Laporan Penjualan Per Pelanggan</h1>

        <!-- Form Filter Tanggal -->
        <form method="GET" action="/laporan/penjualan-pelanggan" class="flex flex-wrap gap-4 items-end mb-6">
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

            <!-- Tombol Aksi Sejajar & Menggunakan Component (Ukuran sm/md pas input) -->
            <div class="flex gap-2 items-center">
                <!-- Filter Data menggunakan Component Button -->
                <x-button type="submit" color="blue" size="sm" class="items-center">
                    Filter Data
                </x-button>
                
                <!-- Export XLS menggunakan Component (Dibalut <a> agar tetap link bertingkah button) -->
                <a href="{{ request()->fullUrlWithQuery(['export' => 'excel']) }}">
                    <x-button type="button" color="green" size="sm" class="items-center">
                        <i class="ri-file-excel-2-line"></i> Export XLS
                    </x-button>
                </a>
                
                <!-- Cetak PDF menggunakan Component -->
                <a href="{{ request()->fullUrlWithQuery(['export' => 'pdf']) }}" target="_blank">
                    <x-button type="button" color="red" size="sm" class="items-center">
                        <i class="ri-file-pdf-line"></i> Cetak PDF
                    </x-button>
                </a>
            </div>
        </form>

        

        @php
            $getSortLink = function($column) use ($sortBy, $sortDir, $startDate, $endDate) {
                $nextDir = ($sortBy === $column && $sortDir === 'asc') ? 'desc' : 'asc';
                return request()->fullUrlWithQuery([
                    'sort_by' => $column,
                    'sort_dir' => $nextDir,
                    'start_date' => $startDate,
                    'end_date' => $endDate,
                    'page' => 1
                ]);
            };
            
            $renderArrow = function($column) use ($sortBy, $sortDir) {
                if ($sortBy !== $column) return '';
                return $sortDir === 'asc' ? ' ▲' : ' ▼';
            };
        @endphp

        <!-- Tabel Laporan -->
        <table class="w-full border-collapse border border-gray-200">
            <thead>
                <tr class="bg-slate-100 border-b border-gray-200 text-gray-700 text-sm select-none">
                    <th class="p-3 text-left border border-gray-200 w-12">No</th>
                    <th class="p-0 border border-gray-200 hover:bg-slate-200 transition">
                        <a href="{{ $getSortLink('kode_pelanggan') }}" class="block p-3 text-left w-full h-full font-bold">
                            Kode Pelanggan<span class="text-blue-600 text-xs">{{ $renderArrow('kode_pelanggan') }}</span>
                        </a>
                    </th>
                    <th class="p-0 border border-gray-200 hover:bg-slate-200 transition">
                        <a href="{{ $getSortLink('nama_pelanggan') }}" class="block p-3 text-left w-full h-full font-bold">
                            Nama Pelanggan<span class="text-blue-600 text-xs">{{ $renderArrow('nama_pelanggan') }}</span>
                        </a>
                    </th>
                    <th class="p-0 border border-gray-200 hover:bg-slate-200 transition">
                        <a href="{{ $getSortLink('total_transaksi') }}" class="block p-3 text-center w-full h-full font-bold">
                            Jumlah Transaksi<span class="text-blue-600 text-xs">{{ $renderArrow('total_transaksi') }}</span>
                        </a>
                    </th>
                    <th class="p-0 border border-gray-200 hover:bg-slate-200 transition">
                        <a href="{{ $getSortLink('total_belanja') }}" class="block p-3 text-right w-full h-full font-bold">
                            Total Kontribusi Omset<span class="text-blue-600 text-xs">{{ $renderArrow('total_belanja') }}</span>
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
                        <td class="p-3 border border-gray-200 font-mono text-gray-500">{{ $row->kode_pelanggan ?? '-' }}</td>
                        <td class="p-3 border border-gray-200 font-medium text-gray-900">{{ $row->nama_pelanggan }}</td>
                        <td class="p-3 border border-gray-200 text-center font-semibold">{{ $row->total_transaksi }}x</td>
                        <td class="p-3 border border-gray-200 text-right font-bold text-emerald-600">Rp {{ number_format($row->total_belanja, 0, ',', '.') }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="p-4 text-center text-gray-500">Tidak ada data transaksi pelanggan pada rentang tanggal ini.</td>
                    </tr>
                @endforelse
            </tbody>
            
            @if($reportData->count() > 0)
            <tfoot class="bg-slate-50 font-bold text-sm text-gray-800">
                <tr>
                    <td colspan="3" class="p-3 border border-gray-200 text-right">TOTAL KESELURUHAN (SEMUA HALAMAN):</td>
                    <td class="p-3 border border-gray-200 text-center text-blue-600 text-base">
                        {{ $totals->grand_qty_transaksi ?? 0 }}x
                    </td>
                    <td class="p-3 border border-gray-200 text-right text-emerald-600 text-base">
                        Rp {{ number_format($totals->grand_omset ?? 0, 0, ',', '.') }}
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