@extends('layouts.app')

@section('title','Produk')

@section('content')

    <!-- CONTENT -->
     <div class="flex justify-between mb-4">
        <h2 class="text-2xl font-bold">
            POS ( Point Of Sales )
        </h2>
    </div>

    <!-- <h1 class="text-2xl font-bold mb-4">
        POS ( Point Of Sales )
    </h1>  -->

    <div class="max-w-7xl mx-auto p-4"
    x-data="posKasir()" 
    >
    <!-- @keydown.window="handleShortcut($event)" -->

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

                <div class="bg-white rounded-xl shadow p-4 mb-4">

                    <label
                        class="block text-sm font-semibold mb-2"
                    >
                        Pelanggan
                    </label>

                    <button

                        type="button"

                        class="text-indigo-600 text-sm hover:underline"

                    >

                        + Tambah Pelanggan Baru

                    </button>
                    
                    <div class="relative"
                        @click.outside="customerResults=[]">

                        <input

                            x-ref="customerInput"

                            type="text"

                            x-model="customerSearch"

                            @keydown.arrow-down.prevent="moveCustomerDown()"

                            @keydown.arrow-up.prevent="moveCustomerUp()"

                            @keydown.enter.prevent="chooseCustomer()"

                            @keydown.escape.prevent="closeCustomerSearch()"

                            @input="searchCustomer()"

                            placeholder="Cari nama / kode pelanggan"

                            class="w-full rounded-xl border border-slate-300 px-4 py-3"

                        >

                        <div

                            x-show="customerResults.length"

                            class="absolute left-0 right-0 top-full mt-1 bg-white border rounded-xl shadow-lg z-50 max-h-72 overflow-y-auto"

                        >

                            <template
                            x-for="(customer,index) in customerResults"
                            :key="customer.kode_pelanggan"
                            >

                                <div

                                    @click="selectCustomer(customer)"

                                    {{-- class="px-4 py-3 hover:bg-indigo-50 cursor-pointer border-b" --}}
                                    :class="

                                            customerIndex===index
                                            ?
                                            'bg-indigo-100'
                                            :
                                            ''
                                        "

                                    class="
                                        px-4
                                        py-3
                                        cursor-pointer
                                        hover:bg-indigo-50
                                        border-b
                                    "
                                >

                                    <div
                                        class="font-semibold"
                                        x-text="customer.nama"
                                    ></div>

                                    <div
                                        class="text-xs text-slate-500"
                                        x-text="customer.kode_pelanggan"
                                    ></div>

                                </div>

                            </template>

                        </div>

                    </div>

                    {{-- <div
                        class="mt-3"
                        x-show="selectedCustomer"
                    >

                        <div class="rounded-xl bg-indigo-50 p-3">

                            <div
                                class="font-semibold"
                                x-text="selectedCustomer?.nama"
                            ></div>

                            <div
                                class="text-sm text-slate-500"
                                x-text="selectedCustomer?.kode_pelanggan"
                            ></div>

                            <button

                                @click="clearCustomer()"

                                class="mt-2 text-red-600 text-sm"

                            >

                                Kosongkan Pelanggan

                            </button>

                        </div>

                    </div> --}}

                    <!-- Ganti x-show dengan template x-if -->
                    <template x-if="selectedCustomer">

                        <div class="mt-3">

                            <div class="rounded-xl bg-indigo-50 p-3">

                                <div
                                    class="font-semibold"
                                    x-text="selectedCustomer.nama"
                                ></div>

                                <div
                                    class="text-sm text-slate-500"
                                    x-text="selectedCustomer.kode_pelanggan"
                                ></div>

                                <button
                                    @click="clearCustomer()"
                                    class="mt-2 text-red-600 text-sm"
                                >
                                    Kosongkan Pelanggan
                                </button>

                            </div>

                        </div>

                    </template>

                </div>

                {{-- <div class="bg-white rounded-xl shadow p-4">

                    <h2 class="font-bold text-lg mb-3">
                        Pelanggan
                    </h2>

                    <input
                        type="text"
                        x-model="customerSearch"
                        @input="searchCustomer()"
                        placeholder="Cari nama / kode pelanggan"
                        class="w-full border rounded-lg p-2 mb-3"
                    >

                    <input
                        type="text"
                        placeholder="No HP"
                        class="w-full border rounded-lg p-2"
                    >

                </div> --}}

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
                            <span>F3</span>
                            <span>Cek Harga Barang</span>
                        </div>

                        <div class="flex justify-between">
                            <span>F4</span>
                            <span>Bayar</span>
                        </div>

                        <div class="flex justify-between">
                            <span>F8</span>
                            <span>Pelanggan</span>
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
                                        {{-- @input="

                                                if(item.qty<1) item.qty=1;

                                                calculateItem(item)

                                                " --}}
                                          <input
                                              type="number"
                                              min="1"
                                              
                                              x-model="item.qty"
                                              @change="validateQty(item)"
                                              @keydown.enter.prevent="$refs.barcodeInput.focus()"
                                              @input="calculateItem(item)"
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
                                              <i class="ri-delete-bin-line text-lg"></i>
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
{{-- =========================================================================== --}}
                            {{-- REFACTOR PEMBAYARAN >>>>>>>>     --}}

                            <div class="bg-white rounded-xl  p-5 space-y-5">
                                {{-- shadow border --}}

                                <h3 class="text-lg font-semibold">

                                    Pembayaran

                                </h3>

                                {{-- ======================= --}}
                                {{-- SUBTOTAL --}}
                                {{-- ======================= --}}

                                <div class="flex justify-between items-center">

                                    <span class="text-slate-600">

                                        Subtotal

                                    </span>
                                    {{-- debug sbttl --}}
                                    {{-- <div x-text="subtotal"></div> --}}
                                    {{-- debug sbttl --}}    
                                    <span
                                        class="font-bold text-lg"
                                        x-text="formatRupiah(subtotal)"
                                    ></span>

                                </div>

                                

                                <hr>

                                {{-- ======================= --}}
                                {{-- CASH --}}
                                {{-- ======================= --}}
                                {{-- <x-input
                                    label="Sayar"
                                    name="byr"
                                    icon="ri-wallet-3-line"
                                    :value="0"
                                    class="text-right"
                                    width="w-40"
                                    type="number"
                                /> --}}
                                
                                <div class="flex justify-between items-center mb-3">

                                    <label
                                        class="block mb-1 font-medium"
                                    >

                                        Cash (F4)

                                    </label>

                                    <input

                                        id="cash"

                                        type="number"

                                        min="0"

                                        x-model.number="cash"

                                        @input="recalculate()"

                                        class="text-right

                                                rounded-xl

                                                border

                                                border-slate-300

                                                hover:border-slate-400

                                                w-40

                                                bg-white

                                                pl-12 

                                                pr-4

                                                text-slate-700

                                                placeholder:text-slate-400

                                                focus:border-indigo-500

                                                focus:ring-4

                                                focus:ring-indigo-100

                                                outline-none

                                                transition-all

                                                duration-200"

                                    >

                                </div>

                                {{-- ======================= --}}
                                {{-- VOUCHER --}}
                                {{-- ======================= --}}

                                <div class="flex justify-between items-center mb-3">

                                    <label
                                        class="block mb-1 font-medium"
                                    >

                                        Voucher

                                    </label>

                                    <input

                                        type="number"

                                        min="0"

                                        x-model.number="voucher"

                                        @input="
                                            if(voucher<0)
                                                voucher=0;

                                            recalculate();
                                            "

                                        class="text-right

                                                rounded-xl

                                                border

                                                border-slate-300

                                                hover:border-slate-400

                                                w-40

                                                bg-white

                                                pl-12 

                                                pr-4

                                                text-slate-700

                                                placeholder:text-slate-400

                                                focus:border-indigo-500

                                                focus:ring-4

                                                focus:ring-indigo-100

                                                outline-none

                                                transition-all

                                                duration-200"

                                    >

                                </div>

                                {{-- ======================= --}}
                                {{-- CARD --}}
                                {{-- ======================= --}}

                                <div class="flex justify-between items-center mb-3">

                                    <label
                                        class="block mb-1 font-medium"
                                    >

                                        Card

                                    </label>

                                    <input

                                        type="number"

                                        min="0"

                                        x-model.number="card"

                                        @input="

                                        {{-- if(card<0)
                                            card=0; --}}

                                        recalculate();

                                        "
                                        class="text-right

                                                rounded-xl

                                                border

                                                border-slate-300

                                                hover:border-slate-400

                                                w-40

                                                bg-white

                                                pl-12 

                                                pr-4

                                                

                                                text-slate-700

                                                placeholder:text-slate-400

                                                focus:border-indigo-500

                                                focus:ring-4

                                                focus:ring-indigo-100

                                                outline-none

                                                transition-all

                                                duration-200"
                                        

                                    >
                                    {{-- class="w-full rounded-lg border px-3 py-2" --}}

                                </div>


                                <hr>

                                {{-- ======================= --}}
                                {{-- KURANG BAYAR --}}
                                {{-- ======================= --}}

                                <div
                                    class="flex justify-between text-lg
                                    text-red-500
                                    font-bold "
                                >

                                    <span>

                                        Kurang Bayar

                                    </span>

                                    <span

                                        class="font-bold"

                                        :class="

                                        kurangBayar>0

                                        ?

                                        'text-red-600'

                                        :

                                        'text-slate-700'

                                        "

                                        x-text="formatRupiah(kurangBayar)"

                                    ></span>

                                </div>

                                {{-- ======================= --}}
                                {{-- KEMBALIAN --}}
                                {{-- ======================= --}}

                                <div
                                    class="flex justify-between text-lg 
                                    text-green-600
                                    font-bold "
                                >

                                    <span>

                                        Kembalian

                                    </span>

                                    <span

                                        class="font-bold text-green-600"

                                        x-text="formatRupiah(kembalian)"

                                    ></span>

                                </div>

                                <hr>

                                <button

                                    @click="saveTransaction()"

                                    class="

                                    w-full

                                    bg-indigo-600

                                    hover:bg-indigo-700

                                    text-white

                                    py-3

                                    rounded-xl

                                    font-semibold

                                    "

                                >

                                    <i class="ri-save-line mr-2"></i>

                                    Simpan Transaksi

                                </button>

                            </div>    

                            {{-- REFACTOR PEMBAYARAN END >>>>>>>> --}}
{{-- =========================================================================== --}}
                            <!-- SUBTOTAL -->

                            {{-- <div class="flex justify-between items-center mb-3">

                                <span class="font-medium">
                                    Subtotal
                                </span>

                                <span
                                    class="font-medium"
                                    x-text="'Rp ' + subtotal.toLocaleString('id-ID')"
                                ></span>

                            </div> --}}

                            <!-- VOUCHER -->

                            {{-- <div class="flex justify-between items-center mb-3">

                                <label class="font-medium">
                                    Voucher
                                </label>

                                <input
                                    type="number"
                                    x-model.number="voucher"
                                    min="0"
                                    class="w-40 border rounded-lg p-2 text-right"
                                >

                            </div> --}}

                            <!-- CARD -->

                            {{-- <div class="flex justify-between items-center mb-3">

                                <label class="font-medium">
                                    Card
                                </label>

                                <input
                                    type="number"
                                    x-model.number="card"
                                    min="0"
                                    class="w-40 border rounded-lg p-2 text-right"
                                >

                            </div> --}}

                            {{-- krg bayar --}}
                            {{-- <div
                                class="flex justify-between items-center
                                text-red-600
                                font-bold text-xl
                                mb-3">

                                <span>Kurang Bayar</span>

                                <span
                                x-text="'Rp ' + kurangBayar.toLocaleString('id-ID')"
                                ></span>

                            </div> --}}
                            {{-- krg bayar end --}}
                            
                            {{-- kembalian --}}
                            {{-- <div
                                class="flex justify-between items-center
                                text-green-600
                                font-bold text-2xl
                                mb-4">

                                <span>Kembalian</span>

                                <span
                                x-text="'Rp ' + kembalian.toLocaleString('id-ID')"
                                ></span>

                            </div> --}}
                            {{-- kembalian end --}}


                            <!-- TOTAL -->

                            {{-- <div
                                class="flex justify-between items-center
                                    border-t pt-3 mb-4
                                    font-bold text-xl"
                            >

                                <span>Total</span>

                                <span
                                    x-text="'Rp ' + grandTotal.toLocaleString('id-ID')"
                                ></span>

                            </div> --}}

                            <!-- BAYAR -->

                            {{-- <div class="flex justify-between items-center mb-4">

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

                            </div> --}}

                            <!-- KEMBALIAN -->

                            {{-- <div
                                class="flex justify-between items-center
                                    text-green-600
                                    font-bold text-2xl
                                    mb-4"
                            >

                                <span>Kembalian</span>

                                <span
                                    x-text="'Rp ' + kembalian.toLocaleString('id-ID')"
                                ></span>

                            </div> --}}

                            <!-- BUTTON -->

                            {{-- <button
                                x-ref="saveButton"
                                @click="saveTransaction()"
                                class="w-full bg-indigo-600 text-white p-4 rounded-xl font-bold"
                            >

                                SIMPAN TRANSAKSI (F10)

                            </button> --}}

                        </div>

                    </div>
                    

                </div>

            </div>

        </div>
        
        <!-- modal -->
        <div
            x-show="showPriceModal"
            
            x-cloak
            class="fixed inset-0 bg-black/50 flex items-center justify-center z-50"

            @keydown.escape.window="closePriceModal()"
        >

            <div
                class="bg-white rounded-xl p-6 w-full max-w-xl"
            >

                <div
                    class="flex justify-between mb-4"
                >

                    <h3
                        class="font-bold text-xl"
                    >
                        Cek Harga
                    </h3>

                    <button
                        @click="
                            showPriceModal=false;
                            $refs.barcodeInput.focus();
                            
                        "
                    >
                        ✕
                    </button>

                </div>

                <input
                    x-ref="priceInput"
                    x-model="priceSearch"
                    @input="searchPrice()"
                    placeholder="Scan Barcode / Nama Barang"
                    class="w-full border rounded-lg p-3"
                >

                <div
                    class="mt-4 max-h-80 overflow-auto"
                >

                    <template
                        x-for="item in priceResults"
                        :key="item.id"
                    >

                        <div
                            class="border-b py-3"
                        >

                            <div
                                class="font-semibold"
                                x-text="
                                    item.nama_barang
                                "
                            ></div>

                            <div
                                class="text-green-600"
                                x-text="
                                    'Harga : Rp ' +
                                    Number(item.harga)
                                    .toLocaleString('id-ID')
                                "
                            ></div>

                            <div
                                class="text-gray-500"
                                x-text="
                                    'Stok : ' +
                                    item.stok
                                "
                            ></div>

                        </div>

                    </template>

                </div>

            </div>

        </div>
        <!-- modal end -->

    </div>
<script>

function posKasir() {

    return {

        search: '',

        products: [],

        cart: [],

        cash: 0,
        //cash: '',

        voucher: 0,
        
        card: 0,

        diskon: 0,

        // paymentTotal:0,

        kurangBayar:0,

        kembalian:0,
        
        // pelanggan
        customerSearch: '',
        customerResults: [],
        selectedCustomer: null,
        allCustomers: window.ALL_CUSTOMERS,

        cashDisplay: '',

        allProducts: window.ALL_PRODUCTS,

        showPriceModal: false,
        priceSearch: '',
        priceResults: [],

        // cari plggn
        searchCustomer()
        {
            let keyword =
                this.customerSearch
                    .toLowerCase()
                    .trim();

            if(keyword.length < 2)
            {
                this.customerResults = [];

                this.customerIndex = -1;

                return;
            }

            this.customerResults =
                this.allCustomers.filter(c =>

                    c.nama
                        .toLowerCase()
                        .includes(keyword)

                    ||

                    c.kode_pelanggan
                        .toLowerCase()
                        .includes(keyword)

                ).slice(0,8);

            this.customerIndex = -1;
        },
        // arrow down pilih pelanggan====
        moveCustomerDown()
        {
            if(this.customerResults.length===0)
                return;

            if(
                this.customerIndex <
                this.customerResults.length-1
            ){
                this.customerIndex++;
            }
        },
        // arrow up pilih pelanggan====
        moveCustomerUp()
        {
            if(this.customerResults.length===0)
                return;

            if(this.customerIndex>0)
            {
                this.customerIndex--;
            }
        },

        chooseCustomer()
        {
            if(
                this.customerIndex<0
            )
                return;

            this.selectCustomer(

                this.customerResults[
                    this.customerIndex
                ]

            );
        },

        selectCustomer(customer)
        {
            this.selectedCustomer = customer;

            this.customerSearch =
                customer.nama;

            this.customerResults=[];

            this.customerIndex=-1;

            this.$nextTick(()=>{

                // this.$refs.barcodeInput.focus();
                document.getElementById('barcodeInput')?.focus();
            });

        },

        closeCustomerSearch()
        {
            this.customerResults=[];

            this.customerIndex=-1;

            this.$nextTick(()=>
            {
                this.$refs.barcodeInput.focus();
            });
        },
        
        // kosongi plggn
        clearCustomer(){

            this.selectedCustomer = null;

            this.customerSearch = '';

            this.customerResults = [];

            this.$nextTick(() => {
                document.getElementById('barcodeInput')?.focus()
            });


        },

        get grandTotal()
        {
            return this.subtotal - this.diskon;
        },

        // ESC utk close price checker
        closePriceModal()
        {
            this.showPriceModal = false;

            this.priceSearch = '';
            this.priceResults = [];

            setTimeout(() => {

                this.$refs.barcodeInput.focus();

            }, 50);
        },

        updateCash(value)
        {
            const raw = value.replace(/[^\d]/g,'');

            this.cash = parseInt(raw || 0);

            this.cashDisplay = this.cash.toLocaleString('id-ID');

            this.recalculate();

            
        },

        formatRupiah(value)
        {
            return Number(value || 0)
                .toLocaleString('id-ID');
        },

         init()
        {
            window.addEventListener(
                'keydown',
                this.handleShortcut.bind(this)
            );
             this.$nextTick(() => {
                this.$refs.barcodeInput.focus();
            });

            this.recalculate();
        },

        handleShortcut(e)
        {
            // saat swal semua sortcut off
             if (
                    typeof Swal !== 'undefined'
                    &&
                    Swal.isVisible()
                )
                {
                    return;
                }
                
            if(e.key === 'F2')
            {
                e.preventDefault();

                this.$refs.barcodeInput.focus();
                this.$refs.barcodeInput.select();
            }
            //cek harga barang
            if(e.key === 'F3')
            {
                e.preventDefault();

                this.showPriceModal = true;

                setTimeout(() => {

                    this.$refs.priceInput.focus();

                }, 50);

                return;
            }

            // pelanggan F8
            if(e.key === 'F8')
            {
                e.preventDefault();

                this.$refs.customerInput.focus();

                this.$refs.customerInput.select();

                return;
            }

            //pelanggan ESC
            if(
                e.key === 'Escape'
                &&
                this.customerResults.length
            )
            {
                e.preventDefault();

                this.closeCustomerSearch();

                return;
            }


            if(e.key === 'F4')
            {
                e.preventDefault();

                // this.$refs.cashInput.focus();
                // this.$refs.cashInput.select();

                document
                .getElementById('cash')
                ?.focus();

                document
                .getElementById('cash')
                ?.select();
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
        
        // get subtotal()
        // {
        //     return this.cart.reduce(
        //         (total,item) =>
        //             total + (item.qty * item.harga),
        //         0
        //     );

            
        // },

        // get paymentTotal()
        // {
        //     return (
        //         Number(this.cash || 0) +
        //         Number(this.voucher || 0) +
        //         Number(this.card || 0)
        //     );
        // },

        // get kurangBayar()
        // {
        //     return Math.max(
        //         0,
        //         this.subtotal - this.paymentTotal
        //     );
        // },

        // get kembalian()
        // {
        //     return Math.max(
        //         0,
        //         this.paymentTotal - this.subtotal
        //     );
            
        // },

        subtotal: 0,

        paymentTotal: 0,

        kurangBayar: 0,

        kembalian: 0,
        
        recalculate()
        {
            this.subtotal = this.cart.reduce(
                // (total, item) => total + (item.qty * item.harga),
                // 0
                (total, item) => total + (Number(item.qty) * Number(item.harga)),
                    0
            );

            // Amankan kalkulasi dari input kosong
            let nilaiCash = Number(this.cash || 0);
            let nilaiVoucher = Number(this.voucher || 0);
            let nilaiCard = Number(this.card || 0);
            
            this.paymentTotal =
                Number(this.cash || 0) +
                Number(this.voucher || 0) +
                Number(this.card || 0);

            this.kurangBayar = Math.max(0, this.subtotal - this.paymentTotal);
            this.kembalian   = Math.max(0, this.paymentTotal - this.subtotal);
        },

        removeItem(id)
        {
            this.cart =
                this.cart.filter(
                    item => item.id !== id
                );
            
             this.$nextTick(() => {
                this.recalculate();
            });
            
        },

        // validasi qty
        validateQty(item)
        {
            item.qty = parseInt(item.qty);

            if(isNaN(item.qty) || item.qty < 1){
                item.qty = 1;
            }
        },

       calculateItem(item)
        {
            item.qty = Number(item.qty);

            item.harga = Number(item.harga);

            item.total =
                item.qty * item.harga;

            // this.recalculate();
            this.$nextTick(() => this.recalculate());
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

            this.recalculate();

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
        
                this.$nextTick(() => {
                    this.recalculate();
                    // Optional: reset payment jika mau (bisa di-comment dulu)
                    this.cash = 0;
                    this.voucher = 0;
                    this.card = 0;
                    this.kembalian=0;
                });

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

            if(this.subtotal <= 0)
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

            if(this.paymentTotal < this.subtotal)
            {
                
                await Swal.fire({
                    icon: 'warning',
                    title: 'Pembayaran Kurang',
                    text: 'Total belanja Rp ' + this.formatRupiah(this.subtotal),
                    returnFocus: false,
                    confirmButtonText: 'OK',
                });

                setTimeout(() => {

                        // this.$refs.cashInput.focus();
                        // this.$refs.cashInput.select();
                        document.getElementById('cash')?.focus();
                        document.getElementById('cash')?.select();

                    }, 150);

                return;
            }

            // ==========================================================
            // TAMBAHAN KONFIRMASI CETAK NOTA SEBELUM SIMPAN
            // ==========================================================
            const konfirmasiCetak = await Swal.fire({
                title: 'Cetak Nota?',
                text: 'Transaksi akan disimpan ke sistem',
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: 'Ya (Enter)',
                cancelButtonText: 'Batal',
                confirmButtonColor: '#4f46e5', // Warna indigo agar serasi dengan tema Anda
                returnFocus: false
            });

            // Jika user memilih Batal, batalkan proses submit
            if (!konfirmasiCetak.isConfirmed) {
                setTimeout(() => {
                    document.getElementById('cash')?.focus();
                    document.getElementById('cash')?.select();
                }, 150);
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

                            pelanggan:
                            this.selectedCustomer
                                ? this.selectedCustomer.kode_pelanggan
                                : null,

                            cart:this.cart,

                            // Validasi & Proteksi level JS: Jika kosong/null, paksa jadi 0
                            subtotal: Number(this.subtotal || 0),
                            voucher: Number(this.voucher || 0),
                            card: Number(this.card || 0),
                            grand_total: Number(this.subtotal || 0),
                            cash: Number(this.cash || 0),
                            kembalian: Number(this.kembalian || 0)
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
        },

        searchPrice()
        {
            let q = this.priceSearch.trim();

            if(q.length < 1)
            {
                this.priceResults = [];
                return;
            }

            fetch(
                `/api/products/search?q=${encodeURIComponent(q)}`
            )
            .then(r => r.json())
            .then(data => {

                this.priceResults = data;

            });
        },
        
    }

}

// load semua barang
window.ALL_PRODUCTS =
    @json($products);

// load pelanggan
window.ALL_CUSTOMERS =
    @json($customers);

</script>
@endsection