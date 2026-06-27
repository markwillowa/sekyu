<x-framework.layout.card>
    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-xl font-bold text-slate-900">
                Profile Completion
            </h2>

            <p class="mt-1 text-sm text-slate-500">
                Complete each profile section to improve your hiring readiness.
            </p>
        </div>

        <div class="text-3xl font-bold text-slate-900">
            {{ $completion }}%
        </div>
    </div>

    <div class="mt-6 h-3 overflow-hidden rounded-full bg-slate-100">
        <div
            class="h-full rounded-full bg-amber-500"
            style="width: {{ $completion }}%;"
        ></div>
    </div>

    <div class="mt-6 space-y-4">
        @foreach ($completionGroups as $group)
            <div class="rounded-2xl border border-slate-200 p-4">
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="font-semibold text-slate-900">
                            {{ str($group['key'])->headline() }}
                        </h3>

                        <p class="mt-1 text-sm text-slate-500">
                            {{ $group['completed'] }} of {{ $group['total'] }} completed
                        </p>
                    </div>

                    <div class="text-sm font-bold text-slate-900">
                        {{ $group['percentage'] }}%
                    </div>
                </div>

                <div class="mt-3 h-2 overflow-hidden rounded-full bg-slate-100">
                    <div
                        class="h-full rounded-full bg-amber-500"
                        style="width: {{ $group['percentage'] }}%;"
                    ></div>
                </div>

                <div class="mt-4 grid gap-2 sm:grid-cols-2">
                    @foreach ($group['items'] as $item)
                        <div class="flex items-center gap-2 text-sm">
                            <span
                                class="flex h-5 w-5 items-center justify-center rounded-full text-xs font-bold
                                {{ $item['complete'] ? 'bg-green-100 text-green-700' : 'bg-slate-200 text-slate-500' }}"
                            >
                                {{ $item['complete'] ? '✓' : '!' }}
                            </span>

                            <span class="text-slate-700">
                                {{ $item['label'] }}
                            </span>
                        </div>
                    @endforeach
                </div>
            </div>
        @endforeach
    </div>
</x-framework.layout.card>
