<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\CmsSetting;
use App\Models\Faq;
use App\Support\PublicCache;
use Illuminate\Http\Request;

class ContactController extends Controller {
    public function index() {
        $settings = PublicCache::remember('cms.settings', fn () => CmsSetting::pluck('value', 'key'));
        $faqs     = PublicCache::remember('contact.faqs', fn () => Faq::orderBy('sort_order')->get());
        return view('customer.contact', compact('settings', 'faqs'));
    }

    // Max national-number digit length per dial code, matching the contact form's dropdown
    // (same lengths as config/countries_phone.php for these countries).
    private const PHONE_EXT_MAX_DIGITS = [
        '+92'  => 10, // Pakistan
        '+1'   => 10, // United States
        '+44'  => 10, // United Kingdom
        '+971' => 9,  // United Arab Emirates
        '+966' => 9,  // Saudi Arabia
        '+61'  => 9,  // Australia
        '+49'  => 10, // Germany
        '+33'  => 9,  // France
        '+86'  => 11, // China
        '+91'  => 10, // India
    ];

    public function send(Request $request) {
        $validated = $request->validate([
            'first_name'    => 'required|string|max:100',
            'last_name'     => 'required|string|max:100',
            'email_address' => 'required|email|max:255',
            'phone_ext'     => 'nullable|string|max:10',
            'phone'         => [
                'nullable',
                'string',
                'regex:/^[0-9]+$/',
                function ($attribute, $value, $fail) use ($request) {
                    $max = self::PHONE_EXT_MAX_DIGITS[$request->phone_ext] ?? 15;
                    if (strlen($value) > $max) {
                        $fail("The phone number must not be longer than {$max} digits for the selected country code.");
                    }
                },
            ],
            'message'       => 'required|string|max:5000',
        ], [
            'phone.regex' => 'The phone number must contain only digits.',
        ]);

        $inquiry = \App\Models\ContactInquiry::create([
            'first_name'    => $validated['first_name'],
            'last_name'     => $validated['last_name'],
            'email_address' => $validated['email_address'],
            'phone_ext'     => $request->input('phone_ext'),
            'phone'         => $request->input('phone'),
            'message'       => $validated['message'],
            'is_read'       => false,
        ]);

        // 1. Notify official POBA contact email
        try {
            $adminEmail = \App\Models\CmsSetting::get('contact_email') ?: config('mail.from.address', 'info@poba.com');
            if ($adminEmail) {
                \Illuminate\Support\Facades\Notification::route('mail', $adminEmail)
                    ->notify(new \App\Notifications\NewContactInquiryAdminNotification($inquiry));
            }
        } catch (\Throwable $e) {
            \Illuminate\Support\Facades\Log::warning('Admin contact inquiry email notification failed: ' . $e->getMessage());
        }

        // 2. Send automated acknowledgement to sender
        try {
            \Illuminate\Support\Facades\Notification::route('mail', $inquiry->email_address)
                ->notify(new \App\Notifications\ContactInquiryConfirmationNotification($inquiry));
        } catch (\Throwable $e) {
            \Illuminate\Support\Facades\Log::warning('Sender contact confirmation email failed: ' . $e->getMessage());
        }

        return back()->with('success', 'Thank you! Your message has been sent successfully. We will get back to you shortly.');
    }
}
