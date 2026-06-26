<section class="border-b border-slate-200 bg-white">
    <div class="mx-auto max-w-7xl px-4 py-6 sm:px-6 sm:py-8 lg:py-10">
        <div class="flex flex-col gap-6 lg:flex-row lg:items-center lg:justify-between">

            <div class="flex flex-col items-center text-center sm:flex-row sm:items-start sm:text-left sm:gap-6">
                <div class="flex h-20 w-20 shrink-0 items-center justify-center rounded-full bg-slate-900 text-2xl font-bold text-white sm:h-24 sm:w-24 sm:text-3xl">
                    {{ strtoupper(substr($profile?->first_name ?? $user->name, 0, 1)) }}
                </div>

                <div class="mt-4 min-w-0 sm:mt-0">
                    <p class="text-xs font-semibold uppercase tracking-wider text-amber-600 sm:text-sm">
                        Guard Profile
                    </p>

                    <h1 class="mt-1 break-words text-2xl font-bold tracking-tight text-slate-900 sm:text-4xl">
                        {{ trim(
                            ($profile?->first_name ?? '') . ' ' .
                            ($profile?->middle_name ?? '') . ' ' .
                            ($profile?->last_name ?? '') . ' ' .
                            ($profile?->suffix ?? '')
                        ) ?: $user->name }}
                    </h1>

                    <p class="mt-2 break-words text-base text-slate-600 sm:text-lg">
                        {{ $profile?->headline ?: 'Professional Security Guard' }}
                    </p>

                    <div class="mt-4 flex flex-wrap justify-center gap-2 sm:justify-start">
                        <span class="rounded-full bg-amber-100 px-3 py-1 text-xs font-semibold text-amber-700">
                            {{ $completion }}% Profile Complete
                        </span>

                        <span class="rounded-full bg-slate-100 px-3 py-1 text-xs font-semibold text-slate-700">
                            {{ $workExperiences->count() }} Work Records
                        </span>

                        <span class="rounded-full bg-slate-100 px-3 py-1 text-xs font-semibold text-slate-700">
                            {{ $licenses->count() }} Licenses
                        </span>

                        <span class="rounded-full bg-slate-100 px-3 py-1 text-xs font-semibold text-slate-700">
                            {{ $skills->count() }} Skills
                        </span>
                    </div>
                </div>
            </div>

            <div class="w-full rounded-xl border border-slate-200 bg-slate-50 p-4 sm:max-w-xs sm:bg-white sm:p-0 sm:border-0 lg:max-w-xs">
                <div class="flex items-center justify-between">
                    <span class="text-sm font-semibold text-slate-600">
                        Profile Strength
                    </span>

                    <span class="text-sm font-bold text-slate-900">
                        {{ $completion }}%
                    </span>
                </div>

                <div class="mt-3 h-2 overflow-hidden rounded-full bg-slate-100">
                    <div
                        class="h-full rounded-full bg-amber-500"
                        style="width: {{ $completion }}%;"
                    ></div>
                </div>

                <div class="mt-4 grid grid-cols-1 gap-3 sm:grid-cols-2">
                    <a
                        href="{{ route('guard.profile.show', 'personal') }}"
                        class="rounded-lg bg-slate-900 px-4 py-2.5 text-center text-sm font-semibold text-white transition hover:bg-slate-800"
                    >
                        Edit Profile
                    </a>

                    <a
                        href="{{ route('guard.profile.preview') }}"
                        class="rounded-lg border border-slate-300 bg-white px-4 py-2.5 text-center text-sm font-semibold text-slate-700 transition hover:bg-slate-50"
                    >
                        Preview
                    </a>
                </div>
            </div>

        </div>
    </div>
</section>
