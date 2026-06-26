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
</style>
@endpush

@section('content')
<div class="cgf-page">

    {{-- Top bar --}}
    <div class="cgf-topbar">
        <a href="{{ route('admin.gallery.index') }}" class="cgf-back">←</a>
        <a href="{{ route('admin.gallery.images', $folder->id) }}" class="cgf-btn-add-images">Add Images</a>
    </div>

    <h2 class="cgf-heading">Edit {{ $folder->folder_name }}</h2>

    @if($errors->any())
        <div class="cgf-errors">
            <strong>Please fix the following errors:</strong>
            <ul>@foreach($errors->all() as $err)<li>{{ $err }}</li>@endforeach</ul>
        </div>
    @endif

    <form method="POST" action="{{ route('admin.gallery.update', $folder->id) }}">
        @csrf @method('PUT')

        {{-- Row 1: Folder Name + Class Year --}}
        <div class="cgf-row-2">
            <div class="cgf-group">
                <label class="cgf-label">Folder Name: *</label>
                <input type="text" name="folder_name" class="cgf-input"
                       value="{{ old('folder_name', $folder->folder_name) }}"
                       required oninput="updateSlug(this.value)">
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
                    {{ $folder->slug ? 'https://www.poba.socialknocks.com/'.$folder->slug.'/' : '' }}
                </span>
            </div>
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
function updateSlug(val) {
    var slug = val.toLowerCase().replace(/[^a-z0-9]+/g,'-').replace(/^-|-$/g,'');
    document.getElementById('slugField').value = slug;
    document.getElementById('slugPreview').textContent =
        slug ? 'https://www.poba.socialknocks.com/' + slug + '/' : '';
}
</script>
@endpush
@endsection