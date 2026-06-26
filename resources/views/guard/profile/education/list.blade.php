<section class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm">
    <div class="border-b border-slate-200 pb-5">
        <h2 class="text-xl font-bold text-slate-900">
            Education Records
        </h2>

        <p class="mt-1 text-sm text-slate-500">
            Your schools, courses, years attended, and academic achievements.
        </p>
    </div>

    @if ($educations->isEmpty())
        @include('guard.profile.education.empty')
    @else
        <div class="mt-6 space-y-4">
            @foreach ($educations->sortByDesc('started_year') as $education)
                @include('guard.profile.education.card', [
                    'education' => $education,
                ])
            @endforeach
        </div>
    @endif
</section>
