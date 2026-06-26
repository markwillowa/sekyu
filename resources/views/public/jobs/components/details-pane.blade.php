@props(['job' => null])

<div class="h-full bg-white overflow-hidden flex flex-col">
    @if($job)
        {{-- Header --}}
        <div class="p-6 md:p-8 border-b border-slate-50">
            <div class="flex flex-col gap-4 md:gap-6">
                <div class="flex items-start justify-between">
                    <div class="flex gap-3 md:gap-4">
                        <div class="flex h-12 w-12 md:h-16 md:w-16 shrink-0 items-center justify-center rounded-2xl bg-slate-50 border border-slate-100 uppercase text-lg md:text-xl font-bold text-slate-400">
                             {{ substr($job->agency->name, 0, 2) }}
                        </div>
                        <div>
                            <h2 class="text-xl md:text-2xl font-black text-slate-900 leading-tight">{{ $job->title }}</h2>
                            <div class="mt-1 flex flex-wrap items-center gap-2">
                                <span class="font-bold text-slate-700 text-sm md:text-base">{{ $job->agency->name }}</span>
                                @if($job->agency->is_verified)
                                    <span class="flex items-center gap-1 text-[9px] md:text-[10px] font-bold text-green-600 uppercase tracking-widest">
                                        <x-framework.icon name="check-badge" class="h-3.5 w-3.5" />
                                        Verified
                                    </span>
                                @endif
                            </div>
                        </div>
                    </div>
                    <button @click="showDetailsPane = false" type="button" class="text-slate-400 hover:text-slate-600 transition-colors">
                        <x-framework.icon name="x-mark" class="h-7 w-7 md:h-8 md:w-8" />
                    </button>
                </div>

                <div class="flex flex-wrap gap-2 md:gap-3">
                    <span class="inline-flex items-center gap-1.5 px-3 md:px-4 py-1.5 md:py-2 rounded-xl bg-slate-50 text-slate-600 text-[12px] md:text-sm font-semibold border border-slate-100">
                        <x-framework.icon name="map-pin" class="h-3.5 w-3.5 md:h-4 md:w-4" />
                        {{ $job->city }}
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
        <div class="flex-1 overflow-y-auto p-6 md:p-8 space-y-6 md:y-8">
            <section>
                <h3 class="text-lg font-bold text-slate-900 mb-4">Job Description</h3>
                <div class="sekyu-editor-content-view text-slate-600 leading-relaxed">
                    {!! $job->description !!}
                </div>
            </section>

            @if($job->requirements)
                <section>
                    <h3 class="text-lg font-bold text-slate-900 mb-4">Requirements</h3>
                    <div class="sekyu-editor-content-view text-slate-600 leading-relaxed">
                        {!! $job->requirements !!}
                    </div>
                </section>
            @endif

            @if($job->benefits)
                <section>
                    <h3 class="text-lg font-bold text-slate-900 mb-4">Benefits</h3>
                    <div class="sekyu-editor-content-view text-slate-600 leading-relaxed">
                        {!! $job->benefits !!}
                    </div>
                </section>
            @endif
        </div>

        {{-- Footer Actions --}}
        <div class="p-6 md:p-8 border-t border-slate-50 bg-slate-50/50">
            <div class="flex flex-col md:flex-row items-center justify-between gap-4">
                <div class="text-[12px] md:text-sm text-slate-400 font-medium">
                    Posted {{ $job->published_at ? $job->published_at->diffForHumans() : 'Recently' }}
                </div>
                <x-framework.buttons.primary href="#" class="w-full md:w-auto px-8 md:px-12 py-3 md:py-4 rounded-2xl text-base md:text-lg font-black shadow-lg shadow-amber-200">
                    Apply Now
                </x-framework.buttons.primary>
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
