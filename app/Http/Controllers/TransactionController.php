<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Transaction;
use App\Models\TransactionDetail;
use App\Models\StockMovement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Customer;
use Illuminate\Support\Facades\Auth;
use App\Helpers\DocumentNumber;

class TransactionController extends Controller
{
    // list transaksi
    public function show($id)
    {
        $transaction =
            Transaction::with('details', 'user', 'pembatalan.user')
            ->findOrFail($id);

        return view(
            'transactions.show',
            compact('transaction')
        );
    }

    // load page
    public function index(Request $request)
    {
       
        // 1. Inisialisasi query transaksi dengan eager load relasi user (kasir)
        $query = Transaction::with(['user', 'customerRelation'])->latest();

        // 🔍 2. Filter Pencarian berdasarkan Nomor Nota
        if ($request->filled('search')) {
            $search = trim($request->search);
            $query->where('no_nota', 'like', "%{$search}%");
        }

        // 🔑 2. Proteksi Multi-Role: Jika yang login adalah Kasir, batasi hanya transaksinya sendiri
        if (strtolower(Auth::user()->role) === 'kasir') {
            $query->where('user_id', Auth::id());
        }

        /// 4. Eksekusi pagination dan simpan query string ke link pagination
        $transactions = $query->paginate(20)->appends($request->all());
        // $transactions = $query->paginate(20);

        return view(
            'transactions.index',
            compact('transactions')
        );  
    }

