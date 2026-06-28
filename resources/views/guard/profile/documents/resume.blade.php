@php
    $resume = $profile?->getFirstMedia('resume');
    $photo = $profile?->getFirstMedia('profile-photo');
@endphp

<x-framework.layout.card>
    <div class="border-b border-slate-200 pb-5">
        <h2 class="text-xl font-bold text-slate-900">
            Uploaded Documents
        </h2>

        <p class="mt-1 text-sm text-slate-500">
            Profile photo, resume, and other uploaded files.
        </p>
    </div>

    <div class="mt-6 grid gap-4 sm:grid-cols-2">
        <div class="rounded-2xl border border-slate-200 p-5">
            <h3 class="font-bold text-slate-900">
                Profile Photo
            </h3>

            @if ($photo)
                <div class="mt-4">
                    @php
                        $isPhotoPdf = $photo->mime_type === 'application/pdf';
                    @endphp

                    @if($isPhotoPdf)
                        <div class="flex h-32 w-32 flex-col items-center justify-center rounded-lg border border-slate-200 bg-slate-50">
                            <svg class="h-12 w-12 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                            </svg>
                            <span class="mt-2 text-xs font-medium text-slate-500">PDF Document</span>
                        </div>
                    @else
                        <img src="{{ $photo->getUrl() }}" alt="Profile Photo" class="h-32 w-32 rounded-lg object-cover border border-slate-200">
                    @endif
                </div>
                <p class="mt-2 text-sm text-slate-500">
                    Uploaded
                </p>

                <a
                    href="{{ $photo->getUrl() }}"
                    target="_blank"
                    class="mt-4 inline-flex text-sm font-semibold text-amber-600 hover:text-amber-700"
                >
                    View Document
                </a>
            @else
                <p class="mt-2 text-sm text-slate-500">
                    No profile photo uploaded yet.
                </p>
            @endif

            <form action="{{ route('applicant.profile.update-documents') }}" method="POST" enctype="multipart/form-data" class="mt-4 pt-4 border-t border-slate-100">
                @csrf
                <x-framework.forms.file name="profile_photo" label="Update Photo (Image or PDF)" accept="image/*,.pdf" />
                <x-framework.buttons.primary type="submit" class="mt-3 w-full justify-center text-sm py-2">
                    Upload Photo
                </x-framework.buttons.primary>
            </form>
        </div>

        <div class="rounded-2xl border border-slate-200 p-5">
            <h3 class="font-bold text-slate-900">
                Resume
            </h3>

            @if ($resume)
                <p class="mt-2 text-sm text-slate-500">
                    Uploaded
                </p>

                <a
                    href="{{ $resume->getUrl() }}"
                    target="_blank"
                    class="mt-4 inline-flex text-sm font-semibold text-amber-600 hover:text-amber-700"
                >
                    View Resume
                </a>
            @else
                <p class="mt-2 text-sm text-slate-500">
                    No resume uploaded yet.
                </p>
            @endif

            <form action="{{ route('applicant.profile.update-documents') }}" method="POST" enctype="multipart/form-data" class="mt-4 pt-4 border-t border-slate-100">
                @csrf
                <x-framework.forms.file name="resume" label="Update Resume" accept=".pdf,.doc,.docx" />
                <x-framework.buttons.primary type="submit" class="mt-3 w-full justify-center text-sm py-2">
                    Upload Resume
                </x-framework.buttons.primary>
            </form>
        </div>
    </div>
</x-framework.layout.card>
