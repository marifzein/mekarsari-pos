@extends('layouts.app')

@section('title', 'Laporan Laba Rugi Kotor')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
    <x-card class="bg-white shadow rounded-lg p-6">
        
        <!-- Header & Judul -->
        <div class="flex items-center mb-6">
            <h2 class="text-xl font-bold text-slate-800 flex items-center gap-2">
                <i class="ri-money-dollar-box-line text-emerald-600"></i> Laporan Laba Rugi Kotor Penjualan
            </h2>
        </div>

        <!-- Filter Tanggal -->
        <form method="GET" action="{{ route('laporan.laba-rugi') }}" class="bg-slate-50 border border-slate-100 rounded-xl p-5 mb-6">
            <div class="flex flex-wrap items-end gap-5">
                <div class="w-full sm:w-auto">
                    <label class="block text-sm font-semibold text-slate-700 mb-2">Dari Tanggal</label>
                    <input type="date" name="dari_tanggal" value="{{ $dari_tanggal }}" 
                        class="rounded-xl border border-slate-300 px-4 py-2.5 focus:border-indigo-500 focus:ring-4 focus:ring-indigo-100 w-full sm:w-56">
                </div>
                <div class="w-full sm:w-auto">
                    <label class="block text-sm font-semibold text-slate-700 mb-2">Sampai Tanggal</label>
                    <input type="date" name="sampai_tanggal" value="{{ $sampai_tanggal }}" 
                        class="rounded-xl border border-slate-300 px-4 py-2.5 focus:border-indigo-500 focus:ring-4 focus:ring-indigo-100 w-full sm:w-56">
                </div>
                <div class="flex items-center gap-3">
                    <button type="submit" class="px-6 py-2.5 bg-indigo-600 text-white font-semibold rounded-xl hover:bg-indigo-700 transition duration-150">
                        Tampilkan
                    </button>
                    
                    <!-- Tombol Export Excel -->
                    <a href="{{ route('laporan.laba-rugi.excel', ['dari_tanggal' => $dari_tanggal, 'sampai_tanggal' => $sampai_tanggal]) }}" 
                    class="px-5 py-2.5 bg-emerald-600 text-white font-semibold rounded-xl hover:bg-emerald-700 transition duration-150 flex items-center gap-1">
                    <i class="ri-file-excel-2-line"></i> Excel
                    </a>

                    <!-- Tombol Cetak PDF -->
                    <a href="{{ route('laporan.laba-rugi.pdf', ['dari_tanggal' => $dari_tanggal, 'sampai_tanggal' => $sampai_tanggal]) }}" target="_blank" 
                    class="px-5 py-2.5 bg-rose-600 text-white font-semibold rounded-xl hover:bg-rose-700 transition duration-150 flex items-center gap-1">
                    <i class="ri-file-pdf-line"></i> PDF / Cetak
                    </a>
                </div>
            </div>
        </form>

        <!-- Tabel Laporan -->
        <x-table class="border-collapse">
            <x-table-header>
                <tr class="border-b border-slate-200">
                    <x-table-head class="text-center w-12 font-bold bg-slate-100 text-slate-700 border-r border-slate-200">No</x-table-head>
                    <x-table-head class="text-left font-bold bg-slate-100 text-slate-700 border-r border-slate-200">Tanggal</x-table-head>
                    <x-table-head class="text-right font-bold bg-slate-100 text-slate-700 border-r border-slate-200 w-48">Pendapatan Penjualan</x-table-head>
                    <x-table-head class="text-right font-bold bg-slate-100 text-slate-700 border-r border-slate-200 w-48">HPP (Harga Pokok)</x-table-head>
                    <x-table-head class="text-right font-bold bg-slate-100 text-slate-700 border-r border-slate-200 w-48">Laba Kotor</x-table-head>
                    <x-table-head class="text-center font-bold bg-slate-100 text-slate-700 w-32">Margin</x-table-head>
                </tr>
            </x-table-header>
            
            <x-table-body>
                @forelse ($reports as $report)
                    @php
                        // Hitung Margin Persentase Per Hari: (Laba Kotor / Pendapatan) * 100
                        $margin = $report->total_pendapatan > 0 ? ($report->laba_kotor / $report->total_pendapatan) * 100 : 0;
                    @endphp
                    <x-table-row>
                        <x-table-cell class="text-center border-r border-slate-200">{{ ($reports->currentPage() - 1) * $reports->perPage() + $loop->iteration }}</x-table-cell>
                        <x-table-cell class="border-r border-slate-200">{{ \Carbon\Carbon::parse($report->tanggal)->translatedFormat('d F Y') }}</x-table-cell>
                        <x-table-cell class="text-right border-r border-slate-200">Rp {{ number_format($report->total_pendapatan, 0, ',', '.') }}</x-table-cell>
                        <x-table-cell class="text-right border-r border-slate-200">Rp {{ number_format($report->total_hpp, 0, ',', '.') }}</x-table-cell>
                        <x-table-cell class="text-right border-r border-slate-200 font-semibold text-emerald-600">Rp {{ number_format($report->laba_kotor, 0, ',', '.') }}</x-table-cell>
                        <x-table-cell class="text-center font-medium text-slate-600">{{ number_format($margin, 2, ',', '.') }}%</x-table-cell>
                    </x-table-row>
                @empty
                    <x-table-row>
                        <x-table-cell colspan="6" class="text-center py-8 text-slate-400 italic">
                            Tidak ada data transaksi pada rentang tanggal ini.
                        </x-table-cell>
                    </x-table-row>
                @endforelse
            </x-table-body>

            <!-- Bagian Total Akumulasi Periode -->
            @if($reports->count() > 0)
                @php
                    $total_margin = $totals->total_pendapatan > 0 ? ($totals->laba_kotor / $totals->total_pendapatan) * 100 : 0;
                @endphp
                <tfoot class="bg-slate-100 border-t-2 border-slate-300 font-bold text-slate-800 text-sm">
                    <tr>
                        <td class="px-3 py-4 text-center border-r border-b border-slate-200" colspan="2">TOTAL PERIODE INI</td>
                        <td class="px-2 py-4 text-right text-indigo-700 border-r border-b border-slate-200">Rp {{ number_format($totals->total_pendapatan, 0, ',', '.') }}</td>
                        <td class="px-2 py-4 text-right text-amber-700 border-r border-b border-slate-200">Rp {{ number_format($totals->total_hpp, 0, ',', '.') }}</td>
                        <td class="px-2 py-4 text-right text-emerald-700 text-base border-r border-b border-slate-200">Rp {{ number_format($totals->laba_kotor, 0, ',', '.') }}</td>
                        <td class="px-2 py-4 text-center text-slate-900 border-b border-slate-200">{{ number_format($total_margin, 2, ',', '.') }}%</td>
                    </tr>
                </tfoot>
            @endif
        </x-table>

        <!-- Pagination Links -->
        <div class="mt-5">
            {{ $reports->links() }}
        </div>

    </x-card>
</div>
@endsection