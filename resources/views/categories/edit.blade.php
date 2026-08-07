@extends('layouts.app')

@section('title','Edit Kategori')

@section('content')

<x-page-header

title="Edit Kategori"

subtitle="Perbarui kategori"

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

<form

method="POST"

action="{{ route('categories.update',$category) }}"

>

@csrf

@method('PUT')

<x-input

label="Nama Kategori"

name="name"

:value="$category->name"

icon="ri-price-tag-3-line"

required

/>

<x-textarea

label="Deskripsi"

name="description"

>{{ $category->description }}</x-textarea>

<x-select

label="Status"

name="is_active"

>

<option value="1" @selected($category->is_active)>

Aktif

</option>

<option value="0" @selected(!$category->is_active)>

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
  type="submit"
  color="primary">

<i class="ri-save-line"></i>

Update

</x-button>

</div>

</form>

</x-card>

@endsection