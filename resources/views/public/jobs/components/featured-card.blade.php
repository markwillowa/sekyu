@props(['job'])

<div
    :class="{ 'border-amber-500 ring-2 ring-amber-100 ring-offset-0': activeJob === {{ $job->id }}, 'border-amber-100 shadow-amber-50 shadow-lg': activeJob !== {{ $job->id }} }"
    class="group relative bg-white p-5 md:p-6 rounded-3xl border border-amber-200 transition-all duration-300 overflow-hidden"
>
    {{-- Featured Header Badge --}}
    <div class="absolute top-0 left-0 bg-amber-400 text-white text-[10px] font-black uppercase tracking-widest px-4 py-1.5 rounded-br-2xl shadow-sm">
        Premium Featured
    </div>

    {{-- Save Button --}}
    <button type="button"
        class="absolute right-6 top-6 z-10 transition-colors"
        :class="isSaved({{ $job->id }}) ? 'text-rose-500' : 'text-slate-300 hover:text-rose-500'"
        @click.stop="toggleSave({{ $job->id }})">
        <x-framework.icon name="heart" class="h-7 w-7" variant="s" x-show="isSaved({{ $job->id }})" />
        <x-framework.icon name="heart" class="h-7 w-7" variant="o" x-show="!isSaved({{ $job->id }})" />
    </button>

    <div class="flex flex-col md:flex-row gap-4 md:gap-6">
        {{-- Agency Branding --}}
        <div class="flex md:flex-col items-center gap-3 shrink-0">
            <div class="flex h-12 w-12 md:h-16 md:w-16 items-center justify-center rounded-2xl bg-amber-50 text-xl md:text-2xl font-black text-amber-600 border border-amber-100 uppercase shadow-inner">
                {{ substr($job->agency->name, 0, 2) }}
            </div>
            @if($job->agency->is_verified)
                <div class="flex items-center gap-1 px-2 py-0.5 rounded-full bg-green-50 border border-green-100">
                    <x-framework.icon name="check-badge" class="h-3.5 w-3.5 text-green-600" />
                    <span class="text-[9px] font-black text-green-700 uppercase tracking-tighter">Verified</span>
                </div>
            @endif
        </div>

        <div class="flex-1">
            <div class="flex flex-col">
                <h3 class="text-2xl font-black text-slate-900 group-hover:text-amber-600 transition-colors leading-none tracking-tight">
                    {{ $job->title }}
                </h3>
                <span class="mt-2 font-bold text-slate-600 text-sm block">{{ $job->agency->name }}</span>
            </div>

            <div class="mt-2 text-xs text-slate-600 line-clamp-1 leading-relaxed">
                {{ Str::limit(strip_tags($job->description), 200) }}
            </div>

            <div class="mt-4 flex flex-wrap gap-4 items-center">
                @if($job->min_profile_completion > 0)
                    <span class="flex items-center gap-1.5 px-3 py-1 rounded-full bg-blue-600 text-white text-[11px] font-black tracking-wide">
                        <x-framework.icon name="user-circle" class="h-3.5 w-3.5" />
                        {{ $job->min_profile_completion }}% Profile Required
                    </span>
                @endif
                <span class="flex items-center gap-1.5 text-slate-500 text-sm font-medium">
                    <x-framework.icon name="map-pin" class="h-4.5 w-4.5 text-amber-400" />
                    {{ $job->location?->name ?? $job->city }}
                </span>
                <span class="flex items-center gap-1.5 text-slate-500 text-sm font-medium">
                    <x-framework.icon name="briefcase" class="h-4.5 w-4.5 text-amber-400" />
                    {{ $job->employmentType->name ?? 'Full Time' }}
                </span>
                <span class="px-3 py-1 rounded-full bg-slate-900 text-white text-xs font-black tracking-wide">
                    ₱{{ number_format($job->salary_min / 1000, 0) }}k - ₱{{ number_format($job->salary_max / 1000, 0) }}k
                </span>
            </div>

            <div class="mt-4 flex items-center justify-between border-t border-slate-50 pt-4">
                <div class="flex items-center gap-4">
                    <div class="flex flex-col">
                        <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest leading-none">Status</span>
                        <span class="mt-1 text-xs font-bold text-green-600 flex items-center gap-1">
                            <span class="h-1.5 w-1.5 rounded-full bg-green-500 animate-pulse"></span>
                            Urgent Hiring
                        </span>
                    </div>
                    <div class="h-8 w-px bg-slate-100"></div>
                    <div class="flex flex-col">
                        <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest leading-none">Applicants</span>
                        <span class="mt-1 text-xs font-bold text-slate-700">{{ $job->applications_count ?? 0 }} Applicants</span>
                    </div>
                    <div class="h-8 w-px bg-slate-100"></div>
                    <div class="flex flex-col">
                        <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest leading-none">Posted</span>
                        <span class="mt-1 text-xs font-bold text-slate-700">{{ $job->published_at ? $job->published_at->diffForHumans() : 'Recently' }}</span>
                    </div>
                </div>

                <div class="flex items-center gap-2 cursor-pointer" @click="openDetails({{ $job->id }})">
                    <template x-if="isApplied({{ $job->id }})">
                        <span class="text-sm font-black text-green-600 uppercase tracking-widest">Applied</span>
                    </template>
                    <template x-if="!isApplied({{ $job->id }})">
                        <span class="text-sm font-black text-amber-600 uppercase tracking-widest">Apply Now</span>
                    </template>
                    <div class="flex h-8 w-8 items-center justify-center rounded-full bg-amber-600 text-white shadow-lg shadow-amber-200">
                        <x-framework.icon name="arrow-right" class="h-4 w-4" />
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
