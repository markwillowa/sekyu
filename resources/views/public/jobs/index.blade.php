@extends('layouts.app')

@section('content')
    <div x-data="jobIndex({
        savedJobIds: {{ auth()->check() ? $savedJobs->pluck('id')->toJson() : '[]' }},
        appliedJobIds: {{ auth()->check() ? auth()->user()->jobApplications->pluck('job_id')->toJson() : '[]' }},
        profileCompletion: {{ $profileCompletion ?? 0 }},
        isLoggedIn: {{ auth()->check() ? 'true' : 'false' }},
        loginUrl: '{{ route('login') }}',
        csrfToken: '{{ csrf_token() }}'
    })">
        @include('public.jobs.components.hero')

        <div class="bg-slate-50 py-12 min-h-screen">
            <div class="mx-auto max-w-7xl px-6">

                <div class="flex flex-col gap-8 lg:flex-row">

                    {{-- Filters Sidebar (Desktop) --}}
                    <aside class="hidden w-full lg:block lg:w-[320px] shrink-0">
                        <div class="sticky top-24">
                            @include('public.jobs.components.filters')
                        </div>
                    </aside>

                    {{-- Main Content - Job List Only --}}
                    <div class="flex flex-col flex-1 gap-8 min-w-0">
                        <main class="w-full flex flex-col gap-8">
                            {{-- Toolbar --}}
                            <div class="mb-2">
                                @include('public.jobs.components.toolbar')
                            </div>

                            {{-- Job List - Now Grid for better use of space if details are hidden --}}
                            <div class="grid grid-cols-1 gap-8">
                                {{-- Saved Jobs on Top --}}
                                @if(auth()->check() && $savedJobs->isNotEmpty())
                                    {{-- Divider --}}
                                    <div class="relative py-8">
                                        <div class="absolute inset-0 flex items-center" aria-hidden="true">
                                            <div class="w-full border-t border-slate-200"></div>
                                        </div>
                                        <div class="relative flex justify-center">
                                            <span class="bg-slate-50 px-4 text-xs font-black text-slate-400 uppercase tracking-[0.2em]">Your Saved Jobs</span>
                                        </div>
                                    </div>
                                    @foreach($savedJobs as $job)
                                        @if($job->is_featured)
                                            @include('public.jobs.components.featured-card', ['job' => $job])
                                        @else
                                            @include('public.jobs.components.job-card', ['job' => $job])
                                        @endif
                                    @endforeach
                                @endif

                                {{-- 4 Random Jobs on Top - Only show if not filtered --}}
                                @if(!$isFiltered)
                                    @foreach($randomJobs as $job)
                                        @if($job->is_featured)
                                            @include('public.jobs.components.featured-card', ['job' => $job])
                                        @else
                                            @include('public.jobs.components.job-card', ['job' => $job])
                                        @endif
                                    @endforeach

                                    {{-- Divider --}}
                                    <div class="relative py-8">
                                        <div class="absolute inset-0 flex items-center" aria-hidden="true">
                                            <div class="w-full border-t border-slate-200"></div>
                                        </div>
                                        <div class="relative flex justify-center">
                                            <span class="bg-slate-50 px-4 text-xs font-black text-slate-400 uppercase tracking-[0.2em]">Latest Opportunities</span>
                                        </div>
                                    </div>
                                @endif

                                @forelse ($jobs as $job)
                                    @if(!$isFiltered)
                                        @continue($randomJobs->contains('id', $job->id))
                                    @endif
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
        </div>

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
                    <x-framework.buttons.primary @click="mobileFiltersOpen = false; submitFilterForm()" class="w-full py-4 rounded-2xl shadow-lg">
                        Apply Filters
                    </x-framework.buttons.primary>
                </div>
            </div>
        </div>

        {{-- Master Detail (Job Details Drawer) --}}
        <div
            x-show="showDetailsPane"
            x-cloak
            class="fixed inset-0 z-[1000]"
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
                    @foreach($jobs->getCollection()->concat($randomJobs)->unique('id') as $job)
                        <div x-show="activeJob == {{ $job->id }}" x-cloak style="display: none;">
                            @include('public.jobs.components.details-pane', ['job' => $job])
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script>
    document.addEventListener('alpine:init', () => {
        Alpine.data('jobIndex', (config) => ({
            mobileFiltersOpen: false,
            showDetailsPane: false,
            activeJob: null,
            profileCompletion: config.profileCompletion || 0,
            savedJobIds: config.savedJobIds || [],
            appliedJobIds: config.appliedJobIds || [], // Initialized with jobs already applied for

            isSaved(id) {
                return this.savedJobIds.includes(id);
            },

            isApplied(id) {
                return this.appliedJobIds.includes(id);
            },

            async apply(id) {
                if (!config.isLoggedIn) {
                    window.location.href = config.loginUrl;
                    return;
                }

                try {
                    const response = await fetch(`/jobs/${id}/apply`, {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': config.csrfToken,
                            'Content-Type': 'application/json',
                            'Accept': 'application/json'
                        }
                    });

                    const data = await response.json();

                    if (response.ok) {
                        this.appliedJobIds.push(id);
                        // Optional: You could update a global notification system here
                        // alert(data.success);
                    } else {
                        alert(data.error || 'Something went wrong.');
                    }
                } catch (error) {
                    console.error('Error applying for job:', error);
                    alert('An error occurred. Please try again.');
                }
            },

            async toggleSave(id) {
                if (!config.isLoggedIn) {
                    window.location.href = config.loginUrl;
                    return;
                }

                try {
                    const response = await fetch(`/jobs/${id}/toggle-save`, {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': config.csrfToken,
                            'Content-Type': 'application/json',
                            'Accept': 'application/json'
                        }
                    });

                    if (response.ok) {
                        const data = await response.json();
                        if (data.is_saved) {
                            if (!this.savedJobIds.includes(id)) {
                                this.savedJobIds.push(id);
                            }
                        } else {
                            this.savedJobIds = this.savedJobIds.filter(savedId => savedId !== id);
                        }
                    }
                } catch (error) {
                    console.error('Error toggling save:', error);
                }
            },

            openDetails(id) {
                console.log('Opening details for job:', id);
                this.activeJob = id;
                this.showDetailsPane = true;
            }
        }));
    });
</script>
@endpush
