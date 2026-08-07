@extends('layouts.app')

@section('title','Manajemen User')

@section('content')

@if(session('success'))

<x-alert>

    {{ session('success') }}

</x-alert>

@endif

<div class="flex justify-between items-center mb-6">

    <h2 class="text-2xl font-bold">

        Manajemen User

    </h2>

    <a
        href="/users/create"
        class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-lg"
    >

        + Tambah User

    </a>

</div>

<div class="bg-white rounded-xl shadow">

    <table class="w-full">

        <thead class="bg-gray-100">

            <tr>

                <th class="p-3 text-left">

                    Nama

                </th>

                <th class="p-3 text-left">

                    Email

                </th>

                <th class="p-3 text-center">

                    Role

                </th>

                <th class="p-3 text-center">

                    Dibuat

                </th>

                <th class="p-3 text-center">

                    Status

                </th>

                <th class="p-3 text-center">

                    Aksi

                </th>

            </tr>

        </thead>

        <tbody>

        @forelse($users as $user)

        <tr class="border-t hover:bg-gray-50">

            <td class="p-3">

                {{ $user->name }}

            </td>

            <td class="p-3">

                {{ $user->email }}

            </td>

            <td class="p-3 text-center">

                @if($user->role=='Admin')

                    <span class="bg-red-100 text-red-700 px-3 py-1 rounded-full text-sm">

                        Admin

                    </span>

                @elseif($user->role=='Supervisor')

                    <span class="bg-yellow-100 text-yellow-700 px-3 py-1 rounded-full text-sm">

                        Supervisor

                    </span>

                @else

                    <span class="bg-green-100 text-green-700 px-3 py-1 rounded-full text-sm">

                        Kasir

                    </span>

                @endif

            </td>

            <td class="p-3 text-center">

                {{ $user->created_at->format('d-m-Y') }}

            </td>

            {{-- status --}}
            <td class="p-3 text-center">
            @if($user->is_active)

              <span class="bg-green-100 text-green-700 px-3 py-1 rounded-full text-xs font-semibold">

              Aktif

              </span>

            @else

              <span class="bg-gray-200 text-gray-700 px-3 py-1 rounded-full text-xs font-semibold">

              Nonaktif

              </span>

            @endif
            </td>
            {{-- status end --}}

            <td class="p-3 text-center">

                <a
                    href="/users/{{ $user->id }}/edit"
                    class="bg-blue-600 hover:bg-blue-700 text-white px-3 py-1 rounded"
                >

                    Edit

                </a>

            </td>

        </tr>

        @empty

        <tr>

            <td
                colspan="5"
                class="text-center text-gray-500 py-6"
            >

                Belum ada user.

            </td>

        </tr>

        @endforelse

        </tbody>

    </table>

</div>

<div class="mt-6">

    {{ $users->links() }}

</div>

@endsection