@props([
    'padding' => true,
    'shadow' => true,
])

<div
    {{ $attributes->class([

        'bg-white',

        'rounded-2xl',

        'border border-slate-200',

        'shadow-sm' => $shadow,

        'p-6' => $padding,

    ]) }}
>

    {{ $slot }}

</div>