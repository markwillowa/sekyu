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

        <x-framework.buttons.primary href="#" size="sm">
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
        </div>
    @else
        <div class="mt-6 space-y-4">
            @foreach ($medicals as $medical)
                <article class="rounded-2xl border border-slate-200 p-5">
                    <div class="flex items-start justify-between gap-4">
                        <div>
                            <h3 class="text-lg font-bold text-slate-900">
                                {{ $medical->type ?? 'Medical Record' }}
                            </h3>

                            <p class="mt-1 text-sm text-slate-500">
                                Clinic / Provider:
                                {{ $medical->provider ?? 'Not provided' }}
                            </p>

                            <p class="mt-2 text-sm text-slate-500">
                                Issued:
                                {{ $medical->issued_at?->format('F d, Y') ?? 'Not provided' }}
                                |
                                Expires:
                                {{ $medical->expires_at?->format('F d, Y') ?? 'Not provided' }}
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
