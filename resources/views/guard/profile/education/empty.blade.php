<div class="mt-6 rounded-2xl border border-dashed border-slate-300 bg-slate-50 p-8 text-center">
    <h3 class="text-lg font-bold text-slate-900">
        No educational background added yet
    </h3>

    <p class="mx-auto mt-2 max-w-md text-sm leading-6 text-slate-500">
        Add your school, course, years attended, and any academic achievements.
        This helps agencies better understand your qualifications.
    </p>

    <x-framework.buttons.primary
        href="#"
        size="md"
        @click.prevent="$dispatch('open-modal', 'add-education')"
    >
        Add Education
    </x-framework.buttons.primary>
</div>
