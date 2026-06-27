@extends('layouts.app')

@section('content')
    <section class="bg-slate-50 py-10">
        <div class="mx-auto max-w-7xl px-6">
            <x-framework.layout.page-header
                title="Application Details"
                description="Manage application for {{ $application->applicant->name }} for {{ $application->job->title }}"
            >
                <x-slot:actions>
                    <x-framework.buttons.primary href="{{ route('agency.applications.kanban', ['job_post_id' => $application->job_id]) }}">
                        View in Kanban
                    </x-framework.buttons.primary>
                    <x-framework.buttons.secondary href="{{ route('agency.applications.index') }}">
                        Back to Applications
                    </x-framework.buttons.secondary>
                </x-slot:actions>
            </x-framework.layout.page-header>

            <div class="mt-8 grid grid-cols-1 lg:grid-cols-3 gap-8">
                {{-- Applicant Profile & Info --}}
                <div class="lg:col-span-1 space-y-6">
                    <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm">
                        <div class="flex flex-col items-center mb-6">
                            @if($application->applicant->guardProfile && $application->applicant->guardProfile->hasMedia('profile-photo'))
                                <img src="{{ $application->applicant->guardProfile->getFirstMediaUrl('profile-photo') }}" alt="{{ $application->applicant->name }}" class="h-24 w-24 rounded-full object-cover mb-4 ring-4 ring-blue-50">
                            @else
                                <div class="h-24 w-24 rounded-full bg-blue-100 flex items-center justify-center mb-4 ring-4 ring-blue-50">
                                    <span class="text-blue-600 text-3xl font-bold">{{ substr($application->applicant->name, 0, 1) }}</span>
                                </div>
                            @endif
                            <h3 class="text-xl font-bold text-slate-900">{{ $application->applicant->name }}</h3>
                            <p class="text-slate-500 text-sm text-center">{{ $application->applicant->guardProfile?->headline ?? 'Security Guard' }}</p>

                            <div class="mt-4 flex items-center justify-center w-full">
                                <div class="w-full bg-slate-100 rounded-full h-2">
                                    <div class="bg-blue-600 h-2 rounded-full" style="width: {{ $profileCompletion['percentage'] }}%"></div>
                                </div>
                                <span class="ml-2 text-xs font-bold text-slate-600 whitespace-nowrap">{{ $profileCompletion['percentage'] }}% Complete</span>
                            </div>
                        </div>

                        <div class="space-y-4 border-t border-slate-100 pt-6">
                            <div>
                                <label class="text-xs font-bold text-slate-400 uppercase tracking-wider">Applied For</label>
                                <div class="text-slate-900 font-medium">{{ $application->job->title }}</div>
                            </div>
                            <div>
                                <label class="text-xs font-bold text-slate-400 uppercase tracking-wider">Applied On</label>
                                <div class="text-slate-900 font-medium">{{ $application->applied_at->format('M d, Y') }}</div>
                            </div>
                            <div>
                                <label class="text-xs font-bold text-slate-400 uppercase tracking-wider">Email</label>
                                <div class="text-slate-900 font-medium">{{ $application->applicant->email }}</div>
                            </div>
                        </div>

                        <div class="mt-6 pt-6 border-t border-slate-100">
                            @if($application->applicant->guardProfile)
                                <x-framework.buttons.secondary :href="route('agency.guard-profile.show', $application->applicant->guardProfile)" class="w-full justify-center">
                                    View Full Profile
                                </x-framework.buttons.secondary>
                            @else
                                <div class="text-center py-2 px-4 bg-slate-50 rounded-xl border border-dashed border-slate-200">
                                    <span class="text-xs text-slate-500 font-medium italic">Profile not yet created</span>
                                </div>
                            @endif
                        </div>
                    </div>

                    {{-- Quick Stats / Tags --}}
                    <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm">
                        <h4 class="text-sm font-bold text-slate-900 mb-4 uppercase tracking-wider">Key Qualifications</h4>
                        <div class="space-y-4">
                            <div class="flex items-center justify-between text-sm">
                                <span class="text-slate-500">Experience</span>
                                <span class="font-bold text-slate-900">{{ $application->applicant->guardProfile?->workExperiences?->count() ?? 0 }} Records</span>
                            </div>
                            <div class="flex items-center justify-between text-sm">
                                <span class="text-slate-500">Licenses</span>
                                <span class="font-bold text-slate-900">{{ $application->applicant->guardProfile?->licenses?->count() ?? 0 }} Active</span>
                            </div>
                            <div class="flex items-center justify-between text-sm">
                                <span class="text-slate-500">Certificates</span>
                                <span class="font-bold text-slate-900">{{ $application->applicant->guardProfile?->certifications?->count() ?? 0 }} Verified</span>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Workflow Management --}}
                <div class="lg:col-span-2 space-y-6">
                    {{-- Recruitment Timeline --}}
                    <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm">
                        <div class="flex justify-between items-center mb-6">
                            <h3 class="text-lg font-bold text-slate-900">Application Timeline</h3>
                            <x-framework.feedback.badge color="blue">
                                {{ $application->currentStep->name }}
                            </x-framework.feedback.badge>
                        </div>

                        <div class="relative">
                            <div class="absolute inset-0 flex items-center" aria-hidden="true">
                                <div class="w-full border-t border-slate-200"></div>
                            </div>
                            <div class="relative flex justify-between">
                                @php
                                    $currentIndex = $workflowSteps->search(fn($s) => $s->id === $application->current_workflow_step_id);
                                @endphp
                                @foreach($workflowSteps as $index => $step)
                                    <div class="flex flex-col items-center">
                                        <span @class([
                                            'relative flex h-8 w-8 items-center justify-center rounded-full ring-8 ring-white',
                                            'bg-blue-600 text-white' => $index <= $currentIndex,
                                            'bg-slate-100 text-slate-400' => $index > $currentIndex,
                                        ])>
                                            @if($index < $currentIndex)
                                                <x-framework.icon name="check" class="h-5 w-5" />
                                            @else
                                                <span class="text-xs font-bold">{{ $index + 1 }}</span>
                                            @endif
                                        </span>
                                        <span @class([
                                            'mt-2 text-[10px] font-bold uppercase tracking-tight text-center max-w-[80px]',
                                            'text-blue-600' => $index <= $currentIndex,
                                            'text-slate-400' => $index > $currentIndex,
                                        ])>
                                            {{ $step->name }}
                                        </span>
                                    </div>
                                @endforeach
                            </div>
                        </div>

                        <div class="mt-10 bg-slate-50 p-6 rounded-xl border border-slate-100">
                            <form action="{{ route('agency.applications.move', $application) }}" method="POST" class="grid grid-cols-1 md:grid-cols-3 gap-4 items-end">
                                @csrf
                                <div>
                                    <x-framework.forms.select
                                        label="Move To"
                                        name="workflow_step_id"
                                        id="workflow_step_id"
                                        :options="$workflowSteps->pluck('name', 'id')"
                                        :selected="$application->current_workflow_step_id"
                                    />
                                </div>
                                <div>
                                    <x-framework.forms.input
                                        label="Notes"
                                        name="notes"
                                        id="notes"
                                        placeholder="Optional update notes..."
                                    />
                                </div>
                                <div class="flex items-end">
                                    <x-framework.buttons.primary type="submit" size="sm" class="w-full py-3">
                                        Update Step
                                    </x-framework.buttons.primary>
                                </div>
                            </form>
                        </div>
                    </div>

                    {{-- Applicant Experience & Documents --}}
                    <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm">
                        <div class="border-b border-slate-100 mb-6 pb-2">
                            <nav class="flex space-x-8" aria-label="Tabs">
                                <button class="border-blue-500 text-blue-600 border-b-2 py-4 px-1 text-sm font-bold">Experience</button>
                                <button class="border-transparent text-slate-500 hover:text-slate-700 hover:border-slate-300 border-b-2 py-4 px-1 text-sm font-medium">Certificates</button>
                                <button class="border-transparent text-slate-500 hover:text-slate-700 hover:border-slate-300 border-b-2 py-4 px-1 text-sm font-medium">Documents</button>
                            </nav>
                        </div>

                        <div class="space-y-6">
                            @if($application->applicant->guardProfile)
                                @forelse($application->applicant->guardProfile->workExperiences as $exp)
                                    <div class="flex space-x-4">
                                        <div class="flex-shrink-0 h-10 w-10 rounded-lg bg-slate-100 flex items-center justify-center">
                                            <x-framework.icon name="briefcase" class="h-6 w-6 text-slate-400" />
                                        </div>
                                        <div>
                                            <h4 class="text-sm font-bold text-slate-900">{{ $exp->job_title }}</h4>
                                            <p class="text-xs text-slate-500">{{ $exp->company_name }} • {{ $exp->started_at?->format('M Y') ?? 'N/A' }} - {{ $exp->is_current ? 'Present' : ($exp->ended_at?->format('M Y') ?? 'N/A') }}</p>
                                            @if($exp->description)
                                                <p class="mt-2 text-xs text-slate-600 leading-relaxed">{{ $exp->description }}</p>
                                            @endif
                                        </div>
                                    </div>
                                @empty
                                    <p class="text-sm text-slate-500 italic">No work experience listed.</p>
                                @endforelse
                            @else
                                <p class="text-sm text-slate-500 italic">Profile not yet created.</p>
                            @endif
                        </div>
                    </div>

                    {{-- Certificates & Documents fallbacks --}}
                    <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm hidden" id="certificates-tab">
                        <p class="text-sm text-slate-500 italic">No certificates found.</p>
                    </div>
                    <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm hidden" id="documents-tab">
                        <p class="text-sm text-slate-500 italic">No documents found.</p>
                    </div>

                    {{-- Activity History Feed --}}
                    <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm">
                        <h3 class="text-lg font-bold text-slate-900 mb-6">Activity Logs</h3>
                        <div class="flow-root">
                            <ul role="list" class="-mb-8">
                                @foreach($application->histories as $history)
                                    <li>
                                        <div class="relative pb-8">
                                            @if(!$loop->last)
                                                <span class="absolute top-4 left-4 -ml-px h-full w-0.5 bg-slate-200" aria-hidden="true"></span>
                                            @endif
                                            <div class="relative flex space-x-3">
                                                <div>
                                                    <span class="h-8 w-8 rounded-full bg-blue-50 flex items-center justify-center ring-8 ring-white">
                                                        <x-framework.icon name="check" class="h-5 w-5 text-blue-600" />
                                                    </span>
                                                </div>
                                                <div class="flex min-w-0 flex-1 justify-between space-x-4 pt-1.5">
                                                    <div>
                                                        <p class="text-sm text-slate-500">
                                                            Moved to <span class="font-bold text-slate-900">{{ $history->step->name }}</span>
                                                            @if($history->notes)
                                                                <span class="block mt-1 italic text-xs text-slate-400">"{{ $history->notes }}"</span>
                                                            @endif
                                                        </p>
                                                    </div>
                                                    <div class="whitespace-nowrap text-right text-sm text-slate-500">
                                                        <time datetime="{{ $history->completed_at }}">{{ $history->completed_at?->format('M d, H:i') ?? 'N/A' }}</time>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
