<x-framework.layout.card>
    <div class="flex items-center justify-between border-b border-slate-200 pb-5">
        <div>
            <h2 class="text-xl font-bold text-slate-900">
                Skills
            </h2>

            <p class="mt-1 text-sm text-slate-500">
                Security skills that strengthen your profile.
            </p>
        </div>

        <x-framework.buttons.primary type="button" size="sm" @click.prevent="$dispatch('open-modal', 'add-skill')">
            Add Skill
        </x-framework.buttons.primary>
    </div>

    @if ($skills->isEmpty())
        <div class="mt-6 rounded-2xl border border-dashed border-slate-300 bg-slate-50 p-8 text-center">
            <h3 class="text-lg font-bold text-slate-900">
                No skills added yet
            </h3>

            <p class="mt-2 text-sm text-slate-500">
                Add skills such as CCTV monitoring, access control, patrol operations, or emergency response.
            </p>

            <div class="mt-5">
                <x-framework.buttons.primary type="button" size="sm" @click.prevent="$dispatch('open-modal', 'add-skill')">
                    Add Skill
                </x-framework.buttons.primary>
            </div>
        </div>
    @else
        <div class="mt-6 flex flex-wrap gap-2">
            @foreach ($skills as $skill)
                <div class="group relative">
                    <x-framework.feedback.badge color="slate" class="pr-8">
                        {{ $skill->skill?->name ?? 'Skill' }} ({{ $skill->level?->name ?? 'Level' }})
                    </x-framework.feedback.badge>

                    <div class="absolute right-1 top-1/2 -translate-y-1/2 flex items-center gap-1 opacity-0 group-hover:opacity-100 transition-opacity">
                        <button type="button" @click.prevent="$dispatch('open-modal', 'edit-skill-{{ $skill->id }}')" class="text-slate-400 hover:text-blue-600">
                            <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0 1 15.75 21H5.25A2.25 2.25 0 0 1 3 18.75V8.25A2.25 2.25 0 0 1 5.25 6H10" />
                            </svg>
                        </button>
                    </div>
                </div>

                <!-- Edit Skill Modal -->
                <x-framework.feedback.modal name="edit-skill-{{ $skill->id }}" title="Edit Skill">
                    <form action="{{ route('applicant.profile.update-skill', $skill) }}" method="POST" class="space-y-4">
                        @csrf
                        @method('PATCH')

                        <x-framework.forms.select
                            label="Skill"
                            name="master_skill_id"
                            required
                        >
                            <option value="">Select a skill</option>
                            @foreach($allSkills as $s)
                                <option value="{{ $s->id }}" @selected($skill->master_skill_id == $s->id)>{{ $s->name }}</option>
                            @endforeach
                        </x-framework.forms.select>

                        <x-framework.forms.select
                            label="Proficiency Level"
                            name="master_skill_level_id"
                            required
                        >
                            <option value="">Select level</option>
                            @foreach($skillLevels as $level)
                                <option value="{{ $level->id }}" @selected($skill->master_skill_level_id == $level->id)>{{ $level->name }}</option>
                            @endforeach
                        </x-framework.forms.select>

                        <x-framework.forms.input
                            label="Years of Experience"
                            name="years_of_experience"
                            type="number"
                            min="0"
                            value="{{ $skill->years_of_experience }}"
                        />

                        <div class="flex items-center justify-between pt-4">
                            <button
                                type="submit"
                                form="delete-skill-{{ $skill->id }}"
                                class="text-sm font-bold text-red-600 hover:text-red-700"
                                onclick="return confirm('Are you sure you want to remove this skill?')"
                            >
                                Remove Skill
                            </button>

                            <div class="flex items-center gap-3">
                                <x-framework.buttons.secondary type="button" @click="$dispatch('close-modal', 'edit-skill-{{ $skill->id }}')">
                                    Cancel
                                </x-framework.buttons.secondary>
                                <x-framework.buttons.primary type="submit">
                                    Update Skill
                                </x-framework.buttons.primary>
                            </div>
                        </div>
                    </form>

                    <form id="delete-skill-{{ $skill->id }}" action="{{ route('applicant.profile.delete-skill', $skill) }}" method="POST" class="hidden">
                        @csrf
                        @method('DELETE')
                    </form>
                </x-framework.feedback.modal>
            @endforeach
        </div>
    @endif

    <!-- Add Skill Modal -->
    <x-framework.feedback.modal name="add-skill" title="Add Skill">
        <form action="{{ route('applicant.profile.store-skill') }}" method="POST" class="space-y-4">
            @csrf

            <x-framework.forms.select
                label="Skill"
                name="master_skill_id"
                required
            >
                <option value="">Select a skill</option>
                @foreach($allSkills as $skill)
                    <option value="{{ $skill->id }}">{{ $skill->name }}</option>
                @endforeach
            </x-framework.forms.select>

            <x-framework.forms.select
                label="Proficiency Level"
                name="master_skill_level_id"
                required
            >
                <option value="">Select level</option>
                @foreach($skillLevels as $level)
                    <option value="{{ $level->id }}">{{ $level->name }}</option>
                @endforeach
            </x-framework.forms.select>

            <x-framework.forms.input
                label="Years of Experience"
                name="years_of_experience"
                type="number"
                min="0"
                value="0"
            />

            <div class="flex justify-end gap-3 pt-4">
                <x-framework.buttons.secondary type="button" @click="$dispatch('close-modal', 'add-skill')">
                    Cancel
                </x-framework.buttons.secondary>
                <x-framework.buttons.primary type="submit">
                    Add Skill
                </x-framework.buttons.primary>
            </div>
        </form>
    </x-framework.feedback.modal>
</x-framework.layout.card>
