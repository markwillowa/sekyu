<section class="bg-white py-24">
    <div class="mx-auto max-w-7xl px-6">

        {{-- Section Header --}}
        <div class="mb-12 flex flex-col gap-4 md:flex-row md:items-end md:justify-between">

            <div>

                <span
                    class="inline-flex rounded-full bg-amber-100 px-4 py-2 text-sm font-semibold text-amber-700 uppercase tracking-wider"
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
        <x-framework.layout.grid cols="2">

            @forelse ($jobs as $job)
                <x-framework.layout.card
                    class="group transition-all duration-300 hover:-translate-y-1 hover:border-slate-300 hover:shadow-xl"
                >
                    {{-- Header --}}
                    <div class="flex items-start justify-between">
                        <div class="flex gap-4">
                            {{-- Agency Logo --}}
                            <div
                                class="flex h-14 w-14 shrink-0 items-center justify-center rounded-2xl bg-slate-100 text-lg font-bold text-slate-700 uppercase"
                            >
                                {{ substr($job->agency->name, 0, 2) }}
                            </div>

                            <div>
                                <div class="flex items-center gap-2">
                                    <h3 class="font-semibold text-slate-900">
                                        {{ $job->agency->name }}
                                    </h3>

                                    @if($job->agency->verified_at)
                                        <span class="rounded-full bg-green-100 px-2 py-0.5 text-[10px] font-bold text-green-700 uppercase tracking-wider">
                                            Verified
                                        </span>
                                    @endif
                                </div>

                                <p class="mt-1 text-sm text-slate-500">
                                    {{ $job->city }}, {{ $job->province }}
                                </p>
                            </div>
                        </div>

                        {{-- Save Job --}}
                        <button
                            type="button"
                            class="rounded-xl p-2 text-slate-400 transition hover:bg-slate-100 hover:text-slate-700"
                        >
                            <x-framework.icon name="star" class="h-6 w-6" />
                        </button>
                    </div>

                    {{-- Job Title --}}
                    <h2 class="mt-6 text-2xl font-bold text-slate-900 transition group-hover:text-amber-600">
                        {{ $job->title }}
                    </h2>

                    {{-- Description --}}
                    <div class="mt-3 line-clamp-2 text-slate-600 prose prose-sm max-w-none">
                        {!! $job->description !!}
                    </div>

                    {{-- Tags --}}
                    <div class="mt-5 flex flex-wrap gap-2">
                        @if($job->employmentType)
                            <span class="rounded-full bg-slate-100 px-3 py-1 text-sm text-slate-700">
                                {{ $job->employmentType->name }}
                            </span>
                        @endif

                        @if($job->workLocationType)
                            <span class="rounded-full bg-slate-100 px-3 py-1 text-sm text-slate-700">
                                {{ $job->workLocationType->name }}
                            </span>
                        @endif

                        <span class="rounded-full bg-slate-100 px-3 py-1 text-sm text-slate-700">
                            {{ $job->city }}
                        </span>
                    </div>

                    {{-- Salary --}}
                    <div class="mt-5 text-lg font-semibold text-slate-900">
                        @if($job->salary_min && $job->salary_max)
                            ₱{{ number_format($job->salary_min) }} – ₱{{ number_format($job->salary_max) }}
                            @if($job->salaryType)
                                / {{ strtolower($job->salaryType->name) }}
                            @endif
                        @else
                            Salary Negotiable
                        @endif
                    </div>

                    {{-- Footer --}}
                    <div class="mt-6 flex items-center justify-between border-t border-slate-100 pt-5">
                        <div class="flex items-center gap-4 text-sm text-slate-500">
                            <span class="flex items-center gap-1">
                                <x-framework.icon name="clock" class="h-4 w-4" />
                                {{ $job->published_at ? $job->published_at->diffForHumans() : 'Recently' }}
                            </span>

                            <span class="flex items-center gap-1">
                                <x-framework.icon name="users" class="h-4 w-4" />
                                {{ $job->vacancies }} Vacancies
                            </span>
                        </div>

                        <a
                            href="#"
                            class="font-semibold text-amber-600 transition hover:text-amber-700"
                        >
                            View Job →
                        </a>
                    </div>
                </x-framework.layout.card>

            @empty
                <div class="col-span-full py-12 text-center">
                    <p class="text-slate-500">No job opportunities available at the moment.</p>
                </div>
            @endforelse

        </x-framework.layout.grid>

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
