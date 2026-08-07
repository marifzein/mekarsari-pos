@extends(
    preg_match('/(android|bb\d+|meego).+mobile|avantgo|bada\/|blackberry|blazer|compal|elaine|fennec|hiptop|iemobile|ip(hone|od)|iris|kindle|lge |maemo|midp|mmp|mobile.+firefox|netfront|opera m(ob|in)i|palm( os)?|phone|p(ixi|re)\/|plucker|pocket|psp|series(4|6)0|symbian|treo|up\.(browser|link)|vodafone|wap|windows ce|xda|xiino/i', request()->header('User-Agent')) 
    || preg_match('/(ipad|tablet|(android(?!.*mobile)))/i', request()->header('User-Agent')) 
    ? 'layouts.mobile-app' 
    : 'layouts.app'
)

@section('title', 'Master Pelanggan')

@section('content')

<!-- Header Halaman Fleksibel -->
<div class="flex items-center justify-between gap-3 mb-4 px-1 sm:px-0">
    <div>
        <h1 class="text-xl sm:text-2xl font-bold text-slate-800 leading-tight">Master Pelanggan</h1>
        <p class="text-xs sm:text-sm text-slate-500">Kelola data Pelanggan</p>
    </div>
    <a href="{{ route('customers.create') }}" class="shrink-0">
        <x-button color="primary" size="sm" class="px-3 py-2 text-xs sm:text-sm">
            <i class="ri-add-line"></i>
            <span>Tambah</span>
        </x-button>
    </a>
</div>

<!-- Container Konten Tanpa Margin Berlebih -->
<div class="bg-white rounded-2xl border border-slate-200/80 p-3 sm:p-6 shadow-sm">

    <!-- Form Pencarian compact -->
    <form method="GET" class="mb-4">
        <div class="flex gap-2">
            <div class="flex-1">
                <x-input
                    name="search"
                    placeholder="Cari Pelanggan..."
                    :value="request('search')"
                    class="w-full text-sm"
                />
            </div>
            <x-button color="primary" size="sm" class="px-4">
                Cari
            </x-button>
        </div>
    </form>

    {{-- TAMPILAN DESKTOP (TABEL) --}}
    <div class="hidden md:block overflow-x-auto">
        <x-table>
            <x-table-header>
                <tr>
                    <x-table-head class="text-left">Nama</x-table-head>
                    <x-table-head class="text-left">Alamat</x-table-head>
                    <x-table-head class="text-left">Telepon</x-table-head>
                    <x-table-head>Member</x-table-head>
                    <x-table-head>Status</x-table-head>
                    <x-table-head class="text-center">Aksi</x-table-head>
                </tr>
            </x-table-header>
            <tbody>
                @forelse($customers as $customer)
                    <tr>
                        <x-table-cell class="text-left font-medium">{{ $customer->nama }}</x-table-cell>
                        <x-table-cell class="max-w-xs truncate text-left">{{ $customer->alamat ?: '-' }}</x-table-cell>
                        <x-table-cell class="text-left">{{ $customer->telepon }}</x-table-cell>
                        <x-table-cell class="text-center">{{ $customer->is_member ? 'Member' : '-' }}</x-table-cell>
                        <x-table-cell class="text-center">
                            @if($customer->status)
                                <x-badge color="green">Aktif</x-badge>
                            @else
                                <x-badge color="red">Nonaktif</x-badge>
                            @endif
                        </x-table-cell>
                        <x-table-cell>
                            <div class="flex justify-center">
                                <a href="{{ route('customers.edit',$customer) }}">
                                    <x-button size="sm" color="blue">
                                        <i class="ri-edit-line"></i>
                                    </x-button>
                                </a>
                            </div>
                        </x-table-cell>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6">
                            <x-empty-state icon="ri-user-3-line" title="Belum ada Pelanggan" description="Klik Tambah Pelanggan." />
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </x-table>
    </div>

    {{-- TAMPILAN MOBILE & TABLET (CARD LIST) --}}
    <div class="block md:hidden space-y-3">
        @forelse($customers as $customer)
        <div class="bg-slate-50 border border-slate-200/80 rounded-xl p-3.5 shadow-3xs flex flex-col justify-between gap-2.5">
            <div class="flex justify-between items-start">
                <div>
                    <h4 class="font-bold text-slate-800 text-sm sm:text-base">{{ $customer->nama }}</h4>
                    <p class="text-xs text-slate-500 mt-0.5 flex items-center gap-1">
                        <i class="ri-phone-line"></i> {{ $customer->telepon ?: '-' }}
                    </p>
                </div>
                <div>
                    @if($customer->status)
                        <x-badge color="green" class="text-[10px] px-2 py-0.5">Aktif</x-badge>
                    @else
                        <x-badge color="red" class="text-[10px] px-2 py-0.5">Nonaktif</x-badge>
                    @endif
                </div>
            </div>

            <div class="text-xs text-slate-600 bg-white p-2 rounded-lg border border-slate-100 flex items-center gap-1.5">
                <i class="ri-map-pin-line text-indigo-500 shrink-0"></i> 
                <span class="truncate">{{ $customer->alamat ?: 'Tidak ada alamat' }}</span>
            </div>

            <div class="flex items-center justify-between pt-2 border-t border-slate-200/60">
                <span class="text-[11px] font-medium px-2 py-0.5 bg-indigo-50 text-indigo-600 rounded-md">
                    {{ $customer->is_member ? '★ Member' : 'Regular' }}
                </span>
                <a href="{{ route('customers.edit', $customer) }}">
                    <x-button size="sm" color="blue" class="px-3 py-1 text-xs">
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
    
    @if(method_exists($customers, 'links'))
        <div class="mt-4">
            {{ $customers->links() }}
        </div>
    @endif

</div>

@endsection