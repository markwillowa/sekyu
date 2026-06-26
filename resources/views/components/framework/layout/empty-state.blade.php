@props([
    'title',
    'description',
])

<div class="rounded-3xl border border-dashed border-slate-300 bg-white p-12 text-center">
    <div class="mx-auto flex h-16 w-16 items-center justify-center rounded-full bg-slate-100">
        {{ $icon ?? '📄' }}
    </div>

    <h2 class="mt-6 text-2xl font-bold text-slate-900">
        {{ $title }}
    </h2>

    <p class="mt-3 text-slate-600">
        {{ $description }}
    </p>

    @if(isset($actions))
        <div class="mt-8">
            {{ $actions }}
        </div>
    @endif
</div>
