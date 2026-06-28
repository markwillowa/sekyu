@extends('layouts.app')

@section('content')
<div class="py-12">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <div class="mb-6">
            <nav class="flex" aria-label="Breadcrumb">
                <ol class="flex items-center space-x-4">
                    <li>
                        <div>
                            @php
                                $dashboardRoute = auth()->user()->hasRole('agency') ? route('agency.dashboard') : route('applicant.dashboard');
                                $applicationsRoute = auth()->user()->hasRole('agency') ? route('agency.applications.index') : route('applicant.applications.index');
                                $showRoute = auth()->user()->hasRole('agency') ? route('agency.applications.show', $application) : route('applicant.applications.show', $application);
                            @endphp
                            <a href="{{ $dashboardRoute }}" class="text-gray-400 hover:text-gray-500">
                                <svg class="h-5 w-5 flex-shrink-0" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M9.293 2.293a1 1 0 011.414 0l7 7A1 1 0 0117 11h-1v6a1 1 0 01-1 1h-2a1 1 0 01-1-1v-3a1 1 0 00-1-1H9a1 1 0 00-1 1v3a1 1 0 01-1 1H5a1 1 0 01-1-1v-6H3a1 1 0 01-.707-1.707l7-7z" clip-rule="evenodd" />
                                </svg>
                                <span class="sr-only">Home</span>
                            </a>
                        </div>
                    </li>
                    <li>
                        <div class="flex items-center">
                            <svg class="h-5 w-5 flex-shrink-0 text-gray-300" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M5.555 17.776l8-16 .894.448-8 16-.894-.448z" />
                            </svg>
                            <a href="{{ $applicationsRoute }}" class="ml-4 text-sm font-medium text-gray-500 hover:text-gray-700">Applications</a>
                        </div>
                    </li>
                    <li>
                        <div class="flex items-center">
                            <svg class="h-5 w-5 flex-shrink-0 text-gray-300" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M5.555 17.776l8-16 .894.448-8 16-.894-.448z" />
                            </svg>
                            <a href="{{ $showRoute }}" class="ml-4 text-sm font-medium text-gray-500 hover:text-gray-700">Application #{{ $application->id }}</a>
                        </div>
                    </li>
                    <li>
                        <div class="flex items-center">
                            <svg class="h-5 w-5 flex-shrink-0 text-gray-300" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M5.555 17.776l8-16 .894.448-8 16-.894-.448z" />
                            </svg>
                            <span class="ml-4 text-sm font-medium text-gray-700">Messages</span>
                        </div>
                    </li>
                </ol>
            </nav>
        </div>

        <div class="bg-white shadow overflow-hidden sm:rounded-lg" x-data="messaging({{ $application->id }}, {{ $conversation->id }})">
            <div x-ref="messagesWrapper">
                @include('messaging.partials.chat-pane', ['application' => $application, 'conversation' => $conversation, 'messages' => $messages])
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('alpine:init', () => {
        Alpine.data('messaging', (applicationId, conversationId) => ({
            showMessages: true, // Always true for full page
            loading: false,
            messagesHtml: '', // We'll keep it empty if we want to use the server rendered one initially, but let's just make it consistent
            newMessage: '',
            sending: false,
            conversationId: conversationId,

            async sendMessage() {
                if (!this.newMessage.trim() || this.sending) return;

                this.sending = true;
                try {
                    const response = await fetch(`/conversations/${this.conversationId}/messages`, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'X-Requested-With': 'XMLHttpRequest',
                            'Accept': 'application/json'
                        },
                        body: JSON.stringify({ message: this.newMessage })
                    });
                    const data = await response.json();

                    // Update the HTML content
                    this.$refs.messagesWrapper.innerHTML = data.html;
                    this.newMessage = '';

                    this.$nextTick(() => {
                        this.scrollToBottom();
                    });
                } catch (e) {
                    console.error('Failed to send message', e);
                } finally {
                    this.sending = false;
                }
            },

            scrollToBottom() {
                const container = document.getElementById(`messages-container-${this.conversationId}`);
                if (container) {
                    container.scrollTop = container.scrollHeight;
                }
            }
        }));
    });
</script>
@endpush
@endsection
