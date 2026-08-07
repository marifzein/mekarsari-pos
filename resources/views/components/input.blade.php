@props([

    'label' => '',

    'name',

    'type' => 'text',

    'value' => '',

    'placeholder' => '',

    'required' => false,

    'icon' => null,

    'width' => 'full',

])

@php
    $inputId = $attributes->get('id', $name);
@endphp

<div class="space-y-2">

    @if($label)

        <label
            for="{{ $inputId }}"
            class="block text-sm font-semibold text-slate-700 transition-colors duration-200 peer-focus-within:text-indigo-600"
        >

            {{ $label }}

            @if($required)

                <span class="text-red-500">*</span>

            @endif

        </label>

    @endif

    <div class="relative group">

        @if($icon)

            <i
                class="{{ $icon }}
                absolute
                left-4
                top-1/2
                -translate-y-1/2
                text-slate-400
                group-focus-within:text-indigo-600
                transition-all
                duration-200
                text-lg"
            ></i>

        @endif

        <input

            id="{{ $inputId }}"

            name="{{ $name }}"

            type="{{ $type }}"

            value="{{ old($name,$value) }}"

            placeholder="{{ $placeholder }}"

            {{ $required ? 'required' : '' }}

            {{ $attributes->merge([

                'class'=>

                ($width == 'full' ? 'w-full ' : $width . ' ')

                .

                '

                rounded-xl

                border

                border-slate-300

                hover:border-slate-400

                bg-white

                '

                .

                ($icon ? 'pl-12 ' : 'pl-4 ')

                .

                '

                pr-4

                py-3

                text-slate-700

                placeholder:text-slate-400

                focus:border-indigo-500

                focus:ring-4

                focus:ring-indigo-100

                outline-none

                transition-all

                duration-200'

            ]) }}

        >

    </div>

    @error($name)

        <p class="text-sm text-red-500">

            {{ $message }}

        </p>

    @enderror

</div>