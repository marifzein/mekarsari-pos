<!DOCTYPE html>
<html lang="id">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <meta
        name="csrf-token"
        content="{{ csrf_token() }}"
    >

    <title>
        @yield('title','POS Minimarket')
    </title>
    <meta property="og:type" content="website">
    <meta property="og:title" content="POS Minimarket">
    <meta property="og:description" content="Sistem Aplikasi Point of Sales & Manajemen Stok Handal.">
    <meta property="og:url" content="{{ url()->current() }}">

    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">
    @vite([
        'resources/css/app.css',
        'resources/js/app.js'
    ])

    {{-- <link
        href="https://cdn.jsdelivr.net/npm/remixicon@4.6.0/fonts/remixicon.css"
        rel="stylesheet"
    /> --}}
    {{-- <link href="{{ asset('css/remixicon.css') }}" rel="stylesheet" /> --}}

    {{-- <script defer
        src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js">
    </script> --}}
    <script defer src="{{ asset('js/alpine.min.js') }}"></script>

    {{-- <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script> --}}
    <script src="{{ asset('js/sweetalert2.all.min.js') }}"></script>

    {{-- sidemenu --}}
    <style>

aside{
    background:#0A2947;
    color:#E2E8F0;
}

.menu-group{
    color:#E2E8F0;
    font-weight:600;
}

.menu-parent{
    color:#CBD5E1;
    transition:all .25s ease;
}

.menu-parent:hover{
    background:#123A61;
    color:#fff;
}

.submenu{
    display:block;
    padding:10px 20px 10px 48px;
    color:#94AFC7;
    border-left:4px solid transparent;
    transition:all .25s ease;
}

.submenu:hover{
    background:#123A61;
    color:#fff;
    padding-left:54px;
}

.submenu-active{
    background:#1A4F80;
    color:#fff;
    border-left:4px solid #60A5FA;
}

/* ---------- Accordion ---------- */

.menu-content{

    overflow:hidden;

    max-height:0;

    opacity:0;

    transition:
        max-height .35s cubic-bezier(.4,0,.2,1),
        opacity .25s ease;

}

.menu-content.open{

    max-height:500px;

    opacity:1;

}

/* ---------- Footer ---------- */

.sidebar-footer{
    border-top:1px solid #123A61;
}

.logout-btn{
    color:#F87171;
}

.logout-btn:hover{
    background:#7F1D1D;
    color:#fff;
}

/* ---------- Arrow ---------- */

#icon-kasir,
#icon-master,
#icon-inventory,
#icon-penjualan,
#icon-laporan,
#icon-akunting,
#icon-system{

    transition:transform .30s ease;

}

.rotate{

    transform:rotate(90deg);

}

/* ---------- Scrollbar ---------- */

aside::-webkit-scrollbar{
    width:6px;
}

aside::-webkit-scrollbar-thumb{
    background:#2A5D87;
    border-radius:20px;
}

aside::-webkit-scrollbar-track{
    background:#0A2947;
}


/* Menyembunyikan scrollbar tapi fungsi scroll tetap aktif */
.no-scrollbar::-webkit-scrollbar {
    display: none;
}
.no-scrollbar {
    -ms-overflow-style: none;  /* IE dan Edge */
    scrollbar-width: none;  /* Firefox */
}

</style>


    {{-- sidemenu --}}

</head>


