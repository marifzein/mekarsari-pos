<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\StockMovement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Helpers\DocumentNumber;

class ReturBarangController extends Controller
{
    public function index(Request $request)
    {
        $query = DB::table('retur_barang')
            ->join('suppliers', 'retur_barang.supplier_id', '=', 'suppliers.id')
            ->join('users', 'retur_barang.user_id', '=', 'users.id')
            ->select('retur_barang.*', 'suppliers.nama as supplier_name', 'users.name as kasir_name')
            ->latest('retur_barang.created_at');

        if ($request->filled('search')) {
            $query->where('no_retur', 'like', '%' . $request->search . '%')
                  ->orWhere('suppliers.nama', 'like', '%' . $request->search . '%');
        }

        $retur = $query->paginate(10);
        return view('retur.index', compact('retur'));
    }

    public function create()
    {
        $suppliers = DB::table('suppliers')
            ->where('is_active', 1)
            ->get();
        return view('retur.create', compact('suppliers'));
    }

    public function show($id)
    {
        $retur = DB::table('retur_barang')
            ->join('suppliers', 'retur_barang.supplier_id', '=', 'suppliers.id')
            ->join('users', 'retur_barang.user_id', '=', 'users.id')
            ->select('retur_barang.*', 'suppliers.nama as supplier_name', 'users.name as kasir_name')
            ->where('retur_barang.id', $id)
            ->first();

        if (!$retur) {
            return redirect()->route('retur.index')->with('error', 'Data retur tidak ditemukan.');
        }

        $items = DB::table('retur_barang_items')
            ->join('products', 'retur_barang_items.product_id', '=', 'products.id')
            ->select('retur_barang_items.*', 'products.nama_barang', 'products.kode_barang')
            ->where('retur_barang_items.retur_barang_id', $id)
            ->get();

        return view('retur.show', compact('retur', 'items'));
    }

    public function searchProducts(Request $request)
    {
        $search = $request->get('q');
        $supplierId = $request->get('supplier_id'); 

        $products = Product::where('is_active', 1)
            ->where('supplier_id', $supplierId) // 2. Filter produk wajib dari supplier ini
            ->where(function($query) use ($search) {
                $query->where('kode_barang', $search)
                      ->orWhere('nama_barang', 'like', '%' . $search . '%');
            })
            ->take(8)
            ->get(['id', 'kode_barang', 'nama_barang', 'harga_beli', 'stok']);

        return response()->json($products);
    }

    public function store(Request $request)
    {
        $request->validate([
            'supplier_id' => 'required|exists:suppliers,id',
            'tanggal_retur' => 'required|date',
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.qty_retur' => 'required|integer|min:1',
            'items.*.harga_beli' => 'required|numeric|min:0',
        ]);

        DB::beginTransaction();
        try {
            $noRetur = DocumentNumber::generate('retur_barang', 'no_retur', 'RT');
            $currentUserId = Auth::id() ?? 1;

            // 1. Simpan Induk Retur
            $returId = DB::table('retur_barang')->insertGetId([
                'no_retur' => $noRetur,
                'supplier_id' => $request->supplier_id,
                'tanggal_retur' => $request->tanggal_retur,
                'catatan' => $request->catatan,
                'total_item' => count($request->items),
                'user_id' => $currentUserId,
                'created_at' => now(),
                'updated_at' => now()
            ]);

            foreach ($request->items as $item) {
                $subtotal = $item['qty_retur'] * $item['harga_beli'];

                // 2. Simpan Detail Item
                DB::table('retur_barang_items')->insert([
                    'retur_barang_id' => $returId,
                    'product_id' => $item['product_id'],
                    'qty_retur' => $item['qty_retur'],
                    'harga_beli' => $item['harga_beli'],
                    'subtotal' => $subtotal,
                    'created_at' => now(),
                    'updated_at' => now()
                ]);

                // 3. Potong Stok Produk (Lock for Safety)
                $product = Product::lockForUpdate()->find($item['product_id']);
                $stokSebelum = $product->stok;
                $stokSesudah = $stokSebelum - $item['qty_retur'];

                $product->update([
                    'stok' => $stokSesudah
                ]);

                // 4. Catat Riwayat Mutasi ke Stock Movement (QTY MINUS)
                StockMovement::create([
                    'product_id' => $product->id,
                    'type' => 'RETUR',
                    'qty' => -$item['qty_retur'], // Qty disimpan negatif untuk retur keluar
                    'stock_before' => $stokSebelum,
                    'stock_after' => $stokSesudah,
                    'reference_no' => $noRetur,
                    'notes' => 'Retur Barang ke Supplier. ' . ($request->catatan ?? ''),
                ]);
            }

            DB::commit();
            return redirect()->route('retur.index')->with('success', 'Transaksi retur barang berhasil dibukukan!');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Gagal memproses retur: ' . $e->getMessage())->withInput();
        }
    }
    // print============================
    
    public function print($id)
    {
        $retur = DB::table('retur_barang')
            ->join('suppliers', 'retur_barang.supplier_id', '=', 'suppliers.id')
            ->join('users', 'retur_barang.user_id', '=', 'users.id')
            ->select('retur_barang.*', 'suppliers.nama as supplier_name', 'users.name as kasir_name')
            ->where('retur_barang.id', $id)
            ->first();

        if (!$retur) {
            return redirect()->route('retur.index')->with('error', 'Data tidak ditemukan.');
        }

        $items = DB::table('retur_barang_items')
            ->join('products', 'retur_barang_items.product_id', '=', 'products.id')
            ->select('retur_barang_items.*', 'products.nama_barang', 'products.kode_barang')
            ->where('retur_barang_items.retur_barang_id', $id)
            ->get();

        // Ganti dari Barryvdh PDF ke standard Laravel View biasa
        return view('retur.print', compact('retur', 'items'));
    }
}