@extends('layouts.app')

@section('title', 'Pembatalan Transaksi')

@section('content')

<x-page-header
    title="Pembatalan Transaksi"
    subtitle="Kelola dan proses pembatalan transaksi penjualan"
/>

<x-card>

    {{-- Filter Toolbar --}}
    <div class="flex justify-between items-center mb-6">
        <form
            method="GET"
            action="{{ route('pembatalan.index') }}"
            class="flex gap-3 items-center flex-wrap"
        >
            <div>
                <input 
                    type="date" 
                    name="start_date" 
                    value="{{ request('start_date') }}" 
                    class="px-3 py-2 border border-slate-300 rounded-lg text-sm focus:outline-none focus:border-emerald-500"
                >
            </div>

            <div>
                <input 
                    type="date" 
                    name="end_date" 
                    value="{{ request('end_date') }}" 
                    class="px-3 py-2 border border-slate-300 rounded-lg text-sm focus:outline-none focus:border-emerald-500"
                >
            </div>

            <x-button color="gray" type="submit">
                <i class="ri-filter-3-line"></i>
                Filter
            </x-button>

            <a href="{{ route('pembatalan.index') }}">
                <x-button color="secondary" type="button">
                    <i class="ri-refresh-line"></i>
                    Reset
                </x-button>
            </a>
        </form>
    </div>

    {{-- Tabel Utama --}}
    <x-table>
        <x-table-header>
            <tr>
                <x-table-head class="text-left p-3 font-bold">No Nota</x-table-head>
                <x-table-head class="text-left p-3 font-bold">Tanggal</x-table-head>
                <x-table-head class="text-left p-3 font-bold">Kasir</x-table-head>
                <x-table-head class="text-right p-3 font-bold">Total</x-table-head>
                <x-table-head class="text-center p-3 font-bold">Status</x-table-head>
                <x-table-head class="text-center p-3 font-bold">Aksi</x-table-head>
            </tr>
        </x-table-header>

        <tbody>
        @forelse($transactions as $trx)
            <tr>
                <x-table-cell class="text-left font-semibold">
                    {{ $trx->no_nota }}
                </x-table-cell>

                <x-table-cell class="text-left">
                    {{ $trx->created_at }}
                </x-table-cell>

                <x-table-cell class="text-left">
                    {{ $trx->user?->name ?? 'Kasir' }}
                </x-table-cell>

                <x-table-cell class="text-right font-semibold">
                    Rp {{ number_format($trx->grand_total, 0, ',', '.') }}
                </x-table-cell>

                <x-table-cell class="text-center">
                    @if($trx->status === 'Batal')
                        <x-badge color="red">
                            Batal
                        </x-badge>
                    @else
                        <x-badge color="green">
                            Sold
                        </x-badge>
                    @endif
                </x-table-cell>

                <x-table-cell class="text-center">
                    <div class="flex justify-center">
                        @if($trx->status === 'Batal')
                            <x-button color="secondary" size="sm" disabled title="Transaksi ini sudah dibatalkan">
                                <i class="ri-close-circle-line"></i>
                                Pembatalan
                            </x-button>
                        @else
                            <a href="{{ route('pembatalan.create', $trx->id) }}">
                                <x-button color="red" size="sm">
                                    <i class="ri-close-circle-line"></i>
                                    Pembatalan
                                </x-button>
                            </a>
                        @endif
                    </div>
                </x-table-cell>
            </tr>
        @empty
            <tr>
                <td colspan="6">
                    <x-empty-state
                        icon="ri-history-line"
                        title="Belum ada transaksi"
                        description="Data transaksi penjualan akan muncul di sini."
                    />
                </td>
            </tr>
        @endforelse
        </tbody>
    </x-table>

    <div class="mt-6">
        {{ $transactions->links() }}
    </div>

</x-card>

@endsection