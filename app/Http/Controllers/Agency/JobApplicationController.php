<?php

namespace App\Http\Controllers\Agency;

use App\Http\Controllers\Controller;
use App\Models\MasterInterviewType;
use App\Models\JobApplication;
use App\Models\GuardProfile;
use App\Notifications\ApplicationStepChanged;
use App\Services\Guard\ProfileCompletionService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class JobApplicationController extends Controller
{
    public function index(Request $request, ProfileCompletionService $completionService)
    {
        $agency = auth()->user()->agency;

        $query = $agency->jobApplications()
            ->with(['job', 'applicant.guardProfile', 'currentStep']);

        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('applicant', function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%");
            });
        }

        if ($request->filled('job_post_id')) {
            $query->where('job_id', $request->job_post_id);
        }

        $applications = $query->latest()
            ->paginate(15)
            ->withQueryString();

        foreach ($applications as $application) {
            $application->profile_completion = $completionService->calculate($application->applicant->guardProfile)['percentage'];
        }

        $jobPosts = $agency->jobPosts()->pluck('title', 'id');

        return view('agency.applications.index', compact('applications', 'jobPosts'));
    }

    public function kanban(Request $request)
    {
        $agency = auth()->user()->agency;

        $selectedJobId = $request->job_post_id;
        $jobPosts = $agency->jobPosts()->has('workflowTemplate')->pluck('title', 'id');

        if (!$selectedJobId && $jobPosts->isNotEmpty()) {
            $selectedJobId = $jobPosts->keys()->first();
        }

        $workflowSteps = collect();
        $applicationsByStep = collect();

        if ($selectedJobId) {
            $job = $agency->jobPosts()->with('workflowTemplate.steps')->find($selectedJobId);
            $workflowSteps = $job->workflowTemplate->steps;

            $applications = JobApplication::where('job_id', $selectedJobId)
                ->with(['applicant.guardProfile', 'currentStep'])
                ->get();

            $applicationsByStep = $applications->groupBy('current_workflow_step_id');
        }

        return view('agency.applications.kanban', compact('jobPosts', 'selectedJobId', 'workflowSteps', 'applicationsByStep'));
    }

    public function show(JobApplication $application, ProfileCompletionService $completionService)
    {
        // Authorize that the application belongs to a job of this agency
        $agency = auth()->user()->agency;
        if ($application->job->agency_id !== $agency->id) {
            abort(403);
        }

        $application->load([
            'job.workflowTemplate.steps',
            'applicant.guardProfile' => function ($query) {
                $query->with([
                    'workExperiences',
                    'licenses',
                    'certifications',
                    'skills',
                    'trainings',
                    'educations',
                    'clearances',
                    'medicals',
                    'identifications',
                ]);
            },
            'currentStep',
            'interviews.interviewer',
            'histories.step',
            'histories.user',
        ]);

        $workflowSteps = $application->job->workflowTemplate->steps;
        $profileCompletion = $completionService->calculate($application->applicant->guardProfile);
        $interviewTypes = MasterInterviewType::where('is_active', true)->orderBy('sort_order')->get();

        return view('agency.applications.show', compact('application', 'workflowSteps', 'profileCompletion', 'interviewTypes'));
    }

    public function move(Request $request, JobApplication $application)
    {
        $agency = auth()->user()->agency;
        if ($application->job->agency_id !== $agency->id) {
            abort(403);
        }

        $request->validate([
            'workflow_step_id' => 'required|exists:workflow_template_steps,id',
            'notes' => 'nullable|string',
        ]);

        $newStepId = $request->workflow_step_id;

        // Check if step belongs to the job's workflow template
        $workflowTemplate = $application->job->workflowTemplate;
        if (!$workflowTemplate->steps()->where('id', $newStepId)->exists()) {
            return back()->with('error', 'Invalid workflow step.');
        }

        DB::transaction(function () use ($application, $newStepId, $request) {
            $application->update([
                'current_workflow_step_id' => $newStepId,
            ]);

            $application->histories()->create([
                'workflow_step_id' => $newStepId,
                'updated_by' => auth()->id(),
                'notes' => $request->notes ?? 'Moved to next step.',
                'completed_at' => now(),
            ]);

            // Notify Guard
            $newStep = $application->job->workflowTemplate->steps()->find($newStepId);
            $application->applicant->notify(new ApplicationStepChanged($application, $newStep->name));
        });

        return back()->with('success', 'Applicant moved successfully.');
    }

    public function showGuardProfile(GuardProfile $guardProfile, ProfileCompletionService $completionService)
    {
        $guardProfile->load([
            'user',
            'gender',
            'civilStatus',
            'contactDetails',
            'physicalDetail',
            'employmentPreference',
            'educations',
            'workExperiences',
            'licenses',
            'certifications',
            'skills',
            'languages',
            'trainings',
            'references',
            'availability',
            'medicals',
            'clearances',
            'identifications',
            'emergencyContacts',
            'firearmQualification',
            'specializations',
        ]);

        $completion = $completionService->calculate($guardProfile);

        // We can reuse the guard profile view but we might need to adjust it for agency view
        // For now, let's use a dedicated view or a shared one.
        return view('agency.applications.guard-profile', [
            'profile' => $guardProfile,
            'user' => $guardProfile->user,
            'completion' => $completion['percentage'],
            'completionItems' => $completion['items'],
            'completionGroups' => $completion['groups'],
        ]);
    }
}
