@extends('layouts.app')

@section('content')
    <section class="bg-slate-50 py-10">
        <div class="mx-auto max-w-6xl px-6">

            <x-framework.layout.page-header
                title="Create Job Post"
                description="Create a new job opportunity and start receiving guard applications."
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
                action="{{ route('agency.job-posts.store') }}"
                class="space-y-8"
            >
                @csrf

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
                                required
                            />

                            <x-framework.forms.select
                                name="employment_type_id"
                                label="Employment Type"
                                :options="$employmentTypes"
                                placeholder="Select employment type"
                            />

                            <x-framework.forms.select
                                name="workflow_template_id"
                                label="Workflow Template"
                                :options="$workflowTemplates"
                                placeholder="Select workflow template"
                                required
                            />

                            <x-framework.forms.input
                                name="vacancies"
                                type="number"
                                label="Vacancies"
                                value="1"
                            />

                            <x-framework.forms.input
                                name="min_profile_completion"
                                type="number"
                                label="Min. Profile Completion (%)"
                                value="0"
                                min="0"
                                max="100"
                                required
                            />

                            <div class="flex items-center pt-8">
                                <label class="flex cursor-pointer items-center gap-3">
                                    <input
                                        type="checkbox"
                                        name="is_featured"
                                        value="1"
                                        @checked(old('is_featured'))
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
                                placeholder="Select work location"
                            />

                            <x-framework.forms.input
                                name="city"
                                label="City"
                            />

                            <x-framework.forms.input
                                name="province"
                                label="Province"
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
                                placeholder="Select salary type"
                            />

                            <x-framework.forms.input
                                name="salary_min"
                                type="number"
                                label="Minimum Salary"
                            />

                            <x-framework.forms.input
                                name="salary_max"
                                type="number"
                                label="Maximum Salary"
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
                            />

                            <x-framework.forms.input
                                name="expires_at"
                                type="date"
                                label="Expiration Date"
                            />

                        </x-framework.layout.grid>
                    </x-framework.layout.section>
                </x-framework.layout.card>

                <div class="flex justify-end gap-3">

                    <x-framework.buttons.secondary
                        :href="route('agency.job-posts.index')"
                    >
                        Cancel
                    </x-framework.buttons.secondary>

                    <x-framework.buttons.primary>
                        Save Job Post
                    </x-framework.buttons.primary>

                </div>

            </form>

        </div>
    </section>
@endsection
