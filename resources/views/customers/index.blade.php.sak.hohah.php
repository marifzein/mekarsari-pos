{{-- @extends('layouts.app')

@section('title','Master Pelanggan')

@section('content') --}}

@extends(
    preg_match('/(android|bb\d+|meego).+mobile|avantgo|bada\/|blackberry|blazer|compal|elaine|fennec|hiptop|iemobile|ip(hone|od)|iris|kindle|lge |maemo|midp|mmp|mobile.+firefox|netfront|opera m(ob|in)i|palm( os)?|phone|p(ixi|re)\/|plucker|pocket|psp|series(4|6)0|symbian|treo|up\.(browser|link)|vodafone|wap|windows ce|xda|xiino/i', request()->header('User-Agent')) 
    || preg_match('/(ipad|tablet|(android(?!.*mobile)))/i', request()->header('User-Agent')) 
    ? 'layouts.mobile-app' 
    : 'layouts.app'
)

@section('title', 'Master Pelanggan')

@section('content')

<x-page-header
    title="Master Pelanggan"
    subtitle="Kelola data Pelanggan"
>

    <x-slot:action>

        <a href="{{ route('customers.create') }}">

            <x-button color="primary" size="xs">

            <i class="ri-add-line"></i>

            Tambah Pelanggan

            </x-button>

        </a>

    </x-slot:action>

</x-page-header>

<x-card>

    <form
            method="GET"
            class="mb-6"
    >

        <div class="flex gap-3">

            <x-input

            name="search"

            placeholder="Cari Pelanggan..."

            :value="request('search')"

            />

            <x-button>

                Cari

            </x-button>

        </div>

    </form>

    <div class="hidden md:block">
    <x-table>

        <x-table-header>

            <tr>



                <x-table-head class="text-left">Nama</x-table-head>

                <x-table-head class="text-left">Alamat</x-table-head>

                <x-table-head class="text-left">Telepon</x-table-head>

                <x-table-head>Member</x-table-head>

                <x-table-head>Status</x-table-head>

                <x-table-head class="text-center">

                Aksi

                </x-table-head>

            </tr>

        </x-table-header>

        <tbody>

            @forelse($customers as $customer)

                <tr>



                                <x-table-cell class="text-left">

                                {{ $customer->nama }}

                                </x-table-cell>

                                <x-table-cell class="max-w-xs truncate text-left">
                                    {{ $customer->alamat ?: '-' }}
                                </x-table-cell>

                                <x-table-cell class="text-left">

                                {{ $customer->telepon  }}

                                </x-table-cell>

                                <x-table-cell class="text-center">

                                {{ $customer->is_member ? 'Member' : '-' }}

                                </x-table-cell>

                                <x-table-cell class="text-center">

                                @if($customer->status)
                                    <x-badge color="green">
                                        Aktif
                                    </x-badge>
                                @else
                                    <x-badge color="red">
                                        Nonaktif
                                    </x-badge>
                                @endif

                                </x-table-cell>

                                <x-table-cell>

                                <div class="flex justify-center">

                                <a href="{{ route('customers.edit',$customer) }}">

                                <x-button
                                size="sm"
                                color="blue"
                                >

                                <i class="ri-edit-line"></i>

                                </x-button>

                                </a>

                                </div>

                                </x-table-cell>

                </tr>

            @empty

                <tr>

                    <td colspan="6">

                    <x-empty-state

                    icon="ri-user-3-line"

                    title="Belum ada Pelanggan"

                    description="Klik Tambah Pelanggan."

                    />

                    </td>

                </tr>

            @endforelse

        </tbody>

    </x-table>
</div>

    <!-- TAMPILAN MOBILE & TABLET (CARD LIST) -->
    <div class="block md:hidden space-y-3">
        @forelse($customers as $customer)
        <div class="bg-slate-50 border border-slate-200/80 rounded-2xl p-4 shadow-3xs flex flex-col justify-between gap-3">
            <div class="flex justify-between items-start">
                <div>
                    <h4 class="font-bold text-slate-800 text-base">{{ $customer->nama }}</h4>
                    <p class="text-xs text-slate-500 mt-0.5"><i class="ri-phone-line"></i> {{ $customer->telepon ?: '-' }}</p>
                </div>
                <div>
                    @if($customer->status)
                        <x-badge color="green">Aktif</x-badge>
                    @else
                        <x-badge color="red">Nonaktif</x-badge>
                    @endif
                </div>
            </div>

            <div class="text-xs text-slate-600 bg-white p-2.5 rounded-xl border border-slate-100">
                <i class="ri-map-pin-line text-indigo-500"></i> {{ $customer->alamat ?: 'Tidak ada alamat' }}
            </div>

            <div class="flex items-center justify-between pt-2 border-t border-slate-200/60">
                <span class="text-xs font-semibold px-2 py-0.5 bg-indigo-50 text-indigo-600 rounded-lg">
                    {{ $customer->is_member ? '★ Member' : 'Regular' }}
                </span>
                <a href="{{ route('customers.edit', $customer) }}">
                    <x-button size="sm" color="blue">
                        <i class="ri-edit-line"></i> Edit
                    </x-button>
                </a>
            </div>
        </div>
        @empty
        <x-empty-state
            icon="ri-user-3-line"
            title="Belum ada Pelanggan"
            description="Klik Tambah Pelanggan."
        />
        @endforelse
    </div>
    
    <div class="mt-6">

        {{ $customers->links() }}

    </div>

</x-card>

@endsection