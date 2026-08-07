<aside
class="w-64 bg-white border-r border-zinc-100 shadow-lg z-30 flex flex-col">

    {{-- Logo --}}
    <div class="px-6 py-6 ">

        <div class="flex items-center gap-3">

            <div
                class="w-11 h-11 rounded-xl
                bg-indigo-600
                text-white
                flex items-center justify-center">

                <i class="ri-shopping-cart-2-line text-2xl"></i>

            </div>

            <div>

                <h1 class="font-bold text-lg">

                    POS Minimarket

                </h1>

                <p class="text-xs text-slate-500">

                    Sistem Penjualan

                </p>

            </div>

        </div>

    </div>

    {{-- Menu --}}
    <nav class="flex-1 py-5">

        <p
            class="px-6 mb-2
            text-xs font-bold
            uppercase tracking-wider
            text-slate-400">

            Menu Utama

        </p>

        @php

        $menus=[

            [
                'title'=>'Dashboard',
                'url'=>'/dashboard',
                'icon'=>'ri-home-5-line'
            ],

            [
                'title'=>'POS',
                'url'=>'/pos',
                'icon'=>'ri-shopping-cart-2-line'
            ],

            [
                'title'=>'Transaksi',
                'url'=>'/transactions',
                'icon'=>'ri-file-list-3-line'
            ],

            [
                'title'=>'Produk',
                'url'=>'/products',
                'icon'=>'ri-box-3-line'
            ],

            [
                'title'=>'Import Produk',
                'url'=>'/products/import',
                'icon'=>'ri-file-excel-2-line'
            ],            

            [
                'title'=>'Kategori',
                'url'=>'/categories',
                'icon'=>'ri-price-tag-3-line'  
            ],

            [
                'title' => 'Pelanggan',
                'url' => '/customers',
                'icon' => 'ri-user-star-line'
            ],

            [
                'title'=>'Supplier',
                'url'=>'/suppliers',
                'icon'=>'ri-truck-line'
            ],

            [
                'title'=>'Purchasing',
                'url'=>'/purchasing',
                'icon'=>'ri-file-paper-2-line'
            ],

            [
                'title'=>'Stock Opname',
                'url'=>'/stock-opname',
                'icon'=>'ri-clipboard-line'
            ],

        ];

        @endphp

        @foreach($menus as $menu)

        <a

            href="{{ $menu['url'] }}"

            class="mx-3 mb-1
            flex items-center gap-3
            rounded-xl
            px-4 py-3
            transition

            {{-- {{ request()->is(ltrim($menu['url'],'/').'*')
                ? 'bg-indigo-50 text-indigo-600 border-l-4 border-indigo-600'
                : 'text-slate-700 hover:bg-slate-100'
            }}" --}}
            {{ 
                ($menu['url'] === '/products' && request()->is('products/import*'))
                ? 'text-slate-700 hover:bg-slate-100'
                : (request()->is(ltrim($menu['url'],'/').'*')
                    ? 'bg-indigo-50 text-indigo-600 border-l-4 border-indigo-600'
                    : 'text-slate-700 hover:bg-slate-100')
            }}

        >

            <i class="{{ $menu['icon'] }} text-lg"></i>

            <span>

                {{ $menu['title'] }}

            </span>

        </a>

        @endforeach


        <p
            class="px-6 mt-8 mb-2
            text-xs font-bold
            uppercase tracking-wider
            text-slate-400">

            Master Data

        </p>

        <a

            href="/users"

            class="mx-3
            flex items-center gap-3
            rounded-xl
            px-4 py-3

            {{ request()->is('users*')
                ? 'bg-indigo-50 text-indigo-600 border-l-4 border-indigo-600'
                : 'text-slate-700 hover:bg-slate-100'
            }}"

        >

            <i class="ri-user-settings-line text-lg"></i>

            User

        </a>

    </nav>

    {{-- Logout --}}
    <div class="border-t p-4">

        <form
            method="POST"
            action="{{ route('logout') }}">

            @csrf

            <button

                class="w-full
                flex items-center gap-3
                px-4 py-3
                rounded-xl
                hover:bg-red-50
                text-red-600
                transition"

            >

                <i class="ri-logout-box-r-line"></i>

                Logout

            </button>

        </form>

    </div>

</aside>