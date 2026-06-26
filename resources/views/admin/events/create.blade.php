{{-- FILE: resources/views/admin/events/create.blade.php --}}
@extends('layouts.admin')
@section('title','Create Event - Admin')
@section('page-title','Create an Event')

@push('styles')
<style>
/* ═══════════════════════════════════════════════
   CREATE EVENT FORM  –  pixel-perfect Figma match
   ═══════════════════════════════════════════════ */

.cef-page {
    background: #f8fcfc;
    padding: 0;
    max-width: 780px;
}

.cef-back {
    display: inline-flex;
    align-items: center;
    gap: 4px;
    color: #1a8080;
    font-size: 18px;
    font-weight: 700;
    text-decoration: none;
    margin-bottom: 18px;
    line-height: 1;
}
.cef-back:hover { opacity: .75; }

.cef-heading {
    font-size: 1.55rem;
    font-weight: 700;
    color: #111;
    margin: 0 0 28px;
}

/* ── Error box ─────────────────────────────── */
.cef-errors {
    margin-bottom: 20px;
    padding: 12px 16px;
    background: #fcebeb;
    border: 1px solid #f09595;
    border-radius: 10px;
    color: #a32d2d;
    font-size: 13px;
}
.cef-errors ul { margin: 6px 0 0; padding-left: 16px; }

/* ── Grid helpers ──────────────────────────── */
.cef-row-2 {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 20px 28px;
    margin-bottom: 20px;
}
.cef-row-4 {
    display: grid;
    grid-template-columns: 1fr 1fr 1fr 1fr;
    gap: 20px 20px;
    margin-bottom: 20px;
}
.cef-row-1 {
    margin-bottom: 20px;
}

/* ── Field group ───────────────────────────── */
.cef-group { display: flex; flex-direction: column; }

.cef-label {
    font-size: 13px;
    font-weight: 600;
    color: #111;
    margin-bottom: 8px;
    line-height: 1.3;
}
.cef-label small {
    font-weight: 400;
    color: #888;
}

