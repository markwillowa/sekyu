<x-framework.layout.card>
    <div class="flex items-center justify-between border-b border-slate-200 pb-5">
        <div>
            <h2 class="text-xl font-bold text-slate-900">
                Clearances
            </h2>

            <p class="mt-1 text-sm text-slate-500">
                NBI, police, barangay, and other required clearances.
            </p>
        </div>

        <x-framework.buttons.primary type="button" size="sm" @click.prevent="$dispatch('open-modal', 'add-clearance')">
            Add Clearance
        </x-framework.buttons.primary>
    </div>

    @if ($clearances->isEmpty())
        <div class="mt-6 rounded-2xl border border-dashed border-slate-300 bg-slate-50 p-8 text-center">
            <h3 class="text-lg font-bold text-slate-900">
                No clearances added yet
            </h3>

            <p class="mt-2 text-sm text-slate-500">
                Add your NBI, police, barangay, or other clearance records.
            </p>

            <div class="mt-6">
                <x-framework.buttons.primary type="button" size="sm" @click.prevent="$dispatch('open-modal', 'add-clearance')">
                    Add Clearance
                </x-framework.buttons.primary>
            </div>
        </div>
    @else
        <div class="mt-6 space-y-4">
            @foreach ($clearances as $clearance)
                <article class="rounded-2xl border border-slate-200 p-5">
                    <div class="flex items-start justify-between gap-4">
                        <div>
                            <h3 class="text-lg font-bold text-slate-900">
                                {{ $clearance->clearanceType?->name ?? 'Clearance' }}
                            </h3>

                            <p class="mt-1 text-sm text-slate-500">
                                Clearance No:
                                {{ $clearance->clearance_number ?? 'Not provided' }}
                            </p>

                            <p class="mt-2 text-sm text-slate-500">
                                Issued at:
                                {{ $clearance->issuing_office ?? 'Not provided' }}
                            </p>

                            <p class="mt-2 text-sm text-slate-500">
                                Issued:
                                {{ $clearance->issued_at?->format('F d, Y') ?? 'Not provided' }}
                                |
                                Expires:
                                {{ $clearance->expires_at?->format('F d, Y') ?? 'Not provided' }}
                            </p>
                        </div>

                        <div class="flex gap-2">
                            <x-framework.buttons.secondary type="button" size="sm" @click.prevent="$dispatch('open-modal', 'edit-clearance-{{ $clearance->id }}')">
                                Edit
                            </x-framework.buttons.secondary>

                            <form action="{{ route('applicant.profile.delete-clearance', $clearance) }}" method="POST" onsubmit="return confirm('Are you sure you want to remove this clearance?')">
                                @csrf
                                @method('DELETE')
                                <x-framework.buttons.secondary type="submit" size="sm" class="text-red-600 border-red-200 hover:bg-red-50">
                                    Delete
                                </x-framework.buttons.secondary>
                            </form>
                        </div>
                    </div>

                    <!-- Edit Clearance Modal -->
                    <x-framework.feedback.modal name="edit-clearance-{{ $clearance->id }}" title="Edit Clearance">
                        <form action="{{ route('applicant.profile.update-clearance', $clearance) }}" method="POST" class="space-y-4">
                            @csrf
                            @method('PATCH')

                            <x-framework.forms.select name="master_clearance_type_id" label="Clearance Type" required>
                                <option value="">Select Type</option>
                                @foreach($clearanceTypes as $type)
                                    <option value="{{ $type->id }}" @selected($clearance->master_clearance_type_id == $type->id)>{{ $type->name }}</option>
                                @endforeach
                            </x-framework.forms.select>

                            <x-framework.forms.input name="clearance_number" label="Clearance Number" value="{{ $clearance->clearance_number }}" placeholder="Enter clearance number" />

                            <x-framework.forms.input name="issuing_office" label="Issuing Office" value="{{ $clearance->issuing_office }}" placeholder="e.g. NBI Main, Manila" />

                            <div class="grid grid-cols-2 gap-4">
                                <x-framework.forms.input type="date" name="issued_at" label="Date Issued" value="{{ $clearance->issued_at?->format('Y-m-d') }}" />
                                <x-framework.forms.input type="date" name="expires_at" label="Expiration Date" value="{{ $clearance->expires_at?->format('Y-m-d') }}" />
                            </div>

                            <div class="mt-6 flex justify-end gap-3">
                                <x-framework.buttons.secondary type="button" @click="$dispatch('close-modal', 'edit-clearance-{{ $clearance->id }}')">
                                    Cancel
                                </x-framework.buttons.secondary>
                                <x-framework.buttons.primary type="submit">
                                    Update Clearance
                                </x-framework.buttons.primary>
                            </div>
                        </form>
                    </x-framework.feedback.modal>
                </article>
            @endforeach
        </div>
    @endif

    <!-- Add Clearance Modal -->
    <x-framework.feedback.modal name="add-clearance" title="Add Clearance">
        <form action="{{ route('applicant.profile.store-clearance') }}" method="POST" class="space-y-4">
            @csrf

            <x-framework.forms.select name="master_clearance_type_id" label="Clearance Type" required>
                <option value="">Select Type</option>
                @foreach($clearanceTypes as $type)
                    <option value="{{ $type->id }}">{{ $type->name }}</option>
                @endforeach
            </x-framework.forms.select>

            <x-framework.forms.input name="clearance_number" label="Clearance Number" placeholder="Enter clearance number" />

            <x-framework.forms.input name="issuing_office" label="Issuing Office" placeholder="e.g. NBI Main, Manila" />

            <div class="grid grid-cols-2 gap-4">
                <x-framework.forms.input type="date" name="issued_at" label="Date Issued" />
                <x-framework.forms.input type="date" name="expires_at" label="Expiration Date" />
            </div>

            <div class="mt-6 flex justify-end gap-3">
                <x-framework.buttons.secondary type="button" @click="$dispatch('close-modal', 'add-clearance')">
                    Cancel
                </x-framework.buttons.secondary>
                <x-framework.buttons.primary type="submit">
                    Add Clearance
                </x-framework.buttons.primary>
            </div>
        </form>
    </x-framework.feedback.modal>
</x-framework.layout.card>
