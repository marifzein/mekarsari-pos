@props([

    'color' => 'gray',

    'size' => 'md'

])

@php

$colors = [

    'gray' => 'bg-slate-100 text-slate-700',

    'red' => 'bg-red-100 text-red-700',

    'green' => 'bg-emerald-100 text-emerald-700',

    'blue' => 'bg-sky-100 text-sky-700',

    'yellow' => 'bg-amber-100 text-amber-700',

    'purple' => 'bg-violet-100 text-violet-700',

    'indigo' => 'bg-indigo-100 text-indigo-700',

    'orange' => 'bg-orange-100 text-orange-700',

];

$sizes = [

    'sm' => 'px-2 py-0.5 text-xs',

    'md' => 'px-3 py-1 text-sm',

];

@endphp

<span

    {{ $attributes->merge([

        'class'=>

        'inline-flex

        items-center

        rounded-full

        font-medium

        whitespace-nowrap

        '

        .$colors[$color].' '

        .$sizes[$size]

    ]) }}

>

    {{ $slot }}

</span>