<?php

namespace App\Http\Controllers\Agency\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AgencyLoginController extends Controller
{
    public function create()
    {
        return view('agency.auth.login');
    }

    public function store(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

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
                    ->onlyInput('email');
            }

            return redirect()->intended(route('agency.dashboard'));
        }

        return back()
            ->withErrors([
                'email' => 'Invalid agency login credentials.',
            ])
            ->onlyInput('email');
    }

    public function destroy(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('agency.login');
    }
}
