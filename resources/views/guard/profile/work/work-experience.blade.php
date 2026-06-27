<x-framework.layout.card>
    <div class="flex items-center justify-between border-b border-slate-200 pb-5">
        <div>
            <h2 class="text-xl font-bold text-slate-900">
                Work Experience
            </h2>

            <p class="mt-1 text-sm text-slate-500">
                Your previous security-related employment history.
            </p>
        </div>

        <x-framework.buttons.primary
            href="#"
            size="sm"
            @click.prevent="$dispatch('open-modal', 'add-work-experience')"
        >
            Add Experience
        </x-framework.buttons.primary>
    </div>

    <x-framework.feedback.modal
        name="add-work-experience"
        title="Add Work Experience"
        description="Add your previous security-related employment history."
    >
        <form action="{{ route('applicant.profile.store-work-experience') }}" method="POST" class="space-y-6">
            @csrf

            <div class="grid gap-6 sm:grid-cols-2">
                <div class="sm:col-span-2">
                    <x-framework.forms.input
                        label="Company / Agency Name"
                        name="company_name"
                        required
                    />
                </div>

                <x-framework.forms.input
                    label="Position / Role"
                    name="position"
                    required
                />

                <x-framework.forms.input
                    label="Location"
                    name="location"
                />

                <x-framework.forms.input
                    label="Started Date"
                    name="started_at"
                    type="date"
                    required
                />

                <x-framework.forms.input
                    label="Ended Date"
                    name="ended_at"
                    type="date"
                />

                <div class="sm:col-span-2">
                    <label class="flex items-center">
                        <input type="checkbox" name="is_current" value="1" class="rounded border-slate-300 text-amber-500 shadow-sm focus:border-amber-500 focus:ring-amber-500/20">
                        <span class="ml-2 text-sm text-slate-600">I am currently working here</span>
                    </label>
                </div>

                <div class="sm:col-span-2">
                    <x-framework.forms.textarea
                        label="Responsibilities / Description"
                        name="description"
                        rows="3"
                    ></x-framework.forms.textarea>
                </div>
            </div>

            <div class="flex justify-end gap-3 mt-8">
                <x-framework.buttons.secondary
                    type="button"
                    @click="$dispatch('close-modal', 'add-work-experience')"
                >
                    Cancel
                </x-framework.buttons.secondary>

                <x-framework.buttons.primary type="submit">
                    Add Experience
                </x-framework.buttons.primary>
            </div>
        </form>
    </x-framework.feedback.modal>

    @if ($workExperiences->isEmpty())
        <div class="mt-6 rounded-2xl border border-dashed border-slate-300 bg-slate-50 p-8 text-center">
            <h3 class="text-lg font-bold text-slate-900">
                No work experience added yet
            </h3>

            <p class="mt-2 text-sm text-slate-500">
                Add your previous agencies, posts, roles, and years of experience.
            </p>
        </div>
    @else
        <div class="mt-6 space-y-4">
            @foreach ($workExperiences as $experience)
                <article class="rounded-2xl border border-slate-200 p-5">
                    <div class="flex items-start justify-between gap-4">
                        <div>
                            <h3 class="text-lg font-bold text-slate-900">
                                {{ $experience->position }}
                            </h3>

                            <p class="mt-1 font-medium text-slate-700">
                                {{ $experience->company_name }}
                            </p>

                            <p class="mt-2 text-sm text-slate-500">
                                {{ $experience->started_at?->format('M Y') ?? 'Unknown' }}
                                —
                                {{ $experience->is_current ? 'Present' : ($experience->ended_at?->format('M Y') ?? 'Unknown') }}
                            </p>

                            @if ($experience->location)
                                <p class="mt-1 text-sm text-slate-500">
                                    {{ $experience->location }}
                                </p>
                            @endif

                            @if ($experience->description)
                                <p class="mt-3 leading-7 text-slate-600">
                                    {{ $experience->description }}
                                </p>
                            @endif
                        </div>

                        <div class="flex gap-2">
                            <x-framework.buttons.secondary
                                href="#"
                                size="sm"
                                @click.prevent="$dispatch('open-modal', 'edit-work-experience-{{ $experience->id }}')"
                            >
                                Edit
                            </x-framework.buttons.secondary>

                            <x-framework.feedback.modal
                                name="edit-work-experience-{{ $experience->id }}"
                                title="Edit Work Experience"
                                description="Update your employment history."
                            >
                                <form action="{{ route('applicant.profile.update-work-experience', $experience) }}" method="POST" class="space-y-6">
                                    @csrf
                                    @method('PATCH')

                                    <div class="grid gap-6 sm:grid-cols-2">
                                        <div class="sm:col-span-2">
                                            <x-framework.forms.input
                                                label="Company / Agency Name"
                                                name="company_name"
                                                :value="$experience->company_name"
                                                required
                                            />
                                        </div>

                                        <x-framework.forms.input
                                            label="Position / Role"
                                            name="position"
                                            :value="$experience->position"
                                            required
                                        />

                                        <x-framework.forms.input
                                            label="Location"
                                            name="location"
                                            :value="$experience->location"
                                        />

                                        <x-framework.forms.input
                                            label="Started Date"
                                            name="started_at"
                                            type="date"
                                            :value="$experience->started_at?->format('Y-m-d')"
                                            required
                                        />

                                        <x-framework.forms.input
                                            label="Ended Date"
                                            name="ended_at"
                                            type="date"
                                            :value="$experience->ended_at?->format('Y-m-d')"
                                        />

                                        <div class="sm:col-span-2">
                                            <label class="flex items-center">
                                                <input type="checkbox" name="is_current" value="1" {{ $experience->is_current ? 'checked' : '' }} class="rounded border-slate-300 text-amber-500 shadow-sm focus:border-amber-500 focus:ring-amber-500/20">
                                                <span class="ml-2 text-sm text-slate-600">I am currently working here</span>
                                            </label>
                                        </div>

                                        <div class="sm:col-span-2">
                                            <x-framework.forms.textarea
                                                label="Responsibilities / Description"
                                                name="description"
                                                rows="3"
                                            >{{ $experience->description }}</x-framework.forms.textarea>
                                        </div>
                                    </div>

                                    <div class="flex justify-end gap-3 mt-8">
                                        <x-framework.buttons.secondary
                                            type="button"
                                            @click="$dispatch('close-modal', 'edit-work-experience-{{ $experience->id }}')"
                                        >
                                            Cancel
                                        </x-framework.buttons.secondary>

                                        <x-framework.buttons.primary type="submit">
                                            Save Changes
                                        </x-framework.buttons.primary>
                                    </div>
                                </form>
                            </x-framework.feedback.modal>

                            <form action="{{ route('applicant.profile.delete-work-experience', $experience) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this work experience?')">
                                @csrf
                                @method('DELETE')
                                <x-framework.buttons.secondary type="submit" size="sm" class="text-red-600 border-red-200 hover:bg-red-50">
                                    Delete
                                </x-framework.buttons.secondary>
                            </form>
                        </div>
                    </div>
                </article>
            @endforeach
        </div>
    @endif
</x-framework.layout.card>
