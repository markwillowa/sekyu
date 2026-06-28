<?php

namespace App\Http\Controllers\Pro;

use App\Http\Controllers\Controller;

class ProController extends Controller
{
    public function index()
    {
        if (! session('pro_logged_in')) {
            return redirect()->route('pro-2.login');
        }

        return view('pro.index');
    }
}
