<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Middleware untuk mengatur akses berdasarkan role user.
 * Memastikan keamanan data dan mencegah akses tidak sah ke fitur sensitif.
 */
class CheckRole
{
    /**
     * Handle an incoming request.
     *
     * @param array|string $roles Role yang diizinkan mengakses route
     */
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        if (!auth()->check()) {
            return redirect()->route('login');
        }

        $userRole = auth()->user()->role;

        if (!in_array($userRole, $roles)) {
            // Redirect ke dashboard sesuai role jika akses ditolak
            $message = 'Akses ditolak. Anda tidak memiliki izin untuk halaman ini.';
            return redirect()->route('dashboard')->with('error', $message);
        }

        return $next($request);
    }
}
