@extends(
    preg_match('/(android|bb\d+|meego).+mobile|avantgo|bada\/|blackberry|blazer|compal|elaine|fennec|hiptop|iemobile|ip(hone|od)|iris|kindle|lge |maemo|midp|mmp|mobile.+firefox|netfront|opera m(ob|in)i|palm( os)?|phone|p(ixi|re)\/|plucker|pocket|psp|series(4|6)0|symbian|treo|up\.(browser|link)|vodafone|wap|windows ce|xda|xiino/i', request()->header('User-Agent')) 
    || preg_match('/(ipad|tablet|(android(?!.*mobile)))/i', request()->header('User-Agent')) 
    ? 'layouts.mobile-app' 
    : 'layouts.app'
)

@section('title', 'Tutup Shift Kasir')

<!-- Setting opsional header layout mobile -->
@section('page_title', 'TUTUP SHIFT')
@section('page_subtitle', 'Finalisasi laporan harian')


@section('content')
{{-- <div class="max-w-4xl mx-auto py-8 px-4"> --}}
<!-- Hilangkan padding p-8 di HP, gunakan p-0 md:p-8 -->
<div class="max-w-4xl mx-auto p-0 md:p-8 md:px-4 pb-24">

    <!-- Header -->
    {{-- <div class="mb-8 border-b border-slate-200 pb-5"> --}}
    <!-- Header: hidden md:block biar cuma muncul di PC -->
    <div class="mb-8 border-b border-slate-200 pb-5 hidden md:block">
        <h1 class="text-3xl font-bold text-slate-800">Finalisasi & Tutup Shift</h1>
        <p class="text-sm text-slate-500 mt-1">Lakukan hitung ulang uang fisik di laci kasir secara teliti sebelum menutup sesi kerja.</p>
    </div>

    <!-- Grid: Di HP otomatis stacking ke bawah (block), di PC grid-cols-3 -->

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        
        <!-- KIRI: RINGKASAN DATA SISTEM (2 Kolom) -->
        {{-- <div class="md:col-span-2 space-y-6"> --}}
        <!-- BAGIAN 1 & 2: DATA SISTEM (Stacking di HP) -->
        <div class="md:col-span-2 space-y-4 md:space-y-6">
            
            <!-- Info General -->
            {{-- <div class="bg-white rounded-xl shadow-sm border border-slate-100 p-6"> --}}
                <div class="bg-white rounded-xl shadow-xs border border-slate-100 md:border-slate-100 p-5 md:p-6">
                {{-- <h3 class="text-base font-bold text-slate-700 mb-4 flex items-center gap-2"> --}}
                <h3 class="text-sm md:text-base font-bold text-slate-700 mb-4 flex items-center gap-2">
                    📋 Informasi Sesi Shift
                </h3>
                {{-- <div class="grid grid-cols-2 gap-4 text-sm"> --}}
                <div class="grid grid-cols-2 gap-x-3 gap-y-3 text-xs md:text-sm">
                    <div>
                        <span class="text-slate-400 block">Operator Kasir</span>
                        <strong class="text-slate-700 text-base">{{ auth()->user()->name }}</strong>
                    </div>
                    <div>
                        <span class="text-slate-400 block">Waktu Mulai (Opened At)</span>
                        <strong class="text-slate-700">{{ $activeShift->opened_at->format('d M Y - H:i') }}</strong>
                    </div>
                </div>
            </div>

            <!-- Rincian Kas / Keuangan -->
            {{-- <div class="bg-white rounded-xl shadow-sm border border-slate-100 p-6"> --}}
            <div class="bg-white rounded-xl shadow-xs border border-slate-100 p-5 md:p-6">
                <h3 class="text-base font-bold text-slate-700 mb-4 flex items-center gap-2">
                    📊 Kalkulasi Saldo Laci (Sistem)
                </h3>
                
                {{-- <div class="space-y-3.5"> --}}
                <div class="space-y-3 text-xs md:text-sm">
                    <div class="flex justify-between items-center pb-2.5 border-b border-slate-100 text-sm">
                        <span class="text-slate-500">1. Uang Modal Awal (Starting Cash)</span>
                        <span class="font-semibold text-slate-800">Rp {{ number_format($activeShift->starting_cash, 0, ',', '.') }}</span>
                    </div>
                    <div class="flex justify-between items-center pb-2.5 border-b border-slate-100 text-sm">
                        <span class="text-slate-500">2. (+) Penjualan Tunai Bersih (Cash Sales)</span>
                        <span class="font-semibold text-emerald-600">+ Rp {{ number_format($netCashSales, 0, ',', '.') }}</span>
                    </div>
                    <div class="flex justify-between items-center pb-2.5 border-b border-slate-100 text-sm">
                        <span class="text-slate-500">3. (-) Biaya Operasional Laci (Expense)</span>
                        <span class="font-semibold text-rose-600">- Rp {{ number_format($activeShift->operational_expense, 0, ',', '.') }}</span>
                    </div>
                    
                    <!-- Grand Total Ekspektasi -->
                    <div class="flex justify-between items-center pt-3 bg-indigo-50/50 p-4 rounded-xl border border-indigo-100/70 mt-4">
                        <div>
                            <span class="text-xs font-bold text-indigo-800 block uppercase tracking-wider">Total Ekspektasi Saldo</span>
                            <span class="text-xs text-indigo-500">(Uang yang Seharusnya di Laci)</span>
                        </div>
                        <span class="text-xl font-extrabold text-indigo-700" id="expectedCashRaw" data-value="{{ $expectedCash }}">
                            Rp {{ number_format($expectedCash, 0, ',', '.') }}
                        </span>
                    </div>
                </div>
            </div>
        </div>

        <!-- KANAN: INPUT UANG FISIK AKTUAL (1 Kolom) -->
        {{-- <div class="bg-white rounded-xl shadow-md border border-slate-200/80 p-6 h-fit sticky top-6"> --}}
        <div class="bg-white md:rounded-xl rounded-t-3xl shadow-xl md:shadow-md border border-slate-200 md:border-slate-200/80 p-6 h-fit md:sticky md:top-6 fixed md:relative bottom-0 left-0 right-0 z-10 md:z-0">
            <h3 class="text-base font-bold text-slate-800 mb-4 flex items-center gap-2">
                💰 Verifikasi Uang Fisik
            </h3>

            <form action="{{ route('pos.store-close') }}" method="POST" id="formCloseShift">
                @csrf
                
                <!-- Input Uang Aktual -->
                <div class="mb-5">
                    <label for="ending_cash_actual" class="block text-xs font-bold uppercase tracking-wider text-slate-600 mb-2">
                        Total Uang Fisik Nyata
                    </label>
                    <div class="relative rounded-xl shadow-sm">
                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                            <span class="text-slate-400 font-semibold text-sm">Rp</span>
                        </div>
                        <input 
                            type="number" 
                            name="ending_cash_actual" 
                            id="ending_cash_actual" 
                            {{-- class="block w-full pl-10 pr-4 py-3 border border-slate-300 rounded-xl bg-slate-50 text-slate-800 font-bold focus:bg-white focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 outline-none transition text-lg" --}}
                            class="block w-full pl-12 pr-4 py-4 md:py-3 border-2 border-indigo-200 md:border-slate-300 rounded-xl bg-indigo-50/30 md:bg-slate-50 text-slate-800 font-black focus:bg-white focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 outline-none transition text-xl md:text-lg text-center md:text-left"
                            placeholder="0"
                            required
                            autofocus
                        >
                    </div>
                </div>

                <!-- Live Perhitungan Selisih (JS-Driven) -->
                {{-- <div id="varianceBox" class="mb-5 p-3.5 rounded-xl hidden text-sm border"> --}}
                <div id="varianceBox" class="mb-4 md:mb-5 p-3.5 rounded-xl hidden text-xs md:text-sm border">
                    <div class="flex justify-between items-center">
                        <span class="font-medium">Selisih (Variance):</span>
                        <span id="varianceText" class="font-bold text-base">Rp 0</span>
                    </div>
                </div>

                <!-- Input Alasan Selisih (Muncul dinamis jika ada selisih) -->
                {{-- <div class="mb-5 hidden" id="reasonGroup"> --}}
                <div class="mb-4 md:mb-5 hidden" id="reasonGroup">
                    <label for="variance_reason" class="block text-xs font-bold uppercase tracking-wider text-amber-800 mb-2">
                        ⚠️ Alasan Selisih Kas
                    </label>
                    <textarea 
                        name="variance_reason" 
                        id="variance_reason" 
                        rows="3" 
                        class="block w-full px-3 py-2 border border-amber-300 rounded-xl bg-amber-50/30 text-slate-700 text-sm focus:bg-white focus:border-amber-500 focus:ring-1 focus:ring-amber-500 outline-none transition placeholder:text-slate-400"
                        placeholder="Wajib diisi! Jelaskan kenapa uang laci bisa kurang/lebih..."
                    ></textarea>
                </div>

                <!-- Tombol Submit Akhir -->
                <button 
                    type="submit" 
                    class="w-full bg-slate-800 hover:bg-slate-900 text-white font-bold py-3.5 px-4 rounded-xl shadow-lg transition duration-200 text-sm uppercase tracking-wider"
                >
                    🔒 Kunci & Tutup Shift
                </button>
            </form>
        </div>
    </div>
