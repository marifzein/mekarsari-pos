@extends('layouts.app')

@section('title', 'Edit Akun COA')

@section('content')

<x-page-header
    title="Edit Akun COA"
    subtitle="Ubah detail rekening perkiraan akuntansi"
>
    <x-slot:action>
        <a href="{{ route('coa.index') }}">
            <x-button color="gray">
                <i class="ri-arrow-left-line"></i> Kembali
            </x-button>
        </a>
    </x-slot:action>
</x-page-header>

<x-card>
    <form method="POST" action="{{ route('coa.update', $coa->id) }}">
        @csrf
        @method('PUT')

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <x-input
                label="Kode Akun (Numeric)"
                name="account_code"
                type="number"
                icon="ri-keynote-line"
                placeholder="Contoh: 60700"
                value="{{ old('account_code', $coa->account_code) }}"
                required
            />

            <x-input
                label="Nama Akun"
                name="account_name"
                icon="ri-bank-card-line"
                placeholder="Contoh: Beban Sewa Gedung Toko"
                value="{{ old('account_name', $coa->account_name) }}"
                required
            />

            <x-select label="Klasifikasi Akun (Account Type)" name="account_type" required>
                <option value="">-- Pilih Klasifikasi --</option>
                <option value="HARTA" {{ old('account_type', $coa->account_type) == 'HARTA' ? 'selected' : '' }}>HARTA</option>
                <option value="UTANG" {{ old('account_type', $coa->account_type) == 'UTANG' ? 'selected' : '' }}>UTANG</option>
                <option value="MODAL" {{ old('account_type', $coa->account_type) == 'MODAL' ? 'selected' : '' }}>MODAL</option>
                <option value="PENDAPATAN" {{ old('account_type', $coa->account_type) == 'PENDAPATAN' ? 'selected' : '' }}>PENDAPATAN</option>
                <option value="HPP" {{ old('account_type', $coa->account_type) == 'HPP' ? 'selected' : '' }}>HPP</option>
                <option value="BEBAN" {{ old('account_type', $coa->account_type) == 'BEBAN' ? 'selected' : '' }}>BEBAN</option>
            </x-select>

            <x-select label="Jenis Laporan Keuangan" name="report_type" required>
                <option value="">-- Pilih Jenis Laporan --</option>
                <option value="NERACA" {{ old('report_type', $coa->report_type) == 'NERACA' ? 'selected' : '' }}>NERACA (Harta, Utang, Modal)</option>
                <option value="LABA_RUGI" {{ old('report_type', $coa->report_type) == 'LABA_RUGI' ? 'selected' : '' }}>LABA RUGI (Pendapatan, HPP, Beban)</option>
            </x-select>
        </div>

        <div class="flex justify-end gap-3 mt-8">
            <a href="{{ route('coa.index') }}">
                <x-button color="gray">Batal</x-button>
            </a>
            <x-button color="primary" type="submit">
                <i class="ri-save-line"></i> Perbarui Akun
            </x-button>
        </div>
    </form>
</x-card>

@endsection