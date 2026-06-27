<?php

namespace App\Http\Controllers\Agency;

use App\Http\Controllers\Controller;
use App\Models\WorkflowTemplate;
use App\Models\WorkflowTemplateStep;
use Illuminate\Http\Request;

class WorkflowTemplateStepController extends Controller
{
    public function store(Request $request, WorkflowTemplate $workflowTemplate)
    {
        $agency = auth()->user()->agency;
        abort_if(! $agency || $workflowTemplate->agency_id !== $agency->id, 403);

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'type' => ['required', 'string', 'in:normal,interview,document_request,medical_exam,training,job_offer,deployment'],
            'is_terminal' => ['nullable', 'boolean'],
        ]);

        $maxSortOrder = $workflowTemplate->steps()->max('sort_order') ?? 0;

        $workflowTemplate->steps()->create([
            ...$validated,
            'sort_order' => $maxSortOrder + 1,
            'is_terminal' => $request->boolean('is_terminal'),
        ]);

        return back()->with('success', 'Step added successfully.');
    }

    public function destroy(WorkflowTemplate $workflowTemplate, WorkflowTemplateStep $step)
    {
        $agency = auth()->user()->agency;
        abort_if(! $agency || $workflowTemplate->agency_id !== $agency->id, 403);
        abort_if($step->workflow_template_id !== $workflowTemplate->id, 403);

        if ($step->applications()->exists() || $step->histories()->exists()) {
            return back()->with('error', 'Cannot delete step that has associated applications.');
        }

        $step->delete();

        // Reorder remaining steps
        $workflowTemplate->steps()->orderBy('sort_order')->get()->each(function ($step, $index) {
            $step->update(['sort_order' => $index + 1]);
        });

        return back()->with('success', 'Step removed successfully.');
    }

    public function reorder(Request $request, WorkflowTemplate $workflowTemplate)
    {
        $agency = auth()->user()->agency;
        abort_if(! $agency || $workflowTemplate->agency_id !== $agency->id, 403);

        $request->validate([
            'steps' => ['required', 'array'],
            'steps.*' => ['required', 'exists:workflow_template_steps,id'],
        ]);

        foreach ($request->steps as $index => $stepId) {
            $workflowTemplate->steps()->where('id', $stepId)->update([
                'sort_order' => $index + 1,
            ]);
        }

        return response()->json(['success' => true]);
    }
}
