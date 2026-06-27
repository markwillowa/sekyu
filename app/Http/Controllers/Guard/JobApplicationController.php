<?php

namespace App\Http\Controllers\Guard;

use App\Http\Controllers\Controller;
use App\Models\JobApplication;
use Illuminate\Http\Request;

class JobApplicationController extends Controller
{
    public function index()
    {
        $applications = auth()->user()->jobApplications()
            ->with(['job.agency', 'currentStep'])
            ->latest()
            ->paginate(10);

        return view('guard.applications.index', compact('applications'));
    }

    public function show(JobApplication $application)
    {
        if ($application->guard_id !== auth()->id()) {
            abort(403);
        }

        $application->load(['job.agency', 'currentStep', 'histories.step', 'job.workflowTemplate.steps']);

        return view('guard.applications.show', compact('application'));
    }
}
