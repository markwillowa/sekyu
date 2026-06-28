<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\JobApplication;
use App\Models\JobPost;
use App\Notifications\NewJobApplication;
use App\Services\Guard\ProfileCompletionService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class JobApplicationController extends Controller
{
    public function store(Request $request, JobPost $jobPost, ProfileCompletionService $completionService)
    {
        $user = auth()->user();
        if ($user->hasRole('agency') || $user->hasRole('admin')) {
            $message = "Only candidates with an applicant profile can apply for jobs.";
            if ($request->expectsJson()) {
                return response()->json(['error' => $message], 403);
            }
            return back()->with('error', $message);
        }

        // Check profile completion requirement
        if ($jobPost->min_profile_completion > 0) {
            $completion = $completionService->calculate($user->guardProfile);
            if ($completion['percentage'] < $jobPost->min_profile_completion) {
                $message = "Your profile is only {$completion['percentage']}% complete. This job requires at least {$jobPost->min_profile_completion}% completion to apply.";
                if ($request->expectsJson()) {
                    return response()->json(['error' => $message], 422);
                }
                return back()->with('error', $message);
            }
        }

        // Basic check if already applied
        if (JobApplication::where('job_id', $jobPost->id)->where('guard_id', $user->id)->exists()) {
            if ($request->expectsJson()) {
                return response()->json(['error' => 'You have already applied for this job.'], 422);
            }
            return back()->with('error', 'You have already applied for this job.');
        }

        $workflowTemplate = $jobPost->workflowTemplate;

        if (!$workflowTemplate) {
            if ($request->expectsJson()) {
                return response()->json(['error' => 'This job post is not ready for applications (missing workflow).'], 422);
            }
            return back()->with('error', 'This job post is not ready for applications (missing workflow).');
        }

        $firstStep = $workflowTemplate->steps()->first();

        if (!$firstStep) {
            if ($request->expectsJson()) {
                return response()->json(['error' => 'This job post is not ready for applications (missing workflow steps).'], 422);
            }
            return back()->with('error', 'This job post is not ready for applications (missing workflow steps).');
        }

        $application = null;

        DB::transaction(function () use ($jobPost, $user, $firstStep, &$application) {
            $application = JobApplication::create([
                'job_id' => $jobPost->id,
                'guard_id' => $user->id,
                'current_workflow_step_id' => $firstStep->id,
                'applied_at' => now(),
            ]);

            $application->histories()->create([
                'workflow_step_id' => $firstStep->id,
                'updated_by' => $user->id,
                'notes' => 'Application submitted.',
                'completed_at' => now(),
            ]);

            // Notify Agency Owner
            $jobPost->agency->owner->notify(new NewJobApplication($application));
        });

        if ($request->expectsJson()) {
            return response()->json([
                'success' => 'Your application has been submitted successfully!',
                'applied_at' => $application->applied_at->format('M d, Y')
            ]);
        }

        return back()->with('success', 'Your application has been submitted successfully!');
    }
}
