{{-- FILE: resources/views/customer/member/register.blade.php --}}
@extends('layouts.app')
@section('title','Become a Member - POBA')
@section('content')

<style>
/* ── Page header ── */
.bam-header      { text-align:center; padding:40px 20px 20px; }
.bam-header h1   { font-size:28px; font-weight:700; color:var(--teal,#0d9488); margin-bottom:4px; }

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
.bam-error       { font-size:11px; color:#e53e3e; margin-top:2px; }
.bam-textarea    { resize:vertical; }

/* ── Phone wrap ── */
.bam-phone-wrap  { display:flex; }
.bam-phone-flag  { display:flex; align-items:center; gap:4px; background:#fff;
                   border:1.5px solid #e2e8f0; border-right:none;
                   border-radius:8px 0 0 8px; padding:10px 10px;
                   font-size:13px; white-space:nowrap; }
.bam-phone-flag select { border:none; background:transparent; font-size:13px; outline:none; }
.bam-phone-num   { border-radius:0 8px 8px 0 !important; }

/* ── File upload ── */
.bam-file-area   { background:#fff; border:1.5px dashed #b2d8d8; border-radius:8px;
                   padding:14px 16px; display:flex; align-items:center;
                   gap:10px; cursor:pointer; transition:border .2s; }
.bam-file-area:hover { border-color:#0d9488; }
.bam-file-area.is-invalid { border-color:#e53e3e; }
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
</div>

<div class="bam-wrap">

    {{-- ── Registration Form ── --}}
    <div class="bam-card">
        <div class="bam-card-title">Alumni Registration Form</div>

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

            {{-- Current Country (Dropdown) + Current City (Dropdown) --}}
            <div class="bam-row bam-row-2">
                <div class="bam-group">
                    <label class="bam-label">Current Country <span>*</span></label>
                    <select name="current_country" class="bam-select {{ $errors->has('current_country') ? 'is-invalid' : '' }}" required>
                        <option value="">Select Country</option>
                        @php
                            $countries = [
                                'Pakistan', 'Afghanistan', 'Bangladesh', 'China', 'India',
                                'Iran', 'Iraq', 'Saudi Arabia', 'UAE', 'UK', 'USA',
                                'Canada', 'Australia', 'Germany', 'France', 'Turkey',
                                'Egypt', 'South Africa', 'Nigeria', 'Japan', 'South Korea',
                                'Malaysia', 'Singapore', 'Italy', 'Spain', 'Netherlands',
                                'Switzerland', 'Sweden', 'Norway', 'Denmark', 'Belgium',
                                'Austria', 'Greece', 'Portugal', 'Poland', 'Ukraine',
                                'Russia', 'Brazil', 'Argentina', 'Mexico', 'Colombia'
                            ];
                        @endphp
                        @foreach($countries as $c)
                        <option value="{{ $c }}" {{ old('current_country')==$c ? 'selected' : '' }}>{{ $c }}</option>
                        @endforeach
                    </select>
                    @error('current_country')<div class="bam-error">{{ $message }}</div>@enderror
                </div>
                <div class="bam-group">
                    <label class="bam-label">Current City <span>*</span></label>
                    <select name="current_city" class="bam-select {{ $errors->has('current_city') ? 'is-invalid' : '' }}" required>
                        <option value="">Select City</option>
                        @php
                            $cities = [
                                'Karachi', 'Lahore', 'Islamabad', 'Rawalpindi', 'Peshawar',
                                'Quetta', 'Faisalabad', 'Multan', 'Hyderabad', 'Gujranwala',
                                'Sialkot', 'Sukkur', 'Jhelum', 'Sargodha', 'Bahawalpur',
                                'Mardan', 'Mingora', 'Dera Ghazi Khan', 'Rahim Yar Khan',
                                'Dubai', 'Abu Dhabi', 'London', 'New York', 'Toronto',
                                'Sydney', 'Berlin', 'Paris', 'Tokyo', 'Seoul',
                                'Kuala Lumpur', 'Singapore', 'Istanbul', 'Cairo', 'Riyadh',
                                'Jeddah', 'Mumbai', 'Delhi', 'Dhaka', 'Kabul'
                            ];
                        @endphp
                        @foreach($cities as $ct)
                        <option value="{{ $ct }}" {{ old('current_city')==$ct ? 'selected' : '' }}>{{ $ct }}</option>
                        @endforeach
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

            {{-- Phone Number (with dynamic maxlength) --}}
            <div class="bam-row bam-row-1">
                <div class="bam-group">
                    <label class="bam-label">Phone Number <span>*</span></label>
                    <div class="bam-phone-wrap">
                        <div class="bam-phone-flag">
                            🇵🇰
                            <select name="phone_code" id="phone_code">
                                <option value="+92" {{ old('phone_code','+92')=='+92' ? 'selected' : '' }}>+92</option>
                                <option value="+1"  {{ old('phone_code','+92')=='+1' ? 'selected' : '' }}>+1</option>
                                <option value="+44" {{ old('phone_code','+92')=='+44' ? 'selected' : '' }}>+44</option>
                                <option value="+971"{{ old('phone_code','+92')=='+971' ? 'selected' : '' }}>+971</option>
                                <option value="+966"{{ old('phone_code','+92')=='+966' ? 'selected' : '' }}>+966</option>
                                <option value="+91" {{ old('phone_code','+92')=='+91' ? 'selected' : '' }}>+91</option>
                                <option value="+86" {{ old('phone_code','+92')=='+86' ? 'selected' : '' }}>+86</option>
                                <option value="+81" {{ old('phone_code','+92')=='+81' ? 'selected' : '' }}>+81</option>
                                <option value="+61" {{ old('phone_code','+92')=='+61' ? 'selected' : '' }}>+61</option>
                                <option value="+49" {{ old('phone_code','+92')=='+49' ? 'selected' : '' }}>+49</option>
                                <option value="+33" {{ old('phone_code','+92')=='+33' ? 'selected' : '' }}>+33</option>
                                <option value="+39" {{ old('phone_code','+92')=='+39' ? 'selected' : '' }}>+39</option>
                                <option value="+34" {{ old('phone_code','+92')=='+34' ? 'selected' : '' }}>+34</option>
                                <option value="+55" {{ old('phone_code','+92')=='+55' ? 'selected' : '' }}>+55</option>
                                <option value="+27" {{ old('phone_code','+92')=='+27' ? 'selected' : '' }}>+27</option>
                            </select>
                        </div>
                        <input type="text" name="phone_number"
                               id="phone_number"
                               class="bam-input bam-phone-num {{ $errors->has('phone_number') ? 'is-invalid' : '' }}"
                               placeholder="Enter phone number"
                               value="{{ old('phone_number') }}"
                               required
                               maxlength="15"
                               oninput="this.value = this.value.replace(/\D/g,'')">
                    </div>
                    @error('phone_number')<div class="bam-error">{{ $message }}</div>@enderror
                    <div id="phone_hint" style="font-size:11px;color:#888;margin-top:3px;">Enter digits only</div>
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
                    @error('cnic_file')<div class="bam-error">{{ $message }}</div>@enderror
                </div>
            </div>

            {{-- Profile Photo --}}
            <div class="bam-row bam-row-1">
                <div class="bam-group">
                    <label class="bam-label">Profile Photo</label>
                    <div class="bam-file-area" onclick="document.getElementById('photo_input').click()">
                        <div class="bam-file-icon">🖼</div>
                        <div class="bam-file-txt">
                            <strong id="photo_label">Drag & Drop Files here or click to select files</strong>
                            JPG or PNG — max 5MB
                        </div>
                    </div>
                    <input type="file" id="photo_input" name="profile_photo" class="bam-file-input"
                           accept=".jpg,.jpeg,.png" onchange="showFile(this,'photo_label','photo_name')">
                    <div id="photo_name" class="bam-file-name"></div>
                    @error('profile_photo')<div class="bam-error">{{ $message }}</div>@enderror
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

            {{-- Consent checkboxes --}}
            <div class="bam-row bam-row-1">
                <div>
                    <label class="bam-consent">
                        <input type="checkbox" name="consent_sharing" value="1"
                               {{ old('consent_sharing') ? 'checked' : '' }} required>
                        I consent to sharing my details with other POBA alumni.*
                        Your information will be shared with alumni according to your privacy settings above.
                    </label>
                    @error('consent_sharing')
                    <div class="bam-consent-err">{{ $message }}</div>
                    @enderror

                    <label class="bam-consent">
                        <input type="checkbox" name="agree_terms" value="1"
                               {{ old('agree_terms') ? 'checked' : '' }} required>
                        I agree to the <a href="#">Terms & Conditions</a> and <a href="#">Privacy Policy</a>.*
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

@push('scripts')
{{-- reCAPTCHA script removed --}}
<script>
// ── File picker: show chosen filename in the upload area ──────────────────────
function showFile(input, labelId, nameId) {
    const label = document.getElementById(labelId);
    const name  = document.getElementById(nameId);
    if (input.files && input.files[0]) {
        label.textContent = '✅ ' + input.files[0].name;
        name.textContent  = '';
    }
}

// ── Privacy chip toggle: add/remove .checked class ───────────────────────────
function toggleChip(cb) {
    cb.closest('.bam-chip').classList.toggle('checked', cb.checked);
}

// ── On page load: highlight chips that were checked (after validation fail) ──
document.querySelectorAll('.bam-chip input[type="checkbox"]').forEach(cb => {
    cb.closest('.bam-chip').classList.toggle('checked', cb.checked);
});

// ── Phone number: dynamic maxlength and hint ──────────────────────────────────
const phoneInput = document.getElementById('phone_number');
const codeSelect = document.getElementById('phone_code');
const phoneHint  = document.getElementById('phone_hint');

function updatePhoneLimits() {
    const code = codeSelect.value;
    let maxLen, hint;
    const lengths = {
        '+92': 10,  // Pakistan
        '+1':  10,  // USA/Canada
        '+44': 10,  // UK
        '+971': 9,  // UAE
        '+966': 9,  // Saudi
        '+91': 10,  // India
        '+86': 11,  // China
        '+81': 10,  // Japan
        '+61': 9,   // Australia
        '+49': 10,  // Germany
        '+33': 9,   // France
        '+39': 10,  // Italy
        '+34': 9,   // Spain
        '+55': 11,  // Brazil
        '+27': 9,   // South Africa
    };
    maxLen = lengths[code] || 15;
    hint   = `Enter up to ${maxLen} digits`;
    phoneInput.maxLength = maxLen;
    phoneHint.textContent = hint;
    if (phoneInput.value.length > maxLen) {
        phoneInput.value = phoneInput.value.slice(0, maxLen);
    }
}

codeSelect.addEventListener('change', updatePhoneLimits);
updatePhoneLimits();

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
</script>
@endpush
@endsection