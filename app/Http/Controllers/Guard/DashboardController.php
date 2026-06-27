<?php

namespace App\Http\Controllers\Guard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        $applicationsCount = $user->jobApplications()->count();
        $recentApplications = $user->jobApplications()
            ->with(['job.agency', 'currentStep'])
            ->latest()
            ->take(5)
            ->get();

        return view('guard.dashboard', [
            'applicationsCount' => $applicationsCount,
            'interviewsCount' => 0,   // Placeholder
            'savedJobsCount' => $user->savedJobs()->count(),
            'profileCompletion' => 45, // Placeholder
            'recentApplications' => $recentApplications,
        ]);
    }
}
