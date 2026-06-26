@props([
    'name',
    'style' => 'o',
    'class' => 'h-5 w-5',
])

@php
    $component = match ($style) {
        's' => 'heroicon-s-' . $name,
        'm' => 'heroicon-m-' . $name,
        default => 'heroicon-o-' . $name,
    };
@endphp

<x-dynamic-component
    :component="$component"
    {{ $attributes->merge([
        'class' => $class,
    ]) }}
/>
