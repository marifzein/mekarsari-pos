@extends('layouts.app')

@section('title','Import Produk')

@section('content')

<div class="max-w-3xl mx-auto">

    <x-card>

        <div class="flex items-center justify-between mb-6">

            <h2 class="text-xl font-bold">

                Import Produk Excel

            </h2>

            <a
                href="{{ asset('format-produk.xlsx') }}"
                class="text-indigo-600 hover:underline"
            >

                Download Template

            </a>

        </div>

        @if(session('success'))

            <x-alert>

                {{ session('success') }}

            </x-alert>

        @endif

        @if(session('success'))

            <script>

            Swal.fire({

                icon:'success',

                title:'Import Produk Berhasil',

                text:'{{ session("success") }}',

                timer:1800,

                showConfirmButton:false

            });

            </script>

        @endif

        {{-- jika error --}}
        @if(session('error'))

            <script>

            Swal.fire({

                icon:'error',

                title:'Import Gagal',

                text:'{{ session("error") }}'

            });

            </script>

        @endif


        <form

            action="{{ route('products.import.store') }}"

            method="POST"

            enctype="multipart/form-data"

        >

            @csrf

            <div class="mb-5">

                <label
                    class="block font-semibold mb-2"
                >

                    File Excel

                </label>

                <input

                    type="file"

                    name="file"

                    accept=".xlsx,.xls"

                    class="block w-full rounded-xl border border-slate-300 p-3"

                >

                @error('file')

                    <div class="text-red-500 text-sm mt-1">

                        {{ $message }}

                    </div>

                @enderror

            </div>

            <button
                class="px-5 py-3 rounded-xl bg-indigo-600 text-white hover:bg-indigo-700"
            >

                Upload

            </button>

        </form>

        {{-- summary import --}}
        @if(session('import_result'))

            @php

            $result = session('import_result');

            @endphp

            <div class="mt-8">

                <x-card>

                    <h3
                        class="text-lg font-bold mb-4"
                    >

                        Ringkasan Import

                    </h3>

                    <div
                        class="grid grid-cols-2 gap-5 mb-6"
                    >

                        <div
                            class="rounded-xl bg-green-50 p-5"
                        >

                            <div
                                class="text-sm text-slate-500"
                            >

                                Berhasil

                            </div>

                            <div
                                class="text-3xl font-bold text-green-600"
                            >

                                {{ $result['berhasil'] }}

                            </div>

                        </div>

                        <div
                            class="rounded-xl bg-red-50 p-5"
                        >

                            <div
                                class="text-sm text-slate-500"
                            >

                                Gagal

                            </div>

                            <div
                                class="text-3xl font-bold text-red-600"
                            >

                                {{ count($result['gagal']) }}

                            </div>

                        </div>

                    </div>
                @if(count($result['gagal']))

                    <div
                        class="overflow-auto rounded-xl border"
                        style="max-height:350px"
                    >

                    <table
                        class="min-w-full text-sm"
                    >

                    <thead
                        class="bg-slate-100 sticky top-0"
                    >

                    <tr>

                    <th
                    class="text-left px-4 py-3 w-16"
                    >

                    No

                    </th>

                    <th
                    class="text-left px-4 py-3"
                    >

                    Keterangan

                    </th>

                    </tr>

                    </thead>

                    <tbody>

                    @foreach($result['gagal'] as $item)

                    <tr
                    class="border-t"
                    >

                    <td
                    class="px-4 py-2"
                    >

                    {{ $loop->iteration }}

                    </td>

                    <td
                    class="px-4 py-2 text-red-600"
                    >

                    {!! $item !!}

                    </td>

                    </tr>

                    @endforeach

                    </tbody>

                    </table>

                    </div>

                    @else

                    <div
                    class="rounded-xl bg-green-50 p-4 text-green-700"
                    >

                    Semua produk berhasil diimport.

                    </div>

                    @endif

                    </x-card>

                    </div>

                    @endif
        {{-- summary import end --}}

    </x-card>

</div>

@endsection