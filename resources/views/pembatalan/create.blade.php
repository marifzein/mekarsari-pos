@extends('layouts.app')

@section('title', 'Konfirmasi Pembatalan Transaksi')

@section('content')

<x-page-header
    title="Konfirmasi Pembatalan Transaksi"
    subtitle="Detail transaksi yang akan dibatalkan"
>
    <x-slot:action>
        <a href="{{ route('pembatalan.index') }}">
            <x-button color="gray" type="button">
                <i class="ri-arrow-left-line"></i>
                Kembali
            </x-button>
        </a>
    </x-slot:action>
</x-page-header>

{{-- Info Header Transaksi (Grid 4 Kolom mirip Form PO) --}}
<x-card>
    <div class="grid md:grid-cols-4 gap-6">
        <x-input
            label="Nomor Nota"
            name="no_nota"
            readonly
            :value="$transaction->no_nota"
            icon="ri-file-list-3-line"
        />

        <x-input
            label="Tanggal"
            name="created_at"
            readonly
            :value="\Carbon\Carbon::parse($transaction->created_at)->format('d/m/Y H:i')"
            icon="ri-calendar-line"
        />

        <x-input
            label="Kasir"
            name="kasir"
            readonly
            :value="$transaction->user?->name ?? 'Kasir'"
            icon="ri-user-star-line"
        />

        @php
            $customerName = 'Umum';
            if ($transaction->pelanggan) {
                $customerData = \App\Models\Customer::where('kode_pelanggan', $transaction->pelanggan)->first();
                $customerName = $customerData ? $customerData->nama : $transaction->pelanggan;
            }
        @endphp

        <x-input
            label="Pelanggan"
            name="pelanggan"
            readonly
            :value="$customerName"
            icon="ri-user-3-line"
        />
    </div>
</x-card>

{{-- Detail Barang & Action --}}
<div class="mt-6">
    <x-card>
        <div class="flex justify-between items-center mb-4">
            <div>
                <h3 class="font-semibold text-base text-slate-800">Item Transaksi</h3>
                <p class="text-sm text-slate-500">Produk yang dibeli pada nota ini</p>
            </div>
        </div>

        <x-table>
            <x-table-header>
                <tr>
                    <x-table-head class="text-left font-bold text-slate-700">Produk</x-table-head>
                    <x-table-head class="text-center font-bold text-slate-700">Qty</x-table-head>
                    <x-table-head class="text-right font-bold text-slate-700">Harga</x-table-head>
                    <x-table-head class="text-right font-bold text-slate-700">Subtotal</x-table-head>
                </tr>
            </x-table-header>

            <tbody>
                @foreach($transaction->details as $item)
                <tr>
                    <x-table-cell class="text-left font-semibold text-slate-800">
                        {{ $item->nama_barang }}
                    </x-table-cell>
                    <x-table-cell class="text-center text-slate-700">
                        {{ $item->qty }}
                    </x-table-cell>
                    <x-table-cell class="text-right text-slate-700">
                        Rp {{ number_format($item->harga, 0, ',', '.') }}
                    </x-table-cell>
                    <x-table-cell class="text-right font-semibold text-slate-800">
                        Rp {{ number_format($item->subtotal, 0, ',', '.') }}
                    </x-table-cell>
                </tr>
                @endforeach
            </tbody>
        </x-table>

        <div class="border-t mt-6 pt-6">
            <div class="flex justify-between items-center">
                <a href="{{ route('pembatalan.index') }}">
                    <x-button color="secondary" type="button">
                        <i class="ri-close-line"></i> Batal
                    </x-button>
                </a>

                <div class="flex items-center gap-6">
                    <div class="text-right">
                        <span class="text-sm text-slate-500 block">Total Transaksi</span>
                        <span class="font-bold text-2xl text-slate-800">
                            Rp {{ number_format($transaction->grand_total, 0, ',', '.') }}
                        </span>
                    </div>

                    {{-- <button type="button" id="btn-proses-batal"> --}}
                        <x-button color="red" size="lg" id="btn-proses-batal" type="button">
                            <i class="ri-close-circle-fill"></i> Proses Pembatalan
                        </x-button>
                    {{-- </button> --}}
                </div>
            </div>
        </div>
    </x-card>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
document.getElementById('btn-proses-batal').addEventListener('click', function () {
    Swal.fire({
        title: 'Konfirmasi Pembatalan',
        html: `
            <p class="text-sm text-slate-500 mb-3">Masukkan alasan & password Anda untuk memproses pembatalan ini.</p>
            <input type="text" id="swal-alasan" class="swal2-input" placeholder="Alasan Pembatalan" style="margin-bottom: 10px; width: 80%;">
            <input type="password" id="swal-password" class="swal2-input" placeholder="Masukkan Password Anda" style="width: 80%;">
        `,
        focusConfirm: false,
        showCancelButton: true,
        confirmButtonText: 'Ya, Batalkan Transaksi',
        cancelButtonText: 'Batal',
        confirmButtonColor: '#ef4444',
        cancelButtonColor: '#64748b',
        preConfirm: () => {
            const alasan = document.getElementById('swal-alasan').value;
            const password = document.getElementById('swal-password').value;

            if (!alasan || !password) {
                Swal.showValidationMessage('Alasan dan Password wajib diisi!');
                return false;
            }
            return { alasan: alasan, password: password };
        }
    }).then((result) => {
        if (result.isConfirmed) {
            Swal.fire({ 
                title: 'Memproses...', 
                text: 'Sedang mengembalikan stok dan membatalkan transaksi...',
                allowOutsideClick: false, 
                didOpen: () => Swal.showLoading() 
            });

            fetch("{{ route('pembatalan.store', $transaction->id) }}", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": "{{ csrf_token() }}"
                },
                body: JSON.stringify({
                    alasan: result.value.alasan,
                    password: result.value.password
                })
            })
            .then(res => res.json())
            .then(data => {
                if (data.status === 'success') {
                    Swal.fire({
                        icon: 'success',
                        title: 'Berhasil!',
                        text: data.message,
                        confirmButtonColor: '#22c55e'
                    }).then(() => {
                        window.location.href = "{{ route('pembatalan.index') }}";
                    });
                } else {
                    Swal.fire('Gagal!', data.message, 'error');
                }
            })
            .catch(() => Swal.fire('Error!', 'Terjadi kesalahan sistem.', 'error'));
        }
    });
});
</script>
@endpush

@endsection