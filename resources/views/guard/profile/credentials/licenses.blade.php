<x-framework.layout.card>
    <div class="flex items-center justify-between border-b border-slate-200 pb-5">
        <div>
            <h2 class="text-xl font-bold text-slate-900">
                Licenses
            </h2>

            <p class="mt-1 text-sm text-slate-500">
                Security licenses and professional permits.
            </p>
        </div>

        <x-framework.buttons.primary
            href="#"
            size="sm"
            @click.prevent="$dispatch('open-modal', 'add-license')"
        >
            Add License
        </x-framework.buttons.primary>
    </div>

    <x-framework.feedback.modal
        name="add-license"
        title="Add License"
        description="Add a security license or professional permit."
    >
        <form action="{{ route('applicant.profile.store-license') }}" method="POST" class="space-y-6">
            @csrf

            <div class="grid gap-6 sm:grid-cols-2">
                <x-framework.forms.select
                    label="License Type"
                    name="master_license_type_id"
                    :options="$licenseTypes->pluck('name', 'id')"
                    required
                />

                <x-framework.forms.input
                    label="License Number"
                    name="license_number"
                    required
                />

                <x-framework.forms.input
                    label="Issue Date"
                    name="issued_at"
                    type="date"
                    required
                />

                <x-framework.forms.input
                    label="Expiry Date"
                    name="expires_at"
                    type="date"
                />

                <div class="sm:col-span-2">
                    <x-framework.forms.input
                        label="Issuing Authority"
                        name="issuing_authority"
                    />
                </div>
            </div>

            <div class="flex justify-end gap-3 mt-8">
                <x-framework.buttons.secondary
                    type="button"
                    @click="$dispatch('close-modal', 'add-license')"
                >
                    Cancel
                </x-framework.buttons.secondary>

                <x-framework.buttons.primary type="submit">
                    Add License
                </x-framework.buttons.primary>
            </div>
        </form>
    </x-framework.feedback.modal>

    @if ($licenses->isEmpty())
        <div class="mt-6 rounded-2xl border border-dashed border-slate-300 bg-slate-50 p-8 text-center">
            <h3 class="text-lg font-bold text-slate-900">
                No licenses added yet
            </h3>

            <p class="mt-2 text-sm text-slate-500">
                Add your LESP, security guard license, firearms license, or driver’s license.
            </p>
        </div>
    @else
        <div class="mt-6 space-y-4">
            @foreach ($licenses as $license)
                <article class="rounded-2xl border border-slate-200 p-5">
                    <div class="flex items-start justify-between gap-4">
                        <div>
                            <h3 class="text-lg font-bold text-slate-900">
                                {{ $license->licenseType?->name ?? 'License' }}
                            </h3>

                            <p class="mt-1 text-sm text-slate-500">
                                License No: {{ $license->license_number ?? 'Not provided' }}
                            </p>

                            <p class="mt-2 text-sm text-slate-500">
                                Issued:
                                {{ $license->issued_at?->format('F d, Y') ?? 'Not provided' }}
                                |
                                Expires:
                                {{ $license->expires_at?->format('F d, Y') ?? 'Not provided' }}
                            </p>
                        </div>

                        <div class="flex gap-2">
                            <x-framework.buttons.secondary
                                href="#"
                                size="sm"
                                @click.prevent="$dispatch('open-modal', 'edit-license-{{ $license->id }}')"
                            >
                                Edit
                            </x-framework.buttons.secondary>

                            <x-framework.feedback.modal
                                name="edit-license-{{ $license->id }}"
                                title="Edit License"
                                description="Update your license information."
                            >
                                <form action="{{ route('applicant.profile.update-license', $license) }}" method="POST" class="space-y-6">
                                    @csrf
                                    @method('PATCH')

                                    <div class="grid gap-6 sm:grid-cols-2">
                                        <x-framework.forms.select
                                            label="License Type"
                                            name="master_license_type_id"
                                            :options="$licenseTypes->pluck('name', 'id')"
                                            :selected="$license->master_license_type_id"
                                            required
                                        />

                                        <x-framework.forms.input
                                            label="License Number"
                                            name="license_number"
                                            :value="$license->license_number"
                                            required
                                        />

                                        <x-framework.forms.input
                                            label="Issue Date"
                                            name="issued_at"
                                            type="date"
                                            :value="$license->issued_at?->format('Y-m-d')"
                                            required
                                        />

                                        <x-framework.forms.input
                                            label="Expiry Date"
                                            name="expires_at"
                                            type="date"
                                            :value="$license->expires_at?->format('Y-m-d')"
                                        />

                                        <div class="sm:col-span-2">
                                            <x-framework.forms.input
                                                label="Issuing Authority"
                                                name="issuing_authority"
                                                :value="$license->issuing_authority"
                                            />
                                        </div>
                                    </div>

                                    <div class="flex justify-end gap-3 mt-8">
                                        <x-framework.buttons.secondary
                                            type="button"
                                            @click="$dispatch('close-modal', 'edit-license-{{ $license->id }}')"
                                        >
                                            Cancel
                                        </x-framework.buttons.secondary>

                                        <x-framework.buttons.primary type="submit">
                                            Save Changes
                                        </x-framework.buttons.primary>
                                    </div>
                                </form>
                            </x-framework.feedback.modal>

                            <form action="{{ route('applicant.profile.delete-license', $license) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this license?')">
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
