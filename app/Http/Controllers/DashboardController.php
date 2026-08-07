<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Transaction;
use App\Models\TransactionDetail;
use App\Models\StockOpname;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        /*
        |--------------------------------------------------------------------------
        | FILTER USER MOBILE / KASIR
        |--------------------------------------------------------------------------
        */
        $user = Auth::user();
        $userAgent = $request->header('User-Agent');
        
        // Deteksi apakah perangkat yang mengakses adalah Mobile/Tablet
        $isMobile = preg_match('/(android|bb\d+|meego).+mobile|avantgo|bada\/|blackberry|blazer|compal|elaine|fennec|hiptop|iemobile|ip(hone|od)|iris|kindle|lge |maemo|midp|mmp|mobile.+firefox|netfront|opera m(ob|in)i|palm( os)?|phone|p(ixi|re)\/|plucker|pocket|psp|series(4|6)0|symbian|treo|up\.(browser|link)|vodafone|wap|windows ce|xda|xiino/i', $userAgent) 
                || preg_match('/(ipad|tablet|(android(?!.*mobile)))/i', $userAgent);

        // Jika dia adalah Kasir DAN login lewat HP/Tablet, lempar langsung ke POS Mobile
        if ($user && $user->role === 'Kasir' && $isMobile) {
            return redirect()->route('pos.mobile');
        }
        
        /*
        |--------------------------------------------------------------------------
        | LOGIC DASHBOARD UTAMA DESKTOP (Jalan jika PC / Admin)
        |--------------------------------------------------------------------------
        */
        $todaySales =
            Transaction::whereDate(
                'created_at',
                today()
            )
            ->sum('grand_total');

        $todayTransactions =
            Transaction::whereDate(
                'created_at',
                today()
            )
            ->count();

        $totalProducts =
            Product::count();
        
        $hargaBeliNolCount = Product::where(function($query) {
            $query->where('harga_beli', 0)
                ->orWhereNull('harga_beli');
        })->count();

        $totalStock =
            Product::sum('stok');

        $lowStocks =
            Product::where('stok', '<=', 5)
            ->orderBy('stok')
            ->limit(10)
            ->get();
        
        $latestTransactions =
            Transaction::latest()
            ->limit(10)
            ->get();

        $topProducts = TransactionDetail::select(
                'nama_barang',
                DB::raw('SUM(qty) as total_terjual')
            )
            ->groupBy('nama_barang')
            ->orderByDesc('total_terjual')
            ->limit(10)
            ->get();
        
        $lastOpname = StockOpname::latest()->first();
        
        
        /*
        |--------------------------------------------------------------------------
        | Grafik Penjualan 7 Hari
        |--------------------------------------------------------------------------
        */

        $salesChart = [];

        for ($i = 6; $i >= 0; $i--) {

            $date = Carbon::today()->subDays($i);

            $salesChart[] = [

                'tanggal' => $date->format('d M'),

                'total' => Transaction::whereDate(
                    'created_at',
                    $date
                )->sum('grand_total')

            ];

        }

        return view(
            'dashboard.index',
            compact(
                'todaySales',
                'todayTransactions',
                'totalProducts',
                'hargaBeliNolCount',
                'totalStock',
                'lowStocks',
                'latestTransactions',
                'topProducts',
                'lastOpname',
                'salesChart'
            )
        );
    }

    
}