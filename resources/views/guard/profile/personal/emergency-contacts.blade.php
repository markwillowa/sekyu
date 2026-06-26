<section class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm">
    <div class="flex items-center justify-between border-b border-slate-200 pb-5">
        <div>
            <h2 class="text-xl font-bold text-slate-900">
                Emergency Contacts
            </h2>

            <p class="mt-1 text-sm text-slate-500">
                People agencies may contact in case of emergency.
            </p>
        </div>

        <a href="#" class="rounded-lg bg-slate-900 px-4 py-2 text-sm font-semibold text-white hover:bg-slate-800">
            Add Contact
        </a>
    </div>

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
                            <a href="#" class="rounded-lg border border-slate-300 px-3 py-2 text-sm font-semibold text-slate-700 hover:bg-slate-50">
                                Edit
                            </a>

                            <a href="#" class="rounded-lg border border-red-200 px-3 py-2 text-sm font-semibold text-red-600 hover:bg-red-50">
                                Delete
                            </a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</section>
