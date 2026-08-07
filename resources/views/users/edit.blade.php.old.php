@extends('layouts.app')

@section('title','Edit User')

@section('content')

<div class="max-w-3xl mx-auto">

    <h2 class="text-2xl font-bold mb-6">

        Edit User

    </h2>

    @if ($errors->any())

    <div class="mb-4 bg-red-100 border border-red-300 text-red-700 rounded-xl p-4">

        <ul class="list-disc ml-5">

            @foreach($errors->all() as $error)

                <li>{{ $error }}</li>

            @endforeach

        </ul>

    </div>

    @endif

    @if(session('success'))

    <div class="mb-4 bg-green-100 border border-green-300 text-green-700 rounded-xl p-4">

        {{ session('success') }}

    </div>

    @endif

    <div class="bg-white rounded-2xl shadow-lg p-8">

        <form
            method="POST"
            action="{{ route('users.update',$user) }}"
        >

            @csrf
            @method('PUT')

            <div class="grid md:grid-cols-2 gap-6">

                {{-- Nama --}}

                <div>

                    <label class="block font-semibold mb-2">

                        Nama

                    </label>

                    <input
                        type="text"
                        name="name"
                        value="{{ old('name',$user->name) }}"
                        class="w-full border rounded-xl px-4 py-2 focus:ring-2 focus:ring-indigo-400 outline-none"
                        required
                    >

                </div>

                {{-- Email --}}

                <div>

                    <label class="block font-semibold mb-2">

                        Email

                    </label>

                    <input
                        type="email"
                        name="email"
                        value="{{ old('email',$user->email) }}"
                        class="w-full border rounded-xl px-4 py-2 focus:ring-2 focus:ring-indigo-400 outline-none"
                        required
                    >

                </div>

                {{-- Role --}}

                <div>

                    <label class="block font-semibold mb-2">

                        Role

                    </label>

                    <select
                        name="role"
                        class="w-full border rounded-xl px-4 py-2"
                    >

                        <option
                            value="Admin"
                            {{ $user->role=='Admin' ? 'selected' : '' }}
                        >
                            Admin
                        </option>

                        <option
                            value="Supervisor"
                            {{ $user->role=='Supervisor' ? 'selected' : '' }}
                        >
                            Supervisor
                        </option>

                        <option
                            value="Kasir"
                            {{ $user->role=='Kasir' ? 'selected' : '' }}
                        >
                            Kasir
                        </option>

                    </select>

                </div>

                {{-- Status --}}

                <div>

                    <label class="block font-semibold mb-2">

                        Status

                    </label>

                    <select
                        name="is_active"
                        class="w-full border rounded-xl px-4 py-2"
                    >

                        <option
                            value="1"
                            {{ $user->is_active ? 'selected' : '' }}
                        >
                            Aktif
                        </option>

                        <option
                            value="0"
                            {{ !$user->is_active ? 'selected' : '' }}
                        >
                            Nonaktif
                        </option>

                    </select>

                </div>

            </div>

            <hr class="my-8">

            <div class="flex flex-wrap gap-3">

                <button
                    type="submit"
                    class="bg-indigo-600 hover:bg-indigo-700 text-white px-6 py-2 rounded-xl"
                >

                    Simpan Perubahan

                </button>

                <a
                    href="{{ route('users.index') }}"
                    class="bg-gray-300 hover:bg-gray-400 px-6 py-2 rounded-xl"
                >

                    Batal

                </a>

            </div>

        </form>

        <hr class="my-8">

        <div>

            <h3 class="font-bold text-lg mb-3">

                Reset Password

            </h3>

            <p class="text-gray-500 mb-4">

                Password akan direset menjadi:

                <span class="font-bold">

                    87654321

                </span>

            </p>

            <form
                method="POST"
                action="{{ route('users.reset-password',$user) }}"
            >

                @csrf

                <button
                    class="bg-red-600 hover:bg-red-700 text-white px-6 py-2 rounded-xl"
                    onclick="return confirm('Reset password user ini?')"
                >

                    Reset Password

                </button>

            </form>

        </div>

    </div>

</div>

@endsection