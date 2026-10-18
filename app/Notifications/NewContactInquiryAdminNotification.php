<?php

namespace App\Notifications;

use App\Models\ContactInquiry;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NewContactInquiryAdminNotification extends Notification
{
    use Queueable;

    public ContactInquiry $inquiry;

    public function __construct(ContactInquiry $inquiry)
    {
        $this->inquiry = $inquiry;
    }

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $phone = $this->inquiry->formatted_phone;

        return (new MailMessage)
            ->subject("New Website Inquiry from {$this->inquiry->full_name}")
            ->greeting('Hello Administrator,')
            ->line('A new message has been submitted via the POBA website contact form.')
            ->line("**From:** {$this->inquiry->full_name} ({$this->inquiry->email_address})")
            ->line("**Phone:** {$phone}")
            ->line("**Message:**")
            ->line($this->inquiry->message)
            ->action('View Inquiries in Admin Panel', route('admin.inquiries.index'))
            ->salutation('POBA Notification System');
    }
}
