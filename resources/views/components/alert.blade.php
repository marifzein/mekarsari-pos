@props([

'type'=>'success'

])

@php

$styles=[

'success'=>'bg-emerald-50 border-emerald-200 text-emerald-700',

'error'=>'bg-red-50 border-red-200 text-red-700',

'warning'=>'bg-amber-50 border-amber-200 text-amber-700',

'info'=>'bg-sky-50 border-sky-200 text-sky-700',

];

@endphp

<div

{{ $attributes->merge([

'class'=>

'rounded-xl

border

px-5

py-4

mb-5

'.$styles[$type]

]) }}

>

{{ $slot }}

</div>