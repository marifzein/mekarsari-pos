<?php

namespace App\Http\Controllers\Laporan;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class StockValuationController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');
        
        // 💡 Ambil parameter sorting (Default urutkan berdasarkan Nilai Aset terbesar)
        $sortBy = $request->input('sort_by', 'total_nilai_aset'); 
        $sortDir = $request->input('sort_dir', 'desc');

        // Validasi kolom sorting agar aman dari SQL Injection
        $allowedSorts = ['kode_barang', 'nama_barang', 'stok', 'hpp_average', 'harga_jual', 'total_nilai_aset', 'total_potensi_omset'];
        if (!in_array($sortBy, $allowedSorts)) $sortBy = 'total_nilai_aset';
        if (!in_array($sortDir, ['asc', 'desc'])) $sortDir = 'desc';

        // Query Base
        $query = DB::table('products')
            ->select(
                'kode_barang',
                'nama_barang',
                'stok',
                'harga_beli as hpp_average',
                'harga as harga_jual',
                DB::raw('(stok * harga_beli) as total_nilai_aset'),
                DB::raw('(stok * harga) as total_potensi_omset')
            )
            ->where('stok', '>', 0);

        // Tambah filter pencarian jika diisi
        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('nama_barang', 'like', "%{$search}%")
                  ->orWhere('kode_barang', 'like', "%{$search}%");
            });
        }

        // Aplikasikan sorting dinamis
        $query->orderBy($sortBy, $sortDir);

        // Menghitung total seluruh isi toko (sesuai filter pencarian)
        $totalAsetToko = DB::table('products')
            ->select(
                DB::raw('SUM(stok * harga_beli) as grand_total_aset'),
                DB::raw('SUM(stok * harga) as grand_total_jual')
            )
            ->where('stok', '>', 0)
            ->when($search, function($q) use ($search) {
                $q->where(function($sub) use ($search) {
                    $sub->where('nama_barang', 'like', "%{$search}%")
                        ->orWhere('kode_barang', 'like', "%{$search}%");
                });
            })
            ->first();

        // Cek Aksi Export
        $exportType = $request->input('export');

        if ($exportType === 'excel') {
            $reportData = $query->input();
            $filename = "Laporan_Nilai_Aset_Stok_" . now()->format('Y-m-d') . ".xls";
            
            return response()->view('laporan.nilai-aset-stok.excel', compact('reportData', 'totalAsetToko'))
                ->header('Content-Type', 'application/vnd.ms-excel')
                ->header('Content-Disposition', "attachment; filename={$filename}")
                ->header('Cache-Control', 'max-age=0');
        }

        if ($exportType === 'pdf') {
            $reportData = $query->input();
            return view('laporan.nilai-aset-stok.pdf', compact('reportData', 'totalAsetToko'));
        }

        // Tampilan Standar Web
        $reportData = $query->paginate(30)->withQueryString();

        return view('laporan.nilai-aset-stok.index', compact('reportData', 'totalAsetToko', 'search', 'sortBy', 'sortDir'));
    }
}