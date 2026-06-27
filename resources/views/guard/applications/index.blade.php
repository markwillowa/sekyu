@extends('layouts.app')

@section('content')
    <section class="bg-slate-50 py-10">
        <div class="mx-auto max-w-7xl px-6">
            <x-framework.layout.page-header
                title="My Applications"
                description="Track the status of your job applications."
            />

            <div class="mt-8">
                @if($applications->isEmpty())
                    <x-framework.layout.empty-state
                        title="No Applications Yet"
                        description="You haven't applied to any jobs yet. Start browsing and find your next career opportunity."
                    >
                        <x-slot:actions>
                            <x-framework.buttons.primary href="{{ route('jobs.index') }}">
                                Browse Jobs
                            </x-framework.buttons.primary>
                        </x-slot:actions>
                    </x-framework.layout.empty-state>
                @else
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        @foreach($applications as $application)
                            <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm hover:shadow-md transition-shadow">
                                <div class="flex justify-between items-start mb-6">
                                    <div class="flex items-center space-x-4">
                                        <div class="h-12 w-12 rounded-xl bg-slate-100 flex items-center justify-center border border-slate-200 overflow-hidden">
                                            @if($application->job->agency->hasMedia('logo'))
                                                <img src="{{ $application->job->agency->getFirstMediaUrl('logo') }}" alt="{{ $application->job->agency->name }}" class="h-full w-full object-cover">
                                            @else
                                                <span class="text-slate-400 font-bold text-xl">{{ substr($application->job->agency->name, 0, 1) }}</span>
                                            @endif
                                        </div>
                                        <div>
                                            <h3 class="font-bold text-slate-900">{{ $application->job->title }}</h3>
                                            <p class="text-sm text-slate-500">{{ $application->job->agency->name }}</p>
                                        </div>
                                    </div>
                                    <x-framework.feedback.badge color="blue">
                                        {{ $application->currentStep->name }}
                                    </x-framework.feedback.badge>
                                </div>

                                <div class="space-y-4 mb-6">
                                    <div class="flex items-center justify-between text-sm">
                                        <span class="text-slate-500">Current Stage</span>
                                        <span class="font-bold text-slate-900">{{ $application->currentStep->name }}</span>
                                    </div>
                                    <div class="w-full bg-slate-100 rounded-full h-2">
                                        @php
                                            $steps = $application->job->workflowTemplate->steps;
                                            $currentIndex = $steps->search(fn($s) => $s->id === $application->current_workflow_step_id);
                                            $progress = count($steps) > 0 ? (($currentIndex + 1) / count($steps)) * 100 : 0;
                                        @endphp
                                        <div class="bg-blue-600 h-2 rounded-full" style="width: {{ $progress }}%"></div>
                                    </div>
                                    <div class="flex items-center text-xs text-slate-400">
                                        <x-framework.icon name="calendar" class="h-3 w-3 mr-1" />
                                        Applied {{ $application->applied_at->diffForHumans() }}
                                    </div>
                                </div>

                                <x-framework.buttons.secondary
                                    :href="route('applicant.applications.show', $application)"
                                    class="w-full justify-center"
                                >
                                    Track Progress
                                </x-framework.buttons.secondary>
                            </div>
                        @endforeach
                    </div>

                    <div class="mt-8">
                        {{ $applications->links() }}
                    </div>
                @endif
            </div>
        </div>
    </section>
@endsection
