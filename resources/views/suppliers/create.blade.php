@extends('layouts.app')

@section('title','Tambah Supplier')

@section('content')

<x-page-header
    title="Tambah Supplier"
    subtitle="Tambahkan supplier baru"
>

    <x-slot:action>

        <a href="{{ route('suppliers.index') }}">

            <x-button color="gray">

                <i class="ri-arrow-left-line"></i>

                Kembali

            </x-button>

        </a>

    </x-slot:action>

</x-page-header>


<x-card>

<form
    method="POST"
    action="{{ route('suppliers.store') }}"
>

@csrf

<div class="grid grid-cols-2 gap-6">

    <x-input
        label="Kode Supplier"
        name="kode"
        icon="ri-barcode-line"
        required
        :value="old('kode')"
    />

    <x-input
        label="Nama Supplier"
        name="nama"
        icon="ri-truck-line"
        required
        :value="old('nama')"
    />

    <x-input
        label="PIC"
        name="pic"
        icon="ri-user-3-line"
        :value="old('pic')"
    />

    <x-input
        label="Telepon"
        name="telepon"
        icon="ri-phone-line"
        :value="old('telepon')"
    />

    <x-input
        label="Email"
        name="email"
        type="email"
        icon="ri-mail-line"
        :value="old('email')"
    />

    <x-select
        label="Status"
        name="is_active"
    >

        <option value="1" selected>

            Aktif

        </option>

        <option value="0">

            Nonaktif

        </option>

    </x-select>

</div>

<div class="mt-6">

    <x-textarea
        label="Alamat"
        name="alamat"
        rows="4"
        placeholder="Alamat supplier..."
    />

</div>

<div class="flex justify-end gap-3 mt-8">

    <a href="{{ route('suppliers.index') }}">

        <x-button color="gray">

            <i class="ri-close-line"></i>

            Batal

        </x-button>

    </a>

    <x-button
        color="primary"
        type="submit"
    >

        <i class="ri-save-line"></i>

        Simpan Supplier

    </x-button>

</div>

</form>

</x-card>

@endsection