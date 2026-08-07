@extends('layouts.app')

@section('title','Stock Opname')

@section('content')

{{-- alert status saving trx --}}
@if(session('success'))

    <div class="mb-4 p-3 rounded bg-green-100 text-green-700">

        {{ session('success') }}

    </div>

    @endif


    @if(session('warning'))

    <div class="mb-4 p-3 rounded bg-yellow-100 text-yellow-700">

        {{ session('warning') }}

    </div>

@endif
{{-- end alert status saving trx --}}
 <div x-data="stockAdjustment()">
    <form
        id="formScanSO"
        method="POST"
        action="/stock-opname/{{ $stockOpname->id }}"
    >
        {{-- csrf utk POST , kalo meta utk AJAX --}}
        @csrf
        {{-- --- MODIFIKASI HEADER & TOMBOL KEMBALI DISINI --- --}}
        <x-page-header title="Stock Opname" subtitle="No SO : {{ $stockOpname->opname_no }} | Tanggal : {{ \Carbon\Carbon::parse($stockOpname->opname_date)->format('d-m-Y H:i') }}">
            <x-slot:action>
                <div class="flex items-center gap-3">
                    {{-- Badge Status --}}
                    @if($stockOpname->status=='OPEN')
                        <span class="bg-yellow-100 text-yellow-700 px-3 py-1 rounded-full text-sm font-semibold">
                            OPEN
                        </span>
                    @else
                        <span class="bg-green-100 text-green-700 px-3 py-1 rounded-full text-sm font-semibold">
                            POSTED
                        </span>
                    @endif

                    {{-- Tombol Kembali --}}
                    @if($stockOpname->status=='OPEN')
                         {{-- <a href="#"> --}}
                        <x-button color="gray" type="button" disabled>
                            <i class="ri-arrow-left-line"></i> Kembali
                        </x-button>
                        {{-- </a> --}}
                    @else
                         <a href="/stock-opname">
                        <x-button color="gray" type="button">
                            <i class="ri-arrow-left-line"></i> Kembali
                        </x-button>
                    </a>
                    @endif

                </div>
            </x-slot:action>
        </x-page-header>
        {{-- --- END MODIFIKASI HEADER --- --}}

        <div class="bg-white rounded-xl shadow p-4">
            {{-- scan produk --}}
        

            <label class="font-semibold">
                Scan Barcode / Cari Barang
            </label>
            {{-- <!-- Fitur Navigasi Keyboard --> --}}
           <input
                type="text"
                x-model="keyword"
                x-ref="barcodeInput"
                
                @if($stockOpname->status=='POSTED')
                disabled
                @endif
                
                @input="searchProduct"
                
                
                @keydown.arrow-down.prevent="if (results.length > 0) selectedIndex = (selectedIndex + 1) % results.length"
                @keydown.arrow-up.prevent="if (results.length > 0) selectedIndex = (selectedIndex - 1 + results.length) % results.length"
                @keydown.enter.prevent="
                    if (selectedIndex >= 0 && results[selectedIndex]) { 
                        selectProduct(results[selectedIndex]); 
                    } else { 
                        searchProduct(); 
                    }
                "
                @keydown.escape="results = []; selectedIndex = -1;"

                placeholder="Scan barcode / ketik nama barang..."
                class="w-full border rounded-lg p-2"
            >

            <!-- Dropdown Hasil Pencarian -->
            <div
                x-show="results.length"
                class="border rounded-lg mt-2 bg-white max-h-56 overflow-auto"
            >
                <template
                    x-for="(item, index) in results"
                    :key="item.id"
                >
                    <!-- Ditambahkan class dynamic bg-purple-100 saat index terpilih -->
                    <div
                        class="p-2 hover:bg-gray-100 cursor-pointer transition-colors"
                        :class="{ 'bg-purple-100 font-semibold': selectedIndex === index }"
                        @click="selectProduct(item)"
                        @mouseenter="selectedIndex = index"
                    >
                        <div x-text="item.nama_barang"></div>
                        <small class="text-gray-500">
                            Kode: <span x-text="item.kode_barang"></span> | 
                            Stok: <span x-text="item.stok"></span>
                        </small>
                    </div>
                </template>
            </div>

            <input
                type="hidden"
                name="product_id"
                :value="selected.id"
            >
            {{-- scan produk end--}}

            {{-- data hasil scan --}}
            {{-- x-show="selected.id" --}}
            <div class="mt-4 space-y-3" >
                <div>

                    <label>

                        Kode Barang

                    </label>

                    <input
                        readonly
                        class="w-full border rounded-lg p-2 bg-gray-100"
                        :value="selected.kode_barang ?? '-'"
                    >

                </div>

                <div>

                    <label>

                    Nama Barang

                    </label>

                    <input
                        readonly
                        class="w-full border rounded-lg p-2 bg-gray-100"
                        :value="selected.nama_barang ?? '-'"
                    >

                </div>

                <div>

                    <label>

                    Stok Sistem

                    </label>

                    <input
                        readonly
                        class="w-full border rounded-lg p-2 bg-gray-100"
                        :value="selected.stok ?? 0"
                    >

                </div>

