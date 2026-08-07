{{-- <header
    class="h-20 bg-white border-b border-slate-50 shadow-sm flex items-center justify-end px-8 sticky top-0 z-20"
>

    <div
        class="flex items-center gap-5"
    >

        <button
            class="text-slate-500 hover:text-indigo-600"
        >

            <i class="ri-notification-3-line text-xl"></i>

        </button>

        <div
            class="w-px h-8 bg-slate-200"
        ></div>

        <div
            class="flex items-center gap-3"
        >

            <div
                class="w-10 h-10 rounded-full
                bg-indigo-100
                flex items-center justify-center"
            >

                <i class="ri-user-3-line text-indigo-600"></i>

            </div>

            <div>

                <div
                    class="font-semibold text-slate-800"
                >

                    {{ Auth::user()->name }}

                </div>

                <div
                    class="text-xs text-slate-500"
                >

                    {{ Auth::user()->role }}

                </div>

            </div>

        </div>

    </div>

</header> --}}
<header class="h-20 bg-white border-b border-slate-50 shadow-sm flex items-center justify-between px-8 sticky top-0 z-20">
    <!-- Tombol Hamburger (Sisi Kiri Header) -->
    <div>
        <button @click="sidebarOpen = !sidebarOpen" class="text-slate-500 hover:text-indigo-600 focus:outline-none p-2 rounded-xl hover:bg-slate-100 transition">
            <i class="ri-menu-line text-2xl"></i>
        </button>
    </div>

    <!-- Area Profil & Notifikasi (Sisi Kanan Header Bawaan Kamu) -->
    <div class="flex items-center gap-5">

        <button class="text-slate-500 hover:text-indigo-600">
            <i class="ri-notification-3-line text-xl"></i>
        </button>

        <div class="w-px h-8 bg-slate-200"></div>

        <!-- REFACTOR: Membungkus area Profil dengan Alpine.js untuk fitur Dropdown -->
        <div class="relative" x-data="{ open: false }" @click.away="open = false">
            
            <!-- Tombol Pemicu Dropdown -->
            <button @click="open = !open" class="flex items-center gap-3 focus:outline-none group text-left">
                <div class="w-10 h-10 rounded-full bg-indigo-100 flex items-center justify-center transition group-hover:bg-indigo-200">
                    <i class="ri-user-3-line text-indigo-600"></i>
                </div>

                <div>
                    <div class="font-semibold text-slate-800 flex items-center gap-1 group-hover:text-indigo-600 transition">
                        {{ Auth::user()->name }}
                        <i class="ri-arrow-down-s-line text-sm transition-transform duration-200" :class="open ? 'rotate-180' : ''"></i>
                    </div>
                    <div class="text-xs text-slate-500">
                        {{ Auth::user()->role }}
                    </div>
                </div>
            </button>

            <!-- Menu Dropdown -->
            <div 
                x-show="open"
                x-transition:enter="transition ease-out duration-100"
                x-transition:enter-start="transform opacity-0 scale-95"
                x-transition:enter-end="transform opacity-100 scale-100"
                x-transition:leave="transition ease-in duration-75"
                x-transition:leave-start="transform opacity-100 scale-100"
                x-transition:leave-end="transform opacity-0 scale-95"
                class="absolute right-0 mt-3 w-56 bg-white rounded-xl shadow-xl border border-slate-100 py-2 z-50"
                style="display: none;"
            >
                <div class="px-4 py-2 border-b border-slate-100 mb-1">
                    <p class="text-xs text-slate-400 font-medium">Menu Akun</p>
                </div>

                <!-- Link Ganti Password -->
                <a href="{{ route('password.change') }}" class="flex items-center gap-3 px-4 py-2.5 text-sm text-slate-700 hover:bg-slate-50 hover:text-indigo-600 transition">
                    <i class="ri-lock-password-line text-lg text-slate-400"></i>
                    <span>Ganti Password</span>
                </a>

                <div class="border-t border-slate-100 my-1"></div>

                <!-- Opsi Logout Tambahan (Sangat berguna jika kasir ingin cepat ganti shift) -->
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="w-full flex items-center gap-3 px-4 py-2.5 text-sm text-red-600 hover:bg-red-50 transition">
                        <i class="ri-logout-box-r-line text-lg text-red-500"></i>
                        <span>Logout Aplikasi</span>
                    </button>
                </form>

            </div>
        </div>

    </div>

</header>