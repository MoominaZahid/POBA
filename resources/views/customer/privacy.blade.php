{{-- FILE: resources/views/customer/privacy.blade.php --}}
@extends('layouts.app')
@section('title', 'Privacy Policy - POBA')
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
    <h1>Privacy Policy</h1>
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
                <strong>Our Commitment:</strong> POBA values your trust and is dedicated to safeguarding the personal information you share with our alumni community. You maintain control over what details other members can see through your personalized Privacy Settings.
            </div>

            <h2>1. Information We Collect</h2>
            <p>When you register as an alumnus via the "Become a Member" application or update your profile, we may collect the following details:</p>
            <ul>
                <li><strong>Identity &amp; Academic Credentials:</strong> Full name, cadet college entry/batch number, class year, CCP number, house, education level, and field of study.</li>
                <li><strong>Professional &amp; Contact Details:</strong> Email address, mobile telephone number, current designation, organization/employer, industry/field of work, current city, and country of residence.</li>
                <li><strong>Verification Documents:</strong> National Identity Card (CNIC) or official alumni identification document uploaded for identity confirmation.</li>
                <li><strong>Profile Media:</strong> Profile photograph and personal/professional achievements you choose to provide.</li>
            </ul>

            <h2>2. Purpose and Use of Collected Data</h2>
            <p>Your information is used strictly to foster, administer, and maintain the POBA community:</p>
            <ul>
                <li>Verifying alumnus identity and college credentials before granting access to protected directory features.</li>
                <li>Maintaining the secure, verified POBA Alumni Directory.</li>
                <li>Sending event notifications, reunion invitations, association news, and official announcements.</li>
                <li>Facilitating authentic professional mentorship, networking, and career opportunities among alumni.</li>
            </ul>

            <h2>3. Member Privacy Controls &amp; Information Sharing</h2>
            <p>POBA provides customizable privacy controls on your registration form and within your alumni profile settings:</p>
            <ul>
                <li><strong>Privacy Chips / Selective Hiding:</strong> You can choose to hide sensitive fields—including your Email Address, Phone Number, Current City, Organization, Designation, and Field of Study—from public or directory view by other members.</li>
                <li><strong>CNIC &amp; Verification Documents:</strong> Your uploaded CNIC and identification documents are confidential, restricted exclusively to authorized administrators for verification, and are never made visible to other alumni or the public.</li>
                <li><strong>No Commercial Sale:</strong> We never sell, rent, or lease alumni personal data to commercial third parties or external marketing agencies.</li>
            </ul>

            <h2>4. Security and Data Protection</h2>
            <p>We maintain strict administrative and technical safeguards to protect your personal data against unauthorized access, alteration, disclosure, or destruction. Access to administrative controls is restricted to authenticated committee officers with role-based permissions.</p>

            <h2>5. Your Rights &amp; Access to Your Information</h2>
            <p>As a registered member, you have the right to:</p>
            <ul>
                <li>Access and review your profile data at any time via your Member Dashboard.</li>
                <li>Update your personal, academic, or professional information as your career progresses.</li>
                <li>Modify your privacy visibility preferences whenever you wish.</li>
                <li>Request permanent deactivation or removal of your alumni profile by contacting our administration team.</li>
            </ul>

            <h2>6. Changes to this Privacy Policy</h2>
            <p>POBA reserves the right to update this policy periodically to reflect operational, legal, or regulatory enhancements. Any updates will be posted on this page with the revised date.</p>

            <h2>7. Contact Us</h2>
            <p>If you have any questions or concerns regarding this Privacy Policy or your data, please contact the POBA administration team:</p>
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
