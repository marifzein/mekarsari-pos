<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;
use Symfony\Component\HttpFoundation\Response;

class CheckModuleAccess
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        // 🚀 BYPASS 1: Jika user TIDAK LOGIN (Guest), jangan dicek modulnya! 
        // Ini otomatis meloloskan redirect pasca-logout dan halaman login utama.
        if (!auth()->check()) {
            return $next($request);
        }

        // 🚀 BYPASS 2: Amankan rute-rute otentikasi berdasarkan nama rute atau URL
        if ($request->routeIs('logout') || $request->routeIs('login') || $request->is('logout*') || $request->is('login*')) {
            return $next($request);
        }

        // 1. Dapatkan nama Controller yang sedang diakses saat ini
        $routeAction = $request->route()?->getActionName(); 
        
        if ($routeAction && $routeAction !== 'Closure') {
            // Potong string untuk mengambil nama Class Controller-nya saja
            $segments = explode('@', $routeAction);
            $controllerFull = $segments[0];
            $controllerName = class_basename($controllerFull); 

            // 2. Ambil daftar modul yang AKTIF untuk client ini dari Cache
            $activeModules = Cache::remember('client_active_modules', 3600, function () {
                return DB::table('client_modules')
                    ->where('is_active', true)
                    ->pluck('controller_name')
                    ->toArray();
            });

            // 3. Pengecualian: Dashboard atau Profile boleh diakses semua orang
            $bypassControllers = [
                'DashboardController', 
                'ProfileController',
                'AuthenticatedSessionController', 
                'ConfirmablePasswordController',
                'EmailVerificationNotificationController',
                'EmailVerificationPromptController',
                'NewPasswordController',
                'PasswordController',
                'PasswordResetLinkController',
                'RegisteredUserController',
                'VerifyEmailController'
            ];

            // 4. Jika tidak ada di daftar bypass dan statusnya MATI (tidak ada di list aktif), blokir!
            if (!in_array($controllerName, $bypassControllers) && !in_array($controllerName, $activeModules)) {
                abort(403, 'Paket aplikasi Anda tidak mendukung fitur/halaman ini. Silakan hubungi admin/IT.');
            }
        }

        return $next($request);
    }
}       