<section class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm">
    <div class="flex items-center justify-between border-b border-slate-200 pb-5">
        <div>
            <h2 class="text-xl font-bold text-slate-900">
                Physical Details
            </h2>

            <p class="mt-1 text-sm text-slate-500">
                Physical information often required by security agencies.
            </p>
        </div>

        <a href="#" class="rounded-lg bg-slate-900 px-4 py-2 text-sm font-semibold text-white hover:bg-slate-800">
            Edit
        </a>
    </div>

    <dl class="mt-6 grid gap-6 sm:grid-cols-2">
        <div>
            <dt class="text-sm font-medium text-slate-500">Height</dt>
            <dd class="mt-1 text-slate-900">{{ $physicalDetail?->height ?? 'Not provided' }}</dd>
        </div>

        <div>
            <dt class="text-sm font-medium text-slate-500">Weight</dt>
            <dd class="mt-1 text-slate-900">{{ $physicalDetail?->weight ?? 'Not provided' }}</dd>
        </div>

        <div>
            <dt class="text-sm font-medium text-slate-500">Blood Type</dt>
            <dd class="mt-1 text-slate-900">{{ $physicalDetail?->blood_type ?? 'Not provided' }}</dd>
        </div>

        <div>
            <dt class="text-sm font-medium text-slate-500">Distinguishing Marks</dt>
            <dd class="mt-1 text-slate-900">{{ $physicalDetail?->distinguishing_marks ?? 'Not provided' }}</dd>
        </div>
    </dl>
</section>
