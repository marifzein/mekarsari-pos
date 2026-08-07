<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>POS Kasir Mobile</title>
    
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    
    <!-- Remix Icon (Untuk ikon-ikon minimalis) -->
    <link href="https://cdn.jsdelivr.net/npm/remixicon@4.2.0/fonts/remixicon.css" rel="stylesheet">
    
    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    
    <!-- Alpine.js (Core & Focus plugin untuk kelancaran input) -->
    <script defer src="https://cdn.jsdelivr.net/npm/@alpinejs/focus@3.x.x/dist/cdn.min.js"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <style>
        [x-cloak] { display: none !important; }
        /* Hilangkan spinner input number agar tampilan rapi */
        input::-webkit-outer-spin-button,
        input::-webkit-inner-spin-button { -webkit-appearance: none; margin: 0; }
        input[type=number] { -moz-appearance: textfield; }
        body { background-color: #f8fafc; font-family: system-ui, -apple-system, sans-serif; }
    </style>
</head>
<body x-data="posKasir()">

    <!-- ========================================================== -->
    <!-- SIDEBAR HAMBURGER / DRAWER MOBILE (Slide dari Kiri) -->
    <!-- ========================================================== -->
    <div x-show="showMenu" class="fixed inset-0 z-50 flex" x-cloak>
        <!-- Backdrop Gelap -->
        <div x-show="showMenu" 
             x-transition:enter="transition-opacity ease-linear duration-200" 
             x-transition:enter-start="opacity-0" 
             x-transition:enter-end="opacity-100" 
             x-transition:leave="transition-opacity ease-linear duration-200" 
             x-transition:leave-start="opacity-100" 
             x-transition:leave-end="opacity-0" 
             @click="showMenu = false" 
             class="fixed inset-0 bg-black/50 backdrop-blur-xs"></div>

        <!-- Konten Menu -->
        <div x-show="showMenu" 
             x-transition:enter="transition ease-in-out duration-200 transform" 
             x-transition:enter-start="-translate-x-full" 
             x-transition:enter-end="translate-x-0" 
             x-transition:leave="transition ease-in-out duration-200 transform" 
             x-transition:leave-start="translate-x-0" 
             x-transition:leave-end="-translate-x-full" 
             class="relative flex flex-col w-72 max-w-xs bg-slate-900 text-slate-200 h-full p-5 shadow-2xl">
            
            <!-- Header Menu & Tombol Close -->
            <div class="flex justify-between items-center pb-4 border-b border-slate-800">
                <div class="flex items-center gap-2">
                    <i class="ri-store-2-fill text-indigo-400 text-xl"></i>
                    <span class="font-black text-sm tracking-wider text-white">MENU KASIR</span>
                </div>
                <button @click="showMenu = false" class="w-8 h-8 rounded-lg bg-slate-800 flex items-center justify-center text-slate-400 active:bg-slate-700">
                    <i class="ri-close-line text-lg"></i>
                </button>
            </div>

            <!-- List Navigasi Sesuai Request -->
            <div class="mt-4 flex-1 overflow-y-auto space-y-4">
                <!-- Kelompok Penjualan -->
                <div>
                    <span class="block text-[10px] uppercase font-bold tracking-widest text-slate-500 mb-1">Penjualan</span>
                    <div class="space-y-1">
                        <a href="/pos" class="flex items-center gap-3 px-3 py-2 bg-indigo-600 text-white text-xs font-bold rounded-xl"><i class="ri-computer-line text-base"></i> POS Utama</a>
                        <a href="/shift/close" class="flex items-center gap-3 px-3 py-2 hover:bg-slate-800 text-slate-400 hover:text-white text-xs font-semibold rounded-xl"><i class="ri-lock-password-line text-base"></i> Tutup Shift</a>
                        <a href="/transactions" class="flex items-center gap-3 px-3 py-2 hover:bg-slate-800 text-slate-400 hover:text-white text-xs font-semibold rounded-xl"><i class="ri-history-line text-base"></i> Riwayat Transaksi</a>
                    </div>
                </div>

                <!-- Kelompok Master Data -->
                <div>
                    <span class="block text-[10px] uppercase font-bold tracking-widest text-slate-500 mb-1">Master Data</span>
                    <div class="space-y-1">
                        <a href="/customers" class="flex items-center gap-3 px-3 py-2 hover:bg-slate-800 text-slate-400 hover:text-white text-xs font-semibold rounded-xl"><i class="ri-user-shared-line text-base"></i> Pelanggan</a>
                    </div>
                </div>

                <!-- Kelompok Inventory -->
                <div>
                    <span class="block text-[10px] uppercase font-bold tracking-widest text-slate-500 mb-1">Inventory</span>
                    <div class="space-y-1">
                        <a href="/stock-cards" class="flex items-center gap-3 px-3 py-2 hover:bg-slate-800 text-slate-400 hover:text-white text-xs font-semibold rounded-xl"><i class="ri-file-list-3-line text-base"></i> Kartu Stok</a>
                    </div>
                </div>

                <!-- Kelompok Laporan -->
                <div>
                    <span class="block text-[10px] uppercase font-bold tracking-widest text-slate-500 mb-1">Laporan</span>
                    <div class="space-y-1">
                        <a href="/reports/sales" class="flex items-center gap-3 px-3 py-2 hover:bg-slate-800 text-slate-400 hover:text-white text-xs font-semibold rounded-xl"><i class="ri-pie-chart-line text-base"></i> Penjualan Kasir</a>
                    </div>
                </div>
            </div>

            <!-- Identitas User / Admin Bawah -->
            <div class="pt-4 border-t border-slate-800 flex items-center gap-2.5">
                <div class="w-8 h-8 rounded-full bg-indigo-500 flex items-center justify-center font-bold text-white text-xs">A</div>
                <div class="min-w-0">
                    <p class="text-xs font-bold text-white leading-none truncate">Admin Kasir</p>
                    <span class="text-[10px] text-slate-500">Online</span>
                </div>
            </div>
        </div>
    </div>


    <!-- ========================================================== -->
    <!-- KONTEN APLIKASI UTAMA (POLOS & LUAS) -->
    <!-- ========================================================== -->
    <div class="flex flex-col min-h-screen px-3 py-3 select-none">
        
        <!-- TOPBAR MINIMALIS BARU -->
        <div class="flex items-center justify-between bg-white p-3 rounded-2xl shadow-xs border border-slate-100 mb-3">
            <div class="flex items-center gap-2.5">
                <!-- Tombol Hamburger Menu Utama -->
                <button @click="showMenu = true" type="button" class="w-9 h-9 bg-slate-100 active:bg-slate-200 rounded-xl flex items-center justify-center text-slate-700 active:scale-95 transition">
                    <i class="ri-menu-2-line text-xl"></i>
                </button>
                <div>
                    <h1 class="text-sm font-black text-slate-800 leading-none">POS KASIR</h1>
                    <span class="text-[9px] text-slate-400 font-mono">Nota: {{ $noNota }}</span>
                </div>
            </div>
            
            <!-- Tombol Pintasan Modal Cek Harga -->
            <button @click="showPriceModal = true; setTimeout(() => $refs.priceInput.focus(), 50)" class="w-9 h-9 bg-indigo-50 hover:bg-indigo-100 text-indigo-600 rounded-xl transition flex items-center justify-center active:scale-95">
                <i class="ri-price-tag-3-line text-lg"></i>
            </button>
        </div>

        <!-- SCROLLABLE BODY AREA (Batas aman `pb-36` agar tidak terpotong sticky bar bawah) -->
        <div class="flex-1 space-y-3 pb-36">
            
            <!-- 1. INPUT PENCARIAN / BARCODE SCANNER -->
            <div class="bg-white rounded-2xl p-3 shadow-xs border border-slate-100">
                <div class="relative">
                    <span class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                        <i class="ri-scan-2-line text-slate-400 text-base"></i>
                    </span>
                    <input
                        id="barcodeInput"
                        x-ref="barcodeInput"
                        type="text"
                        x-model="search"
                        @input="searchProduct"
                        @keydown.arrow-down.prevent="if(selectedIndex < products.length - 1) selectedIndex++"
                        @keydown.arrow-up.prevent="if(selectedIndex > 0) selectedIndex--"
                        @keydown.enter.prevent="if(products.length) addToCart(products[selectedIndex])"
                        placeholder="Scan barcode / ketik nama barang..."
                        class="w-full bg-slate-50 border-0 focus:ring-2 focus:ring-indigo-500 rounded-xl pl-9 pr-4 py-2.5 text-xs font-semibold outline-none transition placeholder:text-slate-400"
                    >
                    
                    <!-- Dropdown Hasil Pencarian Produk -->
                    <div x-show="products.length" class="absolute left-0 right-0 bg-white border border-slate-200 rounded-xl shadow-xl mt-1.5 z-40 max-h-52 overflow-y-auto divide-y divide-slate-100" x-cloak>
                        <template x-for="(product, index) in products" :key="product.id">
                            <div
                                 @click="addToCart(product)"
                                 :class="selectedIndex === index ? 'bg-indigo-50 border-l-4 border-indigo-600' : ''"
                                class="p-2.5 cursor-pointer flex justify-between items-center" 
                            >
                                <div>
                                    <div class="font-bold text-slate-800 text-xs" x-text="product.nama_barang"></div>
                                    <div class="text-[9px] font-mono text-slate-400" x-text="product.kode_barang"></div>
                                </div>
                                <div class="text-right">
                                    <div class="text-xs font-black text-indigo-600" x-text="'Rp' + formatRupiah(product.harga)"></div>
                                    <div class="text-[9px] text-slate-400" x-text="'Stok: ' + product.stok"></div>
                                </div>
                            </div>
                        </template>
                    </div>
                </div>
            </div>

            <!-- 2. PILIH DATA PELANGGAN (F8) -->
            <div class="bg-white rounded-2xl p-3 shadow-xs border border-slate-100">
                <div class="flex justify-between items-center mb-1.5">
                    <label class="text-[10px] font-bold uppercase tracking-wider text-slate-400">
                        <i class="ri-user-smile-line text-indigo-500 mr-0.5"></i> Pelanggan (F8)
                    </label>
                    <button type="button" @click="$dispatch('open-customer-modal')" class="text-[11px] text-indigo-600 font-bold hover:underline">+ Baru</button>
                </div>
                
                <div class="relative" @click.outside="customerResults=[]">
                    <input
                        x-ref="customerInput"
                        type="text"
                        x-model="customerSearch"
                        @keydown.arrow-down.prevent="moveCustomerDown()"
                        @keydown.arrow-up.prevent="moveCustomerUp()"
                        @keydown.enter.prevent="chooseCustomer()"
                        @keydown.escape.prevent="closeCustomerSearch()"
                        @input="searchCustomer()"
                        placeholder="Cari nama pelanggan..."
                        class="w-full bg-slate-50 border-0 focus:ring-2 focus:ring-indigo-500 text-xs rounded-xl px-3 py-2 outline-none transition placeholder:text-slate-400"
                    >

                    <!-- Dropdown Pencarian Pelanggan -->
                    <div x-show="customerResults.length" class="absolute left-0 right-0 top-full mt-1 bg-white border border-slate-200 rounded-xl shadow-lg z-40 max-h-40 overflow-y-auto divide-y divide-slate-100" x-cloak>
                        <template x-for="(customer, index) in customerResults" :key="customer.kode_pelanggan">
                            <div @click="selectCustomer(customer)" :class="customerIndex===index ? 'bg-indigo-50' : ''" class="px-3 py-2 cursor-pointer text-xs">
                                <div class="font-bold text-slate-800" x-text="customer.nama"></div>
                                <div class="text-[9px] text-slate-400" x-text="customer.alamat || 'Tidak ada alamat'"></div>
                            </div>
                        </template>
                    </div>
                </div>

                <!-- Banner kecil jika pelanggan dipilih -->
                <template x-if="selectedCustomer">
                    <div class="mt-2 rounded-xl bg-indigo-50 border border-indigo-100 p-2 flex items-center justify-between">
                        <div class="min-w-0">
                            <div class="font-bold text-indigo-950 text-xs" x-text="selectedCustomer.nama"></div>
                            <div class="text-[9px] text-indigo-700 truncate" x-text="selectedCustomer.alamat || 'Alamat nihil'"></div>
                        </div>
                        <button @click="clearCustomer()" class="text-[9px] text-red-500 font-bold bg-white shadow-3xs rounded-md px-2 py-1 flex-shrink-0 active:scale-95 transition">Lepas</button>
                    </div>
                </template>
            </div>

            <!-- 3. AREA DAFTAR KERANJANG BELANJA (KARTU VERTIKAL LEBAR PENUH) -->
            <div class="bg-white rounded-2xl p-3 shadow-xs border border-slate-100">
                <div class="flex justify-between items-center mb-2">
                    <h3 class="font-black text-slate-800 text-xs uppercase tracking-wider flex items-center gap-1">
                        <i class="ri-shopping-cart-2-line text-indigo-600"></i> Item Belanjaan 
                        <span class="text-[9px] px-1.5 py-0.2 bg-indigo-50 text-indigo-600 rounded-full font-extrabold" x-text="cart.length"></span>
                    </h3>
                    <button x-show="cart.length > 0" @click="clearCart()" type="button" class="text-[10px] text-red-500 font-bold">Kosongkan</button>
                </div>

                <!-- List Produk -->
                <div class="space-y-2">
                    <template x-for="(item, index) in cart" :key="item.id">
                        <div class="bg-slate-50 border border-slate-100 rounded-xl p-3 flex flex-col gap-1.5 shadow-2xs">
                            
                            <!-- Atas: Judul Produk & Tombol Hapus -->
                            <div class="flex justify-between items-start gap-2">
                                <div class="min-w-0">
                                    <div class="font-bold text-slate-800 text-xs sm:text-sm tracking-tight break-words" x-text="item.nama_barang"></div>
                                    <div class="text-[10px] font-bold text-slate-400 mt-0.5" x-text="'@Rp ' + item.harga.toLocaleString('id-ID')"></div>
                                </div>
                                <button @click="removeItem(item.id)" class="text-red-400 p-1 active:scale-90 transition">
                                    <i class="ri-delete-bin-6-line text-sm"></i>
                                </button>
                            </div>

                            <!-- Bawah: Subtotal & Pengatur Kuantitas Jumbo -->
                            <div class="flex justify-between items-center pt-2 border-t border-slate-200/60">
                                <div>
                                    <span class="text-[9px] font-bold text-slate-400 block uppercase">Subtotal</span>
                                    <span class="text-xs font-black text-indigo-600" x-text="'Rp ' + (item.qty * item.harga).toLocaleString('id-ID')"></span>
                                </div>

                                <!-- Stepper Ramah Jari Kasir -->
                                <div class="flex items-center bg-white border border-slate-200 rounded-lg p-0.5 shadow-3xs">
                                    <button type="button" @click="if(item.qty > 1) { item.qty--; calculateItem(item); }" class="w-7 h-7 rounded-md bg-slate-50 text-slate-600 flex items-center justify-center font-bold active:bg-slate-200">
                                        <i class="ri-subtract-line text-xs"></i>
                                    </button>
                                    <input
                                        type="number"
                                        min="1"
                                        x-model.number="item.qty"
                                        @change="validateQty(item)"
                                        @input="calculateItem(item)"
                                        class="w-8 border-0 text-center font-black text-xs text-slate-800 p-0 focus:ring-0"
                                    />
                                    <button type="button" @click="item.qty++; calculateItem(item);" class="w-7 h-7 rounded-md bg-slate-50 text-slate-600 flex items-center justify-center font-bold active:bg-slate-200">
                                        <i class="ri-add-line text-xs"></i>
                                    </button>
                                </div>
                            </div>

                        </div>
                    </template>

                    <!-- Kosong State -->
                    <div x-show="cart.length === 0" class="text-center py-6 text-slate-400 border border-dashed border-slate-200 rounded-xl bg-slate-50/50 text-xs">
                        Belum ada item masuk.
                    </div>
                </div>
            </div>

            <!-- 4. KOMPONEN BAYAR CASH / VOUCHER / CARD -->
            <div class="bg-white rounded-2xl p-3.5 shadow-xs border border-slate-100 space-y-2">
                <h3 class="text-[10px] font-bold uppercase tracking-wider text-slate-400 flex items-center gap-1">
                    <i class="ri-bank-card-2-line text-indigo-500"></i> Jenis Pembayaran
                </h3>

                <!-- Cash -->
                <div class="flex justify-between items-center gap-2">
                    <label for="cash" class="text-xs font-bold text-slate-700">Cash (F4)</label>
                    <div class="relative w-36">
                        <span class="absolute inset-y-0 left-0 flex items-center pl-2 text-slate-400 text-[10px] font-bold">Rp</span>
                        <input id="cash" type="number" min="0" x-model.number="cash" @input="recalculate()" class="text-right w-full bg-slate-50 border-0 focus:ring-2 focus:ring-indigo-500 rounded-lg pl-6 pr-2 py-1.5 text-xs font-bold text-slate-800">
                    </div>
                </div>

                <!-- Voucher -->
                <div class="flex justify-between items-center gap-2">
                    <label class="text-xs font-bold text-slate-700">Voucher</label>
                    <div class="relative w-36">
                        <span class="absolute inset-y-0 left-0 flex items-center pl-2 text-slate-400 text-[10px] font-bold">Rp</span>
                        <input type="number" min="0" x-model.number="voucher" @input="if(voucher<0) voucher=0; recalculate();" class="text-right w-full bg-slate-50 border-0 focus:ring-2 focus:ring-indigo-500 rounded-lg pl-6 pr-2 py-1.5 text-xs font-bold text-slate-800">
                    </div>
                </div>

                <!-- Card -->
                <div class="flex justify-between items-center gap-2">
                    <label class="text-xs font-bold text-slate-700">Card</label>
                    <div class="relative w-36">
                        <span class="absolute inset-y-0 left-0 flex items-center pl-2 text-slate-400 text-[10px] font-bold">Rp</span>
                        <input type="number" min="0" x-model.number="card" @input="recalculate();" class="text-right w-full bg-slate-50 border-0 focus:ring-2 focus:ring-indigo-500 rounded-lg pl-6 pr-2 py-1.5 text-xs font-bold text-slate-800">
                    </div>
                </div>
            </div>

        </div>

        <!-- ========================================================== -->
        <!-- FIX BANNER STICKY DI BAWAH HP (ANTI SEMBUNYI / KETUTUP) -->
        <!-- ========================================================== -->
        <div class="fixed bottom-0 left-0 right-0 bg-white border-t border-slate-200 shadow-2xl p-3 z-40 rounded-t-2xl">
            <div class="max-w-md mx-auto flex items-center justify-between gap-3">
                <div class="min-w-0">
                    <span class="text-[9px] font-bold text-slate-400 uppercase tracking-wide block">Total Tagihan</span>
                    <span class="text-lg font-black text-slate-800 tracking-tight block truncate" x-text="'Rp ' + formatRupiah(subtotal)"></span>
                    
                    <template x-if="kembalian > 0">
                        <span class="text-[10px] font-black text-green-600 block truncate" x-text="'Kembali: Rp ' + formatRupiah(kembalian)"></span>
                    </template>
                    <template x-if="kurangBayar > 0">
                        <span class="text-[10px] font-black text-red-500 block truncate" x-text="'Kurang: Rp ' + formatRupiah(kurangBayar)"></span>
                    </template>
                </div>
                
                <button type="button" @click="saveTransaction()" class="flex-1 bg-indigo-600 active:bg-indigo-700 text-white font-black text-xs py-3.5 px-4 rounded-xl flex items-center justify-center gap-1.5 shadow-md active:scale-95 transition-all">
                    <i class="ri-wallet-3-line text-sm"></i>
                    <span>BAYAR & SIMPAN</span>
                </button>
            </div>
        </div>


        <!-- ========================================================== -->
        <!-- MODAL MODAL DIALOG POPUP -->
        <!-- ========================================================== -->
        
        <!-- MODAL CEK HARGA (F3) -->
        <div x-show="showPriceModal" x-cloak class="fixed inset-0 bg-black/60 backdrop-blur-xs flex items-center justify-center p-3 z-50" @keydown.escape.window="closePriceModal()">
            <div class="bg-white rounded-2xl p-4 w-full max-w-sm shadow-2xl" @click.outside="closePriceModal()">
                <div class="flex justify-between items-center mb-3">
                    <h3 class="font-black text-slate-800 text-sm flex items-center gap-1">
                        <i class="ri-price-tag-2-line text-indigo-600"></i> Cek Harga Barang
                    </h3>
                    <button type="button" @click="closePriceModal()" class="w-7 h-7 rounded-lg bg-slate-50 text-slate-400 flex items-center justify-center">✕</button>
                </div>

                <input
                    x-ref="priceInput"
                    x-model="priceSearch"
                    @input="searchPrice()"
                    placeholder="Masukkan nama barang..."
                    class="w-full bg-slate-50 border-0 focus:ring-2 focus:ring-indigo-500 rounded-xl p-2 text-xs outline-none shadow-3xs"
                >

                <div class="mt-2.5 max-h-48 overflow-y-auto divide-y divide-slate-100 rounded-xl border border-slate-100">
                    <template x-for="item in priceResults" :key="item.id">
                        <div class="p-2.5 bg-slate-50/50 flex justify-between items-center">
                            <div>
                                <div class="font-bold text-slate-800 text-xs" x-text="item.nama_barang"></div>
                                <div class="text-[9px] text-slate-400" x-text="'Stok: ' + item.stok"></div>
                            </div>
                            <div class="text-xs font-black text-green-600" x-text="'Rp ' + Number(item.harga).toLocaleString('id-ID')"></div>
                        </div>
                    </template>
                </div>
            </div>
        </div>

        <!-- MODAL PELANGGAN BARU -->
        <div
            x-data="{ 
                showCustomerModal: false,
                newCustomer: { nama: '', telepon: '', alamat: '' },
                async saveNewCustomer() {
                    if (!this.newCustomer.nama.trim()) {
                        Swal.fire({ icon: 'warning', title: 'Perhatian', text: 'Nama pelanggan wajib diisi!', returnFocus: false });
                        return;
                    }
                    try {
                        let response = await fetch('/api/customers', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name=&quot;csrf-token&quot;]').content
                            },
                            body: JSON.stringify(this.newCustomer)
                        });
                        let result = await response.json();
                        if (result.success) {
                            window.dispatchEvent(new CustomEvent('customer-added', { detail: result.customer }));
                            this.newCustomer = { nama: '', telepon: '' , alamat: ''};
                            this.showCustomerModal = false;
                            Swal.fire({ icon: 'success', title: 'Berhasil!', text: 'Pelanggan disimpan', timer: 1200, showConfirmButton: false, returnFocus: false });
                        } else {
                            Swal.fire({ icon: 'error', title: 'Gagal', text: 'Gagal menyimpan data', returnFocus: false });
                        }
                    } catch (error) {
                        Swal.fire({ icon: 'error', title: 'Error', text: 'Kesalahan jaringan', returnFocus: false });
                    }
                }
            }"
            @open-customer-modal.window="showCustomerModal = true; $nextTick(() => $refs.newCustomerName.focus())"
            x-show="showCustomerModal"
            x-cloak
            class="fixed inset-0 bg-black/60 backdrop-blur-xs flex items-center justify-center p-3 z-50"
            @keydown.escape.window="showCustomerModal = false;"
        >
            <div class="bg-white rounded-2xl p-4 w-full max-w-sm shadow-2xl" @click.outside="showCustomerModal = false">
                <div class="flex justify-between items-center mb-3">
                    <h3 class="font-black text-sm text-slate-800 flex items-center gap-1">
                        <i class="ri-user-add-line text-indigo-600"></i> Pelanggan Baru
                    </h3>
                    <button type="button" @click="showCustomerModal = false" class="w-7 h-7 rounded-lg bg-slate-50 text-slate-400 flex items-center justify-center">✕</button>
                </div>

                <div class="space-y-2.5">
                    <div>
                        <label class="block text-[9px] font-bold text-slate-400 uppercase tracking-wide mb-0.5">Nama Lengkap</label>
                        <input x-ref="newCustomerName" type="text" x-model="newCustomer.nama" @keydown.enter.prevent="$refs.newCustomerPhone.focus()" class="w-full bg-slate-50 border-0 focus:ring-2 focus:ring-indigo-500 rounded-xl p-2 text-xs outline-none">
                    </div>
                    <div>
                        <label class="block text-[9px] font-bold text-slate-400 uppercase tracking-wide mb-0.5">No. HP</label>
                        <input x-ref="newCustomerPhone" type="text" x-model="newCustomer.telepon" @keydown.enter.prevent="$refs.newCustomerAlamat.focus()" class="w-full bg-slate-50 border-0 focus:ring-2 focus:ring-indigo-500 rounded-xl p-2 text-xs outline-none">
                    </div>
                    <div>
                        <label class="block text-[9px] font-bold text-slate-400 uppercase tracking-wide mb-0.5">Alamat</label>
                        <textarea x-ref="newCustomerAlamat" x-model="newCustomer.alamat" rows="2" class="w-full bg-slate-50 border-0 focus:ring-2 focus:ring-indigo-500 rounded-xl p-2 text-xs outline-none"></textarea>
                    </div>
                </div>

                <div class="mt-3 flex justify-end gap-2">
                    <button type="button" @click="showCustomerModal = false" class="px-3 py-1.5 bg-slate-100 text-slate-600 rounded-lg text-xs font-bold">Batal</button>
                    <button type="button" @click="saveNewCustomer()" class="px-3 py-1.5 bg-indigo-600 text-white rounded-lg text-xs font-bold">Simpan</button>
                </div>
            </div>
        </div>

    </div>

    <!-- CODE LOGIC FRONTEND (ALPINJS) -->
    <script>
    function posKasir() {
        return {
            showMenu: false, // State pembuka Hamburger Menu
            search: '',
            products: [],
            cart: [],
            cash: 0,
            voucher: 0,
            card: 0,
            diskon: 0,
            kurangBayar: 0,
            kembalian: 0,
            customerSearch: '',
            customerResults: [],
            selectedCustomer: null,
            allCustomers: window.ALL_CUSTOMERS || [],
            allProducts: window.ALL_PRODUCTS || [],
            showPriceModal: false,
            priceSearch: '',
            priceResults: [],
            selectedIndex: 0,
            customerIndex: -1,

            searchCustomer() {
                let keyword = this.customerSearch.toLowerCase().trim();
                if(keyword.length < 2) { this.customerResults = []; this.customerIndex = -1; return; }
                this.customerResults = this.allCustomers.filter(c => c.nama.toLowerCase().includes(keyword) || c.kode_pelanggan.toLowerCase().includes(keyword)).slice(0,5);
                this.customerIndex = -1;
            },
            moveCustomerDown() { if(this.customerResults.length===0) return; if(this.customerIndex < this.customerResults.length-1){ this.customerIndex++; } },
            moveCustomerUp() { if(this.customerResults.length===0) return; if(this.customerIndex>0) { this.customerIndex--; } },
            chooseCustomer() { if(this.customerIndex<0) return; this.selectCustomer(this.customerResults[this.customerIndex]); },
            selectCustomer(customer) { this.selectedCustomer = customer; this.customerSearch = customer.nama; this.customerResults=[]; this.customerIndex=-1; this.$nextTick(()=>{ document.getElementById('barcodeInput')?.focus(); }); },
            closeCustomerSearch() { this.customerResults=[]; this.customerIndex=-1; this.$nextTick(()=> { this.$refs.barcodeInput.focus(); }); },
            clearCustomer(){ this.selectedCustomer = null; this.customerSearch = ''; this.customerResults = []; this.$nextTick(() => { document.getElementById('barcodeInput')?.focus() }); },
            
            getDynamicPrice(product, qty) {
                let hargaEceran = Number(product.harga); 
                let potonganTerpilih = 0;
                let grosirList = product.productPrices || [];
                if (grosirList && grosirList.length > 0) {
                    let sortedGrosir = [...grosirList].sort((a, b) => Number(b.min_qty) - Number(a.min_qty));
                    let match = sortedGrosir.find(g => Number(qty) >= Number(g.min_qty));
                    if (match) { potonganTerpilih = Number(match.potongan); }
                }
                return hargaEceran - potonganTerpilih;
            },
            closePriceModal() { this.showPriceModal = false; this.priceSearch = ''; this.priceResults = []; setTimeout(() => { this.$refs.barcodeInput.focus(); }, 50); },
            formatRupiah(value) { return Number(value || 0).toLocaleString('id-ID'); },
            
            init() {
                window.addEventListener('customer-added', (e) => {
                    const newCustomer = e.detail;
                    this.allCustomers.push(newCustomer);
                    this.selectCustomer(newCustomer);
                });
                window.addEventListener('keydown', this.handleShortcut.bind(this));
                this.$nextTick(() => { this.$refs.barcodeInput.focus(); });
                this.recalculate();
            },
            
            handleShortcut(e) {
                if (typeof Swal !== 'undefined' && Swal.isVisible()) return;
                if(e.key === 'F2') { e.preventDefault(); this.$refs.barcodeInput.focus(); }
                if(e.key === 'F3') { e.preventDefault(); this.showPriceModal = true; setTimeout(() => { this.$refs.priceInput.focus(); }, 50); }
                if(e.key === 'F8') { e.preventDefault(); this.$refs.customerInput.focus(); }
                if(e.key === 'F4') { e.preventDefault(); document.getElementById('cash')?.focus(); }
                if(e.key === 'F10') { e.preventDefault(); this.saveTransaction(); }
            },
            
            recalculate() {
                this.subtotal = this.cart.reduce((total, item) => total + (Number(item.qty) * Number(item.harga)), 0);
                let paymentTotal = Number(this.cash || 0) + Number(this.voucher || 0) + Number(this.card || 0);
                this.kurangBayar = Math.max(0, this.subtotal - paymentTotal);
                this.kembalian   = Math.max(0, paymentTotal - this.subtotal);
            },
            
            removeItem(id) { this.cart = this.cart.filter(item => item.id !== id); this.recalculate(); },
            validateQty(item) { item.qty = parseInt(item.qty); if(isNaN(item.qty) || item.qty < 1){ item.qty = 1; } },
            calculateItem(item) { item.qty = Number(item.qty); if (item._originalProduct) { item.harga = this.getDynamicPrice(item._originalProduct, item.qty); } this.recalculate(); },
            
            addToCart(product) {
                let found = this.cart.find(item => item.id === product.id);
                if(found) { found.qty++; found.harga = this.getDynamicPrice(found._originalProduct, found.qty); }
                else { let initialPrice = this.getDynamicPrice(product, 1); this.cart.push({ id: product.id, kode_barang: product.kode_barang, nama_barang: product.nama_barang, harga: initialPrice, qty: 1, _originalProduct: product }); }
                this.search = ''; this.products = []; this.selectedIndex = 0; this.recalculate(); this.$nextTick(() => { this.$refs.barcodeInput.focus(); });
            },
            
            searchProduct() {
                let q = this.search.toLowerCase().trim(); if(q.length < 1) { this.products = []; return; }
                this.products = this.allProducts.filter(product => (product.nama_barang || '').toLowerCase().includes(q) || (product.kode_barang || '').toLowerCase().includes(q)).slice(0,6); 
                this.selectedIndex = 0;
            },
            
            async clearCart() {
                const result = await Swal.fire({ text: 'Kosongkan keranjang?', icon: 'warning', showCancelButton: true, confirmButtonText: 'Ya', cancelButtonText: 'Batal', returnFocus: false });
                if(result.isConfirmed) { this.cart = []; this.cash = 0; this.voucher = 0; this.card = 0; this.recalculate(); this.$refs.barcodeInput.focus(); }
            },  
            
            async saveTransaction() {
                let paymentTotal = Number(this.cash || 0) + Number(this.voucher || 0) + Number(this.card || 0);
                if(this.cart.length === 0) { Swal.fire({ icon: 'warning', text: 'Keranjang kosong!', returnFocus: false }); return; }
                if(paymentTotal < this.subtotal) { Swal.fire({ icon: 'warning', text: 'Pembayaran kurang!', returnFocus: false }); return; }
                
                const konfirmasi = await Swal.fire({ text: 'Simpan transaksi?', icon: 'question', showCancelButton: true, confirmButtonText: 'Ya', returnFocus: false });
                if (!konfirmasi.isConfirmed) return;

                let response = await fetch('/api/transactions', { 
                    method:'POST', 
                    headers:{ 'Content-Type': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content }, 
                    body:JSON.stringify({ 
                        pelanggan: this.selectedCustomer ? this.selectedCustomer.kode_pelanggan : null, 
                        cart:this.cart, subtotal: this.subtotal, voucher: this.voucher, card: this.card, grand_total: this.subtotal, cash: this.cash, kembalian: this.kembalian 
                    }) 
                });
                let result = await response.json();
                if(result.success) { 
                    window.open('/transactions/' + result.transaction_id + '/print', '_blank'); 
                    Swal.fire({ text: 'Berhasil disimpan', icon: 'success', timer: 1000, showConfirmButton: false }); 
                    setTimeout(() => { location.reload(); }, 1000); 
                } else { 
                    Swal.fire({ icon: 'error', text: result.message, returnFocus: false }); 
                }
            },
            searchPrice() { let q = this.priceSearch.trim(); if(q.length < 1) { this.priceResults = []; return; } fetch(`/api/products/search?q=${encodeURIComponent(q)}`).then(r => r.json()).then(data => { this.priceResults = data; }); },
        }
    }
    window.ALL_PRODUCTS = @json($products);
    window.ALL_CUSTOMERS = @json($customers);
    </script>
</body>
</html>