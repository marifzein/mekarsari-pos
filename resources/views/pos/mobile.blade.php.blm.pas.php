@extends('layouts.app')

@section('title','POS Kasir Mobile')

@section('content')

    <!-- KEPALA HALAMAN (Ringkas & Bersih untuk Mobile) -->
    <div class="flex items-center justify-between mb-3 px-2">
        <div>
            <h2 class="text-xl font-black text-slate-800 tracking-tight">POS KASIR</h2>
            <p class="text-xs text-slate-400">Nota: <span class="font-mono font-bold text-slate-600">{{ $noNota }}</span></p>
        </div>
        <div class="flex gap-2">
            <!-- Tombol Cek Harga Cepat -->
            <button @click="showPriceModal = true; setTimeout(() => $refs.priceInput.focus(), 50)" class="p-2.5 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-xl transition flex items-center gap-1 text-sm font-medium">
                <i class="ri-price-tag-3-line text-lg text-indigo-600"></i>
                <span class="hidden sm:inline">Cek Harga (F3)</span>
            </button>
        </div>
    </div>

    <div class="max-w-7xl mx-auto p-1 sm:p-2" x-data="posKasir()">
        
        <!-- GRID UTAMA: Otomatis 1 kolom di HP, 12 kolom di Tablet/iPad -->
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-4 pb-32 lg:pb-4">

            <!-- AREA KIRI (7 Kolom di Tablet): Fokus Scan & Daftar Barang -->
            <div class="lg:col-span-7 space-y-3">
                
                <!-- INPUT SCANNER / CARI BARANG (Ukuran Jumbo Ramah Jempol) -->
                <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-3 sticky top-2 z-30">
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 flex items-center pl-3.5 pointer-events-none">
                            <i class="ri-scan-2-line text-slate-400 text-xl animate-pulse"></i>
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
                            placeholder="Tembak barcode atau ketik nama produk..."
                            class="w-full bg-slate-50 border-0 focus:ring-2 focus:ring-indigo-500 rounded-xl pl-11 pr-4 py-3.5 text-base font-medium shadow-inner outline-none transition-all placeholder:text-slate-400"
                        >
                        
                        <!-- Dropdown Hasil Pencarian Produk -->
                        <div x-show="products.length" class="absolute left-0 right-0 bg-white border border-slate-200 rounded-xl shadow-xl mt-2 z-50 max-h-64 overflow-y-auto divide-y divide-slate-100" x-cloak>
                            <template x-for="(product,index) in products" :key="product.id">
                                <div
                                     @click="addToCart(product)"
                                     :class="selectedIndex === index ? 'bg-indigo-50 border-l-4 border-indigo-600' : ''"
                                    class="p-3 cursor-pointer hover:bg-slate-50 transition flex justify-between items-center" 
                                >
                                    <div>
                                        <div class="font-bold text-slate-800 text-sm" x-text="product.nama_barang"></div>
                                        <div class="text-xs font-mono text-slate-400" x-text="product.kode_barang"></div>
                                    </div>
                                    <div class="text-right">
                                        <div class="text-sm font-extrabold text-indigo-600" x-text="'Rp' + formatRupiah(product.harga)"></div>
                                        <div class="text-[10px] text-slate-400" x-text="'Stok: ' + product.stok"></div>
                                    </div>
                                </div>
                            </template>
                        </div>
                    </div>
                </div>

                <!-- KERANJANG BELANJA (Card-Based List) -->
                <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-4">
                    <div class="flex justify-between items-center mb-3">
                        <h3 class="font-black text-slate-800 text-base flex items-center gap-1.5">
                            <i class="ri-shopping-cart-2-line text-indigo-600"></i> Item Belanjaan
                            <span class="text-xs px-2 py-0.5 bg-indigo-50 text-indigo-600 rounded-full font-bold" x-text="cart.length + ' Item'"></span>
                        </h3>
                        <button x-show="cart.length > 0" @click="clearCart()" type="button" class="text-xs text-red-500 hover:text-red-700 font-semibold transition flex items-center gap-0.5">
                            <i class="ri-delete-bin-4-line"></i> Kosongkan
                        </button>
                    </div>

                    <!-- List Produk di HP/Tablet -->
                    <div class="space-y-2.5">
                        <template x-for="(item, index) in cart" :key="item.id">
                            <div class="bg-slate-50/70 border border-slate-100 rounded-xl p-3 flex items-center justify-between gap-3 shadow-2xs">
                                <!-- Nama & Informasi Detail -->
                                <div class="flex-1 min-w-0">
                                    <div class="font-bold text-slate-800 text-sm truncate" x-text="item.nama_barang"></div>
                                    <div class="flex items-center gap-2 mt-0.5">
                                        <span class="text-xs font-extrabold text-slate-700" x-text="'@Rp' + item.harga.toLocaleString('id-ID')"></span>
                                    </div>
                                    <!-- Total per item -->
                                    <div class="text-xs font-black text-indigo-600 mt-1" x-text="'Subtotal: Rp' + (item.qty * item.harga).toLocaleString('id-ID')"></div>
                                </div>

                                <!-- Kontrol Kuantitas Khas Mobile (+ dan - Gede) -->
                                <div class="flex items-center bg-white border border-slate-200 rounded-xl p-1 shadow-2xs">
                                    <button type="button" @click="if(item.qty > 1) { item.qty--; calculateItem(item); }" class="w-8 h-8 rounded-lg bg-slate-50 hover:bg-red-50 active:bg-red-100 text-slate-600 hover:text-red-600 flex items-center justify-center font-bold transition">
                                        <i class="ri-subtract-line"></i>
                                    </button>
                                    
                                    <input
                                        type="number"
                                        min="1"
                                        x-model.number="item.qty"
                                        @change="validateQty(item)"
                                        @input="calculateItem(item)"
                                        class="w-12 border-0 text-center font-black text-sm text-slate-800 p-0 focus:ring-0"
                                    />
                                    
                                    <button type="button" @click="item.qty++; calculateItem(item);" class="w-8 h-8 rounded-lg bg-slate-50 hover:bg-green-50 active:bg-green-100 text-slate-600 hover:text-green-600 flex items-center justify-center font-bold transition">
                                        <i class="ri-add-line"></i>
                                    </button>
                                </div>

                                <!-- Tombol Hapus Pojok Kanan -->
                                <button @click="removeItem(item.id)" class="w-9 h-9 text-red-500 hover:bg-red-50 rounded-xl flex items-center justify-center transition">
                                    <i class="ri-delete-bin-line text-lg"></i>
                                </button>
                            </div>
                        </template>

                        <!-- Tampilan Jika Cart Kosong -->
                        <div x-show="cart.length === 0" class="text-center py-12 text-slate-400 border-2 border-dashed border-slate-200 rounded-xl bg-slate-50/50">
                            <i class="ri-inbox-archive-line text-4xl block mb-2 text-slate-300"></i>
                            <p class="text-sm font-medium">Keranjang masih kosong, siap melayani!</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- AREA KANAN (5 Kolom di Tablet): Fokus Pelanggan & Pembayaran -->
            <div class="lg:col-span-5 space-y-3">
                
                <!-- BAGIAN DATA PELANGGAN -->
                <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-4">
                    <div class="flex justify-between items-center mb-2">
                        <label class="block text-xs font-bold uppercase tracking-wider text-slate-500">
                            <i class="ri-user-smile-line text-indigo-500 mr-0.5"></i> Pelanggan (F8)
                        </label>
                        <button type="button" @click="$dispatch('open-customer-modal')" class="text-xs text-indigo-600 font-bold hover:underline">
                            + Baru
                        </button>
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
                            placeholder="Cari nama / kode pelanggan..."
                            class="w-full bg-slate-50 border-0 focus:ring-2 focus:ring-indigo-500 text-sm rounded-xl px-4 py-2.5 shadow-2xs placeholder:text-slate-400 outline-none transition"
                        >

                        <!-- Dropdown Pencarian Pelanggan -->
                        <div x-show="customerResults.length" class="absolute left-0 right-0 top-full mt-2 bg-white border border-slate-200 rounded-xl shadow-xl z-50 max-h-56 overflow-y-auto divide-y divide-slate-100" x-cloak>
                            <template x-for="(customer,index) in customerResults" :key="customer.kode_pelanggan">
                                <div
                                    @click="selectCustomer(customer)"
                                    :class="customerIndex===index ? 'bg-indigo-50' : ''"
                                    class="px-4 py-3 cursor-pointer hover:bg-slate-50 transition"
                                >
                                    <div class="font-bold text-slate-800 text-sm" x-text="customer.nama"></div>
                                    <div class="text-xs text-slate-400" x-text="customer.alamat || customer.telepon || 'Tidak ada alamat/telp'"></div>
                                </div>
                            </template>
                        </div>
                    </div>

                    <!-- Info Pelanggan Terpilih -->
                    <template x-if="selectedCustomer">
                        <div class="mt-2.5 rounded-xl bg-indigo-50/70 border border-indigo-100/50 p-3 relative">
                            <div class="font-black text-indigo-900 text-sm" x-text="selectedCustomer.nama"></div>
                            <div class="text-xs text-indigo-700/80 mt-0.5 truncate" x-text="selectedCustomer.alamat || 'Alamat tidak diisi'"></div>
                            <div class="text-[10px] text-indigo-500 font-mono mt-0.5" x-show="selectedCustomer.telepon" x-text="'Telp: ' + selectedCustomer.telepon"></div>
                            <button @click="clearCustomer()" class="absolute top-2.5 right-2.5 text-xs text-red-500 font-bold hover:text-red-700 bg-white shadow-2xs rounded-lg px-2 py-0.5">
                                Lepas
                            </button>
                        </div>
                    </template>
                </div>

                <!-- BAGIAN FORM PEMBAYARAN UTAMA (Desktop & Tablet Area) -->
                <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-4 hidden lg:block">
                    <h3 class="text-sm font-bold uppercase tracking-wider text-slate-500 mb-3 flex items-center gap-1">
                        <i class="ri-bank-card-2-line text-indigo-500"></i> Rincian Pembayaran
                    </h3>

                    <div class="space-y-3">
                        <div class="flex justify-between items-center py-1">
                            <span class="text-slate-500 font-medium">Subtotal</span>
                            <span class="font-black text-xl text-slate-800" x-text="'Rp ' + formatRupiah(subtotal)"></span>
                        </div>
                        
                        <hr class="border-slate-100">

                        <!-- Input Cash -->
                        <div class="flex justify-between items-center gap-4">
                            <label for="cash" class="text-sm font-bold text-slate-700 flex-shrink-0">Cash (F4)</label>
                            <div class="relative w-44">
                                <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-slate-400 text-xs font-bold">Rp</span>
                                <input id="cash" type="number" min="0" x-model.number="cash" @input="recalculate()" class="text-right w-full bg-slate-50 border-0 focus:ring-2 focus:ring-indigo-500 rounded-xl pl-8 pr-3 py-2 text-sm font-bold text-slate-800 shadow-2xs">
                            </div>
                        </div>

                        <!-- Input Voucher -->
                        <div class="flex justify-between items-center gap-4">
                            <label class="text-sm font-bold text-slate-700 flex-shrink-0">Voucher</label>
                            <div class="relative w-44">
                                <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-slate-400 text-xs font-bold">Rp</span>
                                <input type="number" min="0" x-model.number="voucher" @input="if(voucher<0) voucher=0; recalculate();" class="text-right w-full bg-slate-50 border-0 focus:ring-2 focus:ring-indigo-500 rounded-xl pl-8 pr-3 py-2 text-sm font-bold text-slate-800 shadow-2xs">
                            </div>
                        </div>

                        <!-- Input Card -->
                        <div class="flex justify-between items-center gap-4">
                            <label class="text-sm font-bold text-slate-700 flex-shrink-0">Card</label>
                            <div class="relative w-44">
                                <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-slate-400 text-xs font-bold">Rp</span>
                                <input type="number" min="0" x-model.number="card" @input="recalculate();" class="text-right w-full bg-slate-50 border-0 focus:ring-2 focus:ring-indigo-500 rounded-xl pl-8 pr-3 py-2 text-sm font-bold text-slate-800 shadow-2xs">
                            </div>
                        </div>

                        <hr class="border-slate-100">

                        <!-- Kurang Bayar -->
                        <div class="flex justify-between items-center text-sm">
                            <span class="font-bold text-slate-500">Kurang Bayar</span>
                            <span class="font-black" :class="kurangBayar > 0 ? 'text-red-600 text-base' : 'text-slate-700'" x-text="'Rp ' + formatRupiah(kurangBayar)"></span>
                        </div>

                        <!-- Kembalian -->
                        <div class="flex justify-between items-center">
                            <span class="font-bold text-green-600">Kembalian</span>
                            <span class="font-black text-xl text-green-600" x-text="'Rp ' + formatRupiah(kembalian)"></span>
                        </div>

                        <!-- Tombol Eksekusi -->
                        <button @click="saveTransaction()" class="w-full mt-2 bg-indigo-600 hover:bg-indigo-700 text-white py-3.5 rounded-xl font-bold tracking-wide transition shadow-md active:scale-[0.99]">
                            <i class="ri-save-3-line text-lg mr-1.5 align-middle"></i>SIMPAN TRANSAKSI (F10)
                        </button>
                    </div>
                </div>

                <!-- INFO HELP BANTUAN DI TABLET -->
                <div class="bg-white rounded-2xl border border-slate-100 p-4 hidden md:block">
                    <h4 class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-2">Bantuan Cepat Keyboard</h4>
                    <div class="grid grid-cols-2 gap-x-4 gap-y-1 text-xs font-mono text-slate-500">
                        <div class="flex justify-between border-b border-slate-50 py-0.5"><span>[F2]</span> <span class="text-slate-400">Scan</span></div>
                        <div class="flex justify-between border-b border-slate-50 py-0.5"><span>[F3]</span> <span class="text-slate-400">Harga</span></div>
                        <div class="flex justify-between border-b border-slate-50 py-0.5"><span>[F4]</span> <span class="text-slate-400">Bayar Cash</span></div>
                        <div class="flex justify-between border-b border-slate-50 py-0.5"><span>[F10]</span> <span class="text-slate-400">Simpan</span></div>
                    </div>
                </div>
            </div>

        </div>

        <!-- ========================================================== -->
        <!-- STICKY BOTTOM BAR (HANYA MUNCUL DI HP / SCREEN MOBILE) -->
        <!-- ========================================================== -->
        <div class="fixed bottom-0 left-0 right-0 bg-white border-t border-slate-200/80 shadow-2xl p-3 z-40 lg:hidden rounded-t-3xl">
            <div class="max-w-md mx-auto flex items-center justify-between gap-3">
                <!-- Info Total Belanja Ringkas -->
                <div class="min-w-0">
                    <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wide block">Total Tagihan</span>
                    <span class="text-2xl font-black text-slate-800 tracking-tight block truncate" x-text="'Rp' + formatRupiah(subtotal)"></span>
                    
                    <!-- Status Kembalian / Kurang Bayar Kecil -->
                    <template x-if="kembalian > 0">
                        <span class="text-[11px] font-black text-green-600" x-text="'Kembali: Rp' + formatRupiah(kembalian)"></span>
                    </template>
                    <template x-if="kurangBayar > 0">
                        <span class="text-[11px] font-black text-red-500" x-text="'Kurang: Rp' + formatRupiah(kurangBayar)"></span>
                    </template>
                </div>
                
                <!-- Tombol Trigger Bayar / Buka Modal Pembayaran di Mobile -->
                <button type="button" @click="saveTransaction()" class="flex-1 bg-indigo-600 hover:bg-indigo-700 text-white font-extrabold text-sm py-3 px-4 rounded-xl flex items-center justify-center gap-1.5 shadow-md transition-all active:scale-95">
                    <i class="ri-wallet-3-line text-lg"></i>
                    <span>Bayar & Simpan</span>
                </button>
            </div>
        </div>

        <!-- MODAL CEK HARGA (F3) -->
        <div x-show="showPriceModal" x-cloak class="fixed inset-0 bg-black/60 backdrop-blur-xs flex items-center justify-center p-3 z-50 animate-fade-in" @keydown.escape.window="closePriceModal()">
            <div class="bg-white rounded-2xl p-5 w-full max-w-lg shadow-2xl transform transition-all" @click.outside="closePriceModal()">
                <div class="flex justify-between items-center mb-3">
                    <h3 class="font-black text-slate-800 text-lg flex items-center gap-1.5">
                        <i class="ri-price-tag-2-line text-indigo-600"></i> Cek Harga Barang
                    </h3>
                    <button type="button" @click="closePriceModal()" class="w-8 h-8 rounded-lg bg-slate-50 text-slate-400 hover:text-slate-600 flex items-center justify-center">✕</button>
                </div>

                <input
                    x-ref="priceInput"
                    x-model="priceSearch"
                    @input="searchPrice()"
                    placeholder="Ketik nama / scan barcode barang..."
                    class="w-full bg-slate-50 border-0 focus:ring-2 focus:ring-indigo-500 rounded-xl p-3 text-base shadow-inner outline-none transition"
                >

                <div class="mt-3 max-h-64 overflow-y-auto divide-y divide-slate-100 rounded-xl border border-slate-100">
                    <template x-for="item in priceResults" :key="item.id">
                        <div class="p-3 bg-slate-50/50 hover:bg-slate-50 transition flex justify-between items-center">
                            <div>
                                <div class="font-bold text-slate-800 text-sm" x-text="item.nama_barang"></div>
                                <div class="text-[11px] font-mono text-slate-400" x-text="'Stok: ' + item.stok"></div>
                            </div>
                            <div class="text-base font-black text-green-600" x-text="'Rp ' + Number(item.harga).toLocaleString('id-ID')"></div>
                        </div>
                    </template>
                </div>
            </div>
        </div>

        <!-- MODAL TAMBAH PELANGGAN BARU (ISOLATED INLINE SCOPE) -->
        <div
            x-data="{ 
                showCustomerModal: false,
                newCustomer: { nama: '', telepon: '', alamat: '' },
                async saveNewCustomer() {
                    if (!this.newCustomer.nama.trim()) {
                        Swal.fire({ icon: 'warning', title: 'Perhatian', text: 'Nama pelanggan wajib diisi!' });
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
                            
                            Swal.fire({
                                icon: 'success',
                                title: 'Berhasil!',
                                text: 'Pelanggan berhasil disimpan',
                                timer: 1500,
                                showConfirmButton: false
                            });
                        } else {
                            Swal.fire({ icon: 'error', title: 'Gagal', text: 'Gagal menyimpan pelanggan' });
                        }
                    } catch (error) {
                        console.error(error);
                        Swal.fire({ icon: 'error', title: 'Error', text: 'Terjadi kesalahan sistem' });
                    }
                }
            }"
            @open-customer-modal.window="showCustomerModal = true; $nextTick(() => $refs.newCustomerName.focus())"
            x-show="showCustomerModal"
            x-cloak
            class="fixed inset-0 bg-black/60 backdrop-blur-xs flex items-center justify-center p-3 z-50"
            @keydown.escape.window="showCustomerModal = false;"
        >
            <div class="bg-white rounded-2xl p-5 w-full max-w-md shadow-2xl transform transition-all" @click.outside="showCustomerModal = false">
                <div class="flex justify-between items-center mb-3">
                    <h3 class="font-black text-lg text-slate-800 flex items-center gap-1">
                        <i class="ri-user-add-line text-indigo-600"></i> Pelanggan Baru
                    </h3>
                    <button type="button" @click="showCustomerModal = false" class="w-8 h-8 rounded-lg bg-slate-50 text-slate-400 hover:text-slate-600 flex items-center justify-center">✕</button>
                </div>

                <!-- Form Body -->
                <div class="space-y-3.5">
                    <div>
                        <label class="block text-xs font-bold text-slate-500 uppercase tracking-wide mb-1">Nama Lengkap</label>
                        <input x-ref="newCustomerName" type="text" x-model="newCustomer.nama" @keydown.enter.prevent="$refs.newCustomerPhone.focus()" placeholder="Masukkan nama pelanggan..." class="w-full bg-slate-50 border-0 focus:ring-2 focus:ring-indigo-500 rounded-xl p-2.5 text-sm shadow-inner outline-none">
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-500 uppercase tracking-wide mb-1">No. Telepon / HP</label>
                        <input x-ref="newCustomerPhone" type="text" x-model="newCustomer.telepon" @keydown.enter.prevent="$refs.newCustomerAlamat.focus()" placeholder="Contoh: 081234567xx (Opsional)" class="w-full bg-slate-50 border-0 focus:ring-2 focus:ring-indigo-500 rounded-xl p-2.5 text-sm shadow-inner outline-none">
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-500 uppercase tracking-wide mb-1">Alamat</label>
                        <textarea x-ref="newCustomerAlamat" x-model="newCustomer.alamat" rows="2" placeholder="Masukkan alamat lengkap... (Opsional)" class="w-full bg-slate-50 border-0 focus:ring-2 focus:ring-indigo-500 rounded-xl p-2.5 text-sm shadow-inner outline-none"></textarea>
                    </div>
                </div>

                <!-- Form Action Buttons -->
                <div class="mt-5 flex justify-end gap-2.5">
                    <button type="button" @click="showCustomerModal = false" class="px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-600 rounded-xl text-xs font-bold transition">Batal</button>
                    <button type="button" @click="saveNewCustomer()" class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl text-xs font-bold shadow-sm transition">Simpan Pelanggan</button>
                </div>
            </div>
        </div>

    </div>

