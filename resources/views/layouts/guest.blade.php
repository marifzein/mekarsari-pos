<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>
        <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">
        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans text-gray-900 antialiased bg-slate-100 min-h-screen flex items-center justify-center p-4 sm:p-6">
        
        <!-- Card Container Utama (Max-width xl / 2-Kolom Grid) -->
        <div class="w-full max-w-4xl bg-white rounded-3xl shadow-xl overflow-hidden grid grid-cols-1 md:grid-cols-2 min-h-[500px]">
            
           <!-- Kolom Kiri: Gambar Utuh Tanpa Kepotong -->
            <div class="hidden md:flex items-center justify-center  bg-white">
                <img src="{{ asset('images/toys-login.webp') }}" 
                    alt="Kasir Minimarket Illustration" 
                    class="w-full h-full max-w-[460px] object-contain">
            </div>

            <!-- Kolom Kanan: Form Auth -->
            <div class="p-8 sm:p-10 flex flex-col justify-center">
                <!-- Header / Logo Toko -->
                <div class="mb-6">
                    <a href="/" class="flex items-center gap-3 no-underline">
                        {{-- <div class="p-2.5 bg-emerald-500 text-white rounded-xl shadow-md shadow-emerald-200"> --}}
                        <div >
                            
                            <img 
                                src="{{ asset('images/tatakas-color.png') }}" 
                                alt="TataKas Logo" 
                                class="w-7 h-7 object-contain flex-shrink-0 "
                            >
                        </div>
                        <span class="text-xl font-bold tracking-wider text-gray-800 uppercase">Tata<span class="text-indigo-500">Kas</span></span>
                    </a>
                </div>

                {{ $slot }}
            </div>

        </div>

    </body>
</html>