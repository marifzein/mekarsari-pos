@extends('layouts.mobile-app')

@section('title', 'POS Kasir Mobile')
@section('page_subtitle', 'Nota: ' . $noNota)

@section('content')
<div x-data="posKasir()">

    <!-- SCROLLABLE BODY AREA -->
    <div class="flex-1 space-y-3.5 pb-48">
        
        <!-- 1. INPUT PENCARIAN / BARCODE SCANNER + TOMBOL CEK HARGA -->
        <div class="bg-white rounded-2xl p-3.5 shadow-xs border border-slate-100">
            <div class="flex items-center gap-2">
                <div class="relative flex-1">
                    <span class="absolute inset-y-0 left-0 flex items-center pl-3.5 pointer-events-none">
                        <i class="ri-scan-2-line text-slate-400 text-xl"></i>
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
                        placeholder="Scan barcode / nama barang..."
                        class="w-full bg-slate-50 border-0 focus:ring-2 focus:ring-indigo-500 rounded-xl pl-11 pr-4 py-3 text-sm font-bold outline-none transition placeholder:text-slate-400"
                    >

                    <!-- Dropdown Hasil Pencarian Produk -->
                    <div x-show="products.length" class="absolute left-0 right-0 bg-white border border-slate-200 rounded-xl shadow-xl mt-1.5 z-40 max-h-60 overflow-y-auto divide-y divide-slate-100" x-cloak>
                        <template x-for="(product, index) in products" :key="product.id">
                            <div
                                 @click="addToCart(product)"
                                 :class="selectedIndex === index ? 'bg-indigo-50 border-l-4 border-indigo-600' : ''"
                                class="p-3 cursor-pointer flex justify-between items-center" 
                            >
                                <div>
                                    <div class="font-bold text-slate-800 text-sm" x-text="product.nama_barang"></div>
                                    <div class="text-xs font-mono text-slate-400" x-text="product.kode_barang"></div>
                                </div>
                                <div class="text-right">
                                    <div class="text-sm font-black text-indigo-600" x-text="'Rp ' + formatRupiah(product.harga)"></div>
                                    <div class="text-xs text-slate-400" x-text="'Stok: ' + product.stok"></div>
                                </div>
                            </div>
                        </template>
                    </div>
                </div>

                <!-- TOMBOL KHUSUS CEK HARGA (F3) -->
                <button @click="openPriceModal()" type="button" title="Cek Harga Barang (F3)" class="w-12 h-12 bg-indigo-50 hover:bg-indigo-100 text-indigo-600 rounded-xl transition flex items-center justify-center active:scale-95 flex-shrink-0">
                    <i class="ri-price-tag-3-line text-2xl"></i>
                </button>
            </div>
        </div>

        <!-- 2. PILIH DATA PELANGGAN (F8) -->
        <div class="bg-white rounded-2xl p-3.5 shadow-xs border border-slate-100">
            <div class="flex justify-between items-center mb-2">
                <label class="text-xs font-bold uppercase tracking-wider text-slate-500 flex items-center gap-1">
                    <i class="ri-user-smile-line text-indigo-500 text-sm"></i> Pelanggan (F8)
                </label>
                <button type="button" @click="$dispatch('open-customer-modal')" class="text-xs text-indigo-600 font-bold hover:underline">+ Baru</button>
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
                    class="w-full bg-slate-50 border-0 focus:ring-2 focus:ring-indigo-500 text-sm font-semibold rounded-xl px-3.5 py-2.5 outline-none transition placeholder:text-slate-400"
                >

                <div x-show="customerResults.length" class="absolute left-0 right-0 top-full mt-1 bg-white border border-slate-200 rounded-xl shadow-lg z-40 max-h-48 overflow-y-auto divide-y divide-slate-100" x-cloak>
                    <template x-for="(customer, index) in customerResults" :key="customer.kode_pelanggan">
                        <div @click="selectCustomer(customer)" :class="customerIndex===index ? 'bg-indigo-50' : ''" class="px-3.5 py-2.5 cursor-pointer text-sm">
                            <div class="font-bold text-slate-800" x-text="customer.nama"></div>
                            <div class="text-xs text-slate-400" x-text="customer.alamat || 'Tidak ada alamat'"></div>
                        </div>
                    </template>
                </div>
            </div>

            <template x-if="selectedCustomer">
                <div class="mt-2.5 rounded-xl bg-indigo-50 border border-indigo-100 p-2.5 flex items-center justify-between">
                    <div class="min-w-0">
                        <div class="font-bold text-indigo-950 text-sm" x-text="selectedCustomer.nama"></div>
                        <div class="text-xs text-indigo-700 truncate" x-text="selectedCustomer.alamat || 'Alamat nihil'"></div>
                    </div>
                    <button @click="clearCustomer()" class="text-xs text-red-500 font-bold bg-white shadow-3xs rounded-md px-2.5 py-1 flex-shrink-0 active:scale-95 transition">Lepas</button>
                </div>
            </template>
        </div>

        <!-- 3. DAFTAR KERANJANG BELANJA -->
        <div class="bg-white rounded-2xl p-3.5 shadow-xs border border-slate-100">
            <div class="flex justify-between items-center mb-3">
                <h3 class="font-black text-slate-800 text-sm uppercase tracking-wider flex items-center gap-1.5">
                    <i class="ri-shopping-cart-2-line text-indigo-600 text-base"></i> Item Belanjaan 
                    <span class="text-xs px-2 py-0.5 bg-indigo-50 text-indigo-600 rounded-full font-extrabold" x-text="cart.length"></span>
                </h3>
                <button x-show="cart.length > 0" @click="clearCart()" type="button" class="text-xs text-red-500 font-bold">Kosongkan</button>
            </div>

            <div class="space-y-2.5">
                <template x-for="(item, index) in cart" :key="item.id">
                    <div class="bg-slate-50 border border-slate-200/80 rounded-xl p-3 flex flex-col gap-2 shadow-2xs">
                        
                        <div class="flex justify-between items-start gap-2">
                            <div class="min-w-0">
                                <div class="font-bold text-slate-800 text-sm tracking-tight break-words" x-text="item.nama_barang"></div>
                                <div class="text-xs font-bold text-slate-400 mt-0.5" x-text="'@Rp ' + formatRupiah(item.harga)"></div>
                            </div>
                            <button @click="removeItem(item.id)" class="text-red-400 p-1.5 active:scale-90 transition rounded-lg hover:bg-red-50">
                                <i class="ri-delete-bin-6-line text-lg"></i>
                            </button>
                        </div>

                        <div class="flex justify-between items-center pt-2 border-t border-slate-200/60">
                            <div>
                                <span class="text-[10px] font-bold text-slate-400 block uppercase">Subtotal</span>
                                <span class="text-sm font-black text-indigo-600" x-text="'Rp ' + formatRupiah(item.qty * item.harga)"></span>
                            </div>

                            <div class="flex items-center bg-white border border-slate-200 rounded-xl p-1 shadow-3xs">
                                <button type="button" @click="if(item.qty > 1) { item.qty--; calculateItem(item); }" class="w-8 h-8 rounded-lg bg-slate-100 text-slate-700 flex items-center justify-center font-bold active:bg-slate-200">
                                    <i class="ri-subtract-line text-sm"></i>
                                </button>
                                <input
                                    type="number"
                                    min="1"
                                    x-model.number="item.qty"
                                    @change="validateQty(item)"
                                    @input="calculateItem(item)"
                                    class="w-10 border-0 text-center font-black text-sm text-slate-800 p-0 focus:ring-0"
                                />
                                <button type="button" @click="item.qty++; calculateItem(item);" class="w-8 h-8 rounded-lg bg-slate-100 text-slate-700 flex items-center justify-center font-bold active:bg-slate-200">
                                    <i class="ri-add-line text-sm"></i>
                                </button>
                            </div>
                        </div>

                    </div>
                </template>

                <div x-show="cart.length === 0" class="text-center py-8 text-slate-400 border border-dashed border-slate-200 rounded-xl bg-slate-50/50 text-sm">
                    Belum ada item masuk.
                </div>
            </div>
        </div>

        <!-- 4. PEMBAYARAN -->
        <div class="bg-white rounded-2xl p-3.5 shadow-xs border border-slate-100 space-y-3">
            <h3 class="text-xs font-bold uppercase tracking-wider text-slate-500 flex items-center gap-1">
                <i class="ri-bank-card-2-line text-indigo-500 text-sm"></i> Jenis Pembayaran
            </h3>

            <!-- Cash -->
            <div class="flex justify-between items-center gap-2">
                <label for="cash" class="text-sm font-bold text-slate-700">Cash (F4)</label>
                <div class="relative w-44">
                    <span class="absolute inset-y-0 left-0 flex items-center pl-2.5 text-slate-400 text-xs font-bold">Rp</span>
                    <input id="cash" type="number" min="0" x-model.number="cash" @input="recalculate()" class="text-right w-full bg-slate-50 border-0 focus:ring-2 focus:ring-indigo-500 rounded-xl pl-7 pr-3 py-2 text-sm font-bold text-slate-800">
                </div>
            </div>

            <!-- Voucher -->
            <div class="flex justify-between items-center gap-2">
                <label class="text-sm font-bold text-slate-700">Voucher</label>
                <div class="relative w-44">
                    <span class="absolute inset-y-0 left-0 flex items-center pl-2.5 text-slate-400 text-xs font-bold">Rp</span>
                    <input type="number" min="0" x-model.number="voucher" @input="if(voucher<0) voucher=0; recalculate();" class="text-right w-full bg-slate-50 border-0 focus:ring-2 focus:ring-indigo-500 rounded-xl pl-7 pr-3 py-2 text-sm font-bold text-slate-800">
                </div>
            </div>

            <!-- Card -->
            <div class="flex justify-between items-center gap-2">
                <label class="text-sm font-bold text-slate-700">Card</label>
                <div class="relative w-44">
                    <span class="absolute inset-y-0 left-0 flex items-center pl-2.5 text-slate-400 text-xs font-bold">Rp</span>
                    <input type="number" min="0" x-model.number="card" @input="recalculate();" class="text-right w-full bg-slate-50 border-0 focus:ring-2 focus:ring-indigo-500 rounded-xl pl-7 pr-3 py-2 text-sm font-bold text-slate-800">
                </div>
            </div>
        </div>

    </div>

    <!-- ========================================================== -->
    <!-- STICKY FOOTER DI BAWAH HP (TOTAL & KEMBALIAN DI ATAS TOMBOL) -->
    <!-- ========================================================== -->
    <div class="fixed bottom-0 left-0 right-0 bg-white border-t border-slate-200 shadow-2xl p-4 z-40 rounded-t-2xl">
        <div class="max-w-md mx-auto space-y-2">
            
            <!-- Rincian Tagihan & Kembalian Terpampang Jelas -->
            <div class="bg-slate-50 rounded-xl p-2.5 border border-slate-100 flex items-center justify-between">
                <div>
                    <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wide block">Total Tagihan</span>
                    <span class="text-xl font-black text-slate-900 tracking-tight block truncate" x-text="'Rp ' + formatRupiah(subtotal)"></span>
                </div>

                <div class="text-right">
                    <template x-if="kembalian > 0">
                        <div>
                            <span class="text-[10px] font-bold text-green-600 uppercase tracking-wide block">Kembalian</span>
                            <span class="text-lg font-black text-green-600 block truncate" x-text="'Rp ' + formatRupiah(kembalian)"></span>
                        </div>
                    </template>
                    <template x-if="kurangBayar > 0">
                        <div>
                            <span class="text-[10px] font-bold text-red-500 uppercase tracking-wide block">Kurang</span>
                            <span class="text-lg font-black text-red-500 block truncate" x-text="'Rp ' + formatRupiah(kurangBayar)"></span>
                        </div>
                    </template>
                    <template x-if="kembalian === 0 && kurangBayar === 0">
                        <span class="text-xs font-bold text-slate-400 block">Lunas</span>
                    </template>
                </div>
            </div>

            <!-- Tombol Simpan -->
            <button type="button" @click="saveTransaction()" class="w-full bg-indigo-600 active:bg-indigo-700 text-white font-black text-sm py-3.5 px-4 rounded-xl flex items-center justify-center gap-2 shadow-lg active:scale-95 transition-all">
                <i class="ri-wallet-3-line text-lg"></i>
                <span>BAYAR & SIMPAN</span>
            </button>
        </div>
    </div>

    <!-- MODAL CEK HARGA (KHUSUS KASIR MOBILE) -->
    <div x-show="showPriceModal" x-cloak class="fixed inset-0 bg-black/60 backdrop-blur-xs flex items-center justify-center p-4 z-50" @keydown.escape.window="closePriceModal()">
        <div class="bg-white rounded-2xl p-4 w-full max-w-sm shadow-2xl" @click.outside="closePriceModal()">
            <div class="flex justify-between items-center mb-3">
                <h3 class="font-black text-slate-800 text-base flex items-center gap-1.5">
                    <i class="ri-price-tag-2-line text-indigo-600"></i> Cek Harga & Stok
                </h3>
                <button type="button" @click="closePriceModal()" class="w-8 h-8 rounded-lg bg-slate-100 text-slate-500 flex items-center justify-center font-bold">✕</button>
            </div>

            <input
                x-ref="priceInput"
                x-model="priceSearch"
                @input="searchPrice()"
                placeholder="Ketik nama / kode barang..."
                class="w-full bg-slate-50 border border-slate-200 focus:ring-2 focus:ring-indigo-500 rounded-xl p-3 text-sm font-semibold outline-none shadow-3xs"
            >

            <div class="mt-3 max-h-56 overflow-y-auto divide-y divide-slate-100 rounded-xl border border-slate-100">
                <template x-for="item in priceResults" :key="item.id">
                    <div class="p-3 bg-slate-50/50 flex justify-between items-center">
                        <div>
                            <div class="font-bold text-slate-800 text-sm" x-text="item.nama_barang"></div>
                            <div class="text-xs text-slate-400" x-text="'Stok: ' + item.stok"></div>
                        </div>
                        <div class="text-sm font-black text-green-600" x-text="'Rp ' + formatRupiah(item.harga)"></div>
                    </div>
                </template>
                <div x-show="priceResults.length === 0 && priceSearch.length > 0" class="p-4 text-center text-xs text-slate-400">
                    Barang tidak ditemukan
                </div>
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
                    <label class="block text-xs font-bold text-slate-400 uppercase tracking-wide mb-0.5">Nama Lengkap</label>
                    <input x-ref="newCustomerName" type="text" x-model="newCustomer.nama" @keydown.enter.prevent="$refs.newCustomerPhone.focus()" class="w-full bg-slate-50 border-0 focus:ring-2 focus:ring-indigo-500 rounded-xl p-2.5 text-sm outline-none">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-400 uppercase tracking-wide mb-0.5">No. HP</label>
                    <input x-ref="newCustomerPhone" type="text" x-model="newCustomer.telepon" @keydown.enter.prevent="$refs.newCustomerAlamat.focus()" class="w-full bg-slate-50 border-0 focus:ring-2 focus:ring-indigo-500 rounded-xl p-2.5 text-sm outline-none">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-400 uppercase tracking-wide mb-0.5">Alamat</label>
                    <textarea x-ref="newCustomerAlamat" x-model="newCustomer.alamat" rows="2" class="w-full bg-slate-50 border-0 focus:ring-2 focus:ring-indigo-500 rounded-xl p-2.5 text-sm outline-none"></textarea>
                </div>
            </div>

            <div class="mt-4 flex justify-end gap-2">
                <button type="button" @click="showCustomerModal = false" class="px-3.5 py-2 bg-slate-100 text-slate-600 rounded-xl text-xs font-bold">Batal</button>
                <button type="button" @click="saveNewCustomer()" class="px-3.5 py-2 bg-indigo-600 text-white rounded-xl text-xs font-bold">Simpan</button>
            </div>
        </div>
    </div>

