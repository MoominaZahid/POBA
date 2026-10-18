{{-- FILE: resources/views/admin/gallery/create.blade.php --}}
@extends('layouts.admin')
@section('title','Create Gallery Folder - Admin')
@section('page-title','Gallery')

@push('styles')
<style>
/* ═══════════════════════════════════════════════
   CREATE GALLERY FOLDER  –  pixel-perfect Figma
   ═══════════════════════════════════════════════ */

.cgf-page {
    background: #f8fcfc;
    padding: 0;
    max-width: 780px;
}

.cgf-back {
    display: inline-flex;
    align-items: center;
    color: #1a8080;
    font-size: 18px;
    font-weight: 700;
    text-decoration: none;
    margin-bottom: 18px;
    line-height: 1;
}
.cgf-back:hover { opacity: .75; }

.cgf-heading {
    font-size: 1.55rem;
    font-weight: 700;
    color: #111;
    margin: 0 0 28px;
}

/* Error box */
.cgf-errors {
    margin-bottom: 20px;
    padding: 12px 16px;
    background: #fcebeb;
    border: 1px solid #f09595;
    border-radius: 10px;
    color: #a32d2d;
    font-size: 13px;
}
.cgf-errors ul { margin: 6px 0 0; padding-left: 16px; }

/* Grid helpers */
.cgf-row-2 {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 20px 28px;
    margin-bottom: 20px;
}
.cgf-row-1 { margin-bottom: 20px; }

.cgf-group { display: flex; flex-direction: column; }

.cgf-label {
    font-size: 13px;
    font-weight: 600;
    color: #111;
    margin-bottom: 8px;
    line-height: 1.3;
}

