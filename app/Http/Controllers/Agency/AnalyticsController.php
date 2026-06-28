<?php

namespace App\Http\Controllers\Agency;

use App\Http\Controllers\Controller;
use App\Models\JobApplication;
use App\Models\JobApplicationHistory;
use App\Models\JobPost;
use App\Models\WorkflowTemplateStep;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AnalyticsController extends Controller
{
    public function index(Request $request)
    {
        $agency = auth()->user()->agency;
        abort_if(!$agency, 403);

        // Basic Stats
        $jobsPosted = $agency->jobPosts()->count();
        $totalApplicants = $agency->jobApplications()->count();

        $interviewedCount = $agency->jobApplications()
            ->whereHas('histories.step', function ($query) {
                $query->where('type', 'interview');
            })->count();

        $hiredCount = $agency->jobApplications()
            ->whereHas('currentStep', function ($query) {
                $query->where('is_terminal', true)
                      ->where(function($q) {
                          $q->where('name', 'like', '%Hired%')
                            ->orWhere('name', 'like', '%Deployment%');
                      });
            })->count();

        // Hiring Funnel
        // We'll group by step type or name. Types seem more consistent.
        $funnelData = $this->getFunnelData($agency);

        // Workflow Bottlenecks
        $bottlenecks = $this->getBottlenecks($agency);

        // Time to Hire
        $avgTimeToHire = $agency->jobApplications()
            ->whereNotNull('completed_at')
            ->whereHas('currentStep', function ($query) {
                $query->where('is_terminal', true);
            })
            ->select(DB::raw('AVG(DATEDIFF(completed_at, applied_at)) as avg_days'))
            ->first()->avg_days;

        // Top Performing Jobs
        $topJobs = JobPost::where('agency_id', $agency->id)
            ->withCount(['applications as applicants_count', 'applications as hires_count' => function($query) {
                $query->whereHas('currentStep', function($q) {
                    $q->where('is_terminal', true);
                });
            }])
            ->orderByDesc('applicants_count')
            ->take(5)
            ->get();

        return view('agency.analytics.index', [
            'jobsPosted' => $jobsPosted,
            'totalApplicants' => $totalApplicants,
            'interviewedCount' => $interviewedCount,
            'hiredCount' => $hiredCount,
            'funnelData' => $funnelData,
            'bottlenecks' => $bottlenecks,
            'avgTimeToHire' => round($avgTimeToHire ?? 0, 1),
            'topJobs' => $topJobs,
        ]);
    }

    private function getFunnelData($agency)
    {
        // Define common steps for the funnel
        $steps = [
            'Applicants' => null, // Special case for total
            'Reviewed' => ['normal'], // Often "Resume Screening"
            'Interviewed' => ['interview'],
            'Medical' => ['medical_exam'],
            'Hired' => ['deployment'] // Terminal
        ];

        $results = [];
        $results['Applicants'] = $agency->jobApplications()->count();

        foreach ($steps as $label => $types) {
            if ($label === 'Applicants') continue;

            $results[$label] = $agency->jobApplications()
                ->whereHas('histories.step', function ($query) use ($types) {
                    $query->whereIn('type', $types);
                })->count();
        }

        return $results;
    }

    private function getBottlenecks($agency)
    {
        // Calculate avg time spent in each step
        // We need to compare JobApplicationHistory entries for the same application

        $histories = DB::table('job_application_histories as h1')
            ->join('job_application_histories as h2', function($join) {
                $join->on('h1.job_application_id', '=', 'h2.job_application_id')
                     ->on('h1.id', '<', 'h2.id');
            })
            ->join('workflow_template_steps as s', 'h1.workflow_step_id', '=', 's.id')
            ->join('job_applications as a', 'h1.job_application_id', '=', 'a.id')
            ->join('job_posts as j', 'a.job_id', '=', 'j.id')
            ->where('j.agency_id', $agency->id)
            ->select(
                's.name',
                DB::raw('AVG(DATEDIFF(h2.created_at, h1.created_at)) as avg_days')
            )
            ->groupBy('s.id', 's.name')
            ->orderBy('avg_days', 'desc')
            ->get();

        return $histories;
    }
}