<script>
// Seluruh isi fungsi js posKasir() kamu tetap sama persis di sini seperti bawaannya...
// (Sengaja tidak dipotong/diubah agar script internal logic data Alpine tetap bekerja sempurna, Bos)
function posKasir() {
    return {
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
        allCustomers: window.ALL_CUSTOMERS,
        cashDisplay: '',
        allProducts: window.ALL_PRODUCTS,
        showPriceModal: false,
        priceSearch: '',
        priceResults: [],

        searchCustomer() {
            let keyword = this.customerSearch.toLowerCase().trim();
            if(keyword.length < 2) { this.customerResults = []; this.customerIndex = -1; return; }
            this.customerResults = this.allCustomers.filter(c => c.nama.toLowerCase().includes(keyword) || c.kode_pelanggan.toLowerCase().includes(keyword)).slice(0,8);
            this.customerIndex = -1;
        },
        moveCustomerDown() { if(this.customerResults.length===0) return; if(this.customerIndex < this.customerResults.length-1){ this.customerIndex++; } },
        moveCustomerUp() { if(this.customerResults.length===0) return; if(this.customerIndex>0) { this.customerIndex--; } },
        chooseCustomer() { if(this.customerIndex<0) return; this.selectCustomer(this.customerResults[this.customerIndex]); },
        selectCustomer(customer) { this.selectedCustomer = customer; this.customerSearch = customer.nama; this.customerResults=[]; this.customerIndex=-1; this.$nextTick(()=>{ document.getElementById('barcodeInput')?.focus(); }); },
        closeCustomerSearch() { this.customerResults=[]; this.customerIndex=-1; this.$nextTick(()=> { this.$refs.barcodeInput.focus(); }); },
        clearCustomer(){ this.selectedCustomer = null; this.customerSearch = ''; this.customerResults = []; this.$nextTick(() => { document.getElementById('barcodeInput')?.focus() }); },
        getDynamicPrice(product, qty) {
            let hargaEceran = Number(product.harga); let potonganTerpilih = 0;
            let grosirList = product.productPrices || []; 
            if (grosirList && grosirList.length > 0) {
                let sortedGrosir = [...grosirList].sort((a, b) => Number(b.min_qty) - Number(a.min_qty));
                let match = sortedGrosir.find(g => Number(qty) >= Number(g.min_qty));
                if (match) { potonganTerpilih = Number(match.potongan); }
            }
            return hargaEceran - potonganTerpilih;
        },
        get grandTotal() { return this.subtotal - this.diskon; },
        closePriceModal() { this.showPriceModal = false; this.priceSearch = ''; this.priceResults = []; setTimeout(() => { this.$refs.barcodeInput.focus(); }, 50); },
        updateCash(value) { const raw = value.replace(/[^\d]/g,''); this.cash = parseInt(raw || 0); this.cashDisplay = this.cash.toLocaleString('id-ID'); this.recalculate(); },
        formatRupiah(value) { return Number(value || 0).toLocaleString('id-ID'); },
        init() {
            window.addEventListener('customer-added', (e) => {
                const newCustomer = e.detail;
                if (window.ALL_CUSTOMERS) { window.ALL_CUSTOMERS.push(newCustomer); }
                if (this.allCustomers) { this.allCustomers.push(newCustomer); }
                if (typeof this.selectCustomer === 'function') { this.selectCustomer(newCustomer); } else { this.selectedCustomer = newCustomer; this.customerSearch = newCustomer.nama; this.customerResults = []; }
            });
            window.addEventListener('keydown', this.handleShortcut.bind(this));
            this.$nextTick(() => { this.$refs.barcodeInput.focus(); });
            this.recalculate();
        },
        handleShortcut(e) {
            if (typeof Swal !== 'undefined' && Swal.isVisible()) { return; }
            if(e.key === 'F2') { e.preventDefault(); this.$refs.barcodeInput.focus(); this.$refs.barcodeInput.select(); }
            if(e.key === 'F3') { e.preventDefault(); this.showPriceModal = true; setTimeout(() => { this.$refs.priceInput.focus(); }, 50); return; }
            if(e.key === 'F8') { e.preventDefault(); this.$refs.customerInput.focus(); this.$refs.customerInput.select(); return; }
            if(e.key === 'Escape' && this.customerResults.length) { e.preventDefault(); this.closeCustomerSearch(); return; }
            if(e.key === 'F4') { e.preventDefault(); document.getElementById('cash')?.focus(); document.getElementById('cash')?.select(); }
            if(e.key === 'F10') { e.preventDefault(); this.saveTransaction(); }
            if(e.ctrlKey && e.key === 'Delete') { e.preventDefault(); this.clearCart(); }
        },
        subtotal: 0, paymentTotal: 0, kurangBayar: 0, kembalian: 0,
        recalculate() {
            this.subtotal = this.cart.reduce((total, item) => total + (Number(item.qty) * Number(item.harga)), 0);
            this.paymentTotal = Number(this.cash || 0) + Number(this.voucher || 0) + Number(this.card || 0);
            this.kurangBayar = Math.max(0, this.subtotal - this.paymentTotal);
            this.kembalian   = Math.max(0, this.paymentTotal - this.subtotal);
        },
        removeItem(id) { this.cart = this.cart.filter(item => item.id !== id); this.$nextTick(() => { this.recalculate(); }); },
        validateQty(item) { item.qty = parseInt(item.qty); if(isNaN(item.qty) || item.qty < 1){ item.qty = 1; } },
        calculateItem(item) { item.qty = Number(item.qty); if (item._originalProduct) { item.harga = this.getDynamicPrice(item._originalProduct, item.qty); } item.total = item.qty * item.harga; this.$nextTick(() => this.recalculate()); },
        addToCart(product) {
            let found = this.cart.find(item => item.id === product.id);
            if(found) { found.qty++; found.harga = this.getDynamicPrice(found._originalProduct, found.qty); }
            else { let initialPrice = this.getDynamicPrice(product, 1); this.cart.push({ id: product.id, kode_barang: product.kode_barang, nama_barang: product.nama_barang, harga: initialPrice, qty: 1, _originalProduct: product }); }
            this.search = ''; this.products = []; this.selectedIndex = 0; this.recalculate(); this.$nextTick(() => { document.getElementById('barcodeInput')?.focus() });
        },
        searchProduct() {
            let q = this.search.toLowerCase().trim(); if(q.length < 1) { this.products = []; return; }
            this.products = this.allProducts.filter(product => (product.nama_barang || '').toLowerCase().includes(q) || (product.kode_barang || '').toLowerCase().includes(q) || (product.barcode || '') .toLowerCase().includes(q) ).slice(0,10); this.selectedIndex = 0;
        },
        async clearCart() {
            const result = await Swal.fire({ icon: 'warning', title: 'Kosongkan Cart?', text: 'Semua item akan dihapus', showCancelButton: true, confirmButtonText: 'Ya', cancelButtonText: 'Batal', returnFocus: false });
            if(result.isConfirmed) { this.cart = []; this.$nextTick(() => { this.recalculate(); this.cash = 0; this.voucher = 0; this.card = 0; this.kembalian=0; }); this.$refs.barcodeInput.focus(); }
        },  
        async saveTransaction() {
            if(this.cart.length === 0) { await Swal.fire({ icon: 'warning', title: 'Perhatian', text: 'Keranjang belanja kosong !', confirmButtonText: 'OK', returnFocus: false }); setTimeout(() => { this.$refs.barcodeInput.focus(); this.$refs.barcodeInput.select(); }, 150); return; }
            if(this.subtotal <= 0) { await Swal.fire({ icon: 'warning', title: 'Perhatian', text: 'Grand total tidak valid', confirmButtonText: 'OK', returnFocus: false }); this.$nextTick(() => { this.$refs.barcodeInput.focus(); this.$refs.barcodeInput.select(); }); return; }
            if(this.paymentTotal < this.subtotal) { await Swal.fire({ icon: 'warning', title: 'Pembayaran Kurang', text: 'Total belanja Rp ' + this.formatRupiah(this.subtotal), returnFocus: false, confirmButtonText: 'OK', }); setTimeout(() => { document.getElementById('cash')?.focus(); document.getElementById('cash')?.select(); }, 150); return; }
            const konfirmasiCetak = await Swal.fire({ title: 'Cetak Nota?', text: 'Transaksi akan disimpan ke sistem', icon: 'question', showCancelButton: true, confirmButtonText: 'Ya (Enter)', cancelButtonText: 'Batal', confirmButtonColor: '#4f46e5', returnFocus: false });
            if (!konfirmasiCetak.isConfirmed) { setTimeout(() => { document.getElementById('cash')?.focus(); document.getElementById('cash')?.select(); }, 150); return; }
            let response = await fetch('/api/transactions', { method:'POST', headers:{ 'Content-Type': 'application/json', 'X-CSRF-TOKEN': document .querySelector( 'meta[name="csrf-token"]' ) .content }, body:JSON.stringify({ pelanggan: this.selectedCustomer ? this.selectedCustomer.kode_pelanggan : null, cart:this.cart, subtotal: Number(this.subtotal || 0), voucher: Number(this.voucher || 0), card: Number(this.card || 0), grand_total: Number(this.subtotal || 0), cash: Number(this.cash || 0), kembalian: Number(this.kembalian || 0) }) });
            let result = await response.json();
            if(result.success) { window.open( '/transactions/' + result.transaction_id + '/print', '_blank' ); Swal.fire({ title: 'Berhasil!', text: result.no_nota, icon: 'success', timer: 1500, showConfirmButton: false }); setTimeout(() => { location.reload(); }, 1500); }
            else { await Swal.fire({ icon: 'error', title: 'Gagal', text: result.message, confirmButtonText: 'OK', returnFocus: false }); this.$nextTick(() => { this.$refs.barcodeInput?.focus(); }); }
        },
        searchPrice() { let q = this.priceSearch.trim(); if(q.length < 1) { this.priceResults = []; return; } fetch( `/api/products/search?q=${encodeURIComponent(q)}` ) .then(r => r.json()) .then(data => { this.priceResults = data; }); },
    }
}
window.ALL_PRODUCTS = @json($products);
window.ALL_CUSTOMERS = @json($customers);
</script>
@endsection