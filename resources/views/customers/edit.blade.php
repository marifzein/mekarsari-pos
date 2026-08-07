@extends(
    preg_match('/(android|bb\d+|meego).+mobile|avantgo|bada\/|blackberry|blazer|compal|elaine|fennec|hiptop|iemobile|ip(hone|od)|iris|kindle|lge |maemo|midp|mmp|mobile.+firefox|netfront|opera m(ob|in)i|palm( os)?|phone|p(ixi|re)\/|plucker|pocket|psp|series(4|6)0|symbian|treo|up\.(browser|link)|vodafone|wap|windows ce|xda|xiino/i', request()->header('User-Agent')) 
    || preg_match('/(ipad|tablet|(android(?!.*mobile)))/i', request()->header('User-Agent')) 
    ? 'layouts.mobile-app' 
    : 'layouts.app'
)

@section('title', 'Edit Pelanggan')

@section('content')

<!-- Header Halaman Compact -->
<div class="flex items-center justify-between gap-3 mb-4 px-1 sm:px-0">
    <div>
        <h1 class="text-xl sm:text-2xl font-bold text-slate-800 leading-tight">Edit Pelanggan</h1>
        <p class="text-xs sm:text-sm text-slate-500">Perbarui data Pelanggan</p>
    </div>
    <a href="{{ route('customers.index') }}" class="shrink-0">
        <x-button color="gray" size="sm" class="px-3 py-2 text-xs sm:text-sm">
            <i class="ri-arrow-left-line"></i>
            <span>Kembali</span>
        </x-button>
    </a>
</div>

<!-- Container Card Form -->
<div class="bg-white rounded-2xl border border-slate-200/80 p-4 sm:p-6 shadow-sm">

    <form method="POST" action="{{ route('customers.update', $customer) }}">
        @csrf
        @method('PUT')

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

            <x-input
                label="Nama Pelanggan"
                name="nama"
                icon="ri-user-line"
                required
                :value="old('nama', $customer->nama)"
            />

            <x-input
                label="Telepon"
                name="telepon"
                icon="ri-phone-line"
                :value="old('telepon', $customer->telepon)"
            />

            <x-input
                label="Email"
                name="email"
                type="email"
                icon="ri-mail-line"
                :value="old('email', $customer->email)"
            />

            <x-select label="Status" name="status">
                <option value="1" {{ old('status', $customer->status) == 1 ? 'selected' : '' }}>
                    Aktif
                </option>
                <option value="0" {{ old('status', $customer->status) == 0 ? 'selected' : '' }}>
                    Nonaktif
                </option>
            </x-select>

            <div class="md:col-span-2">
                <x-input
                    label="Catatan"
                    name="catatan"
                    icon="ri-sticky-note-line"
                    :value="old('catatan', $customer->catatan)"
                />
            </div>

            <!-- Checkbox Member dibuat rata dengan opsi lain -->
            <div class="md:col-span-2 flex items-center gap-2 py-1">
                <input
                    id="is_member"
                    type="checkbox"
                    name="is_member"
                    value="1"
                    {{ old('is_member', $customer->is_member) ? 'checked' : '' }}
                    class="h-4 w-4 rounded border-slate-300 text-indigo-600 focus:ring-indigo-500"
                >
                <label for="is_member" class="text-sm font-medium text-slate-700 cursor-pointer">
                    Daftarkan sebagai Member
                </label>
            </div>

        </div>

        <div class="mt-4">
            <x-textarea
                label="Alamat"
                name="alamat"
                rows="3"
            >{{ old('alamat', $customer->alamat) }}</x-textarea>
        </div>

        <!-- Tombol Aksi (Mobile: Penuh & Stack, Desktop: Rata Kanan) -->
        <div class="flex flex-col-reverse sm:flex-row justify-end gap-2.5 mt-6 pt-4 border-t border-slate-100">
            <a href="{{ route('customers.index') }}" class="w-full sm:w-auto">
                <x-button color="gray" type="button" class="w-full justify-center text-sm py-2.5">
                    <i class="ri-close-line"></i>
                    Batal
                </x-button>
            </a>

            <x-button color="primary" type="submit" class="w-full sm:w-auto justify-center text-sm py-2.5">
                <i class="ri-save-line"></i>
                Simpan Perubahan
            </x-button>
        </div>

    </form>

</div>

@endsection