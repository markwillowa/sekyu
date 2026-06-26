@props([
    'href' => null,
])

@if($href)
    <a
        href="{{ $href }}"
        {{ $attributes->merge([
            'class' => 'inline-flex items-center justify-center rounded-xl border border-slate-300 bg-white px-6 py-3 text-sm font-semibold text-slate-700 transition hover:bg-slate-50',
        ]) }}
    >
        {{ $slot }}
    </a>
@else
    <button
        type="button"
        {{ $attributes->merge([
            'class' => 'inline-flex items-center justify-center rounded-xl border border-slate-300 bg-white px-6 py-3 text-sm font-semibold text-slate-700 transition hover:bg-slate-50',
        ]) }}
    >
        {{ $slot }}
    </button>
@endif
