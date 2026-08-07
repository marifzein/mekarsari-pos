@props([
    'label' => '',
    'name',
    'value' => '1',
    'checked' => false,
    'required' => false, 
    'icon' => null,
    'width' => 'full',
])

@php
    $inputId = $attributes->get('id', $name);
    $shouldBeChecked = old($name, $checked) ? true : false;
@endphp

<div class="space-y-2">

    
    {{-- <div class="flex items-start gap-3 py-2"> --}}
    <div class="relative group mt-5">
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
                type="checkbox"
                value="{{ $value }}"
                {{ $shouldBeChecked ? 'checked' : '' }}
                {{ $required ? 'required' : '' }}
                {{ $attributes->merge([
                    'class' => '
                        w-7 
                        h-7 
                        
                        text-indigo-600 
                        border-slate-300 
                        rounded-lg
                        bg-white
                        focus:ring-4 
                        focus:ring-indigo-100 
                        focus:border-indigo-500
                        transition-all 
                        duration-200 
                        cursor-pointer
                    '
                ]) }}
            >
        

        @if($label)
            <label
                for="{{ $inputId }}"
                class="text-sm font-semibold text-slate-700 select-none cursor-pointer"
            >
                {{ $label }}
                @if($required)
                    <span class="text-red-500">*</span>
                @endif
            </label>
        @endif

    </div>

    @error($name)
        <p class="text-sm text-red-500 pl-8">
            {{ $message }}
        </p>
    @enderror

</div>