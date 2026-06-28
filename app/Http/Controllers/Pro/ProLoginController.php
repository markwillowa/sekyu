<?php

namespace App\Http\Controllers\Pro;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProLoginController extends Controller
{
    public function create()
    {
        return view('pro.auth.login');
    }

    public function store(Request $request)
    {
        $type = $request->input('type', 'agency'); // 'agency' or 'employee'

        if ($type === 'employee') {
            $credentials = $request->validate([
                'username' => ['required'],
                'password' => ['required'], // PIN
            ]);

            if (Auth::guard('pro_employee')->attempt($credentials, $request->boolean('remember'))) {
                $request->session()->regenerate();

                $user = Auth::guard('pro_employee')->user();
                $user->update([
                    'last_login_at' => now(),
                ]);

                return redirect()->intended(route('pro-2.index'));
            }
        } else {
            $credentials = $request->validate([
                'username' => ['required'],
                'password' => ['required'], // PIN
            ]);

            if (Auth::guard('pro_agency')->attempt($credentials, $request->boolean('remember'))) {
                $request->session()->regenerate();

                $user = Auth::guard('pro_agency')->user();
                $user->update([
                    'last_login_at' => now(),
                ]);

                return redirect()->intended(route('pro-2.index'));
            }
        }

        return back()
            ->withErrors([
                'username' => 'Invalid username or password.',
            ])
            ->withInput($request->only('username', 'type'));
    }

    public function destroy(Request $request)
    {
        Auth::guard('pro_agency')->logout();
        Auth::guard('pro_employee')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('pro-2.login');
    }
}
