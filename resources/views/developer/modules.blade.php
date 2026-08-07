@extends('layouts.app')

@section('title', 'Pengaturan Akses Modul')

@section('content')

<x-page-header
    title="Pengaturan Akses Modul"
    subtitle="Aktifkan atau nonaktifkan modul aplikasi untuk paket client ini"
/>

{{-- Tampilkan Notifikasi Sukses --}}
@if(session('success'))
    <div class="mb-4 p-4 bg-emerald-100 text-emerald-800 rounded-xl border border-emerald-200">
        {{ session('success') }}
    </div>
@endif

<x-card>
    {{-- Form dikirim secara massal --}}
    <form action="{{ route('developer.modules.update') }}" method="POST">
        @csrf

        <x-table>
            <x-table-header>
                <tr>
                    <th class="px-4 py-3 text-left font-semibold text-slate-700">Nama Modul (Controller)</th>
                    <th class="px-4 py-3 text-center font-semibold text-slate-700">Status Database</th>
                    <th class="px-4 py-3 text-center font-semibold text-slate-700 w-32">Aksi Akses</th>
                </tr>
            </x-table-header>

            <tbody>
                @forelse($modules as $module)
                    <tr class="border-b border-slate-100 hover:bg-slate-50/50 transition duration-150">
                        <td class="px-4 py-4 text-slate-700 font-medium">
                            {{ $module->controller_name }}
                        </td>
                        <td class="px-4 py-4 text-center">
                            @if($module->is_active)
                                <x-badge color="green">Aktif</x-badge>
                            @else
                                <x-badge color="red">Nonaktif</x-badge>
                            @endif
                        </td>
                        <td class="px-4 py-4">
                            <div class="flex justify-center items-center">
                                {{-- 💡 Menggunakan custom component input checkbox milikmu --}}
                                <input 
                                    type="checkbox" 
                                    name="active_modules[]" 
                                    value="{{ $module->id }}" 
                                    {{ $module->is_active ? 'checked' : '' }}
                                    class="w-6 h-6 text-indigo-600 border-slate-300 rounded-lg focus:ring-4 focus:ring-indigo-100 focus:border-indigo-500 cursor-pointer transition-all duration-200"
                                >
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="3">
                            <x-empty-state
                                icon="ri-code-s-slash-line"
                                title="Belum ada data modul"
                                description="Silakan jalankan seeder terlebih dahulu untuk mengisi data Controller."
                            />
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </x-table>

        {{-- Tombol Submit Simpan ditaruh di bawah Tabel komponen --}}
        <div class="mt-6 flex justify-end gap-3 border-t border-slate-100 pt-5">
            <x-button type="submit" color="primary" class="shadow-md shadow-indigo-100">
                <i class="ri-save-line"></i> Simpan Konfigurasi Modul
            </x-button>
        </div>
    </form>
</x-card>

@endsection