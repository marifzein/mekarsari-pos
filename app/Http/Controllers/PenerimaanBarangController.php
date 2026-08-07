<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\PurchaseOrder;
use App\Models\StockMovement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Helpers\DocumentNumber;

class PenerimaanBarangController extends Controller
{
    public function index(Request $request)
    {
        // 1. Ambil data induk riwayat penerimaan barang untuk tabel utama
        $query = DB::table('penerimaan_barang')
            ->join('suppliers', 'penerimaan_barang.supplier_id', '=', 'suppliers.id')
            ->join('users', 'penerimaan_barang.user_id', '=', 'users.id')
            ->select('penerimaan_barang.*', 'suppliers.nama as supplier_name', 'users.name as kasir_name')
            ->latest('penerimaan_barang.created_at');

        if ($request->filled('search')) {
            $query->where('no_penerimaan', 'like', '%' . $request->search . '%')
                  ->orWhere('no_dokumen_supplier', 'like', '%' . $request->search . '%')
                  ->orWhere('no_po', 'like', '%' . $request->search . '%');
        }
        $penerimaan = $query->paginate(10);

        // 2. Ambil data PO rujukan hanya yang berstatus 'ORDERED'
        $purchaseOrders = PurchaseOrder::with('supplier')
            ->where('status', 'ORDERED')
            ->latest()
            ->get();

        return view('penerimaan.index', compact('penerimaan', 'purchaseOrders'));
    }

    public function create(Request $request)
    {
        $suppliers = DB::table('suppliers')->get();
        $selectedPo = null;

        // Jalur Rujukan PO: Verifikasi status PO harus 'ORDERED'
        if ($request->filled('po_number')) {
            $selectedPo = PurchaseOrder::with(['purchaseOrderItems.product', 'supplier'])
                ->where('po_number', $request->po_number)
                ->where('status', 'ORDERED')
                ->first();

            // Jika PO tidak ditemukan atau statusnya bukan ORDERED, buang ke index
            if (!$selectedPo) {
                return redirect()->route('penerimaan.index')->with('error', 'Referensi PO tidak valid atau sudah pernah diterima sebelumnya.');
            }
        }

        return view('penerimaan.create', compact('suppliers', 'selectedPo'));
    }

    /**
     * Menampilkan rincian mutasi barang masuk yang sudah disimpan (Read-Only)
     */
    public function show($id)
    {
        // 1. Ambil data induk penerimaan barang
        $penerimaan = DB::table('penerimaan_barang')
            ->join('suppliers', 'penerimaan_barang.supplier_id', '=', 'suppliers.id')
            ->join('users', 'penerimaan_barang.user_id', '=', 'users.id')
            ->select('penerimaan_barang.*', 'suppliers.nama as supplier_name', 'users.name as kasir_name')
            ->where('penerimaan_barang.id', $id)
            ->first();

        if (!$penerimaan) {
            return redirect()->route('penerimaan.index')->with('error', 'Data penerimaan tidak ditemukan.');
        }

        // 2. Ambil data rincian item produk yang masuk
        $items = DB::table('penerimaan_barang_items')
            ->join('products', 'penerimaan_barang_items.product_id', '=', 'products.id')
            ->select('penerimaan_barang_items.*', 'products.nama_barang', 'products.kode_barang')
            ->where('penerimaan_barang_items.penerimaan_barang_id', $id)
            ->get();

        return view('penerimaan.show', compact('penerimaan', 'items'));
    }

    /**
     * API pencarian produk cepat untuk diintegrasikan dengan JS Lookup Sisi Kiri
     */
    public function searchProducts(Request $request)
    {
        $search = $request->get('q');
        
        $products = Product::where('is_active', 1)
            ->where(function($query) use ($search) {
                $query->where('kode_barang', $search) // prioritas pencarian barcode presisi
                      ->orWhere('nama_barang', 'like', '%' . $search . '%');
            })
            ->take(8)
            ->get(['id', 'kode_barang', 'nama_barang', 'harga_beli', 'stok']);

        return response()->json($products);
    }
    
    // print dengan Native Browser Print
    public function print($id)
    {
        // 1. Ambil data induk penerimaan barang
        $penerimaan = DB::table('penerimaan_barang')
            ->join('suppliers', 'penerimaan_barang.supplier_id', '=', 'suppliers.id')
            ->join('users', 'penerimaan_barang.user_id', '=', 'users.id')
            ->select('penerimaan_barang.*', 'suppliers.nama as supplier_name', 'users.name as kasir_name')
            ->where('penerimaan_barang.id', $id)
            ->first();

        if (!$penerimaan) {
            return redirect()->route('penerimaan.index')->with('error', 'Data penerimaan tidak ditemukan.');
        }

        // 2. Ambil data rincian item produk yang masuk
        $items = DB::table('penerimaan_barang_items')
            ->join('products', 'penerimaan_barang_items.product_id', '=', 'products.id')
            ->select('penerimaan_barang_items.*', 'products.nama_barang', 'products.kode_barang')
            ->where('penerimaan_barang_items.penerimaan_barang_id', $id)
            ->get();

        // 3. Langsung lempar ke view biasa (Tanpa Barryvdh PDF)
        return view('penerimaan.print', compact('penerimaan', 'items'));
    }

