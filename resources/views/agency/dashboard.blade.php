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
                                name="document"
                                class="h-6 w-6"
                            />
                        </x-slot:icon>
                    </x-framework.stats.card>

                    <x-framework.stats.card
                        title="Applications"
                        :value="$applicationsCount"
                        color="blue"
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
                                                'published' => 'green',
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

        </div>
    </section>
@endsection
