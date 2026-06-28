@extends('layouts.app')

@section('content')
    <section class="bg-slate-50 py-10" x-data="messaging({{ $application->id }})" x-init="
        @if(session('trigger_modal'))
            $dispatch('open-modal', '{{ session('trigger_modal') }}');
        @elseif(request('trigger_modal'))
            $dispatch('open-modal', '{{ request('trigger_modal') }}');
        @endif
    ">
        <div class="mx-auto max-w-7xl px-6">
            <x-framework.layout.page-header
                title="Application Details"
                description="Manage application for {{ $application->applicant->name }} for {{ $application->job->title }}"
            >
                <x-slot:actions>
                    <x-framework.buttons.primary @click="openMessages()">
                        Messages
                    </x-framework.buttons.primary>
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
                    <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm" x-data="{ activeTab: 'experience' }">
                        <div class="border-b border-slate-100 mb-6 pb-2">
                            <nav class="flex space-x-8" aria-label="Tabs">
                                <button
                                    @click="activeTab = 'experience'"
                                    :class="activeTab === 'experience' ? 'border-blue-500 text-blue-600' : 'border-transparent text-slate-500 hover:text-slate-700 hover:border-slate-300'"
                                    class="border-b-2 py-4 px-1 text-sm font-bold"
                                >
                                    Experience
                                </button>
                                <button
                                    @click="activeTab = 'certificates'"
                                    :class="activeTab === 'certificates' ? 'border-blue-500 text-blue-600' : 'border-transparent text-slate-500 hover:text-slate-700 hover:border-slate-300'"
                                    class="border-b-2 py-4 px-1 text-sm font-medium"
                                >
                                    Certificates
                                </button>
                                <button
                                    @click="activeTab = 'documents'"
                                    :class="activeTab === 'documents' ? 'border-blue-500 text-blue-600' : 'border-transparent text-slate-500 hover:text-slate-700 hover:border-slate-300'"
                                    class="border-b-2 py-4 px-1 text-sm font-medium"
                                >
                                    Documents
                                </button>
                                <button
                                    @click="activeTab = 'job-offer'"
                                    :class="activeTab === 'job-offer' ? 'border-blue-500 text-blue-600' : 'border-transparent text-slate-500 hover:text-slate-700 hover:border-slate-300'"
                                    class="border-b-2 py-4 px-1 text-sm font-medium"
                                >
                                    Job Offer
                                </button>
                            </nav>
                        </div>

                        <div class="space-y-6" x-show="activeTab === 'experience'">
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

                        {{-- Certificates & Documents fallbacks --}}
                        <div x-show="activeTab === 'certificates'">
                            <p class="text-sm text-slate-500 italic">No certificates found.</p>
                        </div>
                        <div x-show="activeTab === 'documents'">
                            <p class="text-sm text-slate-500 italic">No documents found.</p>
                        </div>

                        {{-- Job Offer Tab --}}
                        <div x-show="activeTab === 'job-offer'">
                            <div class="flex justify-between items-center mb-6">
                                <h3 class="text-lg font-bold text-slate-900">Job Offer</h3>
                                @if(!$application->jobOffer)
                                    <x-framework.buttons.primary size="sm" x-on:click="$dispatch('open-modal', 'create-job-offer')">
                                        Generate Offer Letter
                                    </x-framework.buttons.primary>
                                @endif
                            </div>

                            @if($application->jobOffer)
                                <div class="p-6 rounded-xl border border-slate-100 bg-slate-50">
                                    <div class="flex justify-between items-start mb-6">
                                        <div>
                                            <div class="text-xs text-slate-400 font-bold uppercase tracking-wider mb-1">Offer Number</div>
                                            <div class="text-lg font-bold text-slate-900">{{ $application->jobOffer->offer_number }}</div>
                                        </div>
                                        <x-framework.feedback.badge :color="match($application->jobOffer->status?->code) {
                                            'draft' => 'gray',
                                            'sent' => 'blue',
                                            'accepted' => 'green',
                                            'declined' => 'red',
                                            default => 'slate'
                                        }">
                                            {{ $application->jobOffer->status?->name ?? 'Unknown' }}
                                        </x-framework.feedback.badge>
                                    </div>

                                    <div class="grid grid-cols-2 md:grid-cols-3 gap-6 mb-6">
                                        <div>
                                            <span class="text-xs text-slate-400 block uppercase font-bold tracking-tighter">Monthly Salary</span>
                                            <span class="text-slate-900 font-bold">₱{{ number_format($application->jobOffer->salary, 2) }}</span>
                                        </div>
                                        <div>
                                            <span class="text-xs text-slate-400 block uppercase font-bold tracking-tighter">Start Date</span>
                                            <span class="text-slate-900 font-bold">{{ $application->jobOffer->start_date->format('M d, Y') }}</span>
                                        </div>
                                        <div>
                                            <span class="text-xs text-slate-400 block uppercase font-bold tracking-tighter">Employment Type</span>
                                            <span class="text-slate-900 font-bold">{{ $application->jobOffer->employmentType?->name ?? 'Not set' }}</span>
                                        </div>
                                        <div class="col-span-2">
                                            <span class="text-xs text-slate-400 block uppercase font-bold tracking-tighter">Location</span>
                                            <span class="text-slate-900 font-bold">{{ $application->jobOffer->location?->name ?? 'Not set' }}</span>
                                        </div>
                                    </div>

                                    @if($application->jobOffer->benefits)
                                        <div class="mb-6">
                                            <span class="text-xs text-slate-400 block uppercase font-bold tracking-tighter">Benefits</span>
                                            <p class="text-sm text-slate-700 whitespace-pre-line">{{ $application->jobOffer->benefits }}</p>
                                        </div>
                                    @endif

                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8 pt-6 border-t border-slate-200">
                                        <div>
                                            <h4 class="text-sm font-bold text-slate-900 mb-4">Actions</h4>
                                            <div class="flex items-center gap-4">
                                                @if($application->jobOffer->status?->code === 'draft')
                                                    <form action="{{ route('agency.offers.send', $application->jobOffer) }}" method="POST">
                                                        @csrf
                                                        <x-framework.buttons.primary type="submit" size="sm">
                                                            Send to Applicant
                                                        </x-framework.buttons.primary>
                                                    </form>
                                                @endif
                                            </div>
                                        </div>

                                        <div>
                                            <h4 class="text-sm font-bold text-slate-900 mb-4">Offer Letter PDF</h4>
                                            @if($application->jobOffer->hasMedia('offer_letter'))
                                                <div class="flex items-center gap-4 mb-4">
                                                    <div class="flex items-center p-3 bg-slate-50 rounded-lg border border-slate-200">
                                                        <x-framework.icon name="document-text" class="h-6 w-6 text-slate-400 mr-3" />
                                                        <div class="flex flex-col">
                                                            <span class="text-xs font-bold text-slate-700">{{ $application->jobOffer->getFirstMedia('offer_letter')->file_name }}</span>
                                                            <span class="text-[10px] text-slate-400">{{ $application->jobOffer->getFirstMedia('offer_letter')->human_readable_size }}</span>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endif

                                            <form action="{{ route('agency.offers.upload-pdf', $application->jobOffer) }}" method="POST" enctype="multipart/form-data" class="space-y-3">
                                                @csrf
                                                <div>
                                                    <input type="file" name="offer_letter" accept="application/pdf" class="block w-full text-sm text-slate-500
                                                        file:mr-4 file:py-2 file:px-4
                                                        file:rounded-full file:border-0
                                                        file:text-xs file:font-semibold
                                                        file:bg-blue-50 file:text-blue-700
                                                        hover:file:bg-blue-100
                                                    "/>
                                                    <x-framework.forms.error name="offer_letter" />
                                                </div>
                                                <x-framework.buttons.secondary type="submit" size="sm">
                                                    {{ $application->jobOffer->hasMedia('offer_letter') ? 'Replace PDF' : 'Upload PDF' }}
                                                </x-framework.buttons.secondary>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            @else
                                <div class="text-center py-12 bg-slate-50 rounded-xl border border-dashed border-slate-200">
                                    <x-framework.icon name="document-text" class="h-10 w-10 text-slate-200 mx-auto mb-2" />
                                    <p class="text-sm text-slate-500 italic">No offer letter has been generated for this applicant yet.</p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <div class="mt-8 space-y-8">
                {{-- Interviews Section - Now occupying entire row --}}
                <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm">
                    <div class="flex justify-between items-center mb-6">
                        <h3 class="text-lg font-bold text-slate-900">Interviews</h3>
                        @if($application->currentStep->type === 'interview')
                            <button
                                x-data=""
                                x-on:click="$dispatch('open-modal', 'schedule-interview')"
                                class="text-sm font-bold text-blue-600 hover:text-blue-700"
                            >
                                + Schedule Interview
                            </button>
                        @endif
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        @forelse($application->interviews as $interview)
                            <div class="p-6 rounded-xl border border-slate-100 bg-slate-50 flex flex-col justify-between">
                                <div>
                                    <div class="flex justify-between items-start mb-4">
                                        <div>
                                            <h4 class="font-bold text-slate-900">{{ $interview->title }}</h4>
                                            <p class="text-xs text-slate-500">{{ $interview->scheduled_at->format('M d, Y @ h:i A') }} ({{ $interview->duration_minutes }} mins)</p>
                                        </div>
                                        <x-framework.feedback.badge :color="$interview->status === 'scheduled' ? 'blue' : 'gray'">
                                            {{ ucfirst($interview->status) }}
                                        </x-framework.feedback.badge>
                                    </div>
                                    <div class="grid grid-cols-2 gap-4 text-xs mb-6">
                                        <div>
                                            <span class="text-slate-400 block uppercase font-bold tracking-tighter">Type</span>
                                            <span class="text-slate-700">{{ $interview->interviewType?->name ?? 'N/A' }}</span>
                                        </div>
                                        <div>
                                            <span class="text-slate-400 block uppercase font-bold tracking-tighter">Interviewer</span>
                                            <span class="text-slate-700">{{ $interview->interviewer->name }}</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="flex items-center space-x-4 pt-4 border-t border-slate-200/50">
                                    @if($interview->meeting_url)
                                        <a href="{{ $interview->meeting_url }}" target="_blank" class="text-xs text-blue-600 font-bold hover:underline flex items-center">
                                            <x-framework.icon name="link" class="h-3 w-3 mr-1" />
                                            Join Meeting
                                        </a>
                                    @endif
                                    <a href="{{ route('interviews.calendar', $interview) }}" class="text-xs text-slate-600 font-bold hover:underline flex items-center">
                                        <x-framework.icon name="calendar" class="h-3 w-3 mr-1" />
                                        Add to Calendar
                                    </a>
                                </div>
                            </div>
                        @empty
                            <div class="lg:col-span-3 text-center py-12 bg-slate-50 rounded-xl border border-dashed border-slate-200">
                                <x-framework.icon name="calendar" class="h-10 w-10 text-slate-200 mx-auto mb-2" />
                                <p class="text-sm text-slate-500 italic">No interviews scheduled yet.</p>
                            </div>
                        @endforelse
                    </div>
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

        {{-- Messaging Drawer (Details-pane style) --}}
        <div
            x-show="showMessages"
            x-cloak
            class="fixed inset-0 z-[1000]"
            style="display: none;"
        >
            {{-- Backdrop --}}
            <div
                x-show="showMessages"
                x-transition:enter="transition ease-out duration-300"
                x-transition:enter-start="opacity-0"
                x-transition:enter-end="opacity-100"
                x-transition:leave="transition ease-in duration-200"
                x-transition:leave-start="opacity-100"
                x-transition:leave-end="opacity-0"
                class="fixed inset-0 bg-slate-900/30 backdrop-blur-[2px]"
                @click="showMessages = false"
            ></div>

            {{-- Content Drawer --}}
            <div
                x-show="showMessages"
                x-transition:enter="transition ease-out duration-500"
                x-transition:enter-start="translate-x-full"
                x-transition:enter-end="translate-x-0"
                x-transition:leave="transition ease-in duration-500"
                x-transition:leave-start="translate-x-0"
                x-transition:leave-end="translate-x-full"
                class="fixed inset-y-0 right-0 w-full md:w-[85%] lg:w-[70%] bg-white shadow-2xl flex flex-col overflow-hidden"
            >
                <div class="flex-1 overflow-hidden h-full" x-html="messagesHtml" x-show="!loading"></div>
                <div x-show="loading" class="flex-1 flex items-center justify-center">
                    <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-indigo-600"></div>
                </div>
            </div>
        </div>
    </section>

    <x-framework.feedback.modal name="schedule-interview" title="Schedule Interview">
        <form action="{{ route('agency.applications.interviews.store', $application) }}" method="POST" class="space-y-6">
            @csrf
            <x-framework.forms.input
                label="Interview Title"
                name="title"
                placeholder="e.g. Technical Interview"
                value="Technical Interview"
                required
            />

            <div class="grid grid-cols-2 gap-4">
                <x-framework.forms.select
                    label="Interview Type"
                    name="interview_type_id"
                    :options="$interviewTypes->pluck('name', 'id')"
                    required
                />

                <x-framework.forms.select
                    label="Interviewer"
                    name="interviewer_id"
                    :options="[auth()->id() => auth()->user()->name]"
                    :selected="auth()->id()"
                    required
                />
            </div>

            <div class="grid grid-cols-2 gap-4">
                <x-framework.forms.input
                    label="Date & Time"
                    name="scheduled_at"
                    type="datetime-local"
                    required
                />

                <x-framework.forms.input
                    label="Duration (minutes)"
                    name="duration_minutes"
                    type="number"
                    value="30"
                    required
                />
            </div>

            <x-framework.forms.input
                label="Meeting URL / Location"
                name="meeting_url"
                placeholder="Link or address"
            />

            <x-framework.forms.textarea
                label="Notes for Candidate"
                name="notes"
                placeholder="e.g. Bring original documents."
            />

            <div class="flex justify-end gap-3 pt-4">
                <x-framework.buttons.secondary type="button" x-on:click="$dispatch('close-modal', 'schedule-interview')">
                    Cancel
                </x-framework.buttons.secondary>
                <x-framework.buttons.primary type="submit">
                    Save Interview
                </x-framework.buttons.primary>
            </div>
        </form>
    </x-framework.feedback.modal>

    <x-framework.feedback.modal name="create-job-offer" title="Generate Job Offer">
        <form action="{{ route('agency.applications.offers.store', $application) }}" method="POST" class="space-y-6">
            @csrf
            <div class="grid grid-cols-2 gap-4">
                <x-framework.forms.input
                    label="Monthly Salary"
                    name="salary"
                    type="number"
                    step="0.01"
                    placeholder="e.g. 25000"
                    required
                />

                <x-framework.forms.select
                    label="Employment Type"
                    name="employment_type_id"
                    :options="$employmentTypes"
                    required
                />
            </div>

            <div class="grid grid-cols-2 gap-4">
                <x-framework.forms.input
                    label="Start Date"
                    name="start_date"
                    type="date"
                    required
                />

                <x-framework.forms.select
                    label="Location"
                    name="location_id"
                    :options="$locations"
                    :selected="$application->job->location_id"
                    required
                />
            </div>

            <x-framework.forms.textarea
                label="Benefits"
                name="benefits"
                placeholder="List benefits here..."
                rows="3"
            />

            <x-framework.forms.textarea
                label="Remarks"
                name="remarks"
                placeholder="Internal remarks (optional)..."
                rows="2"
            />

            <div class="flex justify-end gap-3 pt-4">
                <x-framework.buttons.secondary type="button" x-on:click="$dispatch('close-modal', 'create-job-offer')">
                    Cancel
                </x-framework.buttons.secondary>
                <x-framework.buttons.primary type="submit">
                    Generate Draft
                </x-framework.buttons.primary>
            </div>
        </form>
    </x-framework.feedback.modal>

    <x-framework.feedback.modal name="request-documents" title="Request Documents">
        <form action="{{ route('agency.applications.move', $application) }}" method="POST" class="space-y-6">
            @csrf
            <input type="hidden" name="workflow_step_id" value="{{ $application->current_workflow_step_id }}">

            <p class="text-sm text-slate-600 mb-4">
                Specify which documents you need the applicant to upload. They will be notified of this request.
            </p>

            <x-framework.forms.textarea
                label="Requested Documents"
                name="notes"
                placeholder="e.g. NBI Clearance, SSS ID, PhilHealth ID..."
                rows="4"
                required
            />

            <div class="flex justify-end gap-3 pt-4">
                <x-framework.buttons.secondary type="button" x-on:click="$dispatch('close-modal', 'request-documents')">
                    Cancel
                </x-framework.buttons.secondary>
                <x-framework.buttons.primary type="submit">
                    Send Request
                </x-framework.buttons.primary>
            </div>
        </form>
    </x-framework.feedback.modal>