    // save data
    public function store(Request $request)
    {
        $request->validate([
            'supplier_id' => 'required|exists:suppliers,id',
            'tanggal_terima' => 'required|date',
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.qty_terima' => 'required|integer|min:1', 
            'items.*.harga_beli' => 'required|numeric|min:0',
        ]);

        // Proteksi Tambahan: Jika melampirkan No PO, pastikan PO tersebut memang masih berstatus ORDERED
        if ($request->filled('no_po')) {
            $poCheck = PurchaseOrder::where('po_number', $request->no_po)->first();
            if (!$poCheck || $poCheck->status !== 'ORDERED') {
                return redirect()->back()->with('error', 'Status PO rujukan sudah berubah atau tidak valid.')->withInput();
            }
        }

        DB::beginTransaction();
        try {
            // $noPenerimaan = 'GR-' . date('Ymd') . '-' . sprintf('%04d', (DB::table('penerimaan_barang')->count() + 1));
            $noPenerimaan = DocumentNumber::generate(
                'penerimaan_barang', // nama tabel database kamu
                'no_penerimaan',     // kolom target kode unik
                'GR'                 // Prefix untuk Goods Receipt / Penerimaan Barang
            );
            $currentUserId = Auth::id() ?? 1;

            // 1. Simpan Induk Penerimaan Barang
            $penerimaanId = DB::table('penerimaan_barang')->insertGetId([
                'no_penerimaan' => $noPenerimaan,
                'no_po' => $request->no_po,
                'no_dokumen_supplier' => $request->no_dokumen_supplier,
                'supplier_id' => $request->supplier_id,
                'tanggal_terima' => $request->tanggal_terima,
                'catatan' => $request->catatan,
                'total_item' => count($request->items),
                'user_id' => $currentUserId,
                'created_at' => now(),
                'updated_at' => now()
            ]);

            foreach ($request->items as $item) {
                $subtotal = $item['qty_terima'] * $item['harga_beli'];

                // 2. Simpan Detail Item Penerimaan
                DB::table('penerimaan_barang_items')->insert([
                    'penerimaan_barang_id' => $penerimaanId,
                    'product_id' => $item['product_id'],
                    'qty_po' => $item['qty_po'] ?? 0,
                    'qty_terima' => $item['qty_terima'],
                    'harga_beli' => $item['harga_beli'],
                    'subtotal' => $subtotal,
                    'created_at' => now(),
                    'updated_at' => now()
                ]);

                // 3. Update Stok Produk & Kalkulasi HPP Rata-Rata Bergerak (Moving Average)
                $product = Product::lockForUpdate()->find($item['product_id']);
                $stokSebelum = $product->stok;
                $stokSesudah = $stokSebelum + $item['qty_terima'];

                $hppLama = $product->harga_beli ?? $item['harga_beli'];
                $hppBaru = $stokSebelum > 0 
                    ? (($stokSebelum * $hppLama) + ($item['qty_terima'] * $item['harga_beli'])) / $stokSesudah 
                    : $item['harga_beli'];

                $product->update([
                    'stok' => $stokSesudah,
                    'harga_beli' => round($hppBaru, 0)
                ]);

                // 4. Catat Riwayat Mutasi ke Stock Movement
                StockMovement::create([
                    'product_id' => $product->id,
                    'type' => 'PENERIMAAN',
                    'qty' => $item['qty_terima'],
                    'stock_before' => $stokSebelum,
                    'stock_after' => $stokSesudah,
                    'reference_no' => $noPenerimaan,
                    'notes' => 'Penerimaan Pembelian. Ref: ' . ($request->no_dokumen_supplier ?? '-') . ($request->no_po ? ' (PO: '.$request->no_po.')' : ''),
                ]);
            }

            // 5. Update Status PO menjadi RECEIVED (Selesai Diterima)
            if ($request->filled('no_po')) {
                PurchaseOrder::where('po_number', $request->no_po)->update(['status' => 'RECEIVED']);
            }

            DB::commit();
            return redirect()->route('penerimaan.index')->with('success', 'Penerimaan barang sukses dicatat oleh sistem!');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Gagal memproses: ' . $e->getMessage())->withInput();
        }
    }
}