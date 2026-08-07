{{-- <aside 
    :class="sidebarOpen ? 'w-64' : 'w-20'" 
    class="shadow-lg z-30 flex flex-col transition-all duration-300 ease-in-out md:overflow-visible bg-[#0A2947] text-[#E2E8F0]"> --}}
<aside 
    :class="sidebarOpen ? 'w-64' : 'w-20'" 
    class="shadow-lg z-30 flex flex-col transition-all duration-300 ease-in-out bg-[#0A2947] text-[#E2E8F0] h-screen sticky top-0 overflow-x-visible">

    {{-- Logo --}}
    <div class="px-6 py-6 border-b border-slate-800/20">
        <div class="flex items-center gap-3">
            <div class="w-11 h-11 rounded-xl bg-indigo-600 text-white flex items-center justify-center flex-shrink-0">
                <i class="ri-shopping-cart-2-line text-2xl"></i>
            </div>
            <div x-show="sidebarOpen" x-transition:enter="transition opacity duration-200" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100">    
                <h1 class="font-bold text-lg">POS Minimarket</h1>   
                <p class="text-xs text-slate-500">Sistem Penjualan</p>
            </div>
        </div>
    </div>

    {{-- Menu --}}
    {{-- <nav class="flex-1 py-5 md:overflow-visible"> --}}
    <nav :class="sidebarOpen ? 'overflow-y-auto' : 'overflow-y-visible'" class="flex-1 py-5 no-scrollbar">    

        {{-- Dashboard --}}
        <div class="relative group" x-data="{ hovered: false }" @mouseenter="hovered = true" @mouseleave="hovered = false">
                <a href="/dashboard"
                class="menu-parent menu-group w-full flex items-center gap-3 px-4 py-3 {{ request()->is('dashboard*') ? 'submenu-active' : '' }}">
                    <i class="ri-dashboard-2-line text-base font-normal"></i>
                    <span x-show="sidebarOpen">Dashboard</span>
                </a>
            
            <!-- POPUP MINIMALIS UNTUK SINGLE MENU -->
            <div x-show="!sidebarOpen && hovered" 
                 x-transition
                 class="absolute left-20 top-1/2 -translate-y-1/2 bg-[#0A2947] border border-slate-700 text-white text-sm rounded-xl py-2 px-4 shadow-xl z-50 whitespace-nowrap pointer-events-none">
                Dashboard
            </div>
        </div>

        {{-- ===================== --}}
        {{-- PENJUALAN --}}
        {{-- ===================== --}}
        <div class="relative group" x-data="{ hovered: false }" @mouseenter="hovered = true" @mouseleave="hovered = false">
            <!-- UBAH BAGIAN INI -->
            <button type="button" 
                    @click="if (sidebarOpen) { toggleMenu('kasir') }"
                    class="menu-parent menu-group w-full px-4 py-3 flex items-center justify-between focus:outline-none">
                <div class="flex items-center gap-3">
                    <i class="ri-shopping-cart-2-line text-base font-normal "></i>
                    <span x-show="sidebarOpen">Penjualan</span>
                </div>
                <i id="icon-kasir" x-show="sidebarOpen" class="ri-arrow-right-s-line transition-all"></i>
            </button>

            <!-- ACCORDION (Hanya saat Sidebar Terbuka) -->
            <div id="menu-kasir" class="menu-content" x-show="sidebarOpen">
                <a href="/pos" class="submenu {{ request()->is('pos') ? 'submenu-active' : '' }}"><i class="ri-shopping-cart-2-line"></i> POS</a>

                <a href="/pos/mobile" class="submenu {{ request()->is('pos/mobile*') ? 'submenu-active' : '' }}"><i class="ri-shopping-cart-2-line"></i> POS Mobile</a>

                <a href="/pos/close-shift" class="submenu {{ request()->is('pos/close-shift*') ? 'submenu-active' : '' }}"><i class="ri-shut-down-line"></i> Tutup Shift</a>
                <a href="/transactions" class="submenu {{ request()->is('transactions*') ? 'submenu-active':'' }}"><i class="ri-price-tag-3-line"></i> Riwayat Transaksi</a>

                {{-- <a href="/transactions" class="submenu {{ request()->is('transactions*') ? 'submenu-active':'' }}"><i class="ri-price-tag-3-line"></i> Riwayat Transaksi</a> --}}

                @can('akses-owner-admin')
                <a href="/pembatalan" class="submenu {{ request()->is('pembatalan*') ? 'submenu-active':'' }}"><i class="ri-forbid-line"></i> Pembatalan Transaksi</a>
                @endcan

            </div>

            <!-- POPUP MELAYANG (Hanya saat Sidebar Menciut & di-Hover) -->
            <div x-show="!sidebarOpen && hovered" 
                x-transition:enter="transition ease-out duration-150"
                x-transition:enter-start="opacity-0 scale-95"
                x-transition:enter-end="opacity-100 scale-100"
                x-transition:leave="transition ease-in duration-100"
                x-transition:leave-start="opacity-100 scale-100"
                x-transition:leave-end="opacity-0 scale-95"
                class="absolute left-20 top-0 bg-[#0A2947] border border-slate-700 rounded-xl shadow-2xl z-50 w-52 overflow-hidden py-2">
                <!-- Kepala Menu (Bold & Terang seperti Sidemenu Utama) -->
                <div class="px-4 py-2 text-sm font-semibold text-white border-b border-slate-700/50 mb-1 flex items-center gap-3">
                    {{-- <i class="ri-shopping-cart-2-line text-base"></i> --}}
                    Penjualan
                </div>
                <!-- Submenu (Lebih Redup & Reguler) -->
                <a href="/pos" class="flex items-center gap-3 px-5 py-2 text-sm text-slate-400 hover:bg-[#123A61] hover:text-white group">
                    <i class="ri-shopping-cart-2-line text-base opacity-60 group-hover:opacity-100"></i> POS
                </a>
                <a href="/pos/close-shift" class="flex items-center gap-3 px-5 py-2 text-sm text-slate-400 hover:bg-[#123A61] hover:text-white group">
                    <i class="ri-shut-down-line text-base opacity-60 group-hover:opacity-100"></i> Tutup Shift
                </a>
                <a href="/transactions" class="flex items-center gap-3 px-5 py-2 text-sm text-slate-400 hover:bg-[#123A61] hover:text-white group">
                    <i class="ri-price-tag-3-line text-base opacity-60 group-hover:opacity-100"></i> Riwayat Transaksi
                </a>
            </div>
        </div>

        {{-- ===================== --}}
        {{-- MASTER DATA --}}
        {{-- ===================== --}}
        <div class="relative group" x-data="{ hovered: false }" @mouseenter="hovered = true" @mouseleave="hovered = false">
            <button type="button" 
                    @click="if (sidebarOpen) { toggleMenu('master') }"
                    class="menu-parent menu-group w-full px-4 py-3 flex items-center justify-between focus:outline-none">
                <div class="flex items-center gap-3">
                    <i class="ri-database-2-line text-base font-normal"></i>
                    <span x-show="sidebarOpen">Master Data</span>
                </div>
                <i id="icon-master" x-show="sidebarOpen" class="ri-arrow-right-s-line transition-all"></i>
            </button>

            <div id="menu-master" class="menu-content" x-show="sidebarOpen">
                @can('akses-spv-keatas')
                <a href="/users" class="submenu {{ request()->is('users*') ? 'submenu-active':'' }}"><i class="ri-group-line"></i> User</a>
                
                <a href="/products" class="submenu {{ request()->is('products*') && !request()->is('products/import*') ? 'submenu-active':'' }}"><i class="ri-box-3-line"></i> Produk</a>
                <a href="/products/import" class="submenu {{ request()->is('products/import*') ? 'submenu-active':'' }}"><i class="ri-file-upload-line"></i> Import Produk</a>
                <a href="/categories" class="submenu {{ request()->is('categories*') ? 'submenu-active':'' }}"><i class="ri-price-tag-3-line"></i> Kategori</a>
                <a href="/suppliers" class="submenu {{ request()->is('suppliers*') ? 'submenu-active':'' }}"><i class="ri-truck-line"></i> Supplier</a>
                @endcan
                <a href="/customers" class="submenu {{ request()->is('customers*') ? 'submenu-active':'' }}"><i class="ri-user-heart-line"></i> Pelanggan</a>
            </div>

            <!-- POPUP MELAYANG MASTER DATA -->
            <div x-show="!sidebarOpen && hovered" 
                x-transition:enter="transition ease-out duration-150"
                x-transition:enter-start="opacity-0 scale-95"
                x-transition:enter-end="opacity-100 scale-100"
                x-transition:leave="transition ease-in duration-100"
                x-transition:leave-start="opacity-100 scale-100"
                x-transition:leave-end="opacity-0 scale-95"
                class="absolute left-20 top-0 bg-[#0A2947] border border-slate-700 rounded-xl shadow-2xl z-50 w-52 overflow-hidden py-2">
                <!-- Kepala Menu -->
                <div class="px-4 py-2 text-sm font-semibold text-white border-b border-slate-700/50 mb-1 flex items-center gap-3">
                    {{-- <i class="ri-database-2-line text-base"></i> --}}
                    Master Data
                </div>
                <!-- Submenu -->
                @can('akses-spv-keatas')
                <a href="/users" class="flex items-center gap-3 px-5 py-2 text-sm text-slate-400 hover:bg-[#123A61] hover:text-white group">
                    <i class="ri-group-line text-base opacity-60 group-hover:opacity-100"></i> User
                </a>
                
                <a href="/products" class="flex items-center gap-3 px-5 py-2 text-sm text-slate-400 hover:bg-[#123A61] hover:text-white group">
                    <i class="ri-box-3-line text-base opacity-60 group-hover:opacity-100"></i> Produk
                </a>
                
                <a href="/products/import" class="flex items-center gap-3 px-5 py-2 text-sm text-slate-400 hover:bg-[#123A61] hover:text-white group">
                    <i class="ri-file-upload-line text-base opacity-60 group-hover:opacity-100"></i> Import Produk
                </a>
                <a href="/categories" class="flex items-center gap-3 px-5 py-2 text-sm text-slate-400 hover:bg-[#123A61] hover:text-white group">
                    <i class="ri-price-tag-3-line text-base opacity-60 group-hover:opacity-100"></i> Kategori
                </a>
                <a href="/suppliers" class="flex items-center gap-3 px-5 py-2 text-sm text-slate-400 hover:bg-[#123A61] hover:text-white group">
                    <i class="ri-truck-line text-base opacity-60 group-hover:opacity-100"></i> Supplier
                </a>
                @endcan
                <a href="/customers" class="flex items-center gap-3 px-5 py-2 text-sm text-slate-400 hover:bg-[#123A61] hover:text-white group">
                    <i class="ri-user-heart-line text-base opacity-60 group-hover:opacity-100"></i> Pelanggan
                </a>
            </div>
        </div>

        {{-- ===================== --}}
        {{-- INVENTORY --}}
        {{-- ===================== --}}
        
        <div class="relative group" x-data="{ hovered: false }" @mouseenter="hovered = true" @mouseleave="hovered = false">
            <button type="button" 
                @click="if (sidebarOpen) { toggleMenu('inventory') }"
                class="menu-parent menu-group w-full px-4 py-3 flex items-center justify-between focus:outline-none">
                <div class="flex items-center gap-3">
                    <i class="ri-archive-line text-base font-normal"></i>
                    <span x-show="sidebarOpen">Inventory</span>
                </div>
                <i id="icon-inventory" x-show="sidebarOpen" class="ri-arrow-right-s-line transition-all"></i>
            </button>

            <div id="menu-inventory" class="menu-content" x-show="sidebarOpen">
                @can('akses-spv-keatas')
                <a href="/purchasing" class="submenu {{ request()->is('purchasing*') ? 'submenu-active':'' }}"><i class="ri-store-2-line"></i> Pembelian (PO)</a>
                <a href="{{ route('penerimaan.index') }}" class="submenu {{ request()->is('penerimaan*') ? 'submenu-active':'' }}"><i class="ri-download-2-line"></i> Penerimaan Barang</a>
                @endcan

                <a href="/stock-cards" class="submenu {{ request()->is('stock-cards*') ? 'submenu-active':'' }}"><i class="ri-file-history-line"></i> Kartu Stok</a>

                @can('akses-spv-keatas')
                <a href="{{ route('retur.index') }}" class="submenu {{ request()->is('retur*') ? 'submenu-active' : '' }}"><i class="ri-arrow-go-back-line"></i> Retur Barang</a>
                <a href="/stock-opname" class="submenu {{ request()->is('stock-opname*') ? 'submenu-active':'' }}"><i class="ri-survey-line"></i> Stok Opname</a>
                <a href="/stock-adjustments" class="submenu {{ request()->is('stock-adjustments*') ? 'submenu-active':'' }}"><i class="ri-equalizer-line"></i> Penyesuaian Stok</a>
                
                <a href="/inventory/daily-reset" class="submenu {{ request()->is('daily-reset*') ? 'submenu-active':'' }}"><i class="ri-equalizer-line"></i> Opname Harian</a>
                {{-- route('daily-reset.index') --}}
                @endcan
            </div>

            <!-- POPUP MELAYANG INVENTORY -->
            <div x-show="!sidebarOpen && hovered" 
                x-transition:enter="transition ease-out duration-150"
                x-transition:enter-start="opacity-0 scale-95"
                x-transition:enter-end="opacity-100 scale-100"
                x-transition:leave="transition ease-in duration-100"
                x-transition:leave-start="opacity-100 scale-100"
                x-transition:leave-end="opacity-0 scale-95"
                class="absolute left-20 top-0 bg-[#0A2947] border border-slate-700 rounded-xl shadow-2xl z-50 w-52 overflow-hidden py-2">
                <!-- Kepala Menu -->
                <div class="px-4 py-2 text-sm font-semibold text-white border-b border-slate-700/50 mb-1 flex items-center gap-3">
                    <i class="ri-archive-line text-base"></i> Inventory
                </div>
                <!-- Submenu -->
                @can('akses-spv-keatas')
                <a href="/purchasing" class="flex items-center gap-3 px-5 py-2 text-sm text-slate-400 hover:bg-[#123A61] hover:text-white group">
                    <i class="ri-store-2-line text-base opacity-60 group-hover:opacity-100"></i> Pembelian (PO)
                </a>
                <a href="{{ route('penerimaan.index') }}" class="flex items-center gap-3 px-5 py-2 text-sm text-slate-400 hover:bg-[#123A61] hover:text-white group">
                    <i class="ri-download-2-line text-base opacity-60 group-hover:opacity-100"></i> Penerimaan Barang
                </a>
                @endcan

                <a href="/stock-cards" class="flex items-center gap-3 px-5 py-2 text-sm text-slate-400 hover:bg-[#123A61] hover:text-white group">
                    <i class="ri-file-history-line text-base opacity-60 group-hover:opacity-100"></i> Kartu Stok
                </a>

                @can('akses-spv-keatas')
                <a href="{{ route('retur.index') }}" class="flex items-center gap-3 px-5 py-2 text-sm text-slate-400 hover:bg-[#123A61] hover:text-white group">
                    <i class="ri-arrow-go-back-line text-base opacity-60 group-hover:opacity-100"></i> Retur Barang
                </a>
                <a href="/stock-opname" class="flex items-center gap-3 px-5 py-2 text-sm text-slate-400 hover:bg-[#123A61] hover:text-white group">
                    <i class="ri-survey-line text-base opacity-60 group-hover:opacity-100"></i> Stok Opname
                </a>
                <a href="/stock-adjustments" class="flex items-center gap-3 px-5 py-2 text-sm text-slate-400 hover:bg-[#123A61] hover:text-white group">
                    <i class="ri-equalizer-line text-base opacity-60 group-hover:opacity-100"></i> Penyesuaian Stok
                </a>
                @endcan
            </div>
        </div>
        

        {{-- ===================== --}}
        {{-- LAPORAN --}}
        {{-- ===================== --}}
        <div class="relative group" x-data="{ hovered: false }" @mouseenter="hovered = true" @mouseleave="hovered = false">
            <button type="button" 
                @click="if (sidebarOpen) { toggleMenu('laporan') }"
                class="menu-parent menu-group w-full px-4 py-3 flex items-center justify-between focus:outline-none">

                <div class="flex items-center gap-3">
                    <i class="ri-file-list-3-line text-base font-normal"></i>
                    <span x-show="sidebarOpen">Laporan</span>
                </div>
                <i id="icon-laporan" x-show="sidebarOpen" class="ri-arrow-right-s-line transition-all"></i>
            </button>

            <div id="menu-laporan" class="menu-content" x-show="sidebarOpen">
                <a href="{{ route('laporan.penjualan-kasir') }}" class="submenu {{ request()->routeIs('laporan.penjualan-kasir') ? 'submenu-active' : '' }}"><i class="ri-line-chart-line"></i> Penjualan Kasir</a>
                @can('akses-owner-admin')
                <a href="{{ route('laporan.shift.index') }}" class="submenu {{ request()->routeIs('laporan.shift.*') ? 'submenu-active' : '' }}"><i class="ri-history-line"></i> Laporan Shift</a>
                <a href="{{ route('laporan.laba-rugi') }}" class="submenu {{ request()->routeIs('laporan.laba-rugi') ? 'submenu-active' : '' }}"><i class="ri-money-dollar-box-line"></i> Laba Rugi Kotor</a>
                @endcan
                @can('akses-spv-keatas')
                <a href="/laporan/penjualan-produk" class="submenu {{ request()->routeIs('laporan/penjualan-produk') ? 'submenu-active' : '' }}"><i class="ri-shopping-bag-3-line"></i> Sales Per Produk</a>
                <a href="/laporan/penjualan-pelanggan" class="submenu {{ request()->is('laporan/penjualan-pelanggan') ? 'submenu-active' : '' }}"><i class="ri-contacts-line"></i> Sales Per Customer</a>
                <a href="{{ route('laporan.nilai-aset') }}" class="submenu {{ request()->is('laporan.nilai-aset') ? 'submenu-active' : '' }}"><i class="ri-bank-card-line"></i> Nilai Aset Stok</a>
                @endcan
            </div>

            <!-- POPUP MELAYANG LAPORAN -->
            <div x-show="!sidebarOpen && hovered" 
                x-transition:enter="transition ease-out duration-150"
                x-transition:enter-start="opacity-0 scale-95"
                x-transition:enter-end="opacity-100 scale-100"
                x-transition:leave="transition ease-in duration-100"
                x-transition:leave-start="opacity-100 scale-100"
                x-transition:leave-end="opacity-0 scale-95"
                class="absolute left-20 top-0 bg-[#0A2947] border border-slate-700 rounded-xl shadow-2xl z-50 w-56 overflow-hidden py-2">
                <!-- Kepala Menu -->
                <div class="px-4 py-2 text-sm font-semibold text-white border-b border-slate-700/50 mb-1 flex items-center gap-3">
                    <i class="ri-file-list-3-line text-base"></i> Laporan
                </div>
                <!-- Submenu -->
                <a href="{{ route('laporan.penjualan-kasir') }}" class="flex items-center gap-3 px-5 py-2 text-sm text-slate-400 hover:bg-[#123A61] hover:text-white group">
                    <i class="ri-line-chart-line text-base opacity-60 group-hover:opacity-100"></i> Penjualan Kasir
                </a>
                @can('akses-owner-admin')
                <a href="{{ route('laporan.shift.index') }}" class="flex items-center gap-3 px-5 py-2 text-sm text-slate-400 hover:bg-[#123A61] hover:text-white group">
                    <i class="ri-history-line text-base opacity-60 group-hover:opacity-100"></i> Laporan Shift
                </a>
                <a href="{{ route('laporan.laba-rugi') }}" class="flex items-center gap-3 px-5 py-2 text-sm text-slate-400 hover:bg-[#123A61] hover:text-white group">
                    <i class="ri-money-dollar-box-line text-base opacity-60 group-hover:opacity-100"></i> Laba Rugi Kotor
                </a>
                @endcan
                @can('akses-spv-keatas')
                <a href="/laporan/penjualan-produk" class="flex items-center gap-3 px-5 py-2 text-sm text-slate-400 hover:bg-[#123A61] hover:text-white group">
                    <i class="ri-shopping-bag-3-line text-base opacity-60 group-hover:opacity-100"></i> Sales Per Produk
                </a>
                <a href="/laporan/penjualan-pelanggan" class="flex items-center gap-3 px-5 py-2 text-sm text-slate-400 hover:bg-[#123A61] hover:text-white group">
                    <i class="ri-contacts-line text-base opacity-60 group-hover:opacity-100"></i> Sales Per Customer
                </a>
                <a href="{{ route('laporan.nilai-aset') }}" class="flex items-center gap-3 px-5 py-2 text-sm text-slate-400 hover:bg-[#123A61] hover:text-white group">
                    <i class="ri-bank-card-line text-base opacity-60 group-hover:opacity-100"></i> Nilai Aset Stok
                </a>
                @endcan
            </div>
        </div>

        {{-- ===================== --}}
        {{-- AKUNTING --}}
        {{-- ===================== --}}
        @can('akses-owner-admin')
        <div class="relative group" x-data="{ hovered: false }" @mouseenter="hovered = true" @mouseleave="hovered = false">
            <button type="button" 
                @click="if (sidebarOpen) { toggleMenu('akunting') }"
                class="menu-parent menu-group w-full px-4 py-3 flex items-center justify-between focus:outline-none">
                <div class="flex items-center gap-3">
                    <i class="ri-bank-line text-base font-normal"></i>
                    <span x-show="sidebarOpen">Akunting</span>
                </div>
                <i id="icon-akunting" x-show="sidebarOpen" class="ri-arrow-right-s-line transition-all"></i>
            </button>

            <div id="menu-akunting" class="menu-content" x-show="sidebarOpen">
                <a href="{{ route('coa.index') }}" class="submenu {{ request()->is('coa*') ? 'submenu-active' : '' }}"><i class="ri-bank-card-line"></i> Master COA</a>
            </div>

            <!-- POPUP MELAYANG AKUNTING -->
            <div x-show="!sidebarOpen && hovered" 
                x-transition:enter="transition ease-out duration-150"
                x-transition:enter-start="opacity-0 scale-95"
                x-transition:enter-end="opacity-100 scale-100"
                x-transition:leave="transition ease-in duration-100"
                x-transition:leave-start="opacity-100 scale-100"
                x-transition:leave-end="opacity-0 scale-95"
                class="absolute left-20 top-0 bg-[#0A2947] border border-slate-700 rounded-xl shadow-2xl z-50 w-52 overflow-hidden py-2">
                <!-- Kepala Menu -->
                <div class="px-4 py-2 text-sm font-semibold text-white border-b border-slate-700/50 mb-1 flex items-center gap-3">
                    <i class="ri-bank-line text-base"></i> Akunting
                </div>
                <!-- Submenu -->
                <a href="{{ route('coa.index') }}" class="flex items-center gap-3 px-5 py-2 text-sm text-slate-400 hover:bg-[#123A61] hover:text-white group">
                    <i class="ri-bank-card-line text-base opacity-60 group-hover:opacity-100"></i> Master COA
                </a>
            </div>
        </div>
        @endcan

        {{-- ===================== --}}
        {{-- SISTEM --}}
        {{-- ===================== --}}
        @can('akses-spv-keatas')
        <div class="relative group" x-data="{ hovered: false }" @mouseenter="hovered = true" @mouseleave="hovered = false">
            <button type="button" 
                @click="if (sidebarOpen) { toggleMenu('system') }"
                class="menu-parent menu-group w-full px-4 py-3 flex items-center justify-between focus:outline-none">
                <div class="flex items-center gap-3">
                    <i class="ri-settings-3-line text-base font-normal"></i>
                    <span x-show="sidebarOpen">Sistem</span>
                </div>
                <i id="icon-system" x-show="sidebarOpen" class="ri-arrow-right-s-line transition-all"></i>
            </button>

            <div id="menu-system" class="menu-content" x-show="sidebarOpen">
                @can('akses-developer')
                <a href="{{ route('setting.index') }}" class="submenu {{ request()->routeIs('setting.index') ? 'submenu-active' : '' }}"><i class="ri-settings-4-line"></i> Pengaturan Toko</a>                        
                <a href="{{ route('developer.modules.index') }}" class="submenu {{ request()->routeIs('developer.modules.*') ? 'submenu-active' : '' }}"><i class="ri-shield-keyhole-line"></i> Akses Modul Client</a>
                <a href="/developer" class="submenu {{ request()->is('developer') ? 'submenu-active':'' }}"><i class="ri-code-s-slash-line"></i> Developer</a>
                @endcan
                <a href="{{ route('backup.index') }}" class="submenu {{ request()->is('backup*') ? 'submenu-active' : '' }}"><i class="ri-hard-drive-2-line"></i> Backup Database</a>
            </div>

            <!-- POPUP MELAYANG SISTEM -->
            <div x-show="!sidebarOpen && hovered" 
                x-transition:enter="transition ease-out duration-150"
                x-transition:enter-start="opacity-0 scale-95"
                x-transition:enter-end="opacity-100 scale-100"
                x-transition:leave="transition ease-in duration-100"
                x-transition:leave-start="opacity-100 scale-100"
                x-transition:leave-end="opacity-0 scale-95"
                class="absolute left-20 top-0 bg-[#0A2947] border border-slate-700 rounded-xl shadow-2xl z-50 w-56 overflow-hidden py-2">
                <!-- Kepala Menu -->
                <div class="px-4 py-2 text-sm font-semibold text-white border-b border-slate-700/50 mb-1 flex items-center gap-3">
                    <i class="ri-settings-3-line text-base"></i> Sistem
                </div>
                <!-- Submenu -->
                @can('akses-developer')
                <a href="{{ route('setting.index') }}" class="flex items-center gap-3 px-5 py-2 text-sm text-slate-400 hover:bg-[#123A61] hover:text-white group">
                    <i class="ri-settings-4-line text-base opacity-60 group-hover:opacity-100"></i> Pengaturan Toko
                </a>
                <a href="{{ route('developer.modules.index') }}" class="flex items-center gap-3 px-5 py-2 text-sm text-slate-400 hover:bg-[#123A61] hover:text-white group">
                    <i class="ri-shield-keyhole-line text-base opacity-60 group-hover:opacity-100"></i> Akses Modul Client
                </a>
                <a href="/developer" class="flex items-center gap-3 px-5 py-2 text-sm text-slate-400 hover:bg-[#123A61] hover:text-white group">
                    <i class="ri-code-s-slash-line text-base opacity-60 group-hover:opacity-100"></i> Developer
                </a>
                @endcan
                <a href="{{ route('backup.index') }}" class="flex items-center gap-3 px-5 py-2 text-sm text-slate-400 hover:bg-[#123A61] hover:text-white group">
                    <i class="ri-hard-drive-2-line text-base opacity-60 group-hover:opacity-100"></i> Backup Database
                </a>
            </div>
        </div>
        @endcan
    </nav>

    {{-- Logout --}}
    <div class="sidebar-footer p-4 border-t border-slate-800/50">
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button class="logout-btn w-full flex items-center gap-3 px-4 py-3 rounded-xl transition justify-start text-red-400 hover:bg-red-950/50">
                <i class="ri-logout-box-r-line text-xl"></i>
                <span x-show="sidebarOpen">Logout</span>
            </button>
        </form>
    </div>
</aside>