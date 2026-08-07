<aside 
    :class="sidebarOpen ? 'w-64' : 'w-20'" 
    class="shadow-lg z-30 flex flex-col transition-all duration-300 ease-in-out md:overflow-visible bg-[#0A2947] text-[#E2E8F0]">

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
    <nav class="flex-1 py-5 md:overflow-visible"> 

        {{-- Dashboard --}}
        <div class="relative group" x-data="{ hovered: false }" @mouseenter="hovered = true" @mouseleave="hovered = false">
            <a href="/dashboard"
               class="menu-parent menu-group w-full flex items-center gap-3 px-4 py-3 {{ request()->is('dashboard*') ? 'submenu-active' : '' }}">
                <i class="ri-home-5-line"></i>
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
            <button type="button" onclick="sidebarOpen ? toggleMenu('kasir') : null"
                    class="menu-parent menu-group w-full px-4 py-3 flex items-center justify-between focus:outline-none">
                <div class="flex items-center gap-3">
                    <i class="ri-shopping-cart-2-line"></i>
                    <span x-show="sidebarOpen">Penjualan</span>
                </div>
                <i id="icon-kasir" x-show="sidebarOpen" class="ri-arrow-right-s-line transition-all"></i>
            </button>

            <!-- ACCORDION (Hanya saat Sidebar Terbuka) -->
            <div id="menu-kasir" class="menu-content" x-show="sidebarOpen">
                <a href="/pos" class="submenu {{ request()->is('pos') ? 'submenu-active' : '' }}"><i class="ri-shopping-cart-2-line"></i> POS</a>
                <a href="/pos/close-shift" class="submenu {{ request()->is('pos/close-shift*') ? 'submenu-active' : '' }}"><i class="ri-shut-down-line"></i> Tutup Shift</a>
                <a href="/transactions" class="submenu {{ request()->is('transactions*') ? 'submenu-active':'' }}"><i class="ri-price-tag-3-line"></i> Riwayat Transaksi</a>
            </div>

            <!-- POPUP MELAYANG (Hanya saat Sidebar Menciut & di-Hover) -->
            <!-- Ubah class 'absolute' menjadi 'fixed' dan sesuaikan jarak atasnya -->
<div x-show="!sidebarOpen && hovered" x-transition
     class="fixed left-20 transform -translate-y-2 bg-[#0A2947] border border-slate-700 rounded-xl shadow-2xl z-50 w-48 overflow-hidden py-2">
                <div class="px-4 py-1 text-xs font-semibold text-slate-400 border-b border-slate-700 mb-1">Penjualan</div>
                <a href="/pos" class="block px-4 py-2 text-sm text-slate-300 hover:bg-[#123A61] hover:text-white">POS</a>
                <a href="/pos/close-shift" class="block px-4 py-2 text-sm text-slate-300 hover:bg-[#123A61] hover:text-white">Tutup Shift</a>
                <a href="/transactions" class="block px-4 py-2 text-sm text-slate-300 hover:bg-[#123A61] hover:text-white">Riwayat Transaksi</a>
            </div>
        </div>

        {{-- ===================== --}}
        {{-- MASTER DATA --}}
        {{-- ===================== --}}
        <div class="relative group" x-data="{ hovered: false }" @mouseenter="hovered = true" @mouseleave="hovered = false">
            <button type="button" onclick="sidebarOpen ? toggleMenu('master') : null"
                    class="menu-parent menu-group w-full px-4 py-3 flex items-center justify-between focus:outline-none">
                <div class="flex items-center gap-3">
                    <i class="ri-database-2-line"></i>
                    <span x-show="sidebarOpen">Master Data</span>
                </div>
                <i id="icon-master" x-show="sidebarOpen" class="ri-arrow-right-s-line transition-all"></i>
            </button>

            <div id="menu-master" class="menu-content" x-show="sidebarOpen">
                @can('akses-spv-keatas')
                <a href="/users" class="submenu {{ request()->is('users*') ? 'submenu-active':'' }}"><i class="ri-group-line"></i> User</a>
                @endcan
                <a href="/products" class="submenu {{ request()->is('products*') && !request()->is('products/import*') ? 'submenu-active':'' }}"><i class="ri-box-3-line"></i> Produk</a>
                @can('akses-spv-keatas')
                <a href="/products/import" class="submenu {{ request()->is('products/import*') ? 'submenu-active':'' }}"><i class="ri-file-upload-line"></i> Import Produk</a>
                <a href="/categories" class="submenu {{ request()->is('categories*') ? 'submenu-active':'' }}"><i class="ri-price-tag-3-line"></i> Kategori</a>
                <a href="/suppliers" class="submenu {{ request()->is('suppliers*') ? 'submenu-active':'' }}"><i class="ri-truck-line"></i> Supplier</a>
                @endcan
                <a href="/customers" class="submenu {{ request()->is('customers*') ? 'submenu-active':'' }}"><i class="ri-user-heart-line"></i> Pelanggan</a>
            </div>

            <!-- POPUP MELAYANG MASTER DATA -->
            <!-- Ubah class 'absolute' menjadi 'fixed' dan sesuaikan jarak atasnya -->
