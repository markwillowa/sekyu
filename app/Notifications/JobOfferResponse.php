<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class JobOfferResponse extends Notification
{
    use Queueable;

    public $offer;
    public $application;
    public $status;

    /**
     * Create a new notification instance.
     */
    public function __construct($offer)
    {
        $this->offer = $offer;
        $this->application = $offer->application;
        $this->status = $offer->status?->name ?? 'Unknown'; // Accepted or Declined
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
        $statusLabel = strtolower($this->status);

        return (new MailMessage)
            ->subject('Job Offer ' . $this->status . ': ' . $this->application->applicant->name)
            ->greeting('Hello ' . $notifiable->name . '!')
            ->line('The job offer for ' . $this->application->job->title . ' has been ' . $statusLabel . ' by the applicant.')
            ->line('Applicant: ' . $this->application->applicant->name)
            ->action('View Application', route('agency.applications.show', $this->application))
            ->line('Thank you for using our application!');
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
            'applicant_name' => $this->application->applicant->name,
            'job_title' => $this->application->job->title,
            'status' => $this->status,
            'message' => 'Job offer for ' . $this->application->job->title . ' was ' . strtolower($this->status) . ' by ' . $this->application->applicant->name,
        ];
    }
}
