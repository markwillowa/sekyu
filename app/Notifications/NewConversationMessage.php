<?php

namespace App\Notifications;

use App\Models\Message;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class NewConversationMessage extends Notification
{
    use Queueable;

    public function __construct(public Message $message)
    {
        //
    }

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toArray(object $notifiable): array
    {
        $application = $this->message->conversation->application;
        $senderName = $this->message->sender?->name ?? 'Someone';

        return [
            'conversation_id' => $this->message->conversation_id,
            'message_id' => $this->message->id,
            'job_application_id' => $application->id,
            'job_title' => $application->job->title,
            'sender_name' => $senderName,
            'preview' => str($this->message->message)->squish()->limit(120)->toString(),
            'message' => "{$senderName} sent a message about {$application->job->title}.",
        ];
    }
}
