<?php

namespace App\Http\Controllers\Pro\Employee;

use App\Http\Controllers\Controller;

class DashboardController extends Controller
{
    public function index()
    {
        return view('pro.employee.dashboard');
    }
}
