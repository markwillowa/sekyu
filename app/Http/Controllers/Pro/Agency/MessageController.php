<?php

namespace App\Http\Controllers\Pro\Agency;

use App\Http\Controllers\Controller;
use App\Models\Conversation;
use App\Models\Message;
use App\Notifications\NewConversationMessage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Notification;

class MessageController extends Controller
{
    public function index(?Conversation $conversation = null)
    {
        $agencyUser = auth('pro_agency')->user();
        $agency = $agencyUser->agency;

        $conversations = Conversation::query()
            ->with([
                'application.applicant.guardProfile',
                'application.job.agency',
                'latestMessage.sender',
            ])
            ->withMax('messages', 'created_at')
            ->whereHas('application.job', fn ($query) => $query->where('agency_id', $agency->id))
            ->orderByDesc('messages_max_created_at')
            ->orderByDesc('updated_at')
            ->get();

        if ($conversation) {
            $conversation->load([
                'application.applicant.guardProfile',
                'application.job.agency',
            ]);

            $this->authorizeAgencyConversation($conversation, $agency->id);
        } else {
            $conversation = $conversations->first();
        }

        if ($conversation) {
            $this->ensureParticipants($conversation);

            $conversation->messages()
                ->where('sender_id', '!=', $agency->owner_id)
                ->whereNull('read_at')
                ->update(['read_at' => now()]);
        }

        $messages = $conversation
            ? $conversation->messages()->with('sender')->oldest()->get()
            : collect();

        return view('pro.agency.messages.index', [
            'conversations' => $conversations,
            'activeConversation' => $conversation,
            'messages' => $messages,
        ]);
    }

    public function send(Request $request, Conversation $conversation)
    {
        $data = $request->validate([
            'message' => ['required', 'string', 'max:5000'],
        ]);

        $agencyUser = auth('pro_agency')->user();
        $agency = $agencyUser->agency;

        $conversation->load([
            'application.job.agency',
            'application.applicant',
        ]);

        $this->authorizeAgencyConversation($conversation, $agency->id);
        $this->ensureParticipants($conversation);

        $message = $conversation->messages()->create([
            'sender_id' => $agency->owner_id,
            'message' => $data['message'],
        ]);

        $conversation->touch();
        $this->sendMessageNotifications($message, $conversation, $agency->owner_id, $agencyUser->id);

        return redirect()
            ->route('pro.agency.messages.index', ['conversation' => $conversation])
            ->with('success', 'Message sent.');
    }

    private function authorizeAgencyConversation(Conversation $conversation, int $agencyId): void
    {
        abort_unless(
            (int) $conversation->application->job->agency_id === $agencyId,
            403
        );
    }

    private function ensureParticipants(Conversation $conversation): void
    {
        $conversation->participants()->firstOrCreate([
            'user_id' => $conversation->application->guard_id,
        ]);

        $conversation->participants()->firstOrCreate([
            'user_id' => $conversation->application->job->agency->owner_id,
        ]);
    }

    private function sendMessageNotifications(
        Message $message,
        Conversation $conversation,
        int $senderId,
        int $proSenderId
    ): void {
        $message->loadMissing('sender', 'conversation.application.job.agency.proUsers');

        $webRecipients = $conversation->participants()
            ->with('user')
            ->where('user_id', '!=', $senderId)
            ->get()
            ->pluck('user')
            ->filter();

        Notification::send($webRecipients, new NewConversationMessage($message));

        $proRecipients = $conversation
            ->application
            ->job
            ->agency
            ->proUsers()
            ->whereKeyNot($proSenderId)
            ->get();

        Notification::send($proRecipients, new NewConversationMessage($message));
    }
}
