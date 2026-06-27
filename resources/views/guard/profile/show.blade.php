@extends('layouts.app')

@section('content')

    @include('guard.profile.partials.hero')

    <section class="bg-slate-50 py-10">
        <div class="mx-auto grid max-w-7xl gap-8 px-6 lg:grid-cols-12">

            <aside class="lg:col-span-3">
                @include('guard.profile.partials.sidebar')
            </aside>

            <main x-data class="space-y-8 lg:col-span-9">
                @switch($section)
                    @case('overview')
                        @include('guard.profile.overview.index')
                        @break

                    @case('education')
                        @include('guard.profile.education.index')
                        @break

                    @case('personal')
                        @include('guard.profile.personal.index')
                        @break

                    @case('work')
                        @include('guard.profile.work.index')
                        @break

                    @case('credentials')
                        @include('guard.profile.credentials.index')
                        @break

                    @case('documents')
                        @include('guard.profile.documents.index')
                        @break

                    @case('preferences')
                        @include('guard.profile.preferences.index')
                        @break
                @endswitch
            </main>

        </div>
    </section>

@endsection
