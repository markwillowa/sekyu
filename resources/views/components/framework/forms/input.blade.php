@props([
    'label',
    'name',
    'type' => 'text',
])

<div>
    <label
        for="{{ $name }}"
        class="mb-2 block text-sm font-semibold text-slate-700"
    >
        {{ $label }}
    </label>

    <input
        id="{{ $name }}"
        name="{{ $name }}"
        type="{{ $type }}"
        value="{{ old($name, $attributes->get('value')) }}"
        {{ $attributes->except('value')->merge([
            'class' => 'block w-full rounded-xl border border-slate-300 px-4 py-3 shadow-sm transition focus:border-amber-500 focus:outline-none focus:ring-2 focus:ring-amber-500/20',
        ]) }}
    >

    @error($name)
    <p class="mt-2 text-sm text-red-600">
        {{ $message }}
    </p>
    @enderror
</div>
