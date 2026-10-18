<?php

namespace App\Notifications;

use App\Models\CmsSetting;
use App\Models\ContactInquiry;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ContactInquiryConfirmationNotification extends Notification
{
    use Queueable;

    public ContactInquiry $inquiry;
    public string $supportPhone;

    public function __construct(ContactInquiry $inquiry)
    {
        $this->inquiry      = $inquiry;
        $this->supportPhone = CmsSetting::get('contact_number') ?: '+92 21 123 4567';
    }

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Thank you for contacting POBA — We received your message')
            ->greeting("Dear {$this->inquiry->first_name},")
            ->line('Thank you for reaching out to the Pakistan Ocean & Bay Alumni (POBA).')
            ->line('We have received your message and our team will get back to you shortly.')
            ->line('**Summary of your message:**')
            ->line('"' . $this->inquiry->message . '"')
            ->line("If you need immediate assistance, please feel free to reach us by phone at {$this->supportPhone}.")
            ->salutation('Warm regards, POBA Administration Team');
    }
}
