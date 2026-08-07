@props([

'title'=>'Belum ada data',

'description'=>'',

'icon'=>'ri-inbox-line'

])

<div class="py-16 text-center">

<i

class="{{ $icon }}

text-6xl

text-slate-300"

></i>

<h3

class="mt-5

text-xl

font-semibold

text-slate-700"

>

{{ $title }}

</h3>

@if($description)

<p

class="mt-2

text-slate-500"

>

{{ $description }}

</p>

@endif

<div class="mt-6">

{{ $slot }}

</div>

</div>