<header
    class="sticky top-0 z-50 border-b border-slate-200 bg-white/95 backdrop-blur"
>
    <div
        class="mx-auto flex h-20 max-w-7xl items-center justify-between px-6"
    >
        {{-- Logo --}}
        <div class="flex items-center gap-12">

            <a
                href="/"
                class="flex items-center gap-3"
            >
                <div
                    class="flex h-10 w-10 items-center justify-center rounded-lg bg-slate-900 text-lg font-bold text-white"
                >
                    S
                </div>

                <div>
                    <div
                        class="text-xl font-bold tracking-wide text-slate-900"
                    >
                        SEKYU
                    </div>

                    <div
                        class="-mt-1 text-xs uppercase tracking-widest text-slate-500"
                    >
                        Security Careers
                    </div>
                </div>
            </a>

            {{-- Desktop Navigation --}}
            <nav class="hidden items-center gap-8 lg:flex">

                <a
                    href="#"
                    class="font-medium text-slate-700 transition hover:text-slate-900"
                >
                    Jobs
                </a>

                <a
                    href="#"
                    class="font-medium text-slate-700 transition hover:text-slate-900"
                >
                    Agencies
                </a>

                <a
                    href="#"
                    class="font-medium text-slate-700 transition hover:text-slate-900"
                >
                    Guard Directory
                </a>

                <a
                    href="#"
                    class="font-medium text-slate-700 transition hover:text-slate-900"
                >
                    Resources
                </a>

            </nav>

        </div>

        {{-- Right Side --}}
        <div class="hidden items-center gap-3 lg:flex">

            <a
                href="#"
                class="rounded-lg px-4 py-2 font-medium text-slate-700 transition hover:bg-slate-100"
            >
                Login
            </a>

            <a
                href="#"
                class="rounded-lg border border-slate-300 px-5 py-2 font-medium text-slate-700 transition hover:bg-slate-50"
            >
                Register Agency
            </a>

            <a
                href="#"
                class="rounded-lg bg-amber-500 px-5 py-2 font-semibold text-white transition hover:bg-amber-600"
            >
                Find Jobs
            </a>

        </div>

        {{-- Mobile Menu Button --}}
        <button
            class="flex h-10 w-10 items-center justify-center rounded-lg border border-slate-200 lg:hidden"
        >
            <svg
                xmlns="http://www.w3.org/2000/svg"
                class="h-5 w-5"
                fill="none"
                viewBox="0 0 24 24"
                stroke="currentColor"
            >
                <path
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    stroke-width="2"
                    d="M4 6h16M4 12h16M4 18h16"
                />
            </svg>
        </button>

    </div>
</header>
