<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Shift;

class CheckOpenShift
{
    public function handle(Request $request, Closure $next)
    {
        // 1. Cek apakah ada user yang sedang login (apapun role-nya)
        if (Auth::check()) {
            
            // 2. Cek apakah user yang sedang login ini punya shift yang statusnya masih 'open'
            $activeShift = Shift::where('user_id', Auth::id())
                                ->where('status', 'open')
                                ->exists();

            // 3. Kalau TIDAK ADA shift yang open, dan dia TIDAK sedang di halaman aktivasi shift
            if (!$activeShift && !$request->is('pos/open-shift*')) {
                // Tendang ke halaman input modal awal
                return redirect()->route('pos.open-shift');
            }
        }

        return $next($request);
    }
}   