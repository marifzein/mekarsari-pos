    @extends('layouts.app')
    @section('title', 'Buat Retur Barang')
    @section('content')

    <x-page-header title="Buat Retur Barang" subtitle="Keluarkan barang dari gudang utama dan kurangi nilai stok komputer">
        <x-slot:action>
            <a href="{{ route('retur.index') }}">
                <x-button color="gray" type="button"><i class="ri-arrow-left-line"></i> Kembali</x-button>
            </a>
        </x-slot:action>
    </x-page-header>

    @if ($errors->any())
        <div class="mb-4 p-4 bg-red-100 border border-red-400 text-red-700 rounded-xl">
            <ul class="list-disc pl-5 text-sm">
                @foreach ($errors->all() as $error) <li>{{ $error }}</li> @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('retur.store') }}" id="main-form" onsubmit="return validateForm(event)">
        @csrf

        <x-card class="mb-6">
            <div class="grid md:grid-cols-2 gap-6">
                <div class="space-y-4">
                    <x-select label="Tujuan Supplier" name="supplier_id" id="supplier_id" required icon="ri-store-3-line">
                        <option value="">-- Pilih Rekanan Supplier --</option>
                        @foreach($suppliers as $sup)
                            <option value="{{ $sup->id }}">{{ $sup->nama }}</option>
                        @endforeach
                    </x-select>
                </div>
                <div class="space-y-4">
                    <x-input label="Tanggal Retur" name="tanggal_retur" type="date" :value="date('Y-m-d')" required />
                </div>
                <div class="md:col-span-2">
                    <x-textarea label="Alasan / Catatan Retur" name="catatan" rows="2" placeholder="Tuliskan keterangan (Contoh: Barang pecah di pojokan, Expired Date dekat, salah kirim rasa, dll)..." />
                </div>
            </div>
        </x-card>

        <x-card>
            <div class="mb-6">
                <div class="font-semibold mb-2 flex items-center justify-between">
                    <label for="search-product" class="text-sm font-semibold text-slate-700">Cari / Scan Produk Diretur</label>
                    <span class="text-xs px-2 py-0.5 bg-slate-900 text-purple-400 font-mono rounded-md"><i class="ri-scan-2-line"></i> Scanner Ready</span>
                </div>
                <x-input id="search-product" name="search" placeholder="Ketik nama produk atau tembak barcodenya langsung disini..." icon="ri-search-line" autocomplete="off" />
                <div id="product-result" class="mt-2 rounded-xl border bg-white max-h-60 overflow-y-auto hidden"></div>
            </div>

            <div class="mb-4">
                <h3 class="font-semibold text-slate-800 mb-1">Daftar Item Pengembalian</h3>
                <p class="text-sm text-slate-500">Isi kuantitas barang riil yang dikembalikan fisik serta harga kesepakatan retur</p>
            </div>

            <x-table>
                <x-table-header>
                    <tr>
                        <x-table-head class="text-left">Produk</x-table-head>
                        <x-table-head class="text-center w-28">Stok Berjalan</x-table-head>
                        <x-table-head class="text-center w-32">Qty Retur <span class="text-red-500">*</span></x-table-head>
                        <x-table-head class="text-right w-40">Harga Beli Retur (Rp)</x-table-head>
                        <x-table-head class="text-right w-36">Subtotal</x-table-head>
                        <x-table-head class="text-center w-16">Hapus</x-table-head>
                    </tr>
                </x-table-header>
                <tbody id="retur-items">
                    <tr>
                        <td colspan="6">
                            <x-empty-state icon="ri-arrow-go-back-line" title="Belum ada daftar item" description="Gunakan kotak pencari di atas untuk memasukkan barang yang ingin diretur." />
                        </td>
                    </tr>
                </tbody>
            </x-table>

            <div class="mt-6 pt-4 border-t border-slate-100 flex justify-end items-center">
                <div class="text-right">
                    <span class="text-slate-800 text-base font-bold mr-2">ESTIMASI TOTAL RETUR:</span>
                    <div class="text-4xl font-black text-purple-600 tracking-tight" id="grand-total-label">Rp0</div>
                </div>
            </div>
        </x-card>

        <div class="flex justify-end gap-3 mt-6">
            <a href="{{ route('retur.index') }}">
                <x-button color="secondary" type="button"><i class="ri-close-circle-line text-red-500"></i> Batal</x-button>
            </a>
            <x-button color="primary" type="submit"><i class="ri-save-3-line"></i> Bukukan Retur</x-button>
        </div>
    </form>

    @push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
    const search = document.getElementById('search-product');
    const result = document.getElementById('product-result');
    const tableItems = document.getElementById('retur-items');
    const grandTotalLabel = document.getElementById('grand-total-label');
    let cart = []; let timer; let selectedIndex = -1;

    document.addEventListener('DOMContentLoaded', () => search.focus());

    function clearSearch() { result.innerHTML = ''; result.classList.add('hidden'); search.value = ''; selectedIndex = -1; search.focus(); }

    function addToCart(id, name, code, price, stok) {
        const existing = cart.find(x => x.id == id);
        if (existing) { existing.qty_retur++; } 
        else { cart.push({ id: id, code: code, name: name, qty_retur: 1, harga_beli: price, stok: stok }); }
        renderTable(); clearSearch();
    }

    search.addEventListener('keyup', function(e) {
        if (['ArrowUp', 'ArrowDown', 'Enter'].includes(e.key)) return;
        clearTimeout(timer);

        // 1. Cek apakah supplier sudah dipilih atau belum
        const supplierId = document.getElementById('supplier_id').value;
        if (!supplierId) {
            this.value = ''; // Bersihkan tulisan/scan barcode yang masuk
            Swal.fire({
                title: 'Peringatan!',
                text: 'Harap pilih supplier terlebih dahulu sebelum mencari atau menscan barang.',
                icon: 'warning',
                confirmButtonColor: '#9333ea'
            }).then(() => {
                document.getElementById('supplier_id').focus(); // Otomatis arahkan fokus ke select supplier
            });
            return;
        }

        const q = this.value.trim();
        if (q.length < 2) { result.innerHTML = ''; result.classList.add('hidden'); return; }

        timer = setTimeout(() => {
            fetch(`/api/retur/search-products?q=${encodeURIComponent(q)}&supplier_id=${supplierId}`)
            .then(r => r.json())
            .then(data => {
                if (data.length === 0) {
                    result.innerHTML = `<div class="p-4 text-center text-slate-400 text-sm">Produk tidak terdaftar</div>`;
                    result.classList.remove('hidden'); return;
                }
                if (data.length === 1 && data[0].kode_barang.toLowerCase() === q.toLowerCase()) {
                    addToCart(data[0].id, data[0].nama_barang, data[0].kode_barang, data[0].harga_beli, data[0].stok); return;
                }
                let html = '';
                data.forEach((item, index) => {
                    html += `
                    <div class="border-b p-3 hover:bg-purple-50 cursor-pointer product-row" id="product-row-${index}" data-id="${item.id}" data-name="${item.nama_barang}" data-code="${item.kode_barang}" data-price="${item.harga_beli}" data-stok="${item.stok}">
                        <div class="font-semibold text-slate-800">${item.nama_barang}</div>
                        <div class="text-xs text-slate-500">${item.kode_barang} | Stok Gudang: ${item.stok} | Harga Beli: Rp${new Intl.NumberFormat('id-ID').format(item.harga_beli)}</div>
                    </div>`;
                });
                result.innerHTML = html; result.classList.remove('hidden'); selectedIndex = -1;

                document.querySelectorAll('.product-row').forEach(row => {
                    row.onclick = function() { addToCart(this.dataset.id, this.dataset.name, this.dataset.code, parseFloat(this.dataset.price), parseInt(this.dataset.stok)); }
                });
            });
        }, 250);
    });

    search.addEventListener('keydown', function(e) {
        const rows = document.querySelectorAll('.product-row'); if (rows.length === 0) return;
        if (e.key === 'ArrowDown') { e.preventDefault(); selectedIndex++; if (selectedIndex >= rows.length) selectedIndex = 0; updateRowHighlight(rows); } 
        else if (e.key === 'ArrowUp') { e.preventDefault(); selectedIndex--; if (selectedIndex < 0) selectedIndex = rows.length - 1; updateRowHighlight(rows); } 
        else if (e.key === 'Enter') { e.preventDefault(); if (selectedIndex >= 0 && selectedIndex < rows.length) { const r = rows[selectedIndex]; addToCart(r.dataset.id, r.dataset.name, r.dataset.code, parseFloat(r.dataset.price), parseInt(r.dataset.stok)); } }
    });

    function updateRowHighlight(rows) {
        rows.forEach((row, i) => { if (i === selectedIndex) { row.classList.add('bg-purple-100'); row.scrollIntoView({ block: 'nearest' }); } else { row.classList.remove('bg-purple-100'); } });
    }

    function renderTable() {
        if (cart.length === 0) {
            tableItems.innerHTML = `<tr><td colspan="6"><div class="text-center py-10 text-slate-400 text-sm">Belum ada item retur. Cari/scan produk di atas.</div></td></tr>`;
            grandTotalLabel.innerText = 'Rp0'; return;
        }
        let html = ''; let grandTotal = 0;
        cart.forEach((item, index) => {
            const subtotal = item.qty_retur * item.harga_beli; grandTotal += subtotal;
            html += `
            <tr class="hover:bg-slate-50 border-b">
                <td class="p-3 text-sm">
                    <div class="font-semibold text-slate-800">${item.name}</div>
                    <div class="text-xs text-slate-400">${item.code}</div>
                    <input type="hidden" name="items[${index}][product_id]" value="${item.id}">
                </td>
                <td class="p-3 text-center font-bold text-slate-500">${item.stok}</td>
                <td class="p-3 text-center"><input type="number" name="items[${index}][qty_retur]" min="1" value="${item.qty_retur}" data-index="${index}" class="qty-input border rounded-lg w-24 px-2 py-1 text-center font-bold text-purple-600 focus:border-purple-500"></td>
                <td class="p-3 text-center"><input type="number" name="items[${index}][harga_beli]" min="0" value="${item.harga_beli}" data-index="${index}" class="price-input border rounded-lg w-36 px-3 py-1 text-right font-semibold text-slate-700 focus:border-purple-500"></td>
                <td class="p-3 text-sm text-right text-slate-800 font-bold">Rp${new Intl.NumberFormat('id-ID').format(subtotal)}</td>
                <td class="p-3 text-center"><button type="button" class="delete-btn text-red-500 hover:text-red-700 transition" data-index="${index}"><i class="ri-delete-bin-line text-lg"></i></button></td>
            </tr>`;
        });
        tableItems.innerHTML = html; grandTotalLabel.innerText = 'Rp' + new Intl.NumberFormat('id-ID').format(grandTotal);
    }

    document.addEventListener('input', function(e) {
        if (e.target.classList.contains('qty-input')) { const idx = e.target.dataset.index; const val = parseInt(e.target.value); cart[idx].qty_retur = val >= 1 ? val : 1; renderTable(); }
        if (e.target.classList.contains('price-input')) { const idx = e.target.dataset.index; const val = parseFloat(e.target.value); cart[idx].harga_beli = val >= 0 ? val : 0; renderTable(); }
    });

    document.addEventListener('click', function(e) {
        const btn = e.target.closest('.delete-btn'); if (!btn) return;
        cart.splice(btn.dataset.index, 1); renderTable(); search.focus();
    });

    document.addEventListener('click', (e) => { if (!search.contains(e.target) && !result.contains(e.target)) result.classList.add('hidden'); });

    function validateForm(e) {
        e.preventDefault();
        if (cart.length === 0) { Swal.fire('Peringatan', 'Daftar barang retur masih kosong!', 'warning'); return false; }
        if (document.getElementById('supplier_id').value === "") { Swal.fire('Peringatan', 'Harap tentukan supplier tujuan!', 'warning'); return false; }
        
        // Cek proteksi jika kuantitas retur melebihi stok yang ada
        for (let item of cart) {
            if (item.qty_retur > item.stok) {
                Swal.fire('Stok Kurang', `Jumlah retur untuk produk [${item.name}] melebihi stok komputer!`, 'error');
                return false;
            }
        }

        Swal.fire({
            title: 'Eksekusi Retur Barang?',
            text: 'Stok barang di sistem akan langsung terpotong permanen dan tidak dapat diubah.',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#9333ea',
            cancelButtonColor: '#64748b',
            confirmButtonText: 'YA, Proses Retur!',
            cancelButtonText: 'Batal'
        }).then((res) => { if (res.isConfirmed) document.getElementById('main-form').submit(); });
    }
    </script>
    @endpush
    @endsection