<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class IsSeller
{
    public function handle($request, Closure $next)
    {
        if (!Auth::check() || Auth::user()->role !== 'seller') {
            return redirect('/seller/login')->withErrors([
                'access' => 'Akses ditolak, hanya penjual yang bisa masuk.'
            ]);
        }

        return $next($request);
    }
}
