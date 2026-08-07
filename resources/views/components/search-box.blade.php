@props([
    'name' => 'search',
    'placeholder' => 'Cari...',
    'value' => '',
])

<div class="relative w-80">

    <i class="ri-search-line
        absolute
        left-4
        top-1/2
        -translate-y-1/2
        text-slate-400">
    </i>

    <input

        type="text"

        name="{{ $name }}"

        value="{{ $value }}"

        placeholder="{{ $placeholder }}"

        {{ $attributes->merge([

            'class'=>

            'w-full

            rounded-xl

            border

            border-slate-300

            pl-11

            pr-4

            py-3

            focus:border-indigo-500

            focus:ring-4

            focus:ring-indigo-100'

        ]) }}

    >

</div>