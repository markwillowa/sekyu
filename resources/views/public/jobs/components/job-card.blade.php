@props(['job', 'active' => false])

<div
    :class="{ 'border-amber-500 ring-2 ring-amber-100 ring-offset-0': activeJob === {{ $job->id }}, 'border-slate-100 hover:border-amber-200': activeJob !== {{ $job->id }} }"
    class="group relative bg-white p-5 md:p-6 rounded-3xl border transition-all duration-300 overflow-hidden"
>
    {{-- Featured Badge Overlay if applicable --}}
    @if($job->is_featured)
        <div class="absolute top-0 left-0 bg-amber-400 text-white text-[10px] font-black uppercase tracking-tighter px-3 py-1 rounded-br-xl">
            Featured
        </div>
    @endif

    {{-- Save Button --}}
    <button type="button"
        class="absolute right-6 top-6 z-10 transition-colors"
        :class="isSaved({{ $job->id }}) ? 'text-rose-500' : 'text-slate-300 hover:text-rose-500'"
        @click.stop="toggleSave({{ $job->id }})">
        <x-framework.icon name="heart" class="h-6 w-6" variant="s" x-show="isSaved({{ $job->id }})" />
        <x-framework.icon name="heart" class="h-6 w-6" variant="o" x-show="!isSaved({{ $job->id }})" />
    </button>

    <div class="flex flex-col md:flex-row gap-4 md:gap-6">
        {{-- Agency Logo --}}
        <div class="flex h-12 w-12 md:h-14 md:w-14 shrink-0 items-center justify-center rounded-2xl bg-slate-50 text-lg md:text-xl font-bold text-slate-400 border border-slate-100 uppercase group-hover:bg-amber-50 group-hover:text-amber-600 transition-colors">
            {{ substr($job->agency->name, 0, 2) }}
        </div>

        <div class="flex-1">
            <div class="flex flex-wrap items-center gap-2">
                <h3 class="text-xl font-black text-slate-900 group-hover:text-amber-600 transition-colors leading-tight">
                    {{ $job->title }}
                </h3>
            </div>

            <div class="mt-1 flex items-center gap-2">
                <span class="font-bold text-slate-700 text-sm">{{ $job->agency->name }}</span>
                @if($job->agency->is_verified)
                    <x-framework.icon name="check-badge" class="h-4 w-4 text-green-500" />
                @endif
            </div>

            <div class="mt-2 text-xs text-slate-600 line-clamp-1 leading-relaxed">
                {{ Str::limit(strip_tags($job->description), 160) }}
            </div>

            <div class="mt-4 flex flex-wrap gap-x-4 gap-y-2 text-sm">
                <span class="flex items-center gap-1.5 text-slate-500">
                    <x-framework.icon name="map-pin" class="h-4 w-4 text-slate-400" />
                    {{ $job->location?->name ?? $job->city }}
                </span>
                <span class="flex items-center gap-1.5 font-bold text-slate-900">
                    ₱{{ number_format($job->salary_min / 1000, 0) }}k - ₱{{ number_format($job->salary_max / 1000, 0) }}k
                </span>
                <span class="flex items-center gap-1.5 text-slate-500">
                    <x-framework.icon name="briefcase" class="h-4 w-4 text-slate-400" />
                    {{ $job->employmentType->name ?? 'Full Time' }}
                </span>
            </div>

            <div class="mt-4 flex items-center justify-between border-t border-slate-50 pt-4">
                <div class="flex items-center gap-3">
                    <span class="text-[11px] text-slate-400 font-medium">
                        {{ $job->published_at ? $job->published_at->diffForHumans() : 'Recently' }}
                    </span>
                    <div class="h-1 w-1 rounded-full bg-slate-200"></div>
                    <span class="text-[11px] text-slate-400 font-medium">
                        12 Applicants
                    </span>
                </div>

                <div class="flex items-center gap-2 cursor-pointer" @click="openDetails({{ $job->id }})">
                    <template x-if="isApplied({{ $job->id }})">
                        <span class="text-xs font-bold text-green-600 uppercase tracking-wider">Applied</span>
                    </template>
                    <template x-if="!isApplied({{ $job->id }})">
                        <span class="text-xs font-bold text-amber-600 uppercase tracking-wider">Apply Now</span>
                    </template>
                    <x-framework.icon name="arrow-right" class="h-4 w-4 text-amber-600" />
                </div>
            </div>
        </div>
    </div>
</div>
