<x-framework.layout.empty-state
    title="No jobs found"
    description="We couldn't find any jobs matching your current filters. Try adjusting your search or clearing all filters."
>
    <x-slot name="icon">
        <x-framework.icon name="magnifying-glass" class="h-10 w-10 text-slate-400" />
    </x-slot>

    <x-slot name="actions">
        <x-framework.buttons.primary href="{{ route('jobs.index') }}" class="px-8 py-3 rounded-2xl">
            Reset All Filters
        </x-framework.buttons.primary>
    </x-slot>
</x-framework.layout.empty-state>
