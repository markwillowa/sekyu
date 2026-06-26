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
                href="{{ route('jobs.index') }}"
                class="font-semibold text-slate-900 transition hover:text-amber-600"
            >
                View All Jobs →
            </a>

        </div>

        {{-- Jobs Grid --}}
        <x-framework.layout.grid cols="2" class="gap-6">

            @forelse ($jobs as $job)
                @if($job->is_featured)
                    @include('public.jobs.components.featured-card', ['job' => $job])
                @else
                    @include('public.jobs.components.job-card', ['job' => $job])
                @endif
            @empty
                <div class="col-span-full py-12 text-center">
                    <p class="text-slate-500">No job opportunities available at the moment.</p>
                </div>
            @endforelse

        </x-framework.layout.grid>

        {{-- Mobile CTA --}}
        <div class="mt-10 text-center md:hidden">
            <x-framework.buttons.secondary href="{{ route('jobs.index') }}">
                View All Jobs
            </x-framework.buttons.secondary>
        </div>

    </div>
</section>
