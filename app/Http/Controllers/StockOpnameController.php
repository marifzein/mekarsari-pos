<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use App\Models\StockMovement;
use App\Models\StockOpname;
use App\Models\StockOpnameDetail;
use Illuminate\Support\Facades\DB;
use App\Helpers\DocumentNumber;

class StockOpnameController extends Controller
{
    
    public function index()
    {
        $openOpname = StockOpname::where(
            'status',
            'OPEN'
        )->first();

        if($openOpname)
        {
            return redirect(
                '/stock-opname/'.$openOpname->id
            );
        }

        $opnames = StockOpname::withCount(
            'details'
        )
        ->latest()
        ->paginate(10);
        // ->get();

        return view(
            'stock-opname.index',
            compact('opnames')
        );
    }

    private function generateOpnameNo()
    {
        return DocumentNumber::generate('stock_opnames', 'opname_no', 'SO');
    }

    public function start()
    {
        $open = StockOpname::where(
            'status',
            'OPEN'
        )->first();

        if($open)
        {
            return redirect(
                '/stock-opname/'.$open->id
            );
        }

        $opname = StockOpname::create([

            'opname_no'   => $this->generateOpnameNo(),

            'opname_date' => now(),

            'user_name'   => 'Admin',

            'status'      => 'OPEN',

            'notes'       => null

        ]);

        return redirect(
            '/stock-opname/'.$opname->id
        );
    }

    public function show(
        StockOpname $stockOpname
    )
    {
        $details = $stockOpname
            ->details()
            ->with('product')
            ->get();

        return view(
            'stock-opname.show',
            compact(
                'stockOpname',
                'details'
            )
        );
    }

    public function store(Request $request, StockOpname $stockOpname)
    {
        $request->validate([
            'product_id' => 'required',
            'stok_fisik' => 'required|integer|min:0'
        ]);

        try {
            $detail = DB::transaction(function () use ($request, $stockOpname) {
                $product = Product::findOrFail($request->product_id);
                $stokSystem = $product->stok;
                
                // --- DISINI PERBAIKANNYA (Spasi sudah dihapus) ---
                $stokFisikBaru = (int) $request->stok_fisik;

                // 1. Cek apakah barang sudah pernah discan di dokumen SO ini
                $existingDetail = StockOpnameDetail::where('stock_opname_id', $stockOpname->id)
                    ->where('product_id', $product->id)
                    ->first();

                if ($existingDetail) {
                    // 1. Ambil nilai STOK SISTEM YANG ASLI dari record pertama kali disimpan
                    $stokSystemAsli = $existingDetail->stock_system; 

                    // 2. Akumulasikan fisik lama dengan fisik baru yang baru discan
                    $stokFisikTotal = $existingDetail->stock_physical + $stokFisikBaru;
                    
                    // 3. Hitung selisih mengacu pada STOK SISTEM ASLI (Fisik - Sistem)
                    $selisihBaru   = $stokFisikTotal - $stokSystemAsli;

                    // Update detail opname
                    $existingDetail->update([
                        'stock_physical' => $stokFisikTotal,
                        'difference'     => $selisihBaru,
                        'notes'          => $request->notes ?? $existingDetail->notes
                    ]);

                    // Update stock di master produk ke kondisi fisik paling mutakhir
                    $product->update(['stok' => $stokFisikTotal]);

                    // Update kartu stok (Stock Movement)
                    DB::table('stock_movements')
                        ->where('product_id', $product->id)
                        ->where('type', 'STOCK_OPNAME')
                        ->where('reference_no', $stockOpname->opname_no)
                        ->update([
                            'qty'          => $selisihBaru,
                            'stock_before' => $stokSystemAsli, // Tetap gunakan sistem asli sebelum SO
                            'stock_after'  => $stokFisikTotal,
                            'notes'        => $request->notes ?? 'Stock Opname diupdate',
                            'updated_at'   => now()
                        ]);

                    return [
                        'success'   => true,
                        'is_update' => true,
                        'message'   => 'Stok produk berhasil diakumulasikan!',
                        'detail'    => [
                            'kode_barang'    => $product->kode_barang,
                            'nama_barang'    => $product->nama_barang,
                            'stock_system'   => $stokSystemAsli,
                            'stock_physical' => $stokFisikTotal,
                            'difference'     => $selisihBaru
                        ]
                    ];
                }

                // 2. JIKA BELUM ADA: Jalankan baris kode original kamu (Insert Baru)
                $selisih = $stokFisikBaru - $stokSystem;

                $detail = StockOpnameDetail::create([
                    'stock_opname_id' => $stockOpname->id,
                    'product_id'      => $product->id,
                    'stock_system'    => $stokSystem,
                    'stock_physical'  => $stokFisikBaru,
                    'difference'      => $selisih,
                    'notes'           => $request->notes
                ]);

                $product->update(['stok' => $stokFisikBaru]);

                DB::table('stock_movements')->insert([
                    'product_id'   => $product->id,
                    'type'         => 'STOCK_OPNAME',
                    'qty'          => $selisih,
                    'stock_before' => $stokSystem,
                    'stock_after'  => $stokFisikBaru,
                    'reference_no' => $stockOpname->opname_no,
                    'notes'        => $request->notes,
                    'created_at'   => now(),
                    'updated_at'   => now()
                ]);

                return [
                    'success'   => true,
                    'is_update' => false,
                    'message'   => 'Item berhasil disimpan.',
                    'detail'    => [
                        'kode_barang'    => $product->kode_barang,
                        'nama_barang'    => $product->nama_barang,
                        'stock_system'   => $stokSystem,
                        'stock_physical' => $stokFisikBaru,
                        'difference'     => $selisih
                    ]
                ];
            });

            return response()->json($detail);

        } catch (\Throwable $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    // finish
    public function finish(
    StockOpname $stockOpname
    )
    {
        // dd(
        //     $stockOpname->id,
        //     $stockOpname->status,
        //     $stockOpname->toArray()
        // );
        if($stockOpname->status!='OPEN')
        {
            return redirect('/stock-opname')

            ->with(

                'warning',

                'Stock Opname sudah diposting.'

            );
        }

        $stockOpname->update([

            'status'=>'POSTED',

            'finished_at'=>now()

        ]);

        return redirect('/stock-opname')

        ->with(

            'success',

            'Stock Opname berhasil diposting.'

        );
    }

    public function print(
        StockOpname $stockOpname
    )
    {
        $details = $stockOpname
            ->details()
            ->with('product')
            ->get();

        return view(
            'stock-opname.print',
            compact(
                'stockOpname',
                'details'
            )
        );
    }
}