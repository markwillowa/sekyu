@extends('layouts.app')

@section('content')
    <section class="bg-slate-50 py-10">
        <div class="mx-auto max-w-6xl px-6">

            <x-framework.layout.page-header
                title="Edit Job Post"
                description="Update the details of your job opportunity."
            >
                <x-slot:actions>
                    <x-framework.buttons.secondary
                        :href="route('agency.job-posts.index')"
                    >
                        Back to Job Posts
                    </x-framework.buttons.secondary>
                </x-slot:actions>
            </x-framework.layout.page-header>

            <form
                method="POST"
                action="{{ route('agency.job-posts.update', $jobPost) }}"
                class="space-y-8"
            >
                @csrf
                @method('PUT')

                {{-- Job Details --}}
                <x-framework.layout.card>
                    <x-framework.layout.section
                        title="Job Details"
                        description="Basic information about the position."
                    >
                        <x-framework.layout.grid :cols="3">

                            <x-framework.forms.input
                                name="title"
                                label="Job Title"
                                placeholder="Security Guard"
                                :value="$jobPost->title"
                                required
                            />

                            <x-framework.forms.select
                                name="employment_type_id"
                                label="Employment Type"
                                :options="$employmentTypes"
                                :selected="$jobPost->employment_type_id"
                                placeholder="Select employment type"
                            />

                            <x-framework.forms.select
                                name="workflow_template_id"
                                label="Workflow Template"
                                :options="$workflowTemplates"
                                :selected="$jobPost->workflow_template_id"
                                placeholder="Select workflow template"
                                required
                            />

                            <x-framework.forms.input
                                name="vacancies"
                                type="number"
                                label="Vacancies"
                                :value="$jobPost->vacancies"
                            />

                            <div class="flex items-center pt-8">
                                <label class="flex cursor-pointer items-center gap-3">
                                    <input
                                        type="checkbox"
                                        name="is_featured"
                                        value="1"
                                        @checked(old('is_featured', $jobPost->is_featured))
                                        class="h-5 w-5 rounded border-slate-300 text-amber-600 focus:ring-amber-500"
                                    >
                                    <span class="text-sm font-semibold text-slate-700">Featured Job Post</span>
                                </label>
                            </div>

                        </x-framework.layout.grid>
                    </x-framework.layout.section>
                </x-framework.layout.card>

                {{-- Assignment --}}
                <x-framework.layout.card>
                    <x-framework.layout.section
                        title="Work Assignment"
                        description="Where the assigned guard will report."
                    >
                        <x-framework.layout.grid :cols="3">

                            <x-framework.forms.select
                                name="work_location_type_id"
                                label="Work Location"
                                :options="$workLocationTypes"
                                :selected="$jobPost->work_location_type_id"
                                placeholder="Select work location"
                            />

                            <x-framework.forms.input
                                name="city"
                                label="City"
                                :value="$jobPost->city"
                            />

                            <x-framework.forms.input
                                name="province"
                                label="Province"
                                :value="$jobPost->province"
                            />

                        </x-framework.layout.grid>
                    </x-framework.layout.section>
                </x-framework.layout.card>

                {{-- Salary --}}
                <x-framework.layout.card>
                    <x-framework.layout.section
                        title="Compensation"
                        description="Salary information."
                    >
                        <x-framework.layout.grid :cols="3">

                            <x-framework.forms.select
                                name="salary_type_id"
                                label="Salary Type"
                                :options="$salaryTypes"
                                :selected="$jobPost->salary_type_id"
                                placeholder="Select salary type"
                            />

                            <x-framework.forms.input
                                name="salary_min"
                                type="number"
                                label="Minimum Salary"
                                :value="$jobPost->salary_min"
                            />

                            <x-framework.forms.input
                                name="salary_max"
                                type="number"
                                label="Maximum Salary"
                                :value="$jobPost->salary_max"
                            />

                        </x-framework.layout.grid>
                    </x-framework.layout.section>
                </x-framework.layout.card>

                {{-- Description --}}
                <x-framework.layout.card>
                    <x-framework.layout.section
                        title="Job Description"
                        description="Describe the duties and responsibilities."
                    >

                        <x-framework.editor.rich-text
                            name="description"
                            label="Job Description"
                            :value="$jobPost->description"
                        />

                    </x-framework.layout.section>
                </x-framework.layout.card>

                {{-- Requirements --}}
                <x-framework.layout.card>
                    <x-framework.layout.section
                        title="Requirements"
                        description="List the qualifications required."
                    >

                        <x-framework.editor.rich-text
                            name="requirements"
                            label="Requirements"
                            :value="$jobPost->requirements"
                        />

                    </x-framework.layout.section>
                </x-framework.layout.card>

                {{-- Benefits --}}
                <x-framework.layout.card>
                    <x-framework.layout.section
                        title="Benefits"
                        description="Tell applicants what they'll receive."
                    >

                        <x-framework.editor.rich-text
                            name="benefits"
                            label="Benefits"
                            :value="$jobPost->benefits"
                        />

                    </x-framework.layout.section>
                </x-framework.layout.card>

                {{-- Publishing --}}
                <x-framework.layout.card>
                    <x-framework.layout.section
                        title="Publishing"
                        description="Control when this job becomes visible."
                    >
                        <x-framework.layout.grid :cols="2">

                            <x-framework.forms.select
                                name="job_status_id"
                                label="Status"
                                :options="$jobStatuses"
                                :selected="$jobPost->job_status_id"
                            />

                            <x-framework.forms.input
                                name="expires_at"
                                type="date"
                                label="Expiration Date"
                                :value="$jobPost->expires_at?->format('Y-m-d')"
                            />

                        </x-framework.layout.grid>
                    </x-framework.layout.section>
                </x-framework.layout.card>

                <div class="flex justify-between items-center">
                    <div class="flex items-center gap-3">
                        <x-framework.buttons.primary>
                            Update Job Post
                        </x-framework.buttons.primary>

                        <x-framework.buttons.secondary
                            :href="route('agency.job-posts.index')"
                        >
                            Cancel
                        </x-framework.buttons.secondary>
                    </div>
                </div>

            </form>

            {{-- Danger Zone --}}
            <div class="mt-12">
                <x-framework.layout.card class="border-red-100 bg-red-50/30">
                    <x-framework.layout.section
                        title="Danger Zone"
                        description="Irreversible actions for this job post."
                    >
                        <div class="flex items-center justify-between">
                            <div>
                                <h4 class="text-sm font-semibold text-slate-900">Delete Job Post</h4>
                                <p class="text-sm text-slate-500">Once you delete a job post, there is no going back. Please be certain.</p>
                            </div>

                            <form
                                action="{{ route('agency.job-posts.destroy', $jobPost) }}"
                                method="POST"
                                onsubmit="return confirm('Are you sure you want to delete this job post?')"
                            >
                                @csrf
                                @method('DELETE')
                                <x-framework.buttons.danger type="submit">
                                    Delete Job Post
                                </x-framework.buttons.danger>
                            </form>
                        </div>
                    </x-framework.layout.section>
                </x-framework.layout.card>
            </div>

        </div>
    </section>
@endsection
