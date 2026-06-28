{{-- FILE: resources/views/partials/theme-css.blade.php --}}
@php
    $theme          = \App\Models\SiteTheme::getAll();
    $headingFont    = $theme['heading_font']    ?? 'Poppins';
    $bodyFont       = $theme['body_font']       ?? 'Roboto';
    $primaryColor   = $theme['primary_color']   ?? '#1a7a7a';
    $secondaryColor = $theme['secondary_color'] ?? '#e87722';
    $headingSize    = $theme['heading_size']    ?? '2rem';
    $subheadingSize = $theme['subheading_size'] ?? '1.5rem';
    $bodySize       = $theme['body_size']       ?? '14px';

    $fonts     = array_unique([$headingFont, $bodyFont]);
    $fontQuery = implode('&family=', array_map(
        fn($f) => str_replace(' ', '+', $f) . ':wght@400;500;600;700;800',
        $fonts
    ));
@endphp

<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family={{ $fontQuery }}&display=swap" rel="stylesheet">

<style>
/* ══════════════════════════════════════════════════════
   POBA THEME OVERRIDES
   Overrides ALL color variables including teal-deep,
   teal-dark, teal-light so every element updates.
   ══════════════════════════════════════════════════════ */

:root {
    /* Override ALL teal variants with primary color */
    --teal:        {{ $primaryColor }} !important;
    --teal-dark:   {{ $primaryColor }}dd !important;
    --teal-deep:   {{ $primaryColor }} !important;
    --teal-light:  {{ $primaryColor }}18 !important;

    /* Override ALL orange variants with secondary color */
    --orange:      {{ $secondaryColor }} !important;
    --orange-dark: {{ $secondaryColor }}dd !important;
}

/* ── Fonts ── */
body, p, li, td, th, input, select, textarea, label {
    font-family: '{{ $bodyFont }}', sans-serif !important;
    font-size: {{ $bodySize }} !important;
}
h1, h2, h3, h4, h5, h6,
.section-title, .section-title-left, .section-title-center {
    font-family: '{{ $headingFont }}', sans-serif !important;
}
h1 { font-size: {{ $headingSize }} !important; }
h2 { font-size: {{ $subheadingSize }} !important; }

/* ── Protect button text ── */
.btn-teal, .btn-teal:hover,
.btn-orange, .btn-orange:hover,
.btn-teal-nav, .btn-teal-nav:hover,
.btn-teal-capsule, .btn-teal-capsule:hover,
.btn-teal-news-view, .btn-teal-news-view:hover,
.btn-teal-alumni-details, .btn-teal-alumni-details:hover,
.btn-approve, .btn-approve:hover,
.sidebar-nav a.active, .sidebar-nav a.active:hover,
.tab-btn.active, .tab-btn.active:hover,
.cms-tab.active, .cms-tab.active:hover {
    color: #ffffff !important;
    text-decoration: none !important;
}
</style>
