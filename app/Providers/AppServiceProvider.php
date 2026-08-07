<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

// use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;
use App\Models\FooterSetting;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\URL;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
    
        // NGROK Cek apakah request datang dari proxy Ngrok (ada header X-Forwarded-Proto https)
        // if (str_contains(request()->header('X-Forwarded-Proto'), 'https')) {
        //     // Paksa semua URL (asset(), route(), dll) pakai https://
        //     URL::forceScheme('https');
        // }

        // 1. MENU DEVELOPER & BACKUP DB: Hanya murni Admin IT saja
        Gate::define('akses-developer', function ($user) {
            return $user->role === 'Admin';
        });

        // 2. MENU STRATEGIS & KEUANGAN (Laporan, Laba Rugi, Pengaturan Toko): Owner & Admin
        Gate::define('akses-owner-admin', function ($user) {
            return in_array($user->role, ['Owner', 'Admin']);
        });

        // 3. MENU OPERASIONAL TINGGI (Buat PO, Approval Stok, Master Data): Owner, Admin, & Supervisor
        Gate::define('akses-spv-keatas', function ($user) {
            return in_array($user->role, ['Owner', 'Admin', 'Supervisor']);
        });

        // 4. MENU TRANSAKSI POS HARI-HARI (Kasir, Supervisor, Admin, Owner bisa buka)
        Gate::define('akses-pos', function ($user) {
            return in_array($user->role, ['Owner', 'Admin', 'Supervisor', 'Kasir']);
        });

        // =========================================================================
        // 💡 LOGIC BARU: Share Data Footer Menggunakan Caching (Anti-Beban Server)
        // =========================================================================
        View::composer('layouts.app', function ($view) {
            // Cek RAM server via Cache. Jika ada, pakai. Jika tidak ada/dihapus, baru senggol DB.
            $footerData = Cache::remember('global_footer_data', 86400, function () {
                return FooterSetting::first() ?? new FooterSetting([
                    'section_left' => 'DaCen : Fipman (Surabaya HQ)',
                    'section_center' => 'Modul: Point of Sales v2.1',
                    'section_right' => '© ' . date('Y') . ' POS Minimarket. Powered by Zezdev Style.'
                ]);
            });

            $view->with('footerData', $footerData);
        });
        
    }
}
