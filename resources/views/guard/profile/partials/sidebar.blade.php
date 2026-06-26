@php
    $items = [
        'overview' => 'Overview',
        'personal' => 'Personal Information',
        'education' => 'Educational Background',
        'work' => 'Work Experience',
        'credentials' => 'Credentials',
        'documents' => 'Documents',
        'preferences' => 'Preferences',
    ];
@endphp

<div class="sticky top-28 rounded-3xl border border-slate-200 bg-white p-4 shadow-sm">
    <nav class="space-y-1 text-sm font-medium">
        @foreach ($items as $key => $label)
            <a
                href="{{ route('guard.profile.show', $key) }}"
                class="block rounded-lg px-4 py-2 transition
                    {{ $section === $key
                        ? 'bg-slate-900 text-white'
                        : 'text-slate-700 hover:bg-slate-100' }}"
            >
                {{ $label }}
            </a>
        @endforeach
    </nav>
</div>
