@extends('layouts.app')

@section('title', 'Laporan Shift (Z-Report)')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
    <x-card class="bg-white shadow rounded-lg p-6">
        
        <!-- Header & Judul -->
        <div class="flex items-center mb-6">
            <h2 class="text-xl font-bold text-slate-800 flex items-center gap-2">
                <i class="ri-history-line text-indigo-600"></i> Riwayat Sesi & Laporan Shift (Z-Report)
            </h2>
        </div>

        <!-- Tabel Laporan Shift -->
        <x-table class="border-collapse">
            <x-table-header>
                <tr class="border-b border-slate-200">
                    <x-table-head class="text-center w-12 font-bold bg-slate-100 text-slate-700 border-r border-slate-200">No</x-table-head>
                    <x-table-head class="text-center font-bold bg-slate-100 text-slate-700 border-r border-slate-200 w-20">ID Shift</x-table-head>
                    <x-table-head class="text-left font-bold bg-slate-100 text-slate-700 border-r border-slate-200">Nama Kasir</x-table-head>
                    <x-table-head class="text-center font-bold bg-slate-100 text-slate-700 border-r border-slate-200">Waktu Buka</x-table-head>
                    <x-table-head class="text-center font-bold bg-slate-100 text-slate-700 border-r border-slate-200">Waktu Tutup</x-table-head>
                    <x-table-head class="text-right font-bold bg-slate-100 text-slate-700 border-r border-slate-200 w-36">Modal Awal</x-table-head>
                    <x-table-head class="text-right font-bold bg-slate-100 text-slate-700 border-r border-slate-200 w-36">Uang Fisik</x-table-head>
                    <x-table-head class="text-right font-bold bg-slate-100 text-slate-700 border-r border-slate-200 w-36">Selisih</x-table-head>
                    <x-table-head class="text-center font-bold bg-slate-100 text-slate-700 border-r border-slate-200 w-28">Status</x-table-head>
                    <x-table-head class="text-center font-bold bg-slate-100 text-slate-700 w-36">Aksi</x-table-head>
                </tr>
            </x-table-header>
            
            <x-table-body>
                @forelse($shifts as $item)
                    <x-table-row>
                        <!-- Nomor Urut -->
                        <x-table-cell class="text-center border-r border-slate-200">
                            {{ ($shifts->currentPage() - 1) * $shifts->perPage() + $loop->iteration }}
                        </x-table-cell>
                        
                        <!-- ID Shift -->
                        <x-table-cell class="text-center border-r border-slate-200 font-semibold text-slate-600">
                            #{{ $item->id }}
                        </x-table-cell>
                        
                        <!-- Nama Kasir -->
                        <x-table-cell class="border-r border-slate-200 font-medium text-slate-800">
                            {{ $item->user->name ?? 'N/A' }}
                        </x-table-cell>
                        
                        <!-- Waktu Buka -->
                        <x-table-cell class="text-center border-r border-slate-200 text-sm text-slate-600">
                            {{ $item->opened_at }}
                        </x-table-cell>
                        
                        <!-- Waktu Tutup -->
                        <x-table-cell class="text-center border-r border-slate-200 text-sm text-slate-600">
                            {{ $item->closed_at ?? '-' }}
                        </x-table-cell>
                        
                        <!-- Modal Awal -->
                        <x-table-cell class="text-right border-r border-slate-200">
                            Rp {{ number_format($item->starting_cash, 0, ',', '.') }}
                        </x-table-cell>
                        
                        <!-- Uang Fisik -->
                        <x-table-cell class="text-right border-r border-slate-200">
                            Rp {{ number_format($item->ending_cash_actual ?? 0, 0, ',', '.') }}
                        </x-table-cell>
                        
                        <!-- Selisih (Variance) -->
                        <x-table-cell class="text-right border-r border-slate-200 font-bold {{ $item->variance < 0 ? 'text-rose-600' : ($item->variance > 0 ? 'text-emerald-600' : 'text-slate-500') }}">
                            Rp {{ number_format($item->variance, 0, ',', '.') }}
                        </x-table-cell>
                        
                        <!-- Status Badge -->
                        <x-table-cell class="text-center border-r border-slate-200">
                            @if($item->status == 'open')
                                <span class="px-3 py-1 text-xs font-bold rounded-full bg-emerald-100 text-emerald-800 uppercase tracking-wider">
                                    OPEN
                                </span>
                            @else
                                <span class="px-3 py-1 text-xs font-bold rounded-full bg-rose-100 text-rose-800 uppercase tracking-wider">
                                    CLOSED
                                </span>
                            @endif
                        </x-table-cell>
                        
                        <!-- Aksi Button -->
                        <x-table-cell class="text-center">
                            <a href="{{ route('laporan.shift.show', $item->id) }}" 
                               class="px-4 py-1.5 bg-indigo-600 text-white text-xs font-semibold rounded-xl hover:bg-indigo-700 transition duration-150 flex items-center justify-center gap-1 mx-auto w-28">
                                <i class="ri-eye-line text-sm"></i> Detail Z
                            </a>
                        </x-table-cell>
                    </x-table-row>
                @empty
                    <x-table-row>
                        <x-table-cell colspan="10" class="text-center py-8 text-slate-400 italic">
                            Belum ada data shift tercatat.
                        </x-table-cell>
                    </x-table-row>
                @endforelse
            </x-table-body>
        </x-table>

        <!-- Pagination Links -->
        <div class="mt-5">
            {{ $shifts->links() }}
        </div>

    </x-card>
</div>
@endsection