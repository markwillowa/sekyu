<section class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm">
    <div class="flex items-center justify-between border-b border-slate-200 pb-5">
        <div>
            <h2 class="text-xl font-bold text-slate-900">
                Basic Information
            </h2>

            <p class="mt-1 text-sm text-slate-500">
                Your name, birth date, gender, civil status, and nationality.
            </p>
        </div>

        <a
            href="#"
            class="rounded-lg bg-slate-900 px-4 py-2 text-sm font-semibold text-white transition hover:bg-slate-800"
        >
            Edit
        </a>
    </div>

    <dl class="mt-6 grid gap-6 sm:grid-cols-2">
        <div>
            <dt class="text-sm font-medium text-slate-500">First Name</dt>
            <dd class="mt-1 text-slate-900">{{ $profile?->first_name ?? 'Not provided' }}</dd>
        </div>

        <div>
            <dt class="text-sm font-medium text-slate-500">Middle Name</dt>
            <dd class="mt-1 text-slate-900">{{ $profile?->middle_name ?? 'Not provided' }}</dd>
        </div>

        <div>
            <dt class="text-sm font-medium text-slate-500">Last Name</dt>
            <dd class="mt-1 text-slate-900">{{ $profile?->last_name ?? 'Not provided' }}</dd>
        </div>

        <div>
            <dt class="text-sm font-medium text-slate-500">Suffix</dt>
            <dd class="mt-1 text-slate-900">{{ $profile?->suffix ?? 'Not provided' }}</dd>
        </div>

        <div>
            <dt class="text-sm font-medium text-slate-500">Birth Date</dt>
            <dd class="mt-1 text-slate-900">
                {{ $profile?->birth_date?->format('F d, Y') ?? 'Not provided' }}
            </dd>
        </div>

        <div>
            <dt class="text-sm font-medium text-slate-500">Gender</dt>
            <dd class="mt-1 text-slate-900">
                {{ $profile?->gender?->name ?? 'Not provided' }}
            </dd>
        </div>

        <div>
            <dt class="text-sm font-medium text-slate-500">Civil Status</dt>
            <dd class="mt-1 text-slate-900">
                {{ $profile?->civilStatus?->name ?? 'Not provided' }}
            </dd>
        </div>

        <div>
            <dt class="text-sm font-medium text-slate-500">Nationality</dt>
            <dd class="mt-1 text-slate-900">
                {{ $profile?->nationality ?? 'Not provided' }}
            </dd>
        </div>
    </dl>
</section>
