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
        $sortBy = $request->get('sort_by', 'laba_kotor'); // Default sort kita ganti ke laba kotor paling cuan
        $sortDir = $request->get('sort_dir', 'desc');

        // Validasi kolom sorting (Ditambahkan HPP dan Laba Kotor)
        $allowedSorts = ['kode_barang', 'nama_barang', 'harga', 'total_terjual', 'total_pendapatan', 'total_hpp', 'laba_kotor'];
        if (!in_array($sortBy, $allowedSorts)) $sortBy = 'laba_kotor';
        if (!in_array($sortDir, ['asc', 'desc'])) $sortDir = 'desc';

        // Query Base
        $query = DB::table('transaction_details')   
            ->join('transactions', 'transaction_details.transaction_id', '=', 'transactions.id')
            ->select(
                'transaction_details.kode_barang',
                'transaction_details.nama_barang',
                'transaction_details.harga', // Tetap diselect
                DB::raw('SUM(transaction_details.qty) as total_terjual'),
                DB::raw('SUM(transaction_details.subtotal) as total_pendapatan'),
                DB::raw('SUM(transaction_details.qty * transaction_details.harga_beli) as total_hpp'),
                DB::raw('SUM(transaction_details.subtotal) - SUM(transaction_details.qty * transaction_details.harga_beli) as laba_kotor')
            )
            ->whereBetween(DB::raw('DATE(transactions.created_at)'), [$startDate, $endDate])
            ->groupBy(
                'transaction_details.product_id', 
                'transaction_details.kode_barang', 
                'transaction_details.nama_barang',
                'transaction_details.harga' // 💡 FIX 1: Kolom ini wajib masuk Group By agar tidak memicu error 1055
            );

        // Hitung Grand Total Keseluruhan (Footer) sebelum pagination merusak datanya
        $totals = DB::table('transaction_details')
            ->join('transactions', 'transaction_details.transaction_id', '=', 'transactions.id')
            ->select(
                DB::raw('SUM(transaction_details.qty) as grand_qty'), // 💡 FIX 2: Menggunakan rumus asli, bukan alias
                DB::raw('SUM(transaction_details.subtotal) as grand_revenue'),
                DB::raw('SUM(transaction_details.qty * transaction_details.harga_beli) as grand_hpp'),
                DB::raw('SUM(transaction_details.subtotal) - SUM(transaction_details.qty * transaction_details.harga_beli) as grand_laba_kotor')
            )
            ->whereBetween(DB::raw('DATE(transactions.created_at)'), [$startDate, $endDate])
            ->first();

        // JIKA ACTION EXPORT EXCEL ATAU PRINT PDF
        $exportType = $request->get('export');

        if ($exportType === 'excel') {
            $reportData = $query->orderBy($sortBy, $sortDir)->get();
            $filename = "Laporan_Penjualan_Produk_{$startDate}_to_{$endDate}.xls";
            
            return response()->view('laporan.penjualan-produk.excel', compact('reportData', 'startDate', 'endDate', 'totals'))
                ->header('Content-Type', 'application/vnd.ms-excel')
                ->header('Content-Disposition', "attachment; filename={$filename}")
                ->header('Cache-Control', 'max-age=0');
        }

        if ($exportType === 'pdf') {
            $reportData = $query->orderBy($sortBy, $sortDir)->get();
            return view('laporan.penjualan-produk.pdf', compact('reportData', 'startDate', 'endDate', 'totals'));
        }

        // Tampilan Standar Web dengan Pagination
        $reportData = $query->orderBy($sortBy, $sortDir)->paginate(15)->withQueryString();

        return view('laporan.penjualan-produk.index', compact(
            'reportData', 'startDate', 'endDate', 'sortBy', 'sortDir', 'totals'
        ));
    }
}