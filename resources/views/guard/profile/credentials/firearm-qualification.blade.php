<x-framework.layout.card>
    <div class="flex items-center justify-between border-b border-slate-200 pb-5">
        <div>
            <h2 class="text-xl font-bold text-slate-900">
                Firearm Qualification
            </h2>

            <p class="mt-1 text-sm text-slate-500">
                Firearm authorization and qualification status.
            </p>
        </div>

        <x-framework.buttons.primary
            type="button"
            size="sm"
            @click.prevent="$dispatch('open-modal', 'edit-firearm-qualification')"
        >
            Edit
        </x-framework.buttons.primary>
    </div>

    <x-framework.feedback.modal
        name="edit-firearm-qualification"
        title="Edit Firearm Qualification"
        description="Update your firearm qualification details."
    >
        <form action="{{ route('applicant.profile.update-firearm-qualification') }}" method="POST" class="space-y-6">
            @csrf
            @method('PATCH')

            <div class="grid gap-6 sm:grid-cols-2">
                <div class="sm:col-span-2">
                    <x-framework.forms.label for="is_firearm_qualified" value="Are you firearm qualified?" />
                    <x-framework.forms.select
                        id="is_firearm_qualified"
                        name="is_firearm_qualified"
                        class="mt-1"
                        required
                    >
                        <option value="1" {{ old('is_firearm_qualified', $firearmQualification?->is_firearm_qualified) ? 'selected' : '' }}>Yes</option>
                        <option value="0" {{ !old('is_firearm_qualified', $firearmQualification?->is_firearm_qualified) ? 'selected' : '' }}>No</option>
                    </x-framework.forms.select>
                    <x-framework.forms.error name="is_firearm_qualified" />
                </div>

                <div>
                    <x-framework.forms.label for="firearm_type" value="Firearm Type" />
                    <x-framework.forms.input
                        id="firearm_type"
                        name="firearm_type"
                        type="text"
                        class="mt-1"
                        placeholder="e.g. Pistol, Shotgun"
                        value="{{ old('firearm_type', $firearmQualification?->firearm_type) }}"
                    />
                    <x-framework.forms.error name="firearm_type" />
                </div>

                <div>
                    <x-framework.forms.label for="permit_number" value="Permit Number" />
                    <x-framework.forms.input
                        id="permit_number"
                        name="permit_number"
                        type="text"
                        class="mt-1"
                        placeholder="Enter permit number"
                        value="{{ old('permit_number', $firearmQualification?->permit_number) }}"
                    />
                    <x-framework.forms.error name="permit_number" />
                </div>

                <div>
                    <x-framework.forms.label for="issued_at" value="Date Issued" />
                    <x-framework.forms.input
                        id="issued_at"
                        name="issued_at"
                        type="date"
                        class="mt-1"
                        value="{{ old('issued_at', $firearmQualification?->issued_at?->format('Y-m-d')) }}"
                    />
                    <x-framework.forms.error name="issued_at" />
                </div>

                <div>
                    <x-framework.forms.label for="expires_at" value="Expiry Date" />
                    <x-framework.forms.input
                        id="expires_at"
                        name="expires_at"
                        type="date"
                        class="mt-1"
                        value="{{ old('expires_at', $firearmQualification?->expires_at?->format('Y-m-d')) }}"
                    />
                    <x-framework.forms.error name="expires_at" />
                </div>
            </div>

            <div class="flex justify-end gap-3">
                <x-framework.buttons.secondary
                    type="button"
                    @click.prevent="$dispatch('close-modal', 'edit-firearm-qualification')"
                >
                    Cancel
                </x-framework.buttons.secondary>

                <x-framework.buttons.primary type="submit">
                    Save Changes
                </x-framework.buttons.primary>
            </div>
        </form>
    </x-framework.feedback.modal>

    @if (! $firearmQualification || (! $firearmQualification->is_firearm_qualified && ! $firearmQualification->firearm_type))
        <div class="mt-6 rounded-2xl border border-dashed border-slate-300 bg-slate-50 p-8 text-center">
            <h3 class="text-lg font-bold text-slate-900">
                No firearm qualification added yet
            </h3>

            <p class="mt-2 text-sm text-slate-500">
                Add this if you are qualified or authorized for armed security assignments.
            </p>
        </div>
    @else
        <dl class="mt-6 grid gap-6 sm:grid-cols-2">
            <div>
                <dt class="text-sm font-medium text-slate-500">Qualified</dt>
                <dd class="mt-1 text-slate-900">
                    {{ $firearmQualification->is_firearm_qualified ? 'Yes' : 'No' }}
                </dd>
            </div>

            <div>
                <dt class="text-sm font-medium text-slate-500">Firearm Type</dt>
                <dd class="mt-1 text-slate-900">
                    {{ $firearmQualification->firearm_type ?? 'Not provided' }}
                </dd>
            </div>

            <div>
                <dt class="text-sm font-medium text-slate-500">Permit Number</dt>
                <dd class="mt-1 text-slate-900">
                    {{ $firearmQualification->permit_number ?? 'Not provided' }}
                </dd>
            </div>

            <div>
                <dt class="text-sm font-medium text-slate-500">Qualification Date</dt>
                <dd class="mt-1 text-slate-900">
                    {{ $firearmQualification->issued_at?->format('F d, Y') ?? 'Not provided' }}
                </dd>
            </div>

            <div>
                <dt class="text-sm font-medium text-slate-500">Expiry Date</dt>
                <dd class="mt-1 text-slate-900">
                    {{ $firearmQualification->expires_at?->format('F d, Y') ?? 'Not provided' }}
                </dd>
            </div>
        </dl>
    @endif
</x-framework.layout.card>
