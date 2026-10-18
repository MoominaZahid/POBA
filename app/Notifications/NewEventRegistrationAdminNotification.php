<?php

namespace App\Notifications;

use App\Models\AlumniUser;
use App\Models\Event;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NewEventRegistrationAdminNotification extends Notification
{
    use Queueable;

    public function __construct(
        private Event $event,
        private AlumniUser $alumni
    ) {}

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $startDateFmt = Carbon::parse($this->event->start_date)->format('d M Y');

        return (new MailMessage)
            ->replyTo($this->alumni->email, $this->alumni->full_name)
            ->subject("New Event Registration — {$this->event->title}")
            ->greeting("Dear {$notifiable->name},")
            ->line("A new alumni has registered for **{$this->event->title}** and is awaiting review.")
            ->line("### Alumnus Details:")
            ->line("**Name:** {$this->alumni->full_name}")
            ->line("**Entry Batch:** {$this->alumni->entry}")
            ->line("**Email:** {$this->alumni->email}")
            ->line("**Phone Number:** " . ($this->alumni->phone_number ?? 'N/A'))
            ->line("**CCP No:** " . ($this->alumni->ccp_no ?? 'N/A'))
            ->line("### Event Details:")
            ->line("**Event:** {$this->event->title}")
            ->line("**Date:** {$startDateFmt}")
            ->line("**Location:** {$this->event->location}")
            ->action('Manage Event Registrations', url("/admin/events/{$this->event->id}/participants"))
            ->salutation('POBA Portal System');
    }
}
