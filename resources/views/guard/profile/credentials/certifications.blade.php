<x-framework.layout.card>
    <div class="flex items-center justify-between border-b border-slate-200 pb-5">
        <div>
            <h2 class="text-xl font-bold text-slate-900">
                Certifications
            </h2>

            <p class="mt-1 text-sm text-slate-500">
                Additional certifications that enhance your qualifications.
            </p>
        </div>

        <x-framework.buttons.primary
            href="#"
            size="sm"
            @click.prevent="$dispatch('open-modal', 'add-certification')"
        >
            Add Certification
        </x-framework.buttons.primary>
    </div>

    <x-framework.feedback.modal
        name="add-certification"
        title="Add Certification"
        description="Add a professional certification."
    >
        <form action="{{ route('applicant.profile.store-certification') }}" method="POST" class="space-y-6">
            @csrf

            <div class="grid gap-6 sm:grid-cols-2">
                <div class="sm:col-span-2">
                    <x-framework.forms.input
                        label="Certification Name"
                        name="name"
                        required
                    />
                </div>

                <x-framework.forms.input
                    label="Issuing Organization"
                    name="issuer"
                    required
                />

                <x-framework.forms.input
                    label="Issue Date"
                    name="issued_at"
                    type="date"
                />

                <x-framework.forms.input
                    label="Expiry Date"
                    name="expires_at"
                    type="date"
                />

                <div class="sm:col-span-2">
                    <x-framework.forms.input
                        label="Credential ID"
                        name="credential_id"
                    />
                </div>
            </div>

            <div class="flex justify-end gap-3 mt-8">
                <x-framework.buttons.secondary
                    type="button"
                    @click="$dispatch('close-modal', 'add-certification')"
                >
                    Cancel
                </x-framework.buttons.secondary>

                <x-framework.buttons.primary type="submit">
                    Add Certification
                </x-framework.buttons.primary>
            </div>
        </form>
    </x-framework.feedback.modal>

    @if ($certifications->isEmpty())
        <div class="mt-6 rounded-2xl border border-dashed border-slate-300 bg-slate-50 p-8 text-center">
            <h3 class="text-lg font-bold text-slate-900">
                No certifications added yet
            </h3>

            <p class="mt-2 text-sm text-slate-500">
                Add certificates you've earned from accredited organizations.
            </p>
        </div>
    @else
        <div class="mt-6 space-y-4">
            @foreach ($certifications as $certification)
                <article class="rounded-2xl border border-slate-200 p-5">
                    <div class="flex items-start justify-between gap-4">
                        <div>
                            <h3 class="text-lg font-bold text-slate-900">
                                {{ $certification->name }}
                            </h3>

                            <p class="mt-1 text-sm text-slate-600">
                                {{ $certification->issuer ?? 'Issuer not specified' }}
                            </p>

                            @if ($certification->issued_at)
                                <p class="mt-2 text-sm text-slate-500">
                                    Issued:
                                    {{ $certification->issued_at->format('F d, Y') }}
                                </p>
                            @endif

                            @if ($certification->expires_at)
                                <p class="mt-1 text-sm text-slate-500">
                                    Expires:
                                    {{ $certification->expires_at->format('F d, Y') }}
                                </p>
                            @endif
                        </div>

                        <div class="flex gap-2">
                            <x-framework.buttons.secondary
                                href="#"
                                size="sm"
                                @click.prevent="$dispatch('open-modal', 'edit-certification-{{ $certification->id }}')"
                            >
                                Edit
                            </x-framework.buttons.secondary>

                            <x-framework.feedback.modal
                                name="edit-certification-{{ $certification->id }}"
                                title="Edit Certification"
                                description="Update certification details."
                            >
                                <form action="{{ route('applicant.profile.update-certification', $certification) }}" method="POST" class="space-y-6">
                                    @csrf
                                    @method('PATCH')

                                    <div class="grid gap-6 sm:grid-cols-2">
                                        <div class="sm:col-span-2">
                                            <x-framework.forms.input
                                                label="Certification Name"
                                                name="name"
                                                :value="$certification->name"
                                                required
                                            />
                                        </div>

                                        <x-framework.forms.input
                                            label="Issuing Organization"
                                            name="issuer"
                                            :value="$certification->issuer"
                                            required
                                        />

                                        <x-framework.forms.input
                                            label="Issue Date"
                                            name="issued_at"
                                            type="date"
                                            :value="$certification->issued_at?->format('Y-m-d')"
                                        />

                                        <x-framework.forms.input
                                            label="Expiry Date"
                                            name="expires_at"
                                            type="date"
                                            :value="$certification->expires_at?->format('Y-m-d')"
                                        />

                                        <div class="sm:col-span-2">
                                            <x-framework.forms.input
                                                label="Credential ID"
                                                name="credential_id"
                                                :value="$certification->credential_id"
                                            />
                                        </div>
                                    </div>

                                    <div class="flex justify-end gap-3 mt-8">
                                        <x-framework.buttons.secondary
                                            type="button"
                                            @click="$dispatch('close-modal', 'edit-certification-{{ $certification->id }}')"
                                        >
                                            Cancel
                                        </x-framework.buttons.secondary>

                                        <x-framework.buttons.primary type="submit">
                                            Save Changes
                                        </x-framework.buttons.primary>
                                    </div>
                                </form>
                            </x-framework.feedback.modal>

                            <form action="{{ route('applicant.profile.delete-certification', $certification) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this certification?')">
                                @csrf
                                @method('DELETE')
                                <x-framework.buttons.secondary type="submit" size="sm" class="text-red-600 border-red-200 hover:bg-red-50">
                                    Delete
                                </x-framework.buttons.secondary>
                            </form>
                        </div>
                    </div>
                </article>
            @endforeach
        </div>
    @endif
</x-framework.layout.card>
