<?php

namespace App\Notifications;

use App\Models\CmsSetting;
use App\Models\ContactInquiry;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ContactInquiryReplyNotification extends Notification
{
    use Queueable;

    public ContactInquiry $inquiry;
    public string $emailSubject;
    public string $replyMessage;
    public string $fromEmail;
    public string $replyToEmail;
    public string $supportPhone;

    public function __construct(ContactInquiry $inquiry, string $emailSubject, string $replyMessage)
    {
        $this->inquiry      = $inquiry;
        $this->emailSubject = $emailSubject;
        $this->replyMessage = $replyMessage;
        $this->fromEmail    = config('mail.from.address') ?: 'noreply@palandrians.org';
        $this->replyToEmail = CmsSetting::get('contact_email') ?: $this->fromEmail;
        $this->supportPhone = CmsSetting::get('contact_number') ?: '+92 21 123 4567';
    }

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $mail = (new MailMessage)
            ->subject($this->emailSubject)
            ->replyTo($this->replyToEmail, config('app.name', 'POBA'))
            ->greeting("Dear {$this->inquiry->first_name},")
            ->line($this->replyMessage)
            ->line('---')
            ->line('**In response to your message:**')
            ->line('"' . $this->inquiry->message . '"')
            ->line("For any further queries, you can reply directly to this email or reach us by phone at {$this->supportPhone}.")
            ->salutation('Best regards, POBA Administration Team');

        if ($this->fromEmail) {
            $mail->from($this->fromEmail, config('mail.from.name', config('app.name', 'POBA')));
        }

        return $mail;
    }
}
