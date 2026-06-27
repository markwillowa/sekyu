<x-framework.layout.card>
    <div class="flex items-center justify-between border-b border-slate-200 pb-5">
        <div>
            <h2 class="text-xl font-bold text-slate-900">
                Contact Details
            </h2>

            <p class="mt-1 text-sm text-slate-500">
                Your phone numbers, email, and address information.
            </p>
        </div>

        <x-framework.buttons.primary
            type="button"
            size="sm"
            @click.prevent="$dispatch('open-modal', 'edit-contact-details')"
        >
            Edit
        </x-framework.buttons.primary>
    </div>

    <x-framework.feedback.modal
        name="edit-contact-details"
        title="Edit Contact Details"
        description="Update your contact and address information below."
    >
        <form action="{{ route('applicant.profile.update-contact-details') }}" method="POST" class="space-y-6">
            @csrf

            <div class="grid gap-6 sm:grid-cols-2">
                <x-framework.forms.input
                    label="Mobile Number"
                    name="mobile_number"
                    :value="$contactDetails?->mobile_number"
                    required
                />

                <x-framework.forms.input
                    label="Alternate Number"
                    name="alternate_mobile_number"
                    :value="$contactDetails?->alternate_mobile_number"
                />

                <div class="sm:col-span-2">
                    <x-framework.forms.textarea
                        label="Current Address"
                        name="current_address"
                        required
                    >{{ $contactDetails?->current_address }}</x-framework.forms.textarea>
                </div>

                <div class="sm:col-span-2">
                    <x-framework.forms.textarea
                        label="Permanent Address"
                        name="permanent_address"
                    >{{ $contactDetails?->permanent_address }}</x-framework.forms.textarea>
                </div>
            </div>

            <div class="flex justify-end gap-3 mt-8">
                <x-framework.buttons.secondary
                    type="button"
                    @click="$dispatch('close-modal', 'edit-contact-details')"
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
            <dt class="text-sm font-medium text-slate-500">Mobile Number</dt>
            <dd class="mt-1 text-slate-900">{{ $contactDetails?->mobile_number ?? 'Not provided' }}</dd>
        </div>

        <div>
            <dt class="text-sm font-medium text-slate-500">Alternate Number</dt>
            <dd class="mt-1 text-slate-900">{{ $contactDetails?->alternate_number ?? 'Not provided' }}</dd>
        </div>

        <div>
            <dt class="text-sm font-medium text-slate-500">Email Address</dt>
            <dd class="mt-1 text-slate-900">{{ $user->email }}</dd>
        </div>

        <div class="sm:col-span-2">
            <dt class="text-sm font-medium text-slate-500">Current Address</dt>
            <dd class="mt-1 text-slate-900">{{ $contactDetails?->current_address ?? 'Not provided' }}</dd>
        </div>

        <div class="sm:col-span-2">
            <dt class="text-sm font-medium text-slate-500">Permanent Address</dt>
            <dd class="mt-1 text-slate-900">{{ $contactDetails?->permanent_address ?? 'Not provided' }}</dd>
        </div>
    </dl>
</x-framework.layout.card>
