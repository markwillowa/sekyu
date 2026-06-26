<section class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm">
    <div class="flex items-center justify-between border-b border-slate-200 pb-5">
        <div>
            <h2 class="text-xl font-bold text-slate-900">
                Licenses
            </h2>

            <p class="mt-1 text-sm text-slate-500">
                Security licenses and professional permits.
            </p>
        </div>

        <a href="#" class="rounded-lg bg-slate-900 px-4 py-2 text-sm font-semibold text-white hover:bg-slate-800">
            Add License
        </a>
    </div>

    @if ($licenses->isEmpty())
        <div class="mt-6 rounded-2xl border border-dashed border-slate-300 bg-slate-50 p-8 text-center">
            <h3 class="text-lg font-bold text-slate-900">
                No licenses added yet
            </h3>

            <p class="mt-2 text-sm text-slate-500">
                Add your LESP, security guard license, firearms license, or driver’s license.
            </p>
        </div>
    @else
        <div class="mt-6 space-y-4">
            @foreach ($licenses as $license)
                <article class="rounded-2xl border border-slate-200 p-5">
                    <div class="flex items-start justify-between gap-4">
                        <div>
                            <h3 class="text-lg font-bold text-slate-900">
                                {{ $license->licenseType?->name ?? 'License' }}
                            </h3>

                            <p class="mt-1 text-sm text-slate-500">
                                License No: {{ $license->license_number ?? 'Not provided' }}
                            </p>

                            <p class="mt-2 text-sm text-slate-500">
                                Issued:
                                {{ $license->issued_at?->format('F d, Y') ?? 'Not provided' }}
                                |
                                Expires:
                                {{ $license->expires_at?->format('F d, Y') ?? 'Not provided' }}
                            </p>
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
