<!DOCTYPE html>
<html lang="en">
<head>
    <meta
        name="csrf-token"
        content="{{ csrf_token() }}"
    >
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>POS Minimarket</title>

    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>

<body class="bg-slate-100">

    <!-- HEADER -->

    <div class="bg-white border-b shadow-sm">

        <div class="max-w-7xl mx-auto px-6 py-4">

            <h1 class="text-2xl font-bold">
                POS Minimarket
            </h1>

            <p class="text-sm text-gray-500">
                Laravel + Blade + AlpineJS
            </p>

        </div>

    </div>

    <!-- CONTENT -->

    <div class="max-w-7xl mx-auto p-4"
    x-data="posKasir()" >

        <div class="grid grid-cols-12 gap-4">

            <!-- KIRI -->

            <div class="col-span-4 space-y-4">

                <!-- INFO NOTA -->

                <div class="bg-white rounded-xl shadow p-4">

                    <h2 class="font-bold text-lg mb-3">
                        Informasi Nota
                    </h2>

                    <label class="text-sm text-gray-500">
                        No Nota
                    </label>

                    <input
                        type="text"
                        value="{{ $noNota }}"
                        readonly
                        class="w-full mt-1 border rounded-lg p-2 bg-gray-50"
                    >

                </div>

                <!-- PELANGGAN -->

                <div class="bg-white rounded-xl shadow p-4">

                    <h2 class="font-bold text-lg mb-3">
                        Pelanggan
                    </h2>

                    <input
                        type="text"
                        placeholder="Nama Pelanggan"
                        class="w-full border rounded-lg p-2 mb-3"
                    >

                    <input
                        type="text"
                        placeholder="No HP"
                        class="w-full border rounded-lg p-2"
                    >

                </div>

                <!-- shortcut help -->
                 <div class="bg-white rounded-xl shadow p-4">

                    <h2 class="font-bold mb-3">
                        Shortcut Keyboard
                    </h2>

                    <div class="space-y-2 text-sm">

                        <div class="flex justify-between">
                            <span>F2</span>
                            <span>Barcode</span>
                        </div>

                        <div class="flex justify-between">
                            <span>F4</span>
                            <span>Bayar</span>
                        </div>

                        <div class="flex justify-between">
                            <span>F10</span>
                            <span>Simpan Transaksi</span>
                        </div>

                        <div class="flex justify-between">
                            <span>Ctrl+Del</span>
                            <span>Kosongkan Cart</span>
                        </div>

                    </div>

                </div>
                <!--  -->

            </div>

            <!-- KANAN -->

            <div class="col-span-8">

                <div 
                class="bg-white rounded-xl shadow">

                    <!-- SEARCH -->

                    <div class="border-b p-4">

                        <div
                            
                            class="relative"
                        >

                            <input
                                id="barcodeInput"
                                x-ref="barcodeInput"
                                type="text"
                                x-model="search"
                                @input="searchProduct"
                                @keydown.arrow-down.prevent="
                                    if(selectedIndex < products.length - 1)
                                        selectedIndex++
                                "

                                @keydown.arrow-up.prevent="
                                    if(selectedIndex > 0)
                                        selectedIndex--
                                "

                                @keydown.enter.prevent="
                                    if(products.length)
                                        addToCart(products[selectedIndex])
                                "

                                placeholder="Scan Barcode / Kode Barang / Nama Barang"
                                class="w-full border rounded-xl p-3 text-lg"
                            >

                            <div
                                x-show="products.length"
                                class="absolute left-0 right-0 bg-white border rounded-lg shadow mt-1 z-50"
                            >

                                <template
                                    x-for="(product,index) in products"
                                    :key="product.id"
                                >

                                    <div
                                         @click="addToCart(product)"
                                         :class="
                                                selectedIndex === index
                                                ? 'bg-blue-100'
                                                : ''
                                            "
                                        class="p-3 border-b cursor-pointer hover:bg-slate-100" 
                                          
                                    >
                                    <!-- class="p-3 border-b hover:bg-slate-100 cursor-pointer" -->

                                        <div
                                            class="font-medium"
                                            x-text="product.nama_barang"
                                        ></div>

                                        <div
                                            class="text-sm text-gray-500"
                                            x-text="product.kode_barang"
                                        ></div>

                                    </div>

                                </template>

                            </div>

                        </div>

                    </div>

                    <!-- CART -->

                    <div class="p-4">

                        <h2 class="font-bold text-lg mb-4">
                            Keranjang Belanja
                        </h2>

                        <div class="border rounded-lg">

                            <table class="w-full">

                                <thead>

                                <tr class="bg-gray-50">

                                    <th class="text-left p-3">
                                        Barang
                                    </th>

                                    <th class="p-3 w-24">
                                        Qty
                                    </th>

                                    <th class="text-right p-3">
                                        Harga
                                    </th>

                                    <th class="text-right p-3">
                                        Total
                                    </th>

                                    <th class="w-16">
                                    </th>

                                </tr>

                                </thead>

                                <tbody>

                                  <template
                                      x-for="item in cart"
                                      :key="item.id"
                                  >

                                  <tr class="border-b">

                                      <td class="p-3">

                                          <div
                                              x-text="item.nama_barang"
                                          ></div>

                                      </td>

                                      <td class="text-center">

                                          <input
                                              type="number"
                                              min="1"
                                              step="1"
                                              x-model="item.qty"
                                              @change="validateQty(item)"
                                              @keydown.enter.prevent="$refs.barcodeInput.focus()"
                                              class="w-20 border rounded text-center p-1"
                                          />

                                      </td>

                                      <td class="text-right pr-3">

                                          <span
                                              x-text="item.harga.toLocaleString()"
                                          ></span>

                                      </td>

                                      <td class="text-right pr-3">

                                          <span
                                              x-text="(item.qty * item.harga).toLocaleString()"
                                          ></span>

                                      </td>
                                      <td class="text-center">

                                          <button
                                              @click="removeItem(item.id)"
                                              class="text-red-600 hover:text-red-800"
                                          >
                                              🗑
                                          </button>

                                      </td>

                                  </tr>

                                  </template>

                                  <tr x-show="cart.length===0">

                                      <td
                                          colspan="4"
                                          class="text-center p-10 text-gray-400"
                                      >

                                          Cart masih kosong

                                      </td>

                                  </tr>

                                  </tbody>

                            </table>

                        </div>

                    </div>

                    <!-- FOOTER TOTAL -->

                    <div class="border-t p-4 flex justify-end">

                        <div class="w-full md:w-[420px]">

                            <!-- SUBTOTAL -->

                            <div class="flex justify-between items-center mb-3">

                                <span class="font-medium">
                                    Subtotal
                                </span>

                                <span
                                    class="font-medium"
                                    x-text="'Rp ' + subtotal.toLocaleString('id-ID')"
                                ></span>

                            </div>

                            <!-- VOUCHER -->

                            <div class="flex justify-between items-center mb-3">

                                <label class="font-medium">
                                    Voucher
                                </label>

                                <input
                                    type="number"
                                    x-model.number="voucher"
                                    min="0"
                                    class="w-40 border rounded-lg p-2 text-right"
                                >

                            </div>

                            <!-- CARD -->

                            <div class="flex justify-between items-center mb-3">

                                <label class="font-medium">
                                    Card
                                </label>

                                <input
                                    type="number"
                                    x-model.number="card"
                                    min="0"
                                    class="w-40 border rounded-lg p-2 text-right"
                                >

                            </div>

                            <!-- TOTAL -->

                            <div
                                class="flex justify-between items-center
                                    border-t pt-3 mb-4
                                    font-bold text-xl"
                            >

                                <span>Total</span>

                                <span
                                    x-text="'Rp ' + grandTotal.toLocaleString('id-ID')"
                                ></span>

                            </div>

                            <!-- BAYAR -->

                            <div class="flex justify-between items-center mb-4">

                                <label class="font-medium">
                                    Bayar
                                </label>

                                <input
                                    x-ref="cashInput"
                                    type="text"
                                    :value="cashDisplay"
                                    @input="updateCash($event.target.value)"
                                    class="w-40 border rounded-lg p-2 text-right text-lg"
                                >

                            </div>

                            <!-- KEMBALIAN -->

                            <div
                                class="flex justify-between items-center
                                    text-green-600
                                    font-bold text-2xl
                                    mb-4"
                            >

                                <span>Kembalian</span>

                                <span
                                    x-text="'Rp ' + kembalian.toLocaleString('id-ID')"
                                ></span>

                            </div>

                            <!-- BUTTON -->

                            <button
                                x-ref="saveButton"
                                @click="saveTransaction()"
                                class="w-full bg-indigo-600 text-white p-4 rounded-xl font-bold"
                            >

                                SIMPAN TRANSAKSI (F10)

                            </button>

                        </div>

                    </div>
                    

                </div>

            </div>

        </div>

    </div>
