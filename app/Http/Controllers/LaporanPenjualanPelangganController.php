<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class LaporanPenjualanPelangganController extends Controller
{
    public function index(Request $request)
    {
        // 1. Filter Tanggal
        $startDate = $request->get('start_date', Carbon::now()->startOfMonth()->toDateString());
        $endDate = $request->get('end_date', Carbon::now()->toDateString());

        // 2. Tangkap Parameter Sorting Dinamis (Default: total_belanja terbesar)
        $sortBy = $request->get('sort_by', 'total_belanja'); 
        $sortDir = $request->get('sort_dir', 'desc');

        // Validasi kolom sorting demi keamanan
        $allowedSorts = ['kode_pelanggan', 'nama_pelanggan', 'total_transaksi', 'total_belanja'];
        if (!in_array($sortBy, $allowedSorts)) $sortBy = 'total_belanja';
        if (!in_array($sortDir, ['asc', 'desc'])) $sortDir = 'desc';

        // 3. Query Base (Gunakan Alias agar select DB::raw bisa di-sorting dengan mudah)
        $query = DB::table('transactions')  
            // ->leftJoin('customers', 'transactions.pelanggan', '=', 'customers.kode_pelanggan')
            ->leftJoin('customers', 'transactions.pelanggan', '=', 'customers.id')
            ->select(
                'transactions.pelanggan as kode_pelanggan',
                DB::raw('COALESCE(customers.nama, "Umum") as nama_pelanggan'),
                DB::raw('COUNT(transactions.id) as total_transaksi'),
                DB::raw('SUM(transactions.grand_total) as total_belanja')
            )
            ->where('transactions.status', '!=', 'Batal') // 👈 Filter mengabaikan transaksi batal
            ->whereBetween(DB::raw('DATE(transactions.created_at)'), [$startDate, $endDate])
            ->groupBy('transactions.pelanggan',
                    'customers.kode_pelanggan',
                    'customers.nama');
            // ->groupBy('transactions.pelanggan', 'customers.nama');

        // 4. Hitung Total Akumulasi Keseluruhan (Grand Total) Lintas Halaman
        $totals = DB::table(DB::raw("({$query->toSql()}) as sub"))
            ->mergeBindings($query)
            ->select(
                DB::raw('SUM(total_transaksi) as grand_qty_transaksi'),
                DB::raw('SUM(total_belanja) as grand_omset')
            )->first();

        // 5. Cek Aksi Export
        $exportType = $request->get('export');

        if ($exportType === 'excel') {
            $reportData = $query->orderBy($sortBy, $sortDir)->get();
            $filename = "Laporan_Penjualan_Pelanggan_{$startDate}_to_{$endDate}.xls";
            
            return response()->view('laporan.penjualan-pelanggan.excel', compact('reportData', 'startDate', 'endDate', 'totals'))
                ->header('Content-Type', 'application/vnd.ms-excel')
                ->header('Content-Disposition', "attachment; filename={$filename}")
                ->header('Cache-Control', 'max-age=0');
        }

        if ($exportType === 'pdf') {
            $reportData = $query->orderBy($sortBy, $sortDir)->get();
            return view('laporan.penjualan-pelanggan.pdf', compact('reportData', 'startDate', 'endDate', 'totals'));
        }

        
        // Tampilan Standar Web dengan Paging (25 Baris)
        $reportData = $query->orderBy($sortBy, $sortDir)->paginate(25)->withQueryString();

        return view('laporan.penjualan-pelanggan.index', compact(
            'reportData', 'startDate', 'endDate', 'sortBy', 'sortDir', 'totals'
        ));
    }
}