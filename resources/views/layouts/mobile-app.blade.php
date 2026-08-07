<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'POS Kasir Mobile')</title>
    
    <!-- CDN Assets dari file asli anda -->
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <link href="https://cdn.jsdelivr.net/npm/remixicon@4.2.0/fonts/remixicon.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/@alpinejs/focus@3.x.x/dist/cdn.min.js"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <style>
        [x-cloak] { display: none !important; }
        input::-webkit-outer-spin-button,
        input::-webkit-inner-spin-button { -webkit-appearance: none; margin: 0; }
        input[type=number] { -moz-appearance: textfield; }
        body { background-color: #f8fafc; font-family: system-ui, -apple-system, sans-serif; }
    </style>
    @stack('styles')
</head>

<body x-data="{ showMenu: false }">

    <!-- ========================================================== -->
    <!-- GLOBAL SIDEBAR HAMBURGER / DRAWER MOBILE -->
    <!-- ========================================================== -->
    <div x-show="showMenu" class="fixed inset-0 z-50 flex" x-cloak>
        <div x-show="showMenu" x-transition:enter="transition-opacity ease-linear duration-200" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="transition-opacity ease-linear duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" @click="showMenu = false" class="fixed inset-0 bg-black/50 backdrop-blur-xs"></div>

        <div x-show="showMenu" x-transition:enter="transition ease-in-out duration-200 transform" x-transition:enter-start="-translate-x-full" x-transition:enter-end="translate-x-0" x-transition:leave="transition ease-in-out duration-200 transform" x-transition:leave-start="translate-x-0" x-transition:leave-end="-translate-x-full" class="relative flex flex-col w-72 max-w-xs bg-slate-900 text-slate-200 h-full p-5 shadow-2xl">
            
            <div class="flex justify-between items-center pb-4 border-b border-slate-800">
                <div class="flex items-center gap-2">
                    <i class="ri-store-2-fill text-indigo-400 text-xl"></i>
                    <span class="font-black text-sm tracking-wider text-white">MENU KASIR</span>
                </div>
                <button @click="showMenu = false" class="w-8 h-8 rounded-lg bg-slate-800 flex items-center justify-center text-slate-400 active:bg-slate-700">
                    <i class="ri-close-line text-lg"></i>
                </button>
            </div>

            <!-- List Navigasi Dinamis -->
            <div class="mt-4 flex-1 overflow-y-auto space-y-4">
                <div>
                    <span class="block text-[10px] uppercase font-bold tracking-widest text-slate-500 mb-1">Penjualan</span>
                    <div class="space-y-1">
                        <a href="{{ route('pos.mobile') }}" class="flex items-center gap-3 px-3 py-2 {{ Request::is('pos/mobile') ? 'bg-indigo-600 text-white' : 'hover:bg-slate-800 text-slate-400' }} text-xs font-bold rounded-xl"><i class="ri-computer-line text-base"></i> POS Utama</a>
                        
                        <a href="{{ route('pos.close-shift') }}" class="flex items-center gap-3 px-3 py-2 {{ Request::is('*close-shift*') ? 'bg-indigo-600 text-white' : 'hover:bg-slate-800 text-slate-400' }} text-xs font-semibold rounded-xl"><i class="ri-lock-password-line text-base"></i> Tutup Shift</a>

                        <a href="/transactions" class="flex items-center gap-3 px-3 py-2 {{ Request::is('*transactions*') ? 'bg-indigo-600 text-white' : 'hover:bg-slate-800 text-slate-400' }} text-xs font-semibold rounded-xl"><i class="ri-history-line text-base"></i> Riwayat Transaksi</a>
                    </div>
                </div>

                <div>
                    <span class="block text-[10px] uppercase font-bold tracking-widest text-slate-500 mb-1">Master Data</span>
                    <div class="space-y-1">
                        <a href="{{ route('customers.index') }}" class="flex items-center gap-3 px-3 py-2 {{ Request::is('*customers*') ? 'bg-indigo-600 text-white' : 'hover:bg-slate-800 text-slate-400' }} text-xs font-semibold rounded-xl"><i class="ri-user-shared-line text-base"></i> Pelanggan</a>
                    </div>
                </div>

                <div>
                    <span class="block text-[10px] uppercase font-bold tracking-widest text-slate-500 mb-1">Inventory</span>
                    <div class="space-y-1">
                        <a href="{{ route('stock-cards.index') }}" class="flex items-center gap-3 px-3 py-2 {{ Request::is('*stock-cards*') ? 'bg-indigo-600 text-white' : 'hover:bg-slate-800 text-slate-400' }} text-xs font-semibold rounded-xl"><i class="ri-file-list-3-line text-base"></i> Kartu Stok</a>
                    </div>
                </div>

                <div>
                    <span class="block text-[10px] uppercase font-bold tracking-widest text-slate-500 mb-1">Laporan</span>
                    <div class="space-y-1">
                        <a href="{{ route('laporan.penjualan-kasir') }}" class="flex items-center gap-3 px-3 py-2 {{ Request::is('*penjualan-kasir*') ? 'bg-indigo-600 text-white' : 'hover:bg-slate-800 text-slate-400' }} text-xs font-semibold rounded-xl"><i class="ri-pie-chart-line text-base"></i> Penjualan Kasir</a>
                    </div>
                </div>
            </div>

            

            <!-- FOOTER PROFIL & TOMBOL LOGOUT -->
            <div class="pt-4 mt-auto border-t border-slate-800 flex items-center justify-between flex-shrink-0">
                <div class="flex items-center gap-2.5 min-w-0">
                    <div class="w-8 h-8 rounded-full bg-indigo-500 flex items-center justify-center font-bold text-white text-xs flex-shrink-0">
                        {{ strtoupper(substr(Auth::user()->name ?? 'A', 0, 1)) }}
                    </div>
                    <div class="min-w-0">
                        <p class="text-xs font-bold text-white leading-none truncate">{{ Auth::user()->name ?? 'Admin Kasir' }}</p>
                        <span class="text-[10px] text-slate-500">Mobile Online</span>
                    </div>
                </div>

                <!-- Form & Tombol Logout -->
                <form id="mobile-logout-form" action="{{ route('logout') }}" method="POST" class="inline flex-shrink-0">
                    @csrf
                    <button type="button" @click="confirmLogout()" class="px-2.5 py-1.5 rounded-xl bg-rose-500/10 hover:bg-rose-500/20 active:bg-rose-500/30 text-rose-400 flex items-center gap-1.5 transition active:scale-95 text-xs font-bold" title="Logout Sesi">
                        <i class="ri-logout-box-r-line text-sm"></i>
                        <span>LOGOUT</span>
                    </button>
                </form>
            </div>

            

        </div>
    </div>

    <!-- ========================================================== -->
    <!-- KONTEN MASTER LAYOUT UTAMA -->
    <!-- ========================================================== -->
    <div class="flex flex-col min-h-screen px-3 py-3 select-none">
        
        <!-- GLOBAL TOPBAR MINIMALIS -->
        <div class="flex items-center justify-between bg-white p-3 rounded-2xl shadow-xs border border-slate-100 mb-3">
            <div class="flex items-center gap-2.5">
                <button @click="showMenu = true" type="button" class="w-9 h-9 bg-slate-100 active:bg-slate-200 rounded-xl flex items-center justify-center text-slate-700 active:scale-95 transition">
                    <i class="ri-menu-2-line text-xl"></i>
                </button>
                <div>
                    <h1 class="text-sm font-black text-slate-800 leading-none">@yield('title', 'POS KASIR')</h1>
                    <span class="text-[11px] text-slate-400 font-mono">@yield('page_subtitle')</span>
                </div>
            </div>
            
            
        </div>

        <!-- SLOT UTAMA ISI KONTEN HALAMAN -->
        @yield('content')

    </div>

    

    <!-- Script Konfirmasi Logout SweetAlert2 -->
    <script>
        function confirmLogout() {
            Swal.fire({
                title: 'Keluar Sesi Kasir?',
                text: 'Pastikan shift kerja anda sudah ditutup jika sudah selesai shift',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#ef4444',
                cancelButtonColor: '#64748b',
                confirmButtonText: 'Ya, Logout!',
                cancelButtonText: 'Batal',
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('mobile-logout-form').submit();
                }
            });
        }
    </script>

    @stack('scripts')
</body>
</html>