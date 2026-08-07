@extends('layouts.app')

@section('title','Buat Purchase Order')

@section('content')

<x-page-header
    title="Edit Purchase Order"
    subtitle="Edit Draft Purchase Order"
>
    <x-slot:action>

        <a href="{{ route('purchasing.index') }}">

            <x-button color="gray" type="button">

                <i class="ri-arrow-left-line"></i>

                Kembali

            </x-button>

        </a>

    </x-slot:action>
</x-page-header>

<form
    method="POST"
    action="{{ route('purchasing.update',$po) }}"
>

@csrf
@method('PUT')

    <x-card>

        <div class="grid md:grid-cols-3 gap-6">

            <x-input
                label="Nomor PO"
                name="po_number"
                readonly
                :value="$po->po_number"
                icon="ri-file-list-3-line"
            />

            <x-input
                label="Tanggal"
                name="po_date"
                type="date"
                :value="$po->po_date"
            />

            <x-select
                label="Supplier"
                name="supplier_id"
                required
                icon="ri-truck-line"
            >

                <option value="">-- Pilih Supplier --</option>

                @foreach($suppliers as $supplier)

                <option

                    value="{{ $supplier->id }}"

                    @selected($po->supplier_id==$supplier->id)

                >

                    {{ $supplier->nama }}

                </option>

                @endforeach

            </x-select>

        </div>

    </x-card>



    <div class="grid lg:grid-cols-3 gap-6 mt-6">

        {{-- kiri --}}
        <div class="lg:col-span-1">

            <x-card>

                <div class="font-semibold mb-4">

                    Cari Produk

                </div>

                {{-- <x-input

                    name="search"

                    placeholder="Barcode / Kode / Nama Produk"

                    icon="ri-search-line"

                    autocomplete="off"

                /> --}}
                <x-input

                    id="search-product"

                    name="search"

                    placeholder="Barcode / Kode / Nama Produk"

                    icon="ri-search-line"

                    autocomplete="off"

                />

                <div
                    id="product-result"
                    class="mt-4 rounded-xl border bg-white"
                >

                    <div class="p-8 text-center text-slate-400">

                        Ketik nama / barcode produk

                    </div>

                </div>

            </x-card>

        </div>


        {{-- kanan --}}
        <div class="lg:col-span-2">

            <x-card>

                <div class="flex justify-between items-center mb-4">

                    <div>

                        <h3 class="font-semibold">

                            Item Purchase Order

                        </h3>

                        <p class="text-sm text-slate-500">

                            Produk yang akan dipesan

                        </p>

                    </div>

                </div>


                <x-table>

                    <x-table-header>

                        <tr>

                            <x-table-head>Produk</x-table-head>

                            <x-table-head class="text-center">

                                Qty

                            </x-table-head>

                            <x-table-head class="text-right">

                                Harga

                            </x-table-head>

                            <x-table-head class="text-right">

                                Subtotal

                            </x-table-head>

                            <x-table-head class="text-center">

                                Aksi

                            </x-table-head>

                        </tr>

                    </x-table-header>

                    <tbody id="po-items">

                        <tr>

                            <td colspan="5">

                                <x-empty-state

                                    icon="ri-shopping-basket-line"

                                    title="Belum ada produk"

                                    description="Cari produk di sebelah kiri."

                                />

                            </td>

                        </tr>

                    </tbody>

                </x-table>


                <div class="border-t mt-6 pt-6">

                    <div class="flex justify-end">

                        <table class="text-sm">

                            <tr>

                                <td class="pr-10 py-2">

                                    Total

                                </td>

                                <td

                                    class="font-bold text-xl text-right"

                                    id="grand-total"

                                >

                                    Rp 0

                                </td>

                            </tr>

                        </table>

                    </div>

                </div>

            </x-card>

        </div>

    </div>


    <div class="flex justify-end gap-3 mt-6">

        <a href="{{ route('purchasing.index') }}">
            <x-button color="secondary" type="button" full>
                <i class="ri-close-circle-line text-red-500 text-base"></i>
                Batal
            </x-button>
        </a>

        <x-button
            color="orange"
            type="submit"
            name="action"
            value="draft"
            full
        >
            <i class="ri-save-line"></i>
            Simpan Draft
        </x-button>

        <x-button
            color="green"
            type="submit"
            name="action"
            value="ordered"
            full
        >
            <i class="ri-printer-line"></i>
            Order & Cetak
        </x-button>

    </div>

