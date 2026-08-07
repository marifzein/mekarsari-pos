@extends('layouts.app')

@section('title','Edit Supplier')

@section('content')

<x-page-header
    title="Edit Supplier"
    subtitle="Perbarui data supplier"
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
    action="{{ route('suppliers.update',$supplier) }}"
>

@csrf
@method('PUT')

<div class="grid grid-cols-2 gap-6">

    <x-input
        label="Kode Supplier"
        name="kode"
        icon="ri-barcode-line"
        required
        :value="old('kode',$supplier->kode)"
    />

    <x-input
        label="Nama Supplier"
        name="nama"
        icon="ri-truck-line"
        required
        :value="old('nama',$supplier->nama)"
    />

    <x-input
        label="PIC"
        name="pic"
        icon="ri-user-3-line"
        :value="old('pic',$supplier->pic)"
    />

    <x-input
        label="Telepon"
        name="telepon"
        icon="ri-phone-line"
        :value="old('telepon',$supplier->telepon)"
    />

    <x-input
        label="Email"
        name="email"
        type="email"
        icon="ri-mail-line"
        :value="old('email',$supplier->email)"
    />

    <x-select
        label="Status"
        name="is_active"
    >

        <option
            value="1"
            {{ old('is_active',$supplier->is_active)==1 ? 'selected' : '' }}
        >

            Aktif

        </option>

        <option
            value="0"
            {{ old('is_active',$supplier->is_active)==0 ? 'selected' : '' }}
        >

            Nonaktif

        </option>

    </x-select>

</div>

<div class="mt-6">

    <x-textarea
        label="Alamat"
        name="alamat"
        rows="4"
    >{{ old('alamat',$supplier->alamat) }}</x-textarea>

</div>

<div class="flex justify-end gap-3 mt-8">

    <a href="{{ route('suppliers.index') }}">

        <x-button color="gray" type="button">

            <i class="ri-close-line"></i>

            Batal

        </x-button>

    </a>

    <x-button
        color="primary"
        type="submit"
    >

        <i class="ri-save-line"></i>

        Simpan Perubahan

    </x-button>

</div>

</form>

</x-card>

@endsection