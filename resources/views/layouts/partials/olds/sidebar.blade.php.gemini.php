@php
    // Mendeteksi rute aktif secara dinamis untuk memberikan penanda visual aktif/fokus
    $currentUrl = request()->url();
    
    // Konfigurasi Menu & Submenu POS sesuai instruksi dan tata letak image_c41136.jpg
    $menus = [
        [
            'category' => 'UTAMA',
            'items' => [
                [
                    'title' => 'Dashboard',
                    'icon' => 'ri-dashboard-3-line',
                    'route' => url('/dashboard'),
                    'is_active' => request()->is('dashboard') || request()->is('/'),
                ]
            ]
        ],
        [
            'category' => 'TRANSAKSI',
            'items' => [
                [
                    'title' => 'Penjualan',
                    'icon' => 'ri-shopping-cart-2-line',
                    'is_active' => request()->is('pos*') || request()->is('sales*'),
                    'submenu' => [
                        ['title' => 'POS', 'route' => url('/pos'), 'is_active' => request()->is('pos')],
                        ['title' => 'Riwayat Penjualan', 'route' => url('/sales/history'), 'is_active' => request()->is('sales/history')],
                    ]
                ],
                [
                    'title' => 'Pembelian',
                    'icon' => 'ri-shopping-bag-4-line',
                    'is_active' => request()->is('po*') || request()->is('purchases*') || request()->is('receiving*'),
                    'submenu' => [
                        ['title' => 'PO', 'route' => url('/po'), 'is_active' => request()->is('po')],
                        ['title' => 'Riwayat PO', 'route' => url('/po/history'), 'is_active' => request()->is('po/history')],
                        ['title' => 'Penerimaan Barang', 'route' => url('/receiving'), 'is_active' => request()->is('receiving')],
                    ]
                ]
            ]
        ],
        [
            'category' => 'PERSEDIAAN',
            'items' => [
                [
                    'title' => 'Inventory',
                    'icon' => 'ri-archive-line',
                    'is_active' => request()->is('inventory*') || request()->is('stock*'),
                    'submenu' => [
                        ['title' => 'Retur', 'route' => url('/inventory/returns'), 'is_active' => request()->is('inventory/returns')],
                        ['title' => 'Stock Adjustment', 'route' => url('/inventory/adjustment'), 'is_active' => request()->is('inventory/adjustment')],
                        ['title' => 'Stock Opname', 'route' => url('/stock-opnames'), 'is_active' => request()->is('stock-opnames*')],
                        ['title' => 'Kartu Stok', 'route' => url('/inventory/card'), 'is_active' => request()->is('inventory/card')],
                    ]
                ]
            ]
        ],
        [
            'category' => 'MASTER',
            'items' => [
                [
                    'title' => 'Master',
                    'icon' => 'ri-folder-shared-line',
                    'is_active' => request()->is('products*') || request()->is('categories*') || request()->is('suppliers*') || request()->is('customers*') || request()->is('brands*') || request()->is('units*'),
                    'submenu' => [
                        ['title' => 'Barang', 'route' => url('/products'), 'is_active' => request()->is('products*')],
                        ['title' => 'Kategori', 'route' => url('/categories'), 'is_active' => request()->is('categories*')],
                        ['title' => 'Supplier', 'route' => url('/suppliers'), 'is_active' => request()->is('suppliers*')],
                        ['title' => 'Pelanggan', 'route' => url('/customers'), 'is_active' => request()->is('customers*')],
                        ['title' => 'Merk', 'route' => '#', 'is_active' => false], // Rute kosong dialihkan ke '#'
                        ['title' => 'Cetak Barcode', 'route' => '#', 'is_active' => false],
                        ['title' => 'Satuan', 'route' => '#', 'is_active' => false],
                    ]
                ]
            ]
        ],
        [
            'category' => 'LAPORAN',
            'items' => [
                [
                    'title' => 'Laporan',
                    'icon' => 'ri-bar-chart-box-line',
                    'is_active' => request()->is('reports*'),
                    'submenu' => [
                        ['title' => 'Laporan Kasir', 'route' => '#', 'is_active' => false],
                        ['title' => 'Laporan Omzet', 'route' => '#', 'is_active' => false],
                        ['title' => 'Laporan Stock minus', 'route' => '#', 'is_active' => false],
                        ['title' => 'Laporan Penjualan per Item', 'route' => '#', 'is_active' => false],
                        ['title' => 'Laporan Penjualan per Pelanggan', 'route' => '#', 'is_active' => false],
                    ]
                ]
            ]
        ],
        [
            'category' => 'PENGATURAN',
            'items' => [
                [
                    'title' => 'Setting',
                    'icon' => 'ri-settings-5-line',
                    'is_active' => request()->is('settings*') || request()->is('developer*') || request()->is('users*'),
                    'submenu' => [
                        ['title' => 'Profil Toko', 'route' => url('/settings'), 'is_active' => request()->is('settings')],
                        ['title' => 'Hak Akses', 'route' => '#', 'is_active' => false],
                        ['title' => 'User', 'route' => '#', 'is_active' => false],
                        ['title' => 'Developer Tools', 'route' => url('/developer'), 'is_active' => request()->is('developer')],
                    ]
                ]
            ]
        ]
    ];
