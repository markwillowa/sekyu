@props([
    'title' => null,
    'description' => null,
])

<div class="mb-8 flex flex-col justify-between gap-6 md:flex-row md:items-center">
    @if($title)
        <div>
            <h1 class="text-3xl font-bold tracking-tight text-slate-900">
                {{ $title }}
            </h1>

            @if($description)
                <p class="mt-2 text-slate-600">
                    {{ $description }}
                </p>
            @endif
        </div>
    @endif

    @if(isset($actions))
        <div class="flex items-center gap-3">
            {{ $actions }}
        </div>
    @endif
</div>