</form>
    @push('scripts')

    <script>

    const search=document.getElementById('search-product');
    const result=document.getElementById('product-result');

    const poItems=document.getElementById('po-items');

    let cart = @json($cart);

    renderTable();

    let timer;

    search.addEventListener('keyup',function(){

        clearTimeout(timer);

        const q=this.value.trim();

        if(q.length<2){

            result.innerHTML=`
                <div class="p-8 text-center text-slate-400">
                    Ketik minimal 2 karakter
                </div>
            `;

            return;

        }

        timer=setTimeout(()=>{

            fetch(`/api/products/search?q=${encodeURIComponent(q)}`)

            .then(r=>r.json())

            .then(data=>{

                if(data.length===0){

                    result.innerHTML=`
                        <div class="p-6 text-center text-slate-400">
                            Produk tidak ditemukan
                        </div>
                    `;

                    return;

                }

                let html='';

                data.forEach(item=>{

                    html+=`

                    <div

                        class="border-b p-3 hover:bg-indigo-50 cursor-pointer product-row"

                        data-id="${item.id}"

                        data-name="${item.nama_barang}"

                        data-price="${item.harga}"

                    >

                        <div class="font-semibold">

                            ${item.nama_barang}

                        </div>

                        <div class="text-sm text-slate-500">

                            ${item.kode_barang}

                        </div>

                    </div>

                    `;

                });

                result.innerHTML=html;

                document.querySelectorAll('.product-row').forEach(row=>{

                    row.onclick=function(){

                        const id=this.dataset.id;

                        const existing=cart.find(x=>x.id==id);

                        if(existing){

                            existing.qty++;

                        }else{

                            cart.push({

                                id:id,

                                name:this.dataset.name,

                                price:Number(this.dataset.price),

                                qty:1

                            });

                        }

                        renderTable();

                    }

                });

            });

        },300);

    });

    function updateGrandTotal(){

        let total = 0;

        cart.forEach(item=>{

            total += item.qty * item.price;

        });

        document.getElementById('grand-total').innerHTML =
            'Rp ' + total.toLocaleString('id-ID');

    }

    function renderTable(){

        if(cart.length===0){

            poItems.innerHTML=`
            <tr>
                <td colspan="5">
                    <div class="text-center py-10 text-slate-400">
                        Belum ada produk
                    </div>
                </td>
            </tr>
            `;

            document.getElementById('grand-total').innerHTML='Rp 0';

            return;
        }

        let total=0;

        let html='';

        cart.forEach((item,index)=>{

            item.subtotal=item.qty*item.price;

            total+=item.subtotal;

            html+=`

            <tr>

                <td>

                    ${item.name}

                    <input
                        type="hidden"
                        name="product_id[]"
                        value="${item.id}"
                    >

                </td>

                <td class="text-center">

                    <input

                        type="number"

                        min="1"

                        value="${item.qty}"

                        data-index="${index}"

                        class="qty border rounded w-20 text-center"

                    >

                    <input

                        type="hidden"

                        name="qty[]"

                        value="${item.qty}"

                        id="qty-hidden-${index}"

                    >

                </td>

                <td class="text-right">

                    <input

                        type="number"

                        min="0"

                        value="${item.price}"

                        data-index="${index}"

                        class="price border rounded w-24 text-right"

                    >

                    <input

                        type="hidden"

                        name="price[]"

                        value="${item.price}"

                        id="price-hidden-${index}"

                    >

                </td>

                <td class="text-right subtotal">

                    Rp ${Number(item.subtotal).toLocaleString('id-ID')}

                </td>

                <td class="text-center">

                    <button

                        type="button"

                        class="delete text-red-600"

                        data-index="${index}"

                    >

                        <i class="ri-delete-bin-line"></i>

                    </button>

                </td>

            </tr>

            `;

        });

        poItems.innerHTML=html;

        document.getElementById('grand-total').innerHTML=
            'Rp '+Number(total).toLocaleString('id-ID');

    }

    document.addEventListener('input',function(e){

        /*
        =========================================
        Qty berubah
        =========================================
        */

        if(e.target.classList.contains('qty')){

            const index = e.target.dataset.index;

            cart[index].qty = parseInt(e.target.value) || 0;

            document.getElementById(
                'qty-hidden-'+index
            ).value = cart[index].qty;

            cart[index].subtotal =
                cart[index].qty * cart[index].price;

            e.target
                .closest('tr')
                .querySelector('.subtotal')
                .innerHTML =
                'Rp ' +
                cart[index].subtotal.toLocaleString('id-ID');

            updateGrandTotal();

        }

        /*
        =========================================
        Harga berubah
        =========================================
        */

        if(e.target.classList.contains('price')){

            const index = e.target.dataset.index;

            cart[index].price = parseInt(e.target.value) || 0;

            document.getElementById(
                'price-hidden-'+index
            ).value = cart[index].price;

            cart[index].subtotal =
                cart[index].qty * cart[index].price;

            e.target
                .closest('tr')
                .querySelector('.subtotal')
                .innerHTML =
                'Rp ' +
                cart[index].subtotal.toLocaleString('id-ID');

            updateGrandTotal();

        }

    });

    // tmbol hapus
    document.addEventListener('click',function(e){

        const btn=e.target.closest('.delete');

        if(!btn)return;

        cart.splice(btn.dataset.index,1);

        renderTable();

    });
    </script>

    @endpush
@endsection