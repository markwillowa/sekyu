@props(['application', 'conversation', 'messages'])

<div class="flex flex-col h-full bg-white">
    <div class="px-4 py-5 border-b border-gray-200 sm:px-6 flex justify-between items-center bg-white sticky top-0 z-10">
        <div>
            <h3 class="text-lg leading-6 font-medium text-gray-900">
                Conversation for {{ $application->job->title }}
            </h3>
            <p class="mt-1 max-w-2xl text-sm text-gray-500">
                @if(auth()->user()->hasRole('agency'))
                    Applicant: {{ $application->applicant->name }}
                @else
                    Agency: {{ $application->job->agency->name }}
                @endif
            </p>
        </div>
        <button @click="showMessages = false" class="text-gray-400 hover:text-gray-500">
            <x-framework.icon name="x-mark" class="h-6 w-6" />
        </button>
    </div>

    <div class="flex-1 overflow-y-auto p-4 space-y-4" id="messages-container-{{ $conversation->id }}">
        @forelse($messages as $message)
            <div class="flex {{ $message->sender_id === auth()->id() ? 'justify-end' : 'justify-start' }}">
                <div class="max-w-xs lg:max-w-md px-4 py-2 rounded-lg {{ $message->sender_id === auth()->id() ? 'bg-indigo-600 text-white' : 'bg-gray-100 text-gray-900' }}">
                    <div class="text-xs {{ $message->sender_id === auth()->id() ? 'text-indigo-100' : 'text-gray-500' }} mb-1 font-semibold">
                        {{ $message->sender->name }} • {{ $message->created_at->format('M d, g:i a') }}
                    </div>
                    <div class="text-sm">
                        {!! nl2br(e($message->message)) !!}
                    </div>
                    @if($message->sender_id === auth()->id() && $message->read_at)
                        <div class="text-[10px] text-indigo-200 mt-1 text-right">
                            Read
                        </div>
                    @endif
                </div>
            </div>
        @empty
            <div class="text-center py-12">
                <p class="text-gray-500">No messages yet. Start the conversation!</p>
            </div>
        @endforelse
    </div>

    <div class="p-4 border-t border-gray-200 bg-gray-50">
        <form @submit.prevent="sendMessage" id="message-form-{{ $conversation->id }}">
            <div class="flex space-x-3">
                <div class="flex-1">
                    <textarea
                        name="message"
                        rows="2"
                        x-model="newMessage"
                        @keydown.enter.prevent="if(!$event.shiftKey) sendMessage()"
                        class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md"
                        placeholder="Type your message..."
                        required
                    ></textarea>
                </div>
                <div class="flex-shrink-0 flex items-end">
                    <button
                        type="submit"
                        :disabled="sending"
                        class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 disabled:opacity-50"
                    >
                        <span x-show="!sending">Send</span>
                        <span x-show="sending">...</span>
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
    (function() {
        const container = document.getElementById('messages-container-{{ $conversation->id }}');
        if (container) {
            container.scrollTop = container.scrollHeight;
        }
    })();
</script>
