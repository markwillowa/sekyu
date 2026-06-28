@php

    $navigation = [

        [
            'title' => 'Overview',

            'items' => [

                [
                    'label' => 'Dashboard',
                    'route' => 'pro.agency.dashboard',
                    'icon' => 'home',
                ],

            ],

        ],

        [
            'title' => 'Workforce',

            'items' => [

                [
                    'label' => 'Employees',
                    'route' => 'pro.agency.onboarding.index',
                    'icon' => 'users',
                ],

                [
                    'label' => 'Onboarding',
                    'route' => 'pro.agency.onboarding.create',
                    'icon' => 'user-plus',
                ],

            ],

        ],

        [
            'title' => 'Operations',

            'items' => [

                [
                    'label' => 'Assignments',
                    'route' => '#',
                    'icon' => 'map-pin',
                ],

                [
                    'label' => 'Attendance',
                    'route' => '#',
                    'icon' => 'clock',
                ],

            ],

        ],

        [
            'title' => 'Development',

            'items' => [

                [
                    'label' => 'Training',
                    'route' => '#',
                    'icon' => 'academic-cap',
                ],

            ],

        ],

        [
            'title' => 'Assets',

            'items' => [

                [
                    'label' => 'Equipment',
                    'route' => '#',
                    'icon' => 'briefcase',
                ],

            ],

        ],

        [
            'title' => 'System',

            'items' => [

                [
                    'label' => 'Reports',
                    'route' => '#',
                    'icon' => 'chart-bar',
                ],

                [
                    'label' => 'Settings',
                    'route' => '#',
                    'icon' => 'cog-6-tooth',
                ],

            ],

        ],

    ];

@endphp

<aside
    class="fixed inset-y-0 left-0 z-50 flex w-72 flex-col bg-slate-950 transition-transform duration-300 lg:relative lg:translate-x-0"
    :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full lg:translate-x-0'"
>

    {{-- Mobile Header --}}
    <div class="flex h-16 items-center justify-between border-b border-slate-800 px-6 lg:hidden">

        <div class="font-bold tracking-tight text-white">

            SEKYU PRO

        </div>

        <button
            @click="sidebarOpen = false"
            class="rounded-lg p-2 text-slate-400 transition hover:bg-slate-900 hover:text-white"
        >

            <x-framework.icon
                name="x-mark"
                class="h-6 w-6"
            />

        </button>

    </div>

    {{-- Desktop Logo --}}
    <div class="hidden border-b border-slate-800 px-7 py-7 lg:block">

        <div class="flex items-center gap-4">

            <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-amber-500">

                <span class="text-lg font-black text-slate-950">

                    S

                </span>

            </div>

            <div>

                <div class="text-xl font-black text-white">

                    SEKYU PRO

                </div>

                <div class="text-xs tracking-[0.25em] text-slate-500 uppercase">

                    Workforce Management

                </div>

            </div>

        </div>

    </div>

    {{-- Navigation --}}
    <div class="flex-1 overflow-y-auto py-6">

        @foreach($navigation as $section)

            <div class="mb-8">

                <div class="mb-2 px-7 text-[11px] font-semibold uppercase tracking-[0.20em] text-slate-600">

                    {{ $section['title'] }}

                </div>

                @foreach($section['items'] as $item)

                    @php
                        $active = $item['route'] !== '#'
                            ? request()->routeIs($item['route'])
                            : false;
                    @endphp

                    <a
                        href="{{ $item['route'] === '#' ? '#' : route($item['route']) }}"
                        @click="sidebarOpen = false"
                        @class([
                            'group flex items-center gap-3 border-l-2 px-7 py-3 text-sm transition',
                            'border-amber-400 bg-slate-900 text-white' => $active,
                            'border-transparent text-slate-400 hover:border-slate-700 hover:bg-slate-900 hover:text-white' => ! $active,
                        ])
                    >

                        <x-framework.icon
                            :name="$item['icon']"
                            class="h-5 w-5 shrink-0"
                        />

                        <span>

                            {{ $item['label'] }}

                        </span>

                    </a>

                @endforeach

            </div>

        @endforeach

    </div>

    {{-- Agency Footer --}}
    <div class="border-t border-slate-800 p-6">

        <div class="text-sm font-semibold text-white">

            {{ auth('pro_agency')->user()->agency->name }}

        </div>

        <div class="mt-1 text-xs text-slate-500">

            {{ auth('pro_agency')->user()->role }}

        </div>

    </div>

</aside>
