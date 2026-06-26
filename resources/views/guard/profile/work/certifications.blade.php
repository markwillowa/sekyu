<section class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm">
    <div class="flex items-center justify-between border-b border-slate-200 pb-5">
        <div>
            <h2 class="text-xl font-bold text-slate-900">
                Certifications
            </h2>

            <p class="mt-1 text-sm text-slate-500">
                Additional certificates that support your guard profile.
            </p>
        </div>

        <a href="#" class="rounded-lg bg-slate-900 px-4 py-2 text-sm font-semibold text-white hover:bg-slate-800">
            Add Certification
        </a>
    </div>

    @if ($certifications->isEmpty())
        <div class="mt-6 rounded-2xl border border-dashed border-slate-300 bg-slate-50 p-8 text-center">
            <h3 class="text-lg font-bold text-slate-900">
                No certifications added yet
            </h3>
        </div>
    @else
        <div class="mt-6 space-y-4">
            @foreach ($certifications as $certification)
                <article class="rounded-2xl border border-slate-200 p-5">
                    <h3 class="text-lg font-bold text-slate-900">
                        {{ $certification->name }}
                    </h3>

                    <p class="mt-1 text-sm text-slate-500">
                        {{ $certification->issuer ?? 'Issuer not provided' }}
                    </p>
                </article>
            @endforeach
        </div>
    @endif
</section>
