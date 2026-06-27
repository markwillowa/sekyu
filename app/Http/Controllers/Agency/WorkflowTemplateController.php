<?php

namespace App\Http\Controllers\Agency;

use App\Http\Controllers\Controller;
use App\Models\WorkflowTemplate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class WorkflowTemplateController extends Controller
{
    public function index()
    {
        $agency = auth()->user()->agency;
        abort_if(! $agency, 403);

        $templates = $agency->workflowTemplates()
            ->withCount('steps')
            ->latest()
            ->paginate(10);

        return view('agency.workflow-templates.index', compact('templates'));
    }

    public function create()
    {
        $stepNames = DB::table('master_workflow_step_names')
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->get();

        return view('agency.workflow-templates.create', compact('stepNames'));
    }

    public function store(Request $request)
    {
        $agency = auth()->user()->agency;
        abort_if(! $agency, 403);

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'is_default' => ['nullable', 'boolean'],
        ]);

        $template = $agency->workflowTemplates()->create([
            ...$validated,
            'is_default' => $request->boolean('is_default'),
        ]);

        return redirect()
            ->route('agency.workflow-templates.edit', $template)
            ->with('success', 'Workflow template created. Now add some steps!');
    }

    public function show(WorkflowTemplate $workflowTemplate)
    {
        return redirect()->route('agency.workflow-templates.edit', $workflowTemplate);
    }

    public function edit(WorkflowTemplate $workflowTemplate)
    {
        $agency = auth()->user()->agency;
        abort_if(! $agency || $workflowTemplate->agency_id !== $agency->id, 403);

        $workflowTemplate->load(['steps' => function ($query) {
            $query->orderBy('sort_order');
        }]);

        $stepNames = DB::table('master_workflow_step_names')
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->get();

        return view('agency.workflow-templates.edit', compact('workflowTemplate', 'stepNames'));
    }

    public function update(Request $request, WorkflowTemplate $workflowTemplate)
    {
        $agency = auth()->user()->agency;
        abort_if(! $agency || $workflowTemplate->agency_id !== $agency->id, 403);

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'is_default' => ['nullable', 'boolean'],
        ]);

        $workflowTemplate->update([
            ...$validated,
            'is_default' => $request->boolean('is_default'),
        ]);

        return redirect()
            ->route('agency.workflow-templates.index')
            ->with('success', 'Workflow template updated.');
    }

    public function destroy(WorkflowTemplate $workflowTemplate)
    {
        $agency = auth()->user()->agency;
        abort_if(! $agency || $workflowTemplate->agency_id !== $agency->id, 403);

        if ($workflowTemplate->jobPosts()->exists()) {
            return back()->with('error', 'Cannot delete template that is being used by job posts.');
        }

        $workflowTemplate->delete();

        return redirect()
            ->route('agency.workflow-templates.index')
            ->with('success', 'Workflow template deleted.');
    }
}
