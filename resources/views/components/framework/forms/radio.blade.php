@props([
    'label',
    'name',
    'value',
])

<label class="flex items-center gap-3">
    <input
        type="radio"
        name="{{ $name }}"
        value="{{ $value }}"
        {{ $attributes->merge([
            'class' => 'h-5 w-5 border-slate-300 text-amber-500 focus:ring-amber-500',
        ]) }}
    >

    <span class="text-sm font-medium text-slate-700">
        {{ $label }}
    </span>
</label>
