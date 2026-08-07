{{-- @extends('layouts.app') --}}
@extends(
    preg_match('/(android|bb\d+|meego).+mobile|avantgo|bada\/|blackberry|blazer|compal|elaine|fennec|hiptop|iemobile|ip(hone|od)|iris|kindle|lge |maemo|midp|mmp|mobile.+firefox|netfront|opera m(ob|in)i|palm( os)?|phone|p(ixi|re)\/|plucker|pocket|psp|series(4|6)0|symbian|treo|up\.(browser|link)|vodafone|wap|windows ce|xda|xiino/i', request()->header('User-Agent')) 
    || preg_match('/(ipad|tablet|(android(?!.*mobile)))/i', request()->header('User-Agent')) 
    ? 'layouts.mobile-app' 
    : 'layouts.app'
)

@section('title','Detail Transaksi')

@section('content')


<div class="max-w-5xl mx-auto p-2 sm:p-6">

{{-- <div class="max-w-5xl mx-auto p-6"> --}}

    {{-- <div class="flex gap-2 mb-4 justify-end"> --}}
    <div class="flex gap-2 mb-4 justify-between sm:justify-end items-center">
        <a
            href="/transactions"
        >
            {{-- ← Kembali --}}
             <x-button color="secondary">
                <i class="ri-arrow-left-circle-line"></i>
                Kembali
            </x-button>
        </a>

        <a
            href="{{ route('transactions.print',$transaction->id) }}"
            target="_blank"
            {{-- class="bg-green-600 text-white px-4 py-2 rounded" --}}
        >
            <!-- 💡 Sembunyikan Tombol Cetak jika transaksi Batal -->
            @if(strtolower($transaction->status) !== 'batal')
                <a href="{{ route('transactions.print',$transaction->id) }}" target="_blank">
                    <x-button color="green">
                        <i class="ri-printer-line"></i>
                        Cetak
                    </x-button>
                </a>
            @endif
           
        </a>

    </div>

    {{-- <div class="bg-white rounded-xl shadow p-6"> --}}
    <div class="bg-white rounded-2xl shadow-sm p-4 sm:p-6 border border-slate-100">

        <h1 class="text-2xl font-bold mb-6">
            Detail Transaksi
        </h1>

        {{-- <div class="grid grid-cols-2 gap-4 mb-6"> --}}
        <!-- Info Transaksi & Pelanggan -->
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-2 sm:gap-4 mb-6 bg-slate-50 p-4 rounded-xl border border-slate-200/60 text-sm">

            {{-- <div> --}}
            <div class="space-y-1.5">

                {{-- <div> --}}
                <div class="flex justify-between sm:justify-start sm:gap-2">
                    <b>No Nota :</b>
                    {{ $transaction->no_nota }}
                </div>

                <div>
                    <b>Tanggal :</b>
                    {{ $transaction->created_at }}
                </div>

                <div>
                    <b>Kasir :</b>
                    <span class="font-medium text-slate-800">
                        {{ $transaction->user?->name ?? 'System' }}
                    </span>
                </div>

            </div>

            <div>

                {{-- <div>
                    <b>Pelanggan :</b>
                    {{ $transaction->pelanggan ?? 'Umum' }}
                </div> --}}
                <div>
                    <b>Pelanggan :</b>
                    @php
                        $customerData = null;
                        if ($transaction->pelanggan) {
                            // Cari data customer berdasarkan kode_pelanggan yang unique
                            $customerData = \App\Models\Customer::where('kode_pelanggan', $transaction->pelanggan)->first();
                        }
                    @endphp
                    {{ $customerData ? $customerData->nama : ($transaction->pelanggan ?? 'Umum') }}
                </div>

            </div>

        </div>


        <!-- 💡 Alert Detail Pembatalan jika Transaksi Batal -->
        @if(strtolower($transaction->status) === 'batal' && $transaction->pembatalan)
            <div class="mb-6 p-4 bg-red-50 border-l-4 border-red-500 rounded-r-lg space-y-1 text-sm">
                <div class="font-bold text-red-800 text-base mb-1">
                    <i class="ri-error-warning-line mr-1"></i> Informasi Pembatalan:
                </div>
                <div>
                    <b>Dibatalkan oleh :</b> 
                    <span class="text-red-700 font-semibold">
                        {{ $transaction->pembatalan->user?->name ?? 'System/Admin' }}
                    </span> 
                    pada 
                    <span class="font-medium text-slate-700">
                        {{ $transaction->pembatalan->created_at->format('Y-m-d H:i:s') }}
                    </span>
                </div>
                <div>
                    <b>Alasan :</b> 
                    <span class="italic text-slate-700">
                        "{{ $transaction->pembatalan->alasan }}"
                    </span>
                </div>
            </div>
        @endif
        

        <table class="w-full border">

            <thead>

            <tr class="bg-slate-100">

                <th class="p-3 text-left">
                    Barang
                </th>

                <th class="p-3 text-center">
                    Qty
                </th>

                <th class="p-3 text-right">
                    Harga
                </th>

                <th class="p-3 text-right">
                    Subtotal
                </th>

            </tr>

            </thead>

            <tbody>

            @foreach($transaction->details as $item)

                <tr class="border-t">

                    <td class="p-3">
                        {{ $item->nama_barang }}
                    </td>

                    <td class="p-3 text-center">
                        {{ $item->qty }}
                    </td>

                    <td class="p-3 text-right">
                        {{ number_format($item->harga,0,',','.') }}
                    </td>

                    <td class="p-3 text-right">
                        {{ number_format($item->subtotal,0,',','.') }}
                    </td>

                </tr>

            @endforeach

            </tbody>

        </table>

        <div class="mt-6 flex justify-end">

            <div class="w-80 space-y-2">

                <div class="flex justify-between">
                    <span>Subtotal</span>
                    <span>
                        Rp {{ number_format($transaction->subtotal,0,',','.') }}
                    </span>
                </div>

                <div class="flex justify-between">
                    <span>Voucher</span>
                    <span>
                        Rp {{ number_format($transaction->voucher,0,',','.') }}
                    </span>
                </div>

                <div class="flex justify-between">
                    <span>Card</span>
                    <span>
                        Rp {{ number_format($transaction->card,0,',','.') }}
                    </span>
                </div>

                <hr>

                <div class="flex justify-between font-bold text-xl">
                    <span>Total</span>
                    <span>
                        Rp {{ number_format($transaction->grand_total,0,',','.') }}
                    </span>
                </div>

                <div class="flex justify-between">
                    <span>Bayar</span>
                    <span>
                        Rp {{ number_format($transaction->cash,0,',','.') }}
                    </span>
                </div>

                <div class="flex justify-between text-green-600 font-bold">
                    <span>Kembalian</span>
                    <span>
                        Rp {{ number_format($transaction->kembalian,0,',','.') }}
                    </span>
                </div>

            </div>

        </div>

    </div>

</div>

@endsection