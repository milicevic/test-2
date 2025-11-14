<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ImportCompletedNotification extends Notification
{
    use Queueable;

    public function __construct(
        public string $importType,
        public string $fileName,
        public int $errorCount
    ) {}

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject("Import Completed: {$this->importType}")
            ->line("The import job for type: '{$this->importType}' has completed.")
            ->line("Errors logged: {$this->errorCount}")
            ->line('Thank you for using our system!');
    }
}
