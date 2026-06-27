<x-framework.layout.card>
    <div class="flex items-center justify-between border-b border-slate-200 pb-5">
        <div>
            <h2 class="text-xl font-bold text-slate-900">
                Specializations
            </h2>

            <p class="mt-1 text-sm text-slate-500">
                Security areas where you have experience or preference.
            </p>
        </div>

        <x-framework.buttons.primary href="#" size="sm">
            Add Specialization
        </x-framework.buttons.primary>
    </div>

    @if ($specializations->isEmpty())
        <div class="mt-6 rounded-2xl border border-dashed border-slate-300 bg-slate-50 p-8 text-center">
            <h3 class="text-lg font-bold text-slate-900">
                No specializations added yet
            </h3>
        </div>
    @else
        <div class="mt-6 flex flex-wrap gap-2">
            @foreach ($specializations as $specialization)
                <x-framework.feedback.badge color="slate">
                    {{ $specialization->specialization?->name ?? 'Specialization' }}
                </x-framework.feedback.badge>
            @endforeach
        </div>
    @endif
</x-framework.layout.card>
