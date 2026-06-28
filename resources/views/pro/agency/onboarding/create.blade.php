@extends('pro.layouts.app')

@section('title', 'Onboard Employee - SEKYU PRO')

@section('content')

    <x-framework.layout.page-header
        title="Onboard Employee"
        description="Create the employee profile and issue portal access in one step."
    >
        <x-slot:actions>
            <x-framework.buttons.secondary href="{{ route('pro.agency.onboarding.index') }}">
                Back
            </x-framework.buttons.secondary>
        </x-slot:actions>
    </x-framework.layout.page-header>

    <form
        method="POST"
        action="{{ route('pro.agency.onboarding.store') }}"
        x-data="{
            applicants: @js($applicantProfilePayload),
            probationDate: '{{ old('probation_end_date', now()->addMonth()->toDateString()) }}',
            updateProbationDate(event) {
                if (! event.target.value) {
                    this.probationDate = ''

                    return
                }

                const date = new Date(`${event.target.value}T00:00:00`)
                date.setMonth(date.getMonth() + 1)
                this.probationDate = date.toISOString().slice(0, 10)
            },
            selectApplicant(event) {
                const applicant = this.applicants[event.target.value]

                if (! applicant) {
                    return
                }

                this.$refs.firstName.value = applicant.first_name || ''
                this.$refs.middleName.value = applicant.middle_name || ''
                this.$refs.lastName.value = applicant.last_name || ''
                this.$refs.suffix.value = applicant.suffix || ''
            },
        }"
        class="space-y-8"
    >
        @csrf

        <x-framework.layout.section
            title="Accepted Applicant"
            description="Only applicants who applied to your agency and accepted a job offer are available."
        >
            <x-framework.layout.card>
                @if($applicantProfiles->isEmpty())
                    <x-framework.feedback.alert type="warning">
                        No accepted applicants are ready for onboarding yet.
                    </x-framework.feedback.alert>
                @else
                    <div>
                        <label
                            for="guard_profile_id"
                            class="mb-2 block text-sm font-semibold text-slate-700"
                        >
                            Applicant
                        </label>

                        <select
                            id="guard_profile_id"
                            name="guard_profile_id"
                            required
                            x-on:change="selectApplicant"
                            class="block w-full rounded-xl border border-slate-300 bg-white px-4 py-3 shadow-sm transition focus:border-amber-500 focus:outline-none focus:ring-2 focus:ring-amber-500/20"
                        >
                            <option value="">Select accepted applicant</option>

                            @foreach($applicantProfiles as $profile)
                                <option
                                    value="{{ $profile->id }}"
                                    @selected(old('guard_profile_id') == $profile->id)
                                >
                                    {{ $profile->full_name }}
                                </option>
                            @endforeach
                        </select>

                        @error('guard_profile_id')
                            <p class="mt-2 text-sm text-red-600">
                                {{ $message }}
                            </p>
                        @enderror
                    </div>
                @endif
            </x-framework.layout.card>
        </x-framework.layout.section>

        <x-framework.layout.section
            title="Employee Details"
            description="Basic identity and internal employee numbering."
        >
            <x-framework.layout.card>
                <div class="grid gap-5 md:grid-cols-2">
                    <x-framework.forms.input
                        label="Employee No."
                        name="employee_no"
                        value="{{ old('employee_no', $nextEmployeeNo) }}"
                    />

                    <x-framework.forms.input
                        label="Employee Code"
                        name="employee_code"
                        placeholder="Optional internal code"
                    />

                    <x-framework.forms.input
                        label="First Name"
                        name="first_name"
                        x-ref="firstName"
                        required
                    />

                    <x-framework.forms.input
                        label="Middle Name"
                        name="middle_name"
                        x-ref="middleName"
                    />

                    <x-framework.forms.input
                        label="Last Name"
                        name="last_name"
                        x-ref="lastName"
                        required
                    />

                    <x-framework.forms.input
                        label="Suffix"
                        name="suffix"
                        x-ref="suffix"
                    />

                    <x-framework.forms.input
                        label="Nickname"
                        name="nickname"
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
                        placeholder="Security Guard"
                        required
                    />

                    <x-framework.forms.input
                        label="Department"
                        name="department"
                        placeholder="Operations"
                        required
                    />

                    <x-framework.forms.select
                        label="Employment Type"
                        name="employment_type"
                        :options="[
                            'full_time' => 'Full-time',
                            'part_time' => 'Part-time',
                            'contractual' => 'Contractual',
                            'project_based' => 'Project-based',
                        ]"
                        selected="full_time"
                        required
                    />

                    <x-framework.forms.select
                        label="Employment Status"
                        name="employment_status"
                        :options="$employmentStatusOptions"
                        selected="{{ old('employment_status', \App\Enums\Pro\EmploymentStatus::Probationary->value) }}"
                        required
                    />

                    <x-framework.forms.input
                        label="Date Hired"
                        name="date_hired"
                        type="date"
                        value="{{ old('date_hired', now()->toDateString()) }}"
                        x-on:change="updateProbationDate"
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
            description="Optional starting site, shift and pay information."
        >
            <x-framework.layout.card>
                <div class="grid gap-5 md:grid-cols-2">
                    <x-framework.forms.input
                        label="Current Site"
                        name="current_site"
                        placeholder="Client or deployment site"
                    />

                    <x-framework.forms.input
                        label="Current Shift"
                        name="current_shift"
                        placeholder="Day shift, 8 AM - 5 PM"
                    />

                    <x-framework.forms.input
                        label="Basic Salary"
                        name="basic_salary"
                        type="number"
                        step="0.01"
                        min="0"
                    />

                    <x-framework.forms.select
                        label="Salary Type"
                        name="salary_type_id"
                        :options="$salaryTypes"
                        placeholder="Select salary type"
                    />
                </div>
            </x-framework.layout.card>
        </x-framework.layout.section>

        <x-framework.layout.section
            title="Portal Access"
            description="Give the employee a PRO login for their employee dashboard."
        >
            <x-framework.layout.card>
                <div
                    x-data="{ createAccount: {{ old('create_account', '1') ? 'true' : 'false' }} }"
                    class="space-y-5"
                >
                    <input
                        type="hidden"
                        name="create_account"
                        value="0"
                    >

                    <label class="flex items-center justify-between gap-4 rounded-2xl border border-slate-200 p-4">
                        <span>
                            <span class="block text-sm font-semibold text-slate-900">Create employee portal account</span>
                            <span class="mt-1 block text-sm text-slate-500">The employee will be asked to change the temporary password.</span>
                        </span>

                        <input
                            type="checkbox"
                            name="create_account"
                            value="1"
                            class="h-5 w-5 rounded border-slate-300 text-amber-500 focus:ring-amber-500"
                            x-model="createAccount"
                        >
                    </label>

                    <div
                        x-show="createAccount"
                        x-cloak
                        class="grid gap-5 md:grid-cols-2"
                    >
                        <x-framework.forms.input
                            label="Username"
                            name="username"
                            value="{{ old('username', $nextEmployeeUsername) }}"
                            placeholder="{{ $nextEmployeeUsername }}"
                        />

                        <x-framework.forms.input
                            label="Temporary 6-digit PIN"
                            name="temporary_password"
                            type="text"
                            inputmode="numeric"
                            maxlength="6"
                            pattern="[0-9]{6}"
                            placeholder="Leave blank to generate"
                        />
                    </div>
                </div>
            </x-framework.layout.card>
        </x-framework.layout.section>

        <div class="flex flex-col-reverse gap-3 sm:flex-row sm:justify-end">
            <x-framework.buttons.secondary href="{{ route('pro.agency.onboarding.index') }}">
                Cancel
            </x-framework.buttons.secondary>

            <x-framework.buttons.primary
                :disabled="$applicantProfiles->isEmpty()"
            >
                Complete Onboarding
            </x-framework.buttons.primary>
        </div>
    </form>

@endsection
