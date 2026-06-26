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
                required
            />

            <x-framework.forms.input
                type="number"
                name="vacancies"
                label="Vacancies"
                value="1"
                required
            />

        </x-framework.layout.grid>

    </x-framework.layout.section>

</x-framework.layout.card>

{{-- Work Assignment --}}
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
        title="Salary"
        description="Compensation offered for this position."
    >

        <x-framework.layout.grid :cols="3">

            <x-framework.forms.select
                name="salary_type_id"
                label="Salary Type"
                :options="$salaryTypes"
                placeholder="Select salary type"
            />

            <x-framework.forms.input
                type="number"
                name="salary_min"
                label="Minimum Salary"
            />

            <x-framework.forms.input
                type="number"
                name="salary_max"
                label="Maximum Salary"
            />

        </x-framework.layout.grid>

    </x-framework.layout.section>

</x-framework.layout.card>

{{-- Description --}}
<x-framework.layout.card>

    <x-framework.layout.section
        title="Job Description"
        description="Describe the responsibilities and expectations."
    >

        <x-framework.forms.textarea
            name="description"
            label="Description"
            rows="8"
        />

    </x-framework.layout.section>

</x-framework.layout.card>

{{-- Requirements --}}
<x-framework.layout.card>

    <x-framework.layout.section
        title="Requirements"
        description="Qualifications needed for this position."
    >

        <x-framework.forms.textarea
            name="requirements"
            label="Requirements"
            rows="8"
        />

    </x-framework.layout.section>

</x-framework.layout.card>

{{-- Benefits --}}
<x-framework.layout.card>

    <x-framework.layout.section
        title="Benefits"
        description="Additional benefits offered."
    >

        <x-framework.forms.textarea
            name="benefits"
            label="Benefits"
            rows="8"
        />

    </x-framework.layout.section>

</x-framework.layout.card>

{{-- Publishing --}}
<x-framework.layout.card>

    <x-framework.layout.section
        title="Publishing"
        description="Control the visibility of this job post."
    >

        <x-framework.layout.grid :cols="2">

            <x-framework.forms.select
                name="job_status_id"
                label="Status"
                :options="$jobStatuses"
            />

            <x-framework.forms.input
                type="date"
                name="expires_at"
                label="Expiration Date"
            />

        </x-framework.layout.grid>

    </x-framework.layout.section>

</x-framework.layout.card>