@endsection

@push('scripts')
<script>
    document.addEventListener('alpine:init', () => {
        Alpine.data('messaging', (applicationId) => ({
            showMessages: false,
            loading: false,
            messagesHtml: '',
            newMessage: '',
            sending: false,
            conversationId: null,

            async openMessages() {
                this.showMessages = true;
                if (!this.messagesHtml) {
                    await this.loadMessages();
                }
            },

            async loadMessages() {
                this.loading = true;
                try {
                    const response = await fetch(`/agency/applications/${applicationId}/messages`, {
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest',
                            'Accept': 'application/json'
                        }
                    });
                    const data = await response.json();
                    this.messagesHtml = data.html;
                    this.conversationId = data.conversation_id;

                    this.$nextTick(() => {
                        this.scrollToBottom();
                    });
                } catch (e) {
                    console.error('Failed to load messages', e);
                } finally {
                    this.loading = false;
                }
            },

            async sendMessage() {
                if (!this.newMessage.trim() || this.sending) return;

                this.sending = true;
                try {
                    const response = await fetch(`/agency/conversations/${this.conversationId}/messages`, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'X-Requested-With': 'XMLHttpRequest',
                            'Accept': 'application/json'
                        },
                        body: JSON.stringify({ message: this.newMessage })
                    });
                    const data = await response.json();
                    this.messagesHtml = data.html;
                    this.newMessage = '';

                    this.$nextTick(() => {
                        this.scrollToBottom();
                    });
                } catch (e) {
                    console.error('Failed to send message', e);
                } finally {
                    this.sending = false;
                }
            },

            scrollToBottom() {
                const container = document.getElementById(`messages-container-${this.conversationId}`);
                if (container) {
                    container.scrollTop = container.scrollHeight;
                }
            }
        }));
    });
</script>
@endpush
