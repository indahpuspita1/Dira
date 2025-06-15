<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth; // Ini sudah benar
use Symfony\Component\HttpFoundation\Response; // Pastikan ini juga ada

class CheckRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     * @param  string  ...$roles
     * @return \Symfony\Component\HttpFoundation\Response  // Tambahkan tipe return ini
     */
    public function handle(Request $request, Closure $next, ...$roles): Response // Tambahkan tipe return Response di sini
    {
        // Periksa apakah user sudah login, apakah objek user ada, DAN apakah role-nya sesuai
        if (!Auth::check() || !Auth::user() || !in_array(Auth::user()->role, $roles)) {
            // Jika user tidak login, atau objek user tidak ada, atau rolenya tidak sesuai,
            // arahkan ke halaman lain atau beri error
            return redirect('/')->with('error', 'Anda tidak memiliki akses ke halaman ini.');
        }
        return $next($request);
    }
}