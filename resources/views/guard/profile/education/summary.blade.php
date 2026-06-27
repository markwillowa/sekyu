@php
    $latestEducation = $educations->sortByDesc('ended_year')->first();
@endphp

<x-framework.layout.card>
    <div class="flex items-center justify-between gap-4">
        <div>
            <h2 class="text-xl font-bold text-slate-900">
                Educational Background
            </h2>

            <p class="mt-1 text-sm text-slate-500">
                Add your academic history and security-related education.
            </p>
        </div>
    </div>

    <div class="mt-6 grid gap-4 sm:grid-cols-3">
        <div class="rounded-2xl bg-slate-50 p-5">
            <div class="text-3xl font-bold text-slate-900">
                {{ $educations->count() }}
            </div>

            <div class="mt-1 text-sm font-medium text-slate-500">
                Records
            </div>
        </div>

        <div class="rounded-2xl bg-slate-50 p-5 sm:col-span-2">
            <div class="text-sm font-medium text-slate-500">
                Latest Education
            </div>

            <div class="mt-1 font-semibold text-slate-900">
                {{ $latestEducation?->level ?? 'Not provided' }}
            </div>

            <div class="mt-1 text-sm text-slate-500">
                {{ $latestEducation?->school_name ?? 'Add your educational background to complete this section.' }}
            </div>
        </div>
    </div>
</x-framework.layout.card>
