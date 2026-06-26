<div class="space-y-8">

    {{-- Salary Range --}}
    <div>
        <h3 class="text-sm font-bold text-slate-900 uppercase tracking-wider mb-4">Salary Range</h3>
        <div class="space-y-4">
            <input type="range" min="15000" max="100000" step="5000" class="w-full h-2 bg-slate-200 rounded-lg appearance-none cursor-pointer accent-amber-500">
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
            @foreach(['Full Time', 'Part Time', 'Contract', 'Temporary'] as $type)
                <x-framework.forms.checkbox :label="$type" :name="'type[]'" class="group-hover:text-slate-900 transition-colors" />
            @endforeach
        </div>
    </div>

    {{-- Experience --}}
    <div>
        <h3 class="text-sm font-bold text-slate-900 uppercase tracking-wider mb-4">Experience</h3>
        <div class="space-y-3">
            @foreach(['Fresh Graduate', '1 Year', '3+ Years', '5+ Years'] as $exp)
                <x-framework.forms.checkbox :label="$exp" :name="'exp[]'" class="group-hover:text-slate-900 transition-colors" />
            @endforeach
        </div>
    </div>

    {{-- License --}}
    <div>
        <h3 class="text-sm font-bold text-slate-900 uppercase tracking-wider mb-4">License</h3>
        <div class="space-y-3">
            @foreach(['Security License', 'Firearms License', 'Driver\'s License'] as $lic)
                <x-framework.forms.checkbox :label="$lic" :name="'lic[]'" class="group-hover:text-slate-900 transition-colors" />
            @endforeach
        </div>
    </div>

    {{-- Shift --}}
    <div>
        <h3 class="text-sm font-bold text-slate-900 uppercase tracking-wider mb-4">Shift</h3>
        <div class="space-y-3">
            @foreach(['Day Shift', 'Night Shift', 'Rotating'] as $shift)
                <x-framework.forms.checkbox :label="$shift" :name="'shift[]'" class="group-hover:text-slate-900 transition-colors" />
            @endforeach
        </div>
    </div>

    {{-- Location --}}
    <div>
        <h3 class="text-sm font-bold text-slate-900 uppercase tracking-wider mb-4">Location</h3>
        <div class="space-y-3">
            @foreach(['NCR', 'Cebu', 'Davao', 'Iloilo', 'Pampanga'] as $loc)
                <x-framework.forms.checkbox :label="$loc" :name="'loc[]'" class="group-hover:text-slate-900 transition-colors" />
            @endforeach
        </div>
    </div>

</div>
