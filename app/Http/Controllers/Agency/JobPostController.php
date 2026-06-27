<?php

namespace App\Http\Controllers\Agency;

use App\Http\Controllers\Controller;
use App\Models\MasterEmploymentType;
use App\Models\MasterJobStatus;
use App\Models\MasterSalaryType;
use App\Models\MasterWorkLocationType;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class JobPostController extends Controller
{
    public function index()
    {
        $agency = auth()->user()->agency;

        abort_if(! $agency, 403);

        $jobPosts = $agency
            ->jobPosts()
            ->latest()
            ->paginate(10);

        return view('agency.job-posts.index', compact('jobPosts'));
    }

    public function create()
    {
        $agency = auth()->user()->agency;
        abort_if(! $agency, 403);

        return view('agency.job-posts.create', [
            'workflowTemplates' => $agency->workflowTemplates()
                ->orderBy('name')
                ->pluck('name', 'id'),

            'employmentTypes' => MasterEmploymentType::where('is_active', true)
                ->orderBy('sort_order')
                ->pluck('name', 'id'),

            'workLocationTypes' => MasterWorkLocationType::where('is_active', true)
                ->orderBy('sort_order')
                ->pluck('name', 'id'),

            'salaryTypes' => MasterSalaryType::where('is_active', true)
                ->orderBy('sort_order')
                ->pluck('name', 'id'),

            'jobStatuses' => MasterJobStatus::where('is_active', true)
                ->orderBy('sort_order')
                ->pluck('name', 'id'),
        ]);
    }

    public function store(Request $request)
    {
        $agency = auth()->user()->agency;

        abort_if(! $agency, 403);

        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'workflow_template_id' => ['required', 'exists:workflow_templates,id'],
            'employment_type_id' => ['required', 'exists:master_employment_types,id'],
            'work_location_type_id' => ['required', 'exists:master_work_location_types,id'],
            'city' => ['nullable', 'string', 'max:255'],
            'province' => ['nullable', 'string', 'max:255'],
            'salary_min' => ['nullable', 'numeric', 'min:0'],
            'salary_max' => ['nullable', 'numeric', 'min:0'],
            'salary_type_id' => ['nullable', 'exists:master_salary_types,id'],
            'description' => ['required', 'string'],
            'requirements' => ['nullable', 'string'],
            'benefits' => ['nullable', 'string'],
            'vacancies' => ['required', 'integer', 'min:1'],
            'is_featured' => ['nullable', 'boolean'],
            'job_status_id' => ['required', 'exists:master_job_statuses,id'],
            'expires_at' => ['nullable', 'date'],
        ]);

        $status = MasterJobStatus::find($validated['job_status_id']);

        $agency->jobPosts()->create([
            ...$validated,
            'is_featured' => $request->boolean('is_featured'),
            'slug' => Str::slug($validated['title']) . '-' . Str::lower(Str::random(6)),
            'country' => 'Philippines',
            'published_at' => $status && in_array($status->code, ['active', 'published']) ? now() : null,
        ]);

        return redirect()
            ->route('agency.job-posts.index')
            ->with('success', 'Job post created successfully.');
    }

    public function edit(\App\Models\JobPost $jobPost)
    {
        $agency = auth()->user()->agency;

        abort_if(! $agency || $jobPost->agency_id !== $agency->id, 403);

        return view('agency.job-posts.edit', [
            'jobPost' => $jobPost,
            'workflowTemplates' => $agency->workflowTemplates()
                ->orderBy('name')
                ->pluck('name', 'id'),

            'employmentTypes' => MasterEmploymentType::where('is_active', true)
                ->orderBy('sort_order')
                ->pluck('name', 'id'),

            'workLocationTypes' => MasterWorkLocationType::where('is_active', true)
                ->orderBy('sort_order')
                ->pluck('name', 'id'),

            'salaryTypes' => MasterSalaryType::where('is_active', true)
                ->orderBy('sort_order')
                ->pluck('name', 'id'),

            'jobStatuses' => MasterJobStatus::where('is_active', true)
                ->orderBy('sort_order')
                ->pluck('name', 'id'),
        ]);
    }

    public function update(Request $request, \App\Models\JobPost $jobPost)
    {
        $agency = auth()->user()->agency;

        abort_if(! $agency || $jobPost->agency_id !== $agency->id, 403);

        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'workflow_template_id' => ['required', 'exists:workflow_templates,id'],
            'employment_type_id' => ['required', 'exists:master_employment_types,id'],
            'work_location_type_id' => ['required', 'exists:master_work_location_types,id'],
            'city' => ['nullable', 'string', 'max:255'],
            'province' => ['nullable', 'string', 'max:255'],
            'salary_min' => ['nullable', 'numeric', 'min:0'],
            'salary_max' => ['nullable', 'numeric', 'min:0'],
            'salary_type_id' => ['nullable', 'exists:master_salary_types,id'],
            'description' => ['required', 'string'],
            'requirements' => ['nullable', 'string'],
            'benefits' => ['nullable', 'string'],
            'vacancies' => ['required', 'integer', 'min:1'],
            'is_featured' => ['nullable', 'boolean'],
            'job_status_id' => ['required', 'exists:master_job_statuses,id'],
            'expires_at' => ['nullable', 'date'],
        ]);

        $status = MasterJobStatus::find($validated['job_status_id']);

        if ($jobPost->title !== $validated['title']) {
            $validated['slug'] = Str::slug($validated['title']) . '-' . Str::lower(Str::random(6));
        }

        $jobPost->update([
            ...$validated,
            'is_featured' => $request->boolean('is_featured'),
            'published_at' => $status && in_array($status->code, ['active', 'published']) ? ($jobPost->published_at ?? now()) : null,
        ]);

        return redirect()
            ->route('agency.job-posts.index')
            ->with('success', 'Job post updated successfully.');
    }

    public function destroy(\App\Models\JobPost $jobPost)
    {
        $agency = auth()->user()->agency;

        abort_if(! $agency || $jobPost->agency_id !== $agency->id, 403);

        $jobPost->delete();

        return redirect()
            ->route('agency.job-posts.index')
            ->with('success', 'Job post deleted successfully.');
    }
}
