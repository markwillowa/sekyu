<section class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm">
    <div class="flex items-center justify-between border-b border-slate-200 pb-5">
        <div>
            <h2 class="text-xl font-bold text-slate-900">
                Languages
            </h2>

            <p class="mt-1 text-sm text-slate-500">
                Languages and proficiency levels you can use on duty.
            </p>
        </div>

        <a href="#" class="rounded-lg bg-slate-900 px-4 py-2 text-sm font-semibold text-white hover:bg-slate-800">
            Add Language
        </a>
    </div>

    @if ($languages->isEmpty())
        <div class="mt-6 rounded-2xl border border-dashed border-slate-300 bg-slate-50 p-8 text-center">
            <h3 class="text-lg font-bold text-slate-900">
                No languages added yet
            </h3>
        </div>
    @else
        <div class="mt-6 grid gap-4 sm:grid-cols-2">
            @foreach ($languages as $language)
                <div class="rounded-2xl border border-slate-200 p-5">
                    <h3 class="font-bold text-slate-900">
                        {{ $language->language?->name ?? 'Language' }}
                    </h3>

                    <p class="mt-1 text-sm text-slate-500">
                        {{ $language->proficiency?->name ?? 'Proficiency not provided' }}
                    </p>
                </div>
            @endforeach
        </div>
    @endif
</section>
