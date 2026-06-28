{{-- FILE: resources/views/admin/cms/verticals.blade.php --}}
@extends('layouts.admin')
@section('title','CMS Verticals - Admin')
@section('page-title','Content Management')
@section('content')

@include('admin.cms._tabs', ['active' => 'verticals'])

<div class="admin-table-wrap">
    <div class="admin-table-toolbar">
        <form method="GET" action="{{ route('admin.cms.verticals') }}" style="display:flex;gap:10px">
            <input type="text" name="search" class="search-input" placeholder="Search" value="{{ request('search') }}" style="width:220px">
        </form>
        <a href="#" onclick="document.getElementById('addCommitteeModal').classList.add('open');return false" class="btn-teal" style="font-size:13px;padding:9px 20px">+ Add Working Committee</a>
    </div>

    <table class="admin-table">
        <thead>
            <tr>
                <th>Title <span class="sort-icon">⇅</span></th>
                <th>Description <span class="sort-icon">⇅</span></th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse($committees as $c)
            <tr>
                <td>{{ $c->title }}</td>
                <td>{{ Str::limit($c->description, 80) }}</td>
                <td>
                    <div class="action-icons">
                        <a href="{{ route('admin.cms.verticals.edit', $c->id) }}" class="btn-view" title="Edit">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
                        </a>
                        <a href="{{ route('admin.cms.verticals.edit', $c->id) }}" class="btn-edit" title="Edit">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
                        </a>
                        <form method="POST" action="{{ route('admin.cms.verticals.delete', $c->id) }}" onsubmit="return confirm('Delete this committee?')">
                            @csrf @method('DELETE')
                            <button type="submit" class="btn-delete" title="Delete">
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="3 6 5 6 21 6"/><path d="M19 6l-1 14H6L5 6"/><path d="M10 11v6"/><path d="M14 11v6"/><path d="M9 6V4h6v2"/></svg>
                            </button>
                        </form>
                    </div>
                </td>
            </tr>
            @empty
            <tr><td colspan="3" style="text-align:center;padding:40px;color:var(--text-muted)">No committees found.</td></tr>
            @endforelse
        </tbody>
    </table>
    <div class="admin-table-footer">
        <div>{{ $committees->links('vendor.pagination.simple-default') }}</div>
    </div>
</div>

{{-- Add Committee Modal --}}
<div class="modal-overlay" id="addCommitteeModal">
    <div class="modal-box" style="max-width:700px;width:95%">
        <button class="modal-close" onclick="document.getElementById('addCommitteeModal').classList.remove('open')">✕</button>
        <h3>Add Working Committee</h3>
        <form method="POST" action="{{ route('admin.cms.verticals.store') }}">
            @csrf
            <div class="admin-form-group">
                <label class="admin-form-label">Title:</label>
                <input type="text" name="title" class="admin-input" placeholder="Admin Committee" required>
            </div>
            <div class="admin-form-group">
                <label class="admin-form-label">Description:</label>
                <textarea name="description" class="admin-input" rows="3" placeholder="Committee description..."></textarea>
            </div>
            <div style="margin-bottom:14px">
                <label class="admin-form-label" style="color:var(--teal);font-size:15px">Members</label>
                <div id="memberRows">
                    <div class="admin-form-row member-row" style="margin-bottom:10px">
                        <div class="admin-form-group">
                            <label class="admin-form-label">Member Name:</label>
                            <input type="text" name="member_names[]" class="admin-input" placeholder="Ashfaq Ahmad">
                        </div>
                        <div class="admin-form-group">
                            <label class="admin-form-label">Member URL:</label>
                            <div style="display:flex;gap:8px;align-items:center">
                                <input type="text" name="member_urls[]" class="admin-input" placeholder="https://www.example.com">
                                <button type="button" onclick="this.closest('.member-row').remove()" style="background:none;border:none;color:#e74c3c;cursor:pointer;font-size:18px;flex-shrink:0">🗑</button>
                            </div>
                        </div>
                    </div>
                </div>
                <a href="#" onclick="addMemberRow();return false" style="font-size:13px;color:var(--teal);font-weight:600">+ Add row</a>
            </div>
            <div class="btn-action-row">
                <button type="submit" class="btn-teal">Save</button>
                <button type="button" class="btn-outline-red" onclick="document.getElementById('addCommitteeModal').classList.remove('open')">Cancel</button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
function addMemberRow() {
    document.getElementById('memberRows').insertAdjacentHTML('beforeend', `
        <div class="admin-form-row member-row" style="margin-bottom:10px">
            <div class="admin-form-group">
                <label class="admin-form-label">Member Name:</label>
                <input type="text" name="member_names[]" class="admin-input" placeholder="Member Name">
            </div>
            <div class="admin-form-group">
                <label class="admin-form-label">Member URL:</label>
                <div style="display:flex;gap:8px;align-items:center">
                    <input type="text" name="member_urls[]" class="admin-input" placeholder="https://...">
                    <button type="button" onclick="this.closest('.member-row').remove()" style="background:none;border:none;color:#e74c3c;cursor:pointer;font-size:18px;flex-shrink:0">🗑</button>
                </div>
            </div>
        </div>`);
}
</script>
@endpush
@endsection
