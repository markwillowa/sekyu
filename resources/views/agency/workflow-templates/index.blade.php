@extends('layouts.app')

@section('content')
    <section class="bg-slate-50 py-10">
        <div class="mx-auto max-w-7xl px-6">

            <x-framework.layout.page-header
                title="Workflow Templates"
                description="Create and manage reusable hiring processes for your job posts."
            >
                <x-slot:actions>
                    <x-framework.buttons.primary
                        :href="route('agency.workflow-templates.create')"
                    >
                        <x-framework.icon
                            name="plus"
                            class="mr-2 h-5 w-5"
                        />

                        Create Template
                    </x-framework.buttons.primary>
                </x-slot:actions>
            </x-framework.layout.page-header>

            @if($templates->isEmpty())

                <x-framework.layout.empty-state
                    title="No Workflow Templates Yet"
                    description="Standardize your recruitment by creating reusable templates."
                >
                    <x-slot:actions>
                        <x-framework.buttons.primary
                            :href="route('agency.workflow-templates.create')"
                        >
                            Create Template
                        </x-framework.buttons.primary>
                    </x-slot:actions>
                </x-framework.layout.empty-state>

            @else

                <x-framework.table.table>

                    <x-framework.table.head>
                        <tr>
                            <x-framework.table.th>Template Name</x-framework.table.th>
                            <x-framework.table.th>Steps</x-framework.table.th>
                            <x-framework.table.th>Default</x-framework.table.th>
                            <x-framework.table.th>Created</x-framework.table.th>
                            <x-framework.table.th class="text-right">
                                Actions
                            </x-framework.table.th>
                        </tr>
                    </x-framework.table.head>

                    <x-framework.table.body>

                        @foreach($templates as $template)

                            <x-framework.table.row>

                                <x-framework.table.td>

                                    <div class="font-semibold text-slate-900">
                                        {{ $template->name }}
                                    </div>

                                    <div class="mt-1 text-xs text-slate-500">
                                        {{ Str::limit($template->description, 60) }}
                                    </div>

                                </x-framework.table.td>

                                <x-framework.table.td>
                                    <x-framework.feedback.badge color="blue">
                                        {{ $template->steps_count }} Steps
                                    </x-framework.feedback.badge>
                                </x-framework.table.td>

                                <x-framework.table.td>
                                    @if($template->is_default)
                                        <x-framework.feedback.badge color="green">
                                            Default
                                        </x-framework.feedback.badge>
                                    @else
                                        <span class="text-xs text-slate-400">No</span>
                                    @endif
                                </x-framework.table.td>

                                <x-framework.table.td>
                                    {{ $template->created_at->format('M d, Y') }}
                                </x-framework.table.td>

                                <x-framework.table.td>

                                    <x-framework.table.actions>

                                        <x-framework.buttons.secondary
                                            :href="route('agency.workflow-templates.edit', $template)"
                                            size="sm"
                                        >
                                            <x-framework.icon name="pencil-square" class="mr-1.5 h-3.5 w-3.5" />
                                            Edit
                                        </x-framework.buttons.secondary>

                                    </x-framework.table.actions>

                                </x-framework.table.td>

                            </x-framework.table.row>

                        @endforeach

                    </x-framework.table.body>

                </x-framework.table.table>

                <div class="mt-6">
                    {{ $templates->links() }}
                </div>

            @endif

        </div>
    </section>
@endsection
