@extends('pro.layouts.app')

@section('title', 'Messages - SEKYU PRO')

@section('fullWidth')
@endsection

@section('content')

    <div class="grid h-[calc(100vh-8rem)] min-h-[38rem] overflow-hidden rounded-lg border border-slate-200 bg-white shadow-sm lg:grid-cols-[22rem_minmax(0,1fr)]">
        <aside
            x-data="{ query: '' }"
            class="flex min-h-0 flex-col border-b border-slate-200 bg-white lg:border-b-0 lg:border-r"
        >
            <div class="border-b border-slate-200 px-4 py-4">
                <div class="flex items-center justify-between gap-3">
                    <div>
                        <h1 class="text-xl font-bold text-slate-950">
                            Messages
                        </h1>

                        <p class="mt-0.5 text-xs font-medium text-slate-500">
                            Applicant conversations
                        </p>
                    </div>

                    <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-full bg-amber-100 text-amber-700">
                        <x-framework.icon
                            name="chat-bubble-left-right"
                            class="h-5 w-5"
                        />
                    </div>
                </div>

                <div class="relative mt-4">
                    <x-framework.icon
                        name="magnifying-glass"
                        class="pointer-events-none absolute left-3 top-1/2 h-4 w-4 -translate-y-1/2 text-slate-400"
                    />

                    <input
                        x-model.debounce.150ms="query"
                        type="search"
                        placeholder="Search messages"
                        class="h-10 w-full rounded-full border border-slate-200 bg-slate-100 pl-9 pr-4 text-sm font-medium text-slate-700 outline-none transition placeholder:text-slate-400 focus:border-amber-400 focus:bg-white"
                    >
                </div>
            </div>

            <div class="min-h-0 flex-1 overflow-y-auto">
                @forelse($conversations as $conversation)
                    @php
                        $application = $conversation->application;
                        $applicant = $application->applicant;
                        $profile = $applicant->guardProfile;
                        $displayName = $profile?->full_name ?? $applicant->name;
                        $latestMessage = $conversation->latestMessage;
                        $isActive = $activeConversation && $activeConversation->id === $conversation->id;
                        $initials = str($displayName)
                            ->explode(' ')
                            ->filter()
                            ->take(2)
                            ->map(fn ($part) => str($part)->substr(0, 1)->upper())
                            ->implode('');
                    @endphp

                    <a
                        x-show="@js(str($displayName.' '.$application->job->title.' '.($latestMessage?->message ?? ''))->lower()->toString()).includes(query.trim().toLowerCase())"
                        x-cloak
                        href="{{ route('pro.agency.messages.index', ['conversation' => $conversation]) }}"
                        @class([
                            'block border-b border-slate-100 px-3 py-2 transition hover:bg-slate-50',
                            'bg-amber-50' => $isActive,
                        ])
                    >
                        <div class="flex items-center gap-3 rounded-lg px-2 py-2">
                            <div
                                @class([
                                    'flex h-12 w-12 shrink-0 items-center justify-center rounded-full text-sm font-bold',
                                    'bg-amber-500 text-slate-950' => $isActive,
                                    'bg-slate-200 text-slate-700' => ! $isActive,
                                ])
                            >
                                {{ $initials ?: str($displayName)->substr(0, 1)->upper() }}
                            </div>

                            <div class="min-w-0 flex-1">
                                <div class="flex items-center justify-between gap-3">
                                    <div class="truncate text-sm font-semibold text-slate-900">
                                        {{ $displayName }}
                                    </div>

                                    @if($latestMessage)
                                        <div class="shrink-0 text-[11px] font-medium text-slate-400">
                                            {{ $latestMessage->created_at->isToday() ? $latestMessage->created_at->format('g:i A') : $latestMessage->created_at->format('M d') }}
                                        </div>
                                    @endif
                                </div>

                                <div class="mt-0.5 truncate text-xs font-medium text-slate-500">
                                    {{ $application->job->title }}
                                </div>

                                <div class="mt-1 truncate text-sm text-slate-500">
                                    {{ $latestMessage?->message ?? 'No messages yet' }}
                                </div>
                            </div>
                        </div>
                    </a>
                @empty
                    <div class="px-5 py-12 text-center">
                        <x-framework.icon
                            name="chat-bubble-left-right"
                            class="mx-auto h-10 w-10 text-slate-300"
                        />

                        <div class="mt-3 text-sm font-semibold text-slate-700">
                            No conversations yet
                        </div>
                    </div>
                @endforelse
            </div>
        </aside>

        <section class="flex min-h-0 min-w-0 flex-col bg-slate-50">
            @if($activeConversation)
                @php
                    $application = $activeConversation->application;
                    $applicant = $application->applicant;
                    $profile = $applicant->guardProfile;
                    $displayName = $profile?->full_name ?? $applicant->name;
                    $agencyOwnerId = $application->job->agency->owner_id;
                    $initials = str($displayName)
                        ->explode(' ')
                        ->filter()
                        ->take(2)
                        ->map(fn ($part) => str($part)->substr(0, 1)->upper())
                        ->implode('');
                @endphp

                <header class="flex items-center justify-between gap-4 border-b border-slate-200 bg-white px-5 py-3">
                    <div class="flex min-w-0 items-center gap-3">
                        <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-full bg-slate-900 text-sm font-bold text-white">
                            {{ $initials ?: str($displayName)->substr(0, 1)->upper() }}
                        </div>

                        <div class="min-w-0">
                            <h2 class="truncate text-base font-bold text-slate-950">
                                {{ $displayName }}
                            </h2>

                            <div class="truncate text-sm font-medium text-slate-500">
                                {{ $application->job->title }}
                            </div>
                        </div>
                    </div>

                    <a
                        href="{{ route('agency.applications.show', $application) }}"
                        class="hidden h-10 items-center gap-2 rounded-full px-3 text-sm font-semibold text-slate-600 transition hover:bg-slate-100 hover:text-slate-950 sm:inline-flex"
                    >
                        <x-framework.icon
                            name="information-circle"
                            class="h-5 w-5"
                        />

                        <span>Application</span>
                    </a>
                </header>

                <div
                    x-data
                    x-init="$nextTick(() => $el.scrollTop = $el.scrollHeight)"
                    class="min-h-0 flex-1 overflow-y-auto px-4 py-6 sm:px-6"
                >
                    <div class="mx-auto flex max-w-4xl flex-col gap-3">
                        @forelse($messages as $message)
                            @php
                                $isAgencyMessage = (int) $message->sender_id === (int) $agencyOwnerId;
                            @endphp

                            <div @class([
                                'flex',
                                'justify-end' => $isAgencyMessage,
                                'justify-start' => ! $isAgencyMessage,
                            ])>
                                <div @class([
                                    'max-w-[min(34rem,82%)] px-4 py-2.5 shadow-sm',
                                    'rounded-[18px] rounded-br-md bg-blue-600 text-white' => $isAgencyMessage,
                                    'rounded-[18px] rounded-bl-md bg-white text-slate-950 ring-1 ring-slate-200' => ! $isAgencyMessage,
                                ])>
                                    <div @class([
                                        'text-[11px] font-semibold',
                                        'text-blue-100' => $isAgencyMessage,
                                        'text-slate-500' => ! $isAgencyMessage,
                                    ])>
                                        {{ $message->sender->name }}
                                        <span class="font-normal">
                                            {{ $message->created_at->format('M d, g:i A') }}
                                        </span>
                                    </div>

                                    <div class="mt-2 whitespace-pre-line text-sm leading-6">
                                        {{ $message->message }}
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="flex h-80 items-center justify-center text-center">
                                <div>
                                    <div class="mx-auto flex h-16 w-16 items-center justify-center rounded-full bg-slate-200 text-xl font-bold text-slate-600">
                                        {{ $initials ?: str($displayName)->substr(0, 1)->upper() }}
                                    </div>

                                    <x-framework.icon
                                        name="chat-bubble-left-right"
                                        class="mx-auto mt-5 h-10 w-10 text-slate-300"
                                    />

                                    <div class="mt-4 text-sm font-semibold text-slate-700">
                                        No messages in this conversation
                                    </div>
                                </div>
                            </div>
                        @endforelse
                    </div>
                </div>

                <form
                    method="POST"
                    action="{{ route('pro.agency.messages.send', $activeConversation) }}"
                    class="border-t border-slate-200 bg-white px-4 py-3 sm:px-5"
                >
                    @csrf

                    <div class="mx-auto flex max-w-4xl items-end gap-3">
                        <div class="min-w-0 flex-1">
                            <label
                                for="message"
                                class="sr-only"
                            >
                                Message
                            </label>

                            <textarea
                                id="message"
                                name="message"
                                rows="1"
                                required
                                placeholder="Message {{ $displayName }}"
                                class="max-h-36 min-h-11 w-full resize-none rounded-[22px] border border-slate-200 bg-slate-100 px-4 py-2.5 text-sm leading-6 text-slate-900 outline-none transition placeholder:text-slate-400 focus:border-blue-300 focus:bg-white focus:ring-4 focus:ring-blue-100"
                            >{{ old('message') }}</textarea>

                            @error('message')
                                <p class="mt-1.5 text-xs font-medium text-red-600">
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>

                        <button
                            type="submit"
                            class="flex h-11 w-11 shrink-0 items-center justify-center rounded-full bg-blue-600 text-white transition hover:bg-blue-700 focus:outline-none focus:ring-4 focus:ring-blue-100"
                            aria-label="Send message"
                        >
                            <x-framework.icon
                                name="paper-airplane"
                                variant="s"
                                class="h-5 w-5 translate-x-px -translate-y-px"
                            />
                        </button>
                    </div>
                </form>
            @else
                <div class="flex flex-1 items-center justify-center px-6 text-center">
                    <div>
                        <div class="mx-auto flex h-20 w-20 items-center justify-center rounded-full bg-slate-200 text-slate-400">
                            <x-framework.icon
                                name="chat-bubble-left-right"
                                class="h-10 w-10"
                            />
                        </div>

                        <h2 class="mt-4 text-lg font-bold text-slate-900">
                            No conversation selected
                        </h2>
                    </div>
                </div>
            @endif
        </section>
    </div>

@endsection
