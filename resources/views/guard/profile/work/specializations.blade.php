<x-framework.layout.card>
    <div class="flex items-center justify-between border-b border-slate-200 pb-5">
        <div>
            <h2 class="text-xl font-bold text-slate-900">
                Specializations
            </h2>

            <p class="mt-1 text-sm text-slate-500">
                Security areas where you have experience or preference.
            </p>
        </div>

        <x-framework.buttons.primary type="button" size="sm" @click.prevent="$dispatch('open-modal', 'add-specialization')">
            Add Specialization
        </x-framework.buttons.primary>
    </div>

    @if ($specializations->isEmpty())
        <div class="mt-6 rounded-2xl border border-dashed border-slate-300 bg-slate-50 p-8 text-center">
            <h3 class="text-lg font-bold text-slate-900">
                No specializations added yet
            </h3>

            <div class="mt-4">
                <x-framework.buttons.primary type="button" size="sm" @click.prevent="$dispatch('open-modal', 'add-specialization')">
                    Add Specialization
                </x-framework.buttons.primary>
            </div>
        </div>
    @else
        <div class="mt-6 flex flex-wrap gap-2">
            @foreach ($specializations as $specialization)
                <div class="group relative">
                    <x-framework.feedback.badge color="slate" class="pr-8">
                        {{ $specialization->specialization?->name ?? 'Specialization' }}
                    </x-framework.feedback.badge>

                    <form action="{{ route('applicant.profile.delete-specialization', $specialization) }}" method="POST" class="absolute right-1 top-1/2 -translate-y-1/2 opacity-0 group-hover:opacity-100 transition-opacity">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="text-slate-400 hover:text-red-600" onclick="return confirm('Remove this specialization?')">
                            <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </form>
                </div>
            @endforeach
        </div>
    @endif

    <!-- Add Specialization Modal -->
    <x-framework.feedback.modal name="add-specialization" title="Add Specialization">
        <form action="{{ route('applicant.profile.store-specialization') }}" method="POST" class="space-y-4">
            @csrf

            <x-framework.forms.select
                label="Specialization"
                name="master_specialization_id"
                required
            >
                <option value="">Select a specialization</option>
                @foreach($allSpecializations as $spec)
                    <option value="{{ $spec->id }}">{{ $spec->name }}</option>
                @endforeach
            </x-framework.forms.select>

            <x-framework.forms.input
                label="Years of Experience"
                name="years_of_experience"
                type="number"
                min="0"
                value="0"
            />

            <x-framework.forms.textarea
                label="Description"
                name="description"
                placeholder="Optional description of your experience in this area..."
            />

            <div class="flex justify-end gap-3 pt-4">
                <x-framework.buttons.secondary type="button" @click="$dispatch('close-modal', 'add-specialization')">
                    Cancel
                </x-framework.buttons.secondary>
                <x-framework.buttons.primary type="submit">
                    Add Specialization
                </x-framework.buttons.primary>
            </div>
        </form>
    </x-framework.feedback.modal>
</x-framework.layout.card>
