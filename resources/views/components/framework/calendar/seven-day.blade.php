@props([
    'startDate',
    'interviews' => [],
    'nextUrl' => '#',
    'prevUrl' => '#',
])

<div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
    {{-- Header --}}
    <div class="flex items-center justify-between px-6 py-4 border-b border-slate-100 bg-slate-50/50">
        <h3 class="text-lg font-bold text-slate-900">
            {{ $startDate->format('F Y') }}
        </h3>
        <div class="flex items-center space-x-2">
            <a href="{{ $prevUrl }}" class="p-2 rounded-lg hover:bg-slate-200 text-slate-600 transition-colors" title="Previous 7 Days">
                <x-framework.icon name="chevron-left" class="h-5 w-5" />
            </a>
            <a href="{{ $nextUrl }}" class="p-2 rounded-lg hover:bg-slate-200 text-slate-600 transition-colors" title="Next 7 Days">
                <x-framework.icon name="chevron-right" class="h-5 w-5" />
            </a>
        </div>
    </div>

    {{-- Calendar Grid Header (Days) --}}
    <div class="grid grid-cols-7 border-b border-slate-100 bg-slate-50/30 overflow-x-auto lg:overflow-visible">
        @for($i = 0; $i < 7; $i++)
            @php
                $currentDay = $startDate->copy()->addDays($i);
                $isToday = $currentDay->isToday();
            @endphp
            <div @class([
                'text-center py-3 border-r border-slate-100 last:border-r-0 min-w-[100px] lg:min-w-0',
                'bg-blue-50/50' => $isToday
            ])>
                <span class="text-[10px] font-bold uppercase tracking-wider text-slate-400 block">{{ $currentDay->format('D') }}</span>
                <span @class([
                    'inline-flex items-center justify-center h-8 w-8 rounded-full text-sm font-bold mt-1',
                    'bg-blue-600 text-white' => $isToday,
                    'text-slate-900' => !$isToday,
                ])>
                    {{ $currentDay->format('d') }}
                </span>
            </div>
        @endfor
    </div>

    {{-- Calendar Grid Body (Events) --}}
    <div class="flex flex-col lg:grid lg:grid-cols-7 min-h-[200px] lg:min-h-[400px]">
        @for($i = 0; $i < 7; $i++)
            @php
                $currentDay = $startDate->copy()->addDays($i);
                $dayInterviews = $interviews->filter(fn($interview) => $interview->scheduled_at->isSameDay($currentDay));
                $isToday = $currentDay->isToday();
            @endphp
            <div @class([
                'border-b lg:border-b-0 lg:border-r border-slate-100 last:border-r-0 last:border-b-0 p-3 lg:p-2 space-y-2',
                'bg-blue-50/20' => $isToday,
                'bg-slate-50/10' => !$isToday
            ])>
                {{-- Mobile Date Header --}}
                <div class="flex lg:hidden items-center justify-between mb-2">
                    <span class="text-xs font-bold text-slate-500">{{ $currentDay->format('D, M d') }}</span>
                    @if($isToday)
                        <span class="text-[10px] font-bold text-blue-600 uppercase">Today</span>
                    @endif
                </div>

                @forelse($dayInterviews as $interview)
                    <div class="bg-white p-3 rounded-xl border border-slate-200 shadow-sm text-left group hover:border-blue-300 transition-all hover:shadow-md">
                        <div class="flex items-center justify-between mb-1">
                            <span class="text-[10px] font-bold text-blue-600 uppercase">{{ $interview->scheduled_at->format('h:i A') }}</span>
                            @if($interview->meeting_url)
                                <span class="h-2 w-2 rounded-full bg-green-500 animate-pulse" title="Online Meeting"></span>
                            @endif
                        </div>

                        <div class="text-xs font-bold text-slate-900 line-clamp-2 mb-1" title="{{ $interview->title }}">
                            {{ $interview->title }}
                        </div>

                        <div class="text-[10px] text-slate-500 truncate mb-2">
                            @if(auth()->user()->hasRole('agency'))
                                {{ $interview->jobApplication->applicant->name }} <span class="text-slate-400">for</span> {{ $interview->jobApplication->job->title }}
                            @else
                                {{ $interview->jobApplication->job->agency->name }}
                            @endif
                        </div>

                        <div class="flex flex-col space-y-1.5 pt-2 border-t border-slate-100">
                            @if($interview->meeting_url)
                                <a href="{{ $interview->meeting_url }}" target="_blank" class="flex items-center text-[10px] text-blue-600 font-bold hover:underline">
                                    <x-framework.icon name="video-camera" class="h-3 w-3 mr-1" />
                                    Join Meeting
                                </a>
                            @endif
                            <a href="{{ route('interviews.calendar', $interview) }}" class="flex items-center text-[10px] text-slate-500 font-bold hover:text-slate-700">
                                <x-framework.icon name="calendar" class="h-3 w-3 mr-1" />
                                Add to Cal
                            </a>
                        </div>
                    </div>
                @empty
                    <div class="h-full flex items-center justify-center py-4 lg:py-0 lg:opacity-0 group-hover:opacity-100 transition-opacity">
                         <span class="text-[10px] text-slate-300 font-medium">No events</span>
                    </div>
                @endforelse
            </div>
        @endfor
    </div>
</div>
