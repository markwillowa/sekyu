@props([
    'label' => null,
    'name',
    'options' => [],
    'placeholder' => 'Select an option',
    'selected' => null,
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

    <select
        id="{{ $name }}"
        name="{{ $name }}"
        {{ $attributes->merge([
            'class' => 'block w-full rounded-xl border border-slate-300 bg-white px-4 py-3 shadow-sm transition focus:border-amber-500 focus:outline-none focus:ring-2 focus:ring-amber-500/20',
        ]) }}
    >
        @if($placeholder)
            <option value="">
                {{ $placeholder }}
            </option>
        @endif

        @foreach($options as $value => $text)
            <option
                value="{{ $value }}"
                @selected(old($name, $selected) == $value)
            >
                {{ $text }}
            </option>
        @endforeach

        {{ $slot }}
    </select>

    @error($name)
    <p class="mt-2 text-sm text-red-600">
        {{ $message }}
    </p>
    @enderror
</div>
