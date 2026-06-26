<section class="relative overflow-hidden bg-slate-50">
    <div class="mx-auto max-w-7xl px-6 py-20 lg:py-28">

        <div class="mx-auto max-w-5xl text-center">

            <div
                class="mb-6 inline-flex items-center gap-2 rounded-full border border-amber-200 bg-amber-50 px-4 py-2 text-sm font-medium text-amber-700"
            >
                Trusted by Security Professionals
            </div>

            <h1
                class="text-5xl font-bold leading-tight text-slate-900 lg:text-6xl"
            >
                Find Your Next Security Role
            </h1>

            <p
                class="mx-auto mt-6 max-w-3xl text-lg leading-8 text-slate-600"
            >
                Browse through active job postings from verified security agencies across the Philippines and discover opportunities that match your skills.
            </p>

            {{-- Search Area --}}
            <div class="mx-auto mt-12 max-w-5xl">

                <form
                    action="{{ route('jobs.index') }}"
                    method="GET"
                    class="rounded-2xl border border-slate-200 bg-white p-2 shadow-lg"
                >

                    <div class="grid gap-2 lg:grid-cols-12">

                        {{-- Job --}}
                        <div class="lg:col-span-5">

                            <input
                                type="text"
                                name="q"
                                value="{{ request('q') }}"
                                placeholder="Search security jobs"
                                class="w-full rounded-xl border-0 px-5 py-4 focus:ring-0"
                            >

                        </div>

                        {{-- Divider --}}
                        <div
                            class="hidden border-r border-slate-200 lg:block"
                        ></div>

                        {{-- Location --}}
                        <div class="lg:col-span-4">

                            <input
                                type="text"
                                name="location"
                                value="{{ request('location') }}"
                                placeholder="Location"
                                class="w-full rounded-xl border-0 px-5 py-4 focus:ring-0"
                            >

                        </div>

                        {{-- Button --}}
                        <div class="lg:col-span-2">

                            <button
                                type="submit"
                                class="w-full rounded-xl bg-amber-500 px-6 py-4 font-semibold text-white transition hover:bg-amber-600"
                            >
                                Search
                            </button>

                        </div>

                    </div>

                </form>

            </div>

            {{-- Trust Indicators --}}
            <div
                class="mt-10 flex flex-wrap justify-center gap-8 text-sm text-slate-600"
            >

                <div>
                    ✓ Verified Agencies
                </div>

                <div>
                    ✓ Security-Focused Platform
                </div>

                <div>
                    ✓ Direct Applications
                </div>

            </div>

        </div>

    </div>
</section>
