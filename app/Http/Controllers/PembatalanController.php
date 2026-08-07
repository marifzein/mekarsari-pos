<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Models\PembatalanPenjualan;
use App\Models\Product;
use App\Models\StockMovement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class PembatalanController extends Controller
{
    // 1. Index: Daftar Penjualan dengan Filter Tanggal & Tombol Aksi
    public function index(Request $request)
    {
        $query = Transaction::with('user')->latest();

        // Filter Tanggal jika diisi
        if ($request->filled('start_date') && $request->filled('end_date')) {
            $query->whereBetween('created_at', [
                $request->start_date . ' 00:00:00', 
                $request->end_date . ' 23:59:59'
            ]);
        }

        $transactions = $query->paginate(10)->withQueryString();

        return view('pembatalan.index', compact('transactions'));
    }

    // 2. Create: Halaman Detail Penjualan sebelum dibatalkan
    public function create($id)
    {
        // Load relasi user (kasir) dan details
        $transaction = Transaction::with(['user', 'details'])->findOrFail($id);

        // CEGATAN: Jika sudah batal, lempar balik ke index
        if ($transaction->status === 'Batal') {
            return redirect()->route('pembatalan.index')
                ->with('error', 'Transaksi ' . $transaction->no_nota . ' sudah pernah dibatalkan sebelumnya!');
        }

        return view('pembatalan.create', compact('transaction'));
    }

    // 3. Store: Eksekusi Pembatalan via SweetAlert Password
    public function store(Request $request, $id)
    {
        $request->validate([
            'password' => 'required|string',
            'alasan'   => 'required|string|max:255',
        ]);

        // Verifikasi Password Kasir/User
        if (!Hash::check($request->password, Auth::user()->password)) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Password yang Anda masukkan salah!'
            ], 422);
        }

        try {
            DB::transaction(function () use ($request, $id) {
                $transaction = Transaction::with('details')->findOrFail($id);

                if ($transaction->status === 'Batal') {
                    throw new \Exception('Transaksi ini sudah dibatalkan sebelumnya.');
                }

                // A. Loop detail transaksi -> Restore stok & Catat stock_movement
                foreach ($transaction->details as $item) {
                    $product = Product::find($item->product_id);

                    if ($product) {
                        $stockBefore = $product->stok;
                        $stockAfter  = $stockBefore + $item->qty; // Revert Stok

                        // Update Stok Fisik
                        $product->update(['stok' => $stockAfter]);

                        // Catat Stock Movement
                        StockMovement::create([
                            'product_id'   => $product->id,
                            'type'         => 'VOID_SALE',
                            'qty'          => $item->qty,
                            'stock_before' => $stockBefore,
                            'stock_after'  => $stockAfter,
                            'reference_no' => $transaction->no_nota,
                            'notes'        => 'Pembatalan Penjualan: ' . $request->alasan, // <-- Prefix diperbarui
                        ]);
                    }
                }

                // B. Update Status Transaksi
                $transaction->update([
                    'status' => 'Batal',
                    'catatan' => 'Dibatalkan oleh ' . Auth::user()->name . '. Alasan: ' . $request->alasan
                ]);

                // C. Insert ke pembatalan_penjualans
                PembatalanPenjualan::create([
                    'transaction_id' => $transaction->id,
                    'user_id'        => Auth::id(),
                    'alasan'         => $request->alasan,
                ]);
            });

            return response()->json([
                'status'  => 'success',
                'message' => 'Transaksi berhasil dibatalkan dan stok telah disesuaikan!'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Gagal memproses pembatalan: ' . $e->getMessage()
            ], 500);
        }
    }

    // 4. Laporan / Daftar Histori Pembatalan (Bonus)
    public function report()
    {
        $pembatalans = PembatalanPenjualan::with(['transaction', 'user'])->latest()->paginate(10);
        return view('pembatalan.report', compact('pembatalans'));
    }
}