/* Mint pill inputs — same as event form */
.cgf-input,
.cgf-textarea,
.cgf-select {
    width: 100%;
    box-sizing: border-box;
    background: #e8f4f4;
    border: none;
    border-radius: 30px;
    padding: 12px 18px;
    font-size: 13.5px;
    color: #222;
    outline: none;
    font-family: inherit;
    transition: background .15s, box-shadow .15s;
    appearance: none;
    -webkit-appearance: none;
}
.cgf-input::placeholder,
.cgf-textarea::placeholder { color: #9db8b8; }
.cgf-input:focus,
.cgf-textarea:focus,
.cgf-select:focus {
    background: #d6eeee;
    box-shadow: 0 0 0 2px rgba(26,128,128,.2);
}

/* Textarea: rectangle not pill */
.cgf-textarea {
    border-radius: 14px;
    resize: vertical;
    min-height: 100px;
    line-height: 1.6;
}

/* Select with arrow */
.cgf-select {
    cursor: pointer;
    padding-right: 36px;
    background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='8' viewBox='0 0 12 8'%3E%3Cpath d='M1 1l5 5 5-5' stroke='%231a8080' stroke-width='1.8' fill='none' stroke-linecap='round'/%3E%3C/svg%3E");
    background-repeat: no-repeat;
    background-position: right 14px center;
}

/* Slug URL preview */
.cgf-slug-preview {
    font-size: 11.5px;
    color: #888;
    margin-top: 6px;
    display: block;
    line-height: 1.5;
    word-break: break-all;
}

/* Readonly slug input */
.cgf-input[readonly] {
    color: #555;
    cursor: default;
}

/* Action buttons */
.cgf-actions {
    display: flex;
    gap: 16px;
    margin-top: 32px;
    align-items: center;
}
.cgf-btn-save {
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
.cgf-btn-save:hover { opacity: .85; }
.cgf-btn-cancel {
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
.cgf-btn-cancel:hover { background: #fff0f0; }

@media (max-width: 580px) {
    .cgf-row-2 { grid-template-columns: 1fr; }
}

/* Upload box */
.cgf-upload-box {
    display: flex;
    align-items: center;
    gap: 14px;
    background: #e8f4f4;
    border-radius: 14px;
    padding: 14px 20px;
    cursor: pointer;
    transition: background .15s, border-color .15s;
    border: 2px dashed #b2d8d8;
}
.cgf-upload-box:hover { background: #d6eeee; border-color: #1a8080; }
.cgf-upload-box.has-error { background: #fff5f5; border-color: #e24b4a; }
.cgf-upload-icon {
    width: 34px;
    height: 34px;
    background: #1a8080;
    border-radius: 8px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #fff;
    font-size: 20px;
    font-weight: 700;
    flex-shrink: 0;
}
.cgf-upload-text {
    font-size: 13.5px;
    font-weight: 600;
    color: #1a8080;
}
</style>
@endpush

@section('content')

<div class="cgf-page">

    <a href="{{ route('admin.gallery.index') }}" class="btn-back" title="Back to Gallery" aria-label="Back to Gallery">
        <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
            <line x1="20" y1="12" x2="4" y2="12"></line>
            <polyline points="10 18 4 12 10 6"></polyline>
        </svg>
        <span>Back</span>
    </a>

    <h2 class="cgf-heading">Create a Gallery Folder</h2>

    <form method="POST" action="{{ route('admin.gallery.store') }}" enctype="multipart/form-data">
        @csrf

        {{-- ── Row 1: Folder Name + Class Year ── --}}
        <div class="cgf-row-2">
            <div class="cgf-group">
                <label class="cgf-label">Folder Name: *</label>
                <input type="text" name="folder_name" class="cgf-input"
                       placeholder="Event 1 Alumni Reunion"
                       value="{{ old('folder_name') }}"
                       required
                       oninput="updateSlug(this.value)">
            </div>
            <div class="cgf-group">
                <label class="cgf-label">Class Year: *</label>
                <select name="class_year" class="cgf-select" required>
                    <option value="">Select Year</option>
                    @foreach(range(date('Y'), 1947, -1) as $y)
                        <option value="{{ $y }}"
                            {{ old('class_year') == $y ? 'selected' : '' }}>
                            {{ $y }}
                        </option>
                    @endforeach
                </select>
            </div>
        </div>

        {{-- ── Description ── --}}
        <div class="cgf-row-1 cgf-group">
            <label class="cgf-label">Description: *</label>
            <textarea name="description" class="cgf-textarea"
                      rows="4"
                      placeholder="Describe this gallery folder..."
                      required>{{ old('description') }}</textarea>
        </div>

        {{-- ── Row 3: Type + Slug ── --}}
        <div class="cgf-row-2">
            <div class="cgf-group">
                <label class="cgf-label">Type: *</label>
                <select name="type" class="cgf-select" required>
                    <option value="">Select Type</option>
                    <option value="Conference" {{ old('type') == 'Conference' ? 'selected' : '' }}>Conference</option>
                    <option value="Private"    {{ old('type') == 'Private'    ? 'selected' : '' }}>Private</option>
                    <option value="Public"     {{ old('type') == 'Public'     ? 'selected' : '' }}>Public</option>
                </select>
            </div>
            <div class="cgf-group">
                <label class="cgf-label">Slug:</label>
                <input type="text" name="slug" id="slugField" class="cgf-input"
                       placeholder="auto-generated"
                       value="{{ old('slug') }}"
                       readonly>
                <span class="cgf-slug-preview" id="slugPreview"></span>
            </div>
        </div>

        {{-- ── Banner Image (Cover for list page) ── --}}
        <div class="cgf-row-1 cgf-group">
            <label class="cgf-label">Banner Image (Cover for Gallery List):</label>
            <div class="cgf-upload-box @error('banner_image') has-error @enderror" onclick="document.getElementById('bannerImgInput').click()">
                <div class="cgf-upload-icon">+</div>
                <div>
                    <div class="cgf-upload-text">Upload Banner / Cover Image</div>
                    <small style="font-size:11.5px;color:#888;">Displayed on the gallery album card on the list page (JPG, PNG, WebP up to 5MB).</small>
                </div>
            </div>
            <input type="file" id="bannerImgInput" name="banner_image" accept="image/jpeg,image/png,image/jpg,image/webp" style="display:none" onchange="previewBanner(this)">
            <span id="bannerFilename" style="font-size:12px;color:#1a8080;margin-top:6px;display:none;font-weight:600;"></span>
            <div id="bannerPreviewWrap" style="display:none;margin-top:10px;max-width:280px;border-radius:10px;overflow:hidden;border:1px solid #cce0e0;box-shadow:0 2px 8px rgba(0,0,0,.06)">
                <img id="bannerPreviewImg" src="" alt="Banner Preview" style="width:100%;height:140px;object-fit:cover;display:block">
            </div>
            @error('banner_image')
                <span style="color:#e24b4a;font-size:12px;margin-top:6px;display:block">{{ $message }}</span>
            @enderror
        </div>

        {{-- ── Actions ── --}}
        <div class="cgf-actions">
            <button type="submit" class="cgf-btn-save">Save</button>
            <a href="{{ route('admin.gallery.index') }}" class="cgf-btn-cancel">Cancel</a>
        </div>

    </form>
</div>

@push('scripts')
<script>
function updateSlug(val) {
    var slug = val.toLowerCase()
                  .replace(/[^a-z0-9]+/g, '-')
                  .replace(/^-|-$/g, '');
    document.getElementById('slugField').value = slug;
    var preview = document.getElementById('slugPreview');
    preview.textContent = slug
        ? "{{ url('/gallery') }}/" + slug
        : '';
}

// Restore slug from old() on validation fail
(function () {
    var existing = document.getElementById('slugField').value;
    if (existing) {
        document.getElementById('slugPreview').textContent =
            "{{ url('/gallery') }}/" + existing;
    }
})();

function previewBanner(input) {
    if (input.files && input.files[0]) {
        var file = input.files[0];
        var nameSpan = document.getElementById('bannerFilename');
        var previewWrap = document.getElementById('bannerPreviewWrap');
        var previewImg = document.getElementById('bannerPreviewImg');

        nameSpan.textContent = file.name + ' (' + Math.round(file.size / 1024) + ' KB)';
        nameSpan.style.display = 'block';

        var reader = new FileReader();
        reader.onload = function(e) {
            previewImg.src = e.target.result;
            previewWrap.style.display = 'block';
        };
        reader.readAsDataURL(file);
    }
}
</script>
@endpush

@endsection