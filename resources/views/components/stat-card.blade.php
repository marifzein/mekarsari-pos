@props([

'title',

'value',

'color'=>'indigo',

'icon'=>null,

'subtitle'=>null

])

@php
    // Mapping warna statis agar dibaca dan di-compile sempurna oleh Tailwind CSS
    $bgColors = [
        'indigo'  => 'bg-indigo-600',
        'blue'    => 'bg-blue-500',
        'orange'  => 'bg-orange-500',
        'purple'  => 'bg-purple-500',
        'pink'    => 'bg-pink-500',
        'red'     => 'bg-red-500',
        // Menggunakan emerald-500 agar warna hijaunya terlihat premium dan modern
        'green'   => 'bg-emerald-500', 
        'emerald' => 'bg-emerald-500',
    ];

    // Ambil kelas bg berdasarkan prop color, default ke indigo-600 jika tidak ditemukan
    $selectedBg = $bgColors[$color] ?? 'bg-indigo-600';
@endphp

<div class="{{ $selectedBg }}  text-white rounded-xl shadow p-5">

    <div class="flex justify-between items-start">

        <div>

            <div class="text-base font-bold opacity-90">

                {{ $title }}

            </div>

            @if($subtitle)

                <div class="text-xs opacity-90 mt-1">

                    {{ $subtitle }}

                </div>

            @endif

        </div>

        @if($icon)

            <i class="{{ $icon }} text-3xl opacity-70"></i>

        @endif

    </div>

    <div class="text-3xl font-bold mt-5">

        {{ $value }}

    </div>

</div>