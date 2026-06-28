@extends('pro.layouts.app')

@section('title', 'Edit Employee - SEKYU PRO')

@section('content')

    <x-framework.layout.page-header
        title="Edit Employee"
        description="Update employee profile, employment, assignment and payroll details."
    >
        <x-slot:actions>
            <x-framework.buttons.secondary href="{{ route('pro.agency.onboarding.index') }}">
                Back
            </x-framework.buttons.secondary>
        </x-slot:actions>
    </x-framework.layout.page-header>

    <form
        method="POST"
        action="{{ route('pro.agency.onboarding.update', $employee) }}"
        x-data="{
            probationDate: '{{ old('probation_end_date', optional($employee->probation_end_date)->toDateString() ?? optional($employee->date_hired)->copy()?->addMonth()->toDateString()) }}',
            updateProbationDate(event) {
                if (! event.target.value) {
                    this.probationDate = ''

                    return
                }

                const date = new Date(`${event.target.value}T00:00:00`)
                date.setMonth(date.getMonth() + 1)
                this.probationDate = date.toISOString().slice(0, 10)
            },
        }"
        class="space-y-8"
    >
        @csrf
        @method('PUT')

        <x-framework.layout.section
            title="Applicant Relationship"
            description="The applicant link is retained for employment history and offer traceability."
        >
            <x-framework.layout.card>
                <div class="grid gap-5 md:grid-cols-2">
                    <div>
                        <div class="text-sm font-semibold text-slate-700">Applicant</div>
                        <div class="mt-2 rounded-xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm text-slate-700">
                            {{ $employee->guardProfile?->full_name ?? 'No applicant linked' }}
                        </div>
                    </div>

                    <div>
                        <div class="text-sm font-semibold text-slate-700">Portal Access</div>
                        <div class="mt-2 rounded-xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm text-slate-700">
                            @if($employee->account)
                                {{ $employee->account->username }}
                            @else
                                No portal account
                            @endif
                        </div>
                    </div>
                </div>
            </x-framework.layout.card>
        </x-framework.layout.section>

        <x-framework.layout.section
            title="Employee Details"
            description="Basic identity and internal employee numbering."
        >
            <x-framework.layout.card>
                <div class="grid gap-5 md:grid-cols-2">
                    <input
                        type="hidden"
                        name="employee_no"
                        value="{{ $employee->employee_no }}"
                    >

                    <x-framework.forms.input
                        label="Employee No."
                        name="employee_no"
                        value="{{ $employee->employee_no }}"
                        disabled
                        class="cursor-not-allowed bg-slate-100 text-slate-500"
                        required
                    />

                    <x-framework.forms.input
                        label="Employee Code"
                        name="employee_code"
                        value="{{ $employee->employee_code }}"
                        placeholder="Optional internal code"
                    />

                    <input
                        type="hidden"
                        name="first_name"
                        value="{{ $employee->first_name }}"
                    >

                    <x-framework.forms.input
                        label="First Name"
                        name="first_name"
                        value="{{ $employee->first_name }}"
                        disabled
                        class="cursor-not-allowed bg-slate-100 text-slate-500"
                        required
                    />

                    <input
                        type="hidden"
                        name="middle_name"
                        value="{{ $employee->middle_name }}"
                    >

                    <x-framework.forms.input
                        label="Middle Name"
                        name="middle_name"
                        value="{{ $employee->middle_name }}"
                        disabled
                        class="cursor-not-allowed bg-slate-100 text-slate-500"
                    />

                    <input
                        type="hidden"
                        name="last_name"
                        value="{{ $employee->last_name }}"
                    >

                    <x-framework.forms.input
                        label="Last Name"
                        name="last_name"
                        value="{{ $employee->last_name }}"
                        disabled
                        class="cursor-not-allowed bg-slate-100 text-slate-500"
                        required
                    />

                    <input
                        type="hidden"
                        name="suffix"
                        value="{{ $employee->suffix }}"
                    >

                    <x-framework.forms.input
                        label="Suffix"
                        name="suffix"
                        value="{{ $employee->suffix }}"
                        disabled
                        class="cursor-not-allowed bg-slate-100 text-slate-500"
                    />

                    <x-framework.forms.input
                        label="Nickname"
                        name="nickname"
                        value="{{ $employee->nickname }}"
                    />
                </div>
            </x-framework.layout.card>
        </x-framework.layout.section>

        <x-framework.layout.section
            title="Employment"
            description="Role, department and hiring status."
        >
            <x-framework.layout.card>
                <div class="grid gap-5 md:grid-cols-2">
                    <x-framework.forms.input
                        label="Position"
                        name="position"
                        value="{{ $employee->position }}"
                        placeholder="Security Guard"
                        required
                    />

                    <x-framework.forms.input
                        label="Department"
                        name="department"
                        value="{{ $employee->department }}"
                        placeholder="Operations"
                        required
                    />

                    <x-framework.forms.select
                        label="Employment Type"
                        name="employment_type"
                        :options="$employmentTypeOptions"
                        selected="{{ $employee->employment_type }}"
                        required
                    />

                    <x-framework.forms.select
                        label="Employment Status"
                        name="employment_status"
                        :options="$employmentStatusOptions"
                        selected="{{ $employee->employment_status }}"
                        required
                    />

                    <input
                        type="hidden"
                        name="date_hired"
                        value="{{ optional($employee->date_hired)->toDateString() }}"
                    >

                    <x-framework.forms.input
                        label="Date Hired"
                        name="date_hired"
                        type="date"
                        value="{{ optional($employee->date_hired)->toDateString() }}"
                        disabled
                        class="cursor-not-allowed bg-slate-100 text-slate-500"
                        required
                    />

                    <x-framework.forms.input
                        label="Probation End Date"
                        name="probation_end_date"
                        type="date"
                        x-bind:value="probationDate"
                        readonly
                    />
                </div>
            </x-framework.layout.card>
        </x-framework.layout.section>

        <x-framework.layout.section
            title="Assignment and Payroll"
            description="Current site, shift and pay information."
        >
            <x-framework.layout.card>
                <div class="grid gap-5 md:grid-cols-2">
                    <x-framework.forms.input
                        label="Current Site"
                        name="current_site"
                        value="{{ $employee->current_site }}"
                        placeholder="Client or deployment site"
                    />

                    <x-framework.forms.input
                        label="Current Shift"
                        name="current_shift"
                        value="{{ $employee->current_shift }}"
                        placeholder="Day shift, 8 AM - 5 PM"
                    />

                    <x-framework.forms.input
                        label="Basic Salary"
                        name="basic_salary"
                        type="number"
                        step="0.01"
                        min="0"
                        value="{{ $employee->basic_salary }}"
                    />

                    <x-framework.forms.select
                        label="Salary Type"
                        name="salary_type_id"
                        :options="$salaryTypes"
                        :selected="$selectedSalaryTypeId"
                        placeholder="Select salary type"
                    />
                </div>
            </x-framework.layout.card>
        </x-framework.layout.section>

        <x-framework.layout.section
            title="Record Status"
            description="Inactive employees stay in records but can be separated from active operations."
        >
            <x-framework.layout.card>
                <input
                    type="hidden"
                    name="is_active"
                    value="0"
                >

                <label class="flex items-center justify-between gap-4 rounded-2xl border border-slate-200 p-4">
                    <span>
                        <span class="block text-sm font-semibold text-slate-900">Active employee record</span>
                        <span class="mt-1 block text-sm text-slate-500">Keep this employee available in active workforce views.</span>
                    </span>

                    <input
                        type="checkbox"
                        name="is_active"
                        value="1"
                        class="h-5 w-5 rounded border-slate-300 text-amber-500 focus:ring-amber-500"
                        @checked(old('is_active', $employee->is_active))
                    >
                </label>
            </x-framework.layout.card>
        </x-framework.layout.section>

        <div class="flex flex-col-reverse gap-3 sm:flex-row sm:justify-end">
            <x-framework.buttons.secondary href="{{ route('pro.agency.onboarding.index') }}">
                Cancel
            </x-framework.buttons.secondary>

            <x-framework.buttons.primary>
                Save Changes
            </x-framework.buttons.primary>
        </div>
    </form>

@endsection