<!-- Input Stok Fisik / Jumlah Ditemukan -->
                <div>
                    <label class="font-semibold block mb-1">
                        <!-- Label Berganti Secara Otomatis -->
                        <span x-text="getScannedItem(selected.id) ? 'Jumlah ditemukan' : 'Stok Fisik'"></span>
                    </label>

                    <input
                        type="number"
                        name="stok_fisik"
                        x-model="stokFisik"
                        x-ref="stokFisikInput"

                        @keydown.enter.prevent="
                            document
                            .getElementById('formScanSO')
                            .requestSubmit()
                        "

                        @if($stockOpname->status=='POSTED')
                        disabled
                        @endif
                        required
                        class="w-full border rounded-lg p-2"
                        :class="getScannedItem(selected.id) ? 'border-purple-400 bg-purple-50/50' : 'border-gray-300'"
                    >
                </div>

                <!-- Hasil Selisih Aktual Komputer -->
                <div>
                    <label class="font-semibold block mb-1">
                        Selisih
                    </label>

                    <input
                        readonly
                        class="w-full border rounded-lg p-2 font-bold"
                        :class="{
                            'text-green-600': getComputedSelisih() > 0,
                            'text-red-600': getComputedSelisih() < 0,
                            'text-gray-700': getComputedSelisih() == 0
                        }"
                        :value="getComputedSelisih()"
                    >
                </div>

                <div class="mt-4">

                    <label class="font-semibold">
                        Keterangan
                    </label>

                    <input
                        type="text"
                        name="notes"
                        class="w-full border rounded-lg p-2"
                    >

                </div>


                {{-- button simpan --}}
                <div class="mt-6">
                    
                    {{-- kalo udah diclosing/diposting maka button hilang--}}
                    @if($stockOpname->status=='OPEN')
                        <button
                            type="submit"
                            
                            class="bg-indigo-600 hover:bg-indigo-700 text-white px-6 py-2 rounded-lg"
                        >
                            Simpan Stok Opname
                        </button>
                    @endif

                    {{-- tambahan info --}}
                    <hr class="my-8">

                        <h3 class="text-xl font-bold mb-4">

                            Barang yang sudah di Scan

                        </h3>

                        <div class="bg-white rounded-xl shadow">

                        <table class="w-full">

                        <thead class="bg-gray-100">

                        <tr>

                        <th class="p-3 text-left">
                        Kode
                        </th>

                        <th>
                        Barang
                        </th>

                        <th class="text-center">
                        Sistem
                        </th>

                        <th class="text-center">
                        Fisik
                        </th>

                        <th class="text-center">
                        Selisih
                        </th>

                        </tr>

                        </thead>

                        <tbody id="tbodySO">

                        @forelse($details as $item)

                        <tr class="border-t">

                        <td class="p-3">

                        {{ $item->product->kode_barang }}

                        </td>

                        <td>

                        {{ $item->product->nama_barang }}

                        </td>

                        <td class="text-center">

                        {{ $item->stock_system }}

                        </td>

                        <td class="text-center">

                        {{ $item->stock_physical }}

                        </td>

                        <td class="text-center">

                        {{ $item->difference }}

                        </td>

                        </tr>

                        @empty

                        <tr>

                        <td
                        colspan="5"
                        class="text-center py-6 text-gray-500"
                        >

                        Belum ada barang.

                        </td>

                        </tr>

                        @endforelse

                        </tbody>

                        </table>

                        </div>
                    {{-- tambahan info end --}}
                </div>

            </div>

        

        
    </form>
    {{-- jika SO blm diclosing/diposting --}}
    

    <div class="mt-6 flex gap-3">
    @if($stockOpname->status=='OPEN')    
    <form
        id="formFinishSO"
        method="POST"
        action="/stock-opname/{{ $stockOpname->id }}/finish"
    >

    @csrf

    <button
    type="submit"
    class="bg-green-600 hover:bg-green-700 text-white px-6 py-2 rounded-lg"
    >

    Posting Stock Opname

    </button>

    

    </form>
    @endif
    {{-- jika SO blm diclosing/diposting end--}}


    {{-- button cetak --}}
    <a
    href="/stock-opname/{{ $stockOpname->id }}/print"
    target="_blank"
    class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg "
    >

    Cetak

    </a>
    {{-- button cetak end --}}

    </div>

    

    

