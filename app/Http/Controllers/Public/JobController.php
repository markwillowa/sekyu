<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\JobPost;
use App\Models\MasterJobStatus;
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
                'salaryType'
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
                $q->where('city', 'like', '%' . $request->location . '%')
                  ->orWhere('province', 'like', '%' . $request->location . '%');
            });
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
                $query->latest('published_at');
                break;
        }

        $jobs = $query->paginate(12)->withQueryString();

        return view('public.jobs.index', compact('jobs'));
    }
}
