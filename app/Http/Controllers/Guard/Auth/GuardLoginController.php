<?php

namespace App\Http\Controllers\Guard\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class GuardLoginController extends Controller
{
    public function create(): RedirectResponse
    {
        return redirect()->route('home')->with('open_login_modal', true);
    }

    public function store(Request $request): RedirectResponse
    {
            $credentials = $request->validate([
                'email' => ['required', 'email'],
                'password' => ['required', 'string'],
            ]);

            $request->merge(['account_type' => 'applicant']);

        if (! Auth::attempt($credentials, $request->boolean('remember'))) {
            return back()
                ->withErrors([
                    'email' => 'Invalid email or password.',
                ])
                ->onlyInput('email', 'account_type');
        }

        $request->session()->regenerate();

        if (! auth()->user()->hasRole('applicant')) {
            Auth::logout();

            return back()
                ->withErrors([
                    'email' => 'This account is not authorized to access the applicant portal.',
                ])
                ->onlyInput('email', 'account_type');
        }

        return redirect()->route('applicant.dashboard');
    }

    public function destroy(Request $request): RedirectResponse
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('applicant.login');
    }
}
