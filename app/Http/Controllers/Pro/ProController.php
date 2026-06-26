<?php

namespace App\Http\Controllers\Pro;

use App\Http\Controllers\Controller;

class ProController extends Controller
{
    public function index()
    {
        return view('pro.index');
    }
}
