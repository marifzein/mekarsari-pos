<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class LaporanPenjualanProdukController extends Controller
{
    public function index(Request $request)
    {
        $startDate = $request->get('start_date', Carbon::now()->startOfMonth()->toDateString());
        $endDate = $request->get('end_date', Carbon::now()->toDateString());
        $sortBy = $request->get('sort_by', 'laba_kotor');
        $sortDir = $request->get('sort_dir', 'desc');

        // Ambil input filter baru
        $searchItem = $request->get('search_item');
        $categoryId = $request->get('category_id');
        $supplierId = $request->get('supplier_id');

        $allowedSorts = ['kode_barang', 'nama_barang', 'harga', 'total_terjual', 'total_pendapatan', 'total_hpp', 'laba_kotor'];
        if (!in_array($sortBy, $allowedSorts)) $sortBy = 'laba_kotor';
        if (!in_array($sortDir, ['asc', 'desc'])) $sortDir = 'desc';

        // MASTER DATA (Disesuaikan dengan nama kolom asli database kamu)
        $categories = DB::table('categories')->select('id', 'name')->get(); 
        $suppliers = DB::table('suppliers')->select('id', 'nama')->get();   

        // Query Base (Join ke tabel products)
        $query = DB::table('transaction_details')
            ->join('transactions', 'transaction_details.transaction_id', '=', 'transactions.id')
            ->join('products', 'transaction_details.product_id', '=', 'products.id')
            ->select(
                'transaction_details.kode_barang',
                'transaction_details.nama_barang',
                'transaction_details.harga',
                DB::raw('SUM(transaction_details.qty) as total_terjual'),
                DB::raw('SUM(transaction_details.subtotal) as total_pendapatan'),
                DB::raw('SUM(transaction_details.qty * transaction_details.harga_beli) as total_hpp'),
                DB::raw('SUM(transaction_details.subtotal) - SUM(transaction_details.qty * transaction_details.harga_beli) as laba_kotor')
            )
            ->whereRaw('transactions.status != ?', ['Batal']) // 👈 FILTER TIDAK BATAL
            ->whereBetween(DB::raw('DATE(transactions.created_at)'), [$startDate, $endDate]);

        // Logika Pemicu Filter Kondisional
        if (!empty($searchItem)) {
            $query->where(function($q) use ($searchItem) {
                $q->where('transaction_details.nama_barang', 'like', "%{$searchItem}%")
                  ->orWhere('transaction_details.kode_barang', 'like', "%{$searchItem}%");
            });
        }
        if (!empty($categoryId)) {
            $query->where('products.category_id', $categoryId);
        }
        if (!empty($supplierId)) {
            $query->where('products.supplier_id', $supplierId);
        }

        // Group By
        $query->groupBy(
            'transaction_details.product_id', 
            'transaction_details.kode_barang', 
            'transaction_details.nama_barang',
            'transaction_details.harga'
        );

        // Hitung Grand Total Keseluruhan (Footer) menggunakan kondisi filter yang sama
        $totalsQuery = DB::table('transaction_details')
            ->join('transactions', 'transaction_details.transaction_id', '=', 'transactions.id')
            ->join('products', 'transaction_details.product_id', '=', 'products.id')
            ->whereRaw('transactions.status != ?', ['Batal']) // 👈 FILTER TIDAK BATAL
            ->whereBetween(DB::raw('DATE(transactions.created_at)'), [$startDate, $endDate]);

        if (!empty($searchItem)) {
            $totalsQuery->where(function($q) use ($searchItem) {
                $q->where('transaction_details.nama_barang', 'like', "%{$searchItem}%")
                  ->orWhere('transaction_details.kode_barang', 'like', "%{$searchItem}%");
            });
        }
        if (!empty($categoryId)) {
            $totalsQuery->where('products.category_id', $categoryId);
        }
        if (!empty($supplierId)) {
            $totalsQuery->where('products.supplier_id', $supplierId);
        }

        $totals = $totalsQuery->select(
            DB::raw('SUM(transaction_details.qty) as grand_qty'),
            DB::raw('SUM(transaction_details.subtotal) as grand_revenue'),
            DB::raw('SUM(transaction_details.qty * transaction_details.harga_beli) as grand_hpp'),
            DB::raw('SUM(transaction_details.subtotal) - SUM(transaction_details.qty * transaction_details.harga_beli) as grand_laba_kotor')
        )->first();

        // Handle Export
        $exportType = $request->get('export');
        if ($exportType === 'excel') {
            $reportData = $query->orderBy($sortBy, $sortDir)->get();
            $filename = "Laporan_Penjualan_Produk_{$startDate}_to_{$endDate}.xls";
            return response()->view('laporan.penjualan-produk.excel', compact('reportData', 'startDate', 'endDate', 'totals'))
                ->header('Content-Type', 'application/vnd.ms-excel')
                ->header('Content-Disposition', "attachment; filename={$filename}");
        }

        if ($exportType === 'pdf') {
            $reportData = $query->orderBy($sortBy, $sortDir)->get();
            return view('laporan.penjualan-produk.pdf', compact('reportData', 'startDate', 'endDate', 'totals'));
        }

        // Tampilan Standar Web dengan Pagination
        $reportData = $query->orderBy($sortBy, $sortDir)->paginate(15)->withQueryString();

        return view('laporan.penjualan-produk.index', compact(
            'reportData', 'startDate', 'endDate', 'sortBy', 'sortDir', 'totals',
            'categories', 'suppliers', 'searchItem', 'categoryId', 'supplierId'
        ));
    }
}