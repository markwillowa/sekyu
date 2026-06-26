@extends('layouts.app')

@section('content')
    <div class="min-h-screen bg-slate-100 px-4 py-6 sm:py-10">
        <div class="mx-auto max-w-5xl">
            <div class="mb-6 text-center">
                <div class="mx-auto flex h-16 w-16 items-center justify-center rounded-2xl bg-slate-900 text-3xl font-bold text-amber-400 shadow">
                    S
                </div>

                <h1 class="mt-4 text-3xl font-extrabold tracking-tight text-slate-900 sm:text-4xl">
                    SEKYU PRO
                </h1>

                <p class="mt-1 text-sm font-semibold uppercase tracking-[0.25em] text-slate-500">
                    Guard On Duty System
                </p>
            </div>

            <div class="overflow-hidden rounded-3xl bg-white shadow-xl">
                <div class="grid lg:grid-cols-2">
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

                    <div class="p-6 sm:p-10">
                        <div class="text-center lg:text-left">
                            <p class="text-sm font-bold uppercase tracking-wider text-amber-600">
                                Guard Login
                            </p>

                            <h2 class="mt-2 text-3xl font-bold text-slate-900">
                                Sign in to continue
                            </h2>

                            <p class="mt-2 text-base text-slate-500">
                                Enter your email and password.
                            </p>
                        </div>

                        <form method="POST" action="{{ route('pro.login.store') }}" class="mt-8 space-y-5">
                            @csrf

                            <div>
                                <label for="email" class="text-lg font-bold text-slate-800">
                                    Email Address
                                </label>

                                <input
                                    id="email"
                                    type="email"
                                    name="email"
                                    value="{{ old('email') }}"
                                    placeholder="Enter your email"
                                    class="mt-2 block w-full rounded-xl border-slate-300 px-5 py-4 text-lg shadow-sm focus:border-amber-500 focus:ring-amber-500"
                                    required
                                    autofocus
                                >

                                @error('email')
                                <p class="mt-2 text-base font-semibold text-red-600">
                                    {{ $message }}
                                </p>
                                @enderror
                            </div>

                            <div>
                                <label for="password" class="text-lg font-bold text-slate-800">
                                    Password
                                </label>

                                <input
                                    id="password"
                                    type="password"
                                    name="password"
                                    placeholder="Enter your password"
                                    class="mt-2 block w-full rounded-xl border-slate-300 px-5 py-4 text-lg shadow-sm focus:border-amber-500 focus:ring-amber-500"
                                    required
                                >
                            </div>

                            <label class="flex items-center gap-3 text-base font-semibold text-slate-700">
                                <input
                                    type="checkbox"
                                    name="remember"
                                    value="1"
                                    class="h-6 w-6 rounded border-slate-300 text-amber-500 focus:ring-amber-500"
                                >

                                Remember me
                            </label>

                            <button
                                type="submit"
                                class="flex w-full items-center justify-center gap-3 rounded-xl bg-slate-900 px-6 py-5 text-xl font-extrabold uppercase tracking-wide text-white shadow-lg transition hover:bg-slate-800"
                            >
                                <span>Sign In</span>
                            </button>
                        </form>

                        <div class="mt-8 rounded-2xl bg-slate-50 p-5">
                            <p class="text-lg font-bold text-slate-900">
                                Need help?
                            </p>

                            <p class="mt-1 text-base leading-7 text-slate-600">
                                Please contact your supervisor or system administrator.
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <p class="mt-6 text-center text-sm text-slate-500">
                SEKYU PRO Guard On Duty System
            </p>
        </div>
    </div>
@endsection
