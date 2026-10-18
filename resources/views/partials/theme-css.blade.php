{{-- FILE: resources/views/partials/theme-css.blade.php --}}
@php
    $theme          = \App\Models\SiteTheme::getAll();
    $headingFont    = $theme['heading_font']    ?? 'Poppins';
    // If body_font is not set or matches old default 'Roboto' while heading_font was changed, sync with heading_font
    $bodyFont       = (!empty($theme['body_font']) && $theme['body_font'] !== 'Roboto') ? $theme['body_font'] : $headingFont;
    $navFont        = (!empty($theme['nav_font']) && $theme['nav_font'] !== 'Poppins') ? $theme['nav_font'] : $headingFont;
    $primaryColor   = $theme['primary_color']   ?? '#1a7a7a';
    $secondaryColor = $theme['secondary_color'] ?? '#e87722';
    $headingSize    = $theme['heading_size']    ?? '32px';
    $subheadingSize = $theme['subheading_size'] ?? '24px';
    $bodySize       = $theme['body_size']       ?? '14px';
    $navSize        = $theme['nav_size']        ?? '14px';

    $darken = function($hex, $percent = 20) {
        $hex = ltrim($hex, '#');
        if (strlen($hex) === 3) {
            $hex = $hex[0].$hex[0].$hex[1].$hex[1].$hex[2].$hex[2];
        }
        if (strlen($hex) !== 6) return '#' . $hex;
        $r = max(0, min(255, (int)(hexdec(substr($hex, 0, 2)) * (100 - $percent) / 100)));
        $g = max(0, min(255, (int)(hexdec(substr($hex, 2, 2)) * (100 - $percent) / 100)));
        $b = max(0, min(255, (int)(hexdec(substr($hex, 4, 2)) * (100 - $percent) / 100)));
        return sprintf("#%02x%02x%02x", $r, $g, $b);
    };

    $primaryDark   = $darken($primaryColor, 18);
    $primaryDeep   = $darken($primaryColor, 28);
    $secondaryDark = $darken($secondaryColor, 18);

    $allFonts = array_unique(array_filter([$headingFont, $bodyFont, $navFont]));
    $webSafe  = ['Georgia', 'Verdana', 'Trebuchet MS', 'Arial', 'Times New Roman', 'sans-serif', 'serif'];
    $gFonts   = array_values(array_filter($allFonts, fn($f) => !in_array($f, $webSafe)));
    $fontQuery = !empty($gFonts) ? implode('&family=', array_map(
        fn($f) => str_replace(' ', '+', $f) . ':wght@300;400;500;600;700;800',
        $gFonts
    )) : '';
@endphp

@if(!empty($fontQuery))
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family={{ $fontQuery }}&display=swap" rel="stylesheet">
@endif

<style id="poba-dynamic-theme-css">
/* ══════════════════════════════════════════════════════
   POBA DYNAMIC THEME SYSTEM
   Dynamically overrides CSS variables, fonts, sizes, and
   all component color rules across the entire frontend.
   ══════════════════════════════════════════════════════ */

:root {
    --teal:         {{ $primaryColor }} !important;
    --teal-dark:    {{ $primaryDark }} !important;
    --teal-deep:    {{ $primaryDeep }} !important;
    --teal-light:   {{ $primaryColor }}18 !important;
    --orange:       {{ $secondaryColor }} !important;
    --orange-dark:  {{ $secondaryDark }} !important;
    --heading-font: '{{ $headingFont }}', sans-serif !important;
    --body-font:    '{{ $bodyFont }}', sans-serif !important;
}

/* ── Typography Overrides ── */
body, p, li, td, th, input, select, textarea, label,
.card-text, .card-news-text, .alumni-info-custom,
.alumni-info-custom .position-custom, .alumni-info-custom .desc-custom,
.alumni-info-custom .class-year-custom, .vertical-card p,
.timeline-text, .timeline-desc, .hero-content-custom .desc,
.footer-col a, .footer-bottom, .footer-copyright,
.ec-meta, .ec-focal, .ec-collapse-inner, .vertical-detail-intro,
.working-committee-desc {
    font-family: '{{ $bodyFont }}', sans-serif !important;
}

body, p, input, select, textarea, label {
    font-size: {{ $bodySize }} !important;
}

h1, h2, h3, h4, h5, h6,
.section-title, .section-title-left, .section-title-center,
.page-header h1, .hero-content-custom h1, .card-title,
.card-news-title, .alumni-info-custom h4, .vertical-card h4,
.vertical-detail-heading, .timeline-heading, .login-heading,
.register-heading, .ec-title, .ec-modal-title, .stat-heading-custom,
.stat-heading, .footer-col h5, .working-committee-title {
    font-family: '{{ $headingFont }}', sans-serif !important;
}

h1, .hero-content-custom h1, .page-header h1 {
    font-size: {{ $headingSize }} !important;
}

h2, .section-title, .section-title-left, .section-title-center {
    font-size: {{ $subheadingSize }} !important;
}

.navbar, .navbar-nav a, .top-banner, .btn-teal-nav {
    font-family: '{{ $navFont }}', sans-serif !important;
    font-size: {{ $navSize }} !important;
}

