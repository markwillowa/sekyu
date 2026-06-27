<article class="rounded-2xl border border-slate-200 p-5">
    <div class="flex flex-col gap-4 sm:flex-row sm:items-start sm:justify-between">
        <div>
            <h3 class="text-lg font-bold text-slate-900">
                {{ $education->level }}
            </h3>

            <p class="mt-1 font-medium text-slate-700">
                {{ $education->school_name }}
            </p>

            @if ($education->course_or_strand || $education->field_of_study)
                <p class="mt-1 text-sm text-slate-500">
                    {{ $education->course_or_strand }}

                    @if ($education->course_or_strand && $education->field_of_study)
                        —
                    @endif

                    {{ $education->field_of_study }}
                </p>
            @endif

            <p class="mt-2 text-sm text-slate-500">
                {{ $education->started_year ?? 'Unknown' }}
                —
                {{ $education->is_current ? 'Present' : ($education->ended_year ?? 'Unknown') }}
            </p>

            @if ($education->honors_or_awards)
                <p class="mt-3 rounded-lg bg-amber-50 px-3 py-2 text-sm font-medium text-amber-800">
                    {{ $education->honors_or_awards }}
                </p>
            @endif

            @if ($education->description)
                <p class="mt-3 leading-7 text-slate-600">
                    {{ $education->description }}
                </p>
            @endif
        </div>

        <div class="flex gap-2">
            <x-framework.buttons.secondary
                href="#"
                size="sm"
                @click.prevent="$dispatch('open-modal', 'edit-education-{{ $education->id }}')"
            >
                Edit
            </x-framework.buttons.secondary>

            <x-framework.feedback.modal
                name="edit-education-{{ $education->id }}"
                title="Edit Education"
                description="Update your educational background."
            >
                <form action="{{ route('applicant.profile.update-education', $education) }}" method="POST" class="space-y-6">
                    @csrf
                    @method('PATCH')

                    <div class="grid gap-6 sm:grid-cols-2">
                        <div class="sm:col-span-2">
                            <x-framework.forms.input
                                label="School Name"
                                name="school_name"
                                :value="$education->school_name"
                                required
                            />
                        </div>

                        <x-framework.forms.input
                            label="Level (e.g., High School, College)"
                            name="level"
                            :value="$education->level"
                            required
                        />

                        <x-framework.forms.input
                            label="Course / Strand"
                            name="course_or_strand"
                            :value="$education->course_or_strand"
                        />

                        <x-framework.forms.input
                            label="Started Year"
                            name="started_year"
                            type="number"
                            min="1900"
                            max="{{ date('Y') }}"
                            :value="$education->started_year"
                        />

                        <x-framework.forms.input
                            label="Ended Year"
                            name="ended_year"
                            type="number"
                            min="1900"
                            max="{{ date('Y') + 10 }}"
                            :value="$education->ended_year"
                        />

                        <div class="sm:col-span-2">
                            <x-framework.forms.textarea
                                label="Description / Achievements"
                                name="description"
                                rows="3"
                            >{{ $education->description }}</x-framework.forms.textarea>
                        </div>
                    </div>

                    <div class="flex justify-end gap-3 mt-8">
                        <x-framework.buttons.secondary
                            type="button"
                            @click="$dispatch('close-modal', 'edit-education-{{ $education->id }}')"
                        >
                            Cancel
                        </x-framework.buttons.secondary>

                        <x-framework.buttons.primary type="submit">
                            Save Changes
                        </x-framework.buttons.primary>
                    </div>
                </form>
            </x-framework.feedback.modal>

            <form action="{{ route('applicant.profile.delete-education', $education) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this education record?')">
                @csrf
                @method('DELETE')
                <x-framework.buttons.secondary type="submit" size="sm" class="text-red-600 border-red-200 hover:bg-red-50">
                    Delete
                </x-framework.buttons.secondary>
            </form>
        </div>
    </div>
</article>
