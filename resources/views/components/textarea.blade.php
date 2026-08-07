@props([

'label' => '',

'name',

'rows' => 4,

'placeholder' => '',

'value' => '',

])

<div class="space-y-2">

    @if($label)

        <label
            for="{{ $name }}"
            class="block text-sm font-semibold text-slate-700"
        >
            {{ $label }}
        </label>

    @endif

    <textarea

        id="{{ $name }}"

        name="{{ $name }}"

        rows="{{ $rows }}"

        placeholder="{{ $placeholder }}"

        {{ $attributes->merge([

            'class'=>

            'w-full

            rounded-xl

            border

            border-slate-300

            hover:border-slate-400

            px-4

            py-3

            focus:border-indigo-500

            focus:ring-4

            focus:ring-indigo-100

            outline-none

            transition'

        ]) }}

    >{{ old($name, $value ? $value : $slot) }}</textarea>
    {{-- >{{ old($name, $slot) }}</textarea> --}}

    @error($name)

        <p class="text-sm text-red-500">

            {{ $message }}

        </p>

    @enderror

</div>