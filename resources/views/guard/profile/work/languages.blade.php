<x-framework.layout.card>
    <div class="flex items-center justify-between border-b border-slate-200 pb-5">
        <div>
            <h2 class="text-xl font-bold text-slate-900">
                Languages
            </h2>

            <p class="mt-1 text-sm text-slate-500">
                Languages and proficiency levels you can use on duty.
            </p>
        </div>

        <x-framework.buttons.primary type="button" size="sm" @click.prevent="$dispatch('open-modal', 'add-language')">
            Add Language
        </x-framework.buttons.primary>
    </div>

    @if ($languages->isEmpty())
        <div class="mt-6 rounded-2xl border border-dashed border-slate-300 bg-slate-50 p-8 text-center">
            <h3 class="text-lg font-bold text-slate-900">
                No languages added yet
            </h3>

            <div class="mt-5">
                <x-framework.buttons.primary type="button" size="sm" @click.prevent="$dispatch('open-modal', 'add-language')">
                    Add Language
                </x-framework.buttons.primary>
            </div>
        </div>
    @else
        <div class="mt-6 grid gap-4 sm:grid-cols-2">
            @foreach ($languages as $language)
                <div class="group relative rounded-2xl border border-slate-200 p-5 pr-12">
                    <h3 class="font-bold text-slate-900">
                        {{ $language->language?->name ?? 'Language' }}
                    </h3>

                    <p class="mt-1 text-sm text-slate-500">
                        {{ $language->proficiency?->name ?? 'Proficiency not provided' }}
                    </p>

                    <div class="absolute right-4 top-5 flex items-center gap-2 opacity-0 group-hover:opacity-100 transition-opacity">
                        <button type="button" @click.prevent="$dispatch('open-modal', 'edit-language-{{ $language->id }}')" class="text-slate-400 hover:text-blue-600">
                            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0 1 15.75 21H5.25A2.25 2.25 0 0 1 3 18.75V8.25A2.25 2.25 0 0 1 5.25 6H10" />
                            </svg>
                        </button>
                    </div>

                    <!-- Edit Language Modal -->
                    <x-framework.feedback.modal name="edit-language-{{ $language->id }}" title="Edit Language">
                        <form action="{{ route('applicant.profile.update-language', $language) }}" method="POST" class="space-y-4">
                            @csrf
                            @method('PATCH')

                            <x-framework.forms.select
                                label="Language"
                                name="master_language_id"
                                required
                            >
                                <option value="">Select a language</option>
                                @foreach($allLanguages as $lang)
                                    <option value="{{ $lang->id }}" @selected($language->master_language_id == $lang->id)>{{ $lang->name }}</option>
                                @endforeach
                            </x-framework.forms.select>

                            <x-framework.forms.select
                                label="Proficiency Level"
                                name="master_language_proficiency_id"
                                required
                            >
                                <option value="">Select level</option>
                                @foreach($proficiencies as $proficiency)
                                    <option value="{{ $proficiency->id }}" @selected($language->master_language_proficiency_id == $proficiency->id)>{{ $proficiency->name }}</option>
                                @endforeach
                            </x-framework.forms.select>

                            <div class="flex items-center justify-between pt-4">
                                <button
                                    type="submit"
                                    form="delete-language-{{ $language->id }}"
                                    class="text-sm font-bold text-red-600 hover:text-red-700"
                                    onclick="return confirm('Are you sure you want to remove this language?')"
                                >
                                    Remove Language
                                </button>

                                <div class="flex items-center gap-3">
                                    <x-framework.buttons.secondary type="button" @click="$dispatch('close-modal', 'edit-language-{{ $language->id }}')">
                                        Cancel
                                    </x-framework.buttons.secondary>
                                    <x-framework.buttons.primary type="submit">
                                        Update Language
                                    </x-framework.buttons.primary>
                                </div>
                            </div>
                        </form>

                        <form id="delete-language-{{ $language->id }}" action="{{ route('applicant.profile.delete-language', $language) }}" method="POST" class="hidden">
                            @csrf
                            @method('DELETE')
                        </form>
                    </x-framework.feedback.modal>
                </div>
            @endforeach
        </div>
    @endif

    <!-- Add Language Modal -->
    <x-framework.feedback.modal name="add-language" title="Add Language">
        <form action="{{ route('applicant.profile.store-language') }}" method="POST" class="space-y-4">
            @csrf

            <x-framework.forms.select
                label="Language"
                name="master_language_id"
                required
            >
                <option value="">Select a language</option>
                @foreach($allLanguages as $language)
                    <option value="{{ $language->id }}">{{ $language->name }}</option>
                @endforeach
            </x-framework.forms.select>

            <x-framework.forms.select
                label="Proficiency Level"
                name="master_language_proficiency_id"
                required
            >
                <option value="">Select level</option>
                @foreach($proficiencies as $proficiency)
                    <option value="{{ $proficiency->id }}">{{ $proficiency->name }}</option>
                @endforeach
            </x-framework.forms.select>

            <div class="flex justify-end gap-3 pt-4">
                <x-framework.buttons.secondary type="button" @click="$dispatch('close-modal', 'add-language')">
                    Cancel
                </x-framework.buttons.secondary>
                <x-framework.buttons.primary type="submit">
                    Add Language
                </x-framework.buttons.primary>
            </div>
        </form>
    </x-framework.feedback.modal>
</x-framework.layout.card>
