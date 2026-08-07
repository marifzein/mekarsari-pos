@extends('layouts.app')

@section('title', 'Master COA (Akuntansi)')

@section('content')

@if(session('success'))
<x-alert>
    {{ session('success') }}
</x-alert>
@endif

<x-page-header
    title="Master COA (Akuntansi)"
    subtitle="Kelola Daftar Rekening Perkiraan Jurnal Otomatis"
>
    <x-slot:action>
        <a href="{{ route('coa.create') }}">
            <x-button color="primary">
                <i class="ri-add-line"></i>
                Tambah Akun COA
            </x-button>
        </a>
    </x-slot:action>
</x-page-header>

<x-card>
    <form method="GET" class="mb-6">
        <div class="relative w-85">
            <i class="ri-search-line absolute left-4 top-1/2 -translate-y-1/2 text-slate-400"></i>
            <input
                type="text"
                name="search"
                value="{{ request('search') }}"
                placeholder="Cari kode, nama, atau tipe akun..."
                class="w-full rounded-xl border border-slate-300 pl-11 pr-4 py-3 focus:border-indigo-500 focus:ring-4 focus:ring-indigo-100"
            >
        </div>
    </form>

    <x-table>
        <x-table-header>
            <tr>
                <x-table-head class="text-left">Kode Akun</x-table-head>
                <x-table-head class="text-left">Nama Akun</x-table-head>
                <x-table-head class="text-center">Klasifikasi</x-table-head>
                <x-table-head class="text-center">Laporan</x-table-head>
                <x-table-head class="text-center">Status</x-table-head>
                <x-table-head class="text-center">Aksi</x-table-head>
            </tr>
        </x-table-header>
        
        <tbody>
        @forelse($coas as $coa)
            <tr>
                <x-table-cell class="font-mono font-bold text-slate-700">
                    {{ $coa->account_code }}
                </x-table-cell>
                
                <x-table-cell>
                    {{ $coa->account_name }}
                </x-table-cell>
                
                <x-table-cell class="text-center">
                    @switch($coa->account_type)
                        @case('HARTA') <x-badge color="blue">HARTA</x-badge> @break
                        @case('UTANG') <x-badge color="red">UTANG</x-badge> @break
                        @case('MODAL') <x-badge color="purple">MODAL</x-badge> @break
                        @case('PENDAPATAN') <x-badge color="green">PENDAPATAN</x-badge> @break
                        @case('HPP') <x-badge color="orange">HPP</x-badge> @break
                        @case('BEBAN') <x-badge color="yellow">BEBAN</x-badge> @break
                        @default <x-badge color="gray">{{ $coa->account_type }}</x-badge>
                    @endswitch
                </x-table-cell>
                
                <x-table-cell class="text-center text-xs font-semibold text-slate-600">
                    {{ $coa->report_type }}
                </x-table-cell>
                
                <x-table-cell class="text-center">
                    @if($coa->is_active)
                        <x-badge color="green">Aktif</x-badge>
                    @else
                        <x-badge color="red">Nonaktif</x-badge>
                    @endif
                </x-table-cell>
                
                <x-table-cell>
                    <div class="flex justify-center">
                        @if($coa->is_system)
                            <span class="text-xs text-slate-400 italic font-medium">
                                <i class="ri-lock-2-line"></i> Sistem
                            </span>
                        @else
                            <a href="{{ route('coa.edit', $coa) }}">
                                <x-button color="blue" size="sm">
                                    <i class="ri-edit-line"></i>
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
                        icon="ri-bank-card-line"
                        title="Belum ada akun COA"
                        description="Klik Tambah Akun COA untuk membuat rekening perkiraan baru."
                    />
                </td>
            </tr>
        @endforelse
        </tbody>
    </x-table>

    <div class="mt-6">
        {{ $coas->links() }}
    </div>
</x-card>

@endsection