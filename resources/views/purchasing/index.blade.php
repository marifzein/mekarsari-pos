@extends('layouts.app')

@section('title','Purchasing')

@section('content')

<x-page-header

    title="Purchasing"

    subtitle="Kelola Purchase Order Supplier"

>

    <x-slot:action>

        <a href="{{ route('purchasing.create') }}">

            <x-button color="primary" full>

                <i class="ri-add-line"></i>

                Buat PO

            </x-button>

        </a>

    </x-slot:action>

</x-page-header>

<x-card>

    {{-- Toolbar --}}
    <div class="flex justify-between items-center mb-6">

        <form
            method="GET"
            class="flex gap-3"
            action="{{ route('purchasing.index') }}"
        >

            <input

                type="text"

                name="search"

                value="{{ request('search') }}"

                placeholder="Cari Nomor PO / Supplier..."

                class="w-80 rounded-xl border border-slate-300 px-4 py-3"

            >

            <x-button type="submit">

                <i class="ri-search-line"></i>

                Cari

            </x-button>

        </form>

    </div>

    <x-table>

        <x-table-header>

            <tr>

                <x-table-head>No PO</x-table-head>

                <x-table-head>Tanggal</x-table-head>

                <x-table-head>Supplier</x-table-head>

                <x-table-head class="text-right">Total</x-table-head>

                <x-table-head class="text-center">Status</x-table-head>

                <x-table-head class="text-center">Aksi</x-table-head>

            </tr>

        </x-table-header>

        <tbody>

        @forelse($purchaseOrders as $po)

            <tr>

                <x-table-cell>

                    {{ $po->po_number }}

                </x-table-cell>

                <x-table-cell>

                    {{ \Carbon\Carbon::parse($po->po_date)->format('d-m-Y') }}

                </x-table-cell>

                <x-table-cell>

                    {{ $po->supplier->nama }}

                </x-table-cell>

                <x-table-cell class="text-right">

                    Rp {{ number_format($po->total,0,',','.') }}

                </x-table-cell>

                <x-table-cell class="text-center">

                    @switch($po->status)

                        @case('DRAFT')

                            <x-badge color="gray">

                                Draft

                            </x-badge>

                        @break

                        @case('ORDERED')

                            <x-badge color="blue">

                                Ordered

                            </x-badge>

                        @break

                        @case('RECEIVED')

                            <x-badge color="green">

                                Received

                            </x-badge>

                        @break

                        @case('CANCELLED')

                            <x-badge color="red">

                                Cancelled

                            </x-badge>

                        @break

                    @endswitch

                </x-table-cell>

                <x-table-cell class="text-center">

                    <div class="flex justify-center gap-2">

                        {{-- <a href="{{ route('purchasing.edit',$po) }}">

                            <x-button color="blue" size="sm" >

                                <i class="ri-edit-line"></i>

                            </x-button>

                        </a> --}}
                        {{-- Cek Apakah Statusnya DRAFT --}}
                        @if($po->status === 'DRAFT')
                            <a href="{{ route('purchasing.edit', $po) }}">
                                <x-button color="blue" size="sm">
                                    <i class="ri-edit-line"></i>
                                </x-button>
                            </a>
                        @else
                            {{-- Tampilkan tombol edit yang mati/disabled secara visual --}}
                            <x-button color="gray" size="sm" class="opacity-50 cursor-not-allowed" disabled>
                                <i class="ri-edit-line"></i>
                            </x-button>
                        @endif

                        <a href="#">

                            <a href="{{ route('purchasing.show', $po) }}">
                                <x-button color="green" size="sm" title="Lihat Detail">
                                    <i class="ri-eye-line"></i>
                                </x-button>
                            </a>

                        </a>

                        @if($po->status === 'ORDERED' || $po->status === 'RECEIVED')
                            <a href="{{ route('purchasing.print-pdf', $po) }}" target="_blank">
                                <x-button color="orange" size="sm" title="Cetak PO">
                                    <i class="ri-printer-line"></i>
                                </x-button>
                            </a>
                        @else
                            <x-button color="gray" size="sm" class="opacity-40 cursor-not-allowed" title="Cetak hanya aktif jika status ORDERED" disabled>
                                <i class="ri-printer-line"></i>
                            </x-button>
                        @endif
                        {{-- <a href="#">

                            <x-button color="orange" size="sm">

                                <i class="ri-printer-line"></i>

                            </x-button>

                        </a> --}}

                    </div>

                </x-table-cell>

            </tr>

        @empty

            <tr>

                <td colspan="6">

                    <x-empty-state

                        icon="ri-file-paper-2-line"

                        title="Belum ada Purchase Order"

                        description="Klik tombol Buat PO untuk membuat Purchase Order pertama."

                    />

                </td>

            </tr>

        @endforelse

        </tbody>

    </x-table>

    <div class="mt-6">

        {{ $purchaseOrders->links() }}

    </div>

</x-card>

@endsection