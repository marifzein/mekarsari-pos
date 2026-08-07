@extends('layouts.app')

@section('title','Tambah Kategori')

@section('content')

<x-page-header

title="Tambah Kategori"

subtitle="Buat kategori produk baru"

>

<x-slot:action>

<a href="{{ route('categories.index') }}">

<x-button color="gray">

<i class="ri-arrow-left-line"></i>

Kembali

</x-button>

</a>

</x-slot:action>

</x-page-header>

<x-card>

<form method="POST" action="{{ route('categories.store') }}">
@csrf

<x-input

label="Nama Kategori"

name="name"

icon="ri-price-tag-3-line"

required

/>

<x-textarea

label="Deskripsi"

name="description"

/>

<x-select

label="Status"

name="is_active"

>

<option value="1">

Aktif

</option>

<option value="0">

Nonaktif

</option>

</x-select>

<div class="flex justify-end gap-3 mt-8">

<a href="{{ route('categories.index') }}">

<x-button color="gray">

Batal

</x-button>

</a>

<x-button 
  color="primary"
  type="submit">

  <i class="ri-save-line"></i>

  Simpan

</x-button>


</div>

</form>

</x-card>

@endsection