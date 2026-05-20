<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class PemilikOnly
{
    public function handle(Request $request, Closure $next)
    {
        if (!auth()->check() || auth()->user()->role !== 'pemilik') {
            abort(403, 'Akses ditolak. Hanya Pemilik yang dapat mengakses halaman ini.');
        }
        return $next($request);
    }
}
