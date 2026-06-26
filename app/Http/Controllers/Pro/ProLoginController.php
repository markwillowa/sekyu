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
        $credentials = $request->validate([
            'username' => ['required'],
            'password' => ['required'],
        ]);

        if (
            $credentials['username'] !== 'admin'
            || $credentials['password'] !== 'admin'
        ) {
            return back()
                ->withErrors([
                    'username' => 'Invalid username or password.',
                ])
                ->onlyInput('username');
        }

        session([
            'pro_logged_in' => true,
        ]);

        return redirect()->route('pro.index');
    }

    public function destroy(Request $request)
    {
        $request->session()->forget('pro_logged_in');

        return redirect()->route('pro.login');
    }
}
