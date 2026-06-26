@props([
    'name',
    'label' => '',
    'value' => '',
    'placeholder' => 'Start writing...',
    'toolbar' => 'full',
])

<div
    class="space-y-2"
    x-data="richEditor({
        content: @js(old($name, $value)),
        placeholder: @js($placeholder),
        toolbar: @js($toolbar),
    })"
>

    @if($label)
        <label class="block text-sm font-semibold text-slate-700">
            {{ $label }}
        </label>
    @endif

    <input
        type="hidden"
        name="{{ $name }}"
        x-model="content"
    >

    <div
        class="sekyu-editor overflow-hidden rounded-2xl border bg-white shadow-sm transition-colors duration-200"
        :class="isFocused ? 'border-blue-400 ring-4 ring-blue-50' : 'border-slate-300'"
    >

        <x-framework.editor.toolbar />

        <div
            x-ref="editor"
            x-on:click.self="focus()"
            class="sekyu-editor-content cursor-text outline-none"
        ></div>

    </div>

    @error($name)
    <p class="text-sm text-red-600">
        {{ $message }}
    </p>
    @enderror

</div>
