@props([

    'color' => 'primary',

    'size' => 'md',

    'type' => 'button',

    'full' => false,

])

@php

$colors = [

    'primary' =>
    'bg-indigo-600 hover:bg-indigo-700 text-white',

    'secondary' =>
    'border border-slate-300 bg-white text-slate-700 hover:bg-slate-50 hover:border-slate-400',

    'blue' =>
    'bg-blue-500 hover:bg-blue-700 text-white',

    'green' =>
    'bg-emerald-600 hover:bg-emerald-700 text-white',

    'red' =>
    'bg-red-600 hover:bg-red-700 text-white',

    'warning' =>
    'bg-red-500 hover:bg-red-600 text-white',

    'gray' =>
    'bg-slate-100 hover:bg-slate-200 text-slate-700',

    'orange' =>
    'bg-orange-500 hover:bg-orange-600 text-white',

    'purple' =>
    'bg-purple-500 hover:bg-purple-600 text-white',


];

$sizes = [

    'xs' => 'px-2.5 py-2 text-sm',
    // 'xs' => 'w-9 h-9 p-0',
    'sm' => 'px-3 py-2 text-sm',
    'md' => 'px-4 py-2',
    'lg' => 'px-6 py-3 text-lg',

];

@endphp
{{-- items-center justify-center  --}}
<button

    type="{{ $type }}"

    {{ $attributes->merge([

        'class'=>

        'inline-flex gap-2
        
        
        rounded-xl font-medium transition duration-200
        focus:outline-none focus:ring-2
        focus:ring-indigo-400 disabled:opacity-50 disabled:cursor-not-allowed
        '

        .($full ? 'min-w-40 ' : '')

        .$colors[$color].' '

        .$sizes[$size]

    ]) }}

>

    {{ $slot }}

</button> 