<div x-show="!sidebarOpen && hovered" x-transition
     class="fixed left-20 transform -translate-y-2 bg-[#0A2947] border border-slate-700 rounded-xl shadow-2xl z-50 w-48 overflow-hidden py-2">
                <div class="px-4 py-1 text-xs font-semibold text-slate-400 border-b border-slate-700 mb-1">Master Data</div>
                @can('akses-spv-keatas')
                <a href="/users" class="block px-4 py-2 text-sm text-slate-300 hover:bg-[#123A61] hover:text-white">User</a>
                @endcan
                <a href="/products" class="block px-4 py-2 text-sm text-slate-300 hover:bg-[#123A61] hover:text-white">Produk</a>
                @can('akses-spv-keatas')
                <a href="/products/import" class="block px-4 py-2 text-sm text-slate-300 hover:bg-[#123A61] hover:text-white">Import Produk</a>
                <a href="/categories" class="block px-4 py-2 text-sm text-slate-300 hover:bg-[#123A61] hover:text-white">Kategori</a>
                <a href="/suppliers" class="block px-4 py-2 text-sm text-slate-300 hover:bg-[#123A61] hover:text-white">Supplier</a>
                @endcan
                <a href="/customers" class="block px-4 py-2 text-sm text-slate-300 hover:bg-[#123A61] hover:text-white">Pelanggan</a>
            </div>
        </div>

        {{-- ===================== --}}
        {{-- INVENTORY --}}
        {{-- ===================== --}}
        @can('akses-spv-keatas')
        <div class="relative group" x-data="{ hovered: false }" @mouseenter="hovered = true" @mouseleave="hovered = false">
            <button type="button" onclick="sidebarOpen ? toggleMenu('inventory') : null"
                    class="menu-parent menu-group w-full px-4 py-3 flex items-center justify-between focus:outline-none">
                <div class="flex items-center gap-3">
                    <i class="ri-archive-line"></i>
                    <span x-show="sidebarOpen">Inventory</span>
                </div>
                <i id="icon-inventory" x-show="sidebarOpen" class="ri-arrow-right-s-line transition-all"></i>
            </button>

            <div id="menu-inventory" class="menu-content" x-show="sidebarOpen">
                <a href="/purchasing" class="submenu {{ request()->is('purchasing*') ? 'submenu-active':'' }}"><i class="ri-store-2-line"></i> Pembelian (PO)</a>
                <a href="{{ route('penerimaan.index') }}" class="submenu {{ request()->is('penerimaan*') ? 'submenu-active':'' }}"><i class="ri-download-2-line"></i> Penerimaan Barang</a>
                <a href="/stock-cards" class="submenu {{ request()->is('stock-cards*') ? 'submenu-active':'' }}"><i class="ri-file-history-line"></i> Kartu Stok</a>
                <a href="{{ route('retur.index') }}" class="submenu {{ request()->is('retur*') ? 'submenu-active' : '' }}"><i class="ri-arrow-go-back-line"></i> Retur Barang</a>
                <a href="/stock-opname" class="submenu {{ request()->is('stock-opname*') ? 'submenu-active':'' }}"><i class="ri-survey-line"></i> Stok Opname</a>
                <a href="/stock-adjustments" class="submenu {{ request()->is('stock-adjustments*') ? 'submenu-active':'' }}"><i class="ri-equalizer-line"></i> Penyesuaian Stok</a>
            </div>

            <!-- POPUP MELAYANG INVENTORY -->
            <!-- Ubah class 'absolute' menjadi 'fixed' dan sesuaikan jarak atasnya -->