/* ── Primary Color Overrides ── */
.top-banner {
    background: {{ $primaryColor }} !important;
    color: #ffffff !important;
}

.navbar-brand-centered {
    background: {{ $primaryColor }} !important;
}

.navbar-nav .dropdown-menu {
    background: {{ $primaryColor }} !important;
}
.navbar-nav .dropdown-menu a:hover {
    background: {{ $primaryDark }} !important;
}

.navbar-nav > li > a.active,
.navbar-nav > li.dropdown > a.active {
    color: {{ $primaryColor }} !important;
}

.btn-teal-nav,
.btn-teal-nav:hover {
    background: {{ $primaryColor }} !important;
    color: #ffffff !important;
}

.section-title,
.section-title-left,
.section-title-center,
.page-header h1,
.vertical-detail-heading,
.login-heading,
.register-heading,
.card-news-type,
.alumni-info-custom h4,
.vertical-card h4,
.vertical-member-card,
.vertical-card-btn,
.stat-heading,
.stat-heading-custom,
.ec-title,
.ec-modal-title,
.ec-gallery-link,
.ec-badge-open,
.ec-day,
.ec-my {
    color: {{ $primaryColor }} !important;
}

.vertical-card::before,
.alumni-avatar-placeholder {
    background: {{ $primaryColor }} !important;
}

.btn-teal,
.btn-teal-capsule,
.btn-teal-news-view,
.btn-teal-alumni-details,
.btn-login,
.login-btn-submit,
.btn-contact-submit,
.ec-btn-primary,
.ec-load-more-btn,
.tab-btn.active,
.btn-modal-confirm:not(.danger) {
    background: {{ $primaryColor }} !important;
    color: #ffffff !important;
}

.btn-teal:hover,
.btn-teal-capsule:hover,
.btn-teal-news-view:hover,
.btn-teal-alumni-details:hover,
.btn-login:hover,
.login-btn-submit:hover,
.btn-contact-submit:hover,
.ec-btn-primary:hover,
.btn-modal-confirm:not(.danger):hover {
    background: {{ $primaryDark }} !important;
    color: #ffffff !important;
}

.btn-outline-teal,
.btn-outline-teal-view,
.ec-btn-outline {
    color: {{ $primaryColor }} !important;
    border-color: {{ $primaryColor }} !important;
}

.btn-outline-teal:hover,
.btn-outline-teal-view:hover,
.ec-btn-outline:hover {
    background: {{ $primaryColor }} !important;
    color: #ffffff !important;
}

footer {
    background: {{ $primaryColor }} !important;
    color: #ffffff !important;
}

/* ── Secondary Color Overrides ── */
.section-title::after,
.section-title-left::after,
.section-title-center::after,
.page-header .underline,
.login-heading-underline,
.register-heading-underline,
.vertical-detail-underline,
.bam-underline,
.gal-orange-line {
    background: {{ $secondaryColor }} !important;
}

.navbar-nav > li > a.active,
.navbar-nav > li.dropdown > a.active {
    text-decoration-color: {{ $secondaryColor }} !important;
}

.hero-content-custom .tagline,
.hero-content-custom .see-more-toggle,
.see-more-toggle,
.card-type,
.card-date,
.login-subtitle,
.working-committee-title,
footer h5,
.text-orange,
.ec-toggle {
    color: {{ $secondaryColor }} !important;
}

.btn-orange,
.btn-orange-capsule,
.card-tag,
.btn-modal-confirm.danger,
.committee-tab-btn.active {
    background: {{ $secondaryColor }} !important;
    color: #ffffff !important;
}

.btn-orange:hover,
.btn-orange-capsule:hover,
.btn-modal-confirm.danger:hover {
    background: {{ $secondaryDark }} !important;
    color: #ffffff !important;
}

.btn-outline-orange,
.btn-outline-orange-capsule,
.ec-btn-cancel {
    border-color: {{ $secondaryColor }} !important;
    color: {{ $secondaryColor }} !important;
}

.btn-outline-orange:hover,
.btn-outline-orange-capsule:hover,
.ec-btn-cancel:hover {
    background: {{ $secondaryColor }} !important;
    color: #ffffff !important;
}

footer .footer-col a:hover {
    color: {{ $secondaryColor }} !important;
}

/* ── Unified Back Button (Figma Arrow) ── */
.btn-back, .poba-back-btn, .ap-back, .cef-back, .cgf-back, .ai-back, .back-link {
    color: {{ $primaryColor }} !important;
    background: transparent !important;
}
.btn-back:hover, .poba-back-btn:hover, .ap-back:hover, .cef-back:hover, .cgf-back:hover, .ai-back:hover, .back-link:hover {
    color: {{ $primaryDark }} !important;
    background: {{ $primaryColor }}18 !important;
}

/* ── Form focus accents ── */
.form-control:focus, .login-input:focus {
    border-color: {{ $primaryColor }} !important;
}
input[type="checkbox"]:checked, input[type="radio"]:checked {
    accent-color: {{ $primaryColor }} !important;
}
</style>
