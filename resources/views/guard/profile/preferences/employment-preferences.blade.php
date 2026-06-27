<x-framework.layout.card>
    <div class="flex items-center justify-between border-b border-slate-200 pb-5">
        <div>
            <h2 class="text-xl font-bold text-slate-900">
                Employment Preferences
            </h2>

            <p class="mt-1 text-sm text-slate-500">
                Preferred work type, salary, location, and assignment setup.
            </p>
        </div>

        <x-framework.buttons.primary href="#" size="sm">
            Edit
        </x-framework.buttons.primary>
    </div>

    @if (! $employmentPreference)
        <div class="mt-6 rounded-2xl border border-dashed border-slate-300 bg-slate-50 p-8 text-center">
            <h3 class="text-lg font-bold text-slate-900">
                No employment preferences added yet
            </h3>

            <p class="mt-2 text-sm text-slate-500">
                Add your preferred employment type, shift, salary, and preferred assignment areas.
            </p>
        </div>
    @else
        <dl class="mt-6 grid gap-6 sm:grid-cols-2">
            <div>
                <dt class="text-sm font-medium text-slate-500">
                    Employment Type
                </dt>
                <dd class="mt-1 text-slate-900">
                    {{ $employmentPreference->employmentType?->name ?? 'Not provided' }}
                </dd>
            </div>

            <div>
                <dt class="text-sm font-medium text-slate-500">
                    Preferred Shift
                </dt>
                <dd class="mt-1 text-slate-900">
                    {{ $employmentPreference->shiftType?->name ?? 'Not provided' }}
                </dd>
            </div>

            <div>
                <dt class="text-sm font-medium text-slate-500">
                    Expected Salary
                </dt>
                <dd class="mt-1 text-slate-900">
                    @if ($employmentPreference->expected_salary)
                        ₱{{ number_format($employmentPreference->expected_salary, 2) }}
                    @else
                        Not provided
                    @endif
                </dd>
            </div>

            <div>
                <dt class="text-sm font-medium text-slate-500">
                    Willing to Relocate
                </dt>
                <dd class="mt-1 text-slate-900">
                    {{ $employmentPreference->willing_to_relocate ? 'Yes' : 'No' }}
                </dd>
            </div>

            <div class="sm:col-span-2">
                <dt class="text-sm font-medium text-slate-500">
                    Preferred Locations
                </dt>
                <dd class="mt-1 text-slate-900">
                    {{ $employmentPreference->preferred_locations ?? 'Not provided' }}
                </dd>
            </div>

            <div class="sm:col-span-2">
                <dt class="text-sm font-medium text-slate-500">
                    Notes
                </dt>
                <dd class="mt-1 text-slate-900">
                    {{ $employmentPreference->notes ?? 'Not provided' }}
                </dd>
            </div>
        </dl>
    @endif
</x-framework.layout.card>
