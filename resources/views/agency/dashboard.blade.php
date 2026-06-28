@extends('layouts.app')

@section('content')
    <section class="bg-slate-50 py-10">
        <div class="mx-auto max-w-7xl px-6">

            <x-framework.layout.page-header
                title="Agency Dashboard"
                description="Welcome back, {{ auth()->user()->agency->name }}."
            >
                <x-slot:actions>
                    <x-framework.buttons.primary
                        href="{{ route('agency.job-posts.create') }}"
                    >
                        <x-framework.icon
                            name="plus"
                            class="mr-2 h-5 w-5"
                        />

                        Create Job Post
                    </x-framework.buttons.primary>

                    <x-framework.buttons.secondary
                        href="{{ route('agency.workflow-templates.index') }}"
                    >
                        <x-framework.icon
                            name="arrow-path"
                            class="mr-2 h-5 w-5"
                        />

                        Workflow Templates
                    </x-framework.buttons.secondary>
                </x-slot:actions>
            </x-framework.layout.page-header>

            <x-framework.feedback.alert type="info">
                Welcome to <strong>SEKYU</strong>.

                Start by creating your first job post to begin receiving guard applications.
            </x-framework.feedback.alert>

            <div class="mt-8">
                <x-framework.stats.grid>

                    <x-framework.stats.card
                        title="Active Jobs"
                        :value="$activeJobsCount"
                        color="green"
                    >
                        <x-slot:icon>
                            <x-framework.icon
                                name="briefcase"
                                class="h-6 w-6"
                            />
                        </x-slot:icon>
                    </x-framework.stats.card>

                    <x-framework.stats.card
                        title="Draft Jobs"
                        :value="$draftJobsCount"
                        color="amber"
                    >
                        <x-slot:icon>
                            <x-framework.icon
                                name="document-text"
                                class="h-6 w-6"
                            />
                        </x-slot:icon>
                    </x-framework.stats.card>

                    <x-framework.stats.card
                        title="Applications"
                        :value="$applicationsCount"
                        color="blue"
                        href="{{ route('agency.applications.index') }}"
                    >
                        <x-slot:icon>
                            <x-framework.icon
                                name="users"
                                class="h-6 w-6"
                            />
                        </x-slot:icon>
                    </x-framework.stats.card>

                    <x-framework.stats.card
                        title="Interviews"
                        :value="$interviewsCount"
                        color="slate"
                    >
                        <x-slot:icon>
                            <x-framework.icon
                                name="chat-bubble-left-right"
                                class="h-6 w-6"
                            />
                        </x-slot:icon>
                    </x-framework.stats.card>

                </x-framework.stats.grid>
            </div>

            <div class="mt-10">
                @if($latestJobPosts->isEmpty())
                    <x-framework.layout.empty-state
                        title="No Job Posts Yet"
                        description="Create your first job post and start finding qualified security professionals."
                    >
                        <x-slot:actions>
                            <x-framework.buttons.primary
                                href="{{ route('agency.job-posts.create') }}"
                            >
                                <x-framework.icon
                                    name="plus"
                                    class="mr-2 h-5 w-5"
                                />

                                Create Job Post
                            </x-framework.buttons.primary>
                        </x-slot:actions>
                    </x-framework.layout.empty-state>
                @else
                    <div class="flex items-center justify-between mb-6">
                        <h2 class="text-xl font-bold text-slate-900">Latest Job Posts</h2>
                        <a href="{{ route('agency.job-posts.index') }}" class="text-sm font-medium text-blue-600 hover:text-blue-500">
                            View all jobs &rarr;
                        </a>
                    </div>

                    <x-framework.table.table>
                        <x-framework.table.head>
                            <tr>
                                <x-framework.table.th>Job Title</x-framework.table.th>
                                <x-framework.table.th>Status</x-framework.table.th>
                                <x-framework.table.th>Vacancies</x-framework.table.th>
                                <x-framework.table.th>Posted</x-framework.table.th>
                                <x-framework.table.th class="text-right">Actions</x-framework.table.th>
                            </tr>
                        </x-framework.table.head>

                        <x-framework.table.body>
                            @foreach($latestJobPosts as $job)
                                <x-framework.table.row>
                                    <x-framework.table.td>
                                        <div class="font-semibold text-slate-900">{{ $job->title }}</div>
                                        <div class="mt-1 text-xs text-slate-500">{{ optional($job->employmentType)->name }}</div>
                                    </x-framework.table.td>

                                    <x-framework.table.td>
                                        @php
                                            $color = match(optional($job->status)->code) {
                                                'active' => 'green',
                                                'draft' => 'amber',
                                                default => 'slate'
                                            };
                                        @endphp
                                        <x-framework.feedback.badge :color="$color">
                                            {{ optional($job->status)->name }}
                                        </x-framework.feedback.badge>
                                    </x-framework.table.td>

                                    <x-framework.table.td>{{ $job->vacancies }}</x-framework.table.td>

                                    <x-framework.table.td>
                                        {{ optional($job->published_at)?->format('M d, Y') ?? 'Draft' }}
                                    </x-framework.table.td>

                                    <x-framework.table.td class="text-right">
                                        <x-framework.buttons.secondary
                                            :href="route('agency.job-posts.edit', $job)"
                                            size="sm"
                                        >
                                            <x-framework.icon name="pencil-square" class="mr-1.5 h-3.5 w-3.5" />
                                            Edit
                                        </x-framework.buttons.secondary>
                                    </x-framework.table.td>
                                </x-framework.table.row>
                            @endforeach
                        </x-framework.table.body>
                    </x-framework.table.table>
                @endif
            </div>

            <div class="mt-10">
                @if($latestJobApplications->isEmpty())
                    <x-framework.layout.empty-state
                        title="No Applicants Yet"
                        description="Once you have job posts, applicants will appear here."
                    />
                @else
                    <div class="flex items-center justify-between mb-6">
                        <h2 class="text-xl font-bold text-slate-900">Latest Applicants</h2>
                        <a href="{{ route('agency.applications.index') }}" class="text-sm font-medium text-blue-600 hover:text-blue-500">
                            View all applicants &rarr;
                        </a>
                    </div>

                    <x-framework.table.table>
                        <x-framework.table.head>
                            <tr>
                                <x-framework.table.th>Applicant</x-framework.table.th>
                                <x-framework.table.th>Job Applied</x-framework.table.th>
                                <x-framework.table.th>Status</x-framework.table.th>
                                <x-framework.table.th>Date Applied</x-framework.table.th>
                                <x-framework.table.th class="text-right">Actions</x-framework.table.th>
                            </tr>
                        </x-framework.table.head>

                        <x-framework.table.body>
                            @foreach($latestJobApplications as $application)
                                <x-framework.table.row>
                                    <x-framework.table.td>
                                        <div class="flex items-center">
                                            <div class="h-8 w-8 flex-shrink-0">
                                                @if($application->applicant->guardProfile && $application->applicant->guardProfile->hasMedia('profile-photo'))
                                                    <img class="h-8 w-8 rounded-full object-cover" src="{{ $application->applicant->guardProfile->getFirstMediaUrl('profile-photo') }}" alt="">
                                                @else
                                                    <div class="h-8 w-8 rounded-full bg-slate-200 flex items-center justify-center">
                                                        <x-framework.icon name="user" class="h-4 w-4 text-slate-400" />
                                                    </div>
                                                @endif
                                            </div>
                                            <div class="ml-4">
                                                <div class="font-semibold text-slate-900">{{ $application->applicant->name }}</div>
                                            </div>
                                        </div>
                                    </x-framework.table.td>

                                    <x-framework.table.td>
                                        <div class="text-slate-900">{{ $application->job->title }}</div>
                                    </x-framework.table.td>

                                    <x-framework.table.td>
                                        <x-framework.feedback.badge color="blue">
                                            {{ optional($application->currentStep)->name ?? 'Applied' }}
                                        </x-framework.feedback.badge>
                                    </x-framework.table.td>

                                    <x-framework.table.td>
                                        {{ $application->applied_at->format('M d, Y') }}
                                    </x-framework.table.td>

                                    <x-framework.table.td class="text-right">
                                        <x-framework.buttons.secondary
                                            :href="route('agency.applications.show', $application)"
                                            size="sm"
                                        >
                                            <x-framework.icon name="eye" class="mr-1.5 h-3.5 w-3.5" />
                                            View
                                        </x-framework.buttons.secondary>
                                    </x-framework.table.td>
                                </x-framework.table.row>
                            @endforeach
                        </x-framework.table.body>
                    </x-framework.table.table>
                @endif
            </div>

        </div>
    </section>
@endsection
