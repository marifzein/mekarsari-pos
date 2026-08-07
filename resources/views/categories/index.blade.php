@extends('layouts.app')

@section('title','Master Kategori')

@section('content')

<x-page-header
    title="Master Kategori"
    subtitle="Kelola kategori produk"
>

<x-slot:action>

<a href="{{ route('categories.create') }}">

<x-button color="primary">

<i class="ri-add-line"></i>

Tambah Kategori

</x-button>

</a>

</x-slot:action>

</x-page-header>

<x-card>

<form method="GET" class="mb-6">

<div class="relative w-80">

<i class="ri-search-line absolute left-4 top-1/2 -translate-y-1/2 text-slate-400"></i>

<input

type="text"

name="search"

value="{{ request('search') }}"

placeholder="Cari kategori..."

class="w-full rounded-xl border border-slate-300 pl-11 pr-4 py-3
focus:border-indigo-500 focus:ring-4 focus:ring-indigo-100">

</div>

</form>

<x-table>

<x-table-header>

<tr>

<x-table-head class="text-left">Nama</x-table-head>

<x-table-head class="text-center">Status</x-table-head>

<x-table-head class="text-center">Aksi</x-table-head>

</tr>

</x-table-header>

<tbody>

@forelse($categories as $category)

<tr>

<x-table-cell>

{{ $category->name }}

</x-table-cell>

<x-table-cell class="text-center">

@if($category->is_active)

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

<a href="{{ route('categories.edit',$category) }}">

<x-button color="blue" size="sm">

<i class="ri-edit-line"></i>

</x-button>

</a>

</div>

</x-table-cell>

</tr>

@empty

<tr>

<td colspan="3">

<x-empty-state

icon="ri-price-tag-3-line"

title="Belum ada kategori"

description="Klik Tambah Kategori."

/>

</td>

</tr>

@endforelse

</tbody>

</x-table>

<div class="mt-6">

{{ $categories->links() }}

</div>

</x-card>

@endsection