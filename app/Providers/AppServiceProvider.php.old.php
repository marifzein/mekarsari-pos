<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

// use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;


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
        
    }
}