    // save transaksi
    public function store(Request $request)
    {
        $cart = $request->cart ?? [];   

        if (count($cart) === 0) {
            return response()->json([
                'success' => false,
                'message' => 'Cart kosong'
            ], 422);
        }

        $customer = null;

        if ($request->pelanggan) {

            $customer = Customer::where(
                'kode_pelanggan',
                $request->pelanggan
            )->first();

        }
        
        $subtotal   = (float) $request->subtotal;
        
        $diskon     = (float) $request->diskon;
        
        $grandTotal = max(0, $subtotal - $diskon);

        $cash       = (float) $request->cash;

        $voucher    =  (float) $request->voucher;

        $card       = (float) $request->card;

        $hutang     = (float) $request->hutang;

        $paymentTotal = $cash + $card + $voucher + $hutang ;

        if ($paymentTotal < $grandTotal)
        {
            return response()->json([
                'success' => false,
                'message' =>
                    'Pembayaran kurang'
            ], 422);
        }

        // 💡 1. CARI SHIFT AKTIF UNTUK USER YANG SEDANG LOGIN SEBELUM MULAI TRANSACTION
        $activeShift = \App\Models\Shift::where('user_id', Auth::id())
                                        ->where('status', 'open')
                                        ->first();

        // Opsional: Kalau mau ketat, tolak transaksi jika kasir belum buka shift
        if (!$activeShift) {
            return response()->json([
                'success' => false,
                'message' => 'Anda belum membuka shift kasir! Silakan buka shift terlebih dahulu.'
            ], 403);
        }

        // 🔒 Safe Check: Jika hutang ada nilainya tapi pelanggan kosong
        if ($hutang > 0 && !$request->pelanggan) {
            return response()->json([
                'success' => false,
                'message' => 'Pelanggan wajib diisi jika transaksi menggunakan Hutang/Kasbon!'
            ], 422);
        }

        DB::beginTransaction();

        try {

            // $noNota ='INV-' . now()->format('YmdHis');
            // $noNota = Transaction::generateNoNota();
            $noNota = DocumentNumber::generate('transactions', 'no_nota', 'INV');

             $customer = null;

            if ($request->pelanggan) {

                $customer = Customer::where(
                    'kode_pelanggan',
                    $request->pelanggan
                )->first();

            }

            /*
            |--------------------------------------------------------------------------
            | TOTAL POTONGAN GROSIR
            |--------------------------------------------------------------------------
            */
            $totalDiskonGrosir = 0;
            

            $transaction = Transaction::create([

                'no_nota'      => $noNota,
                'user_id'      => Auth::id(),

                'shift_id'     => $activeShift->id, // 🔥 shift_id aman tersimpan
                
                // 'pelanggan' => $request->pelanggan,
                'pelanggan'     => $customer?->id,

                'telp'          => $customer?->telepon,

                'subtotal'     => $subtotal,

                 // diskon
                'diskon'       => $diskon, // Diskon nota

                'grand_total'  => $grandTotal,

                'voucher'      => $voucher,
                'card'         => $card,
                'hutang'       => $hutang,
                'cash'         => $cash,
                'kembalian'    => $request->kembalian,
            ]);

            foreach ($cart as $item) {

                if (!isset($item['qty']) || $item['qty'] < 1) {
                    throw new \Exception('Qty tidak valid');
                }

                $qty = (int) $item['qty'];

                // 💡 Menggunakan eager load relasi productPrices agar tidak memicu query berulang-ulang
                $product = Product::with('productPrices')->findOrFail($item['id']);

                /*
                |--------------------------------------------------------------------------
                | HARGA GROSIR
                |--------------------------------------------------------------------------
                */

                $hargaNormal = (float) $product->harga;

                $hargaFinal = $hargaNormal;

                $potonganPerPcs = 0;
                
                // 💡 Disesuaikan dengan nama relasi di model Product: productPrices
                if ($product->productPrices && $product->productPrices->count() > 0) {
                    // Diurutkan dari min_qty terbesar (descending) untuk mencocokkan tier grosir teratas
                    $grosirList = $product->productPrices->sortByDesc('min_qty');

                    foreach ($grosirList as $grosir) {
                        if ($item['qty'] >= $grosir->min_qty) {
                            
                            $potonganPerPcs = (float) $grosir->potongan;
                            // yg disimpan ke trans detail adalah harga jual setelah dikurangi potongan
                            $hargaFinal = $hargaNormal - $potonganPerPcs;

                            break;
                        }
                    }
                }

                /*
                |--------------------------------------------------------------------------
                | TOTAL DISKON ITEM
                |--------------------------------------------------------------------------
                |
                | Contoh:
                | Harga     = 10.000
                | Qty       = 5
                | Potongan  = 1.000 / pcs
                |
                | Diskon item = 1.000 × 5 = 5.000
                |
                */
                $diskonItem =
                $potonganPerPcs * $qty;

                $totalDiskonGrosir += $diskonItem;

                /*
                |--------------------------------------------------------------------------
                | SUBTOTAL DETAIL
                |--------------------------------------------------------------------------
                */

                $itemSubtotal = $hargaFinal * $qty;

                TransactionDetail::create([
                    'transaction_id' => $transaction->id,
                    'product_id'     => $product->id,
                    'kode_barang'    => $product->kode_barang,
                    'nama_barang'    => $product->nama_barang,
                    'harga'          => $hargaFinal,
                    'harga_beli'     => $product->harga_beli,
                    'qty'            => $qty,
                    'subtotal'       => $itemSubtotal
                ]);

                /*
                |--------------------------------------------------------------------------
                | UPDATE STOK
                |--------------------------------------------------------------------------
                */

                $stokSebelum = $product->stok;
                $stokSesudah = $stokSebelum - $qty;

                $product->update([
                    'stok' => $stokSesudah
                ]);

                /*
                |--------------------------------------------------------------------------
                | STOCK MOVEMENT
                |--------------------------------------------------------------------------
                */

                StockMovement::create([
                    'product_id'   => $product->id,
                    'type'         => 'SALE',
                    'qty'          => -$item['qty'],
                    'stock_before' => $stokSebelum,
                    'stock_after'  => $stokSesudah,
                    'reference_no' => $noNota
                ]);
            }

            /*
            |--------------------------------------------------------------------------
            | SIMPAN TOTAL DISKON GROSIR KE MASTER TRANSAKSI
            |--------------------------------------------------------------------------
            */
            // tadinya diskon berisi potongan grosir,skrg diskon itu diinput manual
            // diskon item lgsg dihitung ke harga jual jadi subtotal sudah terkena pot grosir
            // $transaction->update([
            //     'diskon' => $totalDiskonGrosir
            // ]);

            DB::commit();

            return response()->json([
                'success' => true,
                'transaction_id' => $transaction->id,
                'no_nota' => $noNota
            ]);

        } catch (\Exception $e) {

            DB::rollBack();

            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    // cetak struk
    public function print($id)
    {
        // $transaction =
        //     Transaction::with('details')
        //     ->findOrFail($id);

        $transaction = Transaction::with(['details', 'user'])->findOrFail($id);

        $customer = null;

        if ($transaction->pelanggan) {

            $customer = Customer::where(
                // 'kode_pelanggan',
                'id',
                $transaction->pelanggan
            )->first();

        }

        // Ambil data pengaturan toko global
        $shopSetting = \App\Models\Setting::first() ?? new \App\Models\Setting([
            'nama_toko' => 'TOKO ANDA',
            'alamat' => 'Jl. Contoh No.123',
            'telepon' => '08123456789',
            'footer_nota' => 'Terima Kasih\nBarang yang sudah dibeli\ntidak dapat ditukar'
        ]);
        
        return view(
            'transactions.print',
            compact(
                'transaction',
                'customer',
                'shopSetting'
            )
        );
    }

    
}