<?php

namespace App\Http\Controllers;

use App\Models\Conversation;
use App\Models\JobApplication;
use App\Models\Message;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ConversationController extends Controller
{
    public function show(Request $request, JobApplication $application)
    {
        $user = auth()->user();

        // Authorize access
        if ($user->hasRole('agency')) {
            if ($application->job->agency_id !== $user->agency->id) {
                abort(403);
            }
        } elseif ($user->hasRole('applicant')) {
            if ($application->guard_id !== $user->id) {
                abort(403);
            }
        } else {
            abort(403);
        }

        $conversation = $application->conversation()->firstOrCreate([
            'job_application_id' => $application->id,
        ]);

        // Ensure participants are added (Guard and Agency Owner)
        $this->ensureParticipants($conversation, $application);

        $messages = $conversation->messages()->with('sender')->oldest()->get();

        // Mark messages as read
        $conversation->messages()
            ->where('sender_id', '!=', $user->id)
            ->whereNull('read_at')
            ->update(['read_at' => now()]);

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json([
                'html' => view('messaging.partials.chat-pane', compact('application', 'conversation', 'messages'))->render(),
                'conversation_id' => $conversation->id,
            ]);
        }

        return view('messaging.show', compact('application', 'conversation', 'messages'));
    }

    public function sendMessage(Request $request, Conversation $conversation)
    {
        $request->validate([
            'message' => 'required|string',
        ]);

        $user = auth()->user();

        // Check if user is participant
        if (!$conversation->participants()->where('user_id', $user->id)->exists()) {
            abort(403);
        }

        $message = $conversation->messages()->create([
            'sender_id' => $user->id,
            'message' => $request->message,
        ]);

        if ($request->wantsJson() || $request->ajax()) {
            $application = $conversation->application;
            $messages = $conversation->messages()->with('sender')->oldest()->get();
            return response()->json([
                'html' => view('messaging.partials.chat-pane', compact('application', 'conversation', 'messages'))->render(),
                'success' => 'Message sent.',
            ]);
        }

        return back()->with('success', 'Message sent.');
    }

    private function ensureParticipants(Conversation $conversation, JobApplication $application)
    {
        $guardId = $application->guard_id;
        $agencyOwnerId = $application->job->agency->owner_id;

        if (!$conversation->participants()->where('user_id', $guardId)->exists()) {
            $conversation->participants()->create(['user_id' => $guardId]);
        }

        if (!$conversation->participants()->where('user_id', $agencyOwnerId)->exists()) {
            $conversation->participants()->create(['user_id' => $agencyOwnerId]);
        }
    }
}
