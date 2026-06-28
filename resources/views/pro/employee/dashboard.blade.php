@extends('pro.layouts.app')

@section('title', 'Dashboard')

@section('content')

    <x-framework.layout.page-header
        title="Welcome back"
        description="Your workforce management platform is ready."
    />

    <x-framework.layout.card>

        <div class="space-y-6">

            <div>

                <h2 class="text-xl font-bold">

                    {{ auth('pro_agency')->user()->name }}

                </h2>

                <p class="mt-2 text-slate-600">

                    There are currently no employees.

                    Start by onboarding your first employee.

                </p>

            </div>

            <div>

                <x-framework.buttons.primary>

                    Start Onboarding

                </x-framework.buttons.primary>

            </div>

        </div>

    </x-framework.layout.card>

@endsection
