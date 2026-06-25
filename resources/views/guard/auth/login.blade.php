@extends('layouts.app')

@section('content')

    <section class="bg-slate-50 py-24">
        <div class="mx-auto grid max-w-7xl gap-12 px-6 lg:grid-cols-2 lg:items-center">

            <div>
            <span class="inline-flex rounded-full bg-amber-100 px-4 py-2 text-sm font-semibold text-amber-700">
                Guard Portal
            </span>

                <h1 class="mt-6 text-5xl font-bold tracking-tight text-slate-900">
                    Welcome back to SEKYU
                </h1>

                <p class="mt-5 text-lg leading-8 text-slate-600">
                    Login to manage your guard profile, documents, trainings,
                    licenses, and job application readiness.
                </p>
            </div>

            <div class="rounded-3xl border border-slate-200 bg-white p-8 shadow-sm">
                <h2 class="text-2xl font-bold text-slate-900">
                    Guard Login
                </h2>

                <form
                    method="POST"
                    action="{{ route('guard.login.store') }}"
                    class="mt-8 space-y-5"
                >
                    @csrf

                    <div>
                        <label class="block text-sm font-medium text-slate-700">
                            Email address
                        </label>

                        <input
                            name="email"
                            type="email"
                            value="{{ old('email') }}"
                            required
                            autofocus
                            class="mt-2 block w-full rounded-lg border-slate-300 px-4 py-3 shadow-sm focus:border-amber-500 focus:ring-amber-500"
                        >

                        @error('email')
                        <p class="mt-2 text-sm text-red-600">
                            {{ $message }}
                        </p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-slate-700">
                            Password
                        </label>

                        <input
                            name="password"
                            type="password"
                            required
                            class="mt-2 block w-full rounded-lg border-slate-300 px-4 py-3 shadow-sm focus:border-amber-500 focus:ring-amber-500"
                        >
                    </div>

                    <div class="flex items-center justify-between">
                        <label class="flex items-center gap-2 text-sm text-slate-600">
                            <input
                                type="checkbox"
                                name="remember"
                                value="1"
                                class="rounded border-slate-300 text-amber-500 focus:ring-amber-500"
                            >

                            Remember me
                        </label>

                        <a
                            href="#"
                            class="text-sm font-medium text-amber-600 transition hover:text-amber-700"
                        >
                            Forgot password?
                        </a>
                    </div>

                    <button
                        type="submit"
                        class="w-full rounded-lg bg-slate-900 px-4 py-3 font-semibold text-white transition hover:bg-slate-800"
                    >
                        Login
                    </button>

                    <div class="text-center text-sm text-slate-500">
                        Don’t have an account yet?

                        <a
                            href="{{ route('guard.register') }}"
                            class="font-semibold text-amber-600 transition hover:text-amber-700"
                        >
                            Sign Up Now!
                        </a>
                    </div>
                </form>
            </div>

        </div>
    </section>

@endsection
