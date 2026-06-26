<section class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm">
    <div class="flex items-center justify-between border-b border-slate-200 pb-5">
        <div>
            <h2 class="text-xl font-bold text-slate-900">
                Certifications
            </h2>

            <p class="mt-1 text-sm text-slate-500">
                Additional certifications that enhance your qualifications.
            </p>
        </div>

        <a
            href="#"
            class="rounded-lg bg-slate-900 px-4 py-2 text-sm font-semibold text-white hover:bg-slate-800"
        >
            Add Certification
        </a>
    </div>

    @if ($certifications->isEmpty())
        <div class="mt-6 rounded-2xl border border-dashed border-slate-300 bg-slate-50 p-8 text-center">
            <h3 class="text-lg font-bold text-slate-900">
                No certifications added yet
            </h3>

            <p class="mt-2 text-sm text-slate-500">
                Add certificates you've earned from accredited organizations.
            </p>
        </div>
    @else
        <div class="mt-6 space-y-4">
            @foreach ($certifications as $certification)
                <article class="rounded-2xl border border-slate-200 p-5">
                    <div class="flex items-start justify-between gap-4">
                        <div>
                            <h3 class="text-lg font-bold text-slate-900">
                                {{ $certification->name }}
                            </h3>

                            <p class="mt-1 text-sm text-slate-600">
                                {{ $certification->issuer ?? 'Issuer not specified' }}
                            </p>

                            @if ($certification->issued_at)
                                <p class="mt-2 text-sm text-slate-500">
                                    Issued:
                                    {{ $certification->issued_at->format('F d, Y') }}
                                </p>
                            @endif

                            @if ($certification->expires_at)
                                <p class="mt-1 text-sm text-slate-500">
                                    Expires:
                                    {{ $certification->expires_at->format('F d, Y') }}
                                </p>
                            @endif
                        </div>

                        <div class="flex gap-2">
                            <a href="#" class="rounded-lg border border-slate-300 px-3 py-2 text-sm font-semibold text-slate-700 hover:bg-slate-50">
                                Edit
                            </a>

                            <a href="#" class="rounded-lg border border-red-200 px-3 py-2 text-sm font-semibold text-red-600 hover:bg-red-50">
                                Delete
                            </a>
                        </div>
                    </div>
                </article>
            @endforeach
        </div>
    @endif
</section>
