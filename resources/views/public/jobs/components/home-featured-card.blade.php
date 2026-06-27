@props(['job'])

<div class="group relative flex flex-col h-full bg-white rounded-2xl border border-slate-200 overflow-hidden hover:shadow-xl hover:-translate-y-1 transition-all duration-300">
    {{-- Top Section with Image/Logo Placeholder --}}
    <div class="relative h-48 bg-slate-900 overflow-hidden">
        {{-- Pattern Overlay --}}
        <div class="absolute inset-0 opacity-20" style="background-image: url('data:image/svg+xml,%3Csvg width=&quot;20&quot; height=&quot;20&quot; viewBox=&quot;0 0 20 20&quot; xmlns=&quot;http://www.w3.org/2000/svg&quot;%3E%3Cg fill=&quot;%239C92AC&quot; fill-opacity=&quot;0.4&quot; fill-rule=&quot;evenodd&quot;%3E%3Ccircle cx=&quot;3&quot; cy=&quot;3&quot; r=&quot;3&quot;/%3E%3Ccircle cx=&quot;13&quot; cy=&quot;13&quot; r=&quot;3&quot;/%3E%3C/g%3E%3C/svg%3E');"></div>

        {{-- Gradient Overlay --}}
        <div class="absolute inset-0 bg-gradient-to-t from-slate-900 via-transparent to-transparent"></div>

        {{-- Featured Badge --}}
        <div class="absolute top-4 left-4">
            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-amber-400 text-slate-900 uppercase tracking-wider">
                Featured
            </span>
        </div>

        {{-- Salary Badge --}}
        <div class="absolute bottom-4 right-4">
            <span class="inline-flex items-center px-3 py-1 rounded-lg bg-white/10 backdrop-blur-md border border-white/20 text-white font-bold">
                ₱{{ number_format($job->salary_min / 1000, 0) }}k - ₱{{ number_format($job->salary_max / 1000, 0) }}k
            </span>
        </div>

        {{-- Agency Logo (Floating) --}}
        <div class="absolute -bottom-6 left-6 flex h-14 w-14 items-center justify-center rounded-xl bg-white border border-slate-100 text-xl font-black text-amber-600 shadow-lg group-hover:scale-110 transition-transform duration-300 uppercase">
            {{ substr($job->agency->name, 0, 2) }}
        </div>
    </div>

    {{-- Content Section --}}
    <div class="flex-1 p-6 pt-10">
        <div class="flex flex-col h-full">
            <div>
                <h3 class="text-xl font-bold text-slate-900 group-hover:text-amber-600 transition-colors leading-tight line-clamp-2">
                    {{ $job->title }}
                </h3>
                <div class="mt-2 flex items-center gap-2">
                    <span class="text-sm font-semibold text-slate-600">{{ $job->agency->name }}</span>
                    @if($job->agency->is_verified)
                        <x-framework.icon name="check-badge" class="h-4 w-4 text-blue-500" />
                    @endif
                </div>
            </div>

            <div class="mt-4 flex flex-wrap gap-3">
                <span class="inline-flex items-center gap-1 text-xs font-medium text-slate-500 bg-slate-50 px-2 py-1 rounded-md">
                    <x-framework.icon name="map-pin" class="h-3.5 w-3.5" />
                    {{ $job->city }}
                </span>
                <span class="inline-flex items-center gap-1 text-xs font-medium text-slate-500 bg-slate-50 px-2 py-1 rounded-md">
                    <x-framework.icon name="briefcase" class="h-3.5 w-3.5" />
                    {{ $job->employmentType->name ?? 'Full Time' }}
                </span>
            </div>

            <p class="mt-4 text-sm text-slate-600 line-clamp-3 leading-relaxed">
                {{ strip_tags($job->description) }}
            </p>

            <div class="mt-auto pt-6 flex items-center justify-between">
                <span class="text-xs text-slate-400">
                    {{ $job->published_at ? $job->published_at->diffForHumans() : 'Recently' }}
                </span>

                <a
                    href="{{ route('jobs.index', ['job' => $job->id]) }}"
                    class="inline-flex items-center gap-1.5 text-sm font-bold text-amber-600 group-hover:gap-2.5 transition-all"
                >
                    View Details
                    <x-framework.icon name="arrow-right" class="h-4 w-4" />
                </a>
            </div>
        </div>
    </div>
</div>
