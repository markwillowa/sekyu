@extends('layouts.app')

@section('content')
    <section class="bg-slate-50 py-10">
        <div class="mx-auto max-w-7xl px-6">
            <x-framework.layout.page-header
                title="Job Applications"
                description="Manage all guard applications for your agency's job posts."
            >
                <x-slot:actions>
                    <x-framework.buttons.primary href="{{ route('agency.applications.kanban') }}">
                        Kanban Board
                    </x-framework.buttons.primary>
                </x-slot:actions>
            </x-framework.layout.page-header>

            <div class="mt-8 bg-white p-4 rounded-2xl border border-slate-200 shadow-sm mb-6">
                <form action="{{ route('agency.applications.index') }}" method="GET" class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div>
                        <x-framework.forms.input
                            label="Search Applicant"
                            type="text"
                            name="search"
                            id="search"
                            placeholder="Guard name..."
                            value="{{ request('search') }}"
                        />
                    </div>
                    <div>
                        <x-framework.forms.select
                            label="Filter by Job"
                            name="job_post_id"
                            id="job_post_id"
                            :options="$jobPosts"
                            :selected="request('job_post_id')"
                            placeholder="All Jobs"
                        />
                    </div>
                    <div class="flex items-end space-x-2">
                        <x-framework.buttons.primary type="submit" class="flex-1 justify-center">
                            Filter
                        </x-framework.buttons.primary>
                        @if(request()->anyFilled(['search', 'job_post_id']))
                            <x-framework.buttons.secondary href="{{ route('agency.applications.index') }}" class="flex-1 justify-center">
                                Clear
                            </x-framework.buttons.secondary>
                        @endif
                    </div>
                </form>
            </div>

            <div class="mt-8">
                @if($applications->isEmpty())
                    <x-framework.layout.empty-state
                        title="No Applications Found"
                        description="Try adjusting your search or filters."
                    />
                @else
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        @foreach($applications as $application)
                            <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm hover:shadow-md transition-shadow">
                                <div class="flex justify-between items-start mb-4">
                                    <div>
                                        <h3 class="font-bold text-slate-900 text-lg">{{ $application->applicant->name }}</h3>
                                        <p class="text-slate-500 text-sm">{{ $application->job->title }}</p>
                                    </div>
                                    <x-framework.feedback.badge color="blue">
                                        {{ $application->currentStep->name }}
                                    </x-framework.feedback.badge>
                                </div>

                                <div class="space-y-3 mb-6">
                                    <div class="flex items-center text-sm text-slate-600">
                                        <x-framework.icon name="calendar" class="h-4 w-4 mr-2" />
                                        Applied {{ $application->applied_at->diffForHumans() }}
                                    </div>
                                    @if($application->applicant->guardProfile)
                                        <div class="flex items-center text-sm text-slate-600">
                                            <x-framework.icon name="academic-cap" class="h-4 w-4 mr-2" />
                                            Profile Completion: <span class="ml-1 font-bold text-blue-600">{{ $application->profile_completion }}%</span>
                                        </div>
                                    @endif
                                </div>

                                <x-framework.buttons.secondary
                                    :href="route('agency.applications.show', $application)"
                                    class="w-full justify-center"
                                >
                                    View Details
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
