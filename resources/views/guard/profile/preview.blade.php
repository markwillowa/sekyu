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

                <div class="h-40 bg-slate-900 flex items-center justify-end px-8">
                     <x-framework.feedback.badge color="amber" class="text-lg py-2 px-4">
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
                            Contact Information
                        </h2>

                        <dl class="mt-5 space-y-4">
                            <div>
                                <dt class="text-sm font-medium text-slate-500 uppercase tracking-wider text-[10px]">Email</dt>
                                <dd class="mt-1 text-slate-900 font-medium">{{ $user->email }}</dd>
                            </div>

                            <div>
                                <dt class="text-sm font-medium text-slate-500 uppercase tracking-wider text-[10px]">Mobile</dt>
                                <dd class="mt-1 text-slate-900 font-medium">{{ $contactDetails?->mobile_number ?? 'Not provided' }}</dd>
                            </div>

                            <div>
                                <dt class="text-sm font-medium text-slate-500 uppercase tracking-wider text-[10px]">Nationality</dt>
                                <dd class="mt-1 text-slate-900 font-medium">{{ $profile?->nationality ?? 'Not provided' }}</dd>
                            </div>

                            <div>
                                <dt class="text-sm font-medium text-slate-500 uppercase tracking-wider text-[10px]">Gender</dt>
                                <dd class="mt-1 text-slate-900 font-medium">{{ $profile?->gender?->name ?? 'Not provided' }}</dd>
                            </div>

                            <div>
                                <dt class="text-sm font-medium text-slate-500 uppercase tracking-wider text-[10px]">Civil Status</dt>
                                <dd class="mt-1 text-slate-900 font-medium">{{ $profile?->civilStatus?->name ?? 'Not provided' }}</dd>
                            </div>

                            <div>
                                <dt class="text-sm font-medium text-slate-500 uppercase tracking-wider text-[10px]">Birth Date</dt>
                                <dd class="mt-1 text-slate-900 font-medium">
                                    {{ $profile->birth_date?->format('M d, Y') ?? 'Not provided' }}
                                    @if($profile->birth_date)
                                        <span class="text-slate-500 text-xs ml-1">({{ $profile->birth_date->age }} years old)</span>
                                    @endif
                                </dd>
                            </div>
                        </dl>
                    </section>

                    <section class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm">
                        <h2 class="text-lg font-bold text-slate-900">
                            Physical Details
                        </h2>

                        <dl class="mt-5 space-y-4">
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <dt class="text-sm font-medium text-slate-500 uppercase tracking-wider text-[10px]">Height</dt>
                                    <dd class="mt-1 text-slate-900 font-medium">{{ $physicalDetail?->height_cm ? $physicalDetail->height_cm . ' cm' : 'N/A' }}</dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-slate-500 uppercase tracking-wider text-[10px]">Weight</dt>
                                    <dd class="mt-1 text-slate-900 font-medium">{{ $physicalDetail?->weight_kg ? $physicalDetail->weight_kg . ' kg' : 'N/A' }}</dd>
                                </div>
                            </div>

                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <dt class="text-sm font-medium text-slate-500 uppercase tracking-wider text-[10px]">Body Type</dt>
                                    <dd class="mt-1 text-slate-900 font-medium">{{ $physicalDetail?->body_type ?? 'N/A' }}</dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-slate-500 uppercase tracking-wider text-[10px]">Blood Type</dt>
                                    <dd class="mt-1 text-slate-900 font-medium">{{ $physicalDetail?->blood_type ?? 'N/A' }}</dd>
                                </div>
                            </div>
                        </dl>
                    </section>

                    <section class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm">
                        <h2 class="text-lg font-bold text-slate-900">
                            Languages
                        </h2>

                        <div class="mt-5 space-y-3">
                            @forelse($languages as $language)
                                <div class="flex justify-between items-center">
                                    <span class="text-slate-900 font-medium">{{ $language->language?->name }}</span>
                                    <span class="text-xs font-bold text-amber-600 bg-amber-50 px-2 py-0.5 rounded-full">{{ $language->proficiency?->name }}</span>
                                </div>
                            @empty
                                <p class="text-slate-500 text-sm italic">No languages added.</p>
                            @endforelse
                        </div>
                    </section>

                    <section class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm">
                        <h2 class="text-lg font-bold text-slate-900">
                            Emergency Contact
                        </h2>

                        <div class="mt-5 space-y-4">
                            @forelse($emergencyContacts as $contact)
                                <div @class(['p-3 rounded-2xl border', 'bg-amber-50/50 border-amber-100' => $contact->is_primary, 'bg-slate-50 border-slate-100' => !$contact->is_primary])>
                                    <div class="flex justify-between items-start mb-2">
                                        <span class="text-slate-900 font-bold text-sm">{{ $contact->name }}</span>
                                        @if($contact->is_primary)
                                            <span class="text-[8px] font-bold text-amber-600 uppercase tracking-tighter bg-amber-100 px-1.5 py-0.5 rounded">Primary</span>
                                        @endif
                                    </div>
                                    <p class="text-[10px] text-slate-500 font-medium uppercase">{{ $contact->relationshipType?->name ?? 'Relationship' }}</p>
                                    <p class="text-xs text-slate-700 mt-1 font-medium">{{ $contact->mobile_number }}</p>
                                    @if($contact->email)
                                        <p class="text-xs text-slate-700 font-medium">{{ $contact->email }}</p>
                                    @endif
                                </div>
                            @empty
                                <p class="text-slate-500 text-sm italic">No emergency contacts.</p>
                            @endforelse
                        </div>
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
                            Employment Preferences
                        </h2>

                        <div class="mt-6">
                            @if($employmentPreference)
                                <dl class="grid grid-cols-1 sm:grid-cols-2 gap-x-6 gap-y-4">
                                    <div>
                                        <dt class="text-sm font-medium text-slate-500 uppercase tracking-wider text-[10px]">Preferred Role</dt>
                                        <dd class="mt-1 text-slate-900 font-medium">{{ $employmentPreference->preferred_job_title ?? 'Any' }}</dd>
                                    </div>
                                    <div>
                                        <dt class="text-sm font-medium text-slate-500 uppercase tracking-wider text-[10px]">Preferred Location</dt>
                                        <dd class="mt-1 text-slate-900 font-medium">{{ $employmentPreference->preferred_location ?? 'Any' }}</dd>
                                    </div>
                                    <div>
                                        <dt class="text-sm font-medium text-slate-500 uppercase tracking-wider text-[10px]">Expected Salary</dt>
                                        <dd class="mt-1 text-slate-900 font-medium">
                                            @if($employmentPreference->expected_salary_min && $employmentPreference->expected_salary_max)
                                                ₱{{ number_format($employmentPreference->expected_salary_min, 2) }} - ₱{{ number_format($employmentPreference->expected_salary_max, 2) }}
                                            @elseif($employmentPreference->expected_salary_min)
                                                From ₱{{ number_format($employmentPreference->expected_salary_min, 2) }}
                                            @else
                                                Not specified
                                            @endif
                                        </dd>
                                    </div>
                                    <div>
                                        <dt class="text-sm font-medium text-slate-500 uppercase tracking-wider text-[10px]">Relocation</dt>
                                        <dd class="mt-1 text-slate-900 font-medium">{{ $employmentPreference->willing_to_relocate ? 'Willing to relocate' : 'Not willing' }}</dd>
                                    </div>
                                    <div>
                                        <dt class="text-sm font-medium text-slate-500 uppercase tracking-wider text-[10px]">Employment Type</dt>
                                        <dd class="mt-1 text-slate-900 font-medium">{{ $employmentPreference->employmentType?->name ?? 'Any' }}</dd>
                                    </div>
                                    <div>
                                        <dt class="text-sm font-medium text-slate-500 uppercase tracking-wider text-[10px]">Shift Preference</dt>
                                        <dd class="mt-1 text-slate-900 font-medium">{{ $employmentPreference->shiftType?->name ?? 'Any' }}</dd>
                                    </div>
                                </dl>
                            @else
                                <p class="text-slate-500 italic">No employment preferences specified.</p>
                            @endif
                        </div>
                    </section>

                    <section class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm">
                        <h2 class="text-xl font-bold text-slate-900">
                            Availability
                        </h2>

                        <div class="mt-6">
                            @if($availability)
                                <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
                                    <div @class(['p-3 rounded-2xl border text-center', 'bg-green-50 border-green-100 text-green-700' => $availability->available_for_day_shift, 'bg-slate-50 border-slate-100 text-slate-400' => !$availability->available_for_day_shift])>
                                        <x-framework.icon name="sun" class="h-5 w-5 mx-auto mb-1" />
                                        <span class="text-[10px] font-bold uppercase">Day Shift</span>
                                    </div>
                                    <div @class(['p-3 rounded-2xl border text-center', 'bg-green-50 border-green-100 text-green-700' => $availability->available_for_night_shift, 'bg-slate-50 border-slate-100 text-slate-400' => !$availability->available_for_night_shift])>
                                        <x-framework.icon name="moon" class="h-5 w-5 mx-auto mb-1" />
                                        <span class="text-[10px] font-bold uppercase">Night Shift</span>
                                    </div>
                                    <div @class(['p-3 rounded-2xl border text-center', 'bg-green-50 border-green-100 text-green-700' => $availability->available_for_roving, 'bg-slate-50 border-slate-100 text-slate-400' => !$availability->available_for_roving])>
                                        <x-framework.icon name="truck" class="h-5 w-5 mx-auto mb-1" />
                                        <span class="text-[10px] font-bold uppercase">Roving</span>
                                    </div>
                                    <div @class(['p-3 rounded-2xl border text-center', 'bg-green-50 border-green-100 text-green-700' => $availability->available_for_reliever, 'bg-slate-50 border-slate-100 text-slate-400' => !$availability->available_for_reliever])>
                                        <x-framework.icon name="user-group" class="h-5 w-5 mx-auto mb-1" />
                                        <span class="text-[10px] font-bold uppercase">Reliever</span>
                                    </div>
                                </div>
                                <div class="mt-4 grid grid-cols-1 sm:grid-cols-2 gap-4">
                                     <div class="flex items-center space-x-2 text-sm">
                                        <span @class(['h-2 w-2 rounded-full', $availability->willing_to_work_overtime ? 'bg-green-500' : 'bg-slate-300'])></span>
                                        <span class="text-slate-700 font-medium">Willing to work overtime</span>
                                    </div>
                                    <div class="flex items-center space-x-2 text-sm">
                                        <span @class(['h-2 w-2 rounded-full', $availability->willing_to_work_holidays ? 'bg-green-500' : 'bg-slate-300'])></span>
                                        <span class="text-slate-700 font-medium">Willing to work holidays</span>
                                    </div>
                                    <div class="flex items-center space-x-2 text-sm">
                                        <span @class(['h-2 w-2 rounded-full', $availability->willing_to_relocate ? 'bg-green-500' : 'bg-slate-300'])></span>
                                        <span class="text-slate-700 font-medium">Willing to relocate</span>
                                    </div>
                                    @if($availability->available_from)
                                        <div class="flex items-center space-x-2 text-sm">
                                            <x-framework.icon name="calendar" class="h-4 w-4 text-slate-400" />
                                            <span class="text-slate-700 font-medium">Available from: {{ $availability->available_from->format('M d, Y') }}</span>
                                        </div>
                                    @endif
                                </div>
                                @if($availability->notes)
                                    <div class="mt-4 p-4 bg-slate-50 rounded-2xl border border-slate-100">
                                        <p class="text-xs text-slate-500 uppercase font-bold mb-1">Additional Notes</p>
                                        <p class="text-sm text-slate-700">{{ $availability->notes }}</p>
                                    </div>
                                @endif
                            @else
                                <p class="text-slate-500 italic">No availability information provided.</p>
                            @endif
                        </div>
                    </section>

                    <section class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm">
                        <h2 class="text-xl font-bold text-slate-900">
                            Professional References
                        </h2>

                        <div class="mt-6 grid gap-6 sm:grid-cols-2">
                            @forelse ($references as $reference)
                                <div class="rounded-2xl border border-slate-100 bg-slate-50 p-4">
                                    <h3 class="font-bold text-slate-900">{{ $reference->name }}</h3>
                                    <p class="text-xs text-amber-600 font-bold uppercase mt-0.5">{{ $reference->relationship }}</p>

                                    <div class="mt-3 space-y-1">
                                        <p class="text-sm text-slate-700">
                                            <span class="font-bold">{{ $reference->position }}</span>
                                            @if($reference->company)
                                                at <span class="font-bold">{{ $reference->company }}</span>
                                            @endif
                                        </p>
                                        <p class="text-xs text-slate-500 flex items-center">
                                            <x-framework.icon name="phone" class="h-3 w-3 mr-1" />
                                            {{ $reference->mobile_number }}
                                        </p>
                                        @if($reference->email)
                                            <p class="text-xs text-slate-500 flex items-center">
                                                <x-framework.icon name="envelope" class="h-3 w-3 mr-1" />
                                                {{ $reference->email }}
                                            </p>
                                        @endif
                                    </div>

                                    @if($reference->may_contact)
                                        <div class="mt-3 inline-flex items-center px-2 py-0.5 rounded text-[10px] font-bold bg-green-100 text-green-700 uppercase">
                                            May Contact
                                        </div>
                                    @endif
                                </div>
                            @empty
                                <p class="text-slate-500 italic sm:col-span-2">No professional references provided.</p>
                            @endforelse
                        </div>
                    </section>

                    <section class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm">
                        <h2 class="text-xl font-bold text-slate-900">
                            Work Experience
                        </h2>

                        <div class="space-y-8 mt-6">
                            @forelse ($workExperiences as $experience)
                                <div class="relative pl-8 before:absolute before:left-0 before:top-2 before:bottom-0 before:w-0.5 before:bg-slate-100 last:before:bottom-2">
                                    <div class="absolute left-[-4px] top-2 h-2.5 w-2.5 rounded-full border-2 border-white bg-amber-600 ring-2 ring-amber-50"></div>

                                    <h3 class="font-bold text-slate-900">
                                        {{ $experience->position ?? $experience->job_title }}
                                    </h3>

                                    <p class="text-sm font-semibold text-amber-600">
                                        {{ $experience->company_name }}
                                    </p>

                                    @if ($experience->hasMedia('attachments'))
                                        <div class="mt-2 flex items-center space-x-2">
                                            <a href="{{ $experience->getFirstMediaUrl('attachments') }}" target="_blank" class="text-xs font-semibold text-amber-600 hover:text-amber-700 flex items-center">
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
                            @forelse ($educations as $education)
                                <div class="flex items-start space-x-4">
                                    <div class="flex-shrink-0 w-12 h-12 rounded-2xl bg-slate-50 flex items-center justify-center">
                                        <x-framework.icon name="academic-cap" class="h-6 w-6 text-slate-400" />
                                    </div>
                                    <div class="flex-1">
                                        <h3 class="font-bold text-slate-900">
                                            {{ $education->level }}
                                        </h3>
                                        <p class="text-sm font-semibold text-amber-600">
                                            {{ $education->school_name }}
                                        </p>
                                        <p class="mt-1 text-xs text-slate-500">
                                            {{ $education->started_year ?? 'Unknown' }}
                                            —
                                            {{ $education->is_current ? 'Present' : ($education->ended_year ?? 'Unknown') }}
                                        </p>
                                        @if($education->degree)
                                            <p class="mt-2 text-sm text-slate-600">{{ $education->degree }}</p>
                                        @endif
                                    </div>
                                </div>
                            @empty
                                <p class="text-slate-500 italic">No educational background added yet.</p>
                            @endforelse
                        </div>
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

                            @if($firearmQualification)
                                <div class="pt-4">
                                    <h3 class="text-sm font-bold text-slate-500 uppercase tracking-wider">Firearm Qualification</h3>
                                    <div class="mt-3">
                                        <div class="rounded-2xl border border-slate-200 p-4">
                                            <div class="flex justify-between items-start">
                                                <div>
                                                    <h4 class="font-bold text-slate-900">{{ $firearmQualification->firearm_type }}</h4>
                                                    <p class="text-sm text-slate-500">Make/Model: {{ $firearmQualification->make_model }}</p>
                                                    <p class="text-sm text-slate-500">Serial No: {{ $firearmQualification->serial_number }}</p>
                                                </div>
                                                <div class="text-right">
                                                    <span class="inline-flex items-center rounded-md bg-blue-50 px-2 py-1 text-xs font-medium text-blue-700 ring-1 ring-inset ring-blue-600/20">
                                                        Score: {{ $firearmQualification->qualification_score }}%
                                                    </span>
                                                    <p class="mt-1 text-[10px] text-slate-400">Qualified: {{ $firearmQualification->qualified_at?->format('M d, Y') }}</p>
                                                </div>
                                            </div>
                                        </div>
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
