{{-- @extends('layouts.app') --}}
@extends(
    preg_match('/(android|bb\d+|meego).+mobile|avantgo|bada\/|blackberry|blazer|compal|elaine|fennec|hiptop|iemobile|ip(hone|od)|iris|kindle|lge |maemo|midp|mmp|mobile.+firefox|netfront|opera m(ob|in)i|palm( os)?|phone|p(ixi|re)\/|plucker|pocket|psp|series(4|6)0|symbian|treo|up\.(browser|link)|vodafone|wap|windows ce|xda|xiino/i', request()->header('User-Agent')) 
    || preg_match('/(ipad|tablet|(android(?!.*mobile)))/i', request()->header('User-Agent')) 
    ? 'layouts.mobile-app' 
    : 'layouts.app'
)

@section('title','Riwayat Transaksi')
@section('page_subtitle', 'Data penjualan kasir')

@section('content')

<div class="max-w-7xl mx-auto p-2 sm:p-6">
    <div class="flex justify-between items-center mb-4">
        {{-- <h1 class="text-2xl font-bold mb-4"> --}}
        <h1 class="text-2xl font-bold mb-4 hidden md:block">
            Riwayat Transaksi
        </h1>
    </div>

    {{-- <div class="max-w-7xl mx-auto p-0 md:p-6"> --}}

        

        {{-- <div class="bg-white rounded-xl shadow overflow-x-auto">     --}}
    {{-- TAMPILAN DESKTOP (TABEL) --}}
    <div class="hidden md:block bg-white rounded-xl shadow overflow-hidden">
            <table class="w-full">

                <thead>

                <tr class="bg-slate-100">

                    <th class="p-3 text-left">
                        No Nota
                    </th>

                    <th class="p-3 text-left">
                        Tanggal
                    </th>

                    <th class="p-3 text-left">
                        Kasir
                    </th>

                    <th class="p-3 text-left">
                        Pelanggan
                    </th>
                    <th class="p-3 text-center">
                        Status
                    </th>
                    <th class="p-3 text-right">
                        Total
                    </th>
                    <th class="p-3 text-center">
                        Aksi
                    </th>

                </tr>

                </thead>

                <tbody>

                @forelse($transactions as $trx)

                    <tr class="border-b">

                        <td class="p-3">
                            {{ $trx->no_nota }}
                        </td>

                        <td class="p-3">
                            {{ $trx->created_at }}
                        </td>

                        <td class="p-3 text-slate-600">
                            {{ $trx->user?->name ?? 'System' }}
                        </td>

                        <!-- 💡 Menampilkan Nama Pelanggan / Kode Pelanggan -->
                        <td class="p-3">
                            @if($trx->pelanggan)
                                <span class="bg-blue-50 text-blue-700 px-2.5 py-1 rounded-md text-xs font-medium">
                                    {{ $trx->customerRelation->nama }}
                                </span>
                            @else
                                <span class="text-gray-400 text-xs italic">Umum (Non-Member)</span>
                            @endif
                        </td>

                        <!-- 💡 Kolom Status Transaksi -->
                        <td class="p-3 text-center">
                            @if(strtolower($trx->status) === 'batal')
                                <span class="bg-red-100 text-red-700 px-2.5 py-1 rounded-md text-xs font-semibold">
                                    Batal
                                </span>
                            @else
                                <span class="bg-green-100 text-green-700 px-2.5 py-1 rounded-md text-xs font-semibold">
                                    SOLD
                                </span>
                            @endif
                        </td>
                        
                        <td class="p-3 text-right">
                            Rp {{ number_format($trx->grand_total,0,',','.') }}
                        </td>

                        <td class="p-3 text-center">

                            <a
                                href="{{ route('transactions.show',$trx->id) }}"
                                {{-- class="bg-blue-500 text-white px-3 py-1 rounded" --}}
                            >
                                <x-button color="green">
                                    <i class="ri-printer-line"></i>
                                    Detail
                                </x-button>
                                
                            </a>

                        </td>

                    </tr>

                @empty

                    <tr>

                        <td colspan="6"
                            class="p-10 text-center text-gray-400">

                            Belum ada transaksi

                        </td>

                    </tr>

                @endforelse

                </tbody>

            </table>

    </div>

    {{-- TAMPILAN MOBILE & TABLET (CARD LIST FLEKSIBEL & DIBESARKAN) --}}
    <div class="block md:hidden space-y-4 px-1">
        @forelse($transactions as $trx)
            <div class="bg-white border border-slate-200/80 rounded-2xl p-5 shadow-sm space-y-4 w-full">
                <!-- Header Card: No Nota & Status -->
                <div class="flex justify-between items-start border-b border-slate-100 pb-3">
                    <div class="space-y-0.5">
                        <span class="text-base font-bold text-slate-900 block tracking-tight">{{ $trx->no_nota }}</span>
                        <span class="text-xs text-slate-500 font-medium flex items-center gap-1">
                            <i class="ri-time-line"></i> {{ $trx->created_at }}
                        </span>
                    </div>
                    <div>
                        @if(strtolower($trx->status) === 'batal')
                            <span class="bg-red-100 text-red-700 px-3 py-1 rounded-lg text-xs font-bold uppercase tracking-wider">
                                Batal
                            </span>
                        @else
                            <span class="bg-emerald-100 text-emerald-800 px-3 py-1 rounded-lg text-xs font-bold uppercase tracking-wider">
                                SOLD
                            </span>
                        @endif
                    </div>
                </div>

                <!-- Info Kasir & Pelanggan -->
                <div class="grid grid-cols-2 gap-3 bg-slate-50 p-3.5 rounded-xl border border-slate-100 text-sm">
                    <div>
                        <span class="text-xs text-slate-400 font-medium block mb-0.5">Kasir</span>
                        <span class="font-semibold text-slate-800 block truncate">{{ $trx->user?->name ?? 'System' }}</span>
                    </div>
                    <div>
                        <span class="text-xs text-slate-400 font-medium block mb-0.5">Pelanggan</span>
                        @if($trx->pelanggan)
                            <span class="font-semibold text-blue-600 block truncate">{{ $trx->customerRelation->nama }}</span>
                        @else
                            <span class="text-slate-500 italic block">Umum</span>
                        @endif
                    </div>
                </div>

                <!-- Footer Card: Total & Tombol Detail (Beri Jarak Cukup) -->
                <div class="flex items-center justify-between gap-4 pt-1">
                    <div class="shrink-0">
                        <span class="text-xs text-slate-400 font-medium block mb-0.5">Total Transaksi</span>
                        <span class="font-black text-lg text-slate-900 leading-tight">
                            Rp {{ number_format($trx->grand_total,0,',','.') }}
                        </span>
                    </div>
                    <div class="shrink-0">
                        <a href="{{ route('transactions.show',$trx->id) }}">
                            <x-button color="primary" size="md" class="px-5 py-2.5 font-bold shadow-sm">
                                <i class="ri-printer-line text-lg mr-1"></i> Detail
                            </x-button>
                        </a>
                    </div>
                </div>
            </div>
        @empty
            <div class="bg-white rounded-2xl p-8 text-center text-slate-400 italic border border-slate-200">
                Belum ada transaksi
            </div>
        @endforelse
    </div>



    {{-- Pagination jika ada --}}
    @if(method_exists($transactions, 'links'))
        <div class="mt-4">
            {{ $transactions->links() }}
        </div>
    @endif

@endsection