</div>
<script>

    function stockAdjustment()
    {
        return {
            keyword:'',
            results:[],
            selected:{},
            stokFisik:0,
            selectedIndex: -1,
            
            // Masukkan data barang yang sudah di-scan dari server ke state Alpine.js
            scannedItems: @json($details->map(function($d) {
                return [
                    'product_id' => $d->product_id,
                    'stock_system' => $d->stock_system, // <-- Tambahkan ini untuk merekam stok sistem awal (140)
                    'stock_physical' => $d->stock_physical
                ];
            })),

            init()
            {
                this.$watch('selected.id', (id) => {
                    if (!id) return;
                    requestAnimationFrame(() => {
                        this.$refs.stokFisikInput?.focus();
                        this.$refs.stokFisikInput?.select();
                    });
                });

                this.$nextTick(() => {
                    this.$refs.barcodeInput.focus();
                });
            },
            
            async searchProduct()
            {
                if(this.keyword.length < 1)
                {
                    this.results=[];
                    this.selectedIndex = -1;
                    return;
                }

                // let r = await fetch('/api/retur/search-products?q=' + encodeURIComponent(this.keyword));
                let r = await fetch('/api/products/search?q=' + encodeURIComponent(this.keyword));
                this.results = await r.json();
                this.selectedIndex = -1;

                if(this.results.length == 1)
                {
                    this.selectProduct(this.results[0]);
                }
            },

            // Helper untuk mengecek apakah produk sudah pernah di-scan
            getScannedItem(productId) {
                return this.scannedItems.find(item => item.product_id === productId);
            },

            selectProduct(item)
            {
                this.selected = item;
                
                // Cek status riwayat scan produk ini
                let history = this.getScannedItem(item.id);
                if (history) {
                    // Scan Ke-2 dst: Default input fisik diisi 0 (tinggal input jumlah ditemukan saja)
                    this.stokFisik = 0; 
                } else {
                    // Scan Ke-1: Default disamakan dengan stok sistem awal
                    this.stokFisik = item.stok;
                }

                this.results = [];
                this.keyword = item.nama_barang;
                this.selectedIndex = -1;
            },

            // Mengembalikan nilai selisih aktual secara reaktif ke UI komputer
            getComputedSelisih() {
                if (!this.selected.id) return 0;
                
                let history = this.getScannedItem(this.selected.id);
                let qtyDitemukanSekarang = parseInt(this.stokFisik || 0);
                
                if (history) {
                    // SEPERTI DI SKRINŠUT: Total Fisik Komulatif = (Fisik Sebelumnya + Input Ditemukan Baru)
                    let totalFisikBaru = parseInt(history.stock_physical) + qtyDitemukanSekarang;
                    let stokSistemAwal = parseInt(history.stock_system); // Angka 140
                    
                    // Rumus Selisih Aktual Opname: Total Fisik Komulatif - Stok Sistem Awal
                    return totalFisikBaru - stokSistemAwal; 
                } else {
                    // Scan Ke-1 normal: Stok Fisik Baru - Stok Sistem
                    let systemAwal = parseInt(this.selected.stok ?? 0);
                    return qtyDitemukanSekarang - systemAwal;
                }
            },

            resetForm()
            {
                this.keyword='';
                this.results=[];
                this.selected={};
                this.stokFisik=0;
                this.selectedIndex = -1;
                this.$nextTick(()=>{
                    this.$refs.barcodeInput.focus();
                });
            }
        }
    }

    
    // ajax2
    document
    .getElementById('formScanSO')
    .addEventListener('submit', async function(e){
        console.log("submit ajax");
        e.preventDefault();

        let form = this;
        let url = form.action;
        let formData = new FormData(form);

        try {
            let response = await fetch(url, {
                method: 'POST',
                headers: {
                    'Accept': 'application/json'
                },
                body: formData
            });

            let result = await response.json();

            if(result.success){
                Swal.fire({
                    toast: true,
                    position: 'center',
                    icon: 'success',
                    title: 'Sukses',
                    text: result.message,
                    showConfirmButton: false,
                    timer: 800
                });

                // Cek jika response-nya adalah update akumulasi data lama
                if (result.is_update) {
                    // Reload halaman agar tabel di bawah otomatis menampilkan hitungan terbaru dari DB
                    setTimeout(() => {
                        window.location.reload();
                    }, 800);
                } else {
                    // Jika barang benar-benar baru, masukkan langsung ke tabel tanpa reload
                    tambahBaris(result.detail);
                    
                    let alpineElement = document.querySelector('[x-data="stockAdjustment()"]');
                    if (alpineElement && window.Alpine) {
                        Alpine.$data(alpineElement).resetForm();
                    }
                    form.querySelector('input[name="notes"]').value = '';
                }
            }

        }
        catch(error){
            console.error("Detail Error JS:", error); 
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'Terjadi kesalahan sistem di browser: ' + error.message
            });
        }
    });


    // tambah baris baru via AJAX
    function tambahBaris(item){
        let tbody = document.getElementById('tbodySO');

        // --- CEK & HAPUS BARIS "BELUM ADA BARANG" JIKA ADA ---
        if (tbody.rows.length === 1 && tbody.rows[0].cells.length === 1) {
            tbody.innerHTML = '';
        }

        tbody.insertAdjacentHTML(
            'beforeend',
            `
            <tr class="border-t">
                <td class="p-3">${item.kode_barang}</td>
                <td>${item.nama_barang}</td>
                <td class="text-center">${item.stock_system}</td>
                <td class="text-center">${item.stock_physical}</td>
                <td class="text-center">${item.difference}</td>
            </tr>
            `
        );
    }

    // ===============================
    // Posting Stock Opname
    // ===============================

    document
    .getElementById('formFinishSO')
    ?.addEventListener('submit',function(e){

        e.preventDefault();

        let form=this;

        Swal.fire({

            icon:'question',

            title:'Posting Stock Opname?',

            html:`
                Setelah diposting,
                <br>
                <b>Stock Opname tidak dapat diedit lagi.</b>
            `,

            showCancelButton:true,

            confirmButtonText:'Ya, Posting',

            cancelButtonText:'Batal',

            confirmButtonColor:'#16a34a'

        })
        .then((result)=>{

            if(result.isConfirmed){

                form.submit();

            }

        });

    });
</script>
@endsection