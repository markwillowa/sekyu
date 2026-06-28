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

                            @if($clearance->hasMedia('clearances'))
                                <div class="mt-3">
                                    @php
                                        $media = $clearance->getFirstMedia('clearances');
                                        $isPdf = $media && $media->mime_type === 'application/pdf';
                                    @endphp
                                    <a href="{{ $clearance->getFirstMediaUrl('clearances') }}" target="_blank" class="group relative inline-block">
                                        @if($isPdf)
                                            <div class="flex h-20 w-20 flex-col items-center justify-center rounded-lg border border-slate-200 bg-slate-50 transition-opacity group-hover:opacity-75">
                                                <svg class="h-8 w-8 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                                                </svg>
                                                <span class="mt-1 text-[8px] font-medium text-slate-500 uppercase">PDF</span>
                                            </div>
                                        @else
                                            <img src="{{ $clearance->getFirstMediaUrl('clearances') }}" alt="Clearance" class="h-20 w-20 rounded-lg object-cover border border-slate-200 transition-opacity group-hover:opacity-75">
                                        @endif
                                        <div class="absolute inset-0 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity">
                                            <span class="bg-black/50 text-white text-[10px] px-1.5 py-0.5 rounded">View</span>
                                        </div>
                                    </a>
                                </div>
                            @endif

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
                        <form action="{{ route('applicant.profile.update-clearance', $clearance) }}" method="POST" enctype="multipart/form-data" class="space-y-4">
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

                            <x-framework.forms.file name="attachment" label="Clearance Attachment (Image or PDF)" accept="image/*,.pdf" />

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
        <form action="{{ route('applicant.profile.store-clearance') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
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

            <x-framework.forms.file name="attachment" label="Clearance Attachment" accept="image/*" />

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
