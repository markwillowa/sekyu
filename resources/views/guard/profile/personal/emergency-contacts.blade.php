<x-framework.layout.card>
    <div class="flex items-center justify-between border-b border-slate-200 pb-5">
        <div>
            <h2 class="text-xl font-bold text-slate-900">
                Emergency Contacts
            </h2>

            <p class="mt-1 text-sm text-slate-500">
                People agencies may contact in case of emergency.
            </p>
        </div>

        <x-framework.buttons.primary
            type="button"
            size="sm"
            @click.prevent="$dispatch('open-modal', 'add-emergency-contact')"
        >
            Add Contact
        </x-framework.buttons.primary>
    </div>

    <x-framework.feedback.modal
        name="add-emergency-contact"
        title="Add Emergency Contact"
        description="Add a person to contact in case of emergency."
    >
        <form action="{{ route('applicant.profile.store-emergency-contact') }}" method="POST" class="space-y-6">
            @csrf

            <div class="grid gap-6 sm:grid-cols-2">
                <div class="sm:col-span-2">
                    <x-framework.forms.input
                        label="Full Name"
                        name="name"
                        required
                    />
                </div>

                <x-framework.forms.select
                    label="Relationship"
                    name="master_relationship_id"
                    :options="$relationships->pluck('name', 'id')"
                    required
                />

                <x-framework.forms.input
                    label="Mobile Number"
                    name="mobile_number"
                    required
                />

                <x-framework.forms.input
                    label="Alternate Number"
                    name="alternate_mobile_number"
                />

                <x-framework.forms.input
                    label="Email Address"
                    name="email"
                    type="email"
                />

                <div class="sm:col-span-2">
                    <x-framework.forms.textarea
                        label="Address"
                        name="address"
                        rows="3"
                    ></x-framework.forms.textarea>
                </div>
            </div>

            <div class="flex justify-end gap-3 mt-8">
                <x-framework.buttons.secondary
                    type="button"
                    @click="$dispatch('close-modal', 'add-emergency-contact')"
                >
                    Cancel
                </x-framework.buttons.secondary>

                <x-framework.buttons.primary type="submit">
                    Add Contact
                </x-framework.buttons.primary>
            </div>
        </form>
    </x-framework.feedback.modal>

    @if ($emergencyContacts->isEmpty())
        <div class="mt-6 rounded-2xl border border-dashed border-slate-300 bg-slate-50 p-8 text-center">
            <h3 class="text-lg font-bold text-slate-900">
                No emergency contacts added yet
            </h3>

            <p class="mt-2 text-sm text-slate-500">
                Add at least one emergency contact to complete this section.
            </p>
        </div>
    @else
        <div class="mt-6 space-y-4">
            @foreach ($emergencyContacts as $contact)
                <div class="rounded-2xl border border-slate-200 p-5">
                    <div class="flex items-start justify-between gap-4">
                        <div>
                            <h3 class="font-bold text-slate-900">
                                {{ $contact->name }}
                            </h3>

                            <p class="mt-1 text-sm text-slate-500">
                                {{ $contact->relationship?->name ?? 'Relationship not provided' }}
                            </p>

                            <p class="mt-2 text-sm text-slate-700">
                                {{ $contact->mobile_number ?? 'No mobile number' }}
                            </p>

                            @if ($contact->address)
                                <p class="mt-2 text-sm text-slate-500">
                                    {{ $contact->address }}
                                </p>
                            @endif
                        </div>

                        <div class="flex gap-2">
                            <x-framework.buttons.secondary
                                type="button"
                                size="sm"
                                @click.prevent="$dispatch('open-modal', 'edit-emergency-contact-{{ $contact->id }}')"
                            >
                                Edit
                            </x-framework.buttons.secondary>

                            <x-framework.feedback.modal
                                name="edit-emergency-contact-{{ $contact->id }}"
                                title="Edit Emergency Contact"
                                description="Update emergency contact information."
                            >
                                <form action="{{ route('applicant.profile.update-emergency-contact', $contact) }}" method="POST" class="space-y-6">
                                    @csrf
                                    @method('PATCH')

                                    <div class="grid gap-6 sm:grid-cols-2">
                                        <div class="sm:col-span-2">
                                            <x-framework.forms.input
                                                label="Full Name"
                                                name="name"
                                                :value="$contact->name"
                                                required
                                            />
                                        </div>

                                        <x-framework.forms.select
                                            label="Relationship"
                                            name="master_relationship_id"
                                            :options="$relationships->pluck('name', 'id')"
                                            :selected="$contact->master_relationship_id"
                                            required
                                        />

                                        <x-framework.forms.input
                                            label="Mobile Number"
                                            name="mobile_number"
                                            :value="$contact->mobile_number"
                                            required
                                        />

                                        <x-framework.forms.input
                                            label="Alternate Number"
                                            name="alternate_mobile_number"
                                            :value="$contact->alternate_mobile_number"
                                        />

                                        <x-framework.forms.input
                                            label="Email Address"
                                            name="email"
                                            type="email"
                                            :value="$contact->email"
                                        />

                                        <div class="sm:col-span-2">
                                            <x-framework.forms.textarea
                                                label="Address"
                                                name="address"
                                                rows="3"
                                            >{{ $contact->address }}</x-framework.forms.textarea>
                                        </div>
                                    </div>

                                    <div class="flex justify-end gap-3 mt-8">
                                        <x-framework.buttons.secondary
                                            type="button"
                                            @click="$dispatch('close-modal', 'edit-emergency-contact-{{ $contact->id }}')"
                                        >
                                            Cancel
                                        </x-framework.buttons.secondary>

                                        <x-framework.buttons.primary type="submit">
                                            Save Changes
                                        </x-framework.buttons.primary>
                                    </div>
                                </form>
                            </x-framework.feedback.modal>

                            <form action="{{ route('applicant.profile.delete-emergency-contact', $contact) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this contact?')">
                                @csrf
                                @method('DELETE')
                                <x-framework.buttons.secondary type="submit" size="sm" class="text-red-600 border-red-200 hover:bg-red-50">
                                    Delete
                                </x-framework.buttons.secondary>
                            </form>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</x-framework.layout.card>
