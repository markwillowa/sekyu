@props([
    'label' => null,
    'name',
])

<label class="flex items-center gap-3">
    <input
        type="checkbox"
        name="{{ $name }}"
        value="1"
        {{ $attributes->merge([
            'class' => 'h-5 w-5 rounded border-slate-300 text-amber-500 focus:ring-amber-500',
        ]) }}
    >

    @if($label)
        <span class="text-sm font-medium text-slate-700">
            {{ $label }}
        </span>
    @endif
</label>

<x-framework.forms.error
    :name="$name"
/>
