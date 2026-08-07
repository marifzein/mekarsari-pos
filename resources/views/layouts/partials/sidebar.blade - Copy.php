{{-- <aside
class="w-64 bg-white border-r border-zinc-100 shadow-lg z-30 flex flex-col"> --}}
<aside 
    :class="sidebarOpen ? 'w-64' : 'w-20'" 
    class="shadow-lg z-30 flex flex-col transition-all duration-300 ease-in-out overflow-y-auto overflow-x-hidden no-scrollbar">

    {{-- Logo --}}
    {{-- <div class="px-6 py-6 "> --}}
    <div class="px-6 py-6 border-b border-slate-800/20">

        <div class="flex items-center gap-3">

            <div
                class="w-11 h-11 rounded-xl
                bg-indigo-600
                text-white
                flex items-center justify-center">

                <i class="ri-shopping-cart-2-line text-2xl"></i>

            </div>

            {{-- <div> --}}
            <div x-show="sidebarOpen" x-transition:enter="transition opacity duration-200" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100">    
                <h1 class="font-bold text-lg">

                    POS Minimarket

                </h1>

                <p class="text-xs text-slate-500">

                    Sistem Penjualan

                </p>

            </div>

        </div>

    </div>

    {{-- Menu --}}
    {{-- <nav class="flex-1 py-5 overflow-hidden"> --}}
    <nav class="flex-1 py-5 overflow-y-auto no-scrollbar">    

        <!-- TOMBOL HAMBURGER -->
        {{-- <button @click="sidebarOpen = !sidebarOpen" class="text-slate-500 hover:text-indigo-600 focus:outline-none p-2 rounded-xl hover:bg-slate-400 transition">
            <i class="ri-menu-line text-2xl"></i>
        </button> --}}

        {{-- Dashboard --}}
        {{-- <a
            class="
            mx-3 mb-1 flex items-center gap-3 rounded-xl px-4 py-3 transition
            submenu {{ request()->is('dashboard*') ? 'submenu-active' : '' }}"
            href="/dashboard">
            <i class="ri-home-5-line text-lg"></i>
            Dashboard
        </a> --}}
        <a href="/dashboard"
        class="menu-parent menu-group w-full flex items-center gap-3 px-4 py-3
        {{ request()->is('dashboard*') ? 'submenu-active' : '' }}">
            <i class="ri-home-5-line"></i>
            <span x-show="sidebarOpen" x-transition:enter="transition opacity duration-200">Dashboard</span>
        </a>

        {{-- ===================== --}}
        {{-- PENJUALAN --}}
        {{-- ===================== --}}

        <button
            type="button"
            onclick="toggleMenu('kasir')"
            class="menu-parent menu-group w-full px-4 py-3 flex items-center justify-between">

            <div class="flex items-center gap-3">

                <i class="ri-shopping-cart-2-line"></i>

                <span x-show="sidebarOpen" x-transition:enter="transition opacity duration-200">Penjualan</span>

            </div>

            <i id="icon-kasir"
                class="ri-arrow-right-s-line transition-all"></i>

        </button>

        <div id="menu-kasir" class="menu-content" x-show="sidebarOpen">

            <a href="/pos"
                class="submenu {{ request()->is('pos') ? 'submenu-active' : '' }}">
                <i class="ri-shopping-cart-2-line"></i>
                POS
            </a>

            <a href="/pos/close-shift"
                class="submenu {{ request()->is('pos/close-shift*') ? 'submenu-active' : '' }}">
                <i class="ri-shut-down-line"></i>
                Tutup Shift
            </a>

             <a href="/transactions"
                class="submenu {{ request()->is('transactions*') ? 'submenu-active':'' }}">
                <i class="ri-price-tag-3-line"></i>
                Riwayat Transaksi
            </a>

        </div>



        {{-- ===================== --}}
        {{-- MASTER DATA --}}
        {{-- ===================== --}}

        <button
            type="button"
            onclick="toggleMenu('master')"
            class="menu-parent menu-group w-full px-4 py-3 flex items-center justify-between">

            <div class="flex items-center gap-3">

                <i class="ri-database-2-line"></i>

                <span x-show="sidebarOpen" x-transition:enter="transition opacity duration-200">Master Data</span>

            </div>

            <i id="icon-master"
                class="ri-arrow-right-s-line transition-all"></i>

        </button>

        <div id="menu-master" class="menu-content" x-show="sidebarOpen">
            @can('akses-spv-keatas')
            <a href="/users"
                class="submenu {{ request()->is('users*') ? 'submenu-active':'' }}">
                <i class="ri-group-line"></i>
                User
            </a>
            @endcan

            <a href="/products"
                class="submenu
                {{
                request()->is('products*')
                &&
                !request()->is('products/import*')
                ? 'submenu-active':''
                }}">
                <i class="ri-box-3-line"></i>
                Produk
            </a>

            @can('akses-spv-keatas')
            <a href="/products/import"
                class="submenu
                {{
                request()->is('products/import*')
                ? 'submenu-active':''
                }}">
                <i class="ri-file-upload-line   "></i>
                Import Produk
            </a>
            @endcan

            @can('akses-spv-keatas')
            <a href="/categories"
                class="submenu {{ request()->is('categories*') ? 'submenu-active':'' }}">
                <i class="ri-price-tag-3-line"></i>
                Kategori
            </a>
            @endcan

            @can('akses-spv-keatas')
            <a href="/suppliers"
                class="submenu {{ request()->is('suppliers*') ? 'submenu-active':'' }}">
                <i class="ri-truck-line"></i>
                Supplier
            </a>
            @endcan

            <a href="/customers"
                class="submenu {{ request()->is('customers*') ? 'submenu-active':'' }}">
                <i class="ri-user-heart-line"></i>
                Pelanggan
            </a>

        </div>



        {{-- ===================== --}}
        {{-- INVENTORY --}}
        {{-- ===================== --}}
        @can('akses-spv-keatas')
        <button
            type="button"
            onclick="toggleMenu('inventory')"
            class="menu-parent menu-group w-full px-4 py-3 flex items-center justify-between">

            <div class="flex items-center gap-3">

                <i class="ri-archive-line flex-shrink-0 text-xl"></i>

                <span x-show="sidebarOpen" x-transition:enter="transition opacity duration-200">Inventory</span>

            </div>

            <i id="icon-inventory" x-show="sidebarOpen" class="ri-arrow-right-s-line transition-all"></i>

        </button>

        <div id="menu-inventory" class="menu-content" x-show="sidebarOpen">
            
            <a href="/purchasing"
                class="submenu {{ request()->is('purchasing*') ? 'submenu-active':'' }}">
                <i class="ri-store-2-line"></i>
                Pembelian (PO)
            </a>

            <a 
                    href="{{ route('penerimaan.index') }}" 
                    class=submenu {{ request()->is('penerimaan*') ? 'submenu-active':'' }}"
                >
                    <i class="ri-download-2-line"></i>
                    Penerimaan Barang
                </a>

            <a href="/stock-cards"
                class="submenu {{ request()->is('stock-cards*') ? 'submenu-active':'' }}">
                <i class="ri-file-history-line"></i>
                Kartu Stok
            </a>

            <!-- MENU RETUR BARANG TERBARU -->
            <a 
                href="{{ route('retur.index') }}" 
                class="submenu {{ request()->is('retur*') ? 'submenu-active' : '' }}"
            >
                <i class="ri-arrow-go-back-line"></i>
                Retur Barang
            </a>
            
            <a href="/stock-opname"
                class="submenu {{ request()->is('stock-opname*') ? 'submenu-active':'' }}">
                <i class="ri-survey-line"></i>
                Stok Opname
            </a>

            <a href="/stock-adjustments"
                class="submenu {{ request()->is('stock-adjustments*') ? 'submenu-active':'' }}">
                <i class="ri-equalizer-line"></i>
                Penyesuaian Stok
            </a>
            
        </div>
        @endcan


        
        {{-- ===================== --}}
        {{-- LAPORAN --}}
        {{-- ===================== --}}
        
       <button
            type="button"
            onclick="toggleMenu('laporan')"
            class="menu-parent menu-group w-full px-4 py-3 flex items-center justify-between">

            <div class="flex items-center gap-3">
                <i class="ri-file-list-3-line flex-shrink-0 text-xl"></i>
                <span x-show="sidebarOpen" x-transition:enter="transition opacity duration-200">Laporan</span>
            </div>
            <i id="icon-laporan" x-show="sidebarOpen" class="ri-arrow-right-s-line transition-all"></i>
        </button>

        <div id="menu-laporan" class="menu-content" x-show="sidebarOpen">
            <a href="{{ route('laporan.penjualan-kasir') }}" 
                class="submenu {{ request()->routeIs('laporan.penjualan-kasir') ? 'submenu-active' : '' }}">
                <i class="ri-line-chart-line"></i>
                Penjualan Kasir
            </a>

            @can('akses-owner-admin')
            <a href="{{ route('laporan.shift.index') }}" 
                class="submenu {{ request()->routeIs('laporan.shift.*') ? 'submenu-active' : '' }}">
                <i class="ri-history-line"></i>
                Laporan Shift
            </a>
            <a href="{{ route('laporan.laba-rugi') }}" 
                class="submenu {{ request()->routeIs('laporan.laba-rugi') ? 'submenu-active' : '' }}">
                <i class="ri-money-dollar-box-line"></i>
                Laba Rugi Kotor
            </a>

            @endcan
            
            <!-- Hanya Supervisor ke atas yang bisa melihat laporan stok & sales global -->
            @can('akses-spv-keatas')
            <a href="/laporan/penjualan-produk" class="submenu {{ request()->routeIs('laporan/penjualan-produk') ? 'submenu-active' : '' }}">
                <i class="ri-shopping-bag-3-line"></i>
                Sales Per Produk
            </a>
            <a href="/laporan/penjualan-pelanggan" class="submenu {{ request()->is('laporan/penjualan-pelanggan') ? 'submenu-active' : '' }}">
                <i class="ri-contacts-line"></i>
                Sales Per Customer
            </a>
            {{-- laporan nilai aset stock--}}
            <a href="{{ route('laporan.nilai-aset') }}" class="submenu {{ request()->is('laporan.nilai-aset') ?  'submenu-active' : '' }}">
                <i class="ri-bank-card-line mr-2 text-lg"></i>
                <span>Nilai Aset Stok</span>
            </a>

            @endcan
        </div>
        
       {{-- ===================== --}}
       {{-- AKUNTING  --}}
       {{-- ===================== --}}
        @can('akses-owner-admin')
            <button
                type="button"
                onclick="toggleMenu('akunting')"
                class="menu-parent menu-group w-full px-4 py-3 flex items-center justify-between">

                <div class="flex items-center gap-3">
                    {{-- 💡 Diganti jadi icon ri-bank-line biar lebih mencerminkan akuntansi --}}
                    <i class="ri-bank-line flex-shrink-0 text-xl"></i>
                    <span x-show="sidebarOpen" x-transition:enter="transition opacity duration-200">Akunting</span>
                </div>

                {{-- 🚀 FIX 1: ID diganti dari 'icon-system' menjadi 'icon-akunting' --}}
                <i id="icon-akunting" x-show="sidebarOpen" class="ri-arrow-right-s-line transition-all"></i>

            </button>
            
            <div id="menu-akunting" class="menu-content" x-show="sidebarOpen">
                {{-- 🚀 FIX 2: Class diganti full menggunakan 'submenu' terstandar agar UI/UX seragam --}}
                <a href="{{ route('coa.index') }}" 
                   class="submenu {{ request()->is('coa*') ? 'submenu-active' : '' }}">
                    <i class="ri-bank-card-line"></i>
                    Master COA
                </a>
            </div>
        @endcan
        
        {{-- ===================== --}}
        {{-- SISTEM --}}
        {{-- ===================== --}}
        @can('akses-spv-keatas')
        <button
            type="button"
            onclick="toggleMenu('system')"
            class="menu-parent menu-group w-full px-4 py-3 flex items-center justify-between">

            <div class="flex items-center gap-3">

                <i class="ri-settings-3-line flex-shrink-0 text-xl"></i>
                <span x-show="sidebarOpen" x-transition:enter="transition opacity duration-200">Sistem</span>

            </div>

            <i id="icon-system" x-show="sidebarOpen" class="ri-arrow-right-s-line transition-all"></i>

        </button>

        <div id="menu-system" class="menu-content" x-show="sidebarOpen">
            <!-- Bagian Menu Sistem di Sidebar -->
            @can('akses-developer')
            
            <a href="{{ route('setting.index') }}" 
            class="submenu {{ request()->routeIs('setting.index') ? 'submenu-active' : '' }}">
                <i class="ri-settings-4-line    "></i>
                Pengaturan Toko
            </a>                        
            
            <a href="{{ route('developer.modules.index') }}" 
            class="submenu {{ request()->routeIs('developer.modules.*') ? 'submenu-active' : '' }}">
                <i class="ri-shield-keyhole-line"></i>
                Akses Modul Client
            </a>

            <a href="/developer"
                class="submenu {{ request()->is('developer') ? 'submenu-active':'' }}">
                <i class="ri-code-s-slash-line"></i>
                Developer
            </a>
            
            @endcan

            
            <a href="{{ route('backup.index') }}"
                class="submenu {{ request()->is('backup*') ? 'submenu-active' : '' }}">
                <i class="ri-hard-drive-2-line"></i>
                Backup Database
            </a>
            
        </div>
        @endcan
    </nav>

    

{{-- Logout --}}
    <div class="sidebar-footer p-4">

        <form
            method="POST"
            action="{{ route('logout') }}">

            @csrf

            <button class="logout-btn w-full flex items-center gap-3 px-4 py-3 rounded-xl transition justify-start">
                <i class="ri-logout-box-r-line flex-shrink-0 text-xl"></i>
                <span x-show="sidebarOpen" x-transition:enter="transition opacity duration-200">Logout</span>
            </button>

        </form>

    </div>

</aside>