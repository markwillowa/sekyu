<?php

namespace App\Http\Controllers\Agency;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $agency = auth()->user()->agency;

        abort_if(! $agency, 403);

        $activeJobsCount = $agency->jobPosts()
            ->whereHas('status', function ($query) {
                $query->where('code', 'active');
            })
            ->count();

        $draftJobsCount = $agency->jobPosts()
            ->whereHas('status', function ($query) {
                $query->where('code', 'draft');
            })
            ->count();

        $latestJobPosts = $agency->jobPosts()
            ->with(['status', 'employmentType'])
            ->latest()
            ->take(5)
            ->get();

        $applicationsCount = $agency->jobApplications()->count();

        $latestJobApplications = $agency->jobApplications()
            ->with(['applicant.guardProfile', 'job', 'currentStep'])
            ->orderByDesc('applied_at')
            ->take(5)
            ->get();

        return view('agency.dashboard', [
            'activeJobsCount' => $activeJobsCount,
            'draftJobsCount' => $draftJobsCount,
            'applicationsCount' => $applicationsCount,
            'interviewsCount' => 0,   // Placeholder
            'latestJobPosts' => $latestJobPosts,
            'latestJobApplications' => $latestJobApplications,
        ]);
    }
}
