<?php

namespace App\Http\Controllers\Guard;

use App\Http\Controllers\Controller;
use App\Services\Guard\ProfileCompletionService;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(Request $request, ProfileCompletionService $completionService)
    {
        $user = auth()->user();

        $startDateStr = $request->query('start_date');
        $startDate = $startDateStr ? \Carbon\Carbon::parse($startDateStr) : now()->startOfDay();
        $endDate = $startDate->copy()->addDays(7)->endOfDay();

        $applicationsCount = $user->jobApplications()->count();
        $recentApplications = $user->jobApplications()
            ->with(['job.agency', 'currentStep'])
            ->latest()
            ->take(5)
            ->get();

        $calendarInterviews = \App\Models\Interview::whereHas('jobApplication', function ($query) use ($user) {
                $query->where('guard_id', $user->id);
            })
            ->whereBetween('scheduled_at', [$startDate, $endDate])
            ->where('status', 'scheduled')
            ->with('jobApplication.job.agency')
            ->orderBy('scheduled_at')
            ->get();

        $upcomingInterviewsCount = \App\Models\Interview::whereHas('jobApplication', function ($query) use ($user) {
                $query->where('guard_id', $user->id);
            })
            ->where('scheduled_at', '>=', now())
            ->where('status', 'scheduled')
            ->count();

        $profileCompletion = $completionService->calculate($user->guardProfile);

        return view('guard.dashboard', [
            'applicationsCount' => $applicationsCount,
            'interviewsCount' => $upcomingInterviewsCount,
            'calendarInterviews' => $calendarInterviews,
            'startDate' => $startDate,
            'prevUrl' => route('applicant.dashboard', ['start_date' => $startDate->copy()->subDays(7)->format('Y-m-d')]),
            'nextUrl' => route('applicant.dashboard', ['start_date' => $startDate->copy()->addDays(7)->format('Y-m-d')]),
            'savedJobsCount' => $user->savedJobs()->count(),
            'profileCompletion' => $profileCompletion['percentage'],
            'recentApplications' => $recentApplications,
        ]);
    }
}
