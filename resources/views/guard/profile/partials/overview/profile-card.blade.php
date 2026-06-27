<x-framework.layout.card>
    <div class="flex items-start justify-between gap-6">
        <div class="flex gap-5">
            <div class="flex h-24 w-24 shrink-0 items-center justify-center rounded-full bg-slate-900 text-3xl font-bold text-white">
                {{ strtoupper(substr($profile?->first_name ?? $user->name, 0, 1)) }}
            </div>

            <div>
                <h2 class="text-2xl font-bold text-slate-900">
                    {{ trim(($profile?->first_name ?? '') . ' ' . ($profile?->middle_name ?? '') . ' ' . ($profile?->last_name ?? '') . ' ' . ($profile?->suffix ?? '')) ?: $user->name }}
                </h2>

                <p class="mt-1 text-slate-600">
                    {{ $profile?->headline ?? 'Security professional' }}
                </p>

                <p class="mt-1 text-sm text-slate-500">
                    {{ $user->email }}
                </p>

                <div class="mt-4 flex flex-wrap gap-2">
                    <x-framework.feedback.badge color="amber">
                        Guard Account
                    </x-framework.feedback.badge>

                    <x-framework.feedback.badge color="slate">
                        Available for Jobs
                    </x-framework.feedback.badge>
                </div>
            </div>
        </div>

    </div>

    <div class="mt-6 rounded-2xl bg-slate-50 p-5">
        <p class="text-sm font-semibold text-slate-500">
            About
        </p>

        <p class="mt-2 leading-7 text-slate-700">
            {{ $profile?->summary ?? 'No profile summary added yet.' }}
        </p>
    </div>
</x-framework.layout.card>