<script>

function posKasir() {

    return {

        search: '',

        products: [],

        cart: [],

        voucher: 0,
        card: 0,
        // cash: 0,
        cash: '',
        cashDisplay: '',

        allProducts: window.ALL_PRODUCTS,

        updateCash(value)
        {
            const raw = value.replace(/[^\d]/g,'');

            this.cash = parseInt(raw || 0);

            this.cashDisplay = this.cash.toLocaleString('id-ID');
        },

        // formatRupiah(value)
        // {
        //     return Number(value || 0)
        //         .toLocaleString('id-ID');
        // },

         init()
        {
            window.addEventListener(
                'keydown',
                this.handleShortcut.bind(this)
            );
             this.$nextTick(() => {
                this.$refs.barcodeInput.focus();
            });
        },

        handleShortcut(e)
        {
            if(e.key === 'F2')
            {
                e.preventDefault();

                this.$refs.barcodeInput.focus();
                this.$refs.barcodeInput.select();
            }

            if(e.key === 'F4')
            {
                e.preventDefault();

                this.$refs.cashInput.focus();
                this.$refs.cashInput.select();
            }

            if(e.key === 'F10')
            {
                e.preventDefault();

                this.saveTransaction();
            }

            if(e.ctrlKey && e.key === 'Delete')
            {
                e.preventDefault();

                this.clearCart();
            }
        },
        
        get subtotal()
        {
            return this.cart.reduce(
                (total,item) =>
                    total + (item.qty * item.harga),
                0
            );
        },

        get grandTotal()
        {
            return Math.max(
                0,
                this.subtotal -
                Number(this.voucher || 0) -
                Number(this.card || 0)
            );
        },

        removeItem(id)
        {
            this.cart =
                this.cart.filter(
                    item => item.id !== id
                );
        },
        // validasi qty
        validateQty(item)
        {
            item.qty = parseInt(item.qty);

            if(isNaN(item.qty) || item.qty < 1){
                item.qty = 1;
            }
        },

        // get paymentTotal()
        // {
        //     return (
        //         Number(this.cash || 0)
        //         +
        //         Number(this.card || 0)
        //         +
        //         Number(this.voucher || 0)
        //     );
        // },

        get kembalian()
        {
            return Math.max(
                0,
                Number(this.cash || 0) -
                this.grandTotal
            );
        },
        addToCart(product)
        {

            let found =
                this.cart.find(
                    item => item.id === product.id
                );

            if(found)
            {
                found.qty++;
            }
            else
            {
                this.cart.push({
                    id: product.id,
                    kode_barang: product.kode_barang,
                    nama_barang: product.nama_barang,
                    harga: Number(product.harga),
                    qty: 1
                });
            }

            this.search = '';
            this.products = [];
            
            this.selectedIndex = 0;
            this.$nextTick(() => {
                // this.$refs.barcodeInput.focus();
                document.getElementById('barcodeInput')?.focus()
            });
        },

        searchProduct()
        {
            let q =
                this.search.toLowerCase().trim();

            if(q.length < 1)
            {
                this.products = [];
                return;
            }

            this.products =
                this.allProducts
                .filter(product =>

                    (product.nama_barang || '')
                        .toLowerCase()
                        .includes(q)

                    ||

                    (product.kode_barang || '')
                        .toLowerCase()
                        .includes(q)

                    ||

                    (product.barcode || '')
                        .toLowerCase()
                        .includes(q)

                )
                .slice(0,10);
                this.selectedIndex = 0;
        },

        //lama ambil dari server
        // async searchProduct() {
            
        //     if(this.search.length < 1)
        //     {
        //         this.products = [];
        //         return;
        //     }

        //     let response =
        //         await fetch(
        //             '/api/products/search?q=' +
        //             encodeURIComponent(this.search)
        //         );

        //     this.products =
        //         await response.json();
        // }

        //kosongkan cart
        async clearCart()
        {
            const result = await Swal.fire({
                icon: 'warning',
                title: 'Kosongkan Cart?',
                text: 'Semua item akan dihapus',
                showCancelButton: true,
                confirmButtonText: 'Ya',
                cancelButtonText: 'Batal',
                returnFocus: false
            });

            if(result.isConfirmed)
            {
                this.cart = [];

                this.$refs.barcodeInput.focus();
            }
        },  

        async saveTransaction()
        {
            if(this.cart.length === 0)
            {
                
                await Swal.fire({
                    icon: 'warning',
                    title: 'Perhatian',
                    text: 'Keranjang belanja kosong !',
                    confirmButtonText: 'OK',
                    returnFocus: false
                });
                //  this.$nextTick(() => {
                //         this.$refs.barcodeInput.focus();
                //         this.$refs.barcodeInput.select();
                //     });

                setTimeout(() => {

                    this.$refs.barcodeInput.focus();
                    this.$refs.barcodeInput.select();

                }, 150);
                return;
            }

            if(this.grandTotal <= 0)
            {
                
                await Swal.fire({
                    icon: 'warning',
                    title: 'Perhatian',
                    text: 'Grand total tidak valid',
                    confirmButtonText: 'OK',
                    returnFocus: false
                });
                 this.$nextTick(() => {
                        this.$refs.barcodeInput.focus();
                        this.$refs.barcodeInput.select();
                    });
                return;
            }

            // if(this.paymentTotal < this.grandTotal)
            if(Number(this.cash || 0) < this.grandTotal)    
            {
                await Swal.fire({
                    icon: 'warning',
                    title: 'Pembayaran Kurang',
                    text: 'Total belanja Rp ' + this.grandTotal.toLocaleString(),
                    returnFocus: false,
                    confirmButtonText: 'OK',
                });

                setTimeout(() => {

                        this.$refs.cashInput.focus();
                        this.$refs.cashInput.select();

                    }, 150);

                //this.$refs.cashInput.focus();
                //this.$refs.cashInput.select();
                

                return;
            }

            let response =
                await fetch(
                    '/api/transactions',
                    {
                        method:'POST',

                        headers:{
                            'Content-Type':
                                'application/json',

                            'X-CSRF-TOKEN':
                                document
                                .querySelector(
                                    'meta[name="csrf-token"]'
                                )
                                .content
                        },

                        body:JSON.stringify({

                            pelanggan:'',

                            cart:this.cart,

                            subtotal:this.subtotal,

                            voucher:this.voucher,

                            card:this.card,

                            grand_total:
                                this.grandTotal,

                            cash:this.cash,

                            kembalian:
                                this.kembalian
                        })
                    }
                );

            let result =
                await response.json();

            if(result.success)
            {
                window.open(
                    '/transactions/' +
                    result.transaction_id +
                    '/print',
                    '_blank'
                );

                Swal.fire({
                    title: 'Berhasil!',
                    text: result.no_nota,
                    icon: 'success',
                    timer: 1500,
                    showConfirmButton: false
                });

                setTimeout(() => {
                    location.reload();
                }, 1500);
                
                // await Swal.fire({
                //     icon: 'success',
                //     title: 'Transaksi Berhasil',
                //     text: result.message ?? 'Data berhasil disimpan',
                //     timer: 1500,
                //     showConfirmButton: false
                // })
                // .then(() => {
                //     window.open(
                //         '/transactions/' +
                //         result.transaction_id +
                //         '/print',
                //         '_blank'
                //     );

                //     window.location.reload();
                // });
                
                
            }
            else
            {
                await Swal.fire({
                    icon: 'error',
                    title: 'Gagal',
                    text: result.message,
                    confirmButtonText: 'OK',
                    returnFocus: false
                });

                this.$nextTick(() => {
                    this.$refs.barcodeInput?.focus();
                });
            }
        }
        
    }

}

// load semua barang
window.ALL_PRODUCTS =
    @json($products);

// debug focus input
// document.addEventListener('focusin', (e) => {
//     console.log('FOCUS:', e.target);
// });

//F2 focus ke input barcode 
// window.addEventListener('keydown', (e) => {

//     if(e.key === 'F2')
//     {    
//         e.preventDefault();

//         this.$refs.barcodeInput.focus();
//         this.$refs.barcodeInput.select();
//     }
//     //F4 fokus bayar
//     if(e.key === 'F4')
//     {
//         e.preventDefault();

//         this.$refs.cashInput.focus();
//         this.$refs.cashInput.select();
//     }
//     //F10 save
//     if(e.key === 'F10')
//     {
//         e.preventDefault();

//         this.saveTransaction();
//     }
//     //kosongkan cart
//     if(e.ctrlKey && e.key === 'Delete')
//     {
//         e.preventDefault();

//         this.clearCart();
//     }

// });
</script>
</body>
</html>