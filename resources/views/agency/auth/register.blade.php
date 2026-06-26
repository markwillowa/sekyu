@extends('layouts.app')

@section('content')
    <section class="bg-slate-50 py-24">
        <div class="mx-auto grid max-w-7xl gap-12 px-6 lg:grid-cols-2 lg:items-center">

            <div>
                <span class="inline-flex rounded-full bg-amber-100 px-4 py-2 text-sm font-semibold text-amber-700">
                    Agency Registration
                </span>

                <h1 class="mt-6 text-5xl font-bold tracking-tight text-slate-900">
                    Create your SEKYU agency account
                </h1>

                <p class="mt-5 text-lg leading-8 text-slate-600">
                    Register your security agency, create job posts, and manage guard applications from your agency dashboard.
                </p>

                <div class="mt-8 rounded-2xl border border-amber-200 bg-amber-50 p-5">
                    <p class="text-sm leading-6 text-amber-900">
                        <span class="font-semibold">Note:</span>
                        Your agency account will be created as unverified first.
                        Admin verification can be added later before allowing public job posting.
                    </p>
                </div>
            </div>

            <div class="rounded-3xl border border-slate-200 bg-white p-8 shadow-sm">
                <h2 class="text-2xl font-bold text-slate-900">
                    Register as Agency
                </h2>

                <form
                    method="POST"
                    action="{{ route('agency.register.store') }}"
                    class="mt-8 space-y-5"
                >
                    @csrf

                    <div>
                        <label class="block text-sm font-medium text-slate-700">
                            Agency name
                        </label>

                        <input
                            name="agency_name"
                            value="{{ old('agency_name') }}"
                            required
                            autofocus
                            class="mt-2 block w-full rounded-lg border-slate-300 px-4 py-3 shadow-sm focus:border-amber-500 focus:ring-amber-500"
                        >

                        @error('agency_name')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-slate-700">
                            Contact person
                        </label>

                        <input
                            name="contact_person"
                            value="{{ old('contact_person') }}"
                            required
                            class="mt-2 block w-full rounded-lg border-slate-300 px-4 py-3 shadow-sm focus:border-amber-500 focus:ring-amber-500"
                        >

                        @error('contact_person')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
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

                    <div class="grid gap-5 sm:grid-cols-2">
                        <div>
                            <label class="block text-sm font-medium text-slate-700">
                                Phone number
                            </label>

                            <input
                                name="phone"
                                value="{{ old('phone') }}"
                                class="mt-2 block w-full rounded-lg border-slate-300 px-4 py-3 shadow-sm focus:border-amber-500 focus:ring-amber-500"
                            >

                            @error('phone')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-slate-700">
                                License number
                            </label>

                            <input
                                name="license_number"
                                value="{{ old('license_number') }}"
                                class="mt-2 block w-full rounded-lg border-slate-300 px-4 py-3 shadow-sm focus:border-amber-500 focus:ring-amber-500"
                            >

                            @error('license_number')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="grid gap-5 sm:grid-cols-2">
                        <div>
                            <label class="block text-sm font-medium text-slate-700">
                                City
                            </label>

                            <input
                                name="city"
                                value="{{ old('city') }}"
                                class="mt-2 block w-full rounded-lg border-slate-300 px-4 py-3 shadow-sm focus:border-amber-500 focus:ring-amber-500"
                            >

                            @error('city')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-slate-700">
                                Province
                            </label>

                            <input
                                name="province"
                                value="{{ old('province') }}"
                                class="mt-2 block w-full rounded-lg border-slate-300 px-4 py-3 shadow-sm focus:border-amber-500 focus:ring-amber-500"
                            >

                            @error('province')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-slate-700">
                            Password
                        </label>

                        <input
                            name="password"
                            type="password"
                            autocomplete="new-password"
                            required
                            class="mt-2 block w-full rounded-lg border-slate-300 px-4 py-3 shadow-sm focus:border-amber-500 focus:ring-amber-500"
                        >

                        @error('password')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-slate-700">
                            Confirm Password
                        </label>

                        <input
                            name="password_confirmation"
                            type="password"
                            autocomplete="new-password"
                            required
                            class="mt-2 block w-full rounded-lg border-slate-300 px-4 py-3 shadow-sm focus:border-amber-500 focus:ring-amber-500"
                        >
                    </div>

                    <button
                        type="submit"
                        class="w-full rounded-lg bg-slate-900 px-4 py-3 font-semibold text-white transition hover:bg-slate-800"
                    >
                        Create Agency Account
                    </button>

                    <p class="text-center text-sm text-slate-500">
                        Already have an account?

                        <a
                            href="{{ route('agency.login') }}"
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
