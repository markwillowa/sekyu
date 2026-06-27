<x-framework.layout.card>
    <div class="flex items-center justify-between border-b border-slate-200 pb-5">
        <div>
            <h2 class="text-xl font-bold text-slate-900">
                Physical Details
            </h2>

            <p class="mt-1 text-sm text-slate-500">
                Physical information often required by security agencies.
            </p>
        </div>

        <x-framework.buttons.primary
            type="button"
            size="sm"
            @click.prevent="$dispatch('open-modal', 'edit-physical-details')"
        >
            Edit
        </x-framework.buttons.primary>
    </div>

    <x-framework.feedback.modal
        name="edit-physical-details"
        title="Edit Physical Details"
        description="Update your physical information below."
    >
        <form action="{{ route('applicant.profile.update-physical-details') }}" method="POST" class="space-y-6">
            @csrf

            <div class="grid gap-6 sm:grid-cols-2">
                <x-framework.forms.input
                    label="Height (cm)"
                    name="height_cm"
                    type="number"
                    step="0.01"
                    :value="$physicalDetail?->height_cm"
                    required
                />

                <x-framework.forms.input
                    label="Weight (kg)"
                    name="weight_kg"
                    type="number"
                    step="0.01"
                    :value="$physicalDetail?->weight_kg"
                    required
                />

                <x-framework.forms.input
                    label="Blood Type"
                    name="blood_type"
                    :value="$physicalDetail?->blood_type"
                />

                <x-framework.forms.input
                    label="Body Type"
                    name="body_type"
                    :value="$physicalDetail?->body_type"
                />
            </div>

            <div class="flex justify-end gap-3 mt-8">
                <x-framework.buttons.secondary
                    type="button"
                    @click="$dispatch('close-modal', 'edit-physical-details')"
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
            <dt class="text-sm font-medium text-slate-500">Height</dt>
            <dd class="mt-1 text-slate-900">{{ $physicalDetail?->height ?? 'Not provided' }}</dd>
        </div>

        <div>
            <dt class="text-sm font-medium text-slate-500">Weight</dt>
            <dd class="mt-1 text-slate-900">{{ $physicalDetail?->weight ?? 'Not provided' }}</dd>
        </div>

        <div>
            <dt class="text-sm font-medium text-slate-500">Blood Type</dt>
            <dd class="mt-1 text-slate-900">{{ $physicalDetail?->blood_type ?? 'Not provided' }}</dd>
        </div>

        <div>
            <dt class="text-sm font-medium text-slate-500">Distinguishing Marks</dt>
            <dd class="mt-1 text-slate-900">{{ $physicalDetail?->distinguishing_marks ?? 'Not provided' }}</dd>
        </div>
    </dl>
</x-framework.layout.card>
