@extends('layouts.app')

@section('title','Stock Opname')

@section('content')

@if(session('success'))
<div class="mb-4 p-3 rounded bg-green-100 text-green-700">
    {{ session('success') }}
</div>
@endif

<div class="flex justify-between items-center mb-6">

    <h2 class="text-2xl font-bold">
        Stock Opname
    </h2>

    <form
        method="POST"
        action="/stock-opname/start"
    >
        @csrf

        <button
            class="bg-indigo-600 hover:bg-indigo-700 text-white px-5 py-2 rounded-lg"
        >
            + Mulai Stock Opname
        </button>

    </form>

</div>


<div class="bg-white rounded-xl shadow">

    <table class="w-full">

        <thead class="bg-gray-100">

            <tr>

                <th class="p-3 text-left">
                    No SO
                </th>

                <th class="text-left">
                    Tanggal
                </th>

                <th class="text-center">
                    Status
                </th>

                <th class="text-center">
                    Item
                </th>

                <th class="text-center">
                    Aksi
                </th>

            </tr>

        </thead>

        <tbody>

        @forelse($opnames as $opname)

            <tr class="border-t">

                <td class="p-3">

                    {{ $opname->opname_no }}

                </td>

                <td>

                    {{ \Carbon\Carbon::parse($opname->opname_date)->format('d-m-Y') }}

                </td>

                <td class="text-center">

                    @if($opname->status=='OPEN')

                        <span class="text-orange-600 font-semibold">
                            OPEN
                        </span>

                    @else

                        <span class="text-green-600 font-semibold">
                            POSTED
                        </span>

                    @endif

                </td>

                <td class="text-center">

                    {{ $opname->details_count }}

                </td>

                <td class="text-center">

                    <a
                        href="/stock-opname/{{ $opname->id }}"
                        class="text-indigo-600 hover:underline"
                    >

                        {{ $opname->status=='OPEN' ? 'Lanjutkan' : 'Detail' }}

                    </a>

                </td>

            </tr>

        @empty

            <tr>

                <td
                    colspan="5"
                    class="text-center py-10 text-gray-500"
                >

                    Belum ada Stock Opname.

                </td>

            </tr>

        @endforelse

        </tbody>

    </table>

</div>

@endsection