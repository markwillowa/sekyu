<div class="flex flex-wrap items-center gap-1 border-b border-slate-200 bg-slate-50 p-1.5">

    <template
        x-for="(group, index) in toolbar"
        :key="index"
    >

        <div class="flex items-center gap-1">

            <template
                x-for="tool in group.items"
                :key="tool"
            >

                <x-framework.editor.button />

            </template>

            <div
                x-show="index < toolbar.length - 1"
                class="mx-1 h-5 w-px bg-slate-300"
            ></div>

        </div>

    </template>

</div>
