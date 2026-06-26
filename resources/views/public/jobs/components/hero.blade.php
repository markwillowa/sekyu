<section class="relative overflow-hidden bg-slate-50 border-b border-slate-200 mt-4">
    <div class="mx-auto max-w-7xl px-6 py-10 lg:pt-12 lg:pb-16">

        <div class="mx-auto max-w-5xl">

            <div class="text-center px-4">
                <h1 class="text-4xl font-black leading-none text-slate-900 md:text-5xl lg:text-6xl tracking-tighter">
                    Find Your Next <span class="text-amber-600">Security Career</span>
                </h1>

                <p class="mt-4 lg:mt-6 mx-auto max-w-2xl text-lg lg:text-xl text-slate-500 font-medium leading-relaxed">
                    Search thousands of licensed security jobs from verified agencies across the Philippines.
                </p>
            </div>

            {{-- Search Area --}}
            <div class="mt-12 max-w-5xl">

                <form action="{{ route('jobs.index') }}" method="GET" class="rounded-3xl border border-slate-200 bg-white p-2 shadow-sm ring-1 ring-slate-900/5 focus-within:ring-amber-500 focus-within:shadow-md transition-all">

                    <div class="flex flex-col gap-2 lg:flex-row lg:items-center">

                        {{-- Search Input --}}
                        <div class="flex-1 flex items-center">
                            <x-framework.icon name="magnifying-glass" class="ml-4 h-5 w-5 text-slate-400" />
                            <input type="text" name="q" value="{{ request('q') }}" placeholder="Search jobs (e.g. Security Guard)" class="w-full border-0 px-4 py-3 text-slate-900 placeholder:text-slate-400 focus:ring-0">
                        </div>

                        {{-- Divider --}}
                        <div class="hidden h-8 w-px bg-slate-200 lg:block"></div>

                        {{-- Location --}}
                        <div class="flex-1 flex items-center">
                            <x-framework.icon name="map-pin" class="ml-4 h-5 w-5 text-slate-400" />
                            <input type="text" name="location" value="{{ request('location') }}" placeholder="Location" class="w-full border-0 px-4 py-3 text-slate-900 placeholder:text-slate-400 focus:ring-0">
                        </div>

                        {{-- Button --}}
                        <div class="px-2 pb-2 lg:p-0">
                            <x-framework.buttons.primary type="submit" class="w-full px-8 py-3 rounded-2xl">
                                Search
                            </x-framework.buttons.primary>
                        </div>

                    </div>

                </form>

            </div>

            {{-- Popular Searches --}}
            <div class="mt-8 flex flex-wrap justify-center items-center gap-2 md:gap-3 px-4">
                <span class="text-xs md:text-sm font-medium text-slate-500 w-full md:w-auto text-center md:text-left mb-1 md:mb-0">Popular:</span>
                @foreach(['Security Guard', 'Lady Guard', 'CCTV Operator', 'Escort', 'Bodyguard'] as $tag)
                    <a href="{{ route('jobs.index', ['q' => $tag]) }}" class="rounded-full bg-white px-3 md:px-4 py-1 md:py-1.5 text-[10px] md:text-xs font-semibold text-slate-600 border border-slate-200 hover:border-amber-500 hover:text-amber-600 transition-colors">
                        {{ $tag }}
                    </a>
                @endforeach
            </div>

        </div>

    </div>
</section>

{{-- Quick Stats --}}
<section class="bg-white py-8 border-b border-slate-100">
    <div class="mx-auto max-w-7xl px-6">
        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-8">
            <div class="flex flex-col">
                <span class="text-2xl font-bold text-slate-900">25,000+</span>
                <span class="text-sm text-slate-500">Active Jobs</span>
            </div>
            <div class="flex flex-col">
                <span class="text-2xl font-bold text-slate-900">400+</span>
                <span class="text-sm text-slate-500">Verified Agencies</span>
            </div>
            <div class="flex flex-col">
                <span class="text-2xl font-bold text-slate-900">10,000+</span>
                <span class="text-sm text-slate-500">Guards Hired</span>
            </div>
            <div class="hidden lg:flex flex-col">
                <span class="text-2xl font-bold text-slate-900">100%</span>
                <span class="text-sm text-slate-500">Security Focused</span>
            </div>
        </div>
    </div>
</section>
