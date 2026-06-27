@php
    $stats = [
        [
            'label' => 'Licenses',
            'value' => $profile?->licenses?->count() ?? 0,
        ],
        [
            'label' => 'Trainings',
            'value' => $profile?->trainings?->count() ?? 0,
        ],
        [
            'label' => 'Skills',
            'value' => $profile?->skills?->count() ?? 0,
        ],
        [
            'label' => 'Languages',
            'value' => $profile?->languages?->count() ?? 0,
        ],
        [
            'label' => 'Work Experience',
            'value' => $profile?->workExperiences?->count() ?? 0,
        ],
        [
            'label' => 'Clearances',
            'value' => $profile?->clearances?->count() ?? 0,
        ],
    ];
@endphp

<x-framework.layout.card>
    <h2 class="text-xl font-bold text-slate-900">
        Profile Statistics
    </h2>

    <p class="mt-1 text-sm text-slate-500">
        Quick summary of your guard profile records.
    </p>

    <div class="mt-6 grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
        @foreach ($stats as $stat)
            <div class="rounded-2xl bg-slate-50 p-5">
                <div class="text-3xl font-bold text-slate-900">
                    {{ $stat['value'] }}
                </div>

                <div class="mt-1 text-sm font-medium text-slate-500">
                    {{ $stat['label'] }}
                </div>
            </div>
        @endforeach
    </div>
</x-framework.layout.card>
