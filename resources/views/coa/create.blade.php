@extends('layouts.app')

@section('title', 'Tambah Akun COA')

@section('content')

<x-page-header
    title="Tambah Akun COA"
    subtitle="Buat rekening perkiraan akuntansi baru"
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
    <form method="POST" action="{{ route('coa.store') }}">
        @csrf

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <x-input
                label="Kode Akun (Numeric)"
                name="account_code"
                type="number"
                icon="ri-keynote-line"
                placeholder="Contoh: 60700"
                required
            />

            <x-input
                label="Nama Akun"
                name="account_name"
                icon="ri-bank-card-line"
                placeholder="Contoh: Beban Sewa Gedung Toko"
                required
            />

            <x-select label="Klasifikasi Akun (Account Type)" name="account_type" required>
                <option value="">-- Pilih Klasifikasi --</option>
                <option value="HARTA">HARTA</option>
                <option value="UTANG">UTANG</option>
                <option value="MODAL">MODAL</option>
                <option value="PENDAPATAN">PENDAPATAN</option>
                <option value="HPP">HPP</option>
                <option value="BEBAN">BEBAN</option>
            </x-select>

            <x-select label="Jenis Laporan Keuangan" name="report_type" required>
                <option value="">-- Pilih Jenis Laporan --</option>
                <option value="NERACA">NERACA (Harta, Utang, Modal)</option>
                <option value="LABA_RUGI">LABA RUGI (Pendapatan, HPP, Beban)</option>
            </x-select>
        </div>

        <div class="flex justify-end gap-3 mt-8">
            <a href="{{ route('coa.index') }}">
                <x-button color="gray">Batal</x-button>
            </a>
            <x-button color="primary" type="submit">
                <i class="ri-save-line"></i> Simpan Akun
            </x-button>
        </div>
    </form>
</x-card>

@endsection