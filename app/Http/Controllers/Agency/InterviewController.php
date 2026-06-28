<?php

namespace App\Http\Controllers\Agency;

use App\Http\Controllers\Controller;
use App\Models\Interview;
use App\Models\JobApplication;
use App\Models\MasterInterviewType;
use Illuminate\Http\Request;

class InterviewController extends Controller
{
    public function store(Request $request, JobApplication $application)
    {
        $agency = auth()->user()->agency;
        if ($application->job->agency_id !== $agency->id) {
            abort(403);
        }

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'interview_type_id' => 'required|exists:master_interview_types,id',
            'scheduled_at' => 'required|date',
            'duration_minutes' => 'required|integer|min:15',
            'interviewer_id' => 'required|exists:users,id',
            'location' => 'nullable|string|max:255',
            'meeting_url' => 'nullable|url|max:255',
            'notes' => 'nullable|string',
        ]);

        $interviewType = MasterInterviewType::find($validated['interview_type_id']);

        $application->interviews()->create(array_merge($validated, [
            'type' => $interviewType->name,
            'workflow_step_id' => $application->current_workflow_step_id,
            'status' => 'scheduled',
        ]));

        return back()->with('success', 'Interview scheduled successfully.');
    }
}
