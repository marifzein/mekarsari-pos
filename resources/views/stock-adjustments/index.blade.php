@extends('layouts.app')

@section('title', 'Stock Adjustment')

@section('content')

<x-page-header
    title="Stock Adjustment (SA)"
    subtitle="Kelola penyesuaian stok barang rusak, cacat, atau expired"
>
    <x-slot:action>
        <a href="{{ route('stock-adjustments.create') }}">
            <x-button color="primary" full>
                <i class="ri-add-line"></i>
                Buat SA Baru
            </x-button>
        </a>
    </x-slot:action>
</x-page-header>

<x-card>
    {{-- Toolbar Pencarian --}}
    <div class="flex justify-between items-center mb-6">
        <form method="GET" class="flex gap-3">
            <input
                type="text"
                name="search"
                value="{{ request('search') }}"
                placeholder="Cari Nomor SA..."
                class="w-80 rounded-xl border border-slate-300 px-4 py-3"
            >
            <x-button>
                <i class="ri-search-line"></i>
                Cari
            </x-button>
        </form>
    </div>

    {{-- Tabel Data --}}
    <x-table>
        <x-table-header>
            <tr>
                <x-table-head class="text-left">No SA</x-table-head>
                <x-table-head class="text-left">Tanggal</x-table-head>
                <x-table-head class="text-left">Operator</x-table-head>
                <x-table-head class="text-left">Catatan</x-table-head>
                <x-table-head class="text-center">Status</x-table-head>
                <x-table-head class="text-center">Aksi</x-table-head>
            </tr>
        </x-table-header>

        <tbody>
        @forelse($adjustments as $sa)
            <tr>
                <x-table-cell class="font-semibold text-indigo-600">
                    {{ $sa->nomor_sa }}
                </x-table-cell>
                <x-table-cell>
                    {{ \Carbon\Carbon::parse($sa->tgl_sa)->format('d-m-Y') }}
                </x-table-cell>
                <x-table-cell>
                    {{ $sa->user->name }}
                </x-table-cell>
                <x-table-cell>
                    <span class="text-slate-500 text-sm">{{ $sa->catatan ?? '-' }}</span>
                </x-table-cell>
                <x-table-cell class="text-center">
                    @if($sa->status === 'draft')
                        <x-badge color="gray">Draft</x-badge>
                    @else
                        <x-badge color="green">Closed / Posted</x-badge>
                    @endif
                </x-table-cell>
                <x-table-cell class="text-center">
                    <div class="flex justify-center gap-2">
                        @if($sa->status === 'draft')
                        
                            <a href="{{ route('stock-adjustments.edit', $sa->id) }}" class="text-blue-600 hover:underline">
                            {{-- <a href="{{ route('stock-adjustments.edit', $sa) }}"> --}}
                                <x-button color="blue" size="sm" title="Edit Draft">
                                    <i class="ri-edit-line"></i>
                                </x-button>
                            </a>
                        @else
                            <a href="{{ route('stock-adjustments.edit', $sa->id) }}">
                                <x-button color="blue" size="sm" title="Lihat Detail">
                                    <i class="ri-eye-line"></i>
                                </x-button>
                            </a>
                            {{-- <a href="{{ route('stock-adjustments.edit', $sa->id) }}">
                                <x-button color="gray" size="sm" class="opacity-50 cursor-not-allowed" title="Sudah Terkunci" >
                                    <i class="ri-lock-line"></i>
                                </x-button>
                            </a> --}}
                        @endif
                    </div>
                </x-table-cell>
            </tr>
        @empty
            <tr>
                <td colspan="6">
                    <x-empty-state
                        icon="ri-file-shield-2-line"
                        title="Belum ada Stock Adjustment"
                        description="Klik tombol Buat SA Baru untuk mencatat penyesuaian barang keluar."
                    />
                </td>
            </tr>
        @endforelse
        </tbody>
    </x-table>

    <div class="mt-6">
        {{ $adjustments->links() }}
    </div>
</x-card>

@endsection