<div x-show="!sidebarOpen && hovered" x-transition
     class="fixed left-20 transform -translate-y-2 bg-[#0A2947] border border-slate-700 rounded-xl shadow-2xl z-50 w-48 overflow-hidden py-2">
                <div class="px-4 py-1 text-xs font-semibold text-slate-400 border-b border-slate-700 mb-1">Inventory</div>
                <a href="/purchasing" class="block px-4 py-2 text-sm text-slate-300 hover:bg-[#123A61] hover:text-white">Pembelian (PO)</a>
                <a href="{{ route('penerimaan.index') }}" class="block px-4 py-2 text-sm text-slate-300 hover:bg-[#123A61] hover:text-white">Penerimaan Barang</a>
                <a href="/stock-cards" class="block px-4 py-2 text-sm text-slate-300 hover:bg-[#123A61] hover:text-white">Kartu Stok</a>
                <a href="{{ route('retur.index') }}" class="block px-4 py-2 text-sm text-slate-300 hover:bg-[#123A61] hover:text-white">Retur Barang</a>
                <a href="/stock-opname" class="block px-4 py-2 text-sm text-slate-300 hover:bg-[#123A61] hover:text-white">Stok Opname</a>
                <a href="/stock-adjustments" class="block px-4 py-2 text-sm text-slate-300 hover:bg-[#123A61] hover:text-white">Penyesuaian Stok</a>
            </div>
        </div>
        @endcan

        {{-- ===================== --}}
        {{-- LAPORAN --}}
        {{-- ===================== --}}
        <div class="relative group" x-data="{ hovered: false }" @mouseenter="hovered = true" @mouseleave="hovered = false">
            <button type="button" onclick="sidebarOpen ? toggleMenu('laporan') : null"
                    class="menu-parent menu-group w-full px-4 py-3 flex items-center justify-between focus:outline-none">
                <div class="flex items-center gap-3">
                    <i class="ri-file-list-3-line"></i>
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
            <!-- Ubah class 'absolute' menjadi 'fixed' dan sesuaikan jarak atasnya -->