@endphp

<!-- Sidebar Main Wrapper (Menggunakan warna navy gelap premium match dengan referensi gambar) -->
<div id="sidebar-container" class="flex flex-col h-screen w-64 bg-[#0f172a] text-slate-300 border-r border-slate-800 transition-all duration-300 z-30 select-none">
    
    <!-- Bagian Brand/Header Aplikasi -->
    <div class="flex items-center justify-between px-5 py-5 border-b border-slate-800">
        <div class="flex items-center gap-3">
            <div class="flex items-center justify-center w-10 h-10 rounded-xl bg-indigo-600 shadow-lg shadow-indigo-500/30 text-white">
                <i class="ri-store-2-line text-2xl"></i>
            </div>
            <div class="sidebar-text flex flex-col">
                <span class="font-bold text-white text-base tracking-wide leading-tight">POS Minimarket</span>
                <span class="text-[10px] text-emerald-400 font-semibold flex items-center gap-1">
                    <span class="w-1.5 h-1.5 bg-emerald-500 rounded-full animate-pulse"></span>
                    ONLINE VERSION
                </span>
            </div>
        </div>
        <!-- Button Toggle untuk Collapse Menu samping -->
        <button id="toggle-sidebar" class="p-1.5 rounded-lg hover:bg-slate-800 text-slate-400 hover:text-white transition-colors duration-200">
            <i class="ri-menu-2-line text-xl"></i>
        </button>
    </div>

    <!-- Bagian List Menu / Scrollable Navigation Area -->
    <div class="flex-1 overflow-y-auto px-3 py-4 space-y-6 scrollbar-thin scrollbar-thumb-slate-800">
        @foreach($menus as $group)
            <div>
                <!-- Label Pengelompokan Menu (Sesuai Desain: MASTER, TRANSAKSI, dll) -->
                @if($group['category'] !== 'UTAMA')
                    <div class="sidebar-text px-3 mb-2 text-[10px] font-bold text-slate-500 tracking-wider uppercase">
                        {{ $group['category'] }}
                    </div>
                @endif

                <div class="space-y-1">
                    @foreach($group['items'] as $item)
                        <!-- Jika Menu Memiliki Submenu -->
                        @if(isset($item['submenu']))
                            <div class="menu-item-dropdown" data-menu="{{ Str::slug($item['title']) }}">
                                <button 
                                    type="button" 
                                    onclick="toggleSubmenu('{{ Str::slug($item['title']) }}')"
                                    class="w-full flex items-center justify-between px-3 py-2.5 rounded-xl text-sm font-medium transition-all duration-200 hover:bg-slate-800 hover:text-white group {{ $item['is_active'] ? 'text-white' : 'text-slate-400' }}"
                                >
                                    <div class="flex items-center gap-3">
                                        <i class="{{ $item['icon'] }} text-lg group-hover:scale-110 transition-transform duration-200"></i>
                                        <span class="sidebar-text">{{ $item['title'] }}</span>
                                    </div>
                                    <!-- Icon Chevron indikator expand/collapse -->
                                    <i class="ri-arrow-right-s-line text-lg sidebar-chevron transition-transform duration-200 {{ $item['is_active'] ? 'rotate-90 text-white' : 'text-slate-500' }}"></i>
                                </button>

                                <!-- Container Submenu (dengan transisi halus) -->
                                <div 
                                    id="submenu-{{ Str::slug($item['title']) }}" 
                                    class="submenu-container mt-1 pl-9 space-y-1 overflow-hidden transition-all duration-300"
                                    style="max-height: {{ $item['is_active'] ? '500px' : '0px' }}"
                                >
                                    @foreach($item['submenu'] as $sub)
                                        <a 
                                            href="{{ $sub['route'] }}" 
                                            class="flex items-center gap-3 py-2 px-3 rounded-lg text-xs font-medium transition-colors duration-150 {{ $sub['is_active'] ? 'bg-indigo-600/10 text-indigo-400 border-l-2 border-indigo-500 font-bold' : 'text-slate-400 hover:text-white hover:bg-slate-800/50' }}"
                                        >
                                            <span class="w-1.5 h-1.5 rounded-full {{ $sub['is_active'] ? 'bg-indigo-500' : 'bg-slate-600' }}"></span>
                                            <span>{{ $sub['title'] }}</span>
                                        </a>
                                    @endforeach
                                </div>
                            </div>
                        @else
                            <!-- Jika Menu Utama Tunggal (Seperti Dashboard) -->
                            <a 
                                href="{{ $item['route'] }}" 
                                class="flex items-center justify-between px-3 py-2.5 rounded-xl text-sm font-medium transition-all duration-200 {{ $item['is_active'] ? 'bg-indigo-600 text-white shadow-lg shadow-indigo-600/20' : 'text-slate-400 hover:bg-slate-800 hover:text-white group' }}"
                            >
                                <div class="flex items-center gap-3">
                                    <i class="{{ $item['icon'] }} text-lg {{ $item['is_active'] ? 'text-white' : 'group-hover:scale-110 transition-transform duration-200' }}"></i>
                                    <span class="sidebar-text">{{ $item['title'] }}</span>
                                </div>
                            </a>
                        @endif
                    @endforeach
                </div>
            </div>
        @endforeach
    </div>

    <!-- Bagian Informasi User / Footer Sidebar (Sesuai Desain image_c41136.jpg) -->
    <div class="p-4 border-t border-slate-800 bg-[#0b1329]">
        <div class="flex items-center justify-between group">
            <div class="flex items-center gap-3 overflow-hidden">
                <div class="relative flex-shrink-0">
                    <!-- Avatar dengan inisial nama premium -->
                    <div class="w-10 h-10 rounded-full bg-slate-700 border-2 border-indigo-500/50 flex items-center justify-center text-white font-bold text-sm tracking-wider">
                        AD
                    </div>
                    <!-- Status online indicator -->
                    <span class="absolute bottom-0 right-0 w-3 h-3 bg-emerald-500 border-2 border-[#0b1329] rounded-full"></span>
                </div>
                <div class="sidebar-text flex flex-col min-w-0">
                    <span class="font-semibold text-white text-sm truncate">Admin Kasir</span>
                    <span class="text-xs text-slate-500 truncate">admin@pos.com</span>
                </div>
            </div>
            
            <!-- Tombol Logout Cepat -->
            <form method="POST" action="{{ route('logout') }}" id="logout-form" class="inline">
                @csrf
                <button 
                    type="submit" 
                    title="Keluar Aplikasi"
                    class="p-2 rounded-lg hover:bg-red-500/10 text-slate-400 hover:text-red-400 transition-colors duration-200"
                    onclick="event.preventDefault(); document.getElementById('logout-form').submit();"
                >
                    <i class="ri-logout-box-r-line text-lg"></i>
                </button>
            </form>
        </div>
    </div>
