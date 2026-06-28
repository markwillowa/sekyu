@props([
    'color' => 'slate',
])

@php
    $colors = [
        'slate' => 'bg-slate-100 text-slate-700',
        'gray' => 'bg-slate-100 text-slate-700',
        'zinc' => 'bg-zinc-100 text-zinc-700',
        'neutral' => 'bg-neutral-100 text-neutral-700',
        'stone' => 'bg-stone-100 text-stone-700',
        'green' => 'bg-green-100 text-green-700',
        'amber' => 'bg-amber-100 text-amber-700',
        'red' => 'bg-red-100 text-red-700',
        'blue' => 'bg-blue-100 text-blue-700',
        'indigo' => 'bg-indigo-100 text-indigo-700',
        'violet' => 'bg-violet-100 text-violet-700',
        'pink' => 'bg-pink-100 text-pink-700',
    ];
@endphp

<span
    {{ $attributes->merge([
        'class' => 'inline-flex rounded-full px-3 py-1 text-xs font-semibold '.($colors[$color] ?? $colors['slate']),
    ]) }}
>
    {{ $slot }}
</span>
