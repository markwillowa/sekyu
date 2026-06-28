<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>{{ (isset($title) && $title) ? $title . ' | SEKYU PRO' : 'SEKYU PRO - Workforce Management' }}</title>

    @livewireStyles
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @filamentStyles
</head>

<body class="bg-slate-50 text-slate-900 font-sans antialiased">

<div class="flex flex-col min-h-screen">
    @auth('pro_agency')
        @include('pro-2.layouts.nav-agency')
    @elseauth('pro_employee')
        @include('pro-2.layouts.nav-employee')
    @endauth

    <div class="mx-auto w-full max-w-7xl px-4 sm:px-6">
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

    <main class="flex-grow">
        @yield('content')
    </main>

    <footer class="bg-slate-900 text-slate-400 py-6 border-t border-slate-800">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 flex justify-between items-center">
            <div class="flex items-center space-x-2">
                <div class="h-6 w-6 rounded bg-amber-400 text-slate-900 flex items-center justify-center font-bold text-xs">
                    S
                </div>
                <span class="font-bold text-white tracking-tight">SEKYU <span class="text-amber-400">PRO</span></span>
            </div>
            <div class="text-xs">
                &copy; {{ date('Y') }} Sekyu Workforce Management. All rights reserved.
            </div>
        </div>
    </footer>
</div>

@livewireScripts
@filamentScripts
@stack('scripts')
</body>
</html>
