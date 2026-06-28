<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class JobOfferSent extends Notification
{
    use Queueable;

    public $offer;
    public $application;

    /**
     * Create a new notification instance.
     */
    public function __construct($offer)
    {
        $this->offer = $offer;
        $this->application = $offer->application;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail', 'database'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Job Offer: ' . $this->application->job->title)
            ->greeting('Hello ' . $notifiable->name . '!')
            ->line('You have received a new job offer from ' . $this->application->job->agency->name . '.')
            ->line('Salary: ' . number_format($this->offer->salary, 2))
            ->line('Start Date: ' . $this->offer->start_date->format('M d, Y'))
            ->action('View Offer', route('applicant.applications.show', $this->application))
            ->line('Please review the offer and accept or decline it through the portal.');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'job_offer_id' => $this->offer->id,
            'job_application_id' => $this->application->id,
            'agency_name' => $this->application->job->agency->name,
            'job_title' => $this->application->job->title,
            'message' => 'You received a job offer for ' . $this->application->job->title,
        ];
    }
}
