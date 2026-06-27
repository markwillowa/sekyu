<form method="GET" action="{{ route('jobs.index') }}" id="filter-form" class="space-y-8">
    {{-- Preserve search query and location --}}
    @if(request('q')) <input type="hidden" name="q" value="{{ request('q') }}"> @endif
    @if(request('location')) <input type="hidden" name="location" value="{{ request('location') }}"> @endif
    @if(request('sort')) <input type="hidden" name="sort" value="{{ request('sort') }}"> @endif

    {{-- Salary Range --}}
    <div>
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-sm font-bold text-slate-900 uppercase tracking-wider">Salary Range</h3>
            <span class="text-xs font-bold text-amber-600" id="salary-display">₱{{ number_format(request('salary_min', 15000)) }}k</span>
        </div>
        <div class="space-y-4">
            <input
                type="range"
                name="salary_min"
                min="15000"
                max="100000"
                step="5000"
                value="{{ request('salary_min', 15000) }}"
                oninput="document.getElementById('salary-display').innerText = '₱' + (this.value/1000) + 'k'"
                onchange="if(window.innerWidth >= 1024) submitFilterForm()"
                class="w-full h-2 bg-slate-200 rounded-lg appearance-none cursor-pointer accent-amber-500"
            >
            <div class="flex justify-between text-xs font-semibold text-slate-500">
                <span>₱15k</span>
                <span>₱100k+</span>
            </div>
        </div>
    </div>

    {{-- Employment Type --}}
    <div>
        <h3 class="text-sm font-bold text-slate-900 uppercase tracking-wider mb-4">Employment</h3>
        <div class="space-y-3">
            @foreach($employmentTypes as $type)
                <div class="flex items-center">
                    <input
                        type="checkbox"
                        name="type[]"
                        value="{{ $type->id }}"
                        id="type-{{ $type->id }}"
                        {{ is_array(request('type')) && in_array($type->id, request('type')) ? 'checked' : '' }}
                        onchange="if(window.innerWidth >= 1024) submitFilterForm()"
                        class="h-4 w-4 rounded border-slate-300 text-amber-600 focus:ring-amber-500"
                    >
                    <label for="type-{{ $type->id }}" class="ml-3 text-sm font-medium text-slate-600 hover:text-slate-900 cursor-pointer transition-colors">
                        {{ $type->name }}
                    </label>
                </div>
            @endforeach
        </div>
    </div>

    {{-- Location --}}
    <div>
        <h3 class="text-sm font-bold text-slate-900 uppercase tracking-wider mb-4">Location</h3>
        <div class="space-y-3">
            @foreach($locations as $loc)
                <div class="flex items-center">
                    <input
                        type="checkbox"
                        name="loc[]"
                        value="{{ $loc->id }}"
                        id="loc-{{ $loc->id }}"
                        {{ is_array(request('loc')) && in_array($loc->id, request('loc')) ? 'checked' : '' }}
                        onchange="if(window.innerWidth >= 1024) submitFilterForm()"
                        class="h-4 w-4 rounded border-slate-300 text-amber-600 focus:ring-amber-500"
                    >
                    <label for="loc-{{ $loc->id }}" class="ml-3 text-sm font-medium text-slate-600 hover:text-slate-900 cursor-pointer transition-colors">
                        {{ $loc->name }}
                    </label>
                </div>
            @endforeach
        </div>
    </div>

    @if(request()->anyFilled(['type', 'salary_min', 'loc', 'q', 'location']))
        <div class="pt-4">
            <a href="{{ route('jobs.index') }}" class="text-xs font-bold text-slate-400 hover:text-amber-600 uppercase tracking-widest transition-colors flex items-center gap-2">
                <x-framework.icon name="x-mark" class="h-3 w-3" />
                Clear All Filters
            </a>
        </div>
    @endif
</form>

<script>
    function submitFilterForm() {
        const form = document.getElementById('filter-form');
        if (form) form.submit();
    }
</script>
