<?php

namespace App\Notifications;

use App\Models\Document;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class DocumentWorkflowNotification extends Notification
{
    use Queueable;

    public function __construct(
        public Document $document,
        public string $message
    ) {
    }

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toDatabase(object $notifiable): array
    {
        return [
            'document_id' => $this->document->id,
            'document_title' => $this->document->title,
            'message' => $this->message,
            'status' => $this->document->status,
        ];
    }
}