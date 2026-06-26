@props([
    'label' => null,
    'name',
])

<label class="flex items-center justify-between">
    @if($label)
        <span class="text-sm font-semibold text-slate-700">
            {{ $label }}
        </span>
    @endif

    <input
        type="checkbox"
        name="{{ $name }}"
        value="1"
        class="peer sr-only"
    >

    <div
        class="relative h-6 w-11 rounded-full bg-slate-300 transition peer-checked:bg-amber-500
               after:absolute after:left-1 after:top-1 after:h-4 after:w-4 after:rounded-full
               after:bg-white after:transition-all peer-checked:after:translate-x-5"
    ></div>
</label>

<x-framework.forms.error
    :name="$name"
/>
