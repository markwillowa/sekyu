<?php

namespace App\Http\Controllers\Pro\Agency;

use App\Http\Controllers\Controller;

class DashboardController extends Controller
{
    public function index()
    {
        return view('pro.agency.dashboard');
    }
}
