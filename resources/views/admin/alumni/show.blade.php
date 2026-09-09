{{-- FILE: resources/views/admin/alumni/show.blade.php --}}
@extends('layouts.admin')
@section('title', $user->full_name . ' - Admin')
@section('page-title','Alumni Users')
@section('content')

<style>
/* ── Page layout ── */
.ap-wrap        { display:flex; gap:28px; align-items:flex-start; flex-wrap:wrap; }

/* ── Left sidebar ── */
.ap-sidebar     { min-width:190px; max-width:210px; }
.ap-back        { display:flex; align-items:center; gap:6px; color:var(--text-muted); font-size:13px;
                  margin-bottom:18px; text-decoration:none; }
.ap-avatar      { width:90px; height:90px; border-radius:50%; object-fit:cover;
                  display:block; margin-bottom:10px; }
.ap-name        { font-size:16px; font-weight:700; margin-bottom:18px; }
.btn-approve    { display:flex; align-items:center; justify-content:center; gap:6px;
                  width:100%; padding:9px 0; border-radius:22px; border:none; cursor:pointer;
                  background:#0d9488; color:#fff; font-size:13px; font-weight:600; margin-bottom:8px; }
.btn-approve:hover { background:#0b7a70; }
.btn-reject     { display:flex; align-items:center; justify-content:center; gap:6px;
                  width:100%; padding:9px 0; border-radius:22px; border:none; cursor:pointer;
                  background:#e53e3e; color:#fff; font-size:13px; font-weight:600; margin-bottom:16px; }
.btn-reject:hover { background:#c53030; }
.btn-star       { display:block; width:100%; padding:9px 0; border-radius:22px; border:2px solid #0d9488;
                  background:transparent; color:#0d9488; font-size:13px; font-weight:600;
                  cursor:pointer; text-align:center; margin-bottom:8px; }
.btn-star:hover { background:#f0fdfc; }

/* ── Right card ── */
.ap-card        { flex:1; background:#f0f9f9; border-radius:16px; padding:28px 32px; min-width:0; }
.ap-card-header { display:flex; justify-content:space-between; align-items:center; margin-bottom:26px; }
.ap-card-title  { font-size:20px; font-weight:700; }
.ap-edit-btn    { display:flex; align-items:center; gap:5px; font-size:13px; color:#0d9488;
                  font-weight:600; text-decoration:none; cursor:pointer; background:none; border:none; }

/* ── Form grid ── */
.ap-row         { display:grid; gap:16px; margin-bottom:16px; }
.ap-row-2       { grid-template-columns:1fr 1fr; }
.ap-row-1       { grid-template-columns:1fr; }
.ap-group       { display:flex; flex-direction:column; gap:5px; }
.ap-label       { font-size:12px; color:#555; font-weight:500; }
.ap-input, .ap-select, .ap-textarea {
    background:#fff; border:1px solid #e2e8f0; border-radius:8px;
    padding:9px 12px; font-size:13px; color:#222; width:100%; box-sizing:border-box;
    outline:none; transition:border .2s;
}
.ap-input:focus, .ap-select:focus, .ap-textarea:focus { border-color:#0d9488; }
.ap-input[readonly], .ap-select:disabled, .ap-textarea[readonly] {
    background:#eef1f3; cursor:default; color:#555;
}
.ap-edit-btn.active { color:#0d9488; background:#e6f7f5; padding:5px 12px; border-radius:16px; }
.ap-select      { appearance:auto; }
.ap-textarea    { resize:vertical; }

/* Phone field */
.ap-phone-wrap  { display:flex; gap:0; }
.ap-phone-flag  { display:flex; align-items:center; gap:4px; background:#fff;
                  border:1px solid #e2e8f0; border-right:none; border-radius:8px 0 0 8px;
                  padding:9px 10px; font-size:13px; white-space:nowrap; }
.ap-phone-flag select { border:none; background:transparent; font-size:13px; outline:none; cursor:pointer; }
.ap-phone-input { border-radius:0 8px 8px 0 !important; }

/* File / CNIC row */
.ap-file-row    { display:flex; align-items:center; background:#fff;
                  border:1px solid #e2e8f0; border-radius:8px; padding:9px 12px;
                  font-size:13px; color:#555; }
.ap-file-row span { flex:1; }
.ap-eye-btn     { background:none; border:none; cursor:pointer; color:#0d9488; font-size:16px; padding:0 2px; }

/* Privacy chips */
.ap-chips-wrap  { display:flex; flex-wrap:wrap; gap:6px; }
.ap-chip        { display:flex; align-items:center; gap:4px; background:#fff;
                  border:1px solid #e2e8f0; border-radius:20px; padding:4px 12px;
                  font-size:12px; cursor:pointer; user-select:none; }
.ap-chip input  { accent-color:#0d9488; width:13px; height:13px; }

/* Edit actions */
.ap-actions     { display:none; gap:12px; margin-top:22px; }
.btn-teal       { padding:9px 28px; background:#0d9488; color:#fff; border:none;
                  border-radius:8px; font-size:13px; font-weight:600; cursor:pointer; }
.btn-teal:hover { background:#0b7a70; }
.btn-outline-red { padding:9px 28px; background:transparent; color:#e53e3e;
                   border:1.5px solid #e53e3e; border-radius:8px; font-size:13px;
                   font-weight:600; cursor:pointer; }
.btn-outline-red:hover { background:#fff5f5; }

/* ── Modal ── */
.modal-overlay  { display:none; position:fixed; inset:0; background:rgba(0,0,0,.45);
                  z-index:1000; align-items:center; justify-content:center; }
.modal-overlay.open { display:flex; }
.modal-box      { background:#fff; border-radius:14px; padding:32px; width:480px;
                  max-width:95vw; position:relative; }
.modal-close    { position:absolute; top:14px; right:16px; background:none; border:none;
                  font-size:18px; cursor:pointer; color:#888; }
.modal-box h3   { font-size:17px; font-weight:700; margin-bottom:18px; }

/* ── Lightbox ── */
.lightbox-overlay   { display:none; position:fixed; inset:0; background:rgba(0,0,0,.82);
                      z-index:2000; align-items:center; justify-content:center;
                      flex-direction:column; gap:14px; }
.lightbox-overlay.open { display:flex; }
.lightbox-inner     { position:relative; max-width:90vw; max-height:85vh;
                      display:flex; align-items:center; justify-content:center; }
.lightbox-inner img { max-width:88vw; max-height:82vh; border-radius:10px;
                      object-fit:contain; box-shadow:0 8px 40px rgba(0,0,0,.5); }
.lightbox-close     { position:fixed; top:18px; right:22px; background:rgba(255,255,255,.15);
                      border:none; color:#fff; font-size:22px; width:38px; height:38px;
                      border-radius:50%; cursor:pointer; display:flex; align-items:center;
                      justify-content:center; transition:background .2s; z-index:2001; }
.lightbox-close:hover { background:rgba(255,255,255,.3); }
.lightbox-label     { color:#ccc; font-size:13px; letter-spacing:.3px; }
.lightbox-pdf-msg   { background:#fff; border-radius:10px; padding:32px 40px; text-align:center; }
.lightbox-pdf-msg p { margin:0 0 16px; font-size:15px; color:#333; }
.lightbox-pdf-msg a { display:inline-block; padding:9px 24px; background:#0d9488; color:#fff;
                      border-radius:8px; text-decoration:none; font-size:13px; font-weight:600; }

@media(max-width:680px){
    .ap-row-2 { grid-template-columns:1fr; }
    .ap-sidebar { max-width:100%; }
}
</style>

<div class="ap-wrap">

    {{-- ── Left Sidebar ── --}}
    <div class="ap-sidebar">
        <a href="{{ $user->status === 'pending' ? route('admin.alumni.approvals') : route('admin.alumni.index') }}" class="ap-back">← Back</a>

        <img src="{{ $user->profile_photo
                ? asset('storage/'.$user->profile_photo)
                : 'https://placehold.co/180x180/1a7a7a/fff?text='.urlencode(substr($user->full_name,0,1)) }}"
             alt="{{ $user->full_name }}" class="ap-avatar">

        <div class="ap-name">{{ $user->full_name }}</div>

        @if($user->status === 'pending')
        <form method="POST" action="{{ route('admin.alumni.approve', $user->id) }}">
            @csrf
            <button type="submit" class="btn-approve">
                <svg width="14" height="14" viewBox="0 0 16 16" fill="none"><circle cx="8" cy="8" r="7" stroke="#fff" stroke-width="1.5"/><path d="M5 8l2 2 4-4" stroke="#fff" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg>
                Approve
            </button>
        </form>
        <form method="POST" action="{{ route('admin.alumni.reject', $user->id) }}">
            @csrf
            <button type="submit" class="btn-reject">
                <svg width="12" height="12" viewBox="0 0 12 12" fill="none"><path d="M2 2l8 8M10 2l-8 8" stroke="#fff" stroke-width="1.5" stroke-linecap="round"/></svg>
                Reject
            </button>
        </form>
        @endif

        <!-- @if($user->status === 'approved')
        <button class="btn-star" onclick="document.getElementById('starModal').classList.add('open')">
            ★ Mark as Star Alumni
        </button>
        @endif -->

        @if($user->status === 'approved')
    @if($user->is_star_alumni)
        {{-- Already a star ── Edit & Remove buttons --}}
        <button class="btn-star" onclick="openEditStarModal()">
            ✎ Edit Star Description
        </button>
        <form method="POST" action="{{ route('admin.alumni.removeStar', $user->id) }}"
              onsubmit="return confirm('Remove {{ $user->full_name }} from Star Alumni?');">
            @csrf
            <button type="submit" class="btn-star" style="border-color:#e53e3e;color:#e53e3e;">
                ★ Remove Star
            </button>
        </form>
    @else
        {{-- Not a star ── Mark as Star button --}}
        <button class="btn-star" onclick="openMarkStarModal()">
            ★ Mark as Star Alumni
        </button>
    @endif
@endif
    </div>

    {{-- ── Right Card ── --}}
    <div class="ap-card">
        <div class="ap-card-header">
            <span class="ap-card-title">Alumni Information</span>
            <button type="button" class="ap-edit-btn" id="editBtn" onclick="toggleEdit()">
                <svg width="14" height="14" viewBox="0 0 16 16" fill="none"><path d="M11.5 2.5a1.414 1.414 0 012 2L5 13H3v-2L11.5 2.5z" stroke="#0d9488" stroke-width="1.4" stroke-linejoin="round"/></svg>
                <span id="editBtnLabel">Edit</span>
            </button>
        </div>

        <form method="POST" action="{{ route('admin.alumni.update', $user->id) }}"
              enctype="multipart/form-data" id="alumniForm">
            @csrf @method('PUT')

            {{-- Full Name --}}
            <div class="ap-row ap-row-1">
                <div class="ap-group">
                    <label class="ap-label">Full Name: *</label>
                    <input type="text" name="full_name" class="ap-input" value="{{ $user->full_name }}" readonly id="inp_full_name">
                </div>
            </div>

            {{-- Batch + Class Year + CCP No --}}
            <div class="ap-row ap-row-3" style="display:grid;grid-template-columns:repeat(auto-fit, minmax(160px, 1fr));gap:16px;margin-bottom:16px;">
                <div class="ap-group">
                    <label class="ap-label">Batch: *</label>
                    <select name="entry" class="ap-select" disabled id="inp_entry">
                        @foreach(range(1,50) as $e)
                            <option value="{{ $e }}" {{ $user->entry==$e ? 'selected' : '' }}>Batch {{ $e }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="ap-group">
                    <label class="ap-label">Class Year (Class of): *</label>
                    <select name="class_year" class="ap-select" disabled id="inp_class_year">
                        <option value="">Select Class Year</option>
                        @foreach(range(date('Y'), 1947, -1) as $y)
                            <option value="{{ $y }}" {{ $user->class_year==$y ? 'selected' : '' }}>{{ $y }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="ap-group">
                    <label class="ap-label">CCP No.: *</label>
                    <input type="text" name="ccp_no" class="ap-input" value="{{ $user->ccp_no }}" readonly id="inp_ccp">
                </div>
            </div>

            {{-- House + Education --}}
            <div class="ap-row ap-row-2">
                <div class="ap-group">
                    <label class="ap-label">House: *</label>
                    <select name="house" class="ap-select" disabled id="inp_house">
                        @foreach(['Jinnah','Iqbal','Liaquat','Ayub','Ranjit'] as $h)
                            <option value="{{ $h }}" {{ $user->house==$h ? 'selected' : '' }}>{{ $h }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="ap-group">
                    <label class="ap-label">Education: *</label>
                    <select name="education" class="ap-select" disabled id="inp_edu">
                        @foreach(['Matric','Intermediate','Bachelors','Masters','PhD'] as $ed)
                            <option value="{{ $ed }}" {{ $user->education==$ed ? 'selected' : '' }}>{{ $ed }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            {{-- Field of Study + Field of Work --}}
            <div class="ap-row ap-row-2">
                <div class="ap-group">
                    <label class="ap-label">Field of Study:</label>
                    <input type="text" name="field_of_study" class="ap-input" value="{{ $user->field_of_study }}" readonly id="inp_fos">
                </div>
                <div class="ap-group">
                    <label class="ap-label">Field of Work:</label>
                    <input type="text" name="field_of_work" class="ap-input" value="{{ $user->field_of_work }}" readonly id="inp_fow">
                </div>
            </div>

            {{-- Current City + Current Country --}}
            <div class="ap-row ap-row-2">
                <div class="ap-group">
                    <label class="ap-label">Current City</label>
                    <select name="current_city" class="ap-select" disabled id="inp_city">
                        <option value="{{ $user->current_city }}" selected>{{ $user->current_city }}</option>
                    </select>
                </div>
                <div class="ap-group">
                    <label class="ap-label">Current Country: *</label>
                    <select name="current_country" class="ap-select" disabled id="inp_country">
                        <option value="{{ $user->current_country }}" selected>{{ $user->current_country }}</option>
                    </select>
                </div>
            </div>

            {{-- Current Designation + Current Organization --}}
            <div class="ap-row ap-row-2">
                <div class="ap-group">
                    <label class="ap-label">Current Designation</label>
                    <select name="current_designation" class="ap-select" disabled id="inp_desig">
                        <option value="{{ $user->current_designation }}" selected>{{ $user->current_designation }}</option>
                    </select>
                </div>
                <div class="ap-group">
                    <label class="ap-label">Current Organization</label>
                    <select name="current_organization" class="ap-select" disabled id="inp_org">
                        <option value="{{ $user->current_organization }}" selected>{{ $user->current_organization }}</option>
                    </select>
                </div>
            </div>

            {{-- Email ID --}}
            <div class="ap-row ap-row-1">
                <div class="ap-group">
                    <label class="ap-label">Email ID: *</label>
                    <input type="email" name="email" class="ap-input" value="{{ $user->email }}" readonly id="inp_email">
                </div>
            </div>

            {{-- Phone Number --}}
            <div class="ap-row ap-row-1">
                <div class="ap-group">
                    <label class="ap-label">Phone Number:</label>
                    <div class="ap-phone-wrap">
                        <div class="ap-phone-flag">
                            🇺🇸
                            <select name="phone_code" id="inp_phone_code" disabled>
                                <option value="+1" {{ str_starts_with($user->phone_number ?? '', '+1') ? 'selected' : '' }}>+1</option>
                                <option value="+92" {{ str_starts_with($user->phone_number ?? '', '+92') ? 'selected' : '' }}>+92</option>
                                <option value="+44" {{ str_starts_with($user->phone_number ?? '', '+44') ? 'selected' : '' }}>+44</option>
                                <option value="+971" {{ str_starts_with($user->phone_number ?? '', '+971') ? 'selected' : '' }}>+971</option>
                                <option value="+966" {{ str_starts_with($user->phone_number ?? '', '+966') ? 'selected' : '' }}>+966</option>
                            </select>
                        </div>
                        <input type="text" name="phone_number" class="ap-input ap-phone-input"
                               value="{{ $user->phone_number }}" readonly id="inp_phone">
                    </div>
                </div>
            </div>

            {{-- Achievements --}}
            <div class="ap-row ap-row-1">
                <div class="ap-group">
                    <label class="ap-label">Achievements:</label>
                    <textarea name="achievements" class="ap-textarea" rows="4" readonly id="inp_ach">{{ $user->achievements }}</textarea>
                </div>
            </div>

            {{-- Upload CNIC --}}
            <div class="ap-row ap-row-1">
                <div class="ap-group">
                    <label class="ap-label">Upload CNIC:</label>
                    <div class="ap-file-row">
                        <span>{{ $user->cnic_file ? basename($user->cnic_file) : 'No file uploaded' }}</span>
                        @if($user->cnic_file)
                        <button type="button" class="ap-eye-btn" title="View CNIC"
                                onclick="openLightbox('{{ asset('storage/'.$user->cnic_file) }}', 'CNIC Document', '{{ pathinfo($user->cnic_file, PATHINFO_EXTENSION) }}')">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8S1 12 1 12z" stroke="#0d9488" stroke-width="1.8"/><circle cx="12" cy="12" r="3" stroke="#0d9488" stroke-width="1.8"/></svg>
                        </button>
                        @else
                        <button type="button" class="ap-eye-btn" title="No file uploaded" style="opacity:.35;cursor:not-allowed">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8S1 12 1 12z" stroke="#0d9488" stroke-width="1.8"/><circle cx="12" cy="12" r="3" stroke="#0d9488" stroke-width="1.8"/></svg>
                        </button>
                        @endif
                    </div>
                    <input type="file" name="cnic_file" id="inp_cnic" style="display:none" accept="image/*,.pdf">
                </div>
            </div>

            {{-- Profile Photo --}}
            <div class="ap-row ap-row-1">
                <div class="ap-group">
                    <label class="ap-label">Profile Photo:</label>
                    <div class="ap-file-row">
                        <span>{{ $user->profile_photo ? basename($user->profile_photo) : 'No file uploaded' }}</span>
                        @if($user->profile_photo)
                        <button type="button" class="ap-eye-btn" title="View Profile Photo"
                                onclick="openLightbox('{{ asset('storage/'.$user->profile_photo) }}', 'Profile Photo', '{{ pathinfo($user->profile_photo, PATHINFO_EXTENSION) }}')">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8S1 12 1 12z" stroke="#0d9488" stroke-width="1.8"/><circle cx="12" cy="12" r="3" stroke="#0d9488" stroke-width="1.8"/></svg>
                        </button>
                        @else
                        <button type="button" class="ap-eye-btn" title="No file uploaded" style="opacity:.35;cursor:not-allowed">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8S1 12 1 12z" stroke="#0d9488" stroke-width="1.8"/><circle cx="12" cy="12" r="3" stroke="#0d9488" stroke-width="1.8"/></svg>
                        </button>
                        @endif
                    </div>
                    <input type="file" name="profile_photo" id="inp_photo" style="display:none" accept="image/*">
                </div>
            </div>

            {{-- Privacy Settings --}}
            <div class="ap-row ap-row-1">
                <div class="ap-group">
                    <label class="ap-label">Privacy Settings: <span style="font-weight:400;color:#888">Choose which details to hide with other alumni</span></label>
                    <div class="ap-chips-wrap" id="privacyChips">
                        @php
                            $privacyFields = ['Email Address','City','Phone Number','Designation','Organization','Field of Study','Field of Work'];
                            $hiddenFields  = is_array($user->privacy_settings)
                                             ? $user->privacy_settings
                                             : json_decode($user->privacy_settings ?? '[]', true);
                        @endphp
                        @foreach($privacyFields as $pf)
                        <label class="ap-chip">
                            <input type="checkbox" name="privacy_settings[]" value="{{ $pf }}"
                                   {{ in_array($pf, $hiddenFields ?? []) ? 'checked' : '' }}
                                   disabled class="privacy-cb">
                            {{ $pf }}
                        </label>
                        @endforeach
                    </div>
                </div>
            </div>

            {{-- Edit actions --}}
            <div id="editActions" class="ap-actions">
                <button type="submit" class="btn-teal">Save Changes</button>
                <button type="button" class="btn-outline-red" onclick="toggleEdit()">Cancel</button>
            </div>
        </form>
    </div>
</div>

{{-- ── Lightbox Modal ── --}}
<div class="lightbox-overlay" id="lightboxOverlay" onclick="closeLightboxOnBg(event)">
    <button class="lightbox-close" onclick="closeLightbox()" title="Close">✕</button>
    <div class="lightbox-inner" id="lightboxInner">
        {{-- content injected by JS --}}
    </div>
    <div class="lightbox-label" id="lightboxLabel"></div>
</div>

{{-- Star Alumni Modal --}}
<!-- <div class="modal-overlay" id="starModal">
    <div class="modal-box">
        <button class="modal-close" onclick="document.getElementById('starModal').classList.remove('open')">✕</button>
        <h3>Add as Star Alumni</h3>
        <form method="POST" action="{{ route('admin.alumni.star', $user->id) }}">
            @csrf
            <div class="ap-group" style="margin-bottom:20px">
                <label class="ap-label">Featured Description:</label>
                <textarea name="star_description" class="ap-textarea" rows="5"
                          placeholder="Enter a featured description for this star alumni..."></textarea>
            </div>
            <div style="display:flex;gap:12px">
                <button type="submit" class="btn-teal">Save</button>
                <button type="button" class="btn-outline-red"
                        onclick="document.getElementById('starModal').classList.remove('open')">Cancel</button>
            </div>
        </form>
    </div>
</div> -->
{{-- Modal for Mark as Star (NEW) --}}
<div class="modal-overlay" id="markStarModal">
    <div class="modal-box">
        <button class="modal-close" onclick="closeModal('markStarModal')">✕</button>
        <h3>Add as Star Alumni</h3>
        <form method="POST" action="{{ route('admin.alumni.star', $user->id) }}">
            @csrf
            <div class="ap-group" style="margin-bottom:20px">
                <label class="ap-label">Featured Description:</label>
                <textarea name="star_description" class="ap-textarea" rows="5"
                          placeholder="Enter a featured description for this star alumni..."></textarea>
            </div>
            <div style="display:flex;gap:12px">
                <button type="submit" class="btn-teal">Save</button>
                <button type="button" class="btn-outline-red" onclick="closeModal('markStarModal')">Cancel</button>
            </div>
        </form>
    </div>
</div>

{{-- Modal for Edit Star Description (NEW) --}}
<div class="modal-overlay" id="editStarModal">
    <div class="modal-box">
        <button class="modal-close" onclick="closeModal('editStarModal')">✕</button>
        <h3>Edit Star Description</h3>
        <form method="POST" action="{{ route('admin.alumni.updateStarDescription', $user->id) }}">
            @csrf
            <div class="ap-group" style="margin-bottom:20px">
                <label class="ap-label">Featured Description:</label>
                <textarea name="star_description" class="ap-textarea" rows="5"
                          placeholder="Enter a featured description..." required>{{ $user->star_description }}</textarea>
            </div>
            <div style="display:flex;gap:12px">
                <button type="submit" class="btn-teal">Update</button>
                <button type="button" class="btn-outline-red" onclick="closeModal('editStarModal')">Cancel</button>
            </div>
        </form>
    </div>
</div>
@push('scripts')
<script>
// ── Lightbox ──────────────────────────────────────────────────────────────────
function openLightbox(url, label, ext) {
    const overlay = document.getElementById('lightboxOverlay');
    const inner   = document.getElementById('lightboxInner');
    const lbl     = document.getElementById('lightboxLabel');

    inner.innerHTML = ''; // clear previous
    lbl.textContent = label;

    if (ext.toLowerCase() === 'pdf') {
        // PDF: can't embed reliably in all browsers — show a clean open button
        inner.innerHTML = `
            <div class="lightbox-pdf-msg">
                <p>📄 PDF document cannot be previewed inline.</p>
                <a href="${url}" target="_blank">Open PDF in new tab</a>
            </div>`;
    } else {
        // Image
        const img = document.createElement('img');
        img.src = url;
        img.alt = label;
        inner.appendChild(img);
    }

    overlay.classList.add('open');
    document.body.style.overflow = 'hidden'; // prevent bg scroll
}

function closeLightbox() {
    document.getElementById('lightboxOverlay').classList.remove('open');
    document.body.style.overflow = '';
}

function closeLightboxOnBg(e) {
    // Close only if clicking the dark backdrop, not the image/content
    if (e.target === document.getElementById('lightboxOverlay')) {
        closeLightbox();
    }
}

// Close on Escape key
document.addEventListener('keydown', e => {
    if (e.key === 'Escape') closeLightbox();
});

// ── Edit toggle ───────────────────────────────────────────────────────────────
let editing = false;

const editableInputs   = ['inp_full_name','inp_email','inp_ccp','inp_phone','inp_ach','inp_fos','inp_fow'];
const editableSelects  = ['inp_entry','inp_class_year','inp_house','inp_edu','inp_city','inp_country','inp_desig','inp_org','inp_phone_code'];

function toggleEdit() {
    editing = !editing;

    editableInputs.forEach(id => {
        const el = document.getElementById(id);
        if (el) el.readOnly = !editing;
    });
    editableSelects.forEach(id => {
        const el = document.getElementById(id);
        if (el) el.disabled = !editing;
    });

    // Privacy checkboxes
    document.querySelectorAll('.privacy-cb').forEach(cb => cb.disabled = !editing);

    // File inputs – show when editing
    document.getElementById('inp_cnic').style.display  = editing ? 'block' : 'none';
    document.getElementById('inp_photo').style.display = editing ? 'block' : 'none';

    document.getElementById('editActions').style.display = editing ? 'flex' : 'none';

    // Edit button state
    document.getElementById('editBtn').classList.toggle('active', editing);
    document.getElementById('editBtnLabel').textContent = editing ? 'Editing' : 'Edit';
}


// ── Modal helpers for star management ────────────────────────────
function openMarkStarModal() {
    document.getElementById('markStarModal').classList.add('open');
}
function openEditStarModal() {
    document.getElementById('editStarModal').classList.add('open');
}
function closeModal(id) {
    document.getElementById(id).classList.remove('open');
}
// Close modals on Escape key
document.addEventListener('keydown', e => {
    if (e.key === 'Escape') {
        closeModal('markStarModal');
        closeModal('editStarModal');
    }
});
// Close by clicking the overlay background
document.querySelectorAll('.modal-overlay').forEach(overlay => {
    overlay.addEventListener('click', function(e) {
        if (e.target === this) {
            this.classList.remove('open');
        }
    });
});
</script>
@endpush
@endsection