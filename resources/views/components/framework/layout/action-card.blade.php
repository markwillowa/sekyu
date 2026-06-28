@props([
    'title',
    'description' => null,
    'href' => '#',
    'icon' => null,
    'disabled' => false,
])

<a
    href="{{ $disabled ? '#' : $href }}"
    @class([
        'group block overflow-hidden rounded-3xl border bg-white p-6 shadow-sm transition duration-200',
        'border-slate-200 hover:-translate-y-1 hover:border-amber-300 hover:shadow-lg' => ! $disabled,
        'cursor-not-allowed border-slate-200 opacity-60' => $disabled,
    ])
>

    <div class="flex items-start gap-4">

        @if($icon)

            <div
                class="flex h-12 w-12 items-center justify-center rounded-2xl bg-slate-100 transition group-hover:bg-amber-100"
            >

                <x-framework.icon
                    :name="$icon"
                    class="h-6 w-6 text-slate-700"
                />

            </div>

        @endif

        <div class="flex-1">

            <h3
                class="font-semibold text-slate-900 transition group-hover:text-amber-700"
            >
                {{ $title }}
            </h3>

            @if($description)

                <p class="mt-2 text-sm leading-6 text-slate-500">

                    {{ $description }}

                </p>

            @endif

        </div>

    </div>

</a>
