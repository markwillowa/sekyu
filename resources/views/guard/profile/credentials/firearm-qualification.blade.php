<section class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm">
    <div class="flex items-center justify-between border-b border-slate-200 pb-5">
        <div>
            <h2 class="text-xl font-bold text-slate-900">
                Firearm Qualification
            </h2>

            <p class="mt-1 text-sm text-slate-500">
                Firearm authorization and qualification status.
            </p>
        </div>

        <a href="#" class="rounded-lg bg-slate-900 px-4 py-2 text-sm font-semibold text-white hover:bg-slate-800">
            Edit
        </a>
    </div>

    @if (! $firearmQualification)
        <div class="mt-6 rounded-2xl border border-dashed border-slate-300 bg-slate-50 p-8 text-center">
            <h3 class="text-lg font-bold text-slate-900">
                No firearm qualification added yet
            </h3>

            <p class="mt-2 text-sm text-slate-500">
                Add this if you are qualified or authorized for armed security assignments.
            </p>
        </div>
    @else
        <dl class="mt-6 grid gap-6 sm:grid-cols-2">
            <div>
                <dt class="text-sm font-medium text-slate-500">Qualified</dt>
                <dd class="mt-1 text-slate-900">
                    {{ $firearmQualification->is_qualified ? 'Yes' : 'No' }}
                </dd>
            </div>

            <div>
                <dt class="text-sm font-medium text-slate-500">Firearm Type</dt>
                <dd class="mt-1 text-slate-900">
                    {{ $firearmQualification->firearm_type ?? 'Not provided' }}
                </dd>
            </div>

            <div>
                <dt class="text-sm font-medium text-slate-500">Qualification Date</dt>
                <dd class="mt-1 text-slate-900">
                    {{ $firearmQualification->qualified_at?->format('F d, Y') ?? 'Not provided' }}
                </dd>
            </div>

            <div>
                <dt class="text-sm font-medium text-slate-500">Expiry Date</dt>
                <dd class="mt-1 text-slate-900">
                    {{ $firearmQualification->expires_at?->format('F d, Y') ?? 'Not provided' }}
                </dd>
            </div>
        </dl>
    @endif
</section>
