<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>

    <meta charset="utf-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1"
    >

    <title>
        @yield('title', 'SEKYU PRO')
    </title>

    @vite([
        'resources/css/app.css',
        'resources/js/app.js',
    ])

</head>

<body
    x-data="{
        sidebarOpen: false,
    }"
    class="h-screen overflow-hidden bg-slate-100 text-slate-900"
>

{{-- Mobile Overlay --}}
<div
    x-show="sidebarOpen"
    x-cloak
    x-transition.opacity
    class="fixed inset-0 z-40 bg-slate-950/60 backdrop-blur-sm lg:hidden"
    @click="sidebarOpen = false"
></div>

<div class="flex h-screen">

    {{-- Sidebar --}}
    @include('pro.partials.sidebar')

    {{-- Workspace --}}
    <div class="flex min-w-0 flex-1 flex-col">

        {{-- Top Navigation --}}
        @include('pro.partials.topbar')

        {{-- Workspace --}}
        <main
            class="flex-1 overflow-y-auto"
        >

            <div
                @class([
                    'mx-auto max-w-7xl px-6 py-8' => ! View::hasSection('fullWidth'),
                    'w-full px-6 py-8' => View::hasSection('fullWidth'),
                ])
            >

                @yield('content')

            </div>

        </main>

    </div>

</div>

</body>

</html>