/* ── Base input style (mint pill) ──────────── */
/* Figma: light mint background, pill-shaped, no border, gray placeholder */
.cef-input,
.cef-textarea,
.cef-select {
    width: 100%;
    box-sizing: border-box;
    background: #e8f4f4;          /* light mint fill */
    border: none;
    border-radius: 30px;          /* pill shape */
    padding: 12px 18px;
    font-size: 13.5px;
    color: #222;
    outline: none;
    font-family: inherit;
    transition: background .15s, box-shadow .15s;
    appearance: none;
    -webkit-appearance: none;
}
.cef-input::placeholder,
.cef-textarea::placeholder { color: #9db8b8; }

.cef-input:focus,
.cef-textarea:focus,
.cef-select:focus {
    background: #d6eeee;
    box-shadow: 0 0 0 2px rgba(26,128,128,.2);
}

/* Textarea gets a rectangle not pill */
.cef-textarea {
    border-radius: 14px;
    resize: vertical;
    min-height: 100px;
    line-height: 1.6;
}

/* Select keeps the dropdown arrow */
.cef-select {
    border-radius: 30px;
    cursor: pointer;
    padding-right: 36px;
    background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='8' viewBox='0 0 12 8'%3E%3Cpath d='M1 1l5 5 5-5' stroke='%231a8080' stroke-width='1.8' fill='none' stroke-linecap='round'/%3E%3C/svg%3E");
    background-repeat: no-repeat;
    background-position: right 14px center;
}

/* Date/time input – keep icon on right */
input[type="date"].cef-input,
input[type="time"].cef-input {
    cursor: pointer;
}
input[type="date"].cef-input::-webkit-calendar-picker-indicator,
input[type="time"].cef-input::-webkit-calendar-picker-indicator {
    opacity: .55;
    cursor: pointer;
    filter: invert(38%) sepia(60%) saturate(400%) hue-rotate(140deg);
}

/* ── Registration radio ─────────────────────── */
.cef-radio-wrap {
    display: flex;
    gap: 32px;
    margin-top: 6px;
    align-items: center;
    padding: 8px 0;
}
.cef-radio-label {
    display: flex;
    align-items: center;
    gap: 8px;
    font-size: 13.5px;
    color: #333;
    cursor: pointer;
    font-weight: 500;
}
/* Custom radio */
.cef-radio-label input[type="radio"] {
    width: 18px;
    height: 18px;
    accent-color: #1a8080;
    cursor: pointer;
}
.cef-reg-hint {
    font-size: 11.5px;
    color: #888;
    margin-top: 6px;
    display: block;
    line-height: 1.4;
}

/* ── Batch tag box ──────────────────────────── */
.cef-tag-box {
    min-height: 46px;
    background: #e8f4f4;
    border: none;
    border-radius: 14px;
    padding: 8px 12px;
    display: flex;
    flex-wrap: wrap;
    gap: 6px;
    align-items: center;
    cursor: text;
    transition: background .15s;
}
.cef-tag-box:focus-within {
    background: #d6eeee;
    box-shadow: 0 0 0 2px rgba(26,128,128,.2);
}
.cef-tag-box input {
    border: none;
    outline: none;
    font-size: 13px;
    min-width: 180px;
    flex: 1;
    padding: 3px 0;
    background: transparent;
    color: #222;
}
.cef-tag-box input::placeholder { color: #9db8b8; }

/* Individual batch tags */
.cef-tag {
    display: inline-flex;
    align-items: center;
    gap: 5px;
    background: #c8eded;
    color: #0f5f5f;
    border-radius: 20px;
    padding: 3px 12px 3px 10px;
    font-size: 12.5px;
    font-weight: 500;
}
.cef-tag button {
    background: none;
    border: none;
    cursor: pointer;
    color: #0f5f5f;
    font-size: 16px;
    line-height: 1;
    padding: 0;
    display: flex;
    align-items: center;
}

.cef-batch-controls {
    display: flex;
    gap: 8px;
    margin-top: 8px;
    align-items: center;
}
.cef-batch-controls select {
    flex: 1;
    font-size: 12.5px;
    padding: 7px 12px;
    border: 1px solid #cce0e0;
    border-radius: 20px;
    background: #fff;
    color: #333;
    cursor: pointer;
    appearance: none;
    -webkit-appearance: none;
}
.cef-batch-add-btn {
    padding: 7px 16px;
    background: #1a8080;
    color: #fff;
    border: none;
    border-radius: 20px;
    font-size: 12.5px;
    font-weight: 600;
    cursor: pointer;
}
.cef-batch-add-btn:hover { opacity: .85; }
.cef-batch-clear-btn {
    padding: 7px 14px;
    background: #fff;
    color: #e24b4a;
    border: 1px solid #e24b4a;
    border-radius: 20px;
    font-size: 12.5px;
    font-weight: 600;
    cursor: pointer;
}
.cef-batch-clear-btn:hover { background: #fff0f0; }

.cef-hint {
    font-size: 11.5px;
    color: #888;
    margin-top: 6px;
    display: block;
    line-height: 1.4;
}

/* ── Upload box ─────────────────────────────── */
.cef-upload-box {
    display: flex;
    align-items: center;
    gap: 14px;
    background: #e8f4f4;
    border-radius: 14px;
    padding: 16px 22px;
    cursor: pointer;
    transition: background .15s;
    border: 2px dashed #b2d8d8;
    max-width: 380px;
}
.cef-upload-box:hover { background: #d6eeee; border-color: #1a8080; }

.cef-upload-icon {
    width: 34px;
    height: 34px;
    background: #1a8080;
    border-radius: 8px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #fff;
    font-size: 20px;
    font-weight: 300;
    flex-shrink: 0;
    line-height: 1;
}
.cef-upload-text {
    font-size: 13px;
    color: #555;
    line-height: 1.4;
}
.cef-upload-filename {
    font-size: 12px;
    color: #1a8080;
    margin-top: 6px;
    display: block;
    font-weight: 500;
}

/* Preview thumbnail */
.cef-preview-wrap {
    margin-top: 12px;
    display: none;
}
.cef-preview-wrap img {
    width: 90px;
    height: 90px;
    object-fit: cover;
    border-radius: 10px;
    border: 2px solid #b2d8d8;
}

/* ── Action buttons ─────────────────────────── */
.cef-actions {
    display: flex;
    gap: 16px;
    margin-top: 32px;
    align-items: center;
}

/* Publish: teal filled pill */
.cef-btn-publish {
    padding: 12px 44px;
    background: #1a8080;
    color: #fff;
    border: none;
    border-radius: 30px;
    font-size: 14px;
    font-weight: 600;
    cursor: pointer;
    transition: opacity .15s;
}
.cef-btn-publish:hover { opacity: .85; }

/* Cancel: white + red border pill */
.cef-btn-cancel {
    padding: 11px 44px;
    background: #fff;
    color: #e24b4a;
    border: 2px solid #e24b4a;
    border-radius: 30px;
    font-size: 14px;
    font-weight: 600;
    cursor: pointer;
    text-decoration: none;
    display: inline-block;
    transition: background .15s;
}
.cef-btn-cancel:hover { background: #fff0f0; }

/* ── Responsive ─────────────────────────────── */
@media (max-width: 680px) {
    .cef-row-2,
    .cef-row-4 { grid-template-columns: 1fr; }
}
</style>
@endpush

@section('content')

<div class="cef-page">

    <a href="{{ route('admin.events.index') }}" class="cef-back">←</a>

    <!-- <h2 class="cef-heading">Create an Event</h2> -->

    @if($errors->any())
        <div class="cef-errors">
            <strong>Please fix the following errors:</strong>
            <ul>
                @foreach($errors->all() as $err)<li>{{ $err }}</li>@endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('admin.events.store') }}"
          enctype="multipart/form-data" id="eventForm">
        @csrf

        {{-- ── Row 1: Title + Registration Required ── --}}
        <div class="cef-row-2">
            <div class="cef-group">
                <label class="cef-label">Event Title: *</label>
                <input type="text" name="title" class="cef-input"
                       placeholder="Sailing Event"
                       value="{{ old('title') }}" required>
            </div>
            <div class="cef-group">
                <label class="cef-label">Registration Required: *</label>
                <div class="cef-radio-wrap">
                    <label class="cef-radio-label">
                        <input type="radio" name="registration_required" value="1"
                               {{ old('registration_required') == '1' ? 'checked' : '' }}>
                        Yes
                    </label>
                    <label class="cef-radio-label">
                        <input type="radio" name="registration_required" value="0"
                               {{ old('registration_required', '0') == '0' ? 'checked' : '' }}>
                        No
                    </label>
                </div>
                <small class="cef-reg-hint">If No, the event is informational only — no registration button shown.</small>
            </div>
        </div>

        {{-- ── Description ── --}}
        <div class="cef-row-1 cef-group">
            <label class="cef-label">Description: *</label>
            <textarea name="description" class="cef-textarea"
                      rows="4" placeholder="Event description..." required>{{ old('description') }}</textarea>
        </div>

        {{-- ── Row 3: Start Date | End Date | Start Time | End Time ── --}}
        <div class="cef-row-4">
            <div class="cef-group">
                <label class="cef-label">Start Date: *</label>
                <input type="date" name="start_date" id="start_date" class="cef-input"
                       value="{{ old('start_date') }}" required min="{{ date('Y-m-d') }}">
            </div>
            <div class="cef-group">
                <label class="cef-label">End Date: *</label>
                <input type="date" name="end_date" id="end_date" class="cef-input"
                       value="{{ old('end_date') }}" required min="{{ date('Y-m-d') }}">
            </div>
            <div class="cef-group">
                <label class="cef-label">Start Time: *</label>
                <input type="time" name="start_time" id="start_time" class="cef-input"
                       value="{{ old('start_time') }}" required>
            </div>
            <div class="cef-group">
                <label class="cef-label">End Time:</label>
                <input type="time" name="end_time" id="end_time" class="cef-input"
                       value="{{ old('end_time') }}">
            </div>
        </div>

        {{-- ── Location ── --}}
        <div class="cef-row-1 cef-group">
            <label class="cef-label">Location: *</label>
            <input type="text" name="location" class="cef-input"
                   placeholder="Sailing Public Spot"
                   value="{{ old('location') }}" required>
        </div>

        {{-- ── Row 5: Focal Person Name + Number ── --}}
        <div class="cef-row-2">
            <div class="cef-group">
                <label class="cef-label">Focal Person Name: *</label>
                <input type="text" name="focal_person_name" class="cef-input"
                       placeholder="Waleed Ahmed"
                       value="{{ old('focal_person_name') }}" required>
            </div>
            <div class="cef-group">
                <label class="cef-label">Focal Person Number: *</label>
                <input type="text" name="focal_person_number" class="cef-input"
                       placeholder="03454501450"
                       value="{{ old('focal_person_number') }}" required>
            </div>
        </div>

        {{-- ── Row 6: Entry Batches + Gallery Link ── --}}
        <div class="cef-row-2">

            {{-- Batch tag widget --}}
            <div class="cef-group">
                <label class="cef-label">
                    Eligible Entry Batches:
                    <small>(leave empty = open to all)</small>
                </label>

                <div class="cef-tag-box" id="batchTagBox"
                     onclick="document.getElementById('batchInput').focus()">
                    <input type="text" id="batchInput"
                           placeholder="Type batch number & press Enter"
                           inputmode="numeric" pattern="[0-9]*">
                </div>

                <div class="cef-batch-controls">
                    <select id="batchDropdown">
                        <option value="">— Quick add batch —</option>
                        @for($i = 1; $i <= 100; $i++)
                            <option value="{{ $i }}">Batch {{ $i }}</option>
                        @endfor
                    </select>
                    <button type="button" class="cef-batch-add-btn" onclick="addFromDropdown()">Add</button>
                    <button type="button" class="cef-batch-clear-btn" onclick="clearAllBatches()">Clear All</button>
                </div>

                <div id="batchHiddenInputs"></div>
                <small class="cef-hint">Only alumni whose batch matches can register.</small>

                @if(old('entry_batches'))
                    <script>window._oldBatches = @json(old('entry_batches'));</script>
                @endif
            </div>

            {{-- Gallery Link --}}
            <div class="cef-group">
                <label class="cef-label">Gallery Link:</label>
                <input type="url" name="gallery_link" class="cef-input"
                       placeholder="https://palandrians.org/gallery"
                       value="{{ old('gallery_link') }}">
            </div>
        </div>

        {{-- ── Upload Logo ── --}}
        <div class="cef-group" style="margin-bottom:28px">
            <label class="cef-label">Upload Event Logo: *</label>

            <div class="cef-upload-box" onclick="document.getElementById('logoFile').click()">
                <div class="cef-upload-icon">+</div>
                <div class="cef-upload-text">Drag &amp; Drop files here or click to select file(s)</div>
            </div>

            <input type="file" id="logoFile" name="logo" accept="image/*"
                   style="display:none" onchange="handleLogoChange(this)">

            <span id="logoName" class="cef-upload-filename"></span>

            <div class="cef-preview-wrap" id="previewWrap">
                <img id="previewImg" src="" alt="Preview">
            </div>
        </div>

        {{-- ── Actions ── --}}
        <div class="cef-actions">
            <button type="submit" class="cef-btn-publish">Publish</button>
            <a href="{{ route('admin.events.index') }}" class="cef-btn-cancel">Cancel</a>
        </div>

    </form>
</div>

@push('scripts')
<script>
/* ═══════════════════════════════════════════════
   BATCH TAG WIDGET
   ═══════════════════════════════════════════════ */
var batches = new Set();

function renderTags() {
    var box   = document.getElementById('batchTagBox');
    var input = document.getElementById('batchInput');

    // Remove existing tags (keep the text input)
    Array.from(box.children).forEach(function(c) {
        if (c !== input) c.remove();
    });

    // Re-render tags before the input
    batches.forEach(function(b) {
        var tag = document.createElement('span');
        tag.className = 'cef-tag';
        tag.innerHTML =
            'Batch ' + b +
            ' <button type="button" onclick="removeBatch(' + b + ')">×</button>';
        box.insertBefore(tag, input);
    });

    // Rebuild hidden inputs
    var hid = document.getElementById('batchHiddenInputs');
    hid.innerHTML = '';
    batches.forEach(function(b) {
        var inp = document.createElement('input');
        inp.type  = 'hidden';
        inp.name  = 'entry_batches[]';
        inp.value = b;
        hid.appendChild(inp);
    });
}

function addBatch(val) {
    var n = parseInt(val);
    if (!isNaN(n) && n >= 1 && n <= 100) {
        batches.add(n);
        renderTags();
    }
}

function removeBatch(n)   { batches.delete(n); renderTags(); }
function clearAllBatches(){ batches.clear();   renderTags(); }

function addFromDropdown() {
    var dd = document.getElementById('batchDropdown');
    if (dd.value) { addBatch(dd.value); dd.value = ''; }
}

document.getElementById('batchInput').addEventListener('keydown', function(e) {
    if (e.key === 'Enter' || e.key === ',') {
        e.preventDefault();
        addBatch(this.value.trim());
        this.value = '';
    }
    if (e.key === 'Backspace' && this.value === '' && batches.size > 0) {
        removeBatch(Array.from(batches).pop());
    }
});

// Restore old() values after validation failure
if (window._oldBatches && Array.isArray(window._oldBatches)) {
    window._oldBatches.forEach(function(b){ batches.add(parseInt(b)); });
    renderTags();
}

/* ═══════════════════════════════════════════════
   LOGO UPLOAD PREVIEW
   ═══════════════════════════════════════════════ */
function handleLogoChange(input) {
    if (!input.files || !input.files[0]) return;
    var file = input.files[0];

    // Show filename
    document.getElementById('logoName').textContent = '✓ ' + file.name;

    // Show image preview
    var reader = new FileReader();
    reader.onload = function(e) {
        document.getElementById('previewImg').src = e.target.result;
        document.getElementById('previewWrap').style.display = 'block';
    };
    reader.readAsDataURL(file);
}

/* ═══════════════════════════════════════════════
   DATE / TIME RESTRICTIONS
   ═══════════════════════════════════════════════ */
(function () {
    var today     = new Date().toISOString().split('T')[0];
    var startDate = document.getElementById('start_date');
    var endDate   = document.getElementById('end_date');
    var startTime = document.getElementById('start_time');
    var endTime   = document.getElementById('end_time');

    function nowHHMM() {
        var n = new Date();
        return n.getHours().toString().padStart(2,'0') + ':' +
               n.getMinutes().toString().padStart(2,'0');
    }

    startDate.addEventListener('change', function () {
        endDate.min = this.value || today;
        if (endDate.value && endDate.value < this.value) endDate.value = this.value;
        startTime.min = (this.value === today) ? nowHHMM() : '';
    });

    startTime.addEventListener('change', function () {
        if (startDate.value === endDate.value) endTime.min = this.value;
    });

    endDate.addEventListener('change', function () {
        endTime.min = (this.value === startDate.value) ? startTime.value : '';
    });

    if (startDate.value === today) startTime.min = nowHHMM();
})();
</script>
@endpush

@endsection