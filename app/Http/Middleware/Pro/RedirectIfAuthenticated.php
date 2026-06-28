<?php

namespace App\Http\Middleware\Pro;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RedirectIfAuthenticated
{
    public function handle(Request $request, Closure $next): Response
    {
        if (Auth::guard('pro_agency')->check()) {

            return redirect()->route('pro.agency.dashboard');

        }

        if (Auth::guard('pro_employee')->check()) {

            return redirect()->route('pro.employee.dashboard');

        }

        return $next($request);
    }
}
