@extends('layouts.app')

@section('content')
    <section class="bg-slate-50 py-10">
        <div class="mx-auto max-w-7xl px-6">

            <x-framework.layout.page-header
                title="Job Posts"
                description="Manage all your published and draft job posts."
            >
                <x-slot:actions>
                    <x-framework.buttons.primary
                        :href="route('agency.job-posts.create')"
                    >
                        <x-framework.icon
                            name="plus"
                            class="mr-2 h-5 w-5"
                        />

                        Create Job Post
                    </x-framework.buttons.primary>
                </x-slot:actions>
            </x-framework.layout.page-header>

            @if($jobPosts->isEmpty())

                <x-framework.layout.empty-state
                    title="No Job Posts Yet"
                    description="Create your first job post and start receiving guard applications."
                >
                    <x-slot:actions>
                        <x-framework.buttons.primary
                            :href="route('agency.job-posts.create')"
                        >
                            Create Job Post
                        </x-framework.buttons.primary>
                    </x-slot:actions>
                </x-framework.layout.empty-state>

            @else

                <x-framework.table.table>

                    <x-framework.table.head>
                        <tr>
                            <x-framework.table.th>Job Title</x-framework.table.th>
                            <x-framework.table.th>Status</x-framework.table.th>
                            <x-framework.table.th>Location</x-framework.table.th>
                            <x-framework.table.th>Vacancies</x-framework.table.th>
                            <x-framework.table.th>Posted</x-framework.table.th>
                            <x-framework.table.th class="text-right">
                                Actions
                            </x-framework.table.th>
                        </tr>
                    </x-framework.table.head>

                    <x-framework.table.body>

                        @foreach($jobPosts as $job)

                            <x-framework.table.row>

                                <x-framework.table.td>

                                    <div class="font-semibold text-slate-900">
                                        {{ $job->title }}
                                    </div>

                                    <div class="mt-1 text-xs text-slate-500">
                                        {{ optional($job->employmentType)->name }}
                                    </div>

                                </x-framework.table.td>

                                <x-framework.table.td>

                                    <x-framework.feedback.badge :color="$job->status && $job->status->code === 'published' ? 'green' : 'amber'">
                                        {{ optional($job->status)->name }}
                                    </x-framework.feedback.badge>

                                </x-framework.table.td>

                                <x-framework.table.td>
                                    {{ $job->city }}, {{ $job->province }}
                                </x-framework.table.td>

                                <x-framework.table.td>
                                    {{ $job->vacancies }}
                                </x-framework.table.td>

                                <x-framework.table.td>
                                    {{ optional($job->published_at)?->format('M d, Y') ?? 'Draft' }}
                                </x-framework.table.td>

                                <x-framework.table.td>

                                    <x-framework.table.actions>

                                        <x-framework.buttons.secondary
                                            :href="route('agency.job-posts.edit', $job)"
                                            size="sm"
                                        >
                                            <x-framework.icon name="pencil-square" class="mr-1.5 h-3.5 w-3.5" />
                                            Edit
                                        </x-framework.buttons.secondary>

                                    </x-framework.table.actions>

                                </x-framework.table.td>

                            </x-framework.table.row>

                        @endforeach

                    </x-framework.table.body>

                </x-framework.table.table>

                <div class="mt-6">
                    {{ $jobPosts->links() }}
                </div>

            @endif

        </div>
    </section>
@endsection
