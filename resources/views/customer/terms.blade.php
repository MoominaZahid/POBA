{{-- FILE: resources/views/customer/terms.blade.php --}}
@extends('layouts.app')
@section('title', 'Terms & Conditions - POBA')
@section('content')

<style>
.legal-card {
    background: #fff;
    border-radius: 14px;
    box-shadow: 0 4px 24px rgba(0,0,0,0.06);
    padding: 44px 50px;
    max-width: 920px;
    margin: 0 auto;
    color: #334155;
    line-height: 1.8;
}
.legal-card h2 {
    color: var(--teal-deep, #0f766e);
    font-size: 20px;
    font-weight: 700;
    margin-top: 32px;
    margin-bottom: 12px;
    border-bottom: 2px solid #f0fdfa;
    padding-bottom: 8px;
}
.legal-card h2:first-of-type { margin-top: 0; }
.legal-card p, .legal-card ul {
    font-size: 14.5px;
    margin-bottom: 16px;
}
.legal-card ul {
    padding-left: 24px;
}
.legal-card li {
    margin-bottom: 8px;
}
.legal-meta {
    font-size: 13px;
    color: #64748b;
    margin-bottom: 28px;
    border-bottom: 1px solid #e2e8f0;
    padding-bottom: 16px;
    display: flex;
    justify-content: space-between;
    flex-wrap: wrap;
    gap: 8px;
}
.legal-highlight {
    background: #f0fdfa;
    border-left: 4px solid #0d9488;
    padding: 14px 18px;
    border-radius: 0 8px 8px 0;
    margin: 20px 0;
    font-size: 14px;
    color: #0f766e;
}
@media(max-width: 640px) {
    .legal-card { padding: 28px 20px; }
}
</style>

<div class="page-header">
    <h1>Terms &amp; Conditions</h1>
    <div class="underline"></div>
</div>

<section class="section-pad" style="background:#f8fafc;padding:50px 16px;">
    <div class="container">
        <div style="max-width:920px;margin:0 auto 20px auto">
            <a href="{{ url()->previous() }}" class="btn-back" title="Back" aria-label="Back">
                <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                    <line x1="20" y1="12" x2="4" y2="12"></line>
                    <polyline points="10 18 4 12 10 6"></polyline>
                </svg>
                <span>Back</span>
            </a>
        </div>
        <div class="legal-card">
            <div class="legal-meta">
                <span><strong>Pakistan Ocean &amp; Bay Alumni (POBA)</strong></span>
                <span>Last Updated: October 2026</span>
            </div>

            <div class="legal-highlight">
                <strong>Welcome to POBA:</strong> By submitting an application through "Become a Member" or utilizing the POBA website and alumni portal, you agree to comply with and be bound by the following terms and conditions.
            </div>

            <h2>1. Membership Eligibility &amp; Verification</h2>
            <p>Membership in POBA is reserved exclusively for bona fide alumni:</p>
            <ul>
                <li>Applicants must have attended or graduated from the recognized cadet college institution.</li>
                <li>All registration information—including full name, entry/batch number, class graduation year, CCP cadet number, and cadet house—must be accurate and verifiable.</li>
                <li>Applications undergo administrative review and verification before access to the member portal and directory is approved. Submission of false credentials will result in immediate rejection.</li>
            </ul>

            <h2>2. User Account and Security</h2>
            <p>Upon registration approval:</p>
            <ul>
                <li>You are responsible for maintaining the confidentiality of your account login credentials.</li>
                <li>You agree not to share, transfer, or allow third parties to use your personal alumni account.</li>
                <li>You agree to promptly notify the POBA administration team if you discover any unauthorized use or security breach of your account.</li>
            </ul>

            <h2>3. Community Code of Conduct</h2>
            <p>POBA is dedicated to preserving a respectful, fraternal, and supportive professional network. Members agree to:</p>
            <ul>
                <li>Interact with fellow alumni with mutual respect, dignity, and professional courtesy.</li>
                <li>Refrain from posting unlawful, defamatory, abusive, offensive, discriminatory, or disruptive content.</li>
                <li>Not engage in unsolicited bulk commercial marketing, spam, or harvesting member contact details without consent.</li>
                <li>Respect the privacy settings chosen by other alumni members.</li>
            </ul>

            <h2>4. Association Events and Programs</h2>
            <p>Regarding events organized by POBA:</p>
            <ul>
                <li>Event registration is subject to venue capacity and organizer confirmation.</li>
                <li>Members are requested to register conscientiously and notify organizers or cancel through the portal if they are unable to attend.</li>
                <li>Any event-specific participation rules, dress codes, or guest guidelines issued by the event committee must be respected.</li>
            </ul>

            <h2>5. Account Suspension, Deactivation, and Termination</h2>
            <p>POBA administrators reserve the right to suspend, deactivate, or terminate membership privileges if a member:</p>
            <ul>
                <li>Violates these Terms &amp; Conditions or the alumni code of conduct.</li>
                <li>Provides false identity or falsified institutional credentials.</li>
                <li>Engages in unauthorized behavior that impairs the integrity or security of the association or its members.</li>
            </ul>

            <h2>6. Intellectual Property &amp; Association Branding</h2>
            <p>The POBA name, crest, insignia, logos, website layout, and curated content are the exclusive intellectual property of the Pakistan Ocean &amp; Bay Alumni association and may not be reproduced without written authorization.</p>

            <h2>7. Contact Information</h2>
            <p>For questions or clarifications regarding these Terms &amp; Conditions, please reach out to the POBA administrative office:</p>
            <ul>
                <li><strong>Email:</strong> <a href="mailto:{{ $settings['contact_email'] ?? 'info@poba.edu.pk' }}" style="color:#0d9488;">{{ $settings['contact_email'] ?? 'info@poba.edu.pk' }}</a></li>
                <li><strong>Phone:</strong> {{ $settings['contact_number'] ?? '+92 21 123 4567' }}</li>
            </ul>

            <div style="margin-top:36px;text-align:center;">
                <a href="{{ route('member.index') }}" class="btn-teal-nav" style="padding:10px 24px;text-decoration:none;display:inline-block;border-radius:24px;">Return to Become a Member</a>
            </div>
        </div>
    </div>
</section>
@endsection
