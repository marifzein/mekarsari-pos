@extends('layouts.app')

@section('title','Master Supplier')

@section('content')

<x-page-header

title="Master Supplier"

subtitle="Kelola data supplier"

>

<x-slot:action>

<a href="{{ route('suppliers.create') }}">

<x-button color="primary">

<i class="ri-add-line"></i>

Tambah Supplier

</x-button>

</a>

</x-slot:action>

</x-page-header>

<x-card>

<form
method="GET"
class="mb-6"
>

<div class="flex gap-3">

<x-input

name="search"

placeholder="Cari supplier..."

:value="request('search')"

/>

<x-button>

Cari

</x-button>

</div>

</form>

<x-table>

<x-table-header>

<tr>

<x-table-head class="text-left">Kode</x-table-head>

<x-table-head class="text-left">Nama</x-table-head>

<x-table-head class="text-left">PIC</x-table-head>

<x-table-head class="text-left">Telepon</x-table-head>

<x-table-head class="text-center">Status</x-table-head>

<x-table-head class="text-center">

Aksi

</x-table-head>

</tr>

</x-table-header>

<tbody>

@forelse($suppliers as $supplier)

<tr>

<x-table-cell>

{{ $supplier->kode }}

</x-table-cell>

<x-table-cell>

{{ $supplier->nama }}

</x-table-cell>

<x-table-cell>

{{ $supplier->pic }}

</x-table-cell>

<x-table-cell>

{{ $supplier->telepon }}

</x-table-cell>

<x-table-cell>

@if($supplier->is_active)

<x-badge color="green">

Aktif

</x-badge>

@else

<x-badge color="red">

Nonaktif

</x-badge>

@endif

</x-table-cell>

<x-table-cell>

<div class="flex justify-center">

<a href="{{ route('suppliers.edit',$supplier) }}">

<x-button
size="sm"
color="blue"
>

<i class="ri-edit-line"></i>

</x-button>

</a>

</div>

</x-table-cell>

</tr>

@empty

<tr>

<td colspan="6">

<x-empty-state

icon="ri-truck-line"

title="Belum ada supplier"

description="Klik Tambah Supplier."

/>

</td>

</tr>

@endforelse

</tbody>

</x-table>

<div class="mt-6">

{{ $suppliers->links() }}

</div>

</x-card>

@endsection