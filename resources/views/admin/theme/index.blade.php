{{-- FILE: resources/views/admin/theme/index.blade.php --}}
@extends('layouts.admin')
@section('title', 'Theme Settings - Admin')
@section('page-title', 'Theme Settings')

@section('content')
<style>
.ts-wrap        { display:flex; gap:28px; align-items:flex-start; flex-wrap:wrap; }
.ts-form        { flex:1; min-width:300px; max-width:600px; }
.ts-preview-col { width:380px; position:sticky; top:90px; flex-shrink:0; }

.ts-card        { background:#fff; border-radius:14px; padding:28px 30px;
                  box-shadow:0 2px 10px rgba(0,0,0,.05); border:1px solid #e8ecf0;
                  margin-bottom:18px; }
.ts-card h3     { font-size:15px; font-weight:700; color:#1a202c; margin:0 0 22px 0;
                  padding-bottom:12px; border-bottom:2px solid #f0f4f8; }

.clr-field      { margin-bottom:22px; }
.clr-label      { font-size:13px; font-weight:600; color:#374151; margin-bottom:8px; display:block; }
.clr-row        { display:flex; align-items:center; gap:10px; }
.clr-picker     { width:50px; height:42px; border-radius:10px; border:2px solid #e2e8f0;
                  padding:3px; cursor:pointer; background:none; flex-shrink:0; }
.clr-hex        { flex:1; border:1.5px solid #e2e8f0; border-radius:10px;
                  padding:10px 14px; font-size:14px; font-family:monospace;
                  background:#f8fafc; outline:none; color:#1a202c; }
.clr-hex:focus  { border-color:#0d9488; background:#fff; }
.clr-swatch     { width:42px; height:42px; border-radius:10px; flex-shrink:0;
                  border:1px solid rgba(0,0,0,.1); }

.fld-label      { font-size:13px; font-weight:600; color:#374151; margin-bottom:8px; display:block; }
.fld-select     { width:100%; border:1.5px solid #e2e8f0; border-radius:10px;
                  padding:10px 14px; font-size:14px; background:#f8fafc;
                  outline:none; color:#1a202c; cursor:pointer; margin-bottom:14px; }
.fld-select:focus { border-color:#0d9488; background:#fff; }
.fnt-sample     { padding:10px 14px; background:#f8fafc; border-radius:8px;
                  font-size:15px; color:#374151; border:1px solid #e2e8f0; margin-bottom:18px; }

.size-row       { display:grid; grid-template-columns:1fr 1fr 1fr; gap:12px; }
.size-grp       { display:flex; flex-direction:column; gap:5px; }
.size-lbl       { font-size:11px; font-weight:600; color:#64748b;
                  text-transform:uppercase; letter-spacing:.4px; }
.size-sel       { border:1.5px solid #e2e8f0; border-radius:8px; padding:8px 10px;
                  font-size:13px; background:#f8fafc; outline:none; cursor:pointer; }
.size-sel:focus { border-color:#0d9488; }

.ts-actions     { display:flex; gap:12px; margin-top:8px; }
.btn-save       { padding:12px 32px; background:#0d9488; color:#fff !important; border:none;
                  border-radius:10px; font-size:14px; font-weight:600; cursor:pointer; }
.btn-save:hover { background:#0b7a70; }
.btn-reset      { padding:12px 24px; background:transparent; color:#dc2626;
                  border:1.5px solid #dc2626; border-radius:10px; font-size:14px;
                  font-weight:600; cursor:pointer; }

.prev-panel     { background:#fff; border-radius:14px; overflow:hidden;
                  box-shadow:0 4px 20px rgba(0,0,0,.08); border:1px solid #e2e8f0; }
.prev-header    { background:#0d9488; color:#fff; padding:12px 18px;
                  display:flex; align-items:center; justify-content:space-between; }
.prev-header b  { font-size:13px; }
.prev-header span { font-size:11px; opacity:.8; }
#previewFrame   { width:100%; height:560px; border:none; display:block; }
</style>

<form method="POST" action="{{ route('admin.theme.update') }}" id="themeForm">
@csrf @method('PUT')
<div class="ts-wrap">

    <div class="ts-form">

        {{-- Colors --}}
        <div class="ts-card">
            <h3>🎨 Colors</h3>

            <div class="clr-field">
                <label class="clr-label">
                    Primary Color
                    <span style="font-weight:400;color:#888;font-size:12px">
                        — navbar, headings, buttons, footer background
                    </span>
                </label>
                <div class="clr-row">
                    <input type="color" class="clr-picker" id="picker_primary"
                           value="{{ $settings['primary_color'] ?? '#1a7a7a' }}"
                           oninput="syncHex('primary',this.value);liveUpdate()">
                    <input type="text" name="primary_color" id="hex_primary" class="clr-hex"
                           value="{{ $settings['primary_color'] ?? '#1a7a7a' }}"
                           maxlength="7" placeholder="#1a7a7a"
                           oninput="syncPicker('primary',this.value);liveUpdate()">
                    <div class="clr-swatch" id="swatch_primary"
                         style="background:{{ $settings['primary_color'] ?? '#1a7a7a' }}"></div>
                </div>
            </div>

            <div class="clr-field" style="margin-bottom:0">
                <label class="clr-label">
                    Secondary Color
                    <span style="font-weight:400;color:#888;font-size:12px">
                        — accents, labels, underlines, hover effects
                    </span>
                </label>
                <div class="clr-row">
                    <input type="color" class="clr-picker" id="picker_secondary"
                           value="{{ $settings['secondary_color'] ?? '#e87722' }}"
                           oninput="syncHex('secondary',this.value);liveUpdate()">
                    <input type="text" name="secondary_color" id="hex_secondary" class="clr-hex"
                           value="{{ $settings['secondary_color'] ?? '#e87722' }}"
                           maxlength="7" placeholder="#e87722"
                           oninput="syncPicker('secondary',this.value);liveUpdate()">
                    <div class="clr-swatch" id="swatch_secondary"
                         style="background:{{ $settings['secondary_color'] ?? '#e87722' }}"></div>
                </div>
            </div>
        </div>

        {{-- Font --}}
        <div class="ts-card">
            <h3>🔤 Font & Sizes</h3>

            <label class="fld-label">Font Style</label>
            <select name="heading_font" id="f_font" class="fld-select" onchange="liveUpdate()">
                @foreach($googleFonts as $font)
                <option value="{{ $font }}"
                    {{ ($settings['heading_font'] ?? 'Poppins') === $font ? 'selected' : '' }}>
                    {{ $font }}
                </option>
                @endforeach
            </select>
            <div class="fnt-sample" id="fontSample">
                The quick brown fox jumps over the lazy dog — POBA Alumni Portal
            </div>

            <label class="fld-label" style="margin-bottom:10px">Font Sizes</label>
            <div class="size-row">
                <div class="size-grp">
                    <span class="size-lbl">Heading (H1)</span>
                    <select name="heading_size" id="f_h1" class="size-sel" onchange="liveUpdate()">
                        @foreach(['24px'=>'24px','28px'=>'28px','32px'=>'32px','36px'=>'36px','40px'=>'40px','48px'=>'48px','56px'=>'56px'] as $v=>$l)
                        <option value="{{ $v }}" {{ ($settings['heading_size'] ?? '32px') === $v ? 'selected' : '' }}>{{ $l }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="size-grp">
                    <span class="size-lbl">Sub-Heading (H2)</span>
                    <select name="subheading_size" id="f_h2" class="size-sel" onchange="liveUpdate()">
                        @foreach(['16px'=>'16px','18px'=>'18px','20px'=>'20px','24px'=>'24px','28px'=>'28px','32px'=>'32px'] as $v=>$l)
                        <option value="{{ $v }}" {{ ($settings['subheading_size'] ?? '24px') === $v ? 'selected' : '' }}>{{ $l }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="size-grp">
                    <span class="size-lbl">Body Text</span>
                    <select name="body_size" id="f_body" class="size-sel" onchange="liveUpdate()">
                        @foreach(['12px'=>'12px','13px'=>'13px','14px'=>'14px','15px'=>'15px','16px'=>'16px','18px'=>'18px'] as $v=>$l)
                        <option value="{{ $v }}" {{ ($settings['body_size'] ?? '14px') === $v ? 'selected' : '' }}>{{ $l }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>

        {{-- Hidden fields --}}
        <input type="hidden" name="body_font"       value="{{ $settings['body_font']       ?? 'Roboto' }}">
        <input type="hidden" name="nav_font"        value="{{ $settings['nav_font']        ?? 'Poppins' }}">
        <input type="hidden" name="nav_size"        value="{{ $settings['nav_size']        ?? '14px' }}">
        <input type="hidden" name="heading_weight"  value="{{ $settings['heading_weight']  ?? '700' }}">
        <input type="hidden" name="text_color"      value="{{ $settings['text_color']      ?? '#2c3e50' }}">
        <input type="hidden" name="bg_color"        value="{{ $settings['bg_color']        ?? '#ffffff' }}">
        <input type="hidden" name="nav_bg_color"    value="{{ $settings['nav_bg_color']    ?? '#ffffff' }}">
        <input type="hidden" name="footer_bg_color" value="{{ $settings['footer_bg_color'] ?? '#1a7a7a' }}">
        <input type="hidden" name="card_bg_color"   value="{{ $settings['card_bg_color']   ?? '#ffffff' }}">
        <input type="hidden" name="border_radius"   value="{{ $settings['border_radius']   ?? '12px' }}">
        <input type="hidden" name="button_radius"   value="{{ $settings['button_radius']   ?? '30px' }}">

        <div class="ts-actions">
            <button type="submit" class="btn-save">💾 Save & Apply</button>
            <button type="button" class="btn-reset" onclick="confirmReset()">↺ Reset to Default</button>
        </div>
    </div>

    {{-- Preview --}}
    <div class="ts-preview-col">
        <div class="prev-panel">
            <div class="prev-header">
                <b>👁 Live Preview</b>
                <span>Updates instantly</span>
            </div>
            <iframe id="previewFrame" scrolling="yes"></iframe>
        </div>
    </div>
</div>
</form>

<form method="POST" action="{{ route('admin.theme.reset') }}" id="resetForm">
    @csrf @method('DELETE')
</form>

@push('scripts')
<script>
const loadedFonts = new Set();
function loadFont(name) {
    if (!name || loadedFonts.has(name)) return;
    loadedFonts.add(name);
    const l = document.createElement('link');
    l.rel  = 'stylesheet';
    l.href = `https://fonts.googleapis.com/css2?family=${name.replace(/ /g,'+')}:wght@400;500;600;700;800&display=swap`;
    document.head.appendChild(l);
}
function gv(id) { const e=document.getElementById(id); return e?e.value.trim():''; }
function syncHex(key,val){ document.getElementById('hex_'+key).value=val; document.getElementById('swatch_'+key).style.background=val; }
function syncPicker(key,val){ if(/^#[0-9A-Fa-f]{6}$/.test(val)){ document.getElementById('picker_'+key).value=val; document.getElementById('swatch_'+key).style.background=val; } }

function liveUpdate() {
    const primary   = gv('hex_primary')   || '#1a7a7a';
    const secondary = gv('hex_secondary') || '#e87722';
    const font      = gv('f_font')        || 'Poppins';
    const h1size    = gv('f_h1')          || '32px';
    const h2size    = gv('f_h2')          || '24px';
    const bodySize  = gv('f_body')        || '14px';

    loadFont(font);
    const sample = document.getElementById('fontSample');
    sample.style.fontFamily = `'${font}', sans-serif`;
    sample.style.fontSize   = bodySize;

    const fontUrl = `https://fonts.googleapis.com/css2?family=${font.replace(/ /g,'+')}:wght@400;600;700;800&display=swap`;

    document.getElementById('previewFrame').srcdoc = `<!DOCTYPE html>
<html><head>
<meta charset="UTF-8">
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="${fontUrl}" rel="stylesheet">
<style>
*{box-sizing:border-box;margin:0;padding:0;}
body{font-family:'${font}',sans-serif;background:#fff;color:#2c3e50;font-size:${bodySize};}

/* Navbar */
.nav{background:#fff;padding:11px 16px;display:flex;align-items:center;
     justify-content:space-between;box-shadow:0 1px 6px rgba(0,0,0,.08);}
.nav-links{display:flex;gap:14px;}
.nav-links a{color:#2c3e50;text-decoration:none;font-weight:600;font-size:${bodySize};
             font-family:'${font}',sans-serif;}
.nav-links a:hover{color:${secondary};}
.nav-btn{background:${primary};color:#fff;border:none;padding:7px 18px;
         border-radius:30px;font-family:'${font}',sans-serif;font-weight:600;
         font-size:${bodySize};cursor:pointer;}

/* Hero */
.hero{background:${primary};color:#fff;padding:22px 16px;}
.hero-tag{color:${secondary};font-weight:700;font-size:${bodySize};margin-bottom:8px;}
.hero h1{font-family:'${font}',sans-serif;font-size:${h1size};font-weight:800;
         color:#fff;margin-bottom:6px;line-height:1.2;}
.hero p{font-family:'${font}',sans-serif;font-size:${bodySize};opacity:.9;margin-bottom:14px;}
.hero-btn{background:#fff;color:${primary};border:none;padding:8px 20px;
          border-radius:30px;font-family:'${font}',sans-serif;font-weight:700;
          font-size:${bodySize};cursor:pointer;}

/* Content */
.content{padding:16px;}
.sec-tag{color:${secondary};font-size:11px;font-weight:700;text-transform:uppercase;
         letter-spacing:1px;margin-bottom:5px;}
.sec-title{font-family:'${font}',sans-serif;font-size:${h2size};font-weight:800;
           color:${primary};margin-bottom:4px;}
.sec-line{width:40px;height:3px;background:${secondary};border-radius:2px;margin-bottom:14px;}
.body-text{font-family:'${font}',sans-serif;font-size:${bodySize};
           color:#2c3e50;line-height:1.7;margin-bottom:14px;}

/* Buttons */
.btn-wrap{display:flex;gap:10px;margin-bottom:16px;flex-wrap:wrap;}
.btn-p{background:${primary};color:#fff;border:none;padding:9px 20px;
       border-radius:30px;font-family:'${font}',sans-serif;font-weight:600;
       font-size:${bodySize};cursor:pointer;}
.btn-s{background:transparent;color:${secondary};border:2px solid ${secondary};
       padding:7px 20px;border-radius:30px;font-family:'${font}',sans-serif;
       font-weight:600;font-size:${bodySize};cursor:pointer;}

/* Card */
.card{background:#fff;border-radius:12px;border:1px solid rgba(0,0,0,.08);
      padding:14px;margin-bottom:14px;box-shadow:0 2px 8px rgba(0,0,0,.05);}
.card-tag{display:inline-block;background:${secondary}22;color:${secondary};
          font-size:10px;font-weight:700;padding:3px 10px;border-radius:20px;
          margin-bottom:7px;font-family:'${font}',sans-serif;}
.card-title{font-family:'${font}',sans-serif;font-size:15px;font-weight:700;
            color:${primary};margin-bottom:5px;}
.card-text{font-family:'${font}',sans-serif;font-size:12px;color:#64748b;line-height:1.6;}

/* Footer */
.footer{background:${primary};color:#fff;padding:16px;}
.footer-inner{display:flex;justify-content:space-between;margin-bottom:12px;}
.footer-col h5{color:${secondary};font-size:12px;font-weight:700;
               margin-bottom:8px;font-family:'${font}',sans-serif;}
.footer-col a{display:block;color:rgba(255,255,255,.85);font-size:11px;
              text-decoration:none;margin-bottom:4px;font-family:'${font}',sans-serif;}
.footer-bottom{border-top:1px solid rgba(255,255,255,.2);padding-top:10px;
               font-size:11px;color:rgba(255,255,255,.8);font-family:'${font}',sans-serif;}
</style></head><body>

<nav class="nav">
    <div class="nav-links">
        <a href="#">Home</a><a href="#">About</a>
        <a href="#">Events</a><a href="#">Alumni</a>
    </div>
    <button class="nav-btn">Login</button>
</nav>

<div class="hero">
    <div class="hero-tag">Serving with Valour</div>
    <h1>Welcome to POBA Alumni Network</h1>
    <p>Join our prestigious community of Pakistan Ocean & Bay Alumni.</p>
    <button class="hero-btn">Become a Member</button>
</div>

<div class="content">
    <div class="sec-tag">Latest</div>
    <div class="sec-title">Alumni Updates</div>
    <div class="sec-line"></div>
    <p class="body-text">Stay connected with your fellow alumni. Share experiences and build lasting professional relationships as part of the Palandarians community.</p>
    <div class="btn-wrap">
        <button class="btn-p">View Directory</button>
        <button class="btn-s">Learn More</button>
    </div>
    <div class="card">
        <span class="card-tag">EVENT</span>
        <div class="card-title">Annual Alumni Reunion 2025</div>
        <div class="card-text">Join us for an evening of reconnecting with classmates and celebrating our shared heritage.</div>
    </div>
</div>

<footer class="footer">
    <div class="footer-inner">
        <div class="footer-col">
            <h5>Quick Links</h5>
            <a href="#">About Us</a><a href="#">News</a>
            <a href="#">Events</a><a href="#">Star Alumni</a>
        </div>
        <div class="footer-col">
            <h5>Alumni</h5>
            <a href="#">Alumni Directory</a><a href="#">Achievements</a>
            <a href="#">Networking</a><a href="#">Career Services</a>
        </div>
    </div>
    <div class="footer-bottom">© 2025 POBA Alumni Portal — All rights reserved</div>
</footer>

</body></html>`;
}

function confirmReset() {
    if (confirm('Reset theme to POBA defaults?')) {
        document.getElementById('resetForm').submit();
    }
}

document.addEventListener('DOMContentLoaded', function () {
    loadFont(gv('f_font'));
    liveUpdate();
});
</script>
@endpush
@endsection
