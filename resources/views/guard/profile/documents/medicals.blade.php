<x-framework.layout.card>
    <div class="flex items-center justify-between border-b border-slate-200 pb-5">
        <div>
            <h2 class="text-xl font-bold text-slate-900">
                Medical Records
            </h2>

            <p class="mt-1 text-sm text-slate-500">
                Medical clearance, drug test, and health-related requirements.
            </p>
        </div>

        <x-framework.buttons.primary type="button" size="sm" @click.prevent="$dispatch('open-modal', 'add-medical')">
            Add Medical
        </x-framework.buttons.primary>
    </div>

    @if ($medicals->isEmpty())
        <div class="mt-6 rounded-2xl border border-dashed border-slate-300 bg-slate-50 p-8 text-center">
            <h3 class="text-lg font-bold text-slate-900">
                No medical records added yet
            </h3>

            <p class="mt-2 text-sm text-slate-500">
                Add medical clearance, drug test, or health certificate details.
            </p>

            <div class="mt-6">
                <x-framework.buttons.primary type="button" size="sm" @click.prevent="$dispatch('open-modal', 'add-medical')">
                    Add Medical
                </x-framework.buttons.primary>
            </div>
        </div>
    @else
        <div class="mt-6 space-y-4">
            @foreach ($medicals as $medical)
                <article class="rounded-2xl border border-slate-200 p-5">
                    <div class="flex items-start justify-between gap-4">
                        <div>
                            <h3 class="text-lg font-bold text-slate-900">
                                {{ $medical->certificate_type ?? 'Medical Record' }}
                            </h3>

                            <p class="mt-1 text-sm text-slate-500">
                                Clinic / Provider:
                                {{ $medical->clinic_or_hospital ?? 'Not provided' }}
                            </p>

                            <p class="mt-1 text-sm text-slate-500">
                                Physician:
                                {{ $medical->physician_name ?? 'Not provided' }}
                            </p>

                            <p class="mt-2 text-sm text-slate-500">
                                Issued:
                                {{ $medical->issued_at?->format('F d, Y') ?? 'Not provided' }}
                                |
                                Expires:
                                {{ $medical->expires_at?->format('F d, Y') ?? 'Not provided' }}
                            </p>

                            @if($medical->is_fit_to_work)
                                <span class="mt-2 inline-flex items-center rounded-md bg-green-50 px-2 py-1 text-xs font-medium text-green-700 ring-1 ring-inset ring-green-600/20">
                                    Fit to Work
                                </span>
                            @endif
                        </div>

                        <div class="flex gap-2">
                            <x-framework.buttons.secondary type="button" size="sm" @click.prevent="$dispatch('open-modal', 'edit-medical-{{ $medical->id }}')">
                                Edit
                            </x-framework.buttons.secondary>

                            <form action="{{ route('applicant.profile.delete-medical', $medical) }}" method="POST" onsubmit="return confirm('Are you sure you want to remove this medical record?')">
                                @csrf
                                @method('DELETE')
                                <x-framework.buttons.secondary type="submit" size="sm" class="text-red-600 border-red-200 hover:bg-red-50">
                                    Delete
                                </x-framework.buttons.secondary>
                            </form>
                        </div>
                    </div>

                    <!-- Edit Medical Modal -->
                    <x-framework.feedback.modal name="edit-medical-{{ $medical->id }}" title="Edit Medical Record">
                        <form action="{{ route('applicant.profile.update-medical', $medical) }}" method="POST" class="space-y-4">
                            @csrf
                            @method('PATCH')

                            <x-framework.forms.input name="certificate_type" label="Certificate Type" value="{{ $medical->certificate_type }}" placeholder="e.g. Medical Certificate, Drug Test Result" required />

                            <x-framework.forms.input name="clinic_or_hospital" label="Clinic / Hospital" value="{{ $medical->clinic_or_hospital }}" placeholder="Enter clinic or hospital name" />

                            <x-framework.forms.input name="physician_name" label="Physician Name" value="{{ $medical->physician_name }}" placeholder="Enter physician name" />

                            <div class="grid grid-cols-2 gap-4">
                                <x-framework.forms.input type="date" name="issued_at" label="Date Issued" value="{{ $medical->issued_at?->format('Y-m-d') }}" />
                                <x-framework.forms.input type="date" name="expires_at" label="Expiration Date" value="{{ $medical->expires_at?->format('Y-m-d') }}" />
                            </div>

                            <x-framework.forms.toggle name="is_fit_to_work" label="Fit to Work" :checked="$medical->is_fit_to_work" />

                            <div class="mt-6 flex justify-end gap-3">
                                <x-framework.buttons.secondary type="button" @click="$dispatch('close-modal', 'edit-medical-{{ $medical->id }}')">
                                    Cancel
                                </x-framework.buttons.secondary>
                                <x-framework.buttons.primary type="submit">
                                    Update Medical Record
                                </x-framework.buttons.primary>
                            </div>
                        </form>
                    </x-framework.feedback.modal>
                </article>
            @endforeach
        </div>
    @endif

    <!-- Add Medical Modal -->
    <x-framework.feedback.modal name="add-medical" title="Add Medical Record">
        <form action="{{ route('applicant.profile.store-medical') }}" method="POST" class="space-y-4">
            @csrf

            <x-framework.forms.input name="certificate_type" label="Certificate Type" placeholder="e.g. Medical Certificate, Drug Test Result" required />

            <x-framework.forms.input name="clinic_or_hospital" label="Clinic / Hospital" placeholder="Enter clinic or hospital name" />

            <x-framework.forms.input name="physician_name" label="Physician Name" placeholder="Enter physician name" />

            <div class="grid grid-cols-2 gap-4">
                <x-framework.forms.input type="date" name="issued_at" label="Date Issued" />
                <x-framework.forms.input type="date" name="expires_at" label="Expiration Date" />
            </div>

            <x-framework.forms.toggle name="is_fit_to_work" label="Fit to Work" />

            <div class="mt-6 flex justify-end gap-3">
                <x-framework.buttons.secondary type="button" @click="$dispatch('close-modal', 'add-medical')">
                    Cancel
                </x-framework.buttons.secondary>
                <x-framework.buttons.primary type="submit">
                    Add Medical Record
                </x-framework.buttons.primary>
            </div>
        </form>
    </x-framework.feedback.modal>
</x-framework.layout.card>
