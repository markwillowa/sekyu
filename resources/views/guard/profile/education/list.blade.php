<x-framework.layout.card>
    <div class="flex items-center justify-between border-b border-slate-200 pb-5">
        <div>
            <h2 class="text-xl font-bold text-slate-900">
                Education Records
            </h2>

            <p class="mt-1 text-sm text-slate-500">
                Your schools, courses, years attended, and academic achievements.
            </p>
        </div>

        <x-framework.buttons.primary
            href="#"
            size="sm"
            @click.prevent="$dispatch('open-modal', 'add-education')"
        >
            Add Education
        </x-framework.buttons.primary>
    </div>

    <x-framework.feedback.modal
        name="add-education"
        title="Add Education"
        description="Add your educational background."
    >
        <form action="{{ route('applicant.profile.store-education') }}" method="POST" class="space-y-6">
            @csrf

            <div class="grid gap-6 sm:grid-cols-2">
                <div class="sm:col-span-2">
                    <x-framework.forms.input
                        label="School Name"
                        name="school_name"
                        required
                    />
                </div>

                <x-framework.forms.input
                    label="Level (e.g., High School, College)"
                    name="level"
                    required
                />

                <x-framework.forms.input
                    label="Course / Strand"
                    name="course_or_strand"
                />

                <x-framework.forms.input
                    label="Started Year"
                    name="started_year"
                    type="number"
                    min="1900"
                    max="{{ date('Y') }}"
                />

                <x-framework.forms.input
                    label="Ended Year"
                    name="ended_year"
                    type="number"
                    min="1900"
                    max="{{ date('Y') + 10 }}"
                />

                <div class="sm:col-span-2">
                    <x-framework.forms.textarea
                        label="Description / Achievements"
                        name="description"
                        rows="3"
                    ></x-framework.forms.textarea>
                </div>
            </div>

            <div class="flex justify-end gap-3 mt-8">
                <x-framework.buttons.secondary
                    type="button"
                    @click="$dispatch('close-modal', 'add-education')"
                >
                    Cancel
                </x-framework.buttons.secondary>

                <x-framework.buttons.primary type="submit">
                    Add Education
                </x-framework.buttons.primary>
            </div>
        </form>
    </x-framework.feedback.modal>

    @if ($educations->isEmpty())
        @include('guard.profile.education.empty')
    @else
        <div class="mt-6 space-y-4">
            @foreach ($educations->sortByDesc('started_year') as $education)
                @include('guard.profile.education.card', [
                    'education' => $education,
                ])
            @endforeach
        </div>
    @endif
</x-framework.layout.card>
