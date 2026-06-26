@props([
    'cols' => 2,
])

@php
    $classes = [
        1 => 'grid-cols-1',
        2 => 'grid-cols-1 lg:grid-cols-2',
        3 => 'grid-cols-1 lg:grid-cols-3',
        4 => 'grid-cols-1 md:grid-cols-2 xl:grid-cols-4',
    ];
@endphp

<div
    {{ $attributes->merge([
        'class' => 'grid gap-6 '.$classes[$cols],
    ]) }}
>
    {{ $slot }}
</div>
