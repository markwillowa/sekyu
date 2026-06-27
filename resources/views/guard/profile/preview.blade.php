@extends('layouts.app')

@section('content')
    <section class="bg-slate-50 py-12">
        <div class="mx-auto max-w-6xl px-6">

            <div class="mb-6">
                <a
                    href="{{ route('applicant.profile.show') }}"
                    class="text-sm font-semibold text-amber-600 hover:text-amber-700"
                >
                    ← Back to Profile Editor
                </a>
            </div>

            <div class="overflow-hidden rounded-3xl border border-slate-200 bg-white shadow-sm">

                <div class="h-40 bg-slate-900"></div>

                <div class="px-8 pb-8">
                    <div class="-mt-16 flex flex-col gap-6 sm:flex-row sm:items-end sm:justify-between">
                        <div class="flex items-end gap-5">
                            <div class="flex h-32 w-32 items-center justify-center rounded-full border-4 border-white bg-slate-800 text-4xl font-bold text-white">
                                {{ strtoupper(substr($profile?->first_name ?? $user->name, 0, 1)) }}
                            </div>

                            <div class="pb-2">
                                <h1 class="text-3xl font-bold text-slate-900">
                                    {{ trim(
                                        ($profile?->first_name ?? '') . ' ' .
                                        ($profile?->middle_name ?? '') . ' ' .
                                        ($profile?->last_name ?? '') . ' ' .
                                        ($profile?->suffix ?? '')
                                    ) ?: $user->name }}
                                </h1>

                                <p class="mt-1 text-lg text-slate-600">
                                    {{ $profile?->headline ?: 'Professional Security Guard' }}
                                </p>

                                <p class="mt-1 text-sm text-slate-500">
                                    {{ $contactDetails?->current_address ?? 'Location not provided' }}
                                </p>
                            </div>
                        </div>

                    </div>

                    <div class="mt-8 flex flex-wrap gap-2">
                        @foreach ($specializations->take(6) as $specialization)
                            <span class="rounded-full bg-amber-100 px-3 py-1 text-sm font-semibold text-amber-700">
                                {{ $specialization->specialization?->name ?? 'Specialization' }}
                            </span>
                        @endforeach

                        @foreach ($skills->take(8) as $skill)
                            <span class="rounded-full bg-slate-100 px-3 py-1 text-sm font-semibold text-slate-700">
                                {{ $skill->skill?->name ?? 'Skill' }}
                            </span>
                        @endforeach
                    </div>

                    <div class="mt-8 border-t border-slate-200 pt-8">
                        <h2 class="text-xl font-bold text-slate-900">
                            About
                        </h2>

                        <p class="mt-3 leading-8 text-slate-700">
                            {{ $profile?->summary ?: 'No profile summary added yet.' }}
                        </p>
                    </div>
                </div>
            </div>

            <div class="mt-8 grid gap-8 lg:grid-cols-3">

                <aside class="space-y-8 lg:col-span-1">

                    <section class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm">
                        <h2 class="text-lg font-bold text-slate-900">
                            Profile Details
                        </h2>

                        <dl class="mt-5 space-y-4">
                            <div>
                                <dt class="text-sm font-medium text-slate-500">Email</dt>
                                <dd class="mt-1 text-slate-900">{{ $user->email }}</dd>
                            </div>

                            <div>
                                <dt class="text-sm font-medium text-slate-500">Mobile</dt>
                                <dd class="mt-1 text-slate-900">{{ $contactDetails?->mobile_number ?? 'Not provided' }}</dd>
                            </div>

                            <div>
                                <dt class="text-sm font-medium text-slate-500">Nationality</dt>
                                <dd class="mt-1 text-slate-900">{{ $profile?->nationality ?? 'Not provided' }}</dd>
                            </div>

                            <div>
                                <dt class="text-sm font-medium text-slate-500">Gender</dt>
                                <dd class="mt-1 text-slate-900">{{ $profile?->gender?->name ?? 'Not provided' }}</dd>
                            </div>
                        </dl>
                    </section>

                    <section class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm">
                        <h2 class="text-lg font-bold text-slate-900">
                            Credentials Snapshot
                        </h2>

                        <div class="mt-5 grid grid-cols-2 gap-4">
                            <div class="rounded-2xl bg-slate-50 p-4">
                                <div class="text-2xl font-bold text-slate-900">{{ $licenses->count() }}</div>
                                <div class="text-sm text-slate-500">Licenses</div>
                            </div>

                            <div class="rounded-2xl bg-slate-50 p-4">
                                <div class="text-2xl font-bold text-slate-900">{{ $trainings->count() }}</div>
                                <div class="text-sm text-slate-500">Trainings</div>
                            </div>

                            <div class="rounded-2xl bg-slate-50 p-4">
                                <div class="text-2xl font-bold text-slate-900">{{ $skills->count() }}</div>
                                <div class="text-sm text-slate-500">Skills</div>
                            </div>

                            <div class="rounded-2xl bg-slate-50 p-4">
                                <div class="text-2xl font-bold text-slate-900">{{ $clearances->count() }}</div>
                                <div class="text-sm text-slate-500">Clearances</div>
                            </div>

                            <div class="rounded-2xl bg-slate-50 p-4">
                                <div class="text-2xl font-bold text-slate-900">{{ $identifications->count() }}</div>
                                <div class="text-sm text-slate-500">IDs</div>
                            </div>
                        </div>
                    </section>

                </aside>

                <main class="space-y-8 lg:col-span-2">

                    <section class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm">
                        <h2 class="text-xl font-bold text-slate-900">
                            Work Experience
                        </h2>

                        @forelse ($workExperiences as $experience)
                            <div class="mt-6 border-t border-slate-200 pt-6 first:border-t-0 first:pt-0">
                                <h3 class="font-bold text-slate-900">
                                    {{ $experience->position }}
                                </h3>

                                <p class="mt-1 text-slate-700">
                                    {{ $experience->company_name }}
                                </p>

                                <p class="mt-1 text-sm text-slate-500">
                                    {{ $experience->started_at?->format('M Y') ?? 'Unknown' }}
                                    —
                                    {{ $experience->is_current ? 'Present' : ($experience->ended_at?->format('M Y') ?? 'Unknown') }}
                                </p>

                                @if ($experience->description)
                                    <p class="mt-3 leading-7 text-slate-600">
                                        {{ $experience->description }}
                                    </p>
                                @endif
                            </div>
                        @empty
                            <p class="mt-4 text-slate-500">
                                No work experience added yet.
                            </p>
                        @endforelse
                    </section>

                    <section class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm">
                        <h2 class="text-xl font-bold text-slate-900">
                            Education
                        </h2>

                        @forelse ($educations as $education)
                            <div class="mt-6 border-t border-slate-200 pt-6 first:border-t-0 first:pt-0">
                                <h3 class="font-bold text-slate-900">
                                    {{ $education->level }}
                                </h3>

                                <p class="mt-1 text-slate-700">
                                    {{ $education->school_name }}
                                </p>

                                <p class="mt-1 text-sm text-slate-500">
                                    {{ $education->started_year ?? 'Unknown' }}
                                    —
                                    {{ $education->is_current ? 'Present' : ($education->ended_year ?? 'Unknown') }}
                                </p>
                            </div>
                        @empty
                            <p class="mt-4 text-slate-500">
                                No educational background added yet.
                            </p>
                        @endforelse
                    </section>

                    <section class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm">
                        <h2 class="text-xl font-bold text-slate-900">
                            Licenses, Trainings & Certifications
                        </h2>

                        <div class="mt-6 grid gap-4 sm:grid-cols-2">
                            @foreach ($licenses as $license)
                                <div class="rounded-2xl border border-slate-200 p-4">
                                    <h3 class="font-bold text-slate-900">
                                        {{ $license->licenseType?->name ?? 'License' }}
                                    </h3>

                                    <p class="mt-1 text-sm text-slate-500">
                                        Expires:
                                        {{ $license->expires_at?->format('F d, Y') ?? 'Not provided' }}
                                    </p>
                                </div>
                            @endforeach

                            @foreach ($trainings as $training)
                                <div class="rounded-2xl border border-slate-200 p-4">
                                    <h3 class="font-bold text-slate-900">
                                        {{ $training->trainingType?->name ?? 'Training' }}
                                    </h3>

                                    <p class="mt-1 text-sm text-slate-500">
                                        {{ $training->provider ?? 'Provider not provided' }}
                                    </p>
                                </div>
                            @endforeach

                            @foreach ($certifications as $certification)
                                <div class="rounded-2xl border border-slate-200 p-4">
                                    <h3 class="font-bold text-slate-900">
                                        {{ $certification->name }}
                                    </h3>

                                    <p class="mt-1 text-sm text-slate-500">
                                        {{ $certification->issuer ?? 'Issuer not provided' }}
                                    </p>
                                </div>
                            @endforeach
                        </div>
                    </section>

                    <section class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm">
                        <h2 class="text-xl font-bold text-slate-900">
                            Documents & Clearances
                        </h2>

                        <div class="mt-6 space-y-4">
                            @if($clearances->isNotEmpty())
                                <div>
                                    <h3 class="text-sm font-bold text-slate-500 uppercase tracking-wider">Clearances</h3>
                                    <div class="mt-3 grid gap-4 sm:grid-cols-2">
                                        @foreach($clearances as $clearance)
                                            <div class="rounded-2xl border border-slate-200 p-4">
                                                <h4 class="font-bold text-slate-900">
                                                    {{ $clearance->clearanceType?->name ?? 'Clearance' }}
                                                </h4>
                                                <p class="mt-1 text-sm text-slate-500">
                                                    Expires: {{ $clearance->expires_at?->format('F d, Y') ?? 'Not provided' }}
                                                </p>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @endif

                            @if($identifications->isNotEmpty())
                                <div class="pt-4">
                                    <h3 class="text-sm font-bold text-slate-500 uppercase tracking-wider">Identifications</h3>
                                    <div class="mt-3 grid gap-4 sm:grid-cols-2">
                                        @foreach($identifications as $identification)
                                            <div class="rounded-2xl border border-slate-200 p-4">
                                                <h4 class="font-bold text-slate-900">
                                                    {{ $identification->identificationType?->name ?? $identification->id_type }}
                                                </h4>
                                                <p class="mt-1 text-sm text-slate-500">
                                                    ID No: {{ $identification->id_number }}
                                                </p>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @endif

                            @if($medicals->isNotEmpty())
                                <div class="pt-4">
                                    <h3 class="text-sm font-bold text-slate-500 uppercase tracking-wider">Medical Records</h3>
                                    <div class="mt-3 grid gap-4 sm:grid-cols-2">
                                        @foreach($medicals as $medical)
                                            <div class="rounded-2xl border border-slate-200 p-4">
                                                <h4 class="font-bold text-slate-900">
                                                    {{ $medical->certificate_type }}
                                                </h4>
                                                @if($medical->is_fit_to_work)
                                                    <span class="mt-2 inline-flex items-center rounded-md bg-green-50 px-2 py-1 text-xs font-medium text-green-700 ring-1 ring-inset ring-green-600/20">
                                                        Fit to Work
                                                    </span>
                                                @endif
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @endif
                        </div>
                    </section>

                </main>

            </div>
        </div>
    </section>
@endsection
