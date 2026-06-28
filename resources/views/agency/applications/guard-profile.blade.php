@extends('layouts.app')

@section('content')
    <section class="bg-slate-50 py-12">
        <div class="mx-auto max-w-6xl px-6">

            <div class="mb-6 flex justify-between items-center">
                <a
                    href="javascript:history.back()"
                    class="text-sm font-semibold text-blue-600 hover:text-blue-700"
                >
                    ← Back to Application
                </a>

                <div class="flex items-center space-x-2">
                    <span class="text-sm text-slate-500">Profile Completion:</span>
                    <div class="w-32 bg-slate-200 rounded-full h-2">
                        <div class="bg-blue-600 h-2 rounded-full" style="width: {{ $completion }}%"></div>
                    </div>
                    <span class="text-sm font-bold text-slate-700">{{ $completion }}%</span>
                </div>
            </div>

            <div class="overflow-hidden rounded-3xl border border-slate-200 bg-white shadow-sm">

                <div class="h-40 bg-slate-900 flex items-center justify-end px-8">
                     <x-framework.feedback.badge color="blue" class="text-lg py-2 px-4">
                        {{ $profile->headline ?? 'Professional Security Guard' }}
                    </x-framework.feedback.badge>
                </div>

                <div class="px-8 pb-8">
                    <div class="-mt-16 flex flex-col gap-6 sm:flex-row sm:items-end sm:justify-between">
                        <div class="flex items-end gap-5">
                            @if($profile && $profile->hasMedia('profile-photo'))
                                <img src="{{ $profile->getFirstMediaUrl('profile-photo') }}" alt="{{ $user->name }}" class="h-32 w-32 rounded-full border-4 border-white object-cover bg-slate-800 shadow-md">
                            @else
                                <div class="flex h-32 w-32 items-center justify-center rounded-full border-4 border-white bg-slate-800 text-4xl font-bold text-white shadow-md">
                                    {{ strtoupper(substr($profile?->first_name ?? $user->name, 0, 1)) }}
                                </div>
                            @endif

                            <div class="pb-2">
                                <h1 class="text-3xl font-bold text-slate-900">
                                    {{ trim(
                                        ($profile?->first_name ?? '') . ' ' .
                                        ($profile?->middle_name ?? '') . ' ' .
                                        ($profile?->last_name ?? '') . ' ' .
                                        ($profile?->suffix ?? '')
                                    ) ?: $user->name }}
                                </h1>

                                <p class="mt-1 text-sm text-slate-500">
                                    {{ $profile->contactDetails?->current_address ?? 'Location not provided' }}
                                </p>
                            </div>
                        </div>
                    </div>

                    <div class="mt-8 flex flex-wrap gap-2">
                        @foreach ($profile->specializations->take(6) as $specialization)
                            <span class="rounded-full bg-blue-100 px-3 py-1 text-sm font-semibold text-blue-700">
                                {{ $specialization->specialization?->name ?? 'Specialization' }}
                            </span>
                        @endforeach

                        @foreach ($profile->skills->take(8) as $skill)
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
                            Contact Information
                        </h2>

                        <dl class="mt-5 space-y-4">
                            <div>
                                <dt class="text-sm font-medium text-slate-500 uppercase tracking-wider text-[10px]">Email</dt>
                                <dd class="mt-1 text-slate-900 font-medium">{{ $user->email }}</dd>
                            </div>

                            <div>
                                <dt class="text-sm font-medium text-slate-500 uppercase tracking-wider text-[10px]">Mobile</dt>
                                <dd class="mt-1 text-slate-900 font-medium">{{ $profile->contactDetails?->mobile_number ?? 'Not provided' }}</dd>
                            </div>

                            <div>
                                <dt class="text-sm font-medium text-slate-500 uppercase tracking-wider text-[10px]">Nationality</dt>
                                <dd class="mt-1 text-slate-900 font-medium">{{ $profile?->nationality ?? 'Not provided' }}</dd>
                            </div>

                            <div>
                                <dt class="text-sm font-medium text-slate-500 uppercase tracking-wider text-[10px]">Gender</dt>
                                <dd class="mt-1 text-slate-900 font-medium">{{ $profile?->gender?->name ?? 'Not provided' }}</dd>
                            </div>
                        </dl>
                    </section>

                    <section class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm">
                        <h2 class="text-lg font-bold text-slate-900">
                            Credentials Snapshot
                        </h2>

                        <div class="mt-5 grid grid-cols-2 gap-4">
                            <div class="rounded-2xl bg-slate-50 p-4">
                                <div class="text-2xl font-bold text-slate-900">{{ $profile->licenses->count() }}</div>
                                <div class="text-sm text-slate-500">Licenses</div>
                            </div>

                            <div class="rounded-2xl bg-slate-50 p-4">
                                <div class="text-2xl font-bold text-slate-900">{{ $profile->trainings->count() }}</div>
                                <div class="text-sm text-slate-500">Trainings</div>
                            </div>

                            <div class="rounded-2xl bg-slate-50 p-4">
                                <div class="text-2xl font-bold text-slate-900">{{ $profile->skills->count() }}</div>
                                <div class="text-sm text-slate-500">Skills</div>
                            </div>

                            <div class="rounded-2xl bg-slate-50 p-4">
                                <div class="text-2xl font-bold text-slate-900">{{ $profile->clearances->count() }}</div>
                                <div class="text-sm text-slate-500">Clearances</div>
                            </div>
                        </div>
                    </section>

                     @if($profile->hasMedia('resume'))
                        <section class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm">
                            <h2 class="text-lg font-bold text-slate-900 mb-4">Resume / CV</h2>
                            <x-framework.buttons.secondary href="{{ $profile->getFirstMediaUrl('resume') }}" target="_blank" class="w-full justify-center">
                                <x-framework.icon name="document-text" class="h-5 w-5 mr-2" />
                                Download Resume
                            </x-framework.buttons.secondary>
                        </section>
                    @endif

                </aside>

                <main class="space-y-8 lg:col-span-2">

                    <section class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm">
                        <h2 class="text-xl font-bold text-slate-900">
                            Work Experience
                        </h2>

                        <div class="space-y-8 mt-6">
                            @forelse ($profile->workExperiences as $experience)
                                <div class="relative pl-8 before:absolute before:left-0 before:top-2 before:bottom-0 before:w-0.5 before:bg-slate-100 last:before:bottom-2">
                                    <div class="absolute left-[-4px] top-2 h-2.5 w-2.5 rounded-full border-2 border-white bg-blue-600 ring-2 ring-blue-50"></div>

                                    <h3 class="font-bold text-slate-900">
                                        {{ $experience->position ?? $experience->job_title }}
                                    </h3>

                                    <p class="text-sm font-semibold text-blue-600">
                                        {{ $experience->company_name }}
                                    </p>

                                    @if ($experience->hasMedia('attachments'))
                                        <div class="mt-2 flex items-center space-x-2">
                                            <a href="{{ $experience->getFirstMediaUrl('attachments') }}" target="_blank" class="text-xs font-semibold text-blue-600 hover:text-blue-700 flex items-center">
                                                <x-framework.icon name="document-text" class="h-3 w-3 mr-1" />
                                                View Attachment
                                            </a>
                                        </div>
                                    @endif

                                    <p class="mt-1 text-xs text-slate-500">
                                        {{ $experience->started_at?->format('M Y') ?? 'Unknown' }}
                                        —
                                        {{ $experience->is_current ? 'Present' : ($experience->ended_at?->format('M Y') ?? 'Unknown') }}
                                    </p>

                                    @if ($experience->description)
                                        <p class="mt-3 text-sm leading-relaxed text-slate-600">
                                            {{ $experience->description }}
                                        </p>
                                    @endif
                                </div>
                            @empty
                                <p class="text-slate-500 italic">
                                    No work experience added yet.
                                </p>
                            @endforelse
                        </div>
                    </section>

                    <section class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm">
                        <h2 class="text-xl font-bold text-slate-900">
                            Education
                        </h2>

                        <div class="space-y-6 mt-6">
                            @forelse ($profile->educations as $education)
                                <div class="flex items-start space-x-4">
                                    <div class="h-10 w-10 rounded-lg bg-slate-50 flex items-center justify-center text-slate-400">
                                        <x-framework.icon name="academic-cap" class="h-6 w-6" />
                                    </div>
                                    <div>
                                        <h3 class="font-bold text-slate-900">
                                            {{ $education->level }} {{ $education->course_or_strand ? '- ' . $education->course_or_strand : '' }}
                                        </h3>
                                        <p class="text-sm text-slate-700">
                                            {{ $education->school_name }}
                                        </p>
                                        <p class="text-xs text-slate-500">
                                            {{ $education->started_year ?? 'Unknown' }} — {{ $education->ended_year ?? 'Present' }}
                                        </p>

                                        @if ($education->hasMedia('attachments'))
                                            <div class="mt-2 flex items-center space-x-2">
                                                <a href="{{ $education->getFirstMediaUrl('attachments') }}" target="_blank" class="text-xs font-semibold text-blue-600 hover:text-blue-700 flex items-center">
                                                    <x-framework.icon name="document-text" class="h-3 w-3 mr-1" />
                                                    View Attachment
                                                </a>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            @empty
                                <p class="text-slate-500 italic">
                                    No educational background added yet.
                                </p>
                            @endforelse
                        </div>
                    </section>

                    <section class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm">
                        <h2 class="text-xl font-bold text-slate-900">
                            Licenses & Trainings
                        </h2>

                        <div class="mt-6 grid gap-4 sm:grid-cols-2">
                            @foreach ($profile->licenses as $license)
                                <div class="rounded-2xl border border-slate-100 bg-slate-50 p-4">
                                    <div class="flex justify-between items-start">
                                        <div>
                                            <h3 class="font-bold text-slate-900 text-sm">
                                                {{ $license->licenseType?->name ?? 'License' }}
                                            </h3>
                                            <p class="mt-1 text-xs text-slate-500">
                                                License #: {{ $license->license_number }}
                                            </p>
                                            <p class="mt-1 text-xs text-slate-500">
                                                Expires: {{ $license->expires_at?->format('M d, Y') ?? 'N/A' }}
                                            </p>
                                        </div>
                                        @if($license->hasMedia('licenses'))
                                            <a href="{{ $license->getFirstMediaUrl('licenses') }}" target="_blank" class="text-blue-600 hover:text-blue-700" title="View Attachment">
                                                <x-framework.icon name="document-text" class="h-5 w-5" />
                                            </a>
                                        @endif
                                    </div>
                                </div>
                            @endforeach

                            @foreach ($profile->trainings as $training)
                                <div class="rounded-2xl border border-slate-100 bg-slate-50 p-4">
                                    <div class="flex justify-between items-start">
                                        <div>
                                            <h3 class="font-bold text-slate-900 text-sm">
                                                {{ $training->trainingType?->name ?? 'Training' }}
                                            </h3>
                                            <p class="mt-1 text-xs text-slate-500">
                                                {{ $training->provider ?? 'Provider not specified' }}
                                            </p>
                                            @if($training->completion_date)
                                                <p class="mt-1 text-xs text-slate-500">
                                                    Completed: {{ $training->completion_date->format('M Y') }}
                                                </p>
                                            @endif
                                        </div>
                                        @if($training->hasMedia('trainings'))
                                            <a href="{{ $training->getFirstMediaUrl('trainings') }}" target="_blank" class="text-blue-600 hover:text-blue-700" title="View Attachment">
                                                <x-framework.icon name="document-text" class="h-5 w-5" />
                                            </a>
                                        @endif
                                    </div>
                                </div>
                            @endforeach

                            @foreach ($profile->certifications as $certification)
                                <div class="rounded-2xl border border-slate-100 bg-slate-50 p-4">
                                    <div class="flex justify-between items-start">
                                        <div>
                                            <h3 class="font-bold text-slate-900 text-sm">
                                                {{ $certification->name }}
                                            </h3>
                                            <p class="mt-1 text-xs text-slate-500">
                                                {{ $certification->issuing_organization }}
                                            </p>
                                            @if($certification->issued_at)
                                                <p class="mt-1 text-xs text-slate-500">
                                                    Issued: {{ $certification->issued_at->format('M Y') }}
                                                </p>
                                            @endif
                                        </div>
                                        @if($certification->hasMedia('certificates'))
                                            <a href="{{ $certification->getFirstMediaUrl('certificates') }}" target="_blank" class="text-blue-600 hover:text-blue-700" title="View Attachment">
                                                <x-framework.icon name="document-text" class="h-5 w-5" />
                                            </a>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </section>

                    <section class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm">
                        <h2 class="text-xl font-bold text-slate-900">
                            Clearances, Medicals & IDs
                        </h2>

                        <div class="mt-6 grid gap-4 sm:grid-cols-2">
                            @foreach ($profile->clearances as $clearance)
                                <div class="rounded-2xl border border-slate-100 bg-slate-50 p-4">
                                    <div class="flex justify-between items-start">
                                        <div>
                                            <h3 class="font-bold text-slate-900 text-sm">
                                                {{ $clearance->clearanceType?->name ?? 'Clearance' }}
                                            </h3>
                                            <p class="mt-1 text-xs text-slate-500">
                                                {{ $clearance->issuing_office ?? 'Office not specified' }}
                                            </p>
                                            <p class="mt-1 text-xs text-slate-500">
                                                Expires: {{ $clearance->expires_at?->format('M d, Y') ?? 'N/A' }}
                                            </p>
                                        </div>
                                        @if($clearance->hasMedia('clearances'))
                                            <a href="{{ $clearance->getFirstMediaUrl('clearances') }}" target="_blank" class="text-blue-600 hover:text-blue-700" title="View Attachment">
                                                <x-framework.icon name="document-text" class="h-5 w-5" />
                                            </a>
                                        @endif
                                    </div>
                                </div>
                            @endforeach

                            @foreach ($profile->medicals as $medical)
                                <div class="rounded-2xl border border-slate-100 bg-slate-50 p-4">
                                    <div class="flex justify-between items-start">
                                        <div>
                                            <h3 class="font-bold text-slate-900 text-sm">
                                                {{ $medical->certificate_type ?? 'Medical' }}
                                            </h3>
                                            <p class="mt-1 text-xs text-slate-500">
                                                {{ $medical->clinic_or_hospital ?? 'Clinic not specified' }}
                                            </p>
                                            <p class="mt-1 text-xs text-slate-500">
                                                {{ $medical->is_fit_to_work ? 'Fit to Work' : 'Unfit' }}
                                            </p>
                                        </div>
                                        @if($medical->hasMedia('medical'))
                                            <a href="{{ $medical->getFirstMediaUrl('medical') }}" target="_blank" class="text-blue-600 hover:text-blue-700" title="View Attachment">
                                                <x-framework.icon name="document-text" class="h-5 w-5" />
                                            </a>
                                        @endif
                                    </div>
                                </div>
                            @endforeach

                            @foreach ($profile->identifications as $id)
                                <div class="rounded-2xl border border-slate-100 bg-slate-50 p-4">
                                    <div class="flex justify-between items-start">
                                        <div>
                                            <h3 class="font-bold text-slate-900 text-sm">
                                                {{ $id->identificationType?->name ?? $id->id_type ?? 'ID' }}
                                            </h3>
                                            <p class="mt-1 text-xs text-slate-500">
                                                ID #: {{ $id->id_number }}
                                            </p>
                                        </div>
                                        @if($id->hasMedia('identifications'))
                                            <a href="{{ $id->getFirstMediaUrl('identifications') }}" target="_blank" class="text-blue-600 hover:text-blue-700" title="View Attachment">
                                                <x-framework.icon name="document-text" class="h-5 w-5" />
                                            </a>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </section>

                    @if($profile->firearmQualification)
                         <section class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm">
                            <h2 class="text-xl font-bold text-slate-900">
                                Firearm Qualification
                            </h2>

                            <div class="mt-6">
                                <div class="rounded-2xl border border-slate-100 bg-slate-50 p-4">
                                    <div class="flex justify-between items-start">
                                        <div>
                                            <h3 class="font-bold text-slate-900 text-sm">
                                                {{ $profile->firearmQualification->is_firearm_qualified ? 'Qualified' : 'Not Qualified' }}
                                            </h3>
                                            @if($profile->firearmQualification->firearm_type)
                                                <p class="mt-1 text-xs text-slate-500">
                                                    Type: {{ $profile->firearmQualification->firearm_type }}
                                                </p>
                                            @endif
                                            @if($profile->firearmQualification->permit_number)
                                                <p class="mt-1 text-xs text-slate-500">
                                                    Permit #: {{ $profile->firearmQualification->permit_number }}
                                                </p>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </section>
                    @endif

                </main>

            </div>
        </div>
    </section>
@endsection
