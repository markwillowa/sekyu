@extends('layouts.app')

@section('content')
    <section class="bg-slate-50 py-10">
        <div class="mx-auto max-w-7xl px-6">
            <x-framework.layout.page-header
                title="Application Timeline"
                description="Tracking your application for {{ $application->job->title }} at {{ $application->job->agency->name }}"
            >
                <x-slot:actions>
                    <x-framework.buttons.secondary href="{{ route('applicant.applications.index') }}">
                        Back to My Applications
                    </x-framework.buttons.secondary>
                </x-slot:actions>
            </x-framework.layout.page-header>

            <div class="mt-8 grid grid-cols-1 lg:grid-cols-3 gap-8">
                {{-- Job Info --}}
                <div class="lg:col-span-1">
                    <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm">
                        <h3 class="text-lg font-bold text-slate-900 mb-4">Job Details</h3>
                        <div class="space-y-4">
                            <div class="flex items-center gap-3">
                                <div class="h-12 w-12 rounded-xl bg-slate-50 border border-slate-100 flex items-center justify-center overflow-hidden">
                                    @if($application->job->agency->logo)
                                        <img src="{{ $application->job->agency->logo }}" alt="{{ $application->job->agency->name }}" class="h-full w-full object-cover">
                                    @else
                                        <x-framework.icon name="building-office" class="h-6 w-6 text-slate-300" />
                                    @endif
                                </div>
                                <div>
                                    <div class="text-slate-900 font-bold">{{ $application->job->agency->name }}</div>
                                    <div class="text-sm text-slate-500">{{ $application->job->location_name ?? 'Location N/A' }}</div>
                                </div>
                            </div>
                            <div class="pt-4 border-t border-slate-100">
                                <label class="text-xs font-bold text-slate-400 uppercase tracking-wider">Position</label>
                                <div class="text-slate-900 font-medium">{{ $application->job->title }}</div>
                            </div>
                            <div>
                                <label class="text-xs font-bold text-slate-400 uppercase tracking-wider">Applied Date</label>
                                <div class="text-slate-900 font-medium">{{ $application->applied_at->format('M d, Y') }}</div>
                            </div>
                        </div>
                        <div class="mt-6 pt-6 border-t border-slate-100">
                             <x-framework.buttons.secondary :href="route('jobs.index', ['job' => $application->job->id])" class="w-full justify-center">
                                View Job Post
                             </x-framework.buttons.secondary>
                        </div>
                    </div>
                </div>

                {{-- Timeline --}}
                <div class="lg:col-span-2">
                    <div class="bg-white p-8 rounded-2xl border border-slate-200 shadow-sm">
                        <div class="flex justify-between items-center mb-8">
                            <h3 class="text-xl font-bold text-slate-900">Application Progress</h3>
                            @php
                                $allSteps = $application->job->workflowTemplate->steps;
                                $currentIndex = $allSteps->search(fn($s) => $s->id === $application->current_workflow_step_id);
                                $totalSteps = count($allSteps);
                                $progressPercent = $totalSteps > 0 ? (($currentIndex + 1) / $totalSteps) * 100 : 0;
                            @endphp
                            <div class="text-right">
                                <span class="text-sm font-bold text-blue-600">{{ round($progressPercent) }}% Progress</span>
                            </div>
                        </div>

                        <div class="w-full bg-slate-100 rounded-full h-3 mb-10 overflow-hidden">
                            <div class="bg-blue-600 h-3 rounded-full transition-all duration-500" style="width: {{ $progressPercent }}%"></div>
                        </div>

                        <nav aria-label="Progress">
                            <ol role="list" class="overflow-hidden">
                                @foreach($allSteps as $index => $step)
                                    @php
                                        $isCurrent = $application->current_workflow_step_id === $step->id;
                                        $isCompleted = $index < $currentIndex;
                                        $isUpcoming = $index > $currentIndex;
                                    @endphp
                                    <li @class(['relative', 'pb-10' => !$loop->last])>
                                        @if(!$loop->last)
                                            <div @class([
                                                'absolute left-4 top-4 -ml-px mt-0.5 h-full w-0.5',
                                                'bg-blue-600' => $isCompleted || $isCurrent,
                                                'bg-slate-200' => $isUpcoming
                                            ]) aria-hidden="true"></div>
                                        @endif

                                        <div class="group relative flex items-start">
                                            <span class="flex h-9 items-center">
                                                @if($isCompleted)
                                                    <span class="relative z-10 flex h-8 w-8 items-center justify-center rounded-full bg-blue-600 ring-4 ring-blue-50">
                                                        <x-framework.icon name="check" class="h-4 w-4 text-white" />
                                                    </span>
                                                @elseif($isCurrent)
                                                    <span class="relative z-10 flex h-8 w-8 items-center justify-center rounded-full border-2 border-blue-600 bg-white ring-4 ring-blue-50">
                                                        <span class="h-2.5 w-2.5 rounded-full bg-blue-600 animate-pulse"></span>
                                                    </span>
                                                @else
                                                    <span class="relative z-10 flex h-8 w-8 items-center justify-center rounded-full border-2 border-slate-300 bg-white">
                                                        <span class="h-2 w-2 rounded-full bg-transparent"></span>
                                                    </span>
                                                @endif
                                            </span>
                                            <span class="ml-4 flex min-w-0 flex-col">
                                                <span @class([
                                                    'text-sm font-bold tracking-wide uppercase',
                                                    'text-blue-600' => $isCurrent,
                                                    'text-slate-900' => $isCompleted,
                                                    'text-slate-400' => $isUpcoming
                                                ])>
                                                    {{ $step->name }}
                                                </span>
                                                @if($isCurrent)
                                                    <span class="text-sm text-slate-500 mt-1">Your application is currently being reviewed at this stage.</span>
                                                @elseif($isCompleted)
                                                     @php
                                                        $history = $application->histories->where('workflow_step_id', $step->id)->last();
                                                     @endphp
                                                     <span class="text-xs text-slate-400 mt-1">Passed on {{ $history?->completed_at->format('M d, Y') }}</span>
                                                @else
                                                     <span class="text-xs text-slate-300 mt-1">Pending previous stages</span>
                                                @endif
                                            </span>
                                        </div>
                                    </li>
                                @endforeach
                            </ol>
                        </nav>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
