<?php

namespace App\Notifications;

use App\Models\Event;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class EventRegistrationSubmitted extends Notification
{
    use Queueable;

    public function __construct(private Event $event) {}

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $startDateFmt = Carbon::parse($this->event->start_date)->format('d M Y');
        $endDateFmt   = Carbon::parse($this->event->end_date)->format('d M Y');
        $dateStr      = $startDateFmt === $endDateFmt ? $startDateFmt : "{$startDateFmt} – {$endDateFmt}";

        $timeStr = null;
        if ($this->event->start_time) {
            $start = Carbon::parse($this->event->start_time)->format('g:i A');
            $end   = $this->event->end_time ? Carbon::parse($this->event->end_time)->format('g:i A') : null;
            $timeStr = $end ? "{$start} – {$end}" : $start;
        }

        $mail = (new MailMessage)
            ->subject("Event Registration Received — {$this->event->title}")
            ->greeting("Dear {$notifiable->full_name},")
            ->line("Thank you for registering for **{$this->event->title}**.")
            ->line("Your registration request has been submitted successfully and is currently **Pending Confirmation** by the event organizers.")
            ->line("### Event Details:")
            ->line("**Event:** {$this->event->title}")
            ->line("**Date:** {$dateStr}");

        if ($timeStr) {
            $mail->line("**Time:** {$timeStr}");
        }

        $mail->line("**Location:** {$this->event->location}");

        if ($this->event->focal_person_name) {
            $focal = $this->event->focal_person_name;
            if ($this->event->focal_person_number) {
                $focal .= " ({$this->event->focal_person_number})";
            }
            $mail->line("**Focal Person:** {$focal}");
        }

        $mail->action('View Events Portal', route('events.index'))
            ->line('You will be notified once your registration status is updated.')
            ->salutation('Best regards, POBA Events Team');

        return $mail;
    }
}
