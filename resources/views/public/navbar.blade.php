<header
    x-data="{ mobileMenuOpen: false }"
    class="sticky top-0 z-50 border-b border-slate-200 bg-white/95 backdrop-blur"
>
    <div class="mx-auto flex h-16 max-w-7xl items-center justify-between px-4 sm:h-20 sm:px-6">
        {{-- Logo --}}
        <div class="flex items-center gap-8 xl:gap-12">
            <a href="{{ route('home') }}" class="flex items-center gap-3">
                <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-slate-900 text-lg font-bold text-white">
                    S
                </div>

                <div>
                    <div class="text-xl font-bold tracking-wide text-slate-900">
                        SEKYU
                    </div>

                    <div class="-mt-1 text-xs uppercase tracking-widest text-slate-500">
                        Security Careers
                    </div>
                </div>
            </a>

            {{-- Desktop Navigation --}}
            <nav class="hidden items-center gap-8 lg:flex">
                <a href="{{ route('jobs.index') }}" class="font-medium text-slate-700 transition hover:text-slate-900">Jobs</a>
                <a href="#" class="font-medium text-slate-700 transition hover:text-slate-900">Agencies</a>
                <a href="#" class="font-medium text-slate-700 transition hover:text-slate-900">Guard Directory</a>
                <a href="#" class="font-medium text-slate-700 transition hover:text-slate-900">Resources</a>
            </nav>
        </div>

        {{-- Desktop Right Side --}}
        <div class="hidden items-center gap-3 lg:flex">
            <a
                href="{{ route('pro.login') }}"
                class="rounded-full bg-slate-900 px-4 py-2 text-xs font-black uppercase tracking-wide text-amber-400 transition hover:bg-slate-800"
                title="SEKYU PRO"
            >
                PRO
            </a>

            @auth
                <div class="relative" x-data="{ open: false }">
                    <button @click="open = !open" class="relative rounded-full p-2 text-slate-400 hover:bg-slate-100 hover:text-slate-600 focus:outline-none">
                        <x-framework.icon name="bell" class="h-6 w-6" />
                        @if(auth()->user()->unreadNotifications->count() > 0)
                            <span class="absolute top-1 right-1 block h-2.5 w-2.5 rounded-full bg-rose-500 ring-2 ring-white"></span>
                        @endif
                    </button>

                    <div x-show="open" @click.away="open = false" class="absolute right-0 mt-2 w-80 rounded-2xl border border-slate-200 bg-white py-2 shadow-2xl z-50 overflow-hidden" style="display: none;">
                        <div class="px-4 py-2 border-b border-slate-100 flex justify-between items-center">
                            <span class="text-xs font-black uppercase tracking-widest text-slate-400">Notifications</span>
                            <div class="flex items-center gap-3">
                                @if(auth()->user()->unreadNotifications->count() > 0)
                                    <form action="{{ route('notifications.mark-all-read') }}" method="POST">
                                        @csrf
                                        <button type="submit" class="text-[10px] font-bold text-blue-600 uppercase hover:underline">Mark all read</button>
                                    </form>
                                @endif

                                @if(auth()->user()->notifications->count() > 0)
                                    <form action="{{ route('notifications.clear') }}" method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-[10px] font-bold text-rose-600 uppercase hover:underline">Clear</button>
                                    </form>
                                @endif
                            </div>
                        </div>
                        <div class="max-h-96 overflow-y-auto">
                            @forelse(auth()->user()->notifications->take(5) as $notification)
                                <div class="px-4 py-3 hover:bg-slate-50 border-b border-slate-50 last:border-0 transition-colors">
                                    <p class="text-sm text-slate-900 font-medium leading-snug">
                                        {{ $notification->data['message'] ?? 'Notification received' }}
                                    </p>
                                    <p class="text-[10px] text-slate-400 mt-1 uppercase font-bold tracking-wider">{{ $notification->created_at->diffForHumans() }}</p>
                                </div>
                            @empty
                                <div class="px-4 py-8 text-center">
                                    <x-framework.icon name="bell-slash" class="h-10 w-10 text-slate-200 mx-auto mb-2" />
                                    <p class="text-sm text-slate-400">No notifications yet</p>
                                </div>
                            @endforelse
                        </div>
                        <a href="#" class="block px-4 py-2 text-center text-xs font-bold text-slate-500 bg-slate-50 hover:bg-slate-100">
                            View all notifications
                        </a>
                    </div>
                </div>
            @endauth

            @guest
                <button
                    @click="$dispatch('open-modal', 'login-modal')"
                    class="rounded-lg px-4 py-2 font-medium text-slate-700 transition hover:bg-slate-100"
                >
                    Login
                </button>

                <a href="{{ route('agency.register') }}" class="rounded-lg border border-slate-300 px-5 py-2 font-medium text-slate-700 transition hover:bg-slate-50">
                    Register Agency
                </a>

                <a href="{{ route('jobs.index') }}" class="rounded-lg bg-amber-500 px-5 py-2 font-semibold text-white transition hover:bg-amber-600">
                    Find Jobs
                </a>
            @endguest

            @auth
                @if(auth()->user()->hasRole('agency'))
                    <a href="{{ route('agency.dashboard') }}" class="rounded-lg bg-amber-500 px-5 py-2 font-semibold text-white transition hover:bg-amber-600">
                        Dashboard
                    </a>
                @else
                    <a href="{{ route('applicant.dashboard') }}" class="rounded-lg bg-amber-500 px-5 py-2 font-semibold text-white transition hover:bg-amber-600">
                        Dashboard
                    </a>
                @endif

                <details class="relative">
                    <summary class="flex cursor-pointer list-none items-center gap-3 rounded-lg px-3 py-2 transition hover:bg-slate-100">
                        <div class="flex h-10 w-10 items-center justify-center rounded-full bg-slate-900 font-semibold text-white">
                            {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                        </div>

                        <div class="text-left">
                            <div class="text-sm font-semibold text-slate-900">
                                {{ auth()->user()->name }}
                            </div>

                            <div class="text-xs text-slate-500">
                                @if(auth()->user()->hasRole('agency'))
                                    Agency
                                @elseif(auth()->user()->hasRole('admin'))
                                    Administrator
                                @else
                                    Guard
                                @endif
                            </div>
                        </div>
                    </summary>

                    <div class="absolute right-0 mt-2 w-64 rounded-xl border border-slate-200 bg-white py-2 shadow-xl">
                        @if(auth()->user()->hasRole('agency'))
                            <a href="{{ route('agency.dashboard') }}" class="block px-4 py-2 text-sm text-slate-700 hover:bg-slate-100">
                                Agency Dashboard
                            </a>

                            <a href="#" class="block px-4 py-2 text-sm text-slate-700 hover:bg-slate-100">
                                Company Profile
                            </a>

                            <a href="{{ route('agency.job-posts.index') }}" class="block px-4 py-2 text-sm text-slate-700 hover:bg-slate-100">
                                Job Posts
                            </a>

                            <a href="{{ route('agency.workflow-templates.index') }}" class="block px-4 py-2 text-sm text-slate-700 hover:bg-slate-100">
                                Workflow Templates
                            </a>

                            <a href="{{ route('agency.applications.index') }}" class="block px-4 py-2 text-sm text-slate-700 hover:bg-slate-100">
                                Applications
                            </a>
                        @else
                            <a href="{{ route('applicant.dashboard') }}" class="block px-4 py-2 text-sm text-slate-700 hover:bg-slate-100">
                                Dashboard
                            </a>

                            <a href="{{ route('applicant.profile.show') }}" class="block px-4 py-2 text-sm text-slate-700 hover:bg-slate-100">
                                My Profile
                            </a>

                            <a href="{{ route('applicant.applications.index') }}" class="block px-4 py-2 text-sm text-slate-700 hover:bg-slate-100">
                                My Applications
                            </a>

                            <a href="#" class="block px-4 py-2 text-sm text-slate-700 hover:bg-slate-100">
                                My Documents
                            </a>
                        @endif

                        <hr class="my-2 border-slate-200">

                        <form
                            method="POST"
                            action="{{ auth()->user()->hasRole('agency') ? route('agency.logout') : route('applicant.logout') }}"
                        >
                            @csrf

                            <button type="submit" class="block w-full px-4 py-2 text-left text-sm text-red-600 hover:bg-red-50">
                                Logout
                            </button>
                        </form>
                    </div>
                </details>
            @endauth
        </div>

        {{-- Mobile Right Side --}}
        <div class="flex items-center gap-2 lg:hidden">
            <a
                href="{{ route('pro.login') }}"
                class="rounded-full bg-slate-900 px-3 py-2 text-xs font-black uppercase tracking-wide text-amber-400 transition hover:bg-slate-800"
                title="SEKYU PRO"
            >
                PRO
            </a>

            <button
                type="button"
                x-on:click="mobileMenuOpen = ! mobileMenuOpen"
                class="flex h-10 w-10 items-center justify-center rounded-lg border border-slate-200"
                aria-label="Toggle mobile menu"
            >
                <svg
                    x-show="! mobileMenuOpen"
                    xmlns="http://www.w3.org/2000/svg"
                    class="h-5 w-5"
                    fill="none"
                    viewBox="0 0 24 24"
                    stroke="currentColor"
                >
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                </svg>

                <svg
                    x-show="mobileMenuOpen"
                    x-cloak
                    xmlns="http://www.w3.org/2000/svg"
                    class="h-5 w-5"
                    fill="none"
                    viewBox="0 0 24 24"
                    stroke="currentColor"
                >
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18 18 6M6 6l12 12" />
                </svg>
            </button>
        </div>
    </div>

    {{-- Mobile Menu --}}
    <div
        x-show="mobileMenuOpen"
        x-cloak
        x-transition
        class="border-t border-slate-200 bg-white lg:hidden"
    >
        <div class="mx-auto max-w-7xl space-y-1 px-4 py-4 sm:px-6">
            <a href="{{ route('pro.login') }}" class="mb-3 block rounded-xl bg-slate-900 px-4 py-3 text-center font-black uppercase tracking-wide text-amber-400">
                SEKYU PRO
            </a>

            <a href="{{ route('jobs.index') }}" class="block rounded-lg px-4 py-3 font-medium text-slate-700 hover:bg-slate-100">
                Jobs
            </a>

            <a href="#" class="block rounded-lg px-4 py-3 font-medium text-slate-700 hover:bg-slate-100">
                Agencies
            </a>

            <a href="#" class="block rounded-lg px-4 py-3 font-medium text-slate-700 hover:bg-slate-100">
                Guard Directory
            </a>

            <a href="#" class="block rounded-lg px-4 py-3 font-medium text-slate-700 hover:bg-slate-100">
                Resources
            </a>

            <hr class="my-3 border-slate-200">

            @guest
                <button
                    @click="$dispatch('open-modal', 'login-modal')"
                    class="block w-full rounded-lg px-4 py-3 text-left font-medium text-slate-700 hover:bg-slate-100"
                >
                    Login
                </button>

                <a href="{{ route('agency.register') }}" class="block rounded-lg px-4 py-3 font-medium text-slate-700 hover:bg-slate-100">
                    Register Agency
                </a>

                <a href="{{ route('jobs.index') }}" class="mt-3 block rounded-lg bg-amber-500 px-4 py-3 text-center font-semibold text-white hover:bg-amber-600">
                    Find Jobs
                </a>
            @endguest

            @auth
                @if(auth()->user()->hasRole('agency'))
                    <a href="{{ route('agency.dashboard') }}" class="block rounded-lg px-4 py-3 font-medium text-slate-700 hover:bg-slate-100">
                        Agency Dashboard
                    </a>

                    <a href="#" class="block rounded-lg px-4 py-3 font-medium text-slate-700 hover:bg-slate-100">
                        Company Profile
                    </a>

                    <a href="{{ route('agency.job-posts.index') }}" class="block rounded-lg px-4 py-3 font-medium text-slate-700 hover:bg-slate-100">
                        Job Posts
                    </a>

                    <a href="{{ route('agency.applications.index') }}" class="block rounded-lg px-4 py-3 font-medium text-slate-700 hover:bg-slate-100">
                        Applications
                    </a>
                @else
                    <a href="{{ route('applicant.dashboard') }}" class="block rounded-lg px-4 py-3 font-medium text-slate-700 hover:bg-slate-100">
                        Dashboard
                    </a>

                    <a href="{{ route('applicant.profile.show') }}" class="block rounded-lg px-4 py-3 font-medium text-slate-700 hover:bg-slate-100">
                        My Profile
                    </a>

                    <a href="{{ route('applicant.applications.index') }}" class="block rounded-lg px-4 py-3 font-medium text-slate-700 hover:bg-slate-100">
                        My Applications
                    </a>

                    <a href="#" class="block rounded-lg px-4 py-3 font-medium text-slate-700 hover:bg-slate-100">
                        My Documents
                    </a>

                    <a href="#" class="mt-3 block rounded-lg bg-amber-500 px-4 py-3 text-center font-semibold text-white hover:bg-amber-600">
                        Find Jobs
                    </a>
                @endif

                <form
                    method="POST"
                    action="{{ auth()->user()->hasRole('agency') ? route('agency.logout') : route('applicant.logout') }}"
                    class="mt-3"
                >
                    @csrf

                    <button type="submit" class="block w-full rounded-lg px-4 py-3 text-left font-medium text-red-600 hover:bg-red-50">
                        Logout
                    </button>
                </form>
            @endauth
        </div>
    </div>
</header>
