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
        <form action="{{ route('applicant.profile.store-license') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
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

                            @if ($license->hasMedia('licenses'))
                                <div class="mt-4">
                                    <p class="mb-2 text-xs font-bold uppercase tracking-wider text-slate-400">
                                        Attachment
                                    </p>

                                    @php
                                        $media = $license->getFirstMedia('licenses');
                                        $isPdf = $media && $media->mime_type === 'application/pdf';
                                    @endphp

                                    <a
                                        href="{{ $license->getFirstMediaUrl('licenses') }}"
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
                                                src="{{ $license->getFirstMediaUrl('licenses') }}"
                                                alt="License Attachment"
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
                                <form action="{{ route('applicant.profile.update-license', $license) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
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
