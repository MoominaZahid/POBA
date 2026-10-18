<?php

namespace App\Notifications;

use App\Models\CmsSetting;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class AlumniRejected extends Notification
{
    use Queueable;

    public string $reason;
    public string $supportEmail;
    public string $supportPhone;

    public function __construct(string $reason = '')
    {
        $this->reason       = $reason;
        $this->supportEmail = CmsSetting::get('contact_email') ?: config('mail.from.address', 'info@poba.com');
        $this->supportPhone = CmsSetting::get('contact_number') ?: '+92 21 123 4567';
    }

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $mail = (new MailMessage)
            ->subject('POBA Alumni Portal — Application Update')
            ->greeting("Dear {$notifiable->full_name},")
            ->line('Thank you for your interest in joining the POBA Alumni Portal.')
            ->line('After reviewing your application, we were unable to approve your registration at this time.');

        if (! empty($this->reason)) {
            $mail->line("**Reason for Decision:** {$this->reason}");
        }

        $mail->line('If you would like to provide updated information or clarify your application, please reach out to our team:')
            ->line("**Support Email:** {$this->supportEmail}")
            ->line("**Support Phone:** {$this->supportPhone}")
            ->action('Contact Support', url('/contact'))
            ->salutation('Best regards, POBA Administration Team');

        return $mail;
    }
}