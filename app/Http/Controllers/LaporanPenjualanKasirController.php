<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth; // 💡 Jangan lupa import Auth di atas bos
use Carbon\Carbon;

class LaporanPenjualanKasirController extends Controller
{
    public function index(Request $request)
    {
        // Default tanggal dari tanggal 1 bulan ini sampai tanggal hari ini (2026)
        $dari_tanggal = $request->get('dari_tanggal', Carbon::now()->startOfMonth()->toDateString());
        $sampai_tanggal = $request->get('sampai_tanggal', Carbon::now()->toDateString());

        // Base Query untuk Laporan
        $query = DB::table('transactions')
            ->join('users', 'transactions.user_id', '=', 'users.id')
            ->select(
                DB::raw('DATE(transactions.created_at) as tanggal'),
                'users.name as nama_kasir',
                DB::raw('SUM(transactions.cash - transactions.kembalian) as total_cash'),
                DB::raw('SUM(transactions.card) as total_card'),
                DB::raw('SUM(transactions.voucher) as total_voucher'),
                DB::raw('SUM((transactions.cash - transactions.kembalian) + transactions.card + transactions.voucher) as total_grand')
            )
            ->whereBetween(DB::raw('DATE(transactions.created_at)'), [$dari_tanggal, $sampai_tanggal])
            ->where('transactions.status', '!=', 'Batal') // 👈 FILTER EXCLUDE BATAL
            ->groupBy(DB::raw('DATE(transactions.created_at)'), 'transactions.user_id', 'users.name')
            ->orderBy('tanggal', 'asc')
            ->orderBy('nama_kasir', 'asc');

        // Base Query untuk Total di Footer
        $totalsQuery = DB::table('transactions')
            ->select(
                DB::raw('SUM(transactions.cash - transactions.kembalian) as total_cash'),
                DB::raw('SUM(transactions.card) as total_card'),
                DB::raw('SUM(transactions.voucher) as total_voucher'),
                DB::raw('SUM((transactions.cash - transactions.kembalian) + transactions.card + transactions.voucher) as total_grand')
            )
            ->whereBetween(DB::raw('DATE(transactions.created_at)'), [$dari_tanggal, $sampai_tanggal])
            ->where('transactions.status', '!=', 'Batal'); // 👈 FILTER EXCLUDE BATAL;

        // 🔑 PROTEKSI MULTI-ROLE:
        // Jika yang login memiliki role 'kasir', batasi hanya melihat datanya sendiri.
        // Silakan sesuaikan string 'kasir' dengan value role yang ada di DB Anda.
        if (strtolower(Auth::user()->role) === 'kasir') {
            $query->where('transactions.user_id', Auth::id());
            $totalsQuery->where('transactions.user_id', Auth::id());
        }

        // Eksekusi data setelah filter role diterapkan
        $reports = $query->paginate(20)->withQueryString();
        $totals = $totalsQuery->first();

        return view('laporan.penjualan-kasir', compact('reports', 'totals', 'dari_tanggal', 'sampai_tanggal'));
    }
}