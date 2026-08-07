@props([

    'label' => '',

    'name',

    'required' => false,

    'icon' => null,

])

<div class="space-y-2">

    @if($label)

        <label
            for="{{ $name }}"
            class="block text-sm font-semibold text-slate-700"
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
                transition"
            ></i>

        @endif

        <select
            
            id="{{ $name }}"

            name="{{ $name }}"

            {{ $required ? 'required' : '' }}

            {{ $attributes->merge([

                'class'=>

                'w-full
                
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

                pr-10

                py-3

                focus:border-indigo-500

                focus:ring-4

                focus:ring-indigo-100

                outline-none

                transition'

            ]) }}

        >

            {{ $slot }}

        </select>

        {{-- <i class="ri-arrow-down-s-line
              absolute
              right-4
              top-1/2
              -translate-y-1/2
              text-slate-400
              pointer-events-none">
        </i> --}}

    </div>

    @error($name)

        <p class="text-sm text-red-500">

            {{ $message }}

        </p>

    @enderror

</div>