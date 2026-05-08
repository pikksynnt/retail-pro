<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RoleManager
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string  ...$roles  <-- Pakai titik tiga biar bisa nerima banyak role sekaligus
     * @return mixed
     */
    public function handle(Request $request, Closure $next, ...$roles)
    {
        // 1. Jaring pengaman: Cek apakah user sudah login
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        // 2. Ambil role user yang sedang login saat ini
        $authUserRole = Auth::user()->role;

        // 3. Logika "Pintu Masuk": 
        // Cek apakah role user ada di dalam daftar $roles yang kita izinkan di web.php
        if (in_array($authUserRole, $roles)) {
            return $next($request);
        }

        // 4. Jika role tidak cocok (misal: tamu mau akses dashboard admin)
        // Kita lempar balik ke dashboard dengan pesan error
        return redirect()->route('dashboard')->with('error', 'Waduh bro, lu gak punya kunci buat masuk ke sini!');
    }
}