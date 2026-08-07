@extends('layouts.app')

@section('title', 'Pengaturan Profil Toko')

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
    
    @if(session('success'))
        <div class="mb-4 p-4 bg-green-50 border-l-4 border-green-500 text-green-700 rounded-r-lg shadow-sm">
            {{ session('success') }}
        </div>
    @endif

    <x-card class="bg-white shadow rounded-lg p-6">
        <!-- Header -->
        <div class="flex items-center mb-6 border-b border-slate-100 pb-4">
            <h2 class="text-xl font-bold text-slate-800 flex items-center gap-2">
                <i class="ri-store-2-line text-indigo-600"></i> Profil Pengaturan Toko
            </h2>
        </div>

        <!-- Form -->
        <form method="POST" action="{{ route('setting.update') }}" enctype="multipart/form-data" class="space-y-5">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-2">Nama Toko <span class="text-red-500">*</span></label>
                    <input type="text" name="nama_toko" value="{{ old('nama_toko', $setting->nama_toko) }}" required
                        class="rounded-xl border border-slate-300 px-4 py-2.5 focus:border-indigo-500 focus:ring-4 focus:ring-indigo-100 w-full">
                </div>

                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-2">Nama Pemilik (Owner)</label>
                    <input type="text" name="owner" value="{{ old('owner', $setting->owner) }}"
                        class="rounded-xl border border-slate-300 px-4 py-2.5 focus:border-indigo-500 focus:ring-4 focus:ring-indigo-100 w-full">
                </div>

                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-2">No. Telepon / WhatsApp</label>
                    <input type="text" name="telepon" value="{{ old('telepon', $setting->telepon) }}"
                        class="rounded-xl border border-slate-300 px-4 py-2.5 focus:border-indigo-500 focus:ring-4 focus:ring-indigo-100 w-full">
                </div>

                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-2">Keterangan Nota (Footer)</label>
                    <input type="text" name="footer_nota" value="{{ old('footer_nota', $setting->footer_nota) }}" placeholder="Contoh: Barang yang sudah dibeli tidak dapat ditukar"
                        class="rounded-xl border border-slate-300 px-4 py-2.5 focus:border-indigo-500 focus:ring-4 focus:ring-indigo-100 w-full">
                </div>
            </div>

            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-2">Alamat Lengkap Toko</label>
                <textarea name="alamat" rows="3" 
                    class="rounded-xl border border-slate-300 px-4 py-2.5 focus:border-indigo-500 focus:ring-4 focus:ring-indigo-100 w-full">{{ old('alamat', $setting->alamat) }}</textarea>
            </div>

            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-2">Upload Logo Toko</label>
                <div class="flex items-center gap-4">
                    @if($setting->logo)
                        <img src="{{ asset('storage/' . $setting->logo) }}" alt="Logo Toko" class="w-16 h-16 object-cover rounded-xl border border-slate-200">
                    @endif
                    <input type="file" name="logo" class="block w-full text-sm text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100"/>
                </div>
            </div>

            <div class="flex justify-end pt-4 border-t border-slate-100">
                <button type="submit" class="px-6 py-2.5 bg-indigo-600 text-white font-semibold rounded-xl hover:bg-indigo-700 transition duration-150">
                    Simpan Perubahan
                </button>
            </div>
        </form>
    </x-card>
</div>
@endsection