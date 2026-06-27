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
                <p class="mt-2 text-sm text-slate-500">
                    Uploaded
                </p>

                <a
                    href="{{ $photo->getUrl() }}"
                    target="_blank"
                    class="mt-4 inline-flex text-sm font-semibold text-amber-600 hover:text-amber-700"
                >
                    View Photo
                </a>
            @else
                <p class="mt-2 text-sm text-slate-500">
                    No profile photo uploaded yet.
                </p>
            @endif
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
        </div>
    </div>
</x-framework.layout.card>
