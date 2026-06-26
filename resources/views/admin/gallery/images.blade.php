{{-- FILE: resources/views/admin/gallery/images.blade.php --}}
@extends('layouts.admin')
@section('title','Add Images - Admin')
@section('page-title','Gallery')

@push('styles')
<style>
.ai-page { background:#fff; padding:0; max-width:860px; }

/* Back arrow */
.ai-back {
    display:inline-flex;
    align-items:center;
    color:#1a8080;
    font-size:20px;
    font-weight:700;
    text-decoration:none;
    margin-bottom:10px;
    line-height:1;
}
.ai-back:hover { opacity:.7; }

.ai-heading {
    font-size:1.4rem;
    font-weight:700;
    color:#111;
    margin:0 0 16px;
}

/* ══ Drop zone — very compact ══ */
.ai-dropzone {
    width:100%;
    box-sizing:border-box;
    border:2px dashed #e87722;
    border-radius:10px;
    padding:14px 20px 14px;   /* top 14 bottom 14 — very tight */
    text-align:center;
    background:#fff;
    cursor:pointer;
    transition:background .15s;
    margin-bottom:20px;
}
.ai-dropzone:hover,
.ai-dropzone.dragover { background:#fff9f4; }

.ai-dz-icon { color:#e87722; line-height:1; margin-bottom:4px; }

.ai-dz-title {
    font-size:12.5px;
    font-weight:600;
    color:#333;
    margin:0 0 2px;
}
.ai-dz-hint {
    font-size:11px;
    color:#bbb;
    margin:0 0 10px;
}
.ai-browse-btn {
    display:inline-block;
    padding:6px 20px;
    background:#e87722;
    color:#fff;
    border:none;
    border-radius:999px;
    font-size:12px;
    font-weight:600;
    cursor:pointer;
    transition:opacity .15s;
}
.ai-browse-btn:hover { opacity:.85; }

/* ══ Table ══ */
.ai-table {
    width:100%;
    border-collapse:collapse;
    margin-bottom:28px;
}
/* Header row — plain text, bottom border only */
.ai-table thead th {
    padding:8px 12px;
    font-size:12.5px;
    font-weight:700;
    color:#222;
    border-bottom:1.5px solid #e8e8e8;
    text-align:left;
    background:#fff;
}
.ai-table thead th:first-child { width:46px; }
.ai-table thead th:last-child  { width:64px; text-align:center; }

/* Body rows */
.ai-table tbody td {
    padding:10px 12px;
    border-bottom:1px solid #f3f3f3;
    vertical-align:middle;
    font-size:13px;
    color:#333;
}
.ai-table tbody tr:last-child td { border-bottom:none; }

/* Thumbnail — natural image, rounded, no grey wrapper box */
.ai-thumb {
    display:block;
    width:75px;
    height:52px;
    border-radius:6px;
    object-fit:cover;
    background:#eee;
}

/* Red trash icon — just the icon, no wrapper */
.ai-del-btn {
    background:none;
    border:none;
    cursor:pointer;
    color:#e24b4a;
    display:flex;
    align-items:center;
    justify-content:center;
    margin:0 auto;
    padding:4px;
    transition:opacity .15s;
}
.ai-del-btn:hover { opacity:.6; }

/* ══ Save / Cancel — centred below table ══ */
.ai-actions {
    display:flex;
    gap:12px;
    justify-content:center;
    margin-top:32px;
    margin-bottom:40px;
}
.ai-btn-save {
    padding:10px 48px;
    background:#1a8080;
    color:#fff;
    border:none;
    border-radius:999px;
    font-size:13.5px;
    font-weight:600;
    cursor:pointer;
    transition:opacity .15s;
}
.ai-btn-save:hover { opacity:.85; }
.ai-btn-cancel {
    padding:9px 44px;
    background:#fff;
    color:#e24b4a;
    border:2px solid #e24b4a;
    border-radius:999px;
    font-size:13.5px;
    font-weight:600;
    text-decoration:none;
    display:inline-block;
    transition:background .15s;
}
.ai-btn-cancel:hover { background:#fff0f0; }

/* ══ Existing images grid ══ */
.ai-existing-heading {
    font-size:.95rem;
    font-weight:700;
    color:#222;
    margin:32px 0 14px;
}
.ai-gallery-grid {
    display:grid;
    grid-template-columns:repeat(auto-fill,minmax(160px,1fr));
    gap:14px;
    margin-bottom:32px;
}
.ai-gallery-thumb {
    position:relative;
    background:#f2f2f2;
    border-radius:8px;
    overflow:hidden;
    aspect-ratio:4/3;
}
.ai-gallery-thumb img {
    width:100%;height:100%;
    object-fit:cover;display:block;
}
.ai-gallery-del {
    position:absolute;top:6px;right:6px;
    width:26px;height:26px;
    border-radius:50%;
    background:rgba(226,75,74,.85);
    color:#fff;border:none;font-size:13px;
    cursor:pointer;
    display:flex;align-items:center;justify-content:center;
    transition:background .15s;
}
.ai-gallery-del:hover { background:#e24b4a; }

/* Flash */
.ai-flash-ok  { padding:10px 14px;background:#e1f5ee;border:1px solid #5dcaa5;border-radius:8px;color:#0f6e56;font-size:13px;margin-bottom:14px; }
.ai-flash-err { padding:10px 14px;background:#fcebeb;border:1px solid #f09595;border-radius:8px;color:#a32d2d;font-size:13px;margin-bottom:14px; }
</style>
@endpush

@section('content')
<div class="ai-page">

    <a href="{{ route('admin.gallery.edit', $folder->id) }}" class="ai-back">←</a>
    <h2 class="ai-heading">Add Images</h2>

    @if(session('success'))
        <div class="ai-flash-ok">✓ {{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="ai-flash-err">✕ {{ session('error') }}</div>
    @endif

    <form method="POST" action="{{ route('admin.gallery.addImages', $folder->id) }}"
          enctype="multipart/form-data" id="uploadForm">
        @csrf

        {{-- ── Drop zone ── --}}
        <div class="ai-dropzone" id="dropZone"
             onclick="document.getElementById('imgFiles').click()"
             ondragover="onDragOver(event)"
             ondragleave="onDragLeave(event)"
             ondrop="onDrop(event)">

            <div class="ai-dz-icon">
                <svg width="28" height="28" viewBox="0 0 24 24" fill="none"
                     stroke="currentColor" stroke-width="1.7">
                    <polyline points="16 16 12 12 8 16"/>
                    <line x1="12" y1="12" x2="12" y2="21"/>
                    <path d="M20.39 18.39A5 5 0 0 0 18 9h-1.26A8 8 0 1 0 3 16.3"/>
                </svg>
            </div>
            <p class="ai-dz-title">Choose a file or drag &amp; drop it here</p>
            <p class="ai-dz-hint">JPEG, JPG, and PNG formats, up to 5MB</p>
            <button type="button" class="ai-browse-btn"
                    onclick="event.stopPropagation();document.getElementById('imgFiles').click()">
                Browse File
            </button>
        </div>

        <input type="file" id="imgFiles" name="images[]"
               accept="image/jpeg,image/jpg,image/png"
               multiple style="display:none"
               onchange="previewImages(this.files)">

        {{-- ── Preview table (hidden until files picked) ── --}}
        <div id="previewSection" style="display:none">
            <table class="ai-table">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Image</th>
                        <th style="text-align:center">Action</th>
                    </tr>
                </thead>
                <tbody id="previewBody"></tbody>
            </table>

            <div class="ai-actions">
                <button type="submit" class="ai-btn-save">Save</button>
                <a href="{{ route('admin.gallery.index') }}" class="ai-btn-cancel">Cancel</a>
            </div>
        </div>

    </form>

    {{-- ── Existing images ── --}}
    @if($folder->images->count())
        <h3 class="ai-existing-heading">Existing Images ({{ $folder->images->count() }})</h3>
        <div class="ai-gallery-grid">
            @foreach($folder->images as $img)
                <div class="ai-gallery-thumb">
                    <img src="{{ asset('storage/'.$img->image_path) }}" alt="Gallery image"
                         onerror="this.src='https://placehold.co/300x200/eee/999?text=No+Image'">
                    <form method="POST" action="{{ route('admin.gallery.deleteImage', $img->id) }}"
                          onsubmit="return confirm('Delete this image?')">
                        @csrf @method('DELETE')
                        <button type="submit" class="ai-gallery-del" title="Delete">✕</button>
                    </form>
                </div>
            @endforeach
        </div>
    @endif

</div>
@push('scripts')
<script>
function onDragOver(e) { e.preventDefault(); document.getElementById('dropZone').classList.add('dragover'); }
function onDragLeave(e) { document.getElementById('dropZone').classList.remove('dragover'); }
function onDrop(e) {
    e.preventDefault();
    document.getElementById('dropZone').classList.remove('dragover');
    previewImages(e.dataTransfer.files);
}

var selectedFiles = [];

function previewImages(files) {
    Array.from(files).forEach(function(f) { selectedFiles.push(f); });
    rebuildPreview();
}

function rebuildPreview() {
    var body = document.getElementById('previewBody');
    body.innerHTML = '';

    selectedFiles.forEach(function(f, i) {
        var tr = document.createElement('tr');

        // # cell — correct sequential number
        var tdNum = document.createElement('td');
        tdNum.textContent = i + 1;
        tr.appendChild(tdNum);

        // Image cell — bare img, no wrapper div
        var tdImg = document.createElement('td');
        var img   = document.createElement('img');
        img.className = 'ai-thumb';
        img.alt       = f.name;
        (function(im, file) {
            var reader = new FileReader();
            reader.onload = function(e) { im.src = e.target.result; };
            reader.readAsDataURL(file);
        })(img, f);
        tdImg.appendChild(img);
        tr.appendChild(tdImg);

        // Action cell — trash icon only
        var tdAct = document.createElement('td');
        tdAct.style.textAlign = 'center';
        var btn = document.createElement('button');
        btn.type      = 'button';
        btn.className = 'ai-del-btn';
        btn.title     = 'Remove';
        btn.innerHTML = '<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2"><polyline points="3 6 5 6 21 6"/><path d="M19 6l-1 14H6L5 6"/><path d="M10 11v6"/><path d="M14 11v6"/><path d="M9 6V4h6v2"/></svg>';
        // capture current index correctly with IIFE
        (function(idx) {
            btn.onclick = function() { removeFile(idx); };
        })(i);
        tdAct.appendChild(btn);
        tr.appendChild(tdAct);

        body.appendChild(tr);
    });

    document.getElementById('previewSection').style.display = selectedFiles.length ? 'block' : 'none';
    syncFileInput();
}

function removeFile(idx) {
    selectedFiles.splice(idx, 1);
    rebuildPreview();
}

function syncFileInput() {
    try {
        var dt = new DataTransfer();
        selectedFiles.forEach(function(f) { dt.items.add(f); });
        document.getElementById('imgFiles').files = dt.files;
    } catch(e) {}
}
</script>
@endpush
@endsection