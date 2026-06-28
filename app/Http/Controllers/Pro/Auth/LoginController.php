<?php

namespace App\Http\Controllers\Pro\Auth;

use App\Enums\Pro\AccountStatus;
use App\Http\Controllers\Controller;
use App\Http\Requests\Pro\Auth\AgencyLoginRequest;
use App\Http\Requests\Pro\Auth\EmployeeLoginRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function index()
    {
        return view('pro.auth.login');
    }

    public function agency(AgencyLoginRequest $request)
    {
        if (! Auth::guard('pro_agency')->attempt([
            'username' => $request->username,
            'password' => $request->pin,
        ])) {

            return back()
                ->withErrors([
                    'username' => 'Invalid username or PIN.',
                ])
                ->onlyInput('username');
        }

        $request->session()->regenerate();

        $user = Auth::guard('pro_agency')->user();

        if ($user->status !== AccountStatus::Active) {

            Auth::guard('pro_agency')->logout();

            return back()->withErrors([
                'username' => 'Your account is inactive.',
            ]);
        }

        $user->update([
            'last_login_at' => now(),
        ]);

        return redirect()->intended(
            route('pro.agency.dashboard')
        );
    }

    public function employee(EmployeeLoginRequest $request)
    {
        if (! Auth::guard('pro_employee')->attempt([
            'username' => $request->employee_no,
            'password' => $request->pin,
        ])) {

            return back()
                ->withErrors([
                    'employee_no' => 'Invalid employee number or PIN.',
                ])
                ->onlyInput('employee_no');
        }

        $request->session()->regenerate();

        $user = Auth::guard('pro_employee')->user();

        if ($user->status !== AccountStatus::Active) {

            Auth::guard('pro_employee')->logout();

            return back()->withErrors([
                'employee_no' => 'Your account is inactive.',
            ]);
        }

        $user->update([
            'last_login_at' => now(),
        ]);

        return redirect()->intended(
            route('pro.employee.dashboard')
        );
    }

    public function logout(Request $request)
    {
        foreach ([
                     'pro_agency',
                     'pro_employee',
                 ] as $guard) {

            if (Auth::guard($guard)->check()) {
                Auth::guard($guard)->logout();
            }

        }

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect()->route('pro.login');
    }
}
