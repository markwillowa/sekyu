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

        <x-framework.buttons.primary href="#" size="sm">
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
                                Reference No:
                                {{ $clearance->reference_number ?? 'Not provided' }}
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
                            <x-framework.buttons.secondary href="#" size="sm">
                                Edit
                            </x-framework.buttons.secondary>

                            <x-framework.buttons.secondary href="#" size="sm" class="text-red-600 border-red-200 hover:bg-red-50">
                                Delete
                            </x-framework.buttons.secondary>
                        </div>
                    </div>
                </article>
            @endforeach
        </div>
    @endif
</x-framework.layout.card>
