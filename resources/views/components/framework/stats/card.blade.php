@props([
    'title',
    'value',
    'description' => null,
    'color' => 'slate',
])

@php
    $colors = [
        'slate' => [
            'icon' => 'bg-slate-100 text-slate-700',
            'border' => 'border-slate-200',
        ],
        'amber' => [
            'icon' => 'bg-amber-100 text-amber-700',
            'border' => 'border-amber-200',
        ],
        'green' => [
            'icon' => 'bg-green-100 text-green-700',
            'border' => 'border-green-200',
        ],
        'blue' => [
            'icon' => 'bg-blue-100 text-blue-700',
            'border' => 'border-blue-200',
        ],
        'red' => [
            'icon' => 'bg-red-100 text-red-700',
            'border' => 'border-red-200',
        ],
    ];
@endphp

<div
    class="rounded-3xl border {{ $colors[$color]['border'] }} bg-white p-6 shadow-sm transition hover:shadow-md"
>
    <div class="flex items-start justify-between">
        <div>
            <p class="text-sm font-medium text-slate-500">
                {{ $title }}
            </p>

            <h2 class="mt-3 text-4xl font-bold tracking-tight text-slate-900">
                {{ $value }}
            </h2>

            @if($description)
                <p class="mt-3 text-sm text-slate-500">
                    {{ $description }}
                </p>
            @endif
        </div>

        <div
            class="flex h-12 w-12 items-center justify-center rounded-2xl {{ $colors[$color]['icon'] }}"
        >
            {{ $icon ?? '📊' }}
        </div>
    </div>
</div>
