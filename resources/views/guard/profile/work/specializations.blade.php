<section class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm">
    <div class="flex items-center justify-between border-b border-slate-200 pb-5">
        <div>
            <h2 class="text-xl font-bold text-slate-900">
                Specializations
            </h2>

            <p class="mt-1 text-sm text-slate-500">
                Security areas where you have experience or preference.
            </p>
        </div>

        <a href="#" class="rounded-lg bg-slate-900 px-4 py-2 text-sm font-semibold text-white hover:bg-slate-800">
            Add Specialization
        </a>
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
                <span class="rounded-full bg-slate-100 px-4 py-2 text-sm font-semibold text-slate-700">
                    {{ $specialization->specialization?->name ?? 'Specialization' }}
                </span>
            @endforeach
        </div>
    @endif
</section>
