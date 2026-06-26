<section class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm">
    <div class="flex items-center justify-between border-b border-slate-200 pb-5">
        <div>
            <h2 class="text-xl font-bold text-slate-900">
                Contact Details
            </h2>

            <p class="mt-1 text-sm text-slate-500">
                Your phone numbers, email, and address information.
            </p>
        </div>

        <a href="#" class="rounded-lg bg-slate-900 px-4 py-2 text-sm font-semibold text-white hover:bg-slate-800">
            Edit
        </a>
    </div>

    <dl class="mt-6 grid gap-6 sm:grid-cols-2">
        <div>
            <dt class="text-sm font-medium text-slate-500">Mobile Number</dt>
            <dd class="mt-1 text-slate-900">{{ $contactDetails?->mobile_number ?? 'Not provided' }}</dd>
        </div>

        <div>
            <dt class="text-sm font-medium text-slate-500">Alternate Number</dt>
            <dd class="mt-1 text-slate-900">{{ $contactDetails?->alternate_number ?? 'Not provided' }}</dd>
        </div>

        <div>
            <dt class="text-sm font-medium text-slate-500">Email Address</dt>
            <dd class="mt-1 text-slate-900">{{ $user->email }}</dd>
        </div>

        <div class="sm:col-span-2">
            <dt class="text-sm font-medium text-slate-500">Current Address</dt>
            <dd class="mt-1 text-slate-900">{{ $contactDetails?->current_address ?? 'Not provided' }}</dd>
        </div>

        <div class="sm:col-span-2">
            <dt class="text-sm font-medium text-slate-500">Permanent Address</dt>
            <dd class="mt-1 text-slate-900">{{ $contactDetails?->permanent_address ?? 'Not provided' }}</dd>
        </div>
    </dl>
</section>
