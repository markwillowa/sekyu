<header
    class="sticky top-0 z-30 border-b border-slate-200 bg-white/90 backdrop-blur"
>
@php
    $proNotifiable = auth('pro_agency')->user() ?? auth('pro_employee')->user();
    $proUnreadCount = $proNotifiable?->unreadNotifications()->count() ?? 0;
    $proNotifications = $proNotifiable
        ? $proNotifiable->notifications()->latest()->limit(5)->get()
        : collect();
@endphp

    <div class="flex h-16 items-center justify-between px-4 lg:px-8">

        {{-- Left --}}
        <div class="flex items-center gap-3">

            {{-- Mobile Menu --}}
            <button
                type="button"
                @click="sidebarOpen = true"
                class="rounded-lg p-2 transition hover:bg-slate-100 lg:hidden"
            >

                <x-framework.icon
                    name="bars-3"
                    class="h-6 w-6"
                />

            </button>

            {{-- Mobile Brand --}}
            <div class="flex items-center gap-3 lg:hidden">

                <div
                    class="flex h-9 w-9 items-center justify-center rounded-lg bg-amber-500"
                >

                    <span class="font-black text-slate-900">

                        S

                    </span>

                </div>

                <span
                    class="font-bold tracking-tight text-slate-900"
                >

                    SEKYU PRO

                </span>

            </div>

            {{-- Desktop Search --}}
            <div class="relative hidden lg:block">

                <x-framework.icon
                    name="magnifying-glass"
                    class="pointer-events-none absolute left-3 top-1/2 h-5 w-5 -translate-y-1/2 text-slate-400"
                />

                <input
                    type="search"
                    placeholder="Search employees, assignments..."
                    class="w-96 rounded-xl border border-slate-300 bg-slate-50 py-2.5 pl-10 pr-4 text-sm transition focus:border-amber-500 focus:bg-white focus:outline-none"
                >

            </div>

        </div>

        {{-- Right --}}
        <div class="flex items-center gap-2 lg:gap-4">

            {{-- Notifications --}}
            <div
                x-data="{ open: false }"
                class="relative"
            >
                <button
                    type="button"
                    @click="open = ! open"
                    class="relative rounded-xl p-2 transition hover:bg-slate-100"
                >

                    <x-framework.icon
                        name="bell"
                        class="h-6 w-6 text-slate-600"
                    />

                    @if($proUnreadCount > 0)
                        <span
                            class="absolute right-2 top-2 h-2.5 w-2.5 rounded-full bg-red-500 ring-2 ring-white"
                        ></span>
                    @endif

                </button>

                <div
                    x-show="open"
                    x-cloak
                    @click.away="open = false"
                    class="absolute right-0 mt-2 w-80 overflow-hidden rounded-2xl border border-slate-200 bg-white py-2 shadow-2xl"
                >
                    <div class="flex items-center justify-between border-b border-slate-100 px-4 py-2">
                        <span class="text-xs font-black uppercase tracking-widest text-slate-400">
                            Notifications
                        </span>

                        <div class="flex items-center gap-3">
                            @if($proUnreadCount > 0)
                                <form
                                    action="{{ route('pro.notifications.mark-all-read') }}"
                                    method="POST"
                                >
                                    @csrf

                                    <button
                                        type="submit"
                                        class="text-[10px] font-bold uppercase text-blue-600 hover:underline"
                                    >
                                        Mark all read
                                    </button>
                                </form>
                            @endif

                            @if($proNotifications->isNotEmpty())
                                <form
                                    action="{{ route('pro.notifications.clear') }}"
                                    method="POST"
                                >
                                    @csrf
                                    @method('DELETE')

                                    <button
                                        type="submit"
                                        class="text-[10px] font-bold uppercase text-rose-600 hover:underline"
                                    >
                                        Clear
                                    </button>
                                </form>
                            @endif
                        </div>
                    </div>

                    <div class="max-h-96 overflow-y-auto">
                        @forelse($proNotifications as $notification)
                            <div
                                @class([
                                    'border-b border-slate-50 px-4 py-3 transition-colors last:border-0 hover:bg-slate-50',
                                    'bg-amber-50/70' => is_null($notification->read_at),
                                ])
                            >
                                <p class="text-sm font-medium leading-snug text-slate-900">
                                    {{ $notification->data['message'] ?? 'Notification received' }}
                                </p>

                                @if(! empty($notification->data['preview']))
                                    <p class="mt-1 line-clamp-2 text-xs leading-relaxed text-slate-500">
                                        {{ $notification->data['preview'] }}
                                    </p>
                                @endif

                                <p class="mt-1 text-[10px] font-bold uppercase tracking-wider text-slate-400">
                                    {{ $notification->created_at->diffForHumans() }}
                                </p>
                            </div>
                        @empty
                            <div class="px-4 py-8 text-center">
                                <x-framework.icon
                                    name="bell-slash"
                                    class="mx-auto mb-2 h-10 w-10 text-slate-200"
                                />

                                <p class="text-sm text-slate-400">
                                    No notifications yet
                                </p>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>

            {{-- Desktop User --}}
            <div class="hidden lg:block">

                @include('pro.partials.user-menu')

            </div>

            {{-- Mobile User --}}
            <div class="lg:hidden">

                @include('pro.partials.user-menu', [
                    'mobile' => true,
                ])

            </div>

        </div>

    </div>

</header>
