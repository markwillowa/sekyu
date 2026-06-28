<?php

namespace App\Http\Controllers\Agency;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $agency = auth()->user()->agency;

        abort_if(! $agency, 403);

        $startDateStr = $request->query('start_date');
        $startDate = $startDateStr ? \Carbon\Carbon::parse($startDateStr) : now()->startOfDay();
        $endDate = $startDate->copy()->addDays(7)->endOfDay();

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

        $calendarInterviews = \App\Models\Interview::whereHas('jobApplication.job', function ($query) use ($agency) {
                $query->where('agency_id', $agency->id);
            })
            ->whereBetween('scheduled_at', [$startDate, $endDate])
            ->where('status', 'scheduled')
            ->with(['jobApplication.job.agency', 'jobApplication.applicant'])
            ->orderBy('scheduled_at')
            ->get();

        $upcomingInterviewsCount = \App\Models\Interview::whereHas('jobApplication.job', function ($query) use ($agency) {
                $query->where('agency_id', $agency->id);
            })
            ->where('scheduled_at', '>=', now())
            ->where('status', 'scheduled')
            ->count();

        return view('agency.dashboard', [
            'activeJobsCount' => $activeJobsCount,
            'draftJobsCount' => $draftJobsCount,
            'applicationsCount' => $applicationsCount,
            'interviewsCount' => $upcomingInterviewsCount,
            'calendarInterviews' => $calendarInterviews,
            'startDate' => $startDate,
            'prevUrl' => route('agency.dashboard', ['start_date' => $startDate->copy()->subDays(7)->format('Y-m-d')]),
            'nextUrl' => route('agency.dashboard', ['start_date' => $startDate->copy()->addDays(7)->format('Y-m-d')]),
            'latestJobPosts' => $latestJobPosts,
            'latestJobApplications' => $latestJobApplications,
        ]);
    }
}
