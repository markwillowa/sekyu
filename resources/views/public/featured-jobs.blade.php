<section class="bg-white py-24">
    <div class="mx-auto max-w-7xl px-6">

        {{-- Section Header --}}
        <div class="mb-12 flex flex-col gap-4 md:flex-row md:items-end md:justify-between">

            <div>

                <span
                    class="inline-flex rounded-full bg-amber-100 px-4 py-2 text-sm font-semibold text-amber-700"
                >
                    Featured Opportunities
                </span>

                <h2
                    class="mt-4 text-4xl font-bold tracking-tight text-slate-900"
                >
                    Security Jobs From Trusted Agencies
                </h2>

                <p
                    class="mt-3 max-w-2xl text-lg text-slate-600"
                >
                    Explore security opportunities from verified agencies
                    actively hiring across the Philippines.
                </p>

            </div>

            <a
                href="#"
                class="font-semibold text-slate-900 transition hover:text-amber-600"
            >
                View All Jobs →
            </a>

        </div>

        {{-- Jobs Grid --}}
        <div class="grid gap-6 lg:grid-cols-2">

            @foreach (range(1, 6) as $job)

                <article
                    class="group rounded-3xl border border-slate-200 bg-white p-6 transition-all duration-300 hover:-translate-y-1 hover:border-slate-300 hover:shadow-xl"
                >

                    {{-- Header --}}
                    <div class="flex items-start justify-between">

                        <div class="flex gap-4">

                            {{-- Agency Logo --}}
                            <div
                                class="flex h-14 w-14 shrink-0 items-center justify-center rounded-2xl bg-slate-100 text-lg font-bold text-slate-700"
                            >
                                AS
                            </div>

                            <div>

                                <div class="flex items-center gap-2">

                                    <h3
                                        class="font-semibold text-slate-900"
                                    >
                                        ABC Security Agency
                                    </h3>

                                    <span
                                        class="rounded-full bg-green-100 px-2 py-1 text-xs font-medium text-green-700"
                                    >
                                        Verified
                                    </span>

                                </div>

                                <p
                                    class="mt-1 text-sm text-slate-500"
                                >
                                    Metro Manila
                                </p>

                            </div>

                        </div>

                        {{-- Save Job --}}
                        <button
                            type="button"
                            class="rounded-xl p-2 text-slate-400 transition hover:bg-slate-100 hover:text-slate-700"
                        >
                            ☆
                        </button>

                    </div>

                    {{-- Job Title --}}
                    <h2
                        class="mt-6 text-2xl font-bold text-slate-900 transition group-hover:text-amber-600"
                    >
                        Security Guard - Makati
                    </h2>

                    {{-- Description --}}
                    <p
                        class="mt-3 line-clamp-2 text-slate-600"
                    >
                        We are looking for professional and licensed
                        security guards to join our growing team and
                        provide security services for commercial clients.
                    </p>

                    {{-- Tags --}}
                    <div
                        class="mt-5 flex flex-wrap gap-2"
                    >

                        <span
                            class="rounded-full bg-slate-100 px-3 py-1 text-sm text-slate-700"
                        >
                            Full-Time
                        </span>

                        <span
                            class="rounded-full bg-slate-100 px-3 py-1 text-sm text-slate-700"
                        >
                            Day Shift
                        </span>

                        <span
                            class="rounded-full bg-slate-100 px-3 py-1 text-sm text-slate-700"
                        >
                            Makati City
                        </span>

                    </div>

                    {{-- Salary --}}
                    <div
                        class="mt-5 text-lg font-semibold text-slate-900"
                    >
                        ₱18,000 – ₱22,000 / month
                    </div>

                    {{-- Footer --}}
                    <div
                        class="mt-6 flex items-center justify-between border-t border-slate-100 pt-5"
                    >

                        <div
                            class="flex items-center gap-4 text-sm text-slate-500"
                        >
                            <span>
                                Posted 2 days ago
                            </span>

                            <span>
                                25 Applicants
                            </span>
                        </div>

                        <a
                            href="#"
                            class="font-semibold text-amber-600 transition hover:text-amber-700"
                        >
                            View Job →
                        </a>

                    </div>

                </article>

            @endforeach

        </div>

        {{-- Mobile CTA --}}
        <div class="mt-10 text-center md:hidden">

            <a
                href="#"
                class="inline-flex rounded-xl border border-slate-300 px-5 py-3 font-medium text-slate-700 transition hover:bg-slate-100"
            >
                View All Jobs
            </a>

        </div>

    </div>
</section>
