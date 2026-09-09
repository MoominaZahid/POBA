{{-- FILE: resources/views/admin/cms/footer.blade.php --}}
@extends('layouts.admin')
@section('title','Footer Settings - Admin')
@section('page-title','Footer Settings')
@section('content')

<div class="admin-form-page">
    <h2>Footer Settings</h2>
    <form method="POST" action="{{ route('admin.cms.footer.save') }}" enctype="multipart/form-data">
        @csrf

        {{-- Logo --}}
        <div style="background:#fff;border-radius:var(--radius);padding:28px;margin-bottom:24px;box-shadow:var(--shadow)">
            <div class="cms-section-title">Logo</div>
            <div class="admin-form-group">
                <label class="admin-form-label">Footer Logo:</label>
                <div class="footer-logo-upload" onclick="this.querySelector('input').click()">
                    @if (!empty($settings['footer_logo']))
                        <img src="{{ asset('storage/' . $settings['footer_logo']) }}" class="footer-logo-upload-preview" id="footerLogoPreview">
                    @else
                        <img src="{{ asset('images/footerLogo.png') }}" class="footer-logo-upload-preview" id="footerLogoPreview">
                    @endif
                    <input type="file" name="footer_logo" accept="image/*" style="display:none" onchange="previewFooterLogo(this)">
                </div>
                <p style="font-size:12px;color:var(--text-muted);margin-top:6px">Click the box to upload a new logo</p>
            </div>
        </div>

        {{-- Quick Links --}}
        <div style="background:#fff;border-radius:var(--radius);padding:28px;margin-bottom:24px;box-shadow:var(--shadow)">
            <div class="cms-section-title">Quick Links Column</div>
            <div class="admin-form-group">
                <label class="admin-form-label">Column Title:</label>
                <input type="text" name="quick_links_title" class="admin-input"
                    value="{{ $settings['footer_quick_links_title'] ?? 'Quick Links' }}">
            </div>

            <div id="quickLinksRows">
                @php
                    $quickLinks = json_decode($settings['footer_quick_links'] ?? '[]', true);
                    if (empty($quickLinks)) {
                        $quickLinks = [
                            ['label' => 'About Us', 'url' => '/about'],
                            ['label' => 'News', 'url' => '/updates'],
                            ['label' => 'Events', 'url' => '/events'],
                            ['label' => 'Star Alumni', 'url' => '/star-alumni'],
                        ];
                    }
                @endphp
                @foreach ($quickLinks as $i => $link)
                    <div class="admin-form-row link-row" style="margin-bottom:14px">
                        <div>
                            <div class="stat-row-header">
                                <label class="admin-form-label" style="margin-bottom:0">Label:</label>
                                <button type="button" onclick="this.closest('.link-row').remove()" class="remove-stat-btn">
                                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <polyline points="3 6 5 6 21 6"/><path d="M19 6l-1 14a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2L5 6"/>
                                        <path d="M10 11v6M14 11v6M9 6V4a1 1 0 0 1 1-1h4a1 1 0 0 1 1 1v2"/>
                                    </svg>
                                </button>
                            </div>
                            <input type="text" name="quick_link_labels[]" class="admin-input" value="{{ $link['label'] ?? '' }}" placeholder="About Us">
                        </div>
                        <div>
                            <label class="admin-form-label">URL:</label>
                            <input type="text" name="quick_link_urls[]" class="admin-input" value="{{ $link['url'] ?? '' }}" placeholder="/about">
                        </div>
                    </div>
                @endforeach
            </div>
            <a href="#" onclick="addQuickLinkRow(); return false;" style="color:var(--orange);font-weight:600;font-size:13px;display:inline-flex;align-items:center;gap:6px">
                <span style="background:var(--orange);color:#fff;width:18px;height:18px;border-radius:50%;display:flex;align-items:center;justify-content:center;font-size:12px">+</span>
                Add link
            </a>
        </div>

        {{-- Alumni Links --}}
        <div style="background:#fff;border-radius:var(--radius);padding:28px;margin-bottom:24px;box-shadow:var(--shadow)">
            <div class="cms-section-title">Alumni Column</div>
            <div class="admin-form-group">
                <label class="admin-form-label">Column Title:</label>
                <input type="text" name="alumni_title" class="admin-input"
                    value="{{ $settings['footer_alumni_title'] ?? 'Alumni' }}">
            </div>

            <div id="alumniLinksRows">
                @php
                    $alumniLinks = json_decode($settings['footer_alumni_links'] ?? '[]', true);
                    if (empty($alumniLinks)) {
                        $alumniLinks = [
                            ['label' => 'Alumni Directory', 'url' => '/alumni'],
                            ['label' => 'Achievements', 'url' => '/coming-soon?feature=Achievements'],
                            ['label' => 'Networking', 'url' => '/coming-soon?feature=Networking'],
                            ['label' => 'Career Services', 'url' => '/coming-soon?feature=Career Services'],
                        ];
                    }
                @endphp
                @foreach ($alumniLinks as $i => $link)
                    <div class="admin-form-row link-row" style="margin-bottom:14px">
                        <div>
                            <div class="stat-row-header">
                                <label class="admin-form-label" style="margin-bottom:0">Label:</label>
                                <button type="button" onclick="this.closest('.link-row').remove()" class="remove-stat-btn">
                                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <polyline points="3 6 5 6 21 6"/><path d="M19 6l-1 14a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2L5 6"/>
                                        <path d="M10 11v6M14 11v6M9 6V4a1 1 0 0 1 1-1h4a1 1 0 0 1 1 1v2"/>
                                    </svg>
                                </button>
                            </div>
                            <input type="text" name="alumni_link_labels[]" class="admin-input" value="{{ $link['label'] ?? '' }}" placeholder="Alumni Directory">
                        </div>
                        <div>
                            <label class="admin-form-label">URL:</label>
                            <input type="text" name="alumni_link_urls[]" class="admin-input" value="{{ $link['url'] ?? '' }}" placeholder="/alumni">
                        </div>
                    </div>
                @endforeach
            </div>
            <a href="#" onclick="addAlumniLinkRow(); return false;" style="color:var(--orange);font-weight:600;font-size:13px;display:inline-flex;align-items:center;gap:6px">
                <span style="background:var(--orange);color:#fff;width:18px;height:18px;border-radius:50%;display:flex;align-items:center;justify-content:center;font-size:12px">+</span>
                Add link
            </a>
        </div>

        {{-- Social Media --}}
        <div style="background:#fff;border-radius:var(--radius);padding:28px;margin-bottom:24px;box-shadow:var(--shadow)">
            <div class="cms-section-title">Social Media Links</div>
            <div class="admin-form-row">
                <div class="admin-form-group">
                    <label class="admin-form-label">Twitter / X:</label>
                    <input type="url" name="social_twitter" class="admin-input" value="{{ $settings['social_twitter'] ?? '' }}" placeholder="https://twitter.com/...">
                </div>
                <div class="admin-form-group">
                    <label class="admin-form-label">LinkedIn:</label>
                    <input type="url" name="social_linkedin" class="admin-input" value="{{ $settings['social_linkedin'] ?? '' }}" placeholder="https://linkedin.com/...">
                </div>
            </div>
            <div class="admin-form-row">
                <div class="admin-form-group">
                    <label class="admin-form-label">Facebook:</label>
                    <input type="url" name="social_facebook" class="admin-input" value="{{ $settings['social_facebook'] ?? '' }}" placeholder="https://facebook.com/...">
                </div>
                <div class="admin-form-group">
                    <label class="admin-form-label">Instagram:</label>
                    <input type="url" name="social_instagram" class="admin-input" value="{{ $settings['social_instagram'] ?? '' }}" placeholder="https://instagram.com/...">
                </div>
            </div>
            <div class="admin-form-row">
                <div class="admin-form-group">
                    <label class="admin-form-label">TikTok:</label>
                    <input type="url" name="social_tiktok" class="admin-input" value="{{ $settings['social_tiktok'] ?? '' }}" placeholder="https://tiktok.com/...">
                </div>
            </div>
        </div>

        {{-- Contact + Copyright --}}
        <div style="background:#fff;border-radius:var(--radius);padding:28px;margin-bottom:24px;box-shadow:var(--shadow)">
            <div class="cms-section-title">Contact & Copyright</div>
            <div class="admin-form-row">
                <div class="admin-form-group">
                    <label class="admin-form-label">Phone Number:</label>
                    <input type="text" name="contact_number" class="admin-input" value="{{ $settings['contact_number'] ?? '' }}" placeholder="+92 21 123 4567">
                </div>
                <div class="admin-form-group">
                    <label class="admin-form-label">Email Address:</label>
                    <input type="email" name="contact_email" class="admin-input" value="{{ $settings['contact_email'] ?? '' }}" placeholder="info@poba.com">
                </div>
            </div>
            <div class="admin-form-group">
                <label class="admin-form-label">Copyright Text:</label>
                <input type="text" name="copyright_text" class="admin-input" value="{{ $settings['footer_copyright'] ?? '© 2025 POBA. All rights reserved.' }}" placeholder="© 2025 POBA. All rights reserved.">
            </div>
        </div>

        <div style="display:flex;gap:14px;margin-top:10px">
            <button type="submit" class="btn-teal" style="padding:12px 40px">Save</button>
            <button type="reset" class="btn-outline-red" style="padding:12px 40px">Cancel</button>
        </div>
    </form>
