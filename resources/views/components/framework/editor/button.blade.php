<button
    type="button"
    x-on:mousedown.prevent="run(tool)"
    :title="title(tool)"
    :class="{
        'bg-blue-600 text-white shadow-sm': active(tool),
        'hover:bg-slate-200 text-slate-600': ! active(tool)
    }"
    class="flex h-8 w-8 items-center justify-center rounded-md transition-colors"
>
    <span
        x-html="icon(tool)"
        class="flex items-center justify-center"
    ></span>
</button>
