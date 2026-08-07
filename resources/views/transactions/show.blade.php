@extends(
    preg_match('/(android|bb\d+|meego).+mobile|avantgo|bada\/|blackberry|blazer|compal|elaine|fennec|hiptop|iemobile|ip(hone|od)|iris|kindle|lge |maemo|midp|mmp|mobile.+firefox|netfront|opera m(ob|in)i|palm( os)?|phone|p(ixi|re)\/|plucker|pocket|psp|series(4|6)0|symbian|treo|up\.(browser|link)|vodafone|wap|windows ce|xda|xiino/i', request()->header('User-Agent')) 
    || preg_match('/(ipad|tablet|(android(?!.*mobile)))/i', request()->header('User-Agent')) 
    ? 'layouts.mobile-app' 
    : 'layouts.app'
)

@section('title','Detail Transaksi')

@section('content')

<div class="max-w-5xl mx-auto p-2 sm:p-6">

    <!-- Tombol Navigasi Header -->
    <div class="flex gap-2 mb-4 justify-between sm:justify-end items-center">
        <a href="/transactions" class="w-full sm:w-auto">
             <x-button color="secondary" class="w-full sm:w-auto justify-center">
                <i class="ri-arrow-left-circle-line"></i> Kembali
            </x-button>
        </a>

        @if(strtolower($transaction->status) !== 'batal')
            <a href="{{ route('transactions.print',$transaction->id) }}" target="_blank" class="w-full sm:w-auto">
                <x-button color="green" class="w-full sm:w-auto justify-center">
                    <i class="ri-printer-line"></i> Cetak
                </x-button>
            </a>
        @endif
    </div>

    <div class="bg-white rounded-2xl shadow-sm p-4 sm:p-6 border border-slate-100">

        <h1 class="text-xl sm:text-2xl font-bold text-slate-800 mb-4 sm:mb-6">
            Detail Transaksi
        </h1>

        <!-- Info Transaksi & Pelanggan -->
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-2 sm:gap-4 mb-6 bg-slate-50 p-4 rounded-xl border border-slate-200/60 text-sm">
            <div class="space-y-1.5">
                <div class="flex justify-between sm:justify-start sm:gap-2">
                    <span class="text-slate-500 font-medium">No Nota:</span>
                    <span class="font-bold text-slate-800">{{ $transaction->no_nota }}</span>
                </div>
                <div class="flex justify-between sm:justify-start sm:gap-2">
                    <span class="text-slate-500 font-medium">Tanggal:</span>
                    <span class="text-slate-800">{{ $transaction->created_at }}</span>
                </div>
                <div class="flex justify-between sm:justify-start sm:gap-2">
                    <span class="text-slate-500 font-medium">Kasir:</span>
                    <span class="font-semibold text-slate-800">{{ $transaction->user?->name ?? 'System' }}</span>
                </div>
            </div>

            <div class="space-y-1.5 pt-2 sm:pt-0 border-t sm:border-t-0 border-slate-200">
                <div class="flex justify-between sm:justify-start sm:gap-2">
                    <span class="text-slate-500 font-medium">Pelanggan:</span>
                    <span class="font-semibold text-slate-800">
                        @php
                            $customerData = null;
                            if ($transaction->pelanggan) {
                                $customerData = \App\Models\Customer::where('kode_pelanggan', $transaction->pelanggan)->first();
                            }
                        @endphp
                        {{ $customerData ? $customerData->nama : ($transaction->pelanggan ?? 'Umum (Non-Member)') }}
                    </span>
                </div>
                <div class="flex justify-between sm:justify-start sm:gap-2">
                    <span class="text-slate-500 font-medium">Status:</span>
                    @if(strtolower($transaction->status) === 'batal')
                        <span class="bg-red-100 text-red-700 px-2 py-0.5 rounded text-xs font-semibold">Batal</span>
                    @else
                        <span class="bg-green-100 text-green-700 px-2 py-0.5 rounded text-xs font-semibold">SOLD</span>
                    @endif
                </div>
            </div>
        </div>

        <!-- Alert Informasi Pembatalan -->
        @if(strtolower($transaction->status) === 'batal' && $transaction->pembatalan)
            <div class="mb-6 p-4 bg-red-50 border-l-4 border-red-500 rounded-r-xl space-y-1 text-sm">
                <div class="font-bold text-red-800 text-base mb-1 flex items-center gap-1">
                    <i class="ri-error-warning-line"></i> Informasi Pembatalan
                </div>
                <div>
                    <span class="text-slate-600">Dibatalkan oleh:</span> 
                    <span class="text-red-700 font-semibold">{{ $transaction->pembatalan->user?->name ?? 'System/Admin' }}</span> 
                    pada 
                    <span class="font-medium text-slate-700">{{ $transaction->pembatalan->created_at->format('Y-m-d H:i:s') }}</span>
                </div>
                <div>
                    <span class="text-slate-600">Alasan:</span> 
                    <span class="italic text-slate-800">"{{ $transaction->pembatalan->alasan }}"</span>
                </div>
            </div>
        @endif

        <!-- ITEM BARANG (DESKTOP TABEL) -->
        <div class="hidden md:block overflow-hidden rounded-xl border border-slate-200 mb-6">
            <table class="w-full text-sm text-left">
                <thead class="bg-slate-100 text-slate-700 font-bold border-b border-slate-200">
                    <tr>
                        <th class="p-3.5">Barang</th>
                        <th class="p-3.5 text-center w-20">Qty</th>
                        <th class="p-3.5 text-right w-36">Harga</th>
                        <th class="p-3.5 text-right w-40">Subtotal</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                @foreach($transaction->details as $item)
                    <tr>
                        <td class="p-3.5 font-medium text-slate-800">{{ $item->nama_barang }}</td>
                        <td class="p-3.5 text-center">{{ $item->qty }}</td>
                        <td class="p-3.5 text-right">Rp {{ number_format($item->harga,0,',','.') }}</td>
                        <td class="p-3.5 text-right font-semibold text-slate-800">Rp {{ number_format($item->subtotal,0,',','.') }}</td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>

        <!-- ITEM BARANG (MOBILE CARD LIST) -->
        <div class="block md:hidden space-y-2 mb-6">
            <span class="text-xs font-bold text-slate-500 uppercase tracking-wider block mb-2">Daftar Item</span>
            @foreach($transaction->details as $item)
                <div class="bg-slate-50 border border-slate-200/80 p-3 rounded-xl flex justify-between items-center text-xs">
                    <div>
                        <h5 class="font-bold text-slate-800 text-sm mb-0.5">{{ $item->nama_barang }}</h5>
                        <span class="text-slate-500">{{ $item->qty }} x Rp {{ number_format($item->harga,0,',','.') }}</span>
                    </div>
                    <div class="font-bold text-slate-900 text-sm">
                        Rp {{ number_format($item->subtotal,0,',','.') }}
                    </div>
                </div>
            @endforeach
        </div>

        <!-- RINCIAN PEMBAYARAN -->
        <div class="flex justify-end pt-2 border-t border-slate-100">
            <div class="w-full sm:w-80 space-y-2 text-sm bg-slate-50 sm:bg-transparent p-4 sm:p-0 rounded-xl">
                <div class="flex justify-between text-slate-600">
                    <span>Subtotal</span>
                    <span class="font-medium text-slate-800">Rp {{ number_format($transaction->subtotal,0,',','.') }}</span>
                </div>
                <div class="flex justify-between text-slate-600">
                    <span>Voucher</span>
                    <span class="font-medium text-slate-800">Rp {{ number_format($transaction->voucher,0,',','.') }}</span>
                </div>
                <div class="flex justify-between text-slate-600">
                    <span>Card</span>
                    <span class="font-medium text-slate-800">Rp {{ number_format($transaction->card,0,',','.') }}</span>
                </div>
                <hr class="border-slate-200 my-2">
                <div class="flex justify-between font-extrabold text-lg text-slate-900">
                    <span>Total</span>
                    <span>Rp {{ number_format($transaction->grand_total,0,',','.') }}</span>
                </div>
                <div class="flex justify-between text-slate-600">
                    <span>Bayar</span>
                    <span class="font-medium text-slate-800">Rp {{ number_format($transaction->cash,0,',','.') }}</span>
                </div>
                <div class="flex justify-between text-green-600 font-bold text-base">
                    <span>Kembalian</span>
                    <span>Rp {{ number_format($transaction->kembalian,0,',','.') }}</span>
                </div>
            </div>
        </div>

    </div>

</div>

@endsection