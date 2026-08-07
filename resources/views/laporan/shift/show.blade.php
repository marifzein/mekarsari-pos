@extends('layouts.app')

@section('title', 'Detail Z-Report Shift')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
    
    <!-- Header Utama & Tombol Aksi -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
        <h2 class="text-xl font-bold text-slate-800 flex items-center gap-2">
            <i class="ri-file-list-3-line text-indigo-600"></i> Laporan Shift Kasir (Z-Report)
        </h2>
        <div class="flex items-center gap-3">
            <a href="{{ route('laporan.shift.index') }}" 
               class="px-4 py-2 bg-slate-100 text-slate-700 font-semibold rounded-xl hover:bg-slate-200 transition duration-150 flex items-center gap-1 text-sm border border-slate-200">
                <i class="ri-arrow-left-line"></i> Kembali
            </a>
            <button onclick="window.print()" 
                    class="px-4 py-2 bg-indigo-600 text-white font-semibold rounded-xl hover:bg-indigo-700 transition duration-150 flex items-center gap-1 text-sm shadow-sm">
                <i class="ri-printer-line"></i> Cetak Z-Report
            </button>
        </div>
    </div>

    <!-- Grid Layout Utama -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">
        
        <!-- Kiri: Info Sesi Kasir -->
        <div class="lg:col-span-1">
            <x-card class="bg-white shadow rounded-lg p-5 h-full">
                <h3 class="text-base font-bold text-slate-800 border-b border-slate-100 pb-3 mb-4 flex items-center gap-2">
                    <i class="ri-information-line text-slate-500"></i> Info Sesi #{{ $shift->id }}
                </h3>
                <div class="space-y-4">
                    <div>
                        <label class="block text-xs font-semibold text-slate-400 uppercase">Nama Kasir</label>
                        <span class="text-sm font-bold text-slate-800">{{ $shift->user->name ?? 'N/A' }}</span>
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-slate-400 uppercase mb-1">Status Sesi</label>
                        @if($shift->status == 'open')
                            <span class="px-2.5 py-0.5 text-xs font-bold rounded-full bg-emerald-100 text-emerald-800 uppercase tracking-wider">OPEN</span>
                        @else
                            <span class="px-2.5 py-0.5 text-xs font-bold rounded-full bg-rose-100 text-rose-800 uppercase tracking-wider">CLOSED</span>
                        @endif
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-slate-400 uppercase">Waktu Buka</label>
                        <span class="text-sm text-slate-700 font-medium">{{ $shift->opened_at }}</span>
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-slate-400 uppercase">Waktu Tutup</label>
                        <span class="text-sm text-slate-700 font-medium">{{ $shift->closed_at ?? '-' }}</span>
                    </div>
                </div>
            </x-card>
        </div>

        <!-- Kanan: Rekonsiliasi Cash Laci -->
        <div class="lg:col-span-2">
            <x-card class="bg-white shadow rounded-lg p-5 h-full">
                <h3 class="text-base font-bold text-slate-800 border-b border-slate-100 pb-3 mb-4 flex items-center gap-2">
                    <i class="ri-bank-card-line text-indigo-600"></i> Rekonsiliasi Cash Laci
                </h3>
                
                <!-- Info Grid Alur Uang -->
                <div class="grid grid-cols-3 gap-4 bg-slate-50 border border-slate-100 rounded-xl p-4 mb-5 text-center">
                    <div>
                        <span class="block text-xs font-semibold text-slate-500 mb-1">Modal Awal</span>
                        <span class="text-sm font-bold text-slate-800">Rp {{ number_format($shift->starting_cash, 0, ',', '.') }}</span>
                    </div>
                    <div class="border-x border-slate-200">
                        <span class="block text-xs font-semibold text-slate-500 mb-1">Omzet Tunai</span>
                        <span class="text-sm font-bold text-slate-800">Rp {{ number_format($shift->total_cash_sales, 0, ',', '.') }}</span>
                    </div>
                    <div>
                        <span class="block text-xs font-semibold text-slate-500 mb-1">Biaya Ops</span>
                        <span class="text-sm font-bold text-rose-600">Rp {{ number_format($shift->operational_expense, 0, ',', '.') }}</span>
                    </div>
                </div>

                <!-- Detil Uang Fisik & Selisih -->
                <div class="border border-slate-200 rounded-xl overflow-hidden shadow-sm">
                    <div class="flex justify-between items-center px-4 py-3 bg-slate-50 border-b border-slate-200 text-sm">
                        <span class="font-medium text-slate-600">Uang Seharusnya (Sistem)</span>
                        <span class="font-bold text-slate-800">Rp {{ number_format($shift->expected_cash, 0, ',', '.') }}</span>
                    </div>
                    <div class="flex justify-between items-center px-4 py-3 border-b border-slate-200 text-sm">
                        <span class="font-medium text-slate-600">Uang Fisik di Laci</span>
                        <span class="font-bold text-indigo-600 text-base">Rp {{ number_format($shift->ending_cash_actual ?? 0, 0, ',', '.') }}</span>
                    </div>
                    <div class="flex justify-between items-center px-4 py-3.5 bg-slate-900 text-white text-sm">
                        <span class="font-semibold tracking-wide">Selisih (Variance)</span>
                        <span class="font-bold text-base {{ $shift->variance < 0 ? 'text-rose-400' : ($shift->variance > 0 ? 'text-emerald-400' : 'text-slate-300') }}">
                            Rp {{ number_format($shift->variance, 0, ',', '.') }}
                        </span>
                    </div>
                </div>

                <!-- Alasan Jika Ada Selisih -->
                @if($shift->variance_reason)
                    <div class="mt-4 p-3 bg-amber-50 border border-amber-200 rounded-xl flex items-start gap-2 text-xs text-amber-800">
                        <i class="ri-alert-line text-sm text-amber-600 mt-0.5"></i>
                        <div>
                            <strong class="font-bold">Alasan Selisih:</strong> 
                            <span class="italic">"{{ $shift->variance_reason }}"</span>
                        </div>
                    </div>
                @endif
            </x-card>
        </div>
    </div>

    <!-- Bawah: Ringkasan Metode Pembayaran & Performa Penjualan -->
    <x-card class="bg-white shadow rounded-lg p-6">
        <h3 class="text-base font-bold text-slate-800 border-b border-slate-100 pb-3 mb-5 flex items-center gap-2">
            <i class="ri-pie-chart-line text-emerald-600"></i> Ringkasan Non-Cash & Volume Penjualan
        </h3>
        
        <!-- Grid Ringkasan Performa -->
        <div class="grid grid-cols-2 md:grid-cols-4 gap-6 text-center mb-6">
            <div class="bg-slate-50 rounded-xl p-4 border border-slate-100">
                <span class="block text-xs font-semibold text-slate-500 mb-1">Total EDC / Card</span>
                <span class="text-base font-bold text-slate-800">Rp {{ number_format($summary->total_card ?? 0, 0, ',', '.') }}</span>
            </div>
            <div class="bg-slate-50 rounded-xl p-4 border border-slate-100">
                <span class="block text-xs font-semibold text-slate-500 mb-1">Total Voucher</span>
                <span class="text-base font-bold text-slate-800">Rp {{ number_format($summary->total_voucher ?? 0, 0, ',', '.') }}</span>
            </div>
            <div class="bg-slate-50 rounded-xl p-4 border border-slate-100">
                <span class="block text-xs font-semibold text-slate-500 mb-1">Jumlah Transaksi</span>
                <span class="text-base font-bold text-slate-800">{{ $summary->count_trx ?? 0 }} Nota</span>
            </div>
            <div class="bg-slate-50 rounded-xl p-4 border border-slate-100">
                <span class="block text-xs font-semibold text-slate-500 mb-1">Total Item Terjual</span>
                <span class="text-base font-bold text-slate-800">{{ $totalQtySold ?? 0 }} Pcs</span>
            </div>
        </div>

        <!-- Garis Batas & Total Acuan -->
        <div class="border-t border-slate-100 pt-5 flex justify-center">
            <div class="px-6 py-3 bg-emerald-50 border border-emerald-100 rounded-2xl text-center shadow-inner">
                <span class="block text-xs font-bold text-emerald-700 tracking-wider uppercase mb-0.5">Total Acuan Omzet Shift</span>
                <h4 class="text-lg font-black text-emerald-600">Rp {{ number_format($summary->total_grand ?? 0, 0, ',', '.') }}</h4>
            </div>
        </div>
    </x-card>
</div>
@endsection