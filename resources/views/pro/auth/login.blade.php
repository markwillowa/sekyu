@extends('layouts.app')

@section('content')
    <div class="min-h-screen bg-slate-100 px-3 pt-1 pb-3 sm:px-4 sm:py-8">
        <div class="mx-auto flex min-h-[calc(100vh-1rem)] max-w-5xl flex-col justify-center">
            <div class="mb-3 text-center sm:mb-6">
                <div class="mx-auto flex h-12 w-12 items-center justify-center rounded-2xl bg-slate-900 text-2xl font-bold text-amber-400 shadow sm:h-16 sm:w-16 sm:text-3xl">
                    S
                </div>

                <h1 class="mt-2 text-2xl font-extrabold tracking-tight text-slate-900 sm:mt-4 sm:text-4xl">
                    SEKYU PRO
                </h1>

                <p class="mt-1 text-xs font-semibold uppercase tracking-[0.2em] text-slate-500 sm:text-sm sm:tracking-[0.25em]">
                    Guard On Duty System
                </p>
            </div>

            <div class="overflow-hidden rounded-2xl bg-white shadow-xl sm:rounded-3xl">
                <div class="grid lg:grid-cols-2">
                    {{-- Desktop Hero --}}
                    <div class="hidden bg-slate-900 p-10 text-white lg:flex lg:flex-col lg:justify-between">
                        <div>
                            <div class="flex h-24 w-24 items-center justify-center rounded-full bg-white/10 text-5xl">
                                👮
                            </div>

                            <h2 class="mt-8 text-4xl font-bold leading-tight">
                                Welcome back, Guard.
                            </h2>

                            <p class="mt-4 text-lg leading-8 text-slate-300">
                                Sign in first, then complete your face liveness verification before going on duty.
                            </p>
                        </div>

                        <div class="rounded-2xl bg-white/10 p-5">
                            <p class="text-sm font-semibold text-amber-300">
                                Reminder
                            </p>

                            <p class="mt-2 text-sm leading-6 text-slate-300">
                                Use your assigned account only. Ask your supervisor if you forgot your login details.
                            </p>
                        </div>
                    </div>

                    {{-- Login Form --}}
                    <div class="p-5 sm:p-10">
                        <div class="text-center lg:text-left">
                            <p class="text-xs font-bold uppercase tracking-wider text-amber-600 sm:text-sm">
                                Guard Login
                            </p>

                            <h2 class="mt-1 text-2xl font-bold text-slate-900 sm:mt-2 sm:text-3xl">
                                Sign in to continue
                            </h2>

                            <p class="mt-1 text-sm text-slate-500 sm:mt-2 sm:text-base">
                                Enter your username and password.
                            </p>
                        </div>

                        <form
                            method="POST"
                            action="{{ route('pro.login.store') }}"
                            class="mt-6 space-y-4 sm:mt-8 sm:space-y-5"
                        >
                            @csrf

                            <div>
                                <label
                                    for="username"
                                    class="text-base font-bold text-slate-800 sm:text-lg"
                                >
                                    Username
                                </label>

                                <input
                                    id="username"
                                    type="text"
                                    name="username"
                                    value="{{ old('username') }}"
                                    placeholder="Enter username"
                                    class="mt-2 block w-full rounded-xl border-slate-300 px-4 py-3 text-base shadow-sm focus:border-amber-500 focus:ring-amber-500 sm:px-5 sm:py-4 sm:text-lg"
                                    required
                                    autofocus
                                >

                                @error('username')
                                <p class="mt-2 text-sm font-semibold text-red-600">
                                    {{ $message }}
                                </p>
                                @enderror
                            </div>

                            <div>
                                <label
                                    for="password"
                                    class="text-base font-bold text-slate-800 sm:text-lg"
                                >
                                    Password
                                </label>

                                <input
                                    id="password"
                                    type="password"
                                    name="password"
                                    placeholder="Enter your password"
                                    class="mt-2 block w-full rounded-xl border-slate-300 px-4 py-3 text-base shadow-sm focus:border-amber-500 focus:ring-amber-500 sm:px-5 sm:py-4 sm:text-lg"
                                    required
                                >
                            </div>

                            <label class="flex items-center gap-3 text-sm font-semibold text-slate-700 sm:text-base">
                                <input
                                    type="checkbox"
                                    name="remember"
                                    value="1"
                                    class="h-5 w-5 rounded border-slate-300 text-amber-500 focus:ring-amber-500 sm:h-6 sm:w-6"
                                >

                                Remember me
                            </label>

                            <button
                                type="submit"
                                class="flex w-full items-center justify-center rounded-xl bg-slate-900 px-5 py-4 text-lg font-extrabold uppercase tracking-wide text-white shadow-lg transition hover:bg-slate-800 sm:px-6 sm:py-5 sm:text-xl"
                            >
                                Sign In
                            </button>
                        </form>

                        <div class="mt-5 rounded-2xl bg-slate-50 p-4 sm:mt-8 sm:p-5">
                            <p class="text-base font-bold text-slate-900 sm:text-lg">
                                Need help?
                            </p>

                            <p class="mt-1 text-sm leading-6 text-slate-600 sm:text-base">
                                Please contact your supervisor or system administrator.
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <p class="mt-4 text-center text-xs text-slate-500 sm:mt-6 sm:text-sm">
                SEKYU PRO Guard On Duty System
            </p>
        </div>
    </div>
@endsection
