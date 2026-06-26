@extends('layouts.app')

@section('content')
    <div x-data="{
        mobileFiltersOpen: false,
        showDetailsPane: false,
        activeJob: null,
        jobs: {{ $jobs->toJson() }},
        openDetails(id) {
            this.activeJob = id;
            this.showDetailsPane = true;
        }
    }">
        @include('public.jobs.components.hero')

        <x-framework.layout.section class="bg-slate-50 py-12" title="">
            <div class="mx-auto max-w-[1400px] px-6">

                <div class="flex flex-col gap-8 lg:flex-row">

                    {{-- Filters Sidebar (Desktop) --}}
                    <aside class="hidden w-full lg:block lg:w-[320px] shrink-0">
                        <div class="sticky top-24">
                            @include('public.jobs.components.filters')
                        </div>
                    </aside>

                    {{-- Main Content - Job List Only --}}
                    <div class="flex flex-col flex-1 gap-8">
                        <main class="w-full flex flex-col gap-6">
                            {{-- Toolbar --}}
                            <div class="mb-2">
                                @include('public.jobs.components.toolbar')
                            </div>

                            {{-- Job List - Now Grid for better use of space if details are hidden --}}
                            <div class="grid grid-cols-1 gap-8">
                                @forelse ($jobs as $job)
                                    @if($job->is_featured)
                                        @include('public.jobs.components.featured-card', ['job' => $job])
                                    @else
                                        @include('public.jobs.components.job-card', ['job' => $job])
                                    @endif
                                @empty
                                    @include('public.jobs.components.empty')
                                @endforelse
                            </div>

                            {{-- Pagination --}}
                            @include('public.jobs.components.pagination')
                        </main>
                    </div>
                </div>

            </div>
        </x-framework.layout.section>

        {{-- Mobile Filter Drawer --}}
        <div
            x-show="mobileFiltersOpen"
            x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="translate-x-full"
            x-transition:enter-end="translate-x-0"
            x-transition:leave="transition ease-in duration-300"
            x-transition:leave-start="translate-x-0"
            x-transition:leave-end="translate-x-full"
            class="fixed inset-0 z-50 lg:hidden"
            style="display: none;"
        >
            {{-- Backdrop --}}
            <div class="fixed inset-0 bg-slate-900/50 backdrop-blur-sm" @click="mobileFiltersOpen = false"></div>

            {{-- Content --}}
            <div class="fixed inset-y-0 right-0 w-full max-w-xs bg-white shadow-xl flex flex-col">
                <div class="flex items-center justify-between px-6 py-4 border-b border-slate-100">
                    <h2 class="text-lg font-bold text-slate-900">Filters</h2>
                    <button @click="mobileFiltersOpen = false" class="text-slate-400 hover:text-slate-600">
                        <x-framework.icon name="x-mark" class="h-6 w-6" />
                    </button>
                </div>

                <div class="flex-1 overflow-y-auto px-6 py-8">
                    @include('public.jobs.components.filters')
                </div>

                <div class="p-6 border-t border-slate-100">
                    <x-framework.buttons.primary @click="mobileFiltersOpen = false" class="w-full py-4 rounded-2xl shadow-lg">
                        Apply Filters
                    </x-framework.buttons.primary>
                </div>
            </div>
        </div>

        {{-- Master Detail (Job Details Drawer) --}}
        <div
            x-show="showDetailsPane"
            class="fixed inset-0 z-[100]"
            style="display: none;"
        >
            {{-- Backdrop (30% opacity as requested) --}}
            <div
                x-show="showDetailsPane"
                x-transition:enter="transition ease-out duration-300"
                x-transition:enter-start="opacity-0"
                x-transition:enter-end="opacity-100"
                x-transition:leave="transition ease-in duration-200"
                x-transition:leave-start="opacity-100"
                x-transition:leave-end="opacity-0"
                class="fixed inset-0 bg-slate-900/30 backdrop-blur-[2px]"
                @click="showDetailsPane = false"
            ></div>

            {{-- Content Drawer (70% width as requested) --}}
            <div
                x-show="showDetailsPane"
                x-transition:enter="transition ease-out duration-500"
                x-transition:enter-start="translate-x-full"
                x-transition:enter-end="translate-x-0"
                x-transition:leave="transition ease-in duration-500"
                x-transition:leave-start="translate-x-0"
                x-transition:leave-end="translate-x-full"
                class="fixed inset-y-0 right-0 w-full md:w-[85%] lg:w-[70%] bg-white shadow-2xl flex flex-col overflow-hidden"
            >

                {{-- Details Content --}}
                <div class="flex-1 overflow-y-auto scroll-smooth">
                    @foreach($jobs as $job)
                        <div x-show="activeJob === {{ $job->id }}" style="display: none;">
                            @include('public.jobs.components.details-pane', ['job' => $job])
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
@endsection
