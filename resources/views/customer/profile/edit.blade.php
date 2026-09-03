{{-- FILE: resources/views/customer/profile/edit.blade.php --}}
@extends('layouts.app')
@section('title', 'My Profile - POBA')
@section('content')

<style>
/* ── Layout ── */
.pf-wrap         { display:flex; gap:32px; align-items:flex-start; flex-wrap:wrap;
                   max-width:1100px; margin:0 auto; padding:40px 20px; }

/* ── Sidebar ── */
.pf-sidebar      { min-width:200px; max-width:220px; }
.pf-avatar-wrap  { position:relative; width:110px; margin-bottom:14px; }
.pf-avatar       { width:110px; height:110px; border-radius:50%; object-fit:cover;
                   display:block; border:3px solid #0d9488; }
.pf-avatar-edit  { position:absolute; bottom:4px; right:4px; width:28px; height:28px;
                   background:#0d9488; border-radius:50%; border:2px solid #fff;
                   display:flex; align-items:center; justify-content:center;
                   cursor:pointer; color:#fff; font-size:13px; }
.pf-name         { font-size:16px; font-weight:700; margin-bottom:4px; }
.pf-email        { font-size:12px; color:#888; margin-bottom:20px; }

/* Sidebar nav */
.pf-nav          { display:flex; flex-direction:column; gap:4px; }
.pf-nav-btn      { display:flex; align-items:center; gap:10px; padding:10px 14px;
                   border-radius:8px; border:none; background:transparent;
                   font-size:13px; font-weight:500; cursor:pointer; text-align:left;
                   color:#444; width:100%; transition:all .2s; }
.pf-nav-btn:hover   { background:#f0fdfc; color:#0d9488; }
.pf-nav-btn.active  { background:#0d9488; color:#fff; }
.pf-nav-btn svg     { flex-shrink:0; }

/* ── Main card ── */
.pf-card         { flex:1; min-width:0; background:#f0f9f9; border-radius:16px; padding:32px; }
.pf-section      { display:none; }
.pf-section.active { display:block; }
.pf-section-title { font-size:18px; font-weight:700; margin-bottom:24px;
                    padding-bottom:12px; border-bottom:1px solid #d1ece9; }

/* ── Form elements (reuse ap- pattern) ── */
.pf-row          { display:grid; gap:16px; margin-bottom:16px; }
.pf-row-2        { grid-template-columns:1fr 1fr; }
.pf-row-1        { grid-template-columns:1fr; }
.pf-group        { display:flex; flex-direction:column; gap:5px; }
.pf-label        { font-size:12px; color:#555; font-weight:500; }
.pf-input, .pf-select, .pf-textarea {
    background:#fff; border:1px solid #e2e8f0; border-radius:8px;
    padding:10px 13px; font-size:13px; color:#222; width:100%;
    box-sizing:border-box; outline:none; transition:border .2s;
}
.pf-input:focus, .pf-select:focus, .pf-textarea:focus { border-color:#0d9488; }
.pf-textarea     { resize:vertical; }

/* Phone wrap */
.pf-phone-wrap   { display:flex; }
.pf-phone-flag   { display:flex; align-items:center; gap:4px; background:#fff;
                   border:1px solid #e2e8f0; border-right:none;
                   border-radius:8px 0 0 8px; padding:10px 10px;
                   font-size:13px; white-space:nowrap; }
.pf-phone-flag select { border:none; background:transparent; font-size:13px; outline:none; }
.pf-phone-num    { border-radius:0 8px 8px 0 !important; }

/* File row */
.pf-file-row     { display:flex; align-items:center; background:#fff;
                   border:1px solid #e2e8f0; border-radius:8px;
                   padding:10px 13px; font-size:13px; color:#555; gap:10px; }
.pf-file-row span { flex:1; overflow:hidden; text-overflow:ellipsis; white-space:nowrap; }
.pf-file-eye     { background:none; border:none; cursor:pointer; color:#0d9488;
                   padding:0; flex-shrink:0; }
.pf-file-change  { font-size:12px; color:#0d9488; font-weight:600; cursor:pointer;
                   white-space:nowrap; text-decoration:underline; }

/* Privacy chips */
.pf-chips        { display:flex; flex-wrap:wrap; gap:8px; }
.pf-chip         { display:flex; align-items:center; gap:5px; background:#fff;
                   border:1px solid #e2e8f0; border-radius:20px; padding:5px 14px;
                   font-size:12px; cursor:pointer; user-select:none; }
.pf-chip input   { accent-color:#0d9488; width:13px; height:13px; }

/* Alerts */
.pf-alert-success { background:#d1fae5; border:1px solid #6ee7b7; color:#065f46;
                    border-radius:8px; padding:12px 16px; font-size:13px;
                    margin-bottom:20px; display:flex; align-items:center; gap:8px; }
.pf-alert-error   { background:#fee2e2; border:1px solid #fca5a5; color:#991b1b;
                    border-radius:8px; padding:12px 16px; font-size:13px; margin-bottom:20px; }

/* Password strength */
.pw-strength-bar { height:4px; border-radius:4px; background:#e2e8f0;
                   margin-top:6px; overflow:hidden; }
.pw-strength-fill{ height:100%; border-radius:4px; width:0; transition:width .3s, background .3s; }
.pw-strength-txt { font-size:11px; color:#888; margin-top:3px; }

/* Buttons */
.btn-pf-save     { padding:10px 32px; background:#0d9488; color:#fff; border:none;
                   border-radius:8px; font-size:14px; font-weight:600; cursor:pointer; }
.btn-pf-save:hover { background:#0b7a70; }

/* Lightbox */
.lb-overlay      { display:none; position:fixed; inset:0; background:rgba(0,0,0,.82);
                   z-index:3000; align-items:center; justify-content:center;
                   flex-direction:column; gap:14px; }
.lb-overlay.open { display:flex; }
.lb-inner img    { max-width:88vw; max-height:82vh; border-radius:10px; object-fit:contain; }
.lb-close        { position:fixed; top:18px; right:22px; background:rgba(255,255,255,.15);
                   border:none; color:#fff; font-size:22px; width:38px; height:38px;
                   border-radius:50%; cursor:pointer; display:flex;
                   align-items:center; justify-content:center; }
.lb-close:hover  { background:rgba(255,255,255,.3); }
.lb-label        { color:#ccc; font-size:13px; }

@media(max-width:700px){
    .pf-row-2 { grid-template-columns:1fr; }
    .pf-sidebar { max-width:100%; display:flex; flex-wrap:wrap; gap:16px; align-items:center; }
    .pf-nav     { flex-direction:row; flex-wrap:wrap; }
}
</style>

<div class="pf-wrap">

    {{-- ── Sidebar ── --}}
    <div class="pf-sidebar">
        {{-- Avatar --}}
        <div class="pf-avatar-wrap">
            <img id="avatarPreview"
                 src="{{ $alumni->profile_photo ? asset('storage/'.$alumni->profile_photo) : 'https://placehold.co/110x110/1a7a7a/fff?text='.urlencode(substr($alumni->full_name,0,1)) }}"
                 alt="{{ $alumni->full_name }}" class="pf-avatar">
            <label for="quickPhotoInput" class="pf-avatar-edit" title="Change photo">✎</label>
        </div>
        <div class="pf-name">{{ $alumni->full_name }}</div>
        <div class="pf-email">{{ $alumni->email }}</div>

        {{-- Nav --}}
        <nav class="pf-nav">
            <button class="pf-nav-btn active" onclick="switchTab('info', this)">
                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20 21v-2a4 4 0 00-4-4H8a4 4 0 00-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                Personal Info
            </button>
            <button class="pf-nav-btn" onclick="switchTab('work', this)">
                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="2" y="7" width="20" height="14" rx="2"/><path d="M16 7V5a2 2 0 00-2-2h-4a2 2 0 00-2 2v2"/></svg>
                Work & Education
            </button>
            <button class="pf-nav-btn" onclick="switchTab('documents', this)">
                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8z"/><polyline points="14 2 14 8 20 8"/></svg>
                Documents
            </button>
            <button class="pf-nav-btn" onclick="switchTab('privacy', this)">
                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>
                Privacy
            </button>
            <button class="pf-nav-btn" onclick="switchTab('password', this)">
                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="11" width="18" height="11" rx="2"/><path d="M7 11V7a5 5 0 0110 0v4"/></svg>
                Change Password
            </button>
        </nav>
    </div>

    {{-- ── Main Card ── --}}
    <div class="pf-card">

        {{-- ══ TAB 1: Personal Info ══ --}}
        <div class="pf-section active" id="tab-info">
            <div class="pf-section-title">Personal Information</div>



            @if($errors->any())
            <div class="pf-alert-error">❌ {{ $errors->first() }}</div>
            @endif

            <form method="POST" action="{{ route('profile.update') }}" enctype="multipart/form-data" id="infoForm">
                @csrf @method('PUT')

                {{-- Hidden quick-photo input (triggered from avatar pencil icon) --}}
                <input type="file" id="quickPhotoInput" name="profile_photo"
                       accept="image/*" style="display:none" onchange="previewAvatar(this)">

                <div class="pf-row pf-row-2">
                    <div class="pf-group">
                        <label class="pf-label">Full Name *</label>
                        <input type="text" name="full_name" class="pf-input" value="{{ old('full_name', $alumni->full_name) }}" required>
                    </div>
                    <div class="pf-group">
                        <label class="pf-label">Email ID</label>
                        <input type="email" class="pf-input" value="{{ $alumni->email }}" readonly
                               style="background:#f8f8f8;cursor:not-allowed" title="Email cannot be changed">
                        <span style="font-size:11px;color:#aaa">Contact admin to change email</span>
                    </div>
                </div>

                <div class="pf-row pf-row-2">
                    <div class="pf-group">
                        <label class="pf-label">Phone Number *</label>
                        <div class="pf-phone-wrap">
                            <div class="pf-phone-flag">
                                🇵🇰
                                <select name="phone_code">
                                    <option value="+92" {{ str_starts_with($alumni->phone_number ?? '', '+92') ? 'selected' : '' }}>+92</option>
                                    <option value="+1"  {{ str_starts_with($alumni->phone_number ?? '', '+1')  ? 'selected' : '' }}>+1</option>
                                    <option value="+44" {{ str_starts_with($alumni->phone_number ?? '', '+44') ? 'selected' : '' }}>+44</option>
                                    <option value="+971"{{ str_starts_with($alumni->phone_number ?? '', '+971')? 'selected' : '' }}>+971</option>
                                    <option value="+966"{{ str_starts_with($alumni->phone_number ?? '', '+966')? 'selected' : '' }}>+966</option>
                                </select>
                            </div>
                            <input type="text" name="phone_number" class="pf-input pf-phone-num"
                                   value="{{ old('phone_number', $alumni->phone_number) }}" required>
                        </div>
                    </div>
                    <div class="pf-group">
                        <label class="pf-label">Current City *</label>
                        <input type="text" name="current_city" class="pf-input"
                               value="{{ old('current_city', $alumni->current_city) }}" required>
                    </div>
                </div>

                <div class="pf-row pf-row-2">
                    <div class="pf-group">
                        <label class="pf-label">Current Country *</label>
                        <input type="text" name="current_country" class="pf-input"
                               value="{{ old('current_country', $alumni->current_country) }}" required>
                    </div>
                    <div class="pf-group">
                        <label class="pf-label">Batch *</label>
                        <select name="entry" class="pf-select" required>
                            <option value="">Select Batch</option>
                            @foreach(range(1,50) as $e)
                            <option value="{{ $e }}" {{ old('entry', $alumni->entry)==$e ? 'selected' : '' }}>Batch {{ $e }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="pf-row pf-row-2">
                    <div class="pf-group">
                        <label class="pf-label">Class Year (Class of) *</label>
                        <select name="class_year" class="pf-select" required>
                            <option value="">Select Class Year</option>
                            @foreach(range(date('Y'), 1947, -1) as $y)
                            <option value="{{ $y }}" {{ old('class_year', $alumni->class_year)==$y ? 'selected' : '' }}>{{ $y }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="pf-group">
                        <label class="pf-label">CCP No. *</label>
                        <input type="text" name="ccp_no" class="pf-input"
                               value="{{ old('ccp_no', $alumni->ccp_no) }}" required>
                    </div>
                </div>

                <div class="pf-row pf-row-1">
                    <div class="pf-group">
                        <label class="pf-label">House *</label>
                        <select name="house" class="pf-select">
                            @foreach(['Jinnah','Iqbal','Liaquat','Ayub','Ranjit'] as $h)
                            <option value="{{ $h }}" {{ old('house', $alumni->house)==$h ? 'selected' : '' }}>{{ $h }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="pf-row pf-row-1">
                    <div class="pf-group">
                        <label class="pf-label">Achievements</label>
                        <textarea name="achievements" class="pf-textarea" rows="4">{{ old('achievements', $alumni->achievements) }}</textarea>
                    </div>
                </div>

                <button type="submit" class="btn-pf-save">Save Changes</button>
            </form>
        </div>

        {{-- ══ TAB 2: Work & Education ══ --}}
        <div class="pf-section" id="tab-work">
            <div class="pf-section-title">Work & Education</div>



            @if($errors->any())
            <div class="pf-alert-error">❌ {{ $errors->first() }}</div>
            @endif

            <form method="POST" action="{{ route('profile.update') }}" enctype="multipart/form-data">
                @csrf @method('PUT')

                <div class="pf-row pf-row-2">
                    <div class="pf-group">
                        <label class="pf-label">Education *</label>
                        <select name="education" class="pf-select">
                            @foreach(['Matric','Intermediate','Bachelors','Masters','PhD'] as $ed)
                            <option value="{{ $ed }}" {{ old('education', $alumni->education)==$ed ? 'selected' : '' }}>{{ $ed }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="pf-group">
                        <label class="pf-label">Field of Study *</label>
                        <input type="text" name="field_of_study" class="pf-input"
                               value="{{ old('field_of_study', $alumni->field_of_study) }}" required>
                    </div>
                </div>

                <div class="pf-row pf-row-2">
                    <div class="pf-group">
                        <label class="pf-label">Field of Work *</label>
                        <input type="text" name="field_of_work" class="pf-input"
                               value="{{ old('field_of_work', $alumni->field_of_work) }}" required>
                    </div>
                    <div class="pf-group">
                        <label class="pf-label">Current Designation</label>
                        <input type="text" name="current_designation" class="pf-input"
                               value="{{ old('current_designation', $alumni->current_designation) }}">
                    </div>
                </div>

                <div class="pf-row pf-row-1">
                    <div class="pf-group">
                        <label class="pf-label">Current Organization</label>
                        <input type="text" name="current_organization" class="pf-input"
                               value="{{ old('current_organization', $alumni->current_organization) }}">
                    </div>
                </div>

                <button type="submit" class="btn-pf-save">Save Changes</button>
            </form>
        </div>

        {{-- ══ TAB 3: Documents ══ --}}
        <div class="pf-section" id="tab-documents">
            <div class="pf-section-title">Documents</div>



            @if($errors->any())
            <div class="pf-alert-error">❌ {{ $errors->first() }}</div>
            @endif

            <form method="POST" action="{{ route('profile.update') }}" enctype="multipart/form-data">
                @csrf @method('PUT')

                {{-- Profile Photo --}}
                <div class="pf-row pf-row-1" style="margin-bottom:24px">
                    <div class="pf-group">
                        <label class="pf-label">Profile Photo</label>
                        <div class="pf-file-row">
                            <span>{{ $alumni->profile_photo ? basename($alumni->profile_photo) : 'No file uploaded' }}</span>
                            @if($alumni->profile_photo)
                            <button type="button" class="pf-file-eye" title="View"
                                    onclick="openLb('{{ asset('storage/'.$alumni->profile_photo) }}', 'Profile Photo', '{{ pathinfo($alumni->profile_photo, PATHINFO_EXTENSION) }}')">
                                <svg width="18" height="18" viewBox="0 0 24 24" fill="none"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8S1 12 1 12z" stroke="#0d9488" stroke-width="1.8"/><circle cx="12" cy="12" r="3" stroke="#0d9488" stroke-width="1.8"/></svg>
                            </button>
                            @endif
                            <label class="pf-file-change" for="doc_photo_input">Change</label>
                        </div>
                        <input type="file" id="doc_photo_input" name="profile_photo"
                               accept="image/*" style="display:none"
                               onchange="showFileName(this, 'doc_photo_name')">
                        <span id="doc_photo_name" style="font-size:12px;color:#0d9488;margin-top:4px"></span>
                    </div>
                </div>

                {{-- CNIC --}}
                <div class="pf-row pf-row-1" style="margin-bottom:24px">
                    <div class="pf-group">
                        <label class="pf-label">CNIC / ID Document</label>
                        <div class="pf-file-row">
                            <span>{{ $alumni->cnic_file ? basename($alumni->cnic_file) : 'No file uploaded' }}</span>
                            @if($alumni->cnic_file)
                            <button type="button" class="pf-file-eye" title="View"
                                    onclick="openLb('{{ asset('storage/'.$alumni->cnic_file) }}', 'CNIC Document', '{{ pathinfo($alumni->cnic_file, PATHINFO_EXTENSION) }}')">
                                <svg width="18" height="18" viewBox="0 0 24 24" fill="none"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8S1 12 1 12z" stroke="#0d9488" stroke-width="1.8"/><circle cx="12" cy="12" r="3" stroke="#0d9488" stroke-width="1.8"/></svg>
                            </button>
                            @endif
                            <label class="pf-file-change" for="doc_cnic_input">Change</label>
                        </div>
                        <input type="file" id="doc_cnic_input" name="cnic_file"
                               accept="image/*,.pdf" style="display:none"
                               onchange="showFileName(this, 'doc_cnic_name')">
                        <span id="doc_cnic_name" style="font-size:12px;color:#0d9488;margin-top:4px"></span>
                    </div>
                </div>

                <button type="submit" class="btn-pf-save">Save Documents</button>
            </form>
        </div>

        {{-- ══ TAB 4: Privacy ══ --}}
        <div class="pf-section" id="tab-privacy">
            <div class="pf-section-title">Privacy Settings</div>
            <p style="font-size:13px;color:#666;margin-bottom:20px">
                Choose which fields are hidden from other alumni when they view your profile.
            </p>



            @if($errors->any())
            <div class="pf-alert-error">❌ {{ $errors->first() }}</div>
            @endif

            <form method="POST" action="{{ route('profile.update') }}" enctype="multipart/form-data">
                @csrf @method('PUT')

                @php
                    $privacyFields  = ['Email Address','City','Phone Number','Designation','Organization','Field of Study','Field of Work'];
                    $hiddenFields   = is_array($alumni->privacy_settings)
                                      ? $alumni->privacy_settings
                                      : json_decode($alumni->privacy_settings ?? '[]', true);
                @endphp

                <div class="pf-chips" style="margin-bottom:28px">
                    @foreach($privacyFields as $pf)
                    <label class="pf-chip">
                        <input type="checkbox" name="privacy_hide[]" value="{{ $pf }}"
                               {{ in_array($pf, $hiddenFields ?? []) ? 'checked' : '' }}>
                        {{ $pf }}
                    </label>
                    @endforeach
                </div>

                <p style="font-size:12px;color:#aaa;margin-bottom:20px">
                    ✔ Checked = hidden from other alumni &nbsp;|&nbsp; Unchecked = visible
                </p>

                <button type="submit" class="btn-pf-save">Save Privacy Settings</button>
            </form>
        </div>

        {{-- ══ TAB 5: Change Password ══ --}}
        <div class="pf-section" id="tab-password">
            <div class="pf-section-title">Change Password</div>

            @if(session('password_success'))
            <div class="pf-alert-success">✅ {{ session('password_success') }}</div>
            @endif

            @if($errors->has('current_password') || $errors->has('password'))
            <div class="pf-alert-error">{{ $errors->first() }}</div>
            @endif

            <form method="POST" action="{{ route('profile.password') }}" style="max-width:480px">
                @csrf

                <div class="pf-row pf-row-1">
                    <div class="pf-group">
                        <label class="pf-label">Current Password</label>
                        <div style="position:relative">
                            <input type="password" name="current_password" class="pf-input"
                                   id="pw_current" placeholder="Enter current password" required
                                   style="padding-right:42px">
                            <button type="button" onclick="togglePw('pw_current')"
                                    style="position:absolute;right:12px;top:50%;transform:translateY(-50%);background:none;border:none;cursor:pointer;color:#888;font-size:15px">👁</button>
                        </div>
                    </div>
                </div>

                <div class="pf-row pf-row-1">
                    <div class="pf-group">
                        <label class="pf-label">New Password</label>
                        <div style="position:relative">
                            <input type="password" name="password" class="pf-input"
                                   id="pw_new" placeholder="Min. 8 characters" required
                                   style="padding-right:42px" oninput="checkStrength(this.value)">
                            <button type="button" onclick="togglePw('pw_new')"
                                    style="position:absolute;right:12px;top:50%;transform:translateY(-50%);background:none;border:none;cursor:pointer;color:#888;font-size:15px">👁</button>
                        </div>
                        <div class="pw-strength-bar"><div class="pw-strength-fill" id="pwStrFill"></div></div>
                        <div class="pw-strength-txt" id="pwStrTxt"></div>
                    </div>
                </div>

                <div class="pf-row pf-row-1">
                    <div class="pf-group">
                        <label class="pf-label">Confirm New Password</label>
                        <div style="position:relative">
                            <input type="password" name="password_confirmation" class="pf-input"
                                   id="pw_confirm" placeholder="Repeat new password" required
                                   style="padding-right:42px">
                            <button type="button" onclick="togglePw('pw_confirm')"
                                    style="position:absolute;right:12px;top:50%;transform:translateY(-50%);background:none;border:none;cursor:pointer;color:#888;font-size:15px">👁</button>
                        </div>
                    </div>
                </div>

                <button type="submit" class="btn-pf-save" style="margin-top:8px">Update Password</button>
            </form>
        </div>

    </div>{{-- end pf-card --}}
</div>{{-- end pf-wrap --}}

{{-- Lightbox --}}
<div class="lb-overlay" id="lbOverlay" onclick="closeLbBg(event)">
    <button class="lb-close" onclick="closeLb()">✕</button>
    <div class="lb-inner" id="lbInner"></div>
    <div class="lb-label" id="lbLabel"></div>
</div>

@push('scripts')
<script>
// ── Tab switching ─────────────────────────────────────────────────────────────
function switchTab(name, btn) {
    document.querySelectorAll('.pf-section').forEach(s => s.classList.remove('active'));
    document.querySelectorAll('.pf-nav-btn').forEach(b => b.classList.remove('active'));
    document.getElementById('tab-' + name).classList.add('active');
    btn.classList.add('active');
}

// ── Avatar preview ────────────────────────────────────────────────────────────
function previewAvatar(input) {
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = e => document.getElementById('avatarPreview').src = e.target.result;
        reader.readAsDataURL(input.files[0]);
        // sync to the info form so it submits with personal info
        const infoInput = document.querySelector('#infoForm input[name="profile_photo"]');
        if (infoInput) {
            // transfer file via DataTransfer
            const dt = new DataTransfer();
            dt.items.add(input.files[0]);
            infoInput.files = dt.files;
        }
    }
}

// ── Show selected filename ────────────────────────────────────────────────────
function showFileName(input, spanId) {
    const span = document.getElementById(spanId);
    if (input.files && input.files[0]) {
        span.textContent = '📎 ' + input.files[0].name;
    }
}

// ── Password toggle ───────────────────────────────────────────────────────────
function togglePw(id) {
    const el = document.getElementById(id);
    el.type = el.type === 'password' ? 'text' : 'password';
}

// ── Password strength ─────────────────────────────────────────────────────────
function checkStrength(val) {
    const fill = document.getElementById('pwStrFill');
    const txt  = document.getElementById('pwStrTxt');
    let score  = 0;
    if (val.length >= 8)                        score++;
    if (/[A-Z]/.test(val))                      score++;
    if (/[0-9]/.test(val))                      score++;
    if (/[^A-Za-z0-9]/.test(val))              score++;
    const levels = [
        { w:'20%',  bg:'#ef4444', label:'Weak' },
        { w:'45%',  bg:'#f97316', label:'Fair' },
        { w:'70%',  bg:'#eab308', label:'Good' },
        { w:'100%', bg:'#22c55e', label:'Strong' },
    ];
    const l = levels[score - 1] || { w:'0%', bg:'#e2e8f0', label:'' };
    fill.style.width      = l.w;
    fill.style.background = l.bg;
    txt.textContent       = l.label;
    txt.style.color       = l.bg;
}

// ── Lightbox ──────────────────────────────────────────────────────────────────
function openLb(url, label, ext) {
    const inner = document.getElementById('lbInner');
    document.getElementById('lbLabel').textContent = label;
    inner.innerHTML = '';
    if (ext.toLowerCase() === 'pdf') {
        inner.innerHTML = `<div style="background:#fff;border-radius:10px;padding:32px 40px;text-align:center">
            <p style="margin:0 0 16px;font-size:15px;color:#333">📄 PDF — cannot preview inline.</p>
            <a href="${url}" target="_blank" style="display:inline-block;padding:9px 24px;background:#0d9488;color:#fff;border-radius:8px;text-decoration:none;font-size:13px;font-weight:600">Open PDF</a>
        </div>`;
    } else {
        const img = document.createElement('img');
        img.src = url; img.alt = label;
        inner.appendChild(img);
    }
    document.getElementById('lbOverlay').classList.add('open');
    document.body.style.overflow = 'hidden';
}
function closeLb() {
    document.getElementById('lbOverlay').classList.remove('open');
    document.body.style.overflow = '';
}
function closeLbBg(e) {
    if (e.target === document.getElementById('lbOverlay')) closeLb();
}
document.addEventListener('keydown', e => { if (e.key === 'Escape') closeLb(); });

// ── Auto-open correct tab after redirect (e.g. password error) ────────────────
@if(session('password_success') || $errors->has('current_password') || $errors->has('password'))
    document.addEventListener('DOMContentLoaded', () => {
        switchTab('password', document.querySelector('.pf-nav-btn:nth-child(5)'));
    });
@endif
</script>
@endpush
@endsection