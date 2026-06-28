@extends('layouts.app')

@section('content')
    <section class="bg-slate-50 py-10">
        <div class="mx-auto max-w-7xl px-6">

            <x-framework.layout.page-header
                title="Agency Analytics"
                description="Insights into your hiring performance and workflow bottlenecks."
            >
                <x-slot:actions>
                    <x-framework.buttons.secondary
                        href="{{ route('agency.dashboard') }}"
                    >
                        <x-framework.icon
                            name="arrow-left"
                            class="mr-2 h-5 w-5"
                        />
                        Back to Dashboard
                    </x-framework.buttons.secondary>
                </x-slot:actions>
            </x-framework.layout.page-header>

            <!-- Dashboard Stats -->
            <div class="mt-8">
                <h3 class="text-lg font-semibold text-slate-900 mb-4">Dashboard</h3>
                <x-framework.stats.grid>
                    <x-framework.stats.card
                        title="Jobs Posted"
                        :value="$jobsPosted"
                        color="blue"
                    >
                        <x-slot:icon>
                            <x-framework.icon name="briefcase" class="h-6 w-6" />
                        </x-slot:icon>
                    </x-framework.stats.card>

                    <x-framework.stats.card
                        title="Applicants"
                        :value="$totalApplicants"
                        color="indigo"
                    >
                        <x-slot:icon>
                            <x-framework.icon name="users" class="h-6 w-6" />
                        </x-slot:icon>
                    </x-framework.stats.card>

                    <x-framework.stats.card
                        title="Interviewed"
                        :value="$interviewedCount"
                        color="amber"
                    >
                        <x-slot:icon>
                            <x-framework.icon name="chat-bubble-left-right" class="h-6 w-6" />
                        </x-slot:icon>
                    </x-framework.stats.card>

                    <x-framework.stats.card
                        title="Hired"
                        :value="$hiredCount"
                        color="green"
                    >
                        <x-slot:icon>
                            <x-framework.icon name="check-circle" class="h-6 w-6" />
                        </x-slot:icon>
                    </x-framework.stats.card>
                </x-framework.stats.grid>
            </div>

            <div class="mt-10 grid grid-cols-1 gap-8 lg:grid-cols-2">
                <!-- Hiring Funnel -->
                <div class="rounded-xl border border-slate-200 bg-white p-6 shadow-sm">
                    <h3 class="text-lg font-semibold text-slate-900 mb-6">Hiring Funnel</h3>
                    <div class="space-y-4">
                        @foreach($funnelData as $label => $count)
                            <div class="flex flex-col items-center">
                                <div class="w-full rounded-lg bg-slate-50 p-4 flex items-center justify-between border border-slate-100">
                                    <span class="font-medium text-slate-700">{{ $label }}</span>
                                    <span class="text-xl font-bold text-slate-900">{{ $count }}</span>
                                </div>
                                @if(!$loop->last)
                                    <x-framework.icon name="chevron-down" class="my-1 h-5 w-5 text-slate-300" />
                                @endif
                            </div>
                        @endforeach
                    </div>
                </div>

                <!-- Workflow Bottlenecks -->
                <div class="rounded-xl border border-slate-200 bg-white p-6 shadow-sm">
                    <h3 class="text-lg font-semibold text-slate-900 mb-6">Workflow Bottlenecks</h3>
                    <p class="text-sm text-slate-500 mb-6">Average time spent by candidates in each stage.</p>

                    @if($bottlenecks->isEmpty())
                        <div class="flex h-64 items-center justify-center rounded-lg border-2 border-dashed border-slate-200">
                            <p class="text-slate-400">Not enough data to track bottlenecks.</p>
                        </div>
                    @else
                        <div class="space-y-6">
                            @foreach($bottlenecks as $bottleneck)
                                <div>
                                    <div class="flex items-center justify-between mb-2">
                                        <span class="text-sm font-medium text-slate-700">{{ $bottleneck->name }}</span>
                                        <span class="text-sm font-bold text-slate-900">{{ round($bottleneck->avg_days, 1) }} Days</span>
                                    </div>
                                    <div class="h-2 w-full rounded-full bg-slate-100 overflow-hidden">
                                        @php
                                            $maxDays = $bottlenecks->max('avg_days') ?: 1;
                                            $percentage = ($bottleneck->avg_days / $maxDays) * 100;
                                            $barColor = $percentage > 70 ? 'bg-red-500' : ($percentage > 40 ? 'bg-amber-500' : 'bg-blue-500');
                                        @endphp
                                        <div class="h-full {{ $barColor }}" style="width: {{ $percentage }}%"></div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>

            <div class="mt-10 grid grid-cols-1 gap-8 lg:grid-cols-2">
                <!-- Time to Hire -->
                <div class="rounded-xl border border-slate-200 bg-white p-6 shadow-sm flex flex-col justify-center items-center text-center">
                    <h3 class="text-lg font-semibold text-slate-900 mb-2 w-full text-left">Time to Hire</h3>
                    <div class="py-10">
                        <span class="text-6xl font-black text-blue-600">{{ $avgTimeToHire }}</span>
                        <span class="text-xl font-bold text-slate-400 ml-2">Days</span>
                        <p class="mt-4 text-slate-500 max-w-xs mx-auto text-sm">Average duration from application to deployment for successful hires.</p>
                    </div>
                </div>

                <!-- Top Performing Jobs -->
                <div class="rounded-xl border border-slate-200 bg-white p-6 shadow-sm">
                    <h3 class="text-lg font-semibold text-slate-900 mb-6">Top Performing Jobs</h3>
                    <div class="overflow-hidden">
                        <table class="min-w-full divide-y divide-slate-200">
                            <thead>
                                <tr>
                                    <th class="text-left text-xs font-medium text-slate-500 uppercase tracking-wider py-3">Job Title</th>
                                    <th class="text-center text-xs font-medium text-slate-500 uppercase tracking-wider py-3">Applicants</th>
                                    <th class="text-center text-xs font-medium text-slate-500 uppercase tracking-wider py-3">Hires</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-200">
                                @forelse($topJobs as $job)
                                    <tr>
                                        <td class="py-4">
                                            <div class="text-sm font-semibold text-slate-900">{{ $job->title }}</div>
                                        </td>
                                        <td class="py-4 text-center">
                                            <span class="inline-flex items-center rounded-full bg-blue-50 px-2.5 py-0.5 text-xs font-medium text-blue-700">
                                                {{ $job->applicants_count }}
                                            </span>
                                        </td>
                                        <td class="py-4 text-center">
                                            <span class="inline-flex items-center rounded-full bg-green-50 px-2.5 py-0.5 text-xs font-medium text-green-700">
                                                {{ $job->hires_count }}
                                            </span>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="3" class="py-10 text-center text-slate-400 text-sm">No job data available.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

        </div>
    </section>
@endsection
