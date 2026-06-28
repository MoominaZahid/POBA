{{-- FILE: resources/views/customer/contact.blade.php --}}
@extends('layouts.app')
@section('title','Contact Us - POBA')
@section('content')

<div class="page-header">
    <h1>{{ $settings['contact_page_title'] ?? 'Contact Us' }}</h1>
    <div class="underline"></div>
</div>

<section class="section-pad" style="padding-top:16px;padding-bottom:60px">
    <div class="container">

        @if(!empty($settings['contact_page_description']))
<div style="text-align:center;max-width:700px;margin:0 auto 24px">
    <p id="contactDesc" style="color:var(--text-muted);font-size:15px;overflow:hidden;display:-webkit-box;-webkit-line-clamp:2;-webkit-box-orient:vertical;">
        {{ $settings['contact_page_description'] }}
    </p>
    <a href="#" id="descToggle" onclick="toggleDesc(event)"
        style="color:var(--orange);font-size:14px;font-weight:600;">see more</a>
</div>
@endif

        <div class="grid-2" style="gap:50px;align-items:flex-start">

            {{-- Contact Form --}}
            <div class="form-box">
                <h3 style="font-size:20px;font-weight:700;text-align:center;margin-bottom:24px">Contact Form</h3>

                @if(session('success'))
                    <div class="alert alert-success">{{ session('success') }}</div>
                @endif
                @if($errors->any())
                    <div class="alert alert-danger">@foreach($errors->all() as $e)<div>{{ $e }}</div>@endforeach</div>
                @endif

                <form method="POST" action="{{ route('contact.send') }}">
                    @csrf
                    <div class="form-group">
                        <label class="form-label">First Name:</label>
                        <input type="text" name="first_name" class="form-control" placeholder="Enter First Name" value="{{ old('first_name') }}" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Last Name:</label>
                        <input type="text" name="last_name" class="form-control" placeholder="Enter Last Name" value="{{ old('last_name') }}" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Email Address:</label>
                        <input type="email" name="email_address" class="form-control" placeholder="name@email.com" value="{{ old('email_address') }}" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Phone Number:</label>
                        <div class="phone-input-wrapper">
                            <select name="phone_ext" class="phone-ext-select">
                                <option value="+92">🇵🇰 +92</option>
                                <option value="+1">🇺🇸 +1</option>
                                <option value="+44">🇬🇧 +44</option>
                                <option value="+971">🇦🇪 +971</option>
                                <option value="+966">🇸🇦 +966</option>
                                <option value="+61">🇦🇺 +61</option>
                                <option value="+49">🇩🇪 +49</option>
                                <option value="+33">🇫🇷 +33</option>
                                <option value="+86">🇨🇳 +86</option>
                                <option value="+91">🇮🇳 +91</option>
                            </select>
                            <div class="phone-divider"></div>
                            <input type="tel" name="phone" class="phone-number-input" placeholder="321 1234567" value="{{ old('phone') }}">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Message:</label>
                        <textarea name="message" class="form-control" rows="5" placeholder="Enter Message Here" required>{{ old('message') }}</textarea>
                    </div>
                    <button type="submit" class="btn-contact-submit">Submit</button>
                </form>
            </div>

            {{-- Contact Info --}}
            <div>
                <div class="contact-info-box">
                    <h3 style="font-size:22px;font-weight:700;margin-bottom:20px">Let's talk with us</h3>
                    <p style="color:var(--text-muted);font-size:14px;margin-bottom:24px">Questions, comments, or suggestions? Simply fill in the form and we'll be in touch shortly.</p>

                    <div class="info-item">
                        <span class="info-icon">✉️</span>
                        <span style="font-size:14px">{{ $settings['contact_email'] ?? 'info@poba.com' }}</span>
                    </div>
                    <div class="info-item">
                        <span class="info-icon" style="color:var(--orange)">📍</span>
                        <a href="{{ $settings['google_map_link'] ?? '#' }}" style="font-size:14px;color:var(--orange)">{{ $settings['location'] ?? 'Cadet College Palandri' }}</a>
                    </div>
                    <div class="info-item">
                        <span class="info-icon">📞</span>
                        <span style="font-size:14px">{{ $settings['contact_number'] ?? '+92 21 123 4567' }}</span>
                    </div>

                    {{-- Social icons - transparent bg, dark border --}}
                    <div style="display:flex;gap:10px;margin:20px 0">
                        @if(!empty($settings['social_twitter']))
                            <a href="{{ $settings['social_twitter'] }}" target="_blank" class="social-icon-circle">
                                <svg width="15" height="15" viewBox="0 0 24 24" fill="currentColor"><path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-4.714-6.231-5.401 6.231H2.744l7.73-8.835L1.254 2.25H8.08l4.253 5.622zm-1.161 17.52h1.833L7.084 4.126H5.117z"/></svg>
                            </a>
                        @endif
                        @if(!empty($settings['social_linkedin']))
                            <a href="{{ $settings['social_linkedin'] }}" target="_blank" class="social-icon-circle">
                                <svg width="15" height="15" viewBox="0 0 24 24" fill="currentColor"><path d="M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433a2.062 2.062 0 0 1-2.063-2.065 2.064 2.064 0 1 1 2.063 2.065zm1.782 13.019H3.555V9h3.564v11.452zM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451C23.2 24 24 23.227 24 22.271V1.729C24 .774 23.2 0 22.222 0h.003z"/></svg>
                            </a>
                        @endif
                        @if(!empty($settings['social_facebook']))
                            <a href="{{ $settings['social_facebook'] }}" target="_blank" class="social-icon-circle">
                                <svg width="15" height="15" viewBox="0 0 24 24" fill="currentColor"><path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/></svg>
                            </a>
                        @endif
                        @if(!empty($settings['social_instagram']))
                            <a href="{{ $settings['social_instagram'] }}" target="_blank" class="social-icon-circle">
                                <svg width="15" height="15" viewBox="0 0 24 24" fill="currentColor"><path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zM12 0C8.741 0 8.333.014 7.053.072 2.695.272.273 2.69.073 7.052.014 8.333 0 8.741 0 12c0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98C8.333 23.986 8.741 24 12 24c3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98C15.668.014 15.259 0 12 0zm0 5.838a6.162 6.162 0 1 0 0 12.324 6.162 6.162 0 0 0 0-12.324zM12 16a4 4 0 1 1 0-8 4 4 0 0 1 0 8zm6.406-11.845a1.44 1.44 0 1 0 0 2.881 1.44 1.44 0 0 0 0-2.881z"/></svg>
                            </a>
                        @endif
                        @if(!empty($settings['social_tiktok']))
                            <a href="{{ $settings['social_tiktok'] }}" target="_blank" class="social-icon-circle">
                                <svg width="15" height="15" viewBox="0 0 24 24" fill="currentColor"><path d="M19.59 6.69a4.83 4.83 0 0 1-3.77-4.25V2h-3.45v13.67a2.89 2.89 0 0 1-2.88 2.5 2.89 2.89 0 0 1-2.89-2.89 2.89 2.89 0 0 1 2.89-2.89c.28 0 .54.04.79.1V9.01a6.33 6.33 0 0 0-.79-.05 6.34 6.34 0 0 0-6.34 6.34 6.34 6.34 0 0 0 6.34 6.34 6.34 6.34 0 0 0 6.33-6.34V8.69a8.18 8.18 0 0 0 4.78 1.52V6.75a4.85 4.85 0 0 1-1.01-.06z"/></svg>
                            </a>
                        @endif
                        @if(!empty($settings['social_youtube']))
                            <a href="{{ $settings['social_youtube'] }}" target="_blank" class="social-icon-circle">
                                <svg width="15" height="15" viewBox="0 0 24 24" fill="currentColor"><path d="M23.495 6.205a3.007 3.007 0 0 0-2.088-2.088c-1.87-.501-9.396-.501-9.396-.501s-7.507-.01-9.396.501A3.007 3.007 0 0 0 .527 6.205a31.247 31.247 0 0 0-.522 5.805 31.247 31.247 0 0 0 .522 5.783 3.007 3.007 0 0 0 2.088 2.088c1.868.502 9.396.502 9.396.502s7.506 0 9.396-.502a3.007 3.007 0 0 0 2.088-2.088 31.247 31.247 0 0 0 .5-5.783 31.247 31.247 0 0 0-.5-5.805zM9.609 15.601V8.408l6.264 3.602z"/></svg>
                            </a>
                        @endif
                    </div>
                </div>

                {{-- Donate Section --}}
                <div class="donate-section">
                    <h4>Donate Here</h4>

                    <div style="display:flex;align-items:flex-start;gap:20px">
                        {{-- Bank Details left --}}
                        <div class="bank-details" style="flex:1">
                            <p><strong>Bank Title</strong><br><span>{{ $settings['bank_title'] ?? 'Bank of AJK' }}</span></p>
                            <p><strong>Account Title</strong><br><span>{{ $settings['account_title'] ?? 'Palandarians Old Boys Association' }}</span></p>
                            <p><strong>Account Number</strong><br><span>{{ $settings['account_number'] ?? '00001234657980' }}</span></p>
                            <p><strong>Branch Number</strong><br><span>{{ $settings['branch_number'] ?? '063' }}</span></p>
                        </div>

                        {{-- QR Code right --}}
                        @if(!empty($settings['qr_code']))
                        <div style="flex-shrink:0;text-align:center">
                            <p style="font-size:13px;color:var(--orange);margin-bottom:8px">Scan to Donate</p>
                            <img src="{{ asset('storage/'.$settings['qr_code']) }}" alt="QR Code" style="width:110px;border-radius:8px">
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        {{-- FAQs --}}
        @if($faqs->count())
        <div class="faq-section">
            <h2 style="font-size:1.5rem;font-weight:700;color:var(--teal);margin-bottom:10px">Frequently Asked Questions</h2>
            @foreach($faqs as $faq)
            <div class="faq-item" onclick="toggleFaq(this)">
                <div class="faq-question">
                    {{ $faq->question }}
                    <span style="font-size:20px;color:var(--text-muted)">∨</span>
                </div>
                <div class="faq-answer">{!! $faq->answer !!}</div>
            </div>
            @endforeach
        </div>
        @endif
    </div>
</section>

@push('scripts')
<script>
function toggleFaq(el) {
    el.classList.toggle('open');
}
function toggleDesc(e) {
    e.preventDefault();
    const p   = document.getElementById('contactDesc');
    const btn = document.getElementById('descToggle');
    if (p.style.webkitLineClamp === '2' || p.style.webkitLineClamp === '') {
        p.style.webkitLineClamp = 'unset';
        p.style.display = 'block';
        btn.textContent = 'see less';
    } else {
        p.style.webkitLineClamp = '2';
        p.style.display = '-webkit-box';
        btn.textContent = 'see more';
    }
}
</script>

@endpush
@endsection
