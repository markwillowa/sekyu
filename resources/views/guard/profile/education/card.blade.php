<article class="rounded-2xl border border-slate-200 p-5">
    <div class="flex flex-col gap-4 sm:flex-row sm:items-start sm:justify-between">
        <div>
            <h3 class="text-lg font-bold text-slate-900">
                {{ $education->level }}
            </h3>

            <p class="mt-1 font-medium text-slate-700">
                {{ $education->school_name }}
            </p>

            @if ($education->course_or_strand || $education->field_of_study)
                <p class="mt-1 text-sm text-slate-500">
                    {{ $education->course_or_strand }}

                    @if ($education->course_or_strand && $education->field_of_study)
                        —
                    @endif

                    {{ $education->field_of_study }}
                </p>
            @endif

            <p class="mt-2 text-sm text-slate-500">
                {{ $education->started_year ?? 'Unknown' }}
                —
                {{ $education->is_current ? 'Present' : ($education->ended_year ?? 'Unknown') }}
            </p>

            @if ($education->honors_or_awards)
                <p class="mt-3 rounded-lg bg-amber-50 px-3 py-2 text-sm font-medium text-amber-800">
                    {{ $education->honors_or_awards }}
                </p>
            @endif

            @if ($education->description)
                <p class="mt-3 leading-7 text-slate-600">
                    {{ $education->description }}
                </p>
            @endif
        </div>

        <div class="flex gap-2">
            <a
                href="#"
                class="rounded-lg border border-slate-300 px-3 py-2 text-sm font-semibold text-slate-700 transition hover:bg-slate-50"
            >
                Edit
            </a>

            <a
                href="#"
                class="rounded-lg border border-red-200 px-3 py-2 text-sm font-semibold text-red-600 transition hover:bg-red-50"
            >
                Delete
            </a>
        </div>
    </div>
</article>
