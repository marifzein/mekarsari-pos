@extends('layouts.app')

@section('title','Developer')

@section('content')

<div class="max-w-4xl mx-auto">

    <x-card>

        <h2
            class="text-2xl font-bold mb-8"
        >

            Developer Tools

        </h2>

        {{-- <div
            class="grid grid-cols-1 md:grid-cols-3 gap-5"
        > --}}
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5">

            {{-- Cek apakah aplikasi berjalan di environment lokal (localhost / 127.0.0.1) --}}
            @if(in_array(request()->getHttpHost(), ['localhost', '127.0.0.1', 'localhost:8000']))

                <form
                    id="form-reset-transaksi"
                    action="{{ route('developer.reset.transaksi') }}"
                    method="POST"
                >

                    @csrf

                    <button
                        type="button"
                        onclick="konfirmasiMultiTahap('form-reset-transaksi', '⚠️ HAPUS SEMUA TRANSAKSI?', 'Semua data penjualan, operasional, dan stock movement akan hilang permanen!')"
                        class="w-full rounded-xl bg-red-500 text-white py-5 hover:bg-red-600 font-medium transition-colors shadow-sm"
                    >

                        Reset Transaksi

                    </button>

                </form>

                {{-- Reset Master --}}

                <form
                    id="form-reset-master"
                    action="{{ route('developer.reset.master') }}"
                    method="POST"
                >

                    @csrf

                    <button
                        type="button"
                        onclick="konfirmasiMultiTahap('form-reset-master', '⚠️ HAPUS SELURUH MASTER DATA?', 'Data produk, supplier, kategori, dan pelanggan akan dikosongkan secara total!')"
                        class="w-full rounded-xl bg-orange-500 text-white py-5 hover:bg-orange-600 font-medium transition-colors shadow-sm"
                    >

                        Reset Master

                    </button>

                </form>
            @endif

            {{-- Reset Footer --}}
            <form action="{{ route('developer.reset.footer') }}" method="POST" id="form-reset-footer">
                @csrf
                <button
                    type="button"
                    onclick="konfirmasiMultiTahap('form-reset-footer', 'Reset Footer?', 'Update data footer terbaru')"
                    class="w-full rounded-xl bg-slate-700 text-white py-5 hover:bg-slate-800 font-medium transition-colors shadow-sm"
                >
                    Reset Footer
                </button>
            </form> 

            {{-- Seed --}}

            <form
                action="{{ route('developer.seed') }}"
                method="POST"
            >

                @csrf
                {{-- seeding demo/dummy produk , supplier, customer , sinkron/insert stock_movements--}}
                <button
                    type="button"
                    onclick="Swal.fire({
                        title: 'Generate Demo Data?',
                        text: 'Sistem akan memasukkan data dummy produk, supplier, dan pelanggan untuk uji coba.',
                        icon: 'question',
                        showCancelButton: true,
                        confirmButtonColor: '#4f46e5',
                        cancelButtonColor: '#64748b',
                        confirmButtonText: 'Ya, Generate!'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            document.getElementById('form-seed-demo').submit();
                        }
                    })"
                    class="w-full rounded-xl bg-indigo-600 text-white py-5 hover:bg-indigo-700 font-medium transition-colors shadow-sm"
                > 

                    Seed Demo Data

                </button>

            </form>

        </div>

    </x-card>

</div>

{{-- 💡 LOGIKAL DOUBLE-STEP CONFIRMATION DENGAN SWEETALERT2 --}}
<script>
    function konfirmasiMultiTahap(formId, judulPeringatan, teksPeringatan) {
        // TAHAP 1: Peringatan Bahaya Destruktif
        Swal.fire({
            title: judulPeringatan,
            text: teksPeringatan,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#64748b',
            confirmButtonText: 'Ya, Lanjutkan!',
            cancelButtonText: 'Batal',
            focusCancel: true
        }).then((result) => {
            if (result.isConfirmed) {
                // TAHAP 2: Tantangan Memasukkan PIN Developer
                Swal.fire({
                    title: 'Verifikasi Otoritas',
                    html: 'Masukkan PIN Developer untuk mengeksekusi perintah:<br><strong class="text-red-600 font-bold block mt-2">' + judulPeringatan + '</strong>',
                    // text: 'Masukkan PIN Developer untuk mengeksekusi perintah: ' + judulPeringatan ,
                    input: 'password',
                    inputPlaceholder: 'Ketik PIN di sini...',
                    inputAttributes: {
                        autocapitalize: 'off',
                        autocorrect: 'off'
                    },
                    showCancelButton: true,
                    confirmButtonColor: '#10b981',
                    cancelButtonColor: '#64748b',
                    confirmButtonText: 'Eksekusi Perintah',
                    cancelButtonText: 'Batal',
                    inputValidator: (value) => {
                        if (!value) {
                            return 'PIN tidak boleh kosong, Bos!';
                        }
                    }
                }).then((output) => {
                    if (output.isConfirmed) {
                        // Cek Validasi PIN Hardcode
                        if (output.value === 'bismillah') {
                            // Tampilkan efek loading sejenak sebelum submit
                            Swal.fire({
                                title: 'Memproses...',
                                text: 'Sedang menghapus data dari server.',
                                allowOutsideClick: false,
                                didOpen: () => {
                                    Swal.showLoading();
                                }
                            });
                            document.getElementById(formId).submit();
                        } else {
                            // Jika salah, gagalkan aksi
                            Swal.fire({
                                icon: 'error',
                                title: 'Akses Ditolak',
                                text: '❌ PIN Developer Salah! Perintah digagalkan.',
                                confirmButtonColor: '#64748b'
                            });
                        }
                    }
                });
            }
        });
    }
</script>

@endsection