</div>
@endsection

@push('scripts')
<script>
function posKasir() {
    return {
        search: '',
        products: [],
        cart: [],
        cash: 0,
        voucher: 0,
        card: 0,
        diskon: 0,
        subtotal: 0,
        kurangBayar: 0,
        kembalian: 0,
        customerSearch: '',
        customerResults: [],
        selectedCustomer: null,
        allCustomers: window.ALL_CUSTOMERS || [],
        allProducts: window.ALL_PRODUCTS || [],
        selectedIndex: 0,
        customerIndex: -1,

        // State & Handler Cek Harga khusus POS
        showPriceModal: false,
        priceSearch: '',
        priceResults: [],

        openPriceModal() {
            this.showPriceModal = true;
            this.priceSearch = '';
            this.priceResults = [];
            this.$nextTick(() => { this.$refs.priceInput.focus(); });
        },

        closePriceModal() {
            this.showPriceModal = false;
            this.$nextTick(() => { this.$refs.barcodeInput.focus(); });
        },

        searchPrice() {
            let q = this.priceSearch.toLowerCase().trim();
            if (q.length < 1) { this.priceResults = []; return; }
            this.priceResults = this.allProducts.filter(p => 
                (p.nama_barang || '').toLowerCase().includes(q) || 
                (p.kode_barang || '').toLowerCase().includes(q)
            ).slice(0, 10);
        },

        searchCustomer() {
            let keyword = this.customerSearch.toLowerCase().trim();
            if(keyword.length < 2) { this.customerResults = []; this.customerIndex = -1; return; }
            this.customerResults = this.allCustomers.filter(c => c.nama.toLowerCase().includes(keyword) || c.kode_pelanggan.toLowerCase().includes(keyword)).slice(0,5);
            this.customerIndex = -1;
        },
        moveCustomerDown() { if(this.customerResults.length===0) return; if(this.customerIndex < this.customerResults.length-1){ this.customerIndex++; } },
        moveCustomerUp() { if(this.customerResults.length===0) return; if(this.customerIndex>0) { this.customerIndex--; } },
        chooseCustomer() { if(this.customerIndex<0) return; this.selectCustomer(this.customerResults[this.customerIndex]); },
        selectCustomer(customer) { this.selectedCustomer = customer; this.customerSearch = customer.nama; this.customerResults=[]; this.customerIndex=-1; this.$nextTick(()=>{ this.$refs.barcodeInput?.focus(); }); },
        closeCustomerSearch() { this.customerResults=[]; this.customerIndex=-1; this.$nextTick(()=> { this.$refs.barcodeInput?.focus(); }); },
        clearCustomer(){ this.selectedCustomer = null; this.customerSearch = ''; this.customerResults = []; this.$nextTick(() => { this.$refs.barcodeInput?.focus(); }); },
        
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
        formatRupiah(value) { return Number(value || 0).toLocaleString('id-ID'); },
        
        init() {
            window.addEventListener('customer-added', (e) => {
                const newCustomer = e.detail;
                this.allCustomers.push(newCustomer);
                this.selectCustomer(newCustomer);
            });
            window.addEventListener('keydown', this.handleShortcut.bind(this));
            this.$nextTick(() => { this.$refs.barcodeInput?.focus(); });
            this.recalculate();
        },
        
        handleShortcut(e) {
            if (typeof Swal !== 'undefined' && Swal.isVisible()) return;
            if(e.key === 'F2') { e.preventDefault(); this.$refs.barcodeInput?.focus(); }
            if(e.key === 'F3') { e.preventDefault(); this.openPriceModal(); }
            if(e.key === 'F8') { e.preventDefault(); this.$refs.customerInput?.focus(); }
            if(e.key === 'F4') { e.preventDefault(); document.getElementById('cash')?.focus(); }
            if(e.key === 'F10') { e.preventDefault(); this.saveTransaction(); }
        },
        
        recalculate() {
            this.subtotal = this.cart.reduce((total, item) => total + (Number(item.qty) * Number(item.harga)), 0);
            let paymentTotal = Number(this.cash || 0) + Number(this.voucher || 0) + Number(this.card || 0);
            this.kurangBayar = Math.max(0, this.subtotal - paymentTotal);
            this.kembalian   = Math.max(0, paymentTotal - this.subtotal);
        },
        
        // Poin 3: Otomatis fokus ke input barcode setelah delete item
        removeItem(id) { 
            // this.cart = this.cart.filter(item => item.id !== id); 
            // this.recalculate(); 
            // this.$nextTick(() => { this.$refs.barcodeInput?.focus(); });

            this.cart = this.cart.filter(item => item.id !== id); 
            this.recalculate(); 
            
            // Pastikan DOM selesai dirender ulang sebelum fokus ditembakkan
            this.$nextTick(() => { 
                setTimeout(() => {
                    const input = this.$refs.barcodeInput || document.getElementById('barcodeInput');
                    if (input) {
                        input.focus();
                        input.select(); // opsional: agar teks terblokir jika ada sisa input
                    }
                }, 50);
            });
            
        },

        validateQty(item) { item.qty = parseInt(item.qty); if(isNaN(item.qty) || item.qty < 1){ item.qty = 1; } },
        calculateItem(item) { item.qty = Number(item.qty); if (item._originalProduct) { item.harga = this.getDynamicPrice(item._originalProduct, item.qty); } this.recalculate(); },
        
        // Helper fokus yang kompatibel untuk Desktop & HP
        focusBarcode() {
            const el = this.$refs.barcodeInput || document.getElementById('barcodeInput');
            if (el) {
                el.focus();
                // Pada mobile, kadang perlu triggger click agar keyboard muncul
                if ('ontouchstart' in window || navigator.maxTouchPoints > 0) {
                    el.click();
                }
            }
        },

        // Poin 2: Otomatis fokus ke input barcode setelah item masuk keranjang
        addToCart(product) {
            if (!product) return;

            let found = this.cart.find(item => item.id === product.id);
            if(found) { 
                found.qty++; 
                found.harga = this.getDynamicPrice(found._originalProduct, found.qty); 
            } else { 
                let initialPrice = this.getDynamicPrice(product, 1); 
                this.cart.push({ 
                    id: product.id, 
                    kode_barang: product.kode_barang, 
                    nama_barang: product.nama_barang, 
                    harga: initialPrice, 
                    qty: 1, 
                    _originalProduct: product 
                }); 
            }

            // Clear pencarian
            this.search = ''; 
            this.products = []; 
            this.selectedIndex = 0; 
            this.recalculate(); 

            // Eksekusi fokus SINKRON agar dikenali browser HP sebagai User Gesture
            this.focusBarcode();

            // Fallback $nextTick tanpa setTimeout lama
            this.$nextTick(() => {
                this.focusBarcode();
            });
        },
        
        searchProduct() {
            let q = this.search.toLowerCase().trim(); if(q.length < 1) { this.products = []; return; }
            this.products = this.allProducts.filter(product => (product.nama_barang || '').toLowerCase().includes(q) || (product.kode_barang || '').toLowerCase().includes(q)).slice(0,6); 
            this.selectedIndex = 0;
        },
        
        async clearCart() {
            const result = await Swal.fire({ text: 'Kosongkan keranjang?', icon: 'warning', showCancelButton: true, confirmButtonText: 'Ya', cancelButtonText: 'Batal', returnFocus: false });
            if(result.isConfirmed) { this.cart = []; this.cash = 0; this.voucher = 0; this.card = 0; this.recalculate(); this.$refs.barcodeInput?.focus(); }
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
    }
}
window.ALL_PRODUCTS = @json($products);
window.ALL_CUSTOMERS = @json($customers);
</script>
@endpush