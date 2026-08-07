@extends('layouts.app')

@section('title','Dashboard')

@section('content')


    <div class="mb-8 ">
            <h2
                class="text-2xl font-bold"
            >
                Dashboard
            </h2>
            <p class="text-slate-500 mt-1">
                Ringkasan aktivitas hari ini
            </p>
    </div>
    <div class="grid grid-cols-1 md:grid-cols-3 xl:grid-cols-6 gap-4 ">
        
        <x-stat-card

            title="Penjualan"

            :value="'Rp '.number_format($todaySales,0,',','.')"

            color="green"

            icon="ri-money-dollar-circle-line"

            subtitle="Hari ini"

        />

        <x-stat-card

            title="Transaksi"

            :value="number_format($todayTransactions,0,',','.')"

            color="blue"

            icon="ri-shopping-cart-2-line"

            subtitle="Hari ini"

        />

        
        <!-- 💡 CARD PRODUK DENGAN DETEKSI WARNING HPP 0 -->
        @if($hargaBeliNolCount > 0)
            <!-- KONDISI WARNING: Card berubah merah, ada efek pulse lembut, dan border merah -->
            <div class="bg-red-600 text-white rounded-xl shadow p-5 border-2 border-red-400 animate-pulse relative overflow-hidden flex flex-col justify-between">
                <div>
                    <div class="flex justify-between items-center opacity-90 text-sm">
                        <span>Produk</span>
                        <i class="ri-error-warning-fill text-xl"></i>
                    </div>
                    <div class="text-3xl font-bold mt-2">
                        {{ number_format($totalProducts, 0, ',', '.') }}
                    </div>
                </div>
                <div class="mt-4 pt-2 border-t border-red-500">
                    <a href="{{ route('products.index', ['filter' => 'harga_beli_nol']) }}" class="text-xs bg-white text-red-700 px-2 py-1 rounded font-bold hover:bg-red-50 transition block text-center shadow-sm">
                        ⚠️ {{ $hargaBeliNolCount }} Produk HPP Rp 0
                    </a>
                </div>
            </div>
        @else
            <!-- KONDISI NORMAL: Kembali ke komponen stat-card orange bawaan bos jika aman -->
            <x-stat-card
                title="Produk"
                :value="number_format($totalProducts, 0, ',', '.')"
                color="orange"
                icon="ri-box-3-line"
                subtitle="Total Produk"
            />
        @endif
        <x-stat-card

            title="Stock"

            :value="number_format($totalStock,0,',','.')"

            color="purple"

            icon="ri-archive-stack-line"

            subtitle="Total Stock"

        />

        <x-stat-card

            title="Stock Opname"

            :value="$lastOpname?->opname_no ?? '-'"

            color="indigo"

            icon="ri-clipboard-line"

            subtitle="terakhir"

        />

        
    </div>


    <div class="bg-white rounded-xl shadow p-5 mt-6">
        <h3 class="font-bold mb-4">
            Penjualan 7 Hari Terakhir
        </h3>
        <canvas id="salesChart"></canvas>
    </div>

    
    <!-- tambahan -->
     <div class="grid md:grid-cols-3 gap-4 mt-6">
        
        

    
        <div class="bg-white rounded-xl shadow p-4 min-h-[350px]">

            <h3 class="font-bold mb-3">
                Stok Menipis
            </h3>

            <table class="w-full">

                @forelse($lowStocks as $item)

                <tr class="border-b">

                    <td class="py-2">
                        {{ $item->nama_barang }}
                    </td>


                    <td class="text-right">
                        <span class="bg-red-100 text-red-700 px-2 py-1 rounded">
                            {{ $item->stok }}
                        </span>
                    </td>

                </tr>

                @empty

                <tr>

                    <td class="text-gray-500">
                        Tidak ada stok menipis
                    </td>

                </tr>

                @endforelse

            </table>

        </div>
    

        <div class="bg-white rounded-xl shadow p-4 min-h-[350px]">

                <h3 class="font-bold mb-3">
                    Transaksi Terakhir
                </h3>

                <table class="w-full">

                    @foreach($latestTransactions as $trx)

                    <tr class="border-b">

                        {{-- <td class="py-2">
                            {{ $trx->no_nota }}
                        </td> --}}

                        <td class="py-2">

                            <div class="font-semibold">

                            {{ $trx->no_nota }}

                            </div>

                            <div class="text-xs text-gray-500">

                            {{ $trx->created_at->format('H:i') }}

                            </div>

                        </td>

                        <td class="text-right">

                            Rp
                            {{ number_format(
                                $trx->grand_total,
                                0,
                                ',',
                                '.'
                            ) }}

                        </td>

                    </tr>

                    @endforeach

                </table>

           

        </div>


        <div class="bg-white rounded-xl shadow p-4 min-h-[350px]">

            <h3 class="font-bold mb-3">
                Produk Terlaris
            </h3>

            <table class="w-full">

                @forelse($topProducts as $item)

                {{-- @php
                $rank = $loop->iteration;
                @endphp --}}

                <tr class="border-b">

                    <td class="py-2">
                        {{ $item->nama_barang }}
                    </td>
                    

                    <td class="text-right font-bold">

                        {{ number_format(
                            $item->total_terjual
                        ) }}

                        pcs

                    </td>

                </tr>

                @empty

                <tr>

                    <td class="text-gray-500">
                        Belum ada penjualan
                    </td>

                </tr>

                @endforelse

            </table>

        </div>

        

    </div>

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>

        const ctx =
        document
        .getElementById('salesChart');

        new Chart(ctx,{

            type:'line',

            data:{

                labels:[
                    @foreach($salesChart as $row)
                        "{{ $row['tanggal'] }}",
                    @endforeach
                ],

                datasets:[{

                    label:'Penjualan',

                    data:[
                        @foreach($salesChart as $row)
                            {{ $row['total'] }},
                        @endforeach
                    ],

                    borderWidth:3,

                    fill:false,

                    tension:.3

                }]

            },

            options:{

                responsive:true,

                plugins:{

                    legend:{

                        display:false

                    }

                }

            }

        });

    </script>

@endpush

@endsection