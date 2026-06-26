@props([
    'title' => null,
])

<div class="space-y-6">
    @if($title)
        <h3 class="text-lg font-semibold text-slate-900">
            {{ $title }}
        </h3>
    @endif

    {{ $slot }}
</div>
