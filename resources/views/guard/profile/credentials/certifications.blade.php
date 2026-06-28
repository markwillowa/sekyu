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
            type="button"
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
        <form action="{{ route('applicant.profile.store-certification') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
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

                <div class="sm:col-span-2">
                    <x-framework.forms.file
                        label="Attachment (Image or PDF)"
                        name="attachment"
                        accept="image/*,.pdf"
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

                            @if ($certification->hasMedia('certificates'))
                                <div class="mt-4">
                                    <p class="mb-2 text-xs font-bold uppercase tracking-wider text-slate-400">
                                        Attachment
                                    </p>

                                    @php
                                        $media = $certification->getFirstMedia('certificates');
                                        $isPdf = $media && $media->mime_type === 'application/pdf';
                                    @endphp

                                    <a
                                        href="{{ $certification->getFirstMediaUrl('certificates') }}"
                                        target="_blank"
                                        class="group relative inline-block overflow-hidden rounded-xl border border-slate-200"
                                    >
                                        @if($isPdf)
                                            <div class="flex h-32 w-48 flex-col items-center justify-center bg-slate-50 transition duration-300 group-hover:bg-slate-100">
                                                <svg class="h-12 w-12 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                                                </svg>
                                                <span class="mt-2 text-xs font-medium text-slate-500">PDF Document</span>
                                            </div>
                                        @else
                                            <img
                                                src="{{ $certification->getFirstMediaUrl('certificates') }}"
                                                alt="Certification Attachment"
                                                class="h-32 w-48 object-cover transition duration-300 group-hover:scale-110"
                                            >
                                        @endif

                                        <div class="absolute inset-0 flex items-center justify-center bg-slate-900/40 opacity-0 transition duration-300 group-hover:opacity-100">
                                            <svg class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                            </svg>
                                        </div>
                                    </a>
                                </div>
                            @endif
                        </div>

                        <div class="flex gap-2">
                            <x-framework.buttons.secondary
                                type="button"
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
                                <form action="{{ route('applicant.profile.update-certification', $certification) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
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

                                        <div class="sm:col-span-2">
                                            <x-framework.forms.file
                                                label="Update Attachment (Image or PDF)"
                                                name="attachment"
                                                accept="image/*,.pdf"
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
