<x-framework.layout.card>
    <div class="flex items-center justify-between border-b border-slate-200 pb-5">
        <div>
            <h2 class="text-xl font-bold text-slate-900">
                Basic Information
            </h2>

            <p class="mt-1 text-sm text-slate-500">
                Your name, birth date, gender, civil status, and nationality.
            </p>
        </div>

        <x-framework.buttons.primary
            type="button"
            size="sm"
            @click.prevent="$dispatch('open-modal', 'edit-basic-information')"
        >
            Edit
        </x-framework.buttons.primary>
    </div>

    <x-framework.feedback.modal
        name="edit-basic-information"
        title="Edit Basic Information"
        description="Update your personal details below."
    >
        <form action="{{ route('applicant.profile.update-basic-information') }}" method="POST" class="space-y-6">
            @csrf

            <div class="grid gap-6 sm:grid-cols-2">
                <x-framework.forms.input
                    label="First Name"
                    name="first_name"
                    :value="$profile?->first_name"
                    required
                />

                <x-framework.forms.input
                    label="Middle Name"
                    name="middle_name"
                    :value="$profile?->middle_name"
                />

                <x-framework.forms.input
                    label="Last Name"
                    name="last_name"
                    :value="$profile?->last_name"
                    required
                />

                <x-framework.forms.input
                    label="Suffix"
                    name="suffix"
                    :value="$profile?->suffix"
                />

                <x-framework.forms.input
                    label="Birth Date"
                    name="birth_date"
                    type="date"
                    :value="$profile?->birth_date?->format('Y-m-d')"
                    required
                />

                <x-framework.forms.select
                    label="Gender"
                    name="master_gender_id"
                    :options="$genders->pluck('name', 'id')"
                    :selected="$profile?->master_gender_id"
                    required
                />

                <x-framework.forms.select
                    label="Civil Status"
                    name="master_civil_status_id"
                    :options="$civilStatuses->pluck('name', 'id')"
                    :selected="$profile?->master_civil_status_id"
                    required
                />

                <x-framework.forms.input
                    label="Nationality"
                    name="nationality"
                    :value="$profile?->nationality"
                    required
                />
            </div>

            <div class="flex justify-end gap-3 mt-8">
                <x-framework.buttons.secondary
                    type="button"
                    @click="$dispatch('close-modal', 'edit-basic-information')"
                >
                    Cancel
                </x-framework.buttons.secondary>

                <x-framework.buttons.primary type="submit">
                    Save Changes
                </x-framework.buttons.primary>
            </div>
        </form>
    </x-framework.feedback.modal>

    <dl class="mt-6 grid gap-6 sm:grid-cols-2">
        <div>
            <dt class="text-sm font-medium text-slate-500">First Name</dt>
            <dd class="mt-1 text-slate-900">{{ $profile?->first_name ?? 'Not provided' }}</dd>
        </div>

        <div>
            <dt class="text-sm font-medium text-slate-500">Middle Name</dt>
            <dd class="mt-1 text-slate-900">{{ $profile?->middle_name ?? 'Not provided' }}</dd>
        </div>

        <div>
            <dt class="text-sm font-medium text-slate-500">Last Name</dt>
            <dd class="mt-1 text-slate-900">{{ $profile?->last_name ?? 'Not provided' }}</dd>
        </div>

        <div>
            <dt class="text-sm font-medium text-slate-500">Suffix</dt>
            <dd class="mt-1 text-slate-900">{{ $profile?->suffix ?? 'Not provided' }}</dd>
        </div>

        <div>
            <dt class="text-sm font-medium text-slate-500">Birth Date</dt>
            <dd class="mt-1 text-slate-900">
                {{ $profile?->birth_date?->format('F d, Y') ?? 'Not provided' }}
            </dd>
        </div>

        <div>
            <dt class="text-sm font-medium text-slate-500">Gender</dt>
            <dd class="mt-1 text-slate-900">
                {{ $profile?->gender?->name ?? 'Not provided' }}
            </dd>
        </div>

        <div>
            <dt class="text-sm font-medium text-slate-500">Civil Status</dt>
            <dd class="mt-1 text-slate-900">
                {{ $profile?->civilStatus?->name ?? 'Not provided' }}
            </dd>
        </div>

        <div>
            <dt class="text-sm font-medium text-slate-500">Nationality</dt>
            <dd class="mt-1 text-slate-900">
                {{ $profile?->nationality ?? 'Not provided' }}
            </dd>
        </div>
    </dl>
</x-framework.layout.card>