{{-- <body class="bg-slate-100 text-slate-800"> --}}
<body class="bg-slate-100 text-slate-800" x-data="{ sidebarOpen: true }">

    {{-- <div class="flex h-screen"> --}}
    <div class="flex h-screen overflow-hidden">

        {{-- Sidebar --}}
        @include('layouts.partials.sidebar')

        {{-- Content --}}
        {{-- <div class="flex-1 flex flex-col bg-slate-100"> --}}
        
        {{-- Content Area --}}
        <div class="flex-1 flex flex-col bg-slate-100 min-w-0 transition-all duration-300 ease-in-out h-screen overflow-hidden">
                
            {{-- Header / Topbar (Fixed di atas, tidak ikut scroll) --}}
            @include('layouts.partials.topbar')

            {{-- Area Tengah: Konten utama yang scrollable --}}
            <main class="flex-1 overflow-y-auto bg-slate-50">
                <div class="p-8 pb-24"> {{-- Ditambah padding bottom agar konten terbawah tidak ketutup footer --}}
                    <div class="rounded-2xl">
                        @yield('content')
                    </div>
                </div>
            </main>

            {{-- Minimalist Footer: Fixed di bawah main content, terbagi 3 section --}}
            <footer class="h-9 bg-white border-t border-slate-200 shadow-[0_-2px_10px_rgba(0,0,0,0.03)] flex items-center justify-between px-6 text-xs text-slate-500 z-30 select-none">
                
                {{-- Section Kiri: Status / Cabang --}}
                <div class="flex items-center gap-2">
                    <span class="w-2 h-2 rounded-full bg-emerald-500 inline-block animate-pulse"></span>
                    <span>DaCen: <strong class="text-slate-700">{{ $footerData->section_left }}</strong></span>
                </div>

                {{-- Section Tengah: Info Shortcut / Kasir Aktif --}}
                <div class="hidden md:flex items-center gap-4 text-slate-400">
                    <span>Kasir: <strong class="text-slate-600">{{ Auth::user()->name }}</strong></span>
                    <span>•</span>
                    <span>Modul: <strong class="text-indigo-600">{{ $footerData->section_center }}</strong></span>
                </div>

                {{-- Section Kanan: Hak Cipta / Lisensi --}}
                <div class="flex items-center gap-1 font-mono text-[11px]">
                    <span>&copy; {{ date('Y') }} {{ $footerData->section_right }}</span>
                </div>

            </footer>

        </div> {{-- End flex-col Content --}}

    </div> {{-- End H-Screen Flex --}}

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>    


{{-- pesan2 sesuai status transaksi success,error dsb --}}
    @if(session('success'))

    <script>

    document.addEventListener('DOMContentLoaded', function () {

        Swal.fire({

            icon: 'success',

            title: 'Sukses',

            text: @json(session('success')),

            timer: 1200,

            showConfirmButton: false,

            confirmButtonColor: '#4F46E5'

        });

    });

    </script>

    @endif


    @if(session('error'))

    <script>

    document.addEventListener('DOMContentLoaded', function () {

        Swal.fire({

            icon: 'error',

            title: 'Terjadi Kesalahan',

            text: @json(session('error')),

            confirmButtonColor: '#DC2626'

        });

    });

    </script>

    @endif


    @if(session('warning'))

    <script>

    document.addEventListener('DOMContentLoaded', function () {

        Swal.fire({

            icon: 'warning',

            title: 'Perhatian',

            text: @json(session('warning')),

            confirmButtonColor: '#F59E0B'

        });

    });

    </script>

    @endif
{{--pesan2 end --}}

<script>

const menus=[
    'kasir',
    'master',
    'inventory',
    'laporan',
    'system',
    'akunting',
];

function toggleMenu(name){

    menus.forEach(function(item){

        const menu=document.getElementById('menu-'+item);

        const icon=document.getElementById('icon-'+item);

        // 💡 PROTEKSI: Hanya jalankan jika elemennya memang ada di layar!
        if (menu && icon) { 
            if (item === name) {
                if (menu.classList.contains('open')) {
                    menu.classList.remove('open');
                    icon.classList.remove('rotate');
                    localStorage.removeItem('activeMenu');
                } else {
                    menu.classList.add('open');
                    icon.classList.add('rotate');
                    localStorage.setItem('activeMenu', item);
                }
            } else {
                menu.classList.remove('open');
                icon.classList.remove('rotate');
            }
        }

        

    });

}

document.addEventListener('DOMContentLoaded',function(){

    let active=localStorage.getItem('activeMenu');

    if(active){

        document
            .getElementById('menu-'+active)
            ?.classList.add('open');

        document
            .getElementById('icon-'+active)
            ?.classList.add('rotate');

    }

});

</script>

@stack('scripts')
</body>

</html>