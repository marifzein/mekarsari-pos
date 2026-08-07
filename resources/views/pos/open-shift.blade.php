@extends(
    preg_match('/(android|bb\d+|meego).+mobile|avantgo|bada\/|blackberry|blazer|compal|elaine|fennec|hiptop|iemobile|ip(hone|od)|iris|kindle|lge |maemo|midp|mmp|mobile.+firefox|netfront|opera m(ob|in)i|palm( os)?|phone|p(ixi|re)\/|plucker|pocket|psp|series(4|6)0|symbian|treo|up\.(browser|link)|vodafone|wap|windows ce|xda|xiino/i', request()->header('User-Agent')) 
    || preg_match('/(ipad|tablet|(android(?!.*mobile)))/i', request()->header('User-Agent')) 
    ? 'layouts.mobile-app' 
    : 'layouts.app'
)

@section('title', 'Buka Shift Kasir')

@section('content')
{{-- <div class="min-h-[70vh] flex items-center justify-center"> --}}
<div class="min-h-[70vh] md:min-h-0 flex items-center justify-center p-0 md:p-6">
    {{-- <div class="max-w-md w-full bg-white rounded-2xl shadow-xl border border-slate-100 p-8"> --}}
    <div class="w-full max-w-md bg-white rounded-2xl md:shadow-xl border border-slate-100 p-6 md:p-8">
        
        <!-- Header -->
        <div class="text-center mb-6">
            <div class="w-16 h-16 bg-indigo-50 text-indigo-600 rounded-2xl flex items-center justify-center mx-auto mb-4 text-2xl font-bold shadow-sm">
                💰
            </div>
            <h2 class="text-2xl font-bold text-slate-800">Sesi Shift Baru</h2>
            <p class="text-sm text-slate-500 mt-1">Silakan masukkan nominal uang modal awal di laci kasir untuk mengaktifkan aplikasi POS.</p>
        </div>

        <!-- Form -->
        <form action="{{ route('pos.store-shift') }}" method="POST" id="formOpenShift">
            @csrf
            
            <div class="mb-5">
                <label for="starting_cash" class="block text-sm font-semibold text-slate-700 mb-2">
                    Uang Modal Awal (Cash)
                </label>
                <div class="relative rounded-xl shadow-sm">
                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                        <span class="text-slate-400 font-medium text-sm">Rp</span>
                    </div>
                    <input 
                        type="number" 
                        name="starting_cash" 
                        id="starting_cash" 
                        value="{{ old('starting_cash', 0) }}"
                        class="block w-full pl-11 pr-4 py-3 rounded-xl border border-slate-300 bg-slate-50 text-slate-800 font-semibold focus:bg-white focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 outline-none transition text-base"
                        placeholder="0"
                        required
                        autofocus
                        onfocus="this.select()"
                    >
                </div>
                
                @error('starting_cash')
                    <p class="text-red-500 text-xs mt-1 font-medium">{{ $message }}</p>
                @enderror
            </div>

            <!-- Petunjuk Info -->
            <div class="mb-6 p-3.5 bg-amber-50 rounded-xl border border-amber-200/60 flex gap-3 text-amber-800">
                <span class="text-lg">💡</span>
                <p class="text-xs leading-relaxed">
                    <strong>Penting:</strong> Masukkan total uang kertas & koin yang disediakan toko untuk uang kembalian sebelum transaksi dimulai.
                </p>
            </div>

            <!-- Tombol Submit -->
            <button 
                type="submit" 
                class="w-full bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-3 px-4 rounded-xl shadow-lg shadow-indigo-200 transition duration-200 flex items-center justify-center gap-2 text-sm"
            >
                🚀 Buka Shift & Mulai POS
            </button>
        </form>
    </div>
</div>

<script>
// Validasi kilat via JS biar kasir tidak input minus secara tidak sengaja
document.getElementById('formOpenShift').addEventListener('submit', function(e) {
    const cashInput = document.getElementById('starting_cash');
    const cashValue = Number(cashInput.value || 0);

    if (cashValue < 0) {
        e.preventDefault();
        Swal.fire({
            icon: 'error',
            title: 'Gagal Buka Shift',
            text: 'Uang modal awal tidak boleh kurang dari Rp 0, bos!',
            confirmButtonColor: '#4f46e5'
        });
    }
});
</script>
@endsection