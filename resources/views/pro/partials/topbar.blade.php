<header
    class="sticky top-0 z-30 border-b border-slate-200 bg-white/90 backdrop-blur"
>

    <div class="flex h-16 items-center justify-between px-4 lg:px-8">

        {{-- Left --}}
        <div class="flex items-center gap-3">

            {{-- Mobile Menu --}}
            <button
                type="button"
                @click="sidebarOpen = true"
                class="rounded-lg p-2 transition hover:bg-slate-100 lg:hidden"
            >

                <x-framework.icon
                    name="bars-3"
                    class="h-6 w-6"
                />

            </button>

            {{-- Mobile Brand --}}
            <div class="flex items-center gap-3 lg:hidden">

                <div
                    class="flex h-9 w-9 items-center justify-center rounded-lg bg-amber-500"
                >

                    <span class="font-black text-slate-900">

                        S

                    </span>

                </div>

                <span
                    class="font-bold tracking-tight text-slate-900"
                >

                    SEKYU PRO

                </span>

            </div>

            {{-- Desktop Search --}}
            <div class="relative hidden lg:block">

                <x-framework.icon
                    name="magnifying-glass"
                    class="pointer-events-none absolute left-3 top-1/2 h-5 w-5 -translate-y-1/2 text-slate-400"
                />

                <input
                    type="search"
                    placeholder="Search employees, assignments..."
                    class="w-96 rounded-xl border border-slate-300 bg-slate-50 py-2.5 pl-10 pr-4 text-sm transition focus:border-amber-500 focus:bg-white focus:outline-none"
                >

            </div>

        </div>

        {{-- Right --}}
        <div class="flex items-center gap-2 lg:gap-4">

            {{-- Notifications --}}
            <button
                type="button"
                class="relative rounded-xl p-2 transition hover:bg-slate-100"
            >

                <x-framework.icon
                    name="bell"
                    class="h-6 w-6 text-slate-600"
                />

                <span
                    class="absolute right-2 top-2 h-2 w-2 rounded-full bg-red-500"
                ></span>

            </button>

            {{-- Desktop User --}}
            <div class="hidden lg:block">

                @include('pro.partials.user-menu')

            </div>

            {{-- Mobile User --}}
            <div class="lg:hidden">

                @include('pro.partials.user-menu', [
                    'mobile' => true,
                ])

            </div>

        </div>

    </div>

</header>
