{{-- FILE: resources/views/admin/cms/promotions.blade.php --}}
@extends('layouts.admin')
@section('title','CMS Promotions - Admin')
@section('page-title','Content Management')
@section('content')
@include('admin.cms._tabs', ['active' => 'promotions'])

<div class="admin-table-wrap">
    <div class="admin-table-toolbar">
        <span style="font-size:16px;font-weight:700;color:var(--text-dark)">Promotions</span>
        <a href="#" onclick="document.getElementById('addPromoModal').classList.add('open');return false" class="btn-teal" style="font-size:13px;padding:9px 20px">+ Add News</a>
    </div>

    <table class="admin-table">
        <thead>
            <tr>
                <th>Title <span class="sort-icon">⇅</span></th>
                <th>URL <span class="sort-icon">⇅</span></th>
                <th>Description <span class="sort-icon">⇅</span></th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse($promos as $p)
            <tr>
                <td>{{ $p->title }}</td>
                <td><a href="{{ $p->url }}" target="_blank" style="color:var(--teal);font-size:12px">{{ Str::limit($p->url, 30) }}</a></td>
                <td>{{ Str::limit($p->description, 80) }}</td>
                <td>
                    <div class="action-icons">
                        <form method="POST" action="{{ route('admin.cms.promotions.delete', $p->id) }}" onsubmit="return confirm('Delete this promotion?')">
                            @csrf @method('DELETE')
                            <button type="submit" class="btn-delete" title="Delete">
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="3 6 5 6 21 6"/><path d="M19 6l-1 14H6L5 6"/><path d="M10 11v6"/><path d="M14 11v6"/><path d="M9 6V4h6v2"/></svg>
                            </button>
                        </form>
                    </div>
                </td>
            </tr>
            @empty
            <tr><td colspan="4" style="text-align:center;padding:40px;color:var(--text-muted)">No promotions yet.</td></tr>
            @endforelse
        </tbody>
    </table>
</div>

{{-- Add Promo Modal --}}
<div class="modal-overlay" id="addPromoModal">
    <div class="modal-box" style="max-width:700px;width:95%">
        <button class="modal-close" onclick="document.getElementById('addPromoModal').classList.remove('open')">✕</button>
        <h3>Add Promotion</h3>
        <form method="POST" action="{{ route('admin.cms.promotions.store') }}" enctype="multipart/form-data">
            @csrf
            <div class="admin-form-row" style="grid-template-columns:auto 1fr 1fr 1fr">
                <div class="admin-form-group">
                    <label class="admin-form-label">Image:</label>
                    <div class="admin-upload" onclick="document.getElementById('promoImg').click()" style="padding:14px">
                        <span>➕</span><p style="font-size:11px">Upload</p>
                    </div>
                    <input type="file" id="promoImg" name="image" accept="image/*" style="display:none">
                </div>
                <div class="admin-form-group">
                    <label class="admin-form-label">Title:</label>
                    <input type="text" name="title" class="admin-input" placeholder="Promotion Title" required>
                </div>
                <div class="admin-form-group">
                    <label class="admin-form-label">Expiry Date:</label>
                    <input type="date" name="expiry_date" class="admin-input">
                </div>
                <div class="admin-form-group">
                    <label class="admin-form-label">Expiry Time:</label>
                    <input type="time" name="expiry_time" class="admin-input">
                </div>
            </div>
            <div class="admin-form-group">
                <label class="admin-form-label">Description:</label>
                <textarea name="description" class="admin-input" rows="4" placeholder="Promotion details..."></textarea>
            </div>
            <div class="admin-form-group">
                <label class="admin-form-label">URL:</label>
                <input type="url" name="url" class="admin-input" placeholder="https://www.example.com">
            </div>
            <div class="btn-action-row">
                <button type="submit" class="btn-teal">Save</button>
                <button type="button" class="btn-outline-red" onclick="document.getElementById('addPromoModal').classList.remove('open')">Cancel</button>
            </div>
        </form>
    </div>
</div>
@endsection
