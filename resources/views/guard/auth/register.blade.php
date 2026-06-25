@extends('layouts.app')

@section('content')
    <section class="bg-slate-50 py-24">
        <div class="mx-auto grid max-w-7xl gap-12 px-6 lg:grid-cols-2 lg:items-center">

            <div>
            <span class="inline-flex rounded-full bg-amber-100 px-4 py-2 text-sm font-semibold text-amber-700">
                Guard Registration
            </span>

                <h1 class="mt-6 text-5xl font-bold tracking-tight text-slate-900">
                    Create your SEKYU guard profile
                </h1>

                <p class="mt-5 text-lg leading-8 text-slate-600">
                    Start with your basic account. You can complete your documents,
                    licenses, trainings, and work history after signing in.
                </p>
            </div>

            <div class="rounded-3xl border border-slate-200 bg-white p-8 shadow-sm">
                <h2 class="text-2xl font-bold text-slate-900">
                    Register as Guard
                </h2>

                <form
                    method="POST"
                    action="{{ route('guard.register.store') }}"
                    class="mt-8 space-y-5"
                >
                    @csrf

                    <div class="grid gap-5 sm:grid-cols-2">
                        <div>
                            <label class="block text-sm font-medium text-slate-700">
                                First name
                            </label>

                            <input
                                name="first_name"
                                value="{{ old('first_name') }}"
                                required
                                class="mt-2 block w-full rounded-lg border-slate-300 px-4 py-3 shadow-sm focus:border-amber-500 focus:ring-amber-500"
                            >

                            @error('first_name')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-slate-700">
                                Last name
                            </label>

                            <input
                                name="last_name"
                                value="{{ old('last_name') }}"
                                required
                                class="mt-2 block w-full rounded-lg border-slate-300 px-4 py-3 shadow-sm focus:border-amber-500 focus:ring-amber-500"
                            >

                            @error('last_name')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="grid gap-5 sm:grid-cols-2">
                        <div>
                            <label class="block text-sm font-medium text-slate-700">
                                Middle name
                            </label>

                            <input
                                name="middle_name"
                                value="{{ old('middle_name') }}"
                                class="mt-2 block w-full rounded-lg border-slate-300 px-4 py-3 shadow-sm focus:border-amber-500 focus:ring-amber-500"
                            >
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-slate-700">
                                Suffix
                            </label>

                            <input
                                name="suffix"
                                value="{{ old('suffix') }}"
                                placeholder="Jr., Sr., III"
                                class="mt-2 block w-full rounded-lg border-slate-300 px-4 py-3 shadow-sm focus:border-amber-500 focus:ring-amber-500"
                            >
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-slate-700">
                            Email address
                        </label>

                        <input
                            name="email"
                            type="email"
                            value="{{ old('email') }}"
                            required
                            class="mt-2 block w-full rounded-lg border-slate-300 px-4 py-3 shadow-sm focus:border-amber-500 focus:ring-amber-500"
                        >

                        @error('email')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-slate-700">
                            Password
                        </label>

                        <input
                            id="password"
                            name="password"
                            type="password"
                            autocomplete="new-password"
                            required
                            class="mt-2 block w-full rounded-lg border-slate-300 px-4 py-3 shadow-sm focus:border-amber-500 focus:ring-amber-500"
                        >

                        @error('password')
                        <p class="mt-2 text-sm text-red-600">
                            {{ $message }}
                        </p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-slate-700">
                            Confirm Password
                        </label>

                        <input
                            id="password_confirmation"
                            name="password_confirmation"
                            type="password"
                            autocomplete="new-password"
                            required
                            class="mt-2 block w-full rounded-lg border-slate-300 px-4 py-3 shadow-sm focus:border-amber-500 focus:ring-amber-500"
                        >
                    </div>

                    <div class="rounded-lg border border-amber-200 bg-amber-50 p-4">
                        <p class="text-sm text-amber-900">
                            <span class="font-semibold">Password Requirements:</span>
                            Minimum of <strong>8 characters</strong>, including at least
                            <strong>one uppercase letter</strong>,
                            <strong>one lowercase letter</strong>,
                            <strong>one number</strong>, and
                            <strong>one special character</strong>.
                        </p>
                    </div>

                    <button
                        type="submit"
                        class="w-full rounded-lg bg-slate-900 px-4 py-3 font-semibold text-white transition hover:bg-slate-800"
                    >
                        Create Guard Account
                    </button>

                    <p class="text-center text-sm text-slate-500">
                        Already have an account?

                        <a
                            href="{{ route('guard.login') }}"
                            class="font-semibold text-amber-600 hover:text-amber-700"
                        >
                            Login
                        </a>
                    </p>
                </form>
            </div>

        </div>
    </section>
@endsection
