<div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
    <div>
        <h2 class="text-xl font-bold text-slate-900">
            {{ $jobs->total() }} Jobs Found
        </h2>
        <p class="text-sm text-slate-500">
            Showing {{ $jobs->firstItem() ?? 0 }}–{{ $jobs->lastItem() ?? 0 }} of {{ $jobs->total() }}
        </p>
    </div>

    <form method="GET" action="{{ route('jobs.index') }}" class="flex items-center gap-3">
        {{-- Pass through existing search and filter params --}}
        @if(request('q')) <input type="hidden" name="q" value="{{ request('q') }}"> @endif
        @if(request('location')) <input type="hidden" name="location" value="{{ request('location') }}"> @endif
        @if(request('salary_min')) <input type="hidden" name="salary_min" value="{{ request('salary_min') }}"> @endif
        @if(request('type'))
            @foreach(request('type') as $t)
                <input type="hidden" name="type[]" value="{{ $t }}">
            @endforeach
        @endif
        @if(request('loc'))
            @foreach(request('loc') as $l)
                <input type="hidden" name="loc[]" value="{{ $l }}">
            @endforeach
        @endif

        <label for="sort" class="text-sm font-medium text-slate-600">Sort by:</label>
        <x-framework.forms.select id="sort" name="sort" onchange="this.form.submit()" class="py-2 pl-3 pr-10 text-sm">
            <option value="newest" {{ request('sort') == 'newest' ? 'selected' : '' }}>Newest</option>
            <option value="oldest" {{ request('sort') == 'oldest' ? 'selected' : '' }}>Oldest</option>
            <option value="salary_high" {{ request('sort') == 'salary_high' ? 'selected' : '' }}>Highest Salary</option>
            <option value="salary_low" {{ request('sort') == 'salary_low' ? 'selected' : '' }}>Lowest Salary</option>
        </x-framework.forms.select>

        {{-- Mobile Filter Button --}}
        <x-framework.buttons.secondary
            @click="mobileFiltersOpen = true"
            class="lg:hidden gap-2 px-4 py-2 text-sm rounded-xl"
        >
            <x-framework.icon name="adjustments-horizontal" class="h-4 w-4" />
            Filters
        </x-framework.buttons.secondary>
    </form>
</div>
