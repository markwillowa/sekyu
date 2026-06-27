<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\JobPost;
use App\Models\MasterEmploymentType;
use App\Models\MasterJobStatus;
use App\Models\MasterLocation;
use App\Models\MasterShiftType;
use Illuminate\Http\Request;

class JobController extends Controller
{
    public function index(Request $request)
    {
        $statusIds = MasterJobStatus::whereIn('code', ['published', 'active'])->pluck('id');

        $query = JobPost::query()
            ->whereIn('job_status_id', $statusIds)
            ->with([
                'agency' => function($q) {
                    $q->withCount(['jobPosts as active_jobs_count' => function($sq) {
                        $sq->whereHas('status', function($ssq) {
                            $ssq->whereIn('code', ['published', 'active']);
                        });
                    }]);
                },
                'employmentType',
                'workLocationType',
                'salaryType',
                'location'
            ]);

        // Search Filters
        if ($request->filled('q')) {
            $query->where(function($q) use ($request) {
                $q->where('title', 'like', '%' . $request->q . '%')
                  ->orWhere('description', 'like', '%' . $request->q . '%');
            });
        }

        if ($request->filled('location')) {
            $query->where(function($q) use ($request) {
                $queryLocation = $request->location;
                $q->where('city', 'like', '%' . $queryLocation . '%')
                  ->orWhere('province', 'like', '%' . $queryLocation . '%')
                  ->orWhereHas('location', function($lq) use ($queryLocation) {
                      $lq->where('name', 'like', '%' . $queryLocation . '%');
                  });
            });
        }

        if ($request->filled('type')) {
            $query->whereIn('employment_type_id', $request->type);
        }

        if ($request->filled('loc')) {
            $query->whereIn('location_id', $request->loc);
        }

        if ($request->filled('salary_min')) {
            $query->where('salary_max', '>=', $request->salary_min);
        }

        // Sorting
        $sort = $request->get('sort', 'newest');
        switch ($sort) {
            case 'oldest':
                $query->oldest('published_at');
                break;
            case 'salary_high':
                $query->orderByDesc('salary_max');
                break;
            case 'salary_low':
                $query->orderBy('salary_min');
                break;
            case 'newest':
            default:
                $query->latest();
                break;
        }

        $jobs = $query->paginate(50)->withQueryString();

        // Filter data
        $employmentTypes = MasterEmploymentType::where('is_active', true)->orderBy('sort_order')->get();
        $locations = MasterLocation::where('is_active', true)->orderBy('sort_order')->get();

        // Check if we should filter random jobs too
        $isFiltered = $request->anyFilled(['q', 'location', 'type', 'loc', 'salary_min']);

        // Saved jobs for current user
        $savedJobs = collect();
        if (auth()->check()) {
            $savedJobs = auth()->user()->savedJobs()
                ->whereIn('job_status_id', $statusIds)
                ->with([
                    'agency' => function($q) use ($statusIds) {
                        $q->withCount(['jobPosts as active_jobs_count' => function($sq) use ($statusIds) {
                            $sq->whereIn('job_status_id', $statusIds);
                        }]);
                    },
                    'employmentType',
                    'workLocationType',
                    'salaryType',
                    'location'
                ])
                ->get();
        }

        // Fetch 4 random jobs for the top section (prioritizing featured)
        $featuredQuery = JobPost::query()
            ->whereIn('job_status_id', $statusIds)
            ->where(function ($q) {
                $q->whereNull('expires_at')
                    ->orWhere('expires_at', '>', now());
            })
            ->with([
                'agency' => function($q) {
                    $q->withCount(['jobPosts as active_jobs_count' => function($sq) {
                        $sq->whereHas('status', function($ssq) {
                            $ssq->whereIn('code', ['published', 'active']);
                        });
                    }]);
                },
                'employmentType',
                'workLocationType',
                'salaryType',
                'location'
            ]);

        // If we are on a search results page or have active filters, we might want to pick from the filtered query instead.
        // But the user said "4 random jobs are on top", implying a global feature or something.
        // If I use the filtered query, it might be empty.
        // Let's stick with global for now as "Featured" usually works that way.

        $randomJobs = (clone $featuredQuery)->where('is_featured', true)->inRandomOrder()->take(4)->get();

        if ($randomJobs->count() < 4) {
            $excludeIds = $randomJobs->pluck('id')->toArray();
            $additionalJobs = (clone $featuredQuery)
                ->whereNotIn('id', $excludeIds)
                ->inRandomOrder()
                ->take(4 - $randomJobs->count())
                ->get();
            $randomJobs = $randomJobs->concat($additionalJobs);
        }

        return view('public.jobs.index', compact('jobs', 'randomJobs', 'employmentTypes', 'locations', 'isFiltered', 'savedJobs'));
    }
}
