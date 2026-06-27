@extends('layouts.app')

@section('content')
    <section class="bg-slate-50 py-10">
        <div class="mx-auto max-w-6xl px-6">

            <x-framework.layout.page-header
                title="Edit Workflow Template"
                description="Customize your hiring process and its steps."
            >
                <x-slot:actions>
                    <x-framework.buttons.secondary
                        :href="route('agency.workflow-templates.index')"
                    >
                        Back to Templates
                    </x-framework.buttons.secondary>
                </x-slot:actions>
            </x-framework.layout.page-header>

            <form
                id="workflow-template-form"
                method="POST"
                action="{{ route('agency.workflow-templates.update', $workflowTemplate) }}"
                class="space-y-8"
            >
                @csrf
                @method('PUT')

                <x-framework.layout.card>
                    <x-framework.layout.section
                        title="Template Details"
                        description="Basic information about this hiring process."
                    >
                        <x-framework.layout.grid :cols="1">
                            <x-framework.forms.input
                                name="name"
                                label="Template Name"
                                :value="old('name', $workflowTemplate->name)"
                                required
                            />

                            <x-framework.editor.rich-text
                                name="description"
                                label="Description"
                                :value="old('description', $workflowTemplate->description)"
                            />

                            <x-framework.forms.checkbox
                                name="is_default"
                                label="Set as default"
                                :checked="old('is_default', $workflowTemplate->is_default)"
                            />
                        </x-framework.layout.grid>
                    </x-framework.layout.section>
                </x-framework.layout.card>
            </form>

            {{-- Workflow Builder --}}
            <div class="mt-8">
                <x-framework.layout.card>
                    <x-framework.layout.section
                        title="Workflow Steps"
                        description="Drag and drop to reorder. Define the journey from application to deployment."
                    >
                        <div class="space-y-4" id="workflow-steps-list">
                            @forelse($workflowTemplate->steps as $step)
                                <div
                                    data-id="{{ $step->id }}"
                                    class="flex items-center justify-between rounded-lg border border-slate-200 bg-white p-4 shadow-sm"
                                >
                                    <div class="flex items-center gap-4">
                                        <div class="cursor-move text-slate-400">
                                            <x-framework.icon name="bars-3" class="h-5 w-5" />
                                        </div>
                                        <div>
                                            <div class="font-semibold text-slate-900">{{ $step->name }}</div>
                                            <div class="text-xs text-slate-500 uppercase tracking-wider">{{ str_replace('_', ' ', $step->type) }}</div>
                                        </div>
                                    </div>
                                    <div class="flex items-center gap-3">
                                        @if($step->is_terminal)
                                            <x-framework.feedback.badge color="red">Terminal</x-framework.feedback.badge>
                                        @endif

                                        <form action="{{ route('agency.workflow-templates.steps.destroy', [$workflowTemplate, $step]) }}" method="POST">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-slate-400 hover:text-red-600">
                                                <x-framework.icon name="trash" class="h-5 w-5" />
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            @empty
                                <div class="rounded-lg border-2 border-dashed border-slate-200 py-10 text-center text-slate-500">
                                    No steps added yet.
                                </div>
                            @endforelse
                        </div>

                        <hr class="my-8 border-slate-200">

                        {{-- Add Step Form --}}
                        <form action="{{ route('agency.workflow-templates.steps.store', $workflowTemplate) }}" method="POST">
                            @csrf
                            <x-framework.layout.grid :cols="3">
                                <x-framework.forms.select
                                    name="name"
                                    label="Step Name"
                                    :options="$stepNames->pluck('name', 'name')->toArray()"
                                    required
                                />
                                <x-framework.forms.select
                                    name="type"
                                    label="Step Type"
                                    :options="[
                                        'normal' => 'Normal',
                                        'interview' => 'Interview',
                                        'document_request' => 'Document Request',
                                        'medical_exam' => 'Medical Exam',
                                        'training' => 'Training',
                                        'job_offer' => 'Job Offer',
                                        'deployment' => 'Deployment'
                                    ]"
                                    required
                                />
                                <div class="flex items-end pb-1">
                                    <x-framework.buttons.secondary type="submit" class="w-full">
                                        Add Step
                                    </x-framework.buttons.secondary>
                                </div>
                            </x-framework.layout.grid>

                            <div class="mt-4">
                                <x-framework.forms.checkbox
                                    name="is_terminal"
                                    label="This is a terminal step (ends the workflow)"
                                />
                            </div>
                        </form>
                    </x-framework.layout.section>
                </x-framework.layout.card>

                <div class="mt-8 flex items-center gap-3">
                    <x-framework.buttons.primary type="submit" form="workflow-template-form">
                        Update Template
                    </x-framework.buttons.primary>

                    <x-framework.buttons.secondary
                        :href="route('agency.workflow-templates.index')"
                    >
                        Cancel
                    </x-framework.buttons.secondary>
                </div>
            </div>

            {{-- Danger Zone --}}
            <div class="mt-12">
                <x-framework.layout.card class="border-red-100 bg-red-50/30">
                    <x-framework.layout.section
                        title="Danger Zone"
                        description="Irreversible actions for this workflow template."
                    >
                        <div class="flex items-center justify-between">
                            <div>
                                <h4 class="text-sm font-semibold text-slate-900">Delete Template</h4>
                                <p class="text-sm text-slate-500">Once you delete a workflow template, there is no going back. Please be certain.</p>
                            </div>

                            <form
                                action="{{ route('agency.workflow-templates.destroy', $workflowTemplate) }}"
                                method="POST"
                                onsubmit="return confirm('Are you sure you want to delete this template? This cannot be undone.')"
                            >
                                @csrf
                                @method('DELETE')
                                <x-framework.buttons.danger type="submit">
                                    Delete Template
                                </x-framework.buttons.danger>
                            </form>
                        </div>
                    </x-framework.layout.section>
                </x-framework.layout.card>
            </div>
        </div>
    </section>

    @push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.0/Sortable.min.js"></script>
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                var el = document.getElementById('workflow-steps-list');
                if (el) {
                    var sortable = Sortable.create(el, {
                        animation: 150,
                        handle: '.cursor-move',
                        onEnd: function() {
                            var stepIds = Array.from(el.querySelectorAll('[data-id]')).map(function(item) {
                                return item.getAttribute('data-id');
                            });

                            fetch('{{ route('agency.workflow-templates.steps.reorder', $workflowTemplate) }}', {
                                method: 'POST',
                                headers: {
                                    'Content-Type': 'application/json',
                                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                                },
                                body: JSON.stringify({ steps: stepIds })
                            });
                        }
                    });
                }
            });
        </script>
    @endpush
@endsection
