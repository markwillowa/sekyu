@extends('layouts.app')

@section('content')
    <section class="bg-slate-50 py-10">
        <div class="mx-auto max-w-4xl px-6">

            <x-framework.layout.page-header
                title="Create Workflow Template"
                description="Standardize your hiring process."
            >
                <x-slot:actions>
                    <x-framework.buttons.secondary
                        :href="route('agency.workflow-templates.index')"
                    >
                        Back to Templates
                    </x-framework.buttons.secondary>
                </x-slot:actions>
            </x-framework.layout.page-header>

            <form
                method="POST"
                action="{{ route('agency.workflow-templates.store') }}"
                class="space-y-8"
            >
                @csrf

                <x-framework.layout.card>
                    <x-framework.layout.section
                        title="Template Details"
                        description="Basic information about this hiring process."
                    >
                        <x-framework.layout.grid :cols="1">
                            <x-framework.forms.input
                                name="name"
                                label="Template Name"
                                :value="old('name')"
                                required
                            />

                            <x-framework.editor.rich-text
                                name="description"
                                label="Description"
                                :value="old('description')"
                            />

                            <x-framework.forms.checkbox
                                name="is_default"
                                label="Set as default for new jobs"
                                :checked="old('is_default')"
                            />
                        </x-framework.layout.grid>
                    </x-framework.layout.section>

                    <x-slot:footer>
                        <div class="flex justify-end gap-3">
                            <x-framework.buttons.secondary
                                :href="route('agency.workflow-templates.index')"
                            >
                                Cancel
                            </x-framework.buttons.secondary>

                            <x-framework.buttons.primary type="submit">
                                Create & Add Steps
                            </x-framework.buttons.primary>
                        </div>
                    </x-slot:footer>
                </x-framework.layout.card>
            </form>
        </div>
    </section>
@endsection
