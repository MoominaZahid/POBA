{{-- FILE: resources/views/admin/gallery/index.blade.php --}}
@extends('layouts.admin')
@section('title','Gallery - Admin')
@section('page-title','Gallery')

@push('styles')
<style>
/* ═══════════════════════════════════════════════
   GALLERY INDEX  –  pixel-perfect alignment fix
   ═══════════════════════════════════════════════ */

.gi-page { background: #fff; border-radius: 12px; padding: 24px; }

/* ── Toolbar ──────────────────────────────────── */
.gi-toolbar {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 20px;
    gap: 12px;
    flex-wrap: wrap;
}

/* Search pill with icon */
.gi-search-wrap {
    position: relative;
    width: 220px;
}
.gi-search-wrap svg {
    position: absolute;
    left: 14px;
    top: 50%;
    transform: translateY(-50%);
    color: #aaa;
    pointer-events: none;
}
.gi-search {
    width: 100%;
    box-sizing: border-box;
    background: #e8f4f4;
    border: none;
    border-radius: 30px;
    padding: 9px 16px 9px 38px;
    font-size: 13px;
    color: #333;
    outline: none;
    transition: background .15s, box-shadow .15s;
}
.gi-search:focus {
    background: #d6eeee;
    box-shadow: 0 0 0 2px rgba(26,128,128,.2);
}
.gi-search::placeholder { color: #9db8b8; }

/* Toolbar right buttons */
.gi-toolbar-btns { display: flex; gap: 10px; align-items: center; }

.gi-btn-export {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    padding: 9px 20px;
    background: #fff;
    color: #e87722;
    border: 2px solid #e87722;
    border-radius: 30px;
    font-size: 13px;
    font-weight: 600;
    text-decoration: none;
    cursor: pointer;
    transition: background .15s;
}
.gi-btn-export:hover { background: #fff5ee; }

.gi-btn-add {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    padding: 9px 20px;
    background: #1a8080;
    color: #fff;
    border: none;
    border-radius: 30px;
    font-size: 13px;
    font-weight: 600;
    text-decoration: none;
    cursor: pointer;
    transition: opacity .15s;
}
.gi-btn-add:hover { opacity: .85; color: #fff; }
.gi-btn-add-icon {
    width: 20px;
    height: 20px;
    background: rgba(255,255,255,.25);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 16px;
    font-weight: 400;
    line-height: 1;
}

/* ── Table ────────────────────────────────────── */
.gi-table {
    width: 100%;
    border-collapse: separate;
    border-spacing: 0;
    font-size: 13.5px;
}

/* Header row: light mint background */
.gi-table thead tr th {
    background: #f0fafa;
    padding: 14px 16px;
    vertical-align: middle;
    font-weight: 700;
    color: #222;
    font-size: 13px;
    border-bottom: 1px solid #e0eeee;
    white-space: nowrap;
    text-align: left;
    box-sizing: border-box;
    position: relative; /* Needed to hold the sort arrows */
}
.gi-table thead tr th:first-child { border-radius: 8px 0 0 0; }
.gi-table thead tr th:last-child  { 
    border-radius: 0 8px 0 0; 
    text-align: center !important; /* Centering the Actions header */
}

/* Sort arrows - Absolutely positioned to remove them from layout flow */
.gi-sort {
    position: absolute;
    right: 8px;
    top: 50%;
    transform: translateY(-50%);
    display: flex;
    flex-direction: column;
    align-items: center;
    line-height: 1;
    gap: 1px;
    color: #aaa;
    font-size: 9px;
    cursor: pointer;
    pointer-events: none;
}
.gi-sort span { display: block; font-size: 8px; }

/* Body rows */
.gi-table tbody tr td {
    padding: 14px 16px;
    color: #333;
    border-bottom: 1px solid #f3f3f3;
    vertical-align: middle;
    text-align: left;
    box-sizing: border-box;
}
.gi-table tbody tr:hover td { background: #f8fdfd; }
.gi-table tbody tr:last-child td { border-bottom: none; }
.gi-table tbody tr td:last-child { text-align: center !important; } /* Centering the Actions content */

/* Action icons */
.gi-actions {
    display: flex;
    align-items: center;
    justify-content: center; /* Centering icons vertically and horizontally */
    gap: 12px;
}
.gi-action-btn {
    background: none;
    border: none;
    padding: 0;
    cursor: pointer;
    display: flex;
    align-items: center;
    color: #1a8080;
    text-decoration: none;
    transition: opacity .15s;
}
.gi-action-btn:hover { opacity: .65; }
.gi-action-btn.red   { color: #e24b4a; }

/* Empty state */
.gi-empty {
    text-align: center;
    padding: 48px;
    color: #aaa;
    font-size: 14px;
}

/* ── Footer: per-page + pagination + count ────── */
.gi-footer {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding-top: 16px;
    margin-top: 8px;
    border-top: 1px solid #eee;
    font-size: 13px;
    color: #666;
    flex-wrap: wrap;
    gap: 10px;
}

.gi-perpage {
    display: flex;
    align-items: center;
    gap: 8px;
    font-size: 13px;
    color: #555;
}
.gi-perpage select {
    background: #e8f4f4;
    border: none;
    border-radius: 20px;
    padding: 5px 28px 5px 12px;
    font-size: 13px;
    color: #333;
    cursor: pointer;
    outline: none;
    appearance: none;
    -webkit-appearance: none;
    background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='10' height='6' viewBox='0 0 10 6'%3E%3Cpath d='M1 1l4 4 4-4' stroke='%231a8080' stroke-width='1.5' fill='none' stroke-linecap='round'/%3E%3C/svg%3E");
    background-repeat: no-repeat;
    background-position: right 10px center;
}

/* Pagination links styling */
.gi-footer nav { display: flex; align-items: center; }
.gi-footer nav .pagination {
    display: flex;
    gap: 4px;
    list-style: none;
    margin: 0;
    padding: 0;
    align-items: center;
}
.gi-footer nav .pagination li a,
.gi-footer nav .pagination li span {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    width: 28px;
    height: 28px;
    border-radius: 6px;
    font-size: 13px;
    color: #555;
    text-decoration: none;
    border: 1px solid #e0e0e0;
    background: #fff;
    transition: all .15s;
}
.gi-footer nav .pagination li span[aria-current] {
    background: #1a8080;
    color: #fff;
    border-color: #1a8080;
}
.gi-footer nav .pagination li a:hover {
    background: #e8f4f4;
    border-color: #1a8080;
    color: #1a8080;
}

/* Flash messages */
.gi-flash-ok  { padding:11px 16px; background:#e1f5ee; border:1px solid #5dcaa5; border-radius:8px; color:#0f6e56; font-size:13px; margin-bottom:16px; }
.gi-flash-err { padding:11px 16px; background:#fcebeb; border:1px solid #f09595; border-radius:8px; color:#a32d2d; font-size:13px; margin-bottom:16px; }
</style>
@endpush

@section('content')

<div class="gi-page">

    @if(session('success'))
        <div class="gi-flash-ok">✓ {{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="gi-flash-err">✕ {{ session('error') }}</div>
    @endif

    {{-- Toolbar --}}
    <div class="gi-toolbar">
        {{-- Search --}}
        <form method="GET" action="{{ route('admin.gallery.index') }}">
            <div class="gi-search-wrap">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none"
                     stroke="currentColor" stroke-width="2.2">
                    <circle cx="11" cy="11" r="8"/>
                    <line x1="21" y1="21" x2="16.65" y2="16.65"/>
                </svg>
                <input type="text" name="search" class="gi-search"
                       placeholder="Search" value="{{ request('search') }}">
            </div>
        </form>

        {{-- Right buttons --}}
        <div class="gi-toolbar-btns">
            <a href="#" class="gi-btn-export">
                📥 Export
            </a>
            <a href="{{ route('admin.gallery.create') }}" class="gi-btn-add">
                <span class="gi-btn-add-icon">+</span>
                Add Folder
            </a>
        </div>
    </div>

    {{-- Table --}}
    <table class="gi-table">
        <thead>
            <tr>
                <th>
                    Folder Name
                    <span class="gi-sort">
                        <span>▲</span><span>▼</span>
                    </span>
                </th>
                <th>
                    Type
                    <span class="gi-sort">
                        <span>▲</span><span>▼</span>
                    </span>
                </th>
                <th>
                    Class Year
                    <span class="gi-sort">
                        <span>▲</span><span>▼</span>
                    </span>
                </th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse($folders as $folder)
            <tr>
                <td>{{ $folder->folder_name }}</td>
                <td>{{ $folder->type }}</td>
                <td>{{ $folder->class_year }}</td>
                <td>
                    <div class="gi-actions">
                        {{-- View --}}
                        <a href="{{ route('admin.gallery.images', $folder->id) }}"
                           class="gi-action-btn" title="View Images">
                            <svg width="17" height="17" viewBox="0 0 24 24" fill="none"
                                 stroke="currentColor" stroke-width="2.2">
                                <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/>
                                <circle cx="12" cy="12" r="3"/>
                            </svg>
                        </a>
                        {{-- Edit --}}
                        <a href="{{ route('admin.gallery.edit', $folder->id) }}"
                           class="gi-action-btn" title="Edit">
                            <svg width="17" height="17" viewBox="0 0 24 24" fill="none"
                                 stroke="currentColor" stroke-width="2.2">
                                <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/>
                                <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/>
                            </svg>
                        </a>
                        {{-- Delete --}}
                        <form method="POST"
                              action="{{ route('admin.gallery.destroy', $folder->id) }}"
                              onsubmit="return confirm('Delete this folder and all its images?')">
                            @csrf @method('DELETE')
                            <button type="submit" class="gi-action-btn red" title="Delete">
                                <svg width="17" height="17" viewBox="0 0 24 24" fill="none"
                                     stroke="currentColor" stroke-width="2.2">
                                    <polyline points="3 6 5 6 21 6"/>
                                    <path d="M19 6l-1 14H6L5 6"/>
                                    <path d="M10 11v6"/><path d="M14 11v6"/>
                                    <path d="M9 6V4h6v2"/>
                                </svg>
                            </button>
                        </form>
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="4" class="gi-empty">No gallery folders found.</td>
            </tr>
            @endforelse
        </tbody>
    </table>

    {{-- Footer: per-page + pagination + count --}}
    <div class="gi-footer">
        {{-- Per page selector --}}
        <form method="GET" action="{{ route('admin.gallery.index') }}"
              style="display:flex;align-items:center;gap:0">
            @if(request('search'))
                <input type="hidden" name="search" value="{{ request('search') }}">
            @endif
            <div class="gi-perpage">
                Result per page
                <select name="per_page" onchange="this.form.submit()">
                    @foreach([10, 25, 50, 100] as $n)
                        <option value="{{ $n }}"
                            {{ request('per_page', 10) == $n ? 'selected' : '' }}>
                            {{ $n }}
                        </option>
                    @endforeach
                </select>
            </div>
        </form>

        {{-- Pagination --}}
        <div>{{ $folders->links() }}</div>

        {{-- Count --}}
        <div>
            {{ $folders->firstItem() ?? 0 }}–{{ $folders->lastItem() ?? 0 }}
            of {{ $folders->total() }}
        </div>
    </div>

</div>
@endsection