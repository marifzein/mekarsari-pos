@extends('layouts.app')

@section('title','Tambah User')

@section('content')

<x-page-header

    title="Tambah User"

    subtitle="Tambahkan akun pengguna baru"

>

    <x-slot:action>

        <a href="/users">

            <x-button color="gray">

                <i class="ri-arrow-left-line"></i>

                Kembali

            </x-button>

        </a>

    </x-slot:action>

</x-page-header>

@if ($errors->any())

<x-alert type="error">

    <div class="font-semibold mb-2">

        Terdapat kesalahan:

    </div>

    <ul class="list-disc ml-5">

        @foreach($errors->all() as $error)

            <li>{{ $error }}</li>

        @endforeach

    </ul>

</x-alert>

@endif

<x-card>

<form

    method="POST"

    action="{{ route('users.store') }}"

>

@csrf

{{-- <div class="grid grid-cols-1 md:grid-cols-2 gap-6"> --}}
    <div class="grid grid-cols-2 gap-6 ">

    <x-input

        label="Nama Lengkap"

        name="name"

        :value="old('name')"

        icon="ri-user-line"

        required

    />

    <x-input

        label="Email"

        name="email"

        type="email"

        :value="old('email')"

        icon="ri-mail-line"

        required

    />

    <x-input

        label="Password"

        name="password"

        type="password"

        icon="ri-lock-password-line"

        required

    />

    <x-select

        label="Role"

        name="role"

        icon="ri-shield-user-line"

        required

    >

        <option value="">

            -- Pilih Role --

        </option>

        @can('akses-developer')
        <option

            value="Admin"

            {{ old('role')=='Admin' ? 'selected' : '' }}

        >

            Admin

        </option>
        @endcan
        
        @can('akses-owner-admin')
        <option

            value="Owner"

            {{ old('role')=='Owner' ? 'selected' : '' }}

        >

            Owner

        </option>
        @endcan


        <option

            value="Supervisor"

            {{ old('role')=='Supervisor' ? 'selected' : '' }}

        >

            Supervisor

        </option>

        <option

            value="Kasir"

            {{ old('role')=='Kasir' ? 'selected' : '' }}

        >

            Kasir

        </option>

    </x-select>

</div>

<div class="flex justify-end gap-3 mt-8">

    <a href="/users">

        <x-button color="gray">

            <i class="ri-close-line"></i>

            Batal

        </x-button>

    </a>

    <x-button

        type="submit"

        color="primary"

    >

        <i class="ri-save-line"></i>

        Simpan User

    </x-button>

</div>

</form>

</x-card>

@endsection