</div>

<script>
    /**
     * Mengatur buka-tutup submenu (Accordion effect)
     * @param {string} menuId - Identitas menu slug
     */
    function toggleSubmenu(menuId) {
        const container = document.getElementById(`submenu-${menuId}`);
        const parentBtn = container.previousElementSibling;
        const chevron = parentBtn.querySelector('.sidebar-chevron');
        
        // Cek jika saat ini sedang tertutup (maxHeight bernilai 0px atau kosong)
        if (!container.style.maxHeight || container.style.maxHeight === "0px") {
            // Dapatkan tinggi konten aslinya agar transisi mulus
            container.style.maxHeight = container.scrollHeight + "px";
            chevron.classList.add('rotate-90');
            chevron.classList.add('text-white');
        } else {
            // Tutup kembali
            container.style.maxHeight = "0px";
            chevron.classList.remove('rotate-90');
            chevron.classList.remove('text-white');
        }
    }

    /**
     * Logic Mini-Sidebar Collapse/Expand (Sistem Samping Sempit)
     */
    document.addEventListener("DOMContentLoaded", function() {
        const toggleBtn = document.getElementById('toggle-sidebar');
        const sidebar = document.getElementById('sidebar-container');
        const sidebarTexts = document.querySelectorAll('.sidebar-text');
        
        toggleBtn.addEventListener('click', function() {
            // Toggle ukuran sidebar antara ramping (w-20) dan lebar normal (w-64)
            if (sidebar.classList.contains('w-64')) {
                sidebar.classList.replace('w-64', 'w-20');
                
                // Sembunyikan semua teks deskriptif
                sidebarTexts.forEach(el => el.classList.add('hidden'));
                
                // Tutup paksa semua submenu yang terbuka
                document.querySelectorAll('.submenu-container').forEach(sub => {
                    sub.style.maxHeight = "0px";
                    const chevron = sub.previousElementSibling.querySelector('.sidebar-chevron');
                    if(chevron) chevron.classList.remove('rotate-90');
                });
            } else {
                sidebar.classList.replace('w-20', 'w-64');
                
                // Tampilkan kembali semua teks deskriptif
                sidebarTexts.forEach(el => el.classList.remove('hidden'));
            }
        });
    });
</script>s