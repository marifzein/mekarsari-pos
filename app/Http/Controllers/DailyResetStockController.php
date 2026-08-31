<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\StockMovement;
use App\Models\StockOpname;
use App\Models\StockOpnameDetail;
use App\Helpers\DocumentNumber; // Menggunakan helper 
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class DailyResetStockController extends Controller
{
    /**
     * Tampilkan daftar produk aktif yang stoknya > 0
     */
    public function index(Request $request)
    {
        // $products = Product::where('is_active', 1)
        //                     ->get();
        //                     // ->where('stok', '>', 0)

        // return view('stock-opname.daily-reset', compact('products'));
        // Default filter adalah 'semua' (semua barang selain stok 0)

        
        $stokFilter = $request->get('stok_filter', 'semua');

        $query = Product::where('is_active', 1);

        if ($stokFilter === 'minus') {
            // Stok minus (< 0)
            $query->where('stok', '<', 0);
        } elseif ($stokFilter === 'plus') {
            // Stok plus (> 0)
            $query->where('stok', '>', 0);
        } else {
            // Semua barang (Stok tidak sama dengan 0)
            $query->where('stok', '!=', 0);
        }

        $products = $query->get();

        return view('stock-opname.daily-reset', compact('products', 'stokFilter'));
    }

    /**
     * Proses reset stok terpilih langsung POSTED
     */
    public function store(Request $request)
    {
        $request->validate([
            'notes' => 'required|string|max:255',
            'product_ids' => 'required|array|min:1',
        ], [
            'notes.required' => 'Alasan / Catatan reset wajib diisi.',
            'product_ids.required' => 'Pilih minimal satu produk untuk di-reset.',
        ]);

        $productIds = $request->product_ids;
        $notes = $request->notes;
        $userName = Auth::user()->name ?? 'Admin';

        DB::beginTransaction();
        try {
            // 1. Generate nomor SO menggunakan Helper bawaan sistem kamu
            $opnameNo = DocumentNumber::generate('stock_opnames', 'opname_no', 'SO');

            // 2. Buat header Stock Opname dengan status langsung POSTED
            $stockOpname = StockOpname::create([
                'opname_no'   => $opnameNo,
                'opname_date' => now(),
                'user_name'   => $userName,
                'status'      => 'POSTED', // Langsung posted tanpa lewat status OPEN
                'notes'       => '[Daily Reset] ' . $notes,
                'finished_at' => now()
            ]);

            // 3. Loop item yang dicentang untuk dikosongkan stoknya
            foreach ($productIds as $productId) {
                // Gunakan lockForUpdate untuk menghindari race condition saat jam closing resto
                $product = Product::lockForUpdate()->find($productId);
                
                if (!$product) continue;

                $stokSystem = $product->stok;
                $stokFisikBaru = 0; // Target reset harian resto
                $selisih = $stokFisikBaru - $stokSystem; // Pasti bernilai minus (misal: 0 - 79 = -79)

                // Jika stok di sistem ternyata sudah 0 atau minus, abaikan
                if ($stokSystem <= 0) continue;

                // a. Insert ke detail opname
                StockOpnameDetail::create([
                    'stock_opname_id' => $stockOpname->id,
                    'product_id'      => $product->id,
                    'stock_system'    => $stokSystem,
                    'stock_physical'  => $stokFisikBaru,
                    'difference'      => $selisih,
                    'notes'           => 'Reset Stok Harian Resto'
                ]);

                // b. Update master produk ke 0
                $product->update(['stok' => $stokFisikBaru]);

                // c. Insert ke kartu stok (Stock Movement) agar sinkron dengan histori laporan keuangan
                StockMovement::create([
                    'product_id'   => $product->id,
                    'type'         => 'STOCK_OPNAME',
                    'qty'          => $selisih,
                    'stock_before' => $stokSystem,
                    'stock_after'  => $stokFisikBaru,
                    'reference_no' => $stockOpname->opname_no,
                    'notes'        => '[Daily Reset] ' . $notes
                ]);
            }

            DB::commit();
            return redirect()->route('daily-reset.index')->with('success', 'Stok berhasil di-reset menjadi 0 dan telah di-posting.');

        } catch (\Throwable $e) {
            DB::rollBack();
            return redirect()->back()->withInput()->with('error', 'Gagal memproses reset: ' . $e->getMessage());
        }
    }
}