</div>

<script>
function previewFooterLogo(input) {
    if (!input.files || !input.files[0]) return;
    const reader = new FileReader();
    reader.onload = e => { document.getElementById('footerLogoPreview').src = e.target.result; };
    reader.readAsDataURL(input.files[0]);
}

function addQuickLinkRow() {
    const html = `<div class="admin-form-row link-row" style="margin-bottom:14px">
        <div>
            <div class="stat-row-header">
                <label class="admin-form-label" style="margin-bottom:0">Label:</label>
                <button type="button" onclick="this.closest('.link-row').remove()" class="remove-stat-btn">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <polyline points="3 6 5 6 21 6"/><path d="M19 6l-1 14a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2L5 6"/>
                        <path d="M10 11v6M14 11v6M9 6V4a1 1 0 0 1 1-1h4a1 1 0 0 1 1 1v2"/>
                    </svg>
                </button>
            </div>
            <input type="text" name="quick_link_labels[]" class="admin-input" placeholder="Label">
        </div>
        <div>
            <label class="admin-form-label">URL:</label>
            <input type="text" name="quick_link_urls[]" class="admin-input" placeholder="/path">
        </div>
    </div>`;
    document.getElementById('quickLinksRows').insertAdjacentHTML('beforeend', html);
}

function addAlumniLinkRow() {
    const html = `<div class="admin-form-row link-row" style="margin-bottom:14px">
        <div>
            <div class="stat-row-header">
                <label class="admin-form-label" style="margin-bottom:0">Label:</label>
                <button type="button" onclick="this.closest('.link-row').remove()" class="remove-stat-btn">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <polyline points="3 6 5 6 21 6"/><path d="M19 6l-1 14a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2L5 6"/>
                        <path d="M10 11v6M14 11v6M9 6V4a1 1 0 0 1 1-1h4a1 1 0 0 1 1 1v2"/>
                    </svg>
                </button>
            </div>
            <input type="text" name="alumni_link_labels[]" class="admin-input" placeholder="Label">
        </div>
        <div>
            <label class="admin-form-label">URL:</label>
            <input type="text" name="alumni_link_urls[]" class="admin-input" placeholder="/path">
        </div>
    </div>`;
    document.getElementById('alumniLinksRows').insertAdjacentHTML('beforeend', html);
}
</script>
@endsection
