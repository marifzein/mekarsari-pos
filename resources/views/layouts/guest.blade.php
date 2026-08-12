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
                        <div class="p-2.5 bg-indigo-500 text-white rounded-xl shadow-md shadow-emerald-200">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-6 h-6">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 3h1.386c.51 0 .955.343 1.087.835l.383 1.437M7.5 14.25a3 3 0 0 0-3 3h15.75m-12.75-3h11.218c1.121-2.3 2.1-4.684 2.924-7.138a60.114 60.114 0 0 0-16.536-1.84M7.5 14.25L5.106 5.272M6 20.25a.75.75 0 1 1-1.5 0 .75.75 0 0 1 1.5 0Zm12.75 0a.75.75 0 1 1-1.5 0 .75.75 0 0 1 1.5 0Z" />
                            </svg>
                        </div>
                        {{-- <span class="text-xl font-bold tracking-wider text-gray-800 uppercase">Flow<span class="text-emerald-500">POS</span></span> --}}
                        <span class="text-xl font-bold tracking-wider text-gray-800 uppercase">Tata<span class="text-indigo-500">Kas</span></span>
                    </a>
                </div>

                {{ $slot }}
            </div>

        </div>

    </body>
</html>