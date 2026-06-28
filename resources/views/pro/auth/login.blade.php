@extends('pro.layouts.guest')

@section('title', 'SEKYU PRO')

@section('content')

    <div
        x-data="{ mode: 'agency' }"
        class="min-h-screen bg-slate-100"
    >

        <div class="grid min-h-screen lg:grid-cols-2">

            {{-- Left --}}
            <div class="hidden bg-slate-950 text-white lg:flex">

                <div class="mx-auto flex w-full max-w-xl flex-col justify-center px-16">

                    <div class="mb-10">

                        <div class="mb-5 flex h-16 w-16 items-center justify-center rounded-2xl bg-white/10">

                        <span class="text-3xl font-black">
                            S
                        </span>

                        </div>

                        <h1 class="text-5xl font-black tracking-tight">
                            SEKYU PRO
                        </h1>

                        <p class="mt-4 text-lg text-slate-300">
                            Human Resource & Workforce Management Platform
                        </p>

                    </div>

                    <div class="space-y-5">

                        @foreach([
                            'Employee Management',
                            'Attendance Monitoring',
                            'Deployment Scheduling',
                            'Training Records',
                            'Equipment Tracking',
                            'Payroll Integration',
                        ] as $feature)

                            <div class="flex items-center gap-3">

                                <div class="h-2.5 w-2.5 rounded-full bg-emerald-400"></div>

                                <span class="text-slate-200">

                                {{ $feature }}

                            </span>

                            </div>

                        @endforeach

                    </div>

                    <div class="mt-16 text-sm text-slate-500">

                        © {{ now()->year }} SEKYU

                    </div>

                </div>

            </div>

            {{-- Right --}}
            <div class="flex items-center justify-center p-6 lg:p-12">

                <x-framework.layout.card
                    class="w-full max-w-md"
                    padding="p-8"
                >

                    <div class="mb-8 text-center">

                        <h2 class="text-3xl font-bold text-slate-900">

                            Welcome Back

                        </h2>

                        <p class="mt-2 text-slate-500">

                            Sign in to continue.

                        </p>

                    </div>

                    {{-- Switch --}}
                    <div class="mb-8 grid grid-cols-2 rounded-xl bg-slate-100 p-1">

                        <button
                            type="button"
                            @click="mode='agency'"
                            :class="mode==='agency'
                            ? 'bg-slate-900 text-white shadow'
                            : 'text-slate-600'"
                            class="rounded-lg px-4 py-2 text-sm font-semibold transition"
                        >

                            Agency

                        </button>

                        <button
                            type="button"
                            @click="mode='employee'"
                            :class="mode==='employee'
                            ? 'bg-slate-900 text-white shadow'
                            : 'text-slate-600'"
                            class="rounded-lg px-4 py-2 text-sm font-semibold transition"
                        >

                            Employee

                        </button>

                    </div>

                    {{-- Agency Login --}}
                    <form
                        x-show="mode==='agency'"
                        method="POST"
                        action="{{ route('pro.agency.login') }}"
                        class="space-y-5"
                    >

                        @csrf

                        <x-framework.forms.input
                            label="Username"
                            name="username"
                            autocomplete="username"
                        />

                        <x-framework.forms.input
                            label="PIN"
                            name="pin"
                            type="password"
                            autocomplete="current-password"
                        />

                        <x-framework.buttons.primary
                            class="w-full"
                        >
                            Sign In
                        </x-framework.buttons.primary>

                    </form>

                    {{-- Employee Login --}}
                    <form
                        x-show="mode==='employee'"
                        method="POST"
                        action="{{ route('pro.employee.login') }}"
                        class="space-y-5"
                    >

                        @csrf

                        <x-framework.forms.input
                            label="Employee Number"
                            name="employee_no"
                        />

                        <x-framework.forms.input
                            label="PIN"
                            name="pin"
                            type="password"
                        />

                        <x-framework.buttons.primary
                            class="w-full"
                        >
                            Sign In
                        </x-framework.buttons.primary>

                    </form>

                    <div class="mt-8 text-center text-sm text-slate-500">

                        Powered by

                        <span class="font-semibold text-slate-900">

                        SEKYU

                    </span>

                    </div>

                </x-framework.layout.card>

            </div>

        </div>

    </div>

@endsection
