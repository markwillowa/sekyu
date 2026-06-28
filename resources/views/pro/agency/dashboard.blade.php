@extends('pro.layouts.app')

@section('content')

    <x-framework.layout.page-header
        :title="'Good ' . (now()->hour < 12 ? 'Morning' : (now()->hour < 18 ? 'Afternoon' : 'Evening')) . ', ' . auth('pro_agency')->user()->name . ' 👋'"
        description="Welcome to SEKYU PRO. Let's get your workforce ready."
    />

    @php
        $employeeCount = auth('pro_agency')->user()->agency->employees()->count();
    @endphp

    <div class="space-y-8">

        {{-- Welcome --}}
        <x-framework.layout.card>

            <div class="flex flex-col gap-8 lg:flex-row lg:items-center lg:justify-between">

                <div>

                    <h2 class="text-2xl font-bold text-slate-900">

                        Start building your workforce

                    </h2>

                    <p class="mt-3 max-w-2xl leading-7 text-slate-600">

                        @if($employeeCount === 0)
                            You haven't onboarded any employees yet.
                        @else
                            You have onboarded {{ $employeeCount }} {{ \Illuminate\Support\Str::plural('employee', $employeeCount) }}.
                        @endif

                        Once employees are added, attendance,
                        assignments, training and reports will
                        automatically appear here.

                    </p>

                </div>

                <div>

                    <x-framework.buttons.primary href="{{ route('pro.agency.onboarding.create') }}">

                        Start Onboarding

                    </x-framework.buttons.primary>

                </div>

            </div>

        </x-framework.layout.card>

        {{-- Quick Actions --}}
        <x-framework.layout.section
            title="Quick Actions"
        >

            <x-framework.layout.grid cols="2">

                <x-framework.layout.action-card
                    title="Onboard Employee"
                    description="Create your first employee."
                    icon="user-plus"
                    href="{{ route('pro.agency.onboarding.create') }}"
                />

                <x-framework.layout.action-card
                    title="Departments"
                    description="Organize your workforce."
                    icon="building-office"
                    href="#"
                />

                <x-framework.layout.action-card
                    title="Sites"
                    description="Register deployment sites."
                    icon="map-pin"
                    href="#"
                />

                <x-framework.layout.action-card
                    title="Shifts"
                    description="Configure work schedules."
                    icon="clock"
                    href="#"
                />

            </x-framework.layout.grid>

        </x-framework.layout.section>

        {{-- Activity --}}
        <x-framework.layout.section
            title="Recent Activity"
        >

            <x-framework.layout.card>

                <div class="py-16 text-center">

                    <div
                        class="mx-auto flex h-16 w-16 items-center justify-center rounded-full bg-slate-100"
                    >

                        <x-framework.icon
                            name="clock"
                            class="h-8 w-8 text-slate-400"
                        />

                    </div>

                    <h3
                        class="mt-6 text-lg font-semibold text-slate-900"
                    >

                        Nothing yet

                    </h3>

                    <p
                        class="mx-auto mt-2 max-w-md text-sm leading-6 text-slate-500"
                    >

                        Employee activity will appear here after
                        onboarding and workforce operations begin.

                    </p>

                </div>

            </x-framework.layout.card>

        </x-framework.layout.section>

    </div>

@endsection
