<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1"
    >

    <title>{{ (isset($title) && $title) ? $title . ' | Sekyu' : 'Sekyu - Security Job Portal' }}</title>

    @livewireStyles

    @vite([
        'resources/css/app.css',
        'resources/js/app.js',
    ])

    @filamentStyles
</head>

<body
    class="bg-slate-50 text-slate-900"
>

@include('public.navbar')

<div class="mx-auto max-w-7xl px-4 sm:px-6">
    @if(session('success'))
        <div class="mt-6">
            <x-framework.feedback.alert type="success">
                {{ session('success') }}
            </x-framework.feedback.alert>
        </div>
    @endif

    @if(session('error'))
        <div class="mt-6">
            <x-framework.feedback.alert type="danger">
                {{ session('error') }}
            </x-framework.feedback.alert>
        </div>
    @endif
</div>

<main>
    @yield('content')
</main>

<x-auth.login-modal />

@include('public.footer')

@livewireScripts
@filamentScripts

@stack('scripts')

</body>
</html>