<div x-show="!sidebarOpen && hovered" x-transition
     class="fixed left-20 transform -translate-y-2 bg-[#0A2947] border border-slate-700 rounded-xl shadow-2xl z-50 w-48 overflow-hidden py-2">
                <div class="px-4 py-1 text-xs font-semibold text-slate-400 border-b border-slate-700 mb-1">Laporan</div>
                <a href="{{ route('laporan.penjualan-kasir') }}" class="block px-4 py-2 text-sm text-slate-300 hover:bg-[#123A61] hover:text-white">Penjualan Kasir</a>
                @can('akses-owner-admin')
                <a href="{{ route('laporan.shift.index') }}" class="block px-4 py-2 text-sm text-slate-300 hover:bg-[#123A61] hover:text-white">Laporan Shift</a>
                <a href="{{ route('laporan.laba-rugi') }}" class="block px-4 py-2 text-sm text-slate-300 hover:bg-[#123A61] hover:text-white">Laba Rugi Kotor</a>
                @endcan
                @can('akses-spv-keatas')
                <a href="/laporan/penjualan-produk" class="block px-4 py-2 text-sm text-slate-300 hover:bg-[#123A61] hover:text-white">Sales Per Produk</a>
                <a href="/laporan/penjualan-pelanggan" class="block px-4 py-2 text-sm text-slate-300 hover:bg-[#123A61] hover:text-white">Sales Per Customer</a>
                <a href="{{ route('laporan.nilai-aset') }}" class="block px-4 py-2 text-sm text-slate-300 hover:bg-[#123A61] hover:text-white">Nilai Aset Stok</a>
                @endcan
            </div>
        </div>

        {{-- ===================== --}}
        {{-- AKUNTING --}}
        {{-- ===================== --}}
        @can('akses-owner-admin')
        <div class="relative group" x-data="{ hovered: false }" @mouseenter="hovered = true" @mouseleave="hovered = false">
            <button type="button" onclick="sidebarOpen ? toggleMenu('akunting') : null"
                    class="menu-parent menu-group w-full px-4 py-3 flex items-center justify-between focus:outline-none">
                <div class="flex items-center gap-3">
                    <i class="ri-bank-line"></i>
                    <span x-show="sidebarOpen">Akunting</span>
                </div>
                <i id="icon-akunting" x-show="sidebarOpen" class="ri-arrow-right-s-line transition-all"></i>
            </button>

            <div id="menu-akunting" class="menu-content" x-show="sidebarOpen">
                <a href="{{ route('coa.index') }}" class="submenu {{ request()->is('coa*') ? 'submenu-active' : '' }}"><i class="ri-bank-card-line"></i> Master COA</a>
            </div>

            <!-- POPUP MELAYANG AKUNTING -->
            <!-- Ubah class 'absolute' menjadi 'fixed' dan sesuaikan jarak atasnya -->
<div x-show="!sidebarOpen && hovered" x-transition
     class="fixed left-20 transform -translate-y-2 bg-[#0A2947] border border-slate-700 rounded-xl shadow-2xl z-50 w-48 overflow-hidden py-2">
                <div class="px-4 py-1 text-xs font-semibold text-slate-400 border-b border-slate-700 mb-1">Akunting</div>
                <a href="{{ route('coa.index') }}" class="block px-4 py-2 text-sm text-slate-300 hover:bg-[#123A61] hover:text-white">Master COA</a>
            </div>
        </div>
        @endcan

        {{-- ===================== --}}
        {{-- SISTEM --}}
        {{-- ===================== --}}
        @can('akses-spv-keatas')
        <div class="relative group" x-data="{ hovered: false }" @mouseenter="hovered = true" @mouseleave="hovered = false">
            <button type="button" onclick="sidebarOpen ? toggleMenu('system') : null"
                    class="menu-parent menu-group w-full px-4 py-3 flex items-center justify-between focus:outline-none">
                <div class="flex items-center gap-3">
                    <i class="ri-settings-3-line"></i>
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
            <!-- Ubah class 'absolute' menjadi 'fixed' dan sesuaikan jarak atasnya -->
<div x-show="!sidebarOpen && hovered" x-transition
     class="fixed left-20 transform -translate-y-2 bg-[#0A2947] border border-slate-700 rounded-xl shadow-2xl z-50 w-48 overflow-hidden py-2">
                <div class="px-4 py-1 text-xs font-semibold text-slate-400 border-b border-slate-700 mb-1">Sistem</div>
                @can('akses-developer')
                <a href="{{ route('setting.index') }}" class="block px-4 py-2 text-sm text-slate-300 hover:bg-[#123A61] hover:text-white">Pengaturan Toko</a>
                <a href="{{ route('developer.modules.index') }}" class="block px-4 py-2 text-sm text-slate-300 hover:bg-[#123A61] hover:text-white">Akses Modul Client</a>
                <a href="/developer" class="block px-4 py-2 text-sm text-slate-300 hover:bg-[#123A61] hover:text-white">Developer</a>
                @endcan
                <a href="{{ route('backup.index') }}" class="block px-4 py-2 text-sm text-slate-300 hover:bg-[#123A61] hover:text-white">Backup Database</a>
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