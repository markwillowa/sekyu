@extends('layouts.app')

@section('content')
    <section class="bg-slate-50 py-10">
        <div class="mx-auto max-w-7xl px-6">

            <x-framework.layout.page-header
                title="Guard Dashboard"
                description="Welcome back, {{ auth()->user()->name }}."
            >
                <x-slot:actions>
                    <x-framework.buttons.primary
                        href="{{ route('jobs.index') }}"
                    >
                        <x-framework.icon
                            name="magnifying-glass"
                            class="mr-2 h-5 w-5"
                        />

                        Find Jobs
                    </x-framework.buttons.primary>
                </x-slot:actions>
            </x-framework.layout.page-header>

            <x-framework.feedback.alert type="info">
                Welcome to <strong>SEKYU</strong>.
                Complete your profile to increase your chances of getting hired by top agencies.
            </x-framework.feedback.alert>

            <div class="mt-8">
                <x-framework.stats.grid>

                    <x-framework.stats.card
                        title="Applications"
                        :value="$applicationsCount"
                        color="blue"
                        href="{{ route('applicant.applications.index') }}"
                    >
                        <x-slot:icon>
                            <x-framework.icon
                                name="document-text"
                                class="h-6 w-6"
                            />
                        </x-slot:icon>
                    </x-framework.stats.card>

                    <x-framework.stats.card
                        title="Interviews"
                        :value="$interviewsCount"
                        color="green"
                    >
                        <x-slot:icon>
                            <x-framework.icon
                                name="chat-bubble-left-right"
                                class="h-6 w-6"
                            />
                        </x-slot:icon>
                    </x-framework.stats.card>

                    <x-framework.stats.card
                        title="Saved Jobs"
                        :value="$savedJobsCount"
                        color="amber"
                    >
                        <x-slot:icon>
                            <x-framework.icon
                                name="heart"
                                class="h-6 w-6"
                            />
                        </x-slot:icon>
                    </x-framework.stats.card>

                    <x-framework.stats.card
                        title="Profile Completion"
                        :value="$profileCompletion . '%'"
                        color="slate"
                    >
                        <x-slot:icon>
                            <x-framework.icon
                                name="user-circle"
                                class="h-6 w-6"
                            />
                        </x-slot:icon>
                    </x-framework.stats.card>

                </x-framework.stats.grid>
            </div>

            <div class="mt-8">
                <div class="mb-8">
                    <h2 class="text-xl font-bold text-slate-900 mb-6">Upcoming Interviews</h2>
                    <x-framework.calendar.seven-day
                        :start-date="$startDate"
                        :interviews="$calendarInterviews"
                        :next-url="$nextUrl"
                        :prev-url="$prevUrl"
                    />
                </div>

                <div class="grid grid-cols-1 gap-8">
                    <div>
                        @if($recentApplications->isEmpty())
                        <x-framework.layout.empty-state
                            title="No Applications Yet"
                            description="Start your career by applying to security job openings from verified agencies."
                        >
                            <x-slot:actions>
                                <x-framework.buttons.primary
                                    href="{{ route('jobs.index') }}"
                                >
                                    <x-framework.icon
                                        name="magnifying-glass"
                                        class="mr-2 h-5 w-5"
                                    />

                                    Browse Jobs
                                </x-framework.buttons.primary>
                            </x-slot:actions>
                        </x-framework.layout.empty-state>
                    @else
                        <div class="flex items-center justify-between mb-6">
                            <h2 class="text-xl font-bold text-slate-900">Recent Applications</h2>
                            <a href="{{ route('applicant.applications.index') }}" class="text-sm font-medium text-blue-600 hover:text-blue-500">
                                View all applications &rarr;
                            </a>
                        </div>

                        <x-framework.table.table>
                            <x-framework.table.head>
                                <tr>
                                    <x-framework.table.th>Agency</x-framework.table.th>
                                    <x-framework.table.th>Job Position</x-framework.table.th>
                                    <x-framework.table.th>Status</x-framework.table.th>
                                    <x-framework.table.th>Applied Date</x-framework.table.th>
                                    <x-framework.table.th class="text-right">Actions</x-framework.table.th>
                                </tr>
                            </x-framework.table.head>

                            <x-framework.table.body>
                                @foreach($recentApplications as $application)
                                    <x-framework.table.row>
                                        <x-framework.table.td>
                                            <div class="font-semibold text-slate-900">{{ $application->job->agency->name }}</div>
                                        </x-framework.table.td>

                                        <x-framework.table.td>
                                            <div class="text-slate-700">{{ $application->job->title }}</div>
                                            <div class="mt-1 text-xs text-slate-500">{{ optional($application->job->employmentType)->name }}</div>
                                        </x-framework.table.td>

                                        <x-framework.table.td>
                                            <x-framework.feedback.badge color="blue">
                                                {{ $application->currentStep->name }}
                                            </x-framework.feedback.badge>
                                        </x-framework.table.td>

                                        <x-framework.table.td>
                                            {{ $application->applied_at->format('M d, Y') }}
                                        </x-framework.table.td>

                                        <x-framework.table.td class="text-right">
                                            <x-framework.buttons.secondary
                                                :href="route('applicant.applications.show', $application)"
                                                size="sm"
                                            >
                                                View Details
                                            </x-framework.buttons.secondary>
                                        </x-framework.table.td>
                                    </x-framework.table.row>
                                @endforeach
                            </x-framework.table.body>
                        </x-framework.table.table>
                    @endif
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
