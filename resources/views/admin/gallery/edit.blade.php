{{-- FILE: resources/views/admin/gallery/edit.blade.php --}}
@extends('layouts.admin')
@section('title','Edit Gallery Folder - Admin')
@section('page-title','Gallery')

@push('styles')
<style>
/* ═══════════════════════════════════════════════
   EDIT GALLERY FOLDER  –  pixel-perfect Figma
   ═══════════════════════════════════════════════ */

.cgf-page { background: #f8fcfc; padding: 0; max-width: 780px; }

/* Top bar: back arrow left, Add Images btn right */
.cgf-topbar {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 18px;
}
.cgf-back {
    color: #1a8080;
    font-size: 18px;
    font-weight: 700;
    text-decoration: none;
    line-height: 1;
}
.cgf-back:hover { opacity: .75; }

/* "Add Images" — orange outline pill (matches Figma exactly) */
.cgf-btn-add-images {
    display: inline-block;
    padding: 9px 24px;
    background: #fff;
    color: #e87722;
    border: 2px solid #e87722;
    border-radius: 30px;
    font-size: 13px;
    font-weight: 600;
    text-decoration: none;
    transition: background .15s;
}
.cgf-btn-add-images:hover { background: #fff5ee; }

.cgf-heading {
    font-size: 1.55rem;
    font-weight: 700;
    color: #111;
    margin: 0 0 28px;
}

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

/* Mint pill inputs */
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
.cgf-input::placeholder { color: #9db8b8; }
.cgf-input:focus,
.cgf-textarea:focus,
.cgf-select:focus {
    background: #d6eeee;
    box-shadow: 0 0 0 2px rgba(26,128,128,.2);
}

.cgf-textarea {
    border-radius: 14px;
    resize: vertical;
    min-height: 100px;
    line-height: 1.6;
}

.cgf-select {
    cursor: pointer;
    padding-right: 36px;
    background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='8' viewBox='0 0 12 8'%3E%3Cpath d='M1 1l5 5 5-5' stroke='%231a8080' stroke-width='1.8' fill='none' stroke-linecap='round'/%3E%3C/svg%3E");
    background-repeat: no-repeat;
    background-position: right 14px center;
}

.cgf-input[readonly] { color: #555; cursor: default; }

.cgf-slug-preview {
    font-size: 11.5px;
    color: #888;
    margin-top: 6px;
    display: block;
    word-break: break-all;
    line-height: 1.5;
}

.cgf-actions {
    display: flex;
    gap: 16px;
    margin-top: 32px;
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
    text-decoration: none;
    display: inline-block;
    transition: background .15s;
}
.cgf-btn-cancel:hover { background: #fff0f0; }

@media (max-width: 580px) { .cgf-row-2 { grid-template-columns: 1fr; } }

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

    {{-- Top bar --}}
    <div class="cgf-topbar">
        <a href="{{ route('admin.gallery.index') }}" class="btn-back" title="Back to Gallery" aria-label="Back to Gallery">
            <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                <line x1="20" y1="12" x2="4" y2="12"></line>
                <polyline points="10 18 4 12 10 6"></polyline>
            </svg>
            <span>Back</span>
        </a>
        <a href="{{ route('admin.gallery.images', $folder->id) }}" class="cgf-btn-add-images">Add Images</a>
    </div>

    <h2 class="cgf-heading">Edit {{ $folder->folder_name }}</h2>

    <form method="POST" action="{{ route('admin.gallery.update', $folder->id) }}" enctype="multipart/form-data">
        @csrf @method('PUT')

        {{-- Row 1: Folder Name + Class Year --}}
        <div class="cgf-row-2">
            <div class="cgf-group">
                <label class="cgf-label">Folder Name: *</label>
                <input type="text" name="folder_name" class="cgf-input"
                       value="{{ old('folder_name', $folder->folder_name) }}"
                       required>
            </div>
            <div class="cgf-group">
                <label class="cgf-label">Class Year: *</label>
                <select name="class_year" class="cgf-select" required>
                    @foreach(range(date('Y'), 1947, -1) as $y)
                        <option value="{{ $y }}"
                            {{ (old('class_year', $folder->class_year) == $y) ? 'selected' : '' }}>
                            {{ $y }}
                        </option>
                    @endforeach
                </select>
            </div>
        </div>

        {{-- Description --}}
        <div class="cgf-row-1 cgf-group">
            <label class="cgf-label">Description: *</label>
            <textarea name="description" class="cgf-textarea" rows="4" required>{{ old('description', $folder->description) }}</textarea>
        </div>

        {{-- Row 3: Type + Slug --}}
        <div class="cgf-row-2">
            <div class="cgf-group">
                <label class="cgf-label">Type: *</label>
                <select name="type" class="cgf-select" required>
                    @foreach(['Conference','Private','Public'] as $t)
                        <option value="{{ $t }}"
                            {{ (old('type', $folder->type) == $t) ? 'selected' : '' }}>
                            {{ $t }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="cgf-group">
                <label class="cgf-label">Slug:</label>
                <input type="text" name="slug" id="slugField" class="cgf-input"
                       value="{{ old('slug', $folder->slug) }}" readonly>
                <span class="cgf-slug-preview" id="slugPreview">
                    {{ $folder->slug ? url('/gallery/'.$folder->slug) : '' }}
                </span>
                <small style="font-size:11px;color:#888;margin-top:4px;">Permanent URL slug (fixed on creation to ensure shared links never break).</small>
            </div>
        </div>

        {{-- Row 4: Banner Image (Cover for list page) --}}
        <div class="cgf-row-1 cgf-group">
            <label class="cgf-label">Banner Image (Cover for Gallery List):</label>

            @if($folder->banner_image)
                <div id="currentBannerBox" style="margin-bottom:12px;display:flex;align-items:center;gap:14px;background:#e8f4f4;padding:12px 16px;border-radius:12px;border:1px solid #b2d8d8;max-width:440px;">
                    <img src="{{ media_url($folder->banner_image) }}" alt="Current Banner" style="width:90px;height:60px;object-fit:cover;border-radius:8px;border:1px solid #cce0e0;">
                    <div style="flex:1;">
                        <div style="font-size:12.5px;font-weight:600;color:#1a8080;">Current Banner Image</div>
                        <label style="display:inline-flex;align-items:center;gap:6px;font-size:12px;color:#e24b4a;cursor:pointer;margin-top:4px;">
                            <input type="checkbox" name="remove_banner_image" value="1" onchange="toggleRemoveBanner(this)">
                            <span>Remove this banner image</span>
                        </label>
                    </div>
                </div>
            @endif

            <div class="cgf-upload-box @error('banner_image') has-error @enderror" onclick="document.getElementById('bannerImgInput').click()">
                <div class="cgf-upload-icon">+</div>
                <div>
                    <div class="cgf-upload-text">{{ $folder->banner_image ? 'Upload New Banner / Cover Image' : 'Upload Banner / Cover Image' }}</div>
                    <small style="font-size:11.5px;color:#888;">Displayed on the gallery album card on the list page (JPG, PNG, WebP up to 5MB).</small>
                </div>
            </div>
            <input type="file" id="bannerImgInput" name="banner_image" accept="image/jpeg,image/png,image/jpg,image/webp" style="display:none" onchange="previewBanner(this)">
            <span id="bannerFilename" style="font-size:12px;color:#1a8080;margin-top:6px;display:none;font-weight:600;"></span>
            <div id="bannerPreviewWrap" style="display:none;margin-top:10px;max-width:280px;border-radius:10px;overflow:hidden;border:1px solid #cce0e0;box-shadow:0 2px 8px rgba(0,0,0,.06)">
                <img id="bannerPreviewImg" src="" alt="New Banner Preview" style="width:100%;height:140px;object-fit:cover;display:block">
            </div>
            @error('banner_image')
                <span style="color:#e24b4a;font-size:12px;margin-top:6px;display:block">{{ $message }}</span>
            @enderror
        </div>

        {{-- Actions --}}
        <div class="cgf-actions">
            <button type="submit" class="cgf-btn-save">Save</button>
            <a href="{{ route('admin.gallery.index') }}" class="cgf-btn-cancel">Cancel</a>
        </div>
    </form>
</div>

@push('scripts')
<script>
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

function toggleRemoveBanner(checkbox) {
    var box = document.getElementById('currentBannerBox');
    if (!box) return;
    if (checkbox.checked) {
        box.style.opacity = '0.5';
    } else {
        box.style.opacity = '1';
    }
}
</script>
@endpush
@endsection