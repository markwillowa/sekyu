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
                        <x-framework.feedback.badge color="amber">
                            {{ $completion }}% Profile Complete
                        </x-framework.feedback.badge>

                        <x-framework.feedback.badge color="slate">
                            {{ $workExperiences->count() }} Work Records
                        </x-framework.feedback.badge>

                        <x-framework.feedback.badge color="slate">
                            {{ $licenses->count() }} Licenses
                        </x-framework.feedback.badge>

                        <x-framework.feedback.badge color="slate">
                            {{ $skills->count() }} Skills
                        </x-framework.feedback.badge>
                    </div>
                </div>
            </div>

            <x-framework.layout.card class="w-full sm:max-w-xs" padding="p-4 sm:p-6">
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
                    <x-framework.buttons.primary
                        href="{{ route('applicant.profile.show', 'personal') }}"
                    >
                        Edit Profile
                    </x-framework.buttons.primary>

                    <x-framework.buttons.secondary
                        href="{{ route('applicant.profile.preview') }}"
                    >
                        Preview
                    </x-framework.buttons.secondary>
                </div>
            </x-framework.layout.card>

        </div>
    </div>
</section>
