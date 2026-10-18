{{-- FILE: resources/views/admin/cms/verticals_create.blade.php --}}
@extends('layouts.admin')
@section('title','Add Sub Working Committee - Admin')
@section('page-title','Content Management')
@section('content')

@include('admin.cms._tabs', ['active' => 'verticals'])

<div style="margin-bottom:16px">
    <a href="{{ route('admin.cms.verticals') }}" class="btn-back" title="Back to Verticals" aria-label="Back to Verticals">
        <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
            <line x1="20" y1="12" x2="4" y2="12"></line>
            <polyline points="10 18 4 12 10 6"></polyline>
        </svg>
        <span>Back</span>
    </a>
    <h2 style="color:#1a202c;font-size:22px;font-weight:800;margin:6px 0 0 0">Add Sub Working Committee</h2>
</div>

<div class="admin-form-page" style="max-width:850px;background:#fff;padding:36px;border-radius:16px;box-shadow:0 4px 15px rgba(0,0,0,0.03);border:1px solid #e2e8f0">
    <form method="POST" action="{{ route('admin.cms.verticals.store') }}">
        @csrf

        <div class="admin-form-group" style="margin-bottom:20px">
            <label class="admin-form-label" style="font-weight:700;color:#2d3748;margin-bottom:8px">Sub Working Committee Title: *</label>
            <input type="text" name="title" class="admin-input" value="{{ old('title') }}" placeholder="e.g. IT Committee, Marketing Committee" required style="background:#E6F3F4;border-radius:30px;border:1px solid #cbd5e0;padding:12px 20px;font-size:14px">
        </div>

        <div class="admin-form-group">
            <label class="admin-form-label" style="font-weight:700;color:#2d3748;margin-bottom:8px">Description:</label>
            <textarea name="description" class="admin-input" rows="4" placeholder="Brief write-up of this sub-committee's mandate and activities..." style="background:#E6F3F4;border-radius:20px;border:1px solid #cbd5e0;padding:14px 20px;font-size:14px">{{ old('description') }}</textarea>
        </div>

        <div style="margin-top:28px;margin-bottom:28px">
            <h4 style="color:var(--teal);font-size:18px;font-weight:800;margin-bottom:16px">Members</h4>
            
            <div id="memberRows">
                <div class="member-row" style="margin-bottom:14px;display:flex;gap:12px;align-items:flex-end">
                    <div class="admin-form-group" style="margin-bottom:0;flex:1">
                        <label class="admin-form-label" style="font-size:13px;font-weight:600;color:#2d3748;margin-bottom:6px">Member Name:</label>
                        <input type="text" name="member_names[]" class="admin-input" placeholder="Member Name" style="background:#E6F3F4;border-radius:30px;border:1px solid #cbd5e0;padding:10px 18px;font-size:13px">
                    </div>
                    <div class="admin-form-group" style="margin-bottom:0;flex:1">
                        <label class="admin-form-label" style="font-size:13px;font-weight:600;color:#2d3748;margin-bottom:6px">Member URL (Optional):</label>
                        <input type="text" name="member_urls[]" class="admin-input" placeholder="https://..." style="background:#E6F3F4;border-radius:30px;border:1px solid #cbd5e0;padding:10px 18px;font-size:13px">
                    </div>
                    <button type="button" onclick="removeMemberRow(this)" class="btn-delete" title="Remove Row" style="background:none;border:none;color:#e74c3c;cursor:pointer;font-size:18px;padding:10px 4px;flex-shrink:0">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="3 6 5 6 21 6"/><path d="M19 6l-1 14H6L5 6"/><path d="M10 11v6"/><path d="M14 11v6"/><path d="M9 6V4h6v2"/></svg>
                    </button>
                </div>
            </div>

            <div style="margin-top:10px">
                <a href="#" onclick="addMemberRow();return false" style="font-size:14px;color:var(--teal);font-weight:700;text-decoration:none;display:inline-flex;align-items:center;gap:4px">
                    + Add row
                </a>
            </div>
        </div>

        <div class="btn-action-row" style="margin-top:36px;display:flex;justify-content:center;gap:16px">
            <button type="submit" class="btn-teal" style="padding:12px 48px;font-size:15px;border-radius:30px">Save</button>
            <a href="{{ route('admin.cms.verticals') }}" class="btn-outline-red" style="padding:12px 48px;font-size:15px;border-radius:30px;text-decoration:none">Cancel</a>
        </div>
    </form>
</div>

@push('scripts')
<script>
function addMemberRow() {
    document.getElementById('memberRows').insertAdjacentHTML('beforeend', `
        <div class="member-row" style="margin-bottom:14px;display:flex;gap:12px;align-items:flex-end">
            <div class="admin-form-group" style="margin-bottom:0;flex:1">
                <label class="admin-form-label" style="font-size:13px;font-weight:600;color:#2d3748;margin-bottom:6px">Member Name:</label>
                <input type="text" name="member_names[]" class="admin-input" placeholder="Member Name" style="background:#E6F3F4;border-radius:30px;border:1px solid #cbd5e0;padding:10px 18px;font-size:13px">
            </div>
            <div class="admin-form-group" style="margin-bottom:0;flex:1">
                <label class="admin-form-label" style="font-size:13px;font-weight:600;color:#2d3748;margin-bottom:6px">Member URL (Optional):</label>
                <input type="text" name="member_urls[]" class="admin-input" placeholder="https://..." style="background:#E6F3F4;border-radius:30px;border:1px solid #cbd5e0;padding:10px 18px;font-size:13px">
            </div>
            <button type="button" onclick="removeMemberRow(this)" class="btn-delete" title="Remove Row" style="background:none;border:none;color:#e74c3c;cursor:pointer;font-size:18px;padding:10px 4px;flex-shrink:0">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="3 6 5 6 21 6"/><path d="M19 6l-1 14H6L5 6"/><path d="M10 11v6"/><path d="M14 11v6"/><path d="M9 6V4h6v2"/></svg>
            </button>
        </div>`);
}

function removeMemberRow(btn) {
    const rows = document.querySelectorAll('.member-row');
    if (rows.length > 1) {
        btn.closest('.member-row').remove();
    } else {
        const inputs = btn.closest('.member-row').querySelectorAll('input');
        inputs.forEach(i => i.value = '');
    }
}
</script>
@endpush
@endsection
