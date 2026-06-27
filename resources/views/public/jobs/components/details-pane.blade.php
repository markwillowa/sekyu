@props(['job' => null])

<div class="h-full bg-white overflow-hidden flex flex-col">
    @if($job)
        {{-- Header --}}
        <div class="p-6 md:p-8 border-b border-slate-50 relative overflow-hidden">
            {{-- Premium Detail Background Decoration --}}
            <div class="absolute top-0 right-0 -mt-20 -mr-20 h-64 w-64 rounded-full bg-slate-50 opacity-50"></div>

            <div class="relative flex flex-col gap-4 md:gap-6">
                <div class="flex items-start justify-between">
                    <div class="flex gap-4 md:gap-6">
                        <div class="flex h-16 w-16 md:h-20 md:w-20 shrink-0 items-center justify-center rounded-2xl bg-white border border-slate-200 shadow-sm uppercase text-xl md:text-2xl font-black text-slate-400">
                             {{ substr($job->agency->name, 0, 2) }}
                        </div>
                        <div>
                            <h2 class="text-2xl md:text-3xl font-black text-slate-900 leading-tight tracking-tight">{{ $job->title }}</h2>
                            <div class="mt-2 flex flex-wrap items-center gap-3">
                                <span class="font-bold text-blue-600 text-sm md:text-base">{{ $job->agency->name }}</span>
                                @if($job->agency->is_verified)
                                    <span class="flex items-center gap-1 px-2 py-0.5 rounded-full bg-green-50 text-[10px] font-bold text-green-600 uppercase tracking-widest border border-green-100">
                                        <x-framework.icon name="check-badge" class="h-3 w-3" />
                                        Verified Agency
                                    </span>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="flex items-center gap-3">
                        <button type="button"
                            class="transition-colors"
                            :class="isSaved({{ $job->id }}) ? 'text-rose-500' : 'text-slate-300 hover:text-rose-500'"
                            @click.stop="toggleSave({{ $job->id }})">
                            <x-framework.icon name="heart" class="h-7 w-7 md:h-8 md:w-8" variant="s" x-show="isSaved({{ $job->id }})" />
                            <x-framework.icon name="heart" class="h-7 w-7 md:h-8 md:w-8" variant="o" x-show="!isSaved({{ $job->id }})" />
                        </button>
                        <button @click="showDetailsPane = false" type="button" class="text-slate-400 hover:text-slate-600 transition-colors">
                            <x-framework.icon name="x-mark" class="h-7 w-7 md:h-8 md:w-8" />
                        </button>
                    </div>
                </div>

                <div class="flex flex-wrap gap-2 md:gap-3">
                    <span class="inline-flex items-center gap-1.5 px-3 md:px-4 py-1.5 md:py-2 rounded-xl bg-slate-50 text-slate-600 text-[12px] md:text-sm font-semibold border border-slate-100">
                        <x-framework.icon name="map-pin" class="h-3.5 w-3.5 md:h-4 md:w-4" />
                        {{ $job->location?->name ?? $job->city }}
                    </span>
                    <span class="inline-flex items-center gap-1.5 px-3 md:px-4 py-1.5 md:py-2 rounded-xl bg-amber-50 text-amber-700 text-[12px] md:text-sm font-bold border border-amber-100">
                        <x-framework.icon name="banknotes" class="h-3.5 w-3.5 md:h-4 md:w-4" />
                        @if($job->salary_min && $job->salary_max)
                            ₱{{ number_format($job->salary_min / 1000, 0) }}k - ₱{{ number_format($job->salary_max / 1000, 0) }}k
                        @else
                            Negotiable
                        @endif
                    </span>
                    @if($job->employmentType)
                        <span class="inline-flex items-center gap-1.5 px-3 md:px-4 py-1.5 md:py-2 rounded-xl bg-slate-50 text-slate-600 text-[12px] md:text-sm font-semibold border border-slate-100">
                            <x-framework.icon name="briefcase" class="h-3.5 w-3.5 md:h-4 md:w-4" />
                            {{ $job->employmentType->name }}
                        </span>
                    @endif
                </div>
            </div>
        </div>

        {{-- Content --}}
        <div class="flex-1 overflow-y-auto p-6 md:p-8 space-y-10 md:space-y-12">
            <section>
                <div class="flex items-center gap-2 mb-6">
                    <div class="h-8 w-1 bg-blue-600 rounded-full"></div>
                    <h3 class="text-xl font-black text-slate-900 uppercase tracking-tight">Job Overview</h3>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                    <div class="bg-slate-50 p-4 rounded-2xl border border-slate-100">
                        <div class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1">Position Type</div>
                        <div class="text-sm font-bold text-slate-900">{{ $job->employmentType->name ?? 'Full Time' }}</div>
                    </div>
                    <div class="bg-slate-50 p-4 rounded-2xl border border-slate-100">
                        <div class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1">Work Setting</div>
                        <div class="text-sm font-bold text-slate-900">{{ $job->workLocationType->name ?? 'On-Site' }}</div>
                    </div>
                </div>
                <div class="sekyu-editor-content-view text-slate-600 leading-relaxed text-base">
                    {!! $job->description !!}
                </div>
            </section>

            @if($job->requirements)
                <section>
                    <div class="flex items-center gap-2 mb-6">
                        <div class="h-8 w-1 bg-amber-500 rounded-full"></div>
                        <h3 class="text-xl font-black text-slate-900 uppercase tracking-tight">Candidate Requirements</h3>
                    </div>
                    <div class="sekyu-editor-content-view text-slate-600 leading-relaxed bg-slate-50/50 p-6 rounded-3xl border border-slate-100">
                        {!! $job->requirements !!}
                    </div>
                </section>
            @endif

            @if($job->benefits)
                <section>
                    <div class="flex items-center gap-2 mb-6">
                        <div class="h-8 w-1 bg-green-500 rounded-full"></div>
                        <h3 class="text-xl font-black text-slate-900 uppercase tracking-tight">Perks & Benefits</h3>
                    </div>
                    <div class="sekyu-editor-content-view text-slate-600 leading-relaxed">
                        {!! $job->benefits !!}
                    </div>
                </section>
            @endif

            <section class="pt-8 border-t border-slate-100">
                <h3 class="text-lg font-bold text-slate-900 mb-6">About the Company</h3>
                <div class="flex items-center gap-4 p-6 bg-slate-900 rounded-3xl text-white">
                    <div class="h-16 w-16 rounded-2xl bg-white/10 flex items-center justify-center text-xl font-black">
                        {{ substr($job->agency->name, 0, 1) }}
                    </div>
                    <div>
                        <div class="font-bold text-lg">{{ $job->agency->name }}</div>
                        <div class="text-slate-400 text-sm">Member since {{ $job->agency->created_at->format('Y') }}</div>
                    </div>
                </div>
            </section>
        </div>

        {{-- Footer Actions --}}
        <div class="p-6 md:p-8 border-t border-slate-50 bg-slate-50/50">
            <div class="flex flex-col md:flex-row items-center justify-between gap-4">
                <div class="text-[12px] md:text-sm text-slate-400 font-medium">
                    Posted {{ $job->published_at ? $job->published_at->diffForHumans() : 'Recently' }}
                </div>
                @php
                    $userApplication = auth()->check() ? $job->applications()->where('guard_id', auth()->id())->first() : null;
                @endphp

                @if($userApplication)
                    <div class="flex items-center gap-2 text-green-600 font-bold bg-green-50 px-6 py-3 rounded-2xl border border-green-100">
                        <x-framework.icon name="check-circle" class="h-6 w-6" />
                        Applied on {{ $userApplication->applied_at->format('M d, Y') }}
                    </div>
                @elseif(!$job->workflowTemplate || $job->workflowTemplate->steps()->count() === 0)
                    <div class="flex items-center gap-2 text-amber-600 font-bold bg-amber-50 px-6 py-3 rounded-2xl border border-amber-100">
                        <x-framework.icon name="exclamation-circle" class="h-6 w-6" />
                        Not accepting applications yet
                    </div>
                @else
                    <form action="{{ route('jobs.apply', $job) }}" method="POST" class="w-full md:w-auto">
                        @csrf
                        <x-framework.buttons.primary type="submit" class="w-full md:w-auto px-8 md:px-12 py-3 md:py-4 rounded-2xl text-base md:text-lg font-black shadow-lg shadow-amber-200">
                            Apply Now
                        </x-framework.buttons.primary>
                    </form>
                @endif
            </div>
        </div>
    @else
        <div class="flex-1 flex flex-col items-center justify-center p-12 text-center">
            <div class="h-24 w-24 rounded-3xl bg-slate-50 flex items-center justify-center mb-6 border border-slate-100">
                <x-framework.icon name="briefcase" class="h-12 w-12 text-slate-200" />
            </div>
            <h3 class="text-xl font-bold text-slate-900">Select a job to view details</h3>
            <p class="mt-2 text-slate-500 max-w-xs mx-auto">Click on a job card from the list to see the full requirements and apply.</p>
        </div>
    @endif
</div>
