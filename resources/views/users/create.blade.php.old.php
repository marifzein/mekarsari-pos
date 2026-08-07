@extends('layouts.app')

@section('title','Tambah User')

@section('content')

<h2 class="text-2xl font-bold mb-6">

    Tambah User

</h2>

@if ($errors->any())

<div class="mb-4 bg-red-100 border border-red-300 text-red-700 p-4 rounded">

    <ul class="list-disc ml-5">

        @foreach($errors->all() as $error)

        <li>{{ $error }}</li>

        @endforeach

    </ul>

</div>

@endif

<div class="bg-white rounded-xl shadow p-6 max-w-2xl">

<form
    method="POST"
    action="{{ route('users.store') }}"
>

@csrf

<div class="mb-4">

    <label class="block mb-2 font-semibold">

        Nama

    </label>

    <input
        type="text"
        name="name"
        value="{{ old('name') }}"
        class="w-full border rounded-lg px-4 py-2 focus:ring focus:ring-indigo-200"
        required
    >

</div>

<div class="mb-4">

    <label class="block mb-2 font-semibold">

        Email

    </label>

    <input
        type="email"
        name="email"
        value="{{ old('email') }}"
        class="w-full border rounded-lg px-4 py-2 focus:ring focus:ring-indigo-200"
        required
    >

</div>

<div class="mb-4">

    <label class="block mb-2 font-semibold">

        Password

    </label>

    <input
        type="password"
        name="password"
        class="w-full border rounded-lg px-4 py-2 focus:ring focus:ring-indigo-200"
        required
    >

</div>

<div class="mb-6">

    <label class="block mb-2 font-semibold">

        Role

    </label>

    <select
        name="role"
        class="w-full border rounded-lg px-4 py-2"
        required
    >

        <option value="">

            -- Pilih Role --

        </option>

        <option
            value="Admin"
            {{ old('role')=='Admin' ? 'selected' : '' }}
        >

            Admin

        </option>

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

    </select>

</div>

<div class="flex gap-3">

    <button
        type="submit"
        class="bg-indigo-600 hover:bg-indigo-700 text-white px-6 py-2 rounded-lg"
    >

        Simpan

    </button>

    <a
        href="/users"
        class="bg-gray-300 hover:bg-gray-400 px-6 py-2 rounded-lg"
    >

        Batal

    </a>

</div>

</form>

</div>

@endsection