</div>

<!-- JAVASCRIPT LIVE CALCULATION & VALIDASI -->
<script>
document.addEventListener('DOMContentLoaded', function () {
    const inputActual = document.getElementById('ending_cash_actual');
    const expectedCash = parseFloat(document.getElementById('expectedCashRaw').getAttribute('data-value'));
    const varianceBox = document.getElementById('varianceBox');
    const varianceText = document.getElementById('varianceText');
    const reasonGroup = document.getElementById('reasonGroup');
    const reasonInput = document.getElementById('variance_reason');
    const form = document.getElementById('formCloseShift');

    // Format mata uang rupiah helper
    function formatRupiah(angka) {
        const isMinus = angka < 0 ? '-' : '';
        const absoluteVal = Math.abs(angka);
        return isMinus + 'Rp ' + absoluteVal.toLocaleString('id-ID');
    }

    // Kalkulasi Live Real-time saat kasir mengetik nominal uang
    inputActual.addEventListener('input', function () {
        const actualValue = parseFloat(this.value) || 0;
        const variance = actualValue - expectedCash;

        if (this.value !== '') {
            varianceBox.classList.remove('hidden');
            varianceText.textContent = formatRupiah(variance);

            if (variance === 0) {
                // Pas / Klop
                varianceBox.className = "mb-5 p-3.5 rounded-xl text-sm border bg-emerald-50 border-emerald-200 text-emerald-800";
                reasonGroup.classList.add('hidden');
                reasonInput.removeAttribute('required');
            } else if (variance < 0) {
                // Minus / Nomok
                varianceBox.className = "mb-5 p-3.5 rounded-xl text-sm border bg-rose-50 border-rose-200 text-rose-800";
                reasonGroup.classList.remove('hidden');
                reasonInput.setAttribute('required', 'required');
            } else {
                // Plus / Kelebihan
                varianceBox.className = "mb-5 p-3.5 rounded-xl text-sm border bg-amber-50 border-amber-200 text-amber-800";
                reasonGroup.classList.remove('hidden');
                reasonInput.setAttribute('required', 'required');
            }
        } else {
            varianceBox.classList.add('hidden');
            reasonGroup.classList.add('hidden');
            reasonInput.removeAttribute('required');
        }
    });

    // Validasi saat form diklik kirim
    form.addEventListener('submit', function (e) {
        const actualValue = parseFloat(inputActual.value) || 0;
        const variance = actualValue - expectedCash;

        if (variance !== 0 && !reasonInput.value.trim()) {
            e.preventDefault();
            Swal.fire({
                icon: 'warning',
                title: 'Alasan Wajib Diisi',
                text: 'Ada selisih kas sebesar ' + formatRupiah(variance) + '. Lu harus tulis alasannya dulu bos!',
                confirmButtonColor: '#f59e0b'
            });
        }
    });
});
</script>
@endsection