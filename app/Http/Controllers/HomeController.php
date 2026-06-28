<?php

namespace App\Http\Controllers;

use App\Models\JobPost;
use App\Models\MasterJobStatus;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        $activeStatus = MasterJobStatus::whereIn('code', ['active', 'published'])->get();
        $activeStatusIds = $activeStatus->pluck('id')->toArray();

        $query = JobPost::with(['agency', 'employmentType', 'workLocationType', 'salaryType'])
            ->withCount('applications')
            ->whereIn('job_status_id', $activeStatusIds)
            ->where(function ($q) {
                $q->whereNull('expires_at')
                    ->orWhere('expires_at', '>', now());
            })
            ->latest('published_at');

        // First, try to get featured jobs
        $jobs = (clone $query)->where('is_featured', true)->inRandomOrder()->take(4)->get();

        // If no featured jobs, get non-featured ones
        if ($jobs->isEmpty()) {
            $jobs = $query->inRandomOrder()->take(4)->get();
        }

        $profileCompletion = 0;
        if (auth()->check()) {
            $user = auth()->user();
            if ($user->hasRole('applicant')) {
                $completionService = app(\App\Services\Guard\ProfileCompletionService::class);
                $profileCompletion = $completionService->calculate($user->guardProfile)['percentage'];
            }
        }

        return view('welcome', compact('jobs', 'profileCompletion'));
    }
}
