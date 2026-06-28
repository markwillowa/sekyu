@props([
    'label' => null,
    'name',
    'accept' => 'image/*',
])

<div>
    @if($label)
        <label
            for="{{ $name }}"
            class="mb-2 block text-sm font-semibold text-slate-700"
        >
            {{ $label }}
        </label>
    @endif

    <input
        id="{{ $name }}"
        name="{{ $name }}"
        type="file"
        accept="{{ $accept }}"
        {{ $attributes->merge([
            'class' => 'block w-full text-sm text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-sm file:font-semibold file:bg-amber-50 file:text-amber-700 hover:file:bg-amber-100 cursor-pointer',
        ]) }}
    >

    @error($name)
    <p class="mt-2 text-sm text-red-600">
        {{ $message }}
    </p>
    @enderror
</div>
