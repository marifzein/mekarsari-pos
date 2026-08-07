@extends('layouts.app')

@section('title','History Stock Opname')

@section('content')

<div class="flex justify-between items-center mb-6">

    <h2 class="text-2xl font-bold">

        History Stock Opname

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

@if(session('success'))

<div class="mb-4 p-3 rounded bg-green-100 text-green-700">

{{ session('success') }}

</div>

@endif

@if(session('warning'))

<div class="mb-4 p-3 rounded bg-yellow-100 text-yellow-700">

{{ session('warning') }}

</div>

@endif

<div class="bg-white rounded-xl shadow overflow-hidden">

<table class="w-full">

<thead class="bg-gray-100">

<tr>

<th class="p-3 text-left">

No SO

</th>

<th>

Tanggal

</th>

<th>

Status

</th>

<th>

Item

</th>

<th>

Operator

</th>

<th>

Aksi

</th>

</tr>

</thead>

<tbody>

@forelse($opnames as $so)

<tr class="border-t hover:bg-gray-50">

<td class="p-3">

{{ $so->opname_no }}

</td>

<td>

{{ \Carbon\Carbon::parse($so->opname_date)->format('d-m-Y H:i') }}

</td>

<td>

@if($so->status=='OPEN')

<span
class="px-2 py-1 bg-yellow-100 text-yellow-700 rounded"
>

OPEN

</span>

@else

<span
class="px-2 py-1 bg-green-100 text-green-700 rounded"
>

POSTED

</span>

@endif

</td>

<td class="text-center">

{{ $so->details_count }}

</td>

<td>

{{ $so->user_name }}

</td>

<td>

<a
href="/stock-opname/{{ $so->id }}"
class="text-indigo-600 hover:underline"
>

Detail

</a>

</td>

</tr>

@empty

<tr>

<td
colspan="6"
class="text-center py-8 text-gray-500"
>

Belum ada data Stock Opname.

</td>

</tr>

@endforelse

</tbody>

</table>

<div class="p-4">

{{ $opnames->links() }}

</div>

</div>

@endsection