<x-framework.layout.card>
    <div class="flex items-center justify-between border-b border-slate-200 pb-5">
        <div>
            <h2 class="text-xl font-bold text-slate-900">
                Identifications
            </h2>

            <p class="mt-1 text-sm text-slate-500">
                Government-issued IDs and identity documents.
            </p>
        </div>

        <x-framework.buttons.primary type="button" size="sm" @click.prevent="$dispatch('open-modal', 'add-identification')">
            Add ID
        </x-framework.buttons.primary>
    </div>

    @if ($identifications->isEmpty())
        <div class="mt-6 rounded-2xl border border-dashed border-slate-300 bg-slate-50 p-8 text-center">
            <h3 class="text-lg font-bold text-slate-900">
                No identifications added yet
            </h3>

            <p class="mt-2 text-sm text-slate-500">
                Add government IDs such as UMID, PhilHealth, TIN, SSS, or passport.
            </p>

            <div class="mt-6">
                <x-framework.buttons.primary type="button" size="sm" @click.prevent="$dispatch('open-modal', 'add-identification')">
                    Add ID
                </x-framework.buttons.primary>
            </div>
        </div>
    @else
        <div class="mt-6 space-y-4">
            @foreach ($identifications as $identification)
                <article class="rounded-2xl border border-slate-200 p-5">
                    <div class="flex items-start justify-between gap-4">
                        <div>
                            <h3 class="text-lg font-bold text-slate-900">
                                {{ $identification->identificationType?->name ?? $identification->id_type ?? 'Identification' }}
                            </h3>

                            @if($identification->hasMedia('identifications'))
                                <div class="mt-3">
                                    @php
                                        $media = $identification->getFirstMedia('identifications');
                                        $isPdf = $media && $media->mime_type === 'application/pdf';
                                    @endphp
                                    <a href="{{ $identification->getFirstMediaUrl('identifications') }}" target="_blank" class="group relative inline-block">
                                        @if($isPdf)
                                            <div class="flex h-20 w-20 flex-col items-center justify-center rounded-lg border border-slate-200 bg-slate-50 transition-opacity group-hover:opacity-75">
                                                <svg class="h-8 w-8 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                                                </svg>
                                                <span class="mt-1 text-[8px] font-medium text-slate-500 uppercase">PDF</span>
                                            </div>
                                        @else
                                            <img src="{{ $identification->getFirstMediaUrl('identifications') }}" alt="Identification" class="h-20 w-20 rounded-lg object-cover border border-slate-200 transition-opacity group-hover:opacity-75">
                                        @endif
                                        <div class="absolute inset-0 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity">
                                            <span class="bg-black/50 text-white text-[10px] px-1.5 py-0.5 rounded">View</span>
                                        </div>
                                    </a>
                                </div>
                            @endif

                            <p class="mt-1 text-sm text-slate-500">
                                ID Number:
                                {{ $identification->id_number ?? 'Not provided' }}
                            </p>

                            <p class="mt-2 text-sm text-slate-500">
                                Issued by:
                                {{ $identification->issuing_authority ?? 'Not provided' }}
                            </p>

                            <p class="mt-2 text-sm text-slate-500">
                                Issued:
                                {{ $identification->issued_at?->format('F d, Y') ?? 'Not provided' }}
                                |
                                Expires:
                                {{ $identification->expires_at?->format('F d, Y') ?? 'Not provided' }}
                            </p>
                        </div>

                        <div class="flex gap-2">
                            <x-framework.buttons.secondary type="button" size="sm" @click.prevent="$dispatch('open-modal', 'edit-identification-{{ $identification->id }}')">
                                Edit
                            </x-framework.buttons.secondary>

                            <form action="{{ route('applicant.profile.delete-identification', $identification) }}" method="POST" onsubmit="return confirm('Are you sure you want to remove this identification?')">
                                @csrf
                                @method('DELETE')
                                <x-framework.buttons.secondary type="submit" size="sm" class="text-red-600 border-red-200 hover:bg-red-50">
                                    Delete
                                </x-framework.buttons.secondary>
                            </form>
                        </div>
                    </div>

                    <!-- Edit Identification Modal -->
                    <x-framework.feedback.modal name="edit-identification-{{ $identification->id }}" title="Edit Identification">
                        <form action="{{ route('applicant.profile.update-identification', $identification) }}" method="POST" enctype="multipart/form-data" class="space-y-4">
                            @csrf
                            @method('PATCH')

                            <x-framework.forms.select name="master_identification_type_id" label="ID Type" required>
                                @foreach($identificationTypes as $type)
                                    <option value="{{ $type->id }}" @selected($identification->master_identification_type_id == $type->id)>{{ $type->name }}</option>
                                @endforeach
                            </x-framework.forms.select>

                            <x-framework.forms.input name="id_number" label="ID Number" value="{{ $identification->id_number }}" placeholder="Enter ID number" required />

                            <x-framework.forms.input name="issuing_authority" label="Issuing Authority" value="{{ $identification->issuing_authority }}" placeholder="e.g. DFA, LTO, PRC" />

                            <div class="grid grid-cols-2 gap-4">
                                <x-framework.forms.input type="date" name="issued_at" label="Date Issued" value="{{ $identification->issued_at?->format('Y-m-d') }}" />
                                <x-framework.forms.input type="date" name="expires_at" label="Expiration Date" value="{{ $identification->expires_at?->format('Y-m-d') }}" />
                            </div>

                            <x-framework.forms.file name="attachment" label="ID Attachment (Image or PDF)" accept="image/*,.pdf" />

                            <div class="mt-6 flex justify-end gap-3">
                                <x-framework.buttons.secondary type="button" @click="$dispatch('close-modal', 'edit-identification-{{ $identification->id }}')">
                                    Cancel
                                </x-framework.buttons.secondary>
                                <x-framework.buttons.primary type="submit">
                                    Update Identification
                                </x-framework.buttons.primary>
                            </div>
                        </form>
                    </x-framework.feedback.modal>
                </article>
            @endforeach
        </div>
    @endif

    <!-- Add Identification Modal -->
    <x-framework.feedback.modal name="add-identification" title="Add Identification">
        <form action="{{ route('applicant.profile.store-identification') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
            @csrf

            <x-framework.forms.select name="master_identification_type_id" label="ID Type" required>
                <option value="" selected disabled>Select ID Type</option>
                @foreach($identificationTypes as $type)
                    <option value="{{ $type->id }}">{{ $type->name }}</option>
                @endforeach
            </x-framework.forms.select>

            <x-framework.forms.input name="id_number" label="ID Number" placeholder="Enter ID number" required />

            <x-framework.forms.input name="issuing_authority" label="Issuing Authority" placeholder="e.g. DFA, LTO, PRC" />

            <div class="grid grid-cols-2 gap-4">
                <x-framework.forms.input type="date" name="issued_at" label="Date Issued" />
                <x-framework.forms.input type="date" name="expires_at" label="Expiration Date" />
            </div>

            <x-framework.forms.file name="attachment" label="ID Attachment" accept="image/*" />

            <div class="mt-6 flex justify-end gap-3">
                <x-framework.buttons.secondary type="button" @click="$dispatch('close-modal', 'add-identification')">
                    Cancel
                </x-framework.buttons.secondary>
                <x-framework.buttons.primary type="submit">
                    Add Identification
                </x-framework.buttons.primary>
            </div>
        </form>
    </x-framework.feedback.modal>
</x-framework.layout.card>
