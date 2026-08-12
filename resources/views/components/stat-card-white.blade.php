@props([
    'title' => '',
    'value' => '0',
    'subtitle' => '',
    'icon' => 'ri-line-chart-line',
    'color' => 'dark' // Default untuk icon background
])

@php
    // Mapping warna background & shadow berdasarkan prop 'color'
    $colorClasses = [
        'dark'   => 'bg-slate-800 text-white shadow-slate-300',
        'black'  => 'bg-gray-900 text-white shadow-gray-400',
        'blue'   => 'bg-blue-600 text-white shadow-blue-200',
        'green'  => 'bg-emerald-600 text-white shadow-emerald-200',
        'orange' => 'bg-orange-500 text-white shadow-orange-200',
        'purple' => 'bg-purple-600 text-white shadow-purple-200',
        'indigo' => 'bg-indigo-600 text-white shadow-indigo-200',
        'red'    => 'bg-red-600 text-white shadow-red-200',
    ];

    // Ambil class sesuai prop $color, jika pilihan tidak ada di array gunakan 'dark'
    $selectedColor = $colorClasses[$color] ?? $colorClasses['dark'];
@endphp

<div class="bg-white rounded-2xl p-5 drop-shadow-lg border border-slate-200 flex flex-col justify-between relative">
    <div class="flex justify-between items-start">
        <div>
            <p class="text-sm font-extrabold text-slate-500 tracking-wider">
                {{ $title }}
            </p>
            @if($subtitle)

                <div class="text-xs opacity-90 mt-1">

                    {{ $subtitle }}

                </div>

            @endif
            {{-- <span class="text-sm">{{ $subtitle }}</span> --}}
            {{-- <h3 class="text-2xl font-bold text-slate-900 mt-1">
                {{ $value }}
            </h3> --}}
        </div>

        <!-- Container Icon Hitam Bergaya Floating/Elevated -->
        <div class="w-12 h-12 {{ $selectedColor }} text-white rounded-xl flex items-center justify-center shadow-lg shadow-slate-300">
            <i class="{{ $icon }} text-xl"></i>
        </div>
    </div>

    @if($subtitle)
        <div class="mt-4 pt-3 border-t border-slate-200 text-xs text-slate-500 flex items-center gap-1">
            {{-- <span>{{ $subtitle }}</span> --}}
            <h3 class="text-xl font-bold text-slate-500 mt-5">
                {{ $value }}
            </h3>
        </div>
    @endif
</div>