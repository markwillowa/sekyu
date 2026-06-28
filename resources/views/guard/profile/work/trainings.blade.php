<x-framework.layout.card>
    <div class="flex items-center justify-between border-b border-slate-200 pb-5">
        <div>
            <h2 class="text-xl font-bold text-slate-900">
                Trainings
            </h2>

            <p class="mt-1 text-sm text-slate-500">
                Security, safety, and professional trainings completed.
            </p>
        </div>

        <x-framework.buttons.primary type="button" size="sm" @click.prevent="$dispatch('open-modal', 'add-training')">
            Add Training
        </x-framework.buttons.primary>
    </div>

    @if ($trainings->isEmpty())
        <div class="mt-6 rounded-2xl border border-dashed border-slate-300 bg-slate-50 p-8 text-center">
            <h3 class="text-lg font-bold text-slate-900">
                No trainings added yet
            </h3>

            <div class="mt-4">
                <x-framework.buttons.primary type="button" size="sm" @click.prevent="$dispatch('open-modal', 'add-training')">
                    Add Training
                </x-framework.buttons.primary>
            </div>
        </div>
    @else
        <div class="mt-6 space-y-4">
            @foreach ($trainings as $training)
                <article class="group relative rounded-2xl border border-slate-200 p-5">
                    <div class="absolute right-4 top-4 flex items-center gap-2 opacity-0 group-hover:opacity-100 transition-opacity">
                        <button type="button" @click.prevent="$dispatch('open-modal', 'edit-training-{{ $training->id }}')" class="text-slate-400 hover:text-blue-600">
                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0 1 15.75 21H5.25A2.25 2.25 0 0 1 3 18.75V8.25A2.25 2.25 0 0 1 5.25 6H10" />
                            </svg>
                        </button>
                    </div>

                    <h3 class="text-lg font-bold text-slate-900">
                        {{ $training->trainingType?->name ?? $training->title ?? 'Training' }}
                    </h3>

                    <p class="mt-1 text-sm text-slate-500">
                        {{ $training->training_provider ?? 'Provider not provided' }}
                    </p>

                <div class="mt-2 flex gap-4">
                    @if ($training->completed_at)
                        <p class="text-sm text-slate-500">
                            Completed {{ $training->completed_at->format('F d, Y') }}
                        </p>
                    @endif

                    @if ($training->hours)
                        <p class="text-sm text-slate-500">
                            {{ $training->hours }} Hours
                        </p>
                    @endif
                </div>

                @if ($training->hasMedia('trainings'))
                    <div class="mt-4">
                        <p class="mb-2 text-xs font-bold uppercase tracking-wider text-slate-400">
                            Attachment
                        </p>

                        @php
                            $media = $training->getFirstMedia('trainings');
                            $isPdf = $media && $media->mime_type === 'application/pdf';
                        @endphp

                        <a
                            href="{{ $training->getFirstMediaUrl('trainings') }}"
                            target="_blank"
                            class="group relative inline-block overflow-hidden rounded-xl border border-slate-200"
                        >
                            @if($isPdf)
                                <div class="flex h-32 w-48 flex-col items-center justify-center bg-slate-50 transition duration-300 group-hover:bg-slate-100">
                                    <svg class="h-12 w-12 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                                    </svg>
                                    <span class="mt-2 text-xs font-medium text-slate-500">PDF Document</span>
                                </div>
                            @else
                                <img
                                    src="{{ $training->getFirstMediaUrl('trainings') }}"
                                    alt="Training Attachment"
                                    class="h-32 w-48 object-cover transition duration-300 group-hover:scale-110"
                                >
                            @endif

                            <div class="absolute inset-0 flex items-center justify-center bg-slate-900/40 opacity-0 transition duration-300 group-hover:opacity-100">
                                <svg class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                </svg>
                            </div>
                        </a>
                    </div>
                @endif
                </article>

                <!-- Edit Training Modal -->
                <x-framework.feedback.modal name="edit-training-{{ $training->id }}" title="Edit Training">
                    <form action="{{ route('applicant.profile.update-training', $training) }}" method="POST" enctype="multipart/form-data" class="space-y-4">
                        @csrf
                        @method('PATCH')

                        <x-framework.forms.select
                            label="Training Type"
                            name="master_training_type_id"
                            required
                        >
                            <option value="">Select training type</option>
                            @foreach($trainingTypes as $type)
                                <option value="{{ $type->id }}" @selected($training->master_training_type_id == $type->id)>{{ $type->name }}</option>
                            @endforeach
                        </x-framework.forms.select>

                        <x-framework.forms.input
                            label="Training Title"
                            name="title"
                            required
                            value="{{ $training->title }}"
                        />

                        <x-framework.forms.input
                            label="Training Provider"
                            name="training_provider"
                            value="{{ $training->training_provider }}"
                        />

                        <div class="grid grid-cols-2 gap-4">
                            <x-framework.forms.input
                                label="Completed At"
                                name="completed_at"
                                type="date"
                                value="{{ $training->completed_at?->format('Y-m-d') }}"
                            />

                            <x-framework.forms.input
                                label="Hours"
                                name="hours"
                                type="number"
                                min="0"
                                value="{{ $training->hours }}"
                            />
                        </div>

                        <x-framework.forms.input
                            label="Certificate Number"
                            name="certificate_number"
                            value="{{ $training->certificate_number }}"
                        />

                        <x-framework.forms.textarea
                            label="Description"
                            name="description"
                        >{{ $training->description }}</x-framework.forms.textarea>

                        <x-framework.forms.file
                            label="Update Attachment (Image or PDF)"
                            name="attachment"
                            accept="image/*,.pdf"
                        />

                        <div class="flex items-center justify-between pt-4">
                            <button
                                type="submit"
                                form="delete-training-{{ $training->id }}"
                                class="text-sm font-bold text-red-600 hover:text-red-700"
                                onclick="return confirm('Are you sure you want to remove this training?')"
                            >
                                Remove Training
                            </button>

                            <div class="flex items-center gap-3">
                                <x-framework.buttons.secondary type="button" @click="$dispatch('close-modal', 'edit-training-{{ $training->id }}')">
                                    Cancel
                                </x-framework.buttons.secondary>
                                <x-framework.buttons.primary type="submit">
                                    Update Training
                                </x-framework.buttons.primary>
                            </div>
                        </div>
                    </form>

                    <form id="delete-training-{{ $training->id }}" action="{{ route('applicant.profile.delete-training', $training) }}" method="POST" class="hidden">
                        @csrf
                        @method('DELETE')
                    </form>
                </x-framework.feedback.modal>
            @endforeach
        </div>
    @endif

    <!-- Add Training Modal -->
    <x-framework.feedback.modal name="add-training" title="Add Training">
        <form action="{{ route('applicant.profile.store-training') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
            @csrf

            <x-framework.forms.select
                label="Training Type"
                name="master_training_type_id"
                required
            >
                <option value="">Select training type</option>
                @foreach($trainingTypes as $type)
                    <option value="{{ $type->id }}">{{ $type->name }}</option>
                @endforeach
            </x-framework.forms.select>

            <x-framework.forms.input
                label="Training Title"
                name="title"
                required
                placeholder="e.g. Basic Security Training"
            />

            <x-framework.forms.input
                label="Training Provider"
                name="training_provider"
                placeholder="e.g. Security Training Institute"
            />

            <div class="grid grid-cols-2 gap-4">
                <x-framework.forms.input
                    label="Completed At"
                    name="completed_at"
                    type="date"
                />

                <x-framework.forms.input
                    label="Hours"
                    name="hours"
                    type="number"
                    min="0"
                />
            </div>

            <x-framework.forms.input
                label="Certificate Number"
                name="certificate_number"
            />

            <x-framework.forms.textarea
                label="Description"
                name="description"
                placeholder="Briefly describe what you learned..."
            />

            <x-framework.forms.file
                label="Attachment (Image or PDF)"
                name="attachment"
                accept="image/*,.pdf"
            />

            <div class="flex justify-end gap-3 pt-4">
                <x-framework.buttons.secondary type="button" @click="$dispatch('close-modal', 'add-training')">
                    Cancel
                </x-framework.buttons.secondary>
                <x-framework.buttons.primary type="submit">
                    Add Training
                </x-framework.buttons.primary>
            </div>
        </form>
    </x-framework.feedback.modal>
</x-framework.layout.card>
