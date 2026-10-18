{{-- FILE: resources/views/customer/member/register.blade.php --}}
@extends('layouts.app')
@section('title','Become a Member - POBA')
@section('content')

<style>
/* ── Page header ── */
.bam-header      { text-align:center; padding:44px 20px 28px; }
.bam-header h1   { font-size:32px; font-weight:700; color:var(--teal,#0d9488); margin-bottom:0; }
.bam-underline   { width:140px; height:3.5px; background:var(--orange, #e87722); margin:10px auto 0; border-radius:2px; }

/* ── Two-col layout ── */
.bam-wrap        { display:flex; gap:40px; align-items:flex-start; flex-wrap:wrap;
                   max-width:1100px; margin:0 auto; padding:0 20px 60px; }

/* ── Form card ── */
.bam-card        { flex:1; min-width:300px; background:#f0f9f9;
                   border-radius:16px; padding:32px; }
.bam-card-title  { font-size:17px; font-weight:700; text-align:center;
                   margin-bottom:24px; color:#222; }

/* ── Grid rows ── */
.bam-row         { display:grid; gap:14px; margin-bottom:14px; }
.bam-row-2       { grid-template-columns:1fr 1fr; }
.bam-row-1       { grid-template-columns:1fr; }
.bam-group       { display:flex; flex-direction:column; gap:4px; }
.bam-label       { font-size:12px; font-weight:500; color:#444; }
.bam-label span  { color:#e53e3e; margin-left:2px; }

/* ── Inputs ── */
.bam-input, .bam-select, .bam-textarea {
    background:#fff; border:1.5px solid #e2e8f0; border-radius:8px;
    padding:10px 13px; font-size:13px; color:#222;
    width:100%; box-sizing:border-box; outline:none; transition:border .2s;
}
.bam-input:focus, .bam-select:focus, .bam-textarea:focus { border-color:#0d9488; }
.bam-input.is-invalid, .bam-select.is-invalid, .bam-textarea.is-invalid {
    border-color:#e53e3e;
}
.bam-error       { font-size:12px; color:#e53e3e; margin-top:4px; font-weight:500; }
.bam-textarea    { resize:vertical; }

/* ── Phone wrap ── */
.bam-phone-wrap  { display:flex; align-items:stretch; }
.bam-phone-flag  { position:relative; display:inline-flex; align-items:center; gap:6px; background:#fff;
                   border:1.5px solid #e2e8f0; border-right:none;
                   border-radius:8px 0 0 8px; padding:8px 12px;
                   font-size:13px; white-space:nowrap; cursor:pointer;
                   transition:border-color .15s, background .15s; }
.bam-phone-flag:hover { border-color:#0d9488; background:#f0fdfc; }
.bam-phone-flag:focus-within { border-color:#0d9488; box-shadow:0 0 0 2px rgba(13,148,136,0.15); }
.bam-phone-flag .phone-flag-icon { display:inline-flex; align-items:center; }
.bam-phone-flag .phone-country-label { font-size:12px; font-weight:700; color:#334155; }
.bam-phone-flag .phone-code-badge { font-size:13px; font-weight:700; color:#0d9488; margin-left:2px; }
.bam-phone-flag .phone-chevron { font-size:10px; color:#94a3b8; margin-left:2px; }
.bam-phone-flag select { position:absolute; top:0; left:0; width:100%; height:100%; opacity:0; cursor:pointer; z-index:5; }
.bam-phone-num   { border-radius:0 8px 8px 0 !important; }

/* ── File upload ── */
.bam-file-area   { background:#fff; border:1.5px dashed #b2d8d8; border-radius:8px;
                   padding:14px 16px; display:flex; align-items:center;
                   gap:10px; cursor:pointer; transition:border .2s; }
.bam-file-area:hover { border-color:#0d9488; }
.bam-file-area * { pointer-events:none; }
.bam-file-area.bam-dragover {
    border-color:#0d9488 !important;
    background:#e6f7f5 !important;
    border-style:solid !important;
    box-shadow:0 0 0 3px rgba(13, 148, 136, 0.18) !important;
    transform:scale(1.008);
}
.bam-file-area.is-invalid { border-color:#e53e3e !important; background:#fef2f2 !important; }
.bam-file-icon   { font-size:18px; flex-shrink:0; }
.bam-file-txt    { font-size:12px; color:#888; flex:1; }
.bam-file-txt strong { display:block; font-size:13px; color:#0d9488; font-weight:600; }
.bam-file-input  { display:none; }
.bam-file-name   { font-size:12px; color:#0d9488; margin-top:4px; }

/* ── Privacy checkboxes ── */
.bam-privacy-note { font-size:12px; color:#666; margin-bottom:10px; }
.bam-chips       { display:flex; flex-wrap:wrap; gap:8px; margin-bottom:6px; }
.bam-chip        { display:flex; align-items:center; gap:5px; background:#fff;
                   border:1.5px solid #e2e8f0; border-radius:20px;
                   padding:5px 14px; font-size:12px; cursor:pointer;
                   user-select:none; transition:border .2s; }
.bam-chip:hover  { border-color:#0d9488; }
.bam-chip input  { accent-color:#0d9488; width:13px; height:13px; }
.bam-chip.checked { border-color:#0d9488; background:#f0fdfc; }

/* ── Consent ── */
.bam-consent     { display:flex; align-items:flex-start; gap:10px;
                   font-size:12px; color:#555; margin-bottom:10px; line-height:1.6; }
.bam-consent input { margin-top:3px; accent-color:#0d9488; width:14px; height:14px;
                     flex-shrink:0; }
.bam-consent a   { color:#0d9488; }
.bam-consent-err { font-size:11px; color:#e53e3e; margin-bottom:8px; margin-left:24px; margin-top:-6px; }

/* ── Submit ── */
.bam-submit      { width:100%; padding:13px; background:#0d9488; color:#fff;
                   border:none; border-radius:8px; font-size:15px; font-weight:700;
                   cursor:pointer; transition:background .2s; margin-top:4px; }
.bam-submit:hover { background:#0b7a70; }
.bam-note        { text-align:center; font-size:11px; color:#aaa; margin-top:12px; line-height:1.6; }

/* ── Right: Benefits ── */
.bam-benefits    { min-width:240px; max-width:280px; padding-top:8px; }
.bam-benefits h2 { font-size:20px; font-weight:700; text-align:center;
                   margin-bottom:28px; color:#222; }
.bam-benefit-item { display:flex; flex-direction:column; align-items:center;
                    text-align:center; margin-bottom:28px; }
.bam-benefit-icon { width:48px; height:48px; margin-bottom:10px; }
.bam-benefit-title { font-size:14px; font-weight:700; color:#222; margin-bottom:4px; }
.bam-benefit-desc  { font-size:12px; color:#888; line-height:1.6; }

/* ── Global alert ── */
.bam-alert-err   { background:#fee2e2; border:1px solid #fca5a5; color:#991b1b;
                   border-radius:8px; padding:12px 16px; font-size:13px; margin-bottom:20px; }

@media(max-width:720px){
    .bam-row-2   { grid-template-columns:1fr; }
    .bam-benefits { max-width:100%; }
    .bam-wrap    { flex-direction:column-reverse; }
}
</style>

<div class="bam-header">
    <h1>Become a Member</h1>
    <div class="bam-underline"></div>
</div>

<div class="bam-wrap">
   {{-- ── Registration Form ── --}}
    <div class="bam-card">
        <div class="bam-card-title">Alumni Registration Form</div>

        @if(session('error'))
        <div class="bam-alert-err">
            ⚠ {{ session('error') }}
        </div>
        @endif
        @if($errors->any())
        <div class="bam-alert-err">
            ⚠ Please fix the highlighted fields below and resubmit.
        </div>
        @endif

        <form method="POST" action="{{ route('member.store') }}"
              enctype="multipart/form-data" id="regForm" novalidate>
            @csrf

            {{-- Full Name --}}
            <div class="bam-row bam-row-1">
                <div class="bam-group">
                    <label class="bam-label">Full Name <span>*</span></label>
                    <input type="text" name="full_name" class="bam-input {{ $errors->has('full_name') ? 'is-invalid' : '' }}"
                           placeholder="Enter Full Name" value="{{ old('full_name') }}" required>
                    @error('full_name')<div class="bam-error">{{ $message }}</div>@enderror
                </div>
            </div>

            {{-- Batch + Class Year + CCP No --}}
            <div class="bam-row" style="display:grid;grid-template-columns:repeat(auto-fit, minmax(180px, 1fr));gap:16px;margin-bottom:16px;">
                <div class="bam-group">
                    <label class="bam-label">Batch <span>*</span></label>
                    <select name="entry" class="bam-select {{ $errors->has('entry') ? 'is-invalid' : '' }}" required>
                        <option value="">Select Batch</option>
                        @foreach(range(1,50) as $e)
                        <option value="{{ $e }}" {{ old('entry')==$e ? 'selected' : '' }}>Batch {{ $e }}</option>
                        @endforeach
                    </select>
                    @error('entry')<div class="bam-error">{{ $message }}</div>@enderror
                </div>
                <div class="bam-group">
                    <label class="bam-label">Class Year (Class of) <span>*</span></label>
                    <select name="class_year" class="bam-select {{ $errors->has('class_year') ? 'is-invalid' : '' }}" required>
                        <option value="">Select Class Year</option>
                        @foreach(range(date('Y'), 1947, -1) as $y)
                        <option value="{{ $y }}" {{ old('class_year')==$y ? 'selected' : '' }}>{{ $y }}</option>
                        @endforeach
                    </select>
                    @error('class_year')<div class="bam-error">{{ $message }}</div>@enderror
                </div>
                <div class="bam-group">
                    <label class="bam-label">CCP No. <span>*</span></label>
                    <input type="text" name="ccp_no" class="bam-input {{ $errors->has('ccp_no') ? 'is-invalid' : '' }}"
                           placeholder="Enter CCP No." value="{{ old('ccp_no') }}" required>
                    @error('ccp_no')<div class="bam-error">{{ $message }}</div>@enderror
                </div>
            </div>

            {{-- House + Education --}}
            <div class="bam-row bam-row-2">
                <div class="bam-group">
                    <label class="bam-label">House <span>*</span></label>
                    <select name="house" class="bam-select {{ $errors->has('house') ? 'is-invalid' : '' }}" required>
                        <option value="">Select House</option>
                        @foreach(['Jinnah','Iqbal','Liaquat','Ayub','Ranjit'] as $h)
                        <option value="{{ $h }}" {{ old('house')==$h ? 'selected' : '' }}>{{ $h }}</option>
                        @endforeach
                    </select>
                    @error('house')<div class="bam-error">{{ $message }}</div>@enderror
                </div>
                <div class="bam-group">
                    <label class="bam-label">Education <span>*</span></label>
                    <select name="education" class="bam-select {{ $errors->has('education') ? 'is-invalid' : '' }}" required>
                        <option value="">Select Education</option>
                        @foreach(['Matric','Intermediate','Bachelors','Masters','PhD'] as $ed)
                        <option value="{{ $ed }}" {{ old('education')==$ed ? 'selected' : '' }}>{{ $ed }}</option>
                        @endforeach
                    </select>
                    @error('education')<div class="bam-error">{{ $message }}</div>@enderror
                </div>
            </div>

            {{-- Field of Study + Field of Work --}}
            <div class="bam-row bam-row-2">
                <div class="bam-group">
                    <label class="bam-label">Field of Study <span>*</span></label>
                    <input type="text" name="field_of_study" class="bam-input {{ $errors->has('field_of_study') ? 'is-invalid' : '' }}"
                           placeholder="Enter Field of Study" value="{{ old('field_of_study') }}" required>
                    @error('field_of_study')<div class="bam-error">{{ $message }}</div>@enderror
                </div>
                <div class="bam-group">
                    <label class="bam-label">Field of Work <span>*</span></label>
                    <input type="text" name="field_of_work" class="bam-input {{ $errors->has('field_of_work') ? 'is-invalid' : '' }}"
                           placeholder="Enter Field of Work" value="{{ old('field_of_work') }}" required>
                    @error('field_of_work')<div class="bam-error">{{ $message }}</div>@enderror
                </div>
            </div>

            {{-- Current Country (Dropdown) + Current City (Dropdown, populated dynamically) --}}
            <div class="bam-row bam-row-2">
                <div class="bam-group">
                    <label class="bam-label">Current Country <span>*</span></label>
                    <select name="current_country" id="currentCountry" class="bam-select {{ $errors->has('current_country') ? 'is-invalid' : '' }}" required>
                        <option value="">Select Country</option>
                        @foreach($countries as $c)
                        <option value="{{ $c->name }}" data-id="{{ $c->id }}" {{ old('current_country')==$c->name ? 'selected' : '' }}>{{ $c->name }}</option>
                        @endforeach
                    </select>
                    @error('current_country')<div class="bam-error">{{ $message }}</div>@enderror
                </div>
                <div class="bam-group">
                    <label class="bam-label">Current City <span>*</span></label>
                    <select name="current_city" id="currentCity" class="bam-select {{ $errors->has('current_city') ? 'is-invalid' : '' }}" required>
                        <option value="">Select Country First</option>
                    </select>
                    @error('current_city')<div class="bam-error">{{ $message }}</div>@enderror
                </div>
            </div>

            {{-- Current Designation + Current Organization --}}
            <div class="bam-row bam-row-2">
                <div class="bam-group">
                    <label class="bam-label">Current Designation</label>
                    <input type="text" name="current_designation" class="bam-input"
                           placeholder="Enter Designation" value="{{ old('current_designation') }}">
                </div>
                <div class="bam-group">
                    <label class="bam-label">Current Organization</label>
                    <input type="text" name="current_organization" class="bam-input"
                           placeholder="Enter Organization" value="{{ old('current_organization') }}">
                </div>
            </div>

            {{-- Email ID --}}
            <div class="bam-row bam-row-1">
                <div class="bam-group">
                    <label class="bam-label">Email ID <span>*</span></label>
                    <input type="email" name="email" class="bam-input {{ $errors->has('email') ? 'is-invalid' : '' }}"
                           placeholder="name@email.com" value="{{ old('email') }}" required>
                    @error('email')<div class="bam-error">{{ $message }}</div>@enderror
                </div>
            </div>

            {{-- Phone Number (with dynamic flag, country label, and maxlength) --}}
            @php
            $phoneConfig = config('countries_phone', []);
            $priorityNames = ['Pakistan', 'South Africa', 'United States', 'United Kingdom', 'United Arab Emirates', 'Saudi Arabia', 'Albania', 'Afghanistan'];
            $sortedPhoneList = [];
            foreach ($priorityNames as $pName) {
                if (isset($phoneConfig[$pName])) {
                    $sortedPhoneList[] = array_merge($phoneConfig[$pName], ['name' => $pName]);
                }
            }
            $remaining = [];
            foreach ($phoneConfig as $cName => $cData) {
                if (!in_array($cName, $priorityNames)) {
                    $remaining[] = array_merge($cData, ['name' => $cName]);
                }
            }
            usort($remaining, fn($a, $b) => strcmp($a['name'], $b['name']));
            $phoneCountriesList = array_merge($sortedPhoneList, $remaining);
            @endphp
            <div class="bam-row bam-row-1">
                <div class="bam-group">
                    <label class="bam-label">Phone Number <span>*</span></label>
                    <div class="bam-phone-wrap">
                        <div class="bam-phone-flag" id="phone_flag_wrap" title="Select Country Code">
                            <span id="phone_flag" class="phone-flag-icon" aria-hidden="true">
                                <img id="phone_flag_img"
                                     src="{{ asset('images/flags/pk.png') }}"
                                     width="20" height="15"
                                     alt="PK"
                                     style="display:inline-block;width:20px;height:15px;object-fit:cover;border-radius:2px;box-shadow:0 0 1px rgba(0,0,0,0.3);flex-shrink:0;">
                            </span>
                            <span id="phone_country_label" class="phone-country-label">Pakistan (PK)</span>
                            <span id="phone_code_badge" class="phone-code-badge">+92</span>
                            <span class="phone-chevron" aria-hidden="true">▾</span>
                            <select name="phone_code" id="phone_code" aria-label="Country Code">
                                @foreach($phoneCountriesList as $pc)
                                <option value="{{ $pc['code'] }}"
                                        data-flag="{{ $pc['flag'] }}"
                                        data-name="{{ $pc['name'] }}"
                                        data-iso="{{ strtoupper($pc['iso']) }}"
                                        data-maxlen="{{ $pc['max'] }}"
                                        data-placeholder="{{ $pc['ph'] }}"
                                        {{ old('phone_code', '+92') == $pc['code'] ? 'selected' : '' }}>
                                    {{ $pc['flag'] }} {{ $pc['name'] }} ({{ $pc['code'] }})
                                </option>
                                @endforeach
                            </select>
                        </div>
                        <input type="text" name="phone_number"
                               id="phone_number"
                               class="bam-input bam-phone-num {{ $errors->has('phone_number') ? 'is-invalid' : '' }}"
                               placeholder="300 1234567"
                               value="{{ old('phone_number') }}"
                               required
                               maxlength="10"
                               oninput="this.value = this.value.replace(/\D/g,'')">
                    </div>
                    @error('phone_number')<div class="bam-error">{{ $message }}</div>@enderror
                    <div id="phone_hint" style="font-size:11px;color:#888;margin-top:3px;">Pakistan (PK) — Enter up to 10 digits</div>
                </div>
            </div>

            {{-- Achievements --}}
            <div class="bam-row bam-row-1">
                <div class="bam-group">
                    <label class="bam-label">Achievements</label>
                    <textarea name="achievements" class="bam-textarea" rows="3"
                              placeholder="Enter Achievements">{{ old('achievements') }}</textarea>
                </div>
            </div>

            {{-- Upload CNIC --}}
            <div class="bam-row bam-row-1">
                <div class="bam-group">
                    <label class="bam-label">Upload CNIC <span>*</span></label>
                    <div class="bam-file-area {{ $errors->has('cnic_file') ? 'is-invalid' : '' }}"
                         id="cnic_file_area"
                         onclick="document.getElementById('cnic_input').click()">
                        <div class="bam-file-icon">📎</div>
                        <div class="bam-file-txt">
                            <strong id="cnic_label">Drag & Drop Files here or click to select files</strong>
                            JPG, PNG or PDF — max 5MB
                        </div>
                    </div>
                    <input type="file" id="cnic_input" name="cnic_file" class="bam-file-input"
                           accept=".jpg,.jpeg,.png,.pdf" onchange="showFile(this,'cnic_label','cnic_name')">
                    <div id="cnic_name" class="bam-file-name"></div>
                    <div id="cnic_error" class="bam-error" style="display:none;"></div>
                    @error('cnic_file')<div class="bam-error" id="cnic_server_error">{{ $message }}</div>@enderror
                </div>
            </div>

            {{-- Profile Photo --}}
            <div class="bam-row bam-row-1">
                <div class="bam-group">
                    <label class="bam-label">Profile Photo</label>
                    <div class="bam-file-area {{ $errors->has('profile_photo') ? 'is-invalid' : '' }}"
                         id="photo_file_area"
                         onclick="document.getElementById('photo_input').click()">
                        <div class="bam-file-icon">🖼</div>
                        <div class="bam-file-txt">
                            <strong id="photo_label">Drag & Drop Files here or click to select files</strong>
                            JPG, PNG or PDF — max 5MB
                        </div>
                    </div>
                    <input type="file" id="photo_input" name="profile_photo" class="bam-file-input"
                           accept=".jpg,.jpeg,.png,.pdf" onchange="showFile(this,'photo_label','photo_name')">
                    <div id="photo_name" class="bam-file-name"></div>
                    <div id="photo_error" class="bam-error" style="display:none;"></div>
                    @error('profile_photo')<div class="bam-error" id="photo_server_error">{{ $message }}</div>@enderror
                </div>
            </div>

            {{-- Privacy Settings --}}
            <div class="bam-row bam-row-1">
                <div class="bam-group">
                    <label class="bam-label">Privacy Settings</label>
                    <p class="bam-privacy-note">Choose which details to hide with other alumni —
                        <strong>check a field to hide it from others</strong></p>
                    @php
                        $privacyFields = ['Email Address','City','Phone Number','Designation','Organization','Field of Study','Field of Work'];
                        $oldPrivacy    = old('privacy_hide', []);
                    @endphp
                    <div class="bam-chips">
                        @foreach($privacyFields as $pf)
                        <label class="bam-chip {{ in_array($pf, $oldPrivacy) ? 'checked' : '' }}" id="chip_{{ $loop->index }}">
                            <input type="checkbox" name="privacy_hide[]" value="{{ $pf }}"
                                   {{ in_array($pf, $oldPrivacy) ? 'checked' : '' }}
                                   onchange="toggleChip(this)">
                            {{ $pf }}
                        </label>
                        @endforeach
                    </div>
                </div>
            </div>

            {{-- Consent & Legal Policies --}}
            <div class="bam-row bam-row-1" style="margin-bottom:18px;">
                {{-- Policy notice banner --}}
                <!-- <div class="bam-legal-banner" style="background:#f0fdfa;border:1px solid #ccfbf1;border-radius:8px;padding:12px 16px;margin-bottom:14px;display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:10px;">
                    <div style="display:flex;align-items:center;gap:8px;font-size:12.5px;color:#0f766e;font-weight:600;">
                        <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/></svg>
                        <span>Please review POBA policies before submitting:</span>
                    </div>
                    <div style="display:flex;align-items:center;gap:16px;">
                        <a href="{{ route('privacy') }}" target="_blank" onclick="openLegalModal('privacy', event)"
                           style="color:#0d9488;font-size:12.5px;font-weight:700;text-decoration:underline;display:inline-flex;align-items:center;gap:4px;">
                            <span>Privacy Policy</span>
                            <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6"/><polyline points="15 3 21 3 21 9"/><line x1="10" y1="14" x2="21" y2="3"/></svg>
                        </a>
                        <span style="color:#99f6e4;">•</span>
                        <a href="{{ route('terms') }}" target="_blank" onclick="openLegalModal('terms', event)"
                           style="color:#0d9488;font-size:12.5px;font-weight:700;text-decoration:underline;display:inline-flex;align-items:center;gap:4px;">
                            <span>Terms &amp; Conditions</span>
                            <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6"/><polyline points="15 3 21 3 21 9"/><line x1="10" y1="14" x2="21" y2="3"/></svg>
                        </a>
                    </div>
                </div> -->

                <div>
                    <label class="bam-consent">
                        <input type="checkbox" name="consent_sharing" value="1"
                               {{ old('consent_sharing') ? 'checked' : '' }} required>
                        <span>
                            <strong>I consent to sharing my details with other POBA alumni.*</strong><br>
                            <span style="font-size:11px;color:#64748b;">Your information will be shared with verified alumni according to your privacy settings selected above.</span>
                        </span>
                    </label>
                    @error('consent_sharing')
                    <div class="bam-consent-err">{{ $message }}</div>
                    @enderror

                    <label class="bam-consent" style="margin-top:6px;">
                        <input type="checkbox" name="agree_terms" value="1"
                               {{ old('agree_terms') ? 'checked' : '' }} required>
                        <span>
                            I agree to the
                            <a href="{{ route('terms') }}" target="_blank" onclick="openLegalModal('terms', event)" style="color:#0d9488;font-weight:700;text-decoration:underline;">Terms &amp; Conditions</a>
                            and
                            <a href="{{ route('privacy') }}" target="_blank" onclick="openLegalModal('privacy', event)" style="color:#0d9488;font-weight:700;text-decoration:underline;">Privacy Policy</a>.*
                        </span>
                    </label>
                    @error('agree_terms')
                    <div class="bam-consent-err">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            {{-- 🛡️ reCAPTCHA / Security Verification --}}
            <div class="bam-row bam-row-1" style="margin-top:10px;margin-bottom:20px;">
                <div style="background:#f8fafc;border:1.5px solid #cbd5e1;border-radius:10px;padding:16px 20px;display:flex;flex-wrap:wrap;align-items:center;justify-content:space-between;gap:16px;">
                    <div style="display:flex;align-items:center;gap:12px;">
                        <div style="width:40px;height:40px;background:#e2e8f0;border-radius:8px;display:flex;align-items:center;justify-content:center;">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#0d9488" stroke-width="2.2">
                                <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/>
                                <path d="m9 12 2 2 4-4"/>
                            </svg>
                        </div>
                        <div>
                            <div style="font-size:13px;font-weight:700;color:#1e293b;">Security Verification (reCAPTCHA)</div>
                            <div style="font-size:12px;color:#64748b;">Please calculate: <strong style="color:#0d9488;font-size:14px;">{{ session('captcha_n1', $n1 ?? rand(2,8)) }} + {{ session('captcha_n2', $n2 ?? rand(1,9)) }}</strong></div>
                        </div>
                    </div>
                    <div style="display:flex;align-items:center;gap:10px;">
                        <input type="number" name="captcha_answer" placeholder="Answer"
                               class="bam-input {{ $errors->has('captcha_answer') ? 'is-invalid' : '' }}"
                               style="width:100px;text-align:center;font-weight:700;font-size:15px;padding:8px;" required>
                        <span style="font-size:11px;color:#94a3b8;line-height:1.2;text-align:right;">Protected by<br><strong style="color:#64748b;">reCAPTCHA</strong></span>
                    </div>
                </div>
                @error('captcha_answer')
                <div class="bam-error" style="margin-top:6px;">{{ $message }}</div>
                @enderror
            </div>

            <button type="submit" class="bam-submit">Submit</button>

            <p class="bam-note">
                Your registration will be reviewed by administrators.<br>
                You will receive an email once your account is approved.
            </p>
        </form>
    </div>

    {{-- ── Benefits sidebar ── --}}
    <div class="bam-benefits">
        <h2>Membership Benefits</h2>

        @php
        $benefits = [
            ['icon'=>'🤝', 'title'=>'Global Network',    'desc'=>'Connect with fellow professionals worldwide'],
            ['icon'=>'📚', 'title'=>'Resources',         'desc'=>'Access to real-time publications and research'],
            ['icon'=>'🎓', 'title'=>'Alumni Events',      'desc'=>'Attend exclusive reunions and networking events'],
            ['icon'=>'💼', 'title'=>'Career Services',    'desc'=>'Job postings and mentorship opportunities'],
            ['icon'=>'🏆', 'title'=>'Recognition',        'desc'=>'Celebrate achievements with the POBA community'],
            ['icon'=>'📰', 'title'=>'Exclusive Updates',  'desc'=>'Stay informed with the latest POBA news and updates'],
        ];
        @endphp

        @foreach($benefits as $b)
        <div class="bam-benefit-item">
            <div class="bam-benefit-icon" style="font-size:36px;line-height:1">{{ $b['icon'] }}</div>
            <div class="bam-benefit-title">{{ $b['title'] }}</div>
            <div class="bam-benefit-desc">{{ $b['desc'] }}</div>
        </div>
        @endforeach
    </div>

</div>

@if(session('blocked_modal'))
@php $blocked = session('blocked_modal'); @endphp
<div class="modal-overlay open" id="blockedModal" style="position:fixed;inset:0;background:rgba(0,0,0,0.55);z-index:9999;display:flex;align-items:center;justify-content:center;padding:16px;">
    <div style="background:#fff;border-radius:14px;max-width:480px;width:100%;box-shadow:0 20px 40px rgba(0,0,0,0.25);overflow:hidden;animation:popIn .2s ease;">
        <div style="background:{{ ($blocked['type'] ?? '') === 'rejected' ? '#fee2e2' : '#fef3c7' }};padding:20px 24px;border-bottom:1px solid {{ ($blocked['type'] ?? '') === 'rejected' ? '#fca5a5' : '#fcd34d' }};display:flex;align-items:center;gap:12px;">
            <div style="width:38px;height:38px;border-radius:50%;background:{{ ($blocked['type'] ?? '') === 'rejected' ? '#ef4444' : '#f59e0b' }};color:#fff;display:flex;align-items:center;justify-content:center;font-size:18px;font-weight:bold;flex-shrink:0;">
                !
            </div>
            <div>
                <h3 style="margin:0;font-size:17px;font-weight:700;color:{{ ($blocked['type'] ?? '') === 'rejected' ? '#991b1b' : '#92400e' }};">
                    {{ $blocked['title'] ?? 'Access Blocked' }}
                </h3>
                <span style="font-size:12px;color:#666;">Account Notice</span>
            </div>
        </div>
        <div style="padding:22px 24px;color:#333;font-size:14px;line-height:1.6;">
            <p style="margin:0 0 12px 0;">
                @if(($blocked['field'] ?? '') === 'ccp_no')
                    The account associated with CCP No. <strong>{{ old('ccp_no') }}</strong> has been <strong>{{ $blocked['type'] ?? 'restricted' }}</strong>.
                @elseif(($blocked['field'] ?? '') === 'phone_number')
                    The account associated with this phone number has been <strong>{{ $blocked['type'] ?? 'restricted' }}</strong>.
                @else
                    The account associated with this email address has been <strong>{{ $blocked['type'] ?? 'restricted' }}</strong>.
                @endif
            </p>
            @if(!empty($blocked['reason']))
            <div style="background:#f8fafc;border-left:4px solid {{ ($blocked['type'] ?? '') === 'rejected' ? '#ef4444' : '#f59e0b' }};padding:10px 14px;border-radius:4px;margin-bottom:14px;font-size:13px;color:#334155;">
                <strong>Reason:</strong> {{ $blocked['reason'] }}
            </div>
            @endif
            <p style="margin:0 0 8px 0;font-size:13px;color:#555;">
                Please contact support for assistance:
            </p>
            <div style="background:#f1f5f9;border-radius:8px;padding:12px;font-size:13px;display:flex;flex-direction:column;gap:6px;">
                @if(!empty($blocked['email']))
                <div><strong>Email:</strong> <a href="mailto:{{ $blocked['email'] }}" style="color:var(--teal,#0d9488);font-weight:600;text-decoration:none;">{{ $blocked['email'] }}</a></div>
                @endif
                @if(!empty($blocked['phone']))
                <div><strong>Phone:</strong> <a href="tel:{{ $blocked['phone'] }}" style="color:var(--teal,#0d9488);font-weight:600;text-decoration:none;">{{ $blocked['phone'] }}</a></div>
                @endif
            </div>
        </div>
        <div style="padding:14px 24px;background:#f8fafc;border-top:1px solid #e2e8f0;display:flex;justify-content:flex-end;">
            <button type="button" onclick="document.getElementById('blockedModal').remove()" style="padding:8px 20px;border:none;background:var(--teal,#0d9488);color:#fff;border-radius:6px;font-size:13px;font-weight:600;cursor:pointer;">
                Close
            </button>
        </div>
    </div>
</div>
@endif

{{-- ── Legal Modal Dialog (In-Page Review) ── --}}
<div class="modal-overlay" id="legalModal" style="display:none;position:fixed;inset:0;background:rgba(15,23,42,0.65);z-index:99999;align-items:flex-start;justify-content:center;overflow-y:auto;padding:40px 16px;box-sizing:border-box;backdrop-filter:blur(2px);">
    <div style="background:#fff;border-radius:14px;padding:26px 30px;width:720px;max-width:96vw;max-height:calc(100vh - 80px);overflow-y:auto;position:relative;margin:0 auto;box-shadow:0 20px 50px rgba(0,0,0,.35);">
        <button type="button" onclick="closeLegalModal()" style="position:absolute;top:16px;right:18px;background:none;border:none;font-size:24px;cursor:pointer;color:#64748b;line-height:1;">&times;</button>
        <div style="display:flex;align-items:center;gap:10px;margin-bottom:14px;">
            <div style="width:36px;height:36px;border-radius:50%;background:#e6f7f5;color:#0d9488;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>
            </div>
            <div>
                <h3 id="legalModalTitle" style="margin:0;font-size:18px;font-weight:700;color:#0f766e;"></h3>
                <span style="font-size:11px;color:#64748b;">Pakistan Ocean &amp; Bay Alumni (POBA)</span>
            </div>
        </div>
        <div id="legalModalBody" style="font-size:13.5px;color:#334155;line-height:1.7;max-height:50vh;overflow-y:auto;padding:14px 18px;background:#f8fafc;border-radius:8px;border:1px solid #e2e8f0;"></div>
        <div style="margin-top:16px;display:flex;justify-content:space-between;align-items:center;flex-wrap:wrap;gap:10px;">
            <a id="legalModalNewTab" href="#" target="_blank" style="font-size:12px;color:#0d9488;font-weight:600;text-decoration:underline;display:inline-flex;align-items:center;gap:4px;">
                <span>Open full page in new tab</span>
                <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6"/><polyline points="15 3 21 3 21 9"/><line x1="10" y1="14" x2="21" y2="3"/></svg>
            </a>
            <button type="button" onclick="closeLegalModal()" style="padding:8px 20px;background:#0d9488;color:#fff;border:none;border-radius:6px;font-size:13px;font-weight:700;cursor:pointer;">
                I Understand &amp; Close
            </button>
        </div>
    </div>
</div>

@push('scripts')
{{-- reCAPTCHA script removed --}}
<script>
const legalContent = {
    privacy: {
        title: 'Privacy Policy',
        url: '{{ route("privacy") }}',
        html: `
            <h4 style="margin:0 0 8px;color:#0f766e;font-size:15px;">Privacy Policy Summary</h4>
            <p><strong>1. Information Collection:</strong> POBA collects your academic details (batch, entry, CCP number), professional contact information, and verification documents strictly for managing the alumni community.</p>
            <p><strong>2. Member Privacy Controls:</strong> You control what fellow alumni see. Use the Privacy Settings chips on the registration form to hide your phone number, email address, city, or employer from the member directory.</p>
            <p><strong>3. Document Security:</strong> Uploaded CNIC documents are strictly confidential and accessible solely to authorized administrators for identity confirmation. They are never published or shared.</p>
            <p><strong>4. No Commercial Sharing:</strong> POBA does not sell or distribute personal data to third parties or advertisers.</p>
            <p style="margin-bottom:0;">For complete details, please click the link below to view the full Privacy Policy page.</p>
        `
    },
    terms: {
        title: 'Terms & Conditions',
        url: '{{ route("terms") }}',
        html: `
            <h4 style="margin:0 0 8px;color:#0f766e;font-size:15px;">Terms &amp; Conditions Summary</h4>
            <p><strong>1. Eligibility:</strong> Membership is reserved for authentic alumni of the cadet college institution. All submitted credentials must be accurate and truthful.</p>
            <p><strong>2. Verification &amp; Approval:</strong> Registrations undergo administrative review before membership privileges and directory access are activated.</p>
            <p><strong>3. Community Conduct:</strong> Members agree to interact with dignity, respect fellow alumni, honor member privacy settings, and refrain from spam or unauthorized commercial solicitation.</p>
            <p><strong>4. Account Integrity:</strong> Sharing or misrepresenting accounts is prohibited. Violations may result in account deactivation or removal.</p>
            <p style="margin-bottom:0;">For complete details, please click the link below to view the full Terms &amp; Conditions page.</p>
        `
    }
};

function openLegalModal(type, e) {
    if (e) e.preventDefault();
    const data = legalContent[type] || legalContent.privacy;
    document.getElementById('legalModalTitle').textContent = data.title;
    document.getElementById('legalModalBody').innerHTML = data.html;
    document.getElementById('legalModalNewTab').href = data.url;
    document.getElementById('legalModal').style.display = 'flex';
}

function closeLegalModal() {
    document.getElementById('legalModal').style.display = 'none';
}

document.addEventListener('keydown', e => {
    if (e.key === 'Escape') closeLegalModal();
});
document.getElementById('legalModal')?.addEventListener('click', function(e) {
    if (e.target === this) closeLegalModal();
});
// ── File picker: validate and show chosen filename in the upload area ────────
function showFile(input, labelId, nameId) {
    const label = document.getElementById(labelId);
    const name  = document.getElementById(nameId);
    const isCnic = (input && input.id === 'cnic_input') || (labelId && labelId.includes('cnic'));
    const area   = document.getElementById(isCnic ? 'cnic_file_area' : 'photo_file_area');
    const errorEl = document.getElementById(isCnic ? 'cnic_error' : 'photo_error');
    const serverError = document.getElementById(isCnic ? 'cnic_server_error' : 'photo_server_error');

    if (!input || !input.files || !input.files[0]) {
        return true;
    }

    const file = input.files[0];
    const fileName = file.name || '';
    const ext = fileName.includes('.') ? fileName.split('.').pop().toLowerCase() : '';
    const allowedExts = ['pdf', 'jpg', 'jpeg', 'png'];
    const maxSize = 5 * 1024 * 1024; // 5MB

    if (serverError) {
        serverError.style.display = 'none';
    }

    // Format validation: restrict to PDF, JPG, PNG
    const isUnsupported = !allowedExts.includes(ext) || (file.type && file.type.startsWith('video/'));
    if (isUnsupported) {
        input.value = '';
        if (label) label.textContent = 'Drag & Drop Files here or click to select files';
        if (name)  name.textContent = '';
        if (area)  area.classList.add('is-invalid');
        const msg = 'Invalid file format. Only PDF, JPG, and PNG are allowed.';
        if (errorEl) {
            errorEl.textContent = msg;
            errorEl.style.display = 'block';
        }
        return false;
    }

    // Size validation: max 5MB
    if (file.size > maxSize) {
        input.value = '';
        if (label) label.textContent = 'Drag & Drop Files here or click to select files';
        if (name)  name.textContent = '';
        if (area)  area.classList.add('is-invalid');
        const msg = 'File size exceeds 5MB limit. Please choose a smaller file.';
        if (errorEl) {
            errorEl.textContent = msg;
            errorEl.style.display = 'block';
        }
        return false;
    }

    // Valid file: clear any error state
    if (errorEl) {
        errorEl.textContent = '';
        errorEl.style.display = 'none';
    }
    if (area) {
        area.classList.remove('is-invalid');
    }

    let sizeStr = '';
    if (file.size < 1024) {
        sizeStr = file.size + ' B';
    } else if (file.size < 1024 * 1024) {
        sizeStr = (file.size / 1024).toFixed(1) + ' KB';
    } else {
        sizeStr = (file.size / (1024 * 1024)).toFixed(1) + ' MB';
    }

    if (label) label.textContent = '✅ ' + fileName;
    if (name)  name.textContent  = `${fileName} (${sizeStr})`;
    return true;
}

// ── Form submission safety check ─────────────────────────────────────────────
const regFormEl = document.getElementById('regForm');
if (regFormEl) {
    regFormEl.addEventListener('submit', function(e) {
        const cnicInput = document.getElementById('cnic_input');
        const photoInput = document.getElementById('photo_input');
        if (cnicInput && cnicInput.files && cnicInput.files[0]) {
            if (!showFile(cnicInput, 'cnic_label', 'cnic_name')) {
                e.preventDefault();
                document.getElementById('cnic_file_area')?.scrollIntoView({ behavior: 'smooth', block: 'center' });
                return false;
            }
        }
        if (photoInput && photoInput.files && photoInput.files[0]) {
            if (!showFile(photoInput, 'photo_label', 'photo_name')) {
                e.preventDefault();
                document.getElementById('photo_file_area')?.scrollIntoView({ behavior: 'smooth', block: 'center' });
                return false;
            }
        }
    });
}

// ── Session Keepalive: prevent 419 Page Expired during long form completion ───
setInterval(() => {
    fetch('/become-a-member', { method: 'HEAD', cache: 'no-store' }).catch(() => {});
}, 10 * 60 * 1000);

// ── Drag & Drop support for upload areas ──────────────────────────────────────
window.addEventListener('dragover', e => e.preventDefault());
window.addEventListener('drop', e => e.preventDefault());

['cnic_file_area', 'photo_file_area'].forEach(id => {
    const area = document.getElementById(id);
    if (!area) return;
    const input = id === 'cnic_file_area'
        ? document.getElementById('cnic_input')
        : document.getElementById('photo_input');
    const labelId = id === 'cnic_file_area' ? 'cnic_label' : 'photo_label';
    const nameId  = id === 'cnic_file_area' ? 'cnic_name'  : 'photo_name';

    let dragCounter = 0;

    area.addEventListener('dragenter', e => {
        e.preventDefault();
        e.stopPropagation();
        dragCounter++;
        area.classList.add('bam-dragover');
    });

    area.addEventListener('dragover', e => {
        e.preventDefault();
        e.stopPropagation();
        if (e.dataTransfer) {
            e.dataTransfer.dropEffect = 'copy';
        }
        area.classList.add('bam-dragover');
    });

    area.addEventListener('dragleave', e => {
        e.preventDefault();
        e.stopPropagation();
        dragCounter--;
        if (dragCounter <= 0) {
            dragCounter = 0;
            area.classList.remove('bam-dragover');
        }
    });

    area.addEventListener('drop', e => {
        e.preventDefault();
        e.stopPropagation();
        dragCounter = 0;
        area.classList.remove('bam-dragover');

        const dt = e.dataTransfer;
        if (dt && dt.files && dt.files.length) {
            try {
                const transfer = new DataTransfer();
                for (let i = 0; i < dt.files.length; i++) {
                    transfer.items.add(dt.files[i]);
                }
                input.files = transfer.files;
            } catch (err) {
                input.files = dt.files;
            }
            showFile(input, labelId, nameId);
        }
    });
});

// ── Privacy chip toggle: add/remove .checked class ───────────────────────────
function toggleChip(cb) {
    cb.closest('.bam-chip').classList.toggle('checked', cb.checked);
}

// ── On page load: highlight chips that were checked (after validation fail) ──
document.querySelectorAll('.bam-chip input[type="checkbox"]').forEach(cb => {
    cb.closest('.bam-chip').classList.toggle('checked', cb.checked);
});

// ── Phone number: dynamic flag, country label, maxlength, and placeholder ────
const phoneInput        = document.getElementById('phone_number');
const codeSelect        = document.getElementById('phone_code');
const phoneHint         = document.getElementById('phone_hint');
const phoneFlagEl       = document.getElementById('phone_flag');
const phoneFlagImg      = document.getElementById('phone_flag_img');
const phoneFlagFallback = document.getElementById('phone_flag_fallback');
const phoneCountryLabel = document.getElementById('phone_country_label');
const phoneCodeBadge    = document.getElementById('phone_code_badge');
const flagBaseUrl       = "{{ asset('images/flags') }}";

const phoneCountriesList = @json($phoneCountriesList);

const phoneCountries = {};
const countryNameToMeta = {};

phoneCountriesList.forEach(c => {
    const lowerName = (c.name || '').toLowerCase();
    const lowerIso  = (c.iso || '').toLowerCase();
    if (lowerName) countryNameToMeta[lowerName] = c;
    if (lowerIso)  countryNameToMeta[lowerIso]  = c;
    if (!phoneCountries[c.code]) {
        phoneCountries[c.code] = c;
    }
});

// Common country aliases
countryNameToMeta['usa'] = countryNameToMeta['united states'];
countryNameToMeta['uk'] = countryNameToMeta['united kingdom'];
countryNameToMeta['great britain'] = countryNameToMeta['united kingdom'];
countryNameToMeta['uae'] = countryNameToMeta['united arab emirates'];
countryNameToMeta['ksa'] = countryNameToMeta['saudi arabia'];

function updatePhoneLimits(forcedMeta) {
    if (!codeSelect) return;
    const selectedOpt = codeSelect.selectedOptions && codeSelect.selectedOptions[0];
    const meta = forcedMeta || (selectedOpt ? {
        name: selectedOpt.dataset.name || 'Country',
        iso: (selectedOpt.dataset.iso || 'pk').toLowerCase(),
        flag: selectedOpt.dataset.flag || '🇵🇰',
        maxLen: parseInt(selectedOpt.dataset.maxlen) || 15,
        placeholder: selectedOpt.dataset.placeholder || 'Enter phone number',
        code: codeSelect.value
    } : (phoneCountries[codeSelect.value] || { name: 'Pakistan', iso: 'pk', flag: '🇵🇰', maxLen: 10, placeholder: '300 1234567', code: '+92' }));

    const isoLower = (meta.iso || 'pk').toLowerCase();
    if (phoneFlagImg) {
        phoneFlagImg.src = flagBaseUrl + '/' + isoLower + '.png';
        phoneFlagImg.alt = isoLower.toUpperCase();
        phoneFlagImg.style.display = 'inline-block';
    }
    if (phoneCountryLabel) {
        phoneCountryLabel.textContent = `${meta.name} (${isoLower.toUpperCase()})`;
    }
    if (phoneCodeBadge) {
        phoneCodeBadge.textContent = codeSelect.value || meta.code;
    }
    if (phoneHint) {
        const len = meta.maxLen || meta.max || 10;
        phoneHint.textContent = `${meta.name} (${isoLower.toUpperCase()}) — Enter up to ${len} digits`;
    }
    if (phoneInput) {
        const maxLen = meta.maxLen || meta.max || 15;
        phoneInput.placeholder = meta.placeholder || meta.ph || 'Enter phone number';
        phoneInput.maxLength = maxLen;
        if (phoneInput.value.length > maxLen) {
            phoneInput.value = phoneInput.value.slice(0, maxLen);
        }
    }
}

if (codeSelect) {
    codeSelect.addEventListener('change', () => updatePhoneLimits(null));
    updatePhoneLimits(null);
}

function syncPhoneWithSelectedCountry(countryName) {
    if (!countryName || !codeSelect) return;
    const norm = countryName.trim().toLowerCase();
    const meta = countryNameToMeta[norm];
    if (meta) {
        let opt = Array.from(codeSelect.options).find(o => (o.dataset.name || '').toLowerCase() === norm);
        if (!opt) {
            opt = Array.from(codeSelect.options).find(o => o.value === meta.code);
        }
        if (opt) {
            opt.selected = true;
            codeSelect.value = opt.value;
        }
        updatePhoneLimits({
            name: meta.name || countryName,
            iso: meta.iso,
            flag: meta.flag,
            maxLen: meta.max,
            placeholder: meta.ph,
            code: meta.code
        });
    }
}

phoneInput.addEventListener('input', function () {
    this.value = this.value.replace(/\D/g, '');
    if (this.value.length > this.maxLength) {
        this.value = this.value.slice(0, this.maxLength);
    }
});

phoneInput.addEventListener('paste', function (e) {
    e.preventDefault();
    const pasted = (e.clipboardData || window.clipboardData).getData('text');
    const digits = pasted.replace(/\D/g, '');
    const maxLen = parseInt(this.maxLength) || 15;
    const final = digits.slice(0, maxLen);
    this.value = final;
    this.dispatchEvent(new Event('input'));
});

// ── Country → City: load cities dynamically for the selected country ──────────
const countrySelect = document.getElementById('currentCountry');
const citySelect     = document.getElementById('currentCity');
const oldCity         = @json(old('current_city'));

function loadCitiesForSelectedCountry(preselectCity) {
    const selectedOption = countrySelect.options[countrySelect.selectedIndex];
    const countryId = selectedOption ? selectedOption.dataset.id : null;

    citySelect.innerHTML = '';
    if (!countryId) {
        citySelect.innerHTML = '<option value="">Select Country First</option>';
        return;
    }

    citySelect.innerHTML = '<option value="">Loading cities...</option>';
    citySelect.disabled = true;

    fetch('/cities-by-country/' + countryId)
        .then(res => res.json())
        .then(cities => {
            citySelect.innerHTML = '<option value="">Select City</option>';
            cities.forEach(city => {
                const opt = document.createElement('option');
                opt.value = city;
                opt.textContent = city;
                if (preselectCity && city === preselectCity) opt.selected = true;
                citySelect.appendChild(opt);
            });
            citySelect.disabled = false;
        })
        .catch(() => {
            citySelect.innerHTML = '<option value="">Could not load cities</option>';
            citySelect.disabled = false;
        });
}

countrySelect.addEventListener('change', function() {
    loadCitiesForSelectedCountry(null);
    syncPhoneWithSelectedCountry(this.value);
});

// Repopulate on page load if a country was already selected (validation-failed redisplay)
if (countrySelect && countrySelect.value) {
    loadCitiesForSelectedCountry(oldCity);
    @if(!old('phone_code'))
    syncPhoneWithSelectedCountry(countrySelect.value);
    @endif
}
</script>
@endpush
@endsection