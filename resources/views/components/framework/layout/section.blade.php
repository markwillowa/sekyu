@props([
    'title' => null,
    'description' => null,
])

<div {{ $attributes->merge(['class' => 'space-y-6']) }}>
    @if($title)
        <div>
            <h2 class="text-xl font-bold text-slate-900">
                {{ $title }}
            </h2>

            @if($description)
                <p class="mt-2 text-sm text-slate-500">
                    {{ $description }}
                </p>
            @endif
        </div>
    @endif

    <div class="grid gap-6">
        {{ $slot }}
    </div>
</div>
