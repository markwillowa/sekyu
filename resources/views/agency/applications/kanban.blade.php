@extends('layouts.app')

@section('content')
    <section class="bg-slate-50 py-10 min-h-screen">
        <div class="mx-auto max-w-full px-6">
            <x-framework.layout.page-header
                title="Recruitment Kanban"
                description="Drag and drop applicants to update their status."
            >
                <x-slot:actions>
                    <x-framework.buttons.secondary href="{{ route('agency.applications.index') }}">
                        Switch to List View
                    </x-framework.buttons.secondary>
                </x-slot:actions>
            </x-framework.layout.page-header>

            <div class="mt-8 bg-white p-4 rounded-2xl border border-slate-200 shadow-sm mb-6 flex items-center justify-between">
                <form action="{{ route('agency.applications.kanban') }}" method="GET" class="flex items-center space-x-4">
                    <label for="job_post_id" class="text-sm font-bold text-slate-700">View for Job:</label>
                    <select name="job_post_id" id="job_post_id" onchange="this.form.submit()" class="rounded-xl border-slate-200 focus:ring-blue-500 focus:border-blue-500 text-sm min-w-[300px]">
                        @foreach($jobPosts as $id => $title)
                            <option value="{{ $id }}" {{ $selectedJobId == $id ? 'selected' : '' }}>
                                {{ $title }}
                            </option>
                        @endforeach
                    </select>
                </form>

                <div class="flex items-center space-x-2 text-xs text-slate-500">
                    <span class="inline-block w-3 h-3 bg-blue-600 rounded-full"></span>
                    <span>Active Workflow: {{ $workflowSteps->count() }} Stages</span>
                </div>
            </div>

            @if($selectedJobId && $workflowSteps->isNotEmpty())
                <div class="mt-8 overflow-x-auto pb-6">
                    <div class="flex space-x-6 min-w-max">
                        @foreach($workflowSteps as $step)
                            <div class="w-80 flex-shrink-0">
                                <div class="bg-slate-100 p-3 rounded-t-xl border-t border-x border-slate-200 flex justify-between items-center">
                                    <h3 class="font-bold text-slate-900 text-sm uppercase tracking-wider">{{ $step->name }}</h3>
                                    <span class="bg-white px-2 py-0.5 rounded-full text-[10px] font-bold text-slate-500 border border-slate-200">
                                        {{ $applicationsByStep->get($step->id)?->count() ?? 0 }}
                                    </span>
                                </div>
                                <div
                                    class="bg-slate-50 p-3 rounded-b-xl border border-slate-200 min-h-[500px] space-y-4"
                                    id="step-{{ $step->id }}"
                                    onurl-drop="handleDrop(event, {{ $step->id }})"
                                    onurl-dragover="handleDragOver(event)"
                                >
                                    @forelse($applicationsByStep->get($step->id) ?? [] as $application)
                                        <div
                                            class="bg-white p-4 rounded-xl border border-slate-200 shadow-sm hover:shadow-md transition-shadow cursor-move"
                                            draggable="true"
                                            onurl-dragstart="handleDragStart(event, {{ $application->id }})"
                                        >
                                            <div class="flex flex-col">
                                                <div class="font-bold text-slate-900 text-sm">{{ $application->applicant->name }}</div>
                                                <div class="text-[10px] text-slate-500 mt-1">Applied {{ $application->applied_at->diffForHumans() }}</div>

                                                <div class="mt-4 pt-4 border-t border-slate-50 flex justify-between items-center">
                                                    <a href="{{ route('agency.applications.show', $application) }}" class="text-[10px] font-bold text-blue-600 hover:text-blue-700">View Details</a>
                                                    <div class="flex -space-x-2">
                                                        @if($application->applicant->guardProfile && $application->applicant->guardProfile->hasMedia('profile-photo'))
                                                            <img src="{{ $application->applicant->guardProfile->getFirstMediaUrl('profile-photo') }}" alt="" class="h-6 w-6 rounded-full ring-2 ring-white object-cover">
                                                        @else
                                                            <div class="h-6 w-6 rounded-full bg-blue-100 flex items-center justify-center ring-2 ring-white text-[8px] font-bold text-blue-600">
                                                                {{ substr($application->applicant->name, 0, 1) }}
                                                            </div>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @empty
                                        <div class="text-center py-10 text-slate-400 text-xs border-2 border-dashed border-slate-200 rounded-xl">
                                            No applicants here
                                        </div>
                                    @endforelse
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @else
                <x-framework.layout.empty-state
                    title="No Job Selected or No Workflow Found"
                    description="Select a job with an active workflow template to see the Kanban board."
                />
            @endif
        </div>
    </section>

    <script>
        function handleDragStart(e, applicationId) {
            e.dataTransfer.setData('application_id', applicationId);
            e.currentTarget.classList.add('opacity-50');
        }

        function handleDragOver(e) {
            e.preventDefault();
            e.dataTransfer.dropEffect = 'move';
        }

        function handleDrop(e, stepId) {
            e.preventDefault();
            const applicationId = e.dataTransfer.getData('application_id');

            // Call API to update application step
            fetch(`/agency/applications/${applicationId}/move`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({
                    workflow_step_id: stepId,
                    notes: 'Moved via Kanban board'
                })
            }).then(response => response.json())
            .then(data => {
                if (data.next_action) {
                    // If specialized action is needed, redirect to the application show page
                    // The backend will set the flash message for trigger_modal
                    window.location.href = `/agency/applications/${applicationId}?trigger_modal=` + getModalName(data.next_action);
                } else {
                    window.location.reload();
                }
            }).catch(error => {
                console.error('Error:', error);
                alert('Failed to move applicant. Please try again.');
            });
        }

        function getModalName(action) {
            switch(action) {
                case 'interview': return 'schedule-interview';
                case 'job_offer': return 'create-job-offer';
                case 'document_request': return 'request-documents';
                default: return '';
            }
        }

        // Add event listeners for native drag and drop since I used onurl- prefix to avoid issues if any
        document.addEventListener('DOMContentLoaded', () => {
            document.querySelectorAll('[draggable="true"]').forEach(el => {
                el.addEventListener('dragstart', (e) => {
                    const appId = el.getAttribute('onurl-dragstart').match(/\d+/)[0];
                    handleDragStart(e, appId);
                });
                el.addEventListener('dragend', (e) => {
                    e.currentTarget.classList.remove('opacity-50');
                });
            });

            document.querySelectorAll('[id^="step-"]').forEach(el => {
                el.addEventListener('dragover', handleDragOver);
                el.addEventListener('drop', (e) => {
                    const stepId = el.getAttribute('id').split('-')[1];
                    handleDrop(e, stepId);
                });
            });
        });
    </script>
@endsection
