@extends('pro.layouts.guest')

@section('title', 'SEKYU PRO Login')

@section('content')

    @php
        $initialMode = $errors->has('username') ? 'agency' : 'employee';
    @endphp

    <div
        x-data="{ mode: @js($initialMode) }"
        class="min-h-screen bg-slate-100"
    >
        <div class="grid min-h-screen lg:grid-cols-[minmax(0,1fr)_31rem]">
            <section class="relative hidden overflow-hidden bg-slate-950 text-white lg:block">
                <div class="absolute inset-0 opacity-20">
                    <div class="h-full w-full bg-[linear-gradient(90deg,rgba(255,255,255,.08)_1px,transparent_1px),linear-gradient(0deg,rgba(255,255,255,.08)_1px,transparent_1px)] bg-[size:44px_44px]"></div>
                </div>

                <div class="relative flex min-h-screen flex-col justify-between px-12 py-10 xl:px-16">
                    <div class="flex items-center gap-4">
                        <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-amber-500 text-xl font-black text-slate-950">
                            S
                        </div>

                        <div>
                            <div class="text-xl font-black tracking-tight">
                                SEKYU PRO
                            </div>

                            <div class="text-xs font-semibold uppercase tracking-[0.28em] text-slate-500">
                                Workforce Access
                            </div>
                        </div>
                    </div>

                    <div class="max-w-2xl">
                        <div class="mb-6 inline-flex items-center gap-2 rounded-full border border-white/10 bg-white/5 px-4 py-2 text-sm font-semibold text-amber-200">
                            <span class="h-2 w-2 rounded-full bg-emerald-400"></span>
                            Secure team portal
                        </div>

                        <h1 class="max-w-xl text-5xl font-black leading-tight tracking-tight xl:text-6xl">
                            Built for guards, supervisors, and agency teams in motion.
                        </h1>

                        <p class="mt-5 max-w-xl text-lg leading-8 text-slate-300">
                            Sign in to view assignments, messages, attendance, and workforce updates from one operational workspace.
                        </p>

                        <div class="mt-10 grid max-w-xl grid-cols-3 overflow-hidden rounded-lg border border-white/10 bg-white/5">
                            <div class="border-r border-white/10 p-5">
                                <div class="text-2xl font-black text-white">
                                    24/7
                                </div>

                                <div class="mt-1 text-xs font-semibold uppercase tracking-wider text-slate-400">
                                    Coverage
                                </div>
                            </div>

                            <div class="border-r border-white/10 p-5">
                                <div class="text-2xl font-black text-white">
                                    PRO
                                </div>

                                <div class="mt-1 text-xs font-semibold uppercase tracking-wider text-slate-400">
                                    Operations
                                </div>
                            </div>

                            <div class="p-5">
                                <div class="text-2xl font-black text-white">
                                    PIN
                                </div>

                                <div class="mt-1 text-xs font-semibold uppercase tracking-wider text-slate-400">
                                    Access
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="flex items-center justify-between border-t border-white/10 pt-6 text-sm text-slate-500">
                        <span>© {{ now()->year }} SEKYU</span>
                        <span>Professional Security Workforce</span>
                    </div>
                </div>
            </section>

            <main class="flex min-h-screen items-center justify-center px-5 py-8 sm:px-8">
                <div class="w-full max-w-md">
                    <div class="mb-8 flex items-center justify-center gap-3 lg:hidden">
                        <div class="flex h-11 w-11 items-center justify-center rounded-xl bg-slate-950 text-lg font-black text-amber-400">
                            S
                        </div>

                        <div>
                            <div class="text-xl font-black text-slate-950">
                                SEKYU PRO
                            </div>

                            <div class="text-xs font-semibold uppercase tracking-[0.22em] text-slate-500">
                                Workforce Access
                            </div>
                        </div>
                    </div>

                    <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-xl shadow-slate-200/70 sm:p-8">
                        <div class="mb-7">
                            <div class="inline-flex h-11 w-11 items-center justify-center rounded-xl bg-slate-950 text-amber-400">
                                <x-framework.icon
                                    name="shield-check"
                                    class="h-6 w-6"
                                />
                            </div>

                            <h2 class="mt-5 text-3xl font-black tracking-tight text-slate-950">
                                Welcome back
                            </h2>

                            <p class="mt-2 text-sm leading-6 text-slate-500">
                                Select your account type and enter your credentials.
                            </p>
                        </div>

                        <div class="mb-7 grid grid-cols-2 rounded-full bg-slate-100 p-1">
                            <button
                                type="button"
                                @click="mode = 'employee'"
                                :class="mode === 'employee'
                                    ? 'bg-slate-950 text-white shadow-sm'
                                    : 'text-slate-600 hover:text-slate-950'"
                                class="inline-flex h-11 items-center justify-center gap-2 rounded-full px-4 text-sm font-bold transition"
                            >
                                <x-framework.icon
                                    name="identification"
                                    class="h-4 w-4"
                                />

                                <span>Employee</span>
                            </button>

                            <button
                                type="button"
                                @click="mode = 'agency'"
                                :class="mode === 'agency'
                                    ? 'bg-slate-950 text-white shadow-sm'
                                    : 'text-slate-600 hover:text-slate-950'"
                                class="inline-flex h-11 items-center justify-center gap-2 rounded-full px-4 text-sm font-bold transition"
                            >
                                <x-framework.icon
                                    name="building-office-2"
                                    class="h-4 w-4"
                                />

                                <span>Agency</span>
                            </button>
                        </div>

                        <form
                            x-show="mode === 'employee'"
                            x-cloak
                            method="POST"
                            action="{{ route('pro.employee.login') }}"
                            class="space-y-5"
                        >
                            @csrf

                            <x-framework.forms.input
                                label="Employee Number"
                                name="employee_no"
                                autocomplete="username"
                            />

                            <x-framework.forms.input
                                label="PIN"
                                name="pin"
                                type="password"
                                autocomplete="current-password"
                            />

                            <x-framework.buttons.primary class="w-full bg-amber-500 text-slate-950 hover:bg-amber-400">
                                Sign in as Employee
                            </x-framework.buttons.primary>
                        </form>

                        <form
                            x-show="mode === 'agency'"
                            x-cloak
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

                            <x-framework.buttons.primary class="w-full">
                                Sign in as Agency
                            </x-framework.buttons.primary>
                        </form>

                        <div class="mt-7 flex items-center justify-center gap-2 border-t border-slate-100 pt-5 text-xs font-semibold text-slate-400">
                            <x-framework.icon
                                name="lock-closed"
                                class="h-4 w-4"
                            />

                            <span>Protected SEKYU PRO session</span>
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </div>

@endsection
