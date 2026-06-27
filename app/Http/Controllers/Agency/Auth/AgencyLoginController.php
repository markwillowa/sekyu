<?php

namespace App\Http\Controllers\Agency\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AgencyLoginController extends Controller
{
    public function create()
    {
        return redirect()->route('home')->with('open_login_modal', true);
    }

    public function store(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        $request->merge(['account_type' => 'agency']);

        $remember = $request->boolean('remember');

        if (Auth::attempt([
            'email' => $credentials['email'],
            'password' => $credentials['password'],
        ], $remember)) {
            $request->session()->regenerate();

            if (! auth()->user()->hasRole('agency')) {
                Auth::logout();

                return back()
                    ->withErrors([
                        'email' => 'This account is not registered as an agency.',
                    ])
                    ->onlyInput('email', 'account_type');
            }

            return redirect()->intended(route('agency.dashboard'));
        }

        return back()
            ->withErrors([
                'email' => 'Invalid agency login credentials.',
            ])
            ->onlyInput('email', 'account_type');
    }

    public function destroy(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('agency.login');
    }
}
