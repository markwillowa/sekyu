@props([
    'color' => 'slate',
])

@php
    $colors = [
        'slate' => 'bg-slate-100 text-slate-700',
        'green' => 'bg-green-100 text-green-700',
        'amber' => 'bg-amber-100 text-amber-700',
        'red' => 'bg-red-100 text-red-700',
        'blue' => 'bg-blue-100 text-blue-700',
    ];
@endphp

<span
    {{ $attributes->merge([
        'class' => 'inline-flex rounded-full px-3 py-1 text-xs font-semibold '.$colors[$color],
    ]) }}
>
    {{ $slot }}
</span>
