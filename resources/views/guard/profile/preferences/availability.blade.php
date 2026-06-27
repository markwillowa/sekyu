<x-framework.layout.card>
    <div class="flex items-center justify-between border-b border-slate-200 pb-5">
        <div>
            <h2 class="text-xl font-bold text-slate-900">
                Availability
            </h2>

            <p class="mt-1 text-sm text-slate-500">
                Your availability for work, deployment, and preferred schedules.
            </p>
        </div>

        <x-framework.buttons.primary href="#" size="sm">
            Edit
        </x-framework.buttons.primary>
    </div>

    @if (! $availability)
        <div class="mt-6 rounded-2xl border border-dashed border-slate-300 bg-slate-50 p-8 text-center">
            <h3 class="text-lg font-bold text-slate-900">
                No availability added yet
            </h3>

            <p class="mt-2 text-sm text-slate-500">
                Add when you are available to start work and what schedules you can accept.
            </p>
        </div>
    @else
        <dl class="mt-6 grid gap-6 sm:grid-cols-2">
            <div>
                <dt class="text-sm font-medium text-slate-500">
                    Available From
                </dt>
                <dd class="mt-1 text-slate-900">
                    {{ $availability->available_from?->format('F d, Y') ?? 'Not provided' }}
                </dd>
            </div>

            <div>
                <dt class="text-sm font-medium text-slate-500">
                    Immediate Availability
                </dt>
                <dd class="mt-1 text-slate-900">
                    {{ $availability->is_immediately_available ? 'Yes' : 'No' }}
                </dd>
            </div>

            <div>
                <dt class="text-sm font-medium text-slate-500">
                    Can Work Weekends
                </dt>
                <dd class="mt-1 text-slate-900">
                    {{ $availability->can_work_weekends ? 'Yes' : 'No' }}
                </dd>
            </div>

            <div>
                <dt class="text-sm font-medium text-slate-500">
                    Can Work Holidays
                </dt>
                <dd class="mt-1 text-slate-900">
                    {{ $availability->can_work_holidays ? 'Yes' : 'No' }}
                </dd>
            </div>

            <div class="sm:col-span-2">
                <dt class="text-sm font-medium text-slate-500">
                    Notes
                </dt>
                <dd class="mt-1 text-slate-900">
                    {{ $availability->notes ?? 'Not provided' }}
                </dd>
            </div>
        </dl>
    @endif
</x-framework.layout.card>
