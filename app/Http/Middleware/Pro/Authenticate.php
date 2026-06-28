<?php

namespace App\Http\Middleware\Pro;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class Authenticate
{
    public function handle(Request $request, Closure $next): Response
    {
        if (
            Auth::guard('pro_agency')->check() ||
            Auth::guard('pro_employee')->check()
        ) {
            return $next($request);
        }

        return redirect()->route('pro.login');
    }
}
