{{-- FILE: resources/views/admin/cms/verticals_edit.blade.php --}}
@extends('layouts.admin')
@section('title','Edit Committee - Admin')
@section('page-title','Content Management')
@section('content')

@include('admin.cms._tabs', ['active' => 'verticals'])

@php
    $isMain = $committee->isMain() || ($committee->id == 1 || $committee->type === 'executive' || strtolower(trim($committee->title)) === 'executive committee' || strtolower(trim($committee->title)) === 'working committees' || strtolower(trim($committee->title)) === 'working committee');
@endphp

<div style="margin-bottom:16px;display:flex;align-items:flex-start;justify-content:space-between;flex-wrap:wrap;gap:12px">
    <div>
        <a href="{{ route('admin.cms.verticals') }}" class="btn-back" title="Back to Verticals" aria-label="Back to Verticals">
            <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                <line x1="20" y1="12" x2="4" y2="12"></line>
                <polyline points="10 18 4 12 10 6"></polyline>
            </svg>
            <span>Back</span>
        </a>
        <h2 style="color:#1a202c;font-size:22px;font-weight:800;margin:6px 0 0 0">Edit Committee: {{ $committee->title }}</h2>
    </div>
    @if($isMain)
        <span style="background:#E6F3F4;color:#02828e;padding:6px 14px;border-radius:20px;font-size:12px;font-weight:700">
            🔒 Main Committee (Non-deletable)
        </span>
    @else
        <span style="background:#f1f5f9;color:#64748b;padding:6px 14px;border-radius:20px;font-size:12px;font-weight:600">
            Sub Working Committee
        </span>
    @endif
</div>

<div class="admin-form-page" style="max-width:850px;background:#fff;padding:36px;border-radius:16px;box-shadow:0 4px 15px rgba(0,0,0,0.03);border:1px solid #e2e8f0">
    <form method="POST" action="{{ route('admin.cms.verticals.update', $committee->id) }}">
        @csrf @method('PUT')

        <div class="admin-form-group" style="margin-bottom:20px">
            <label class="admin-form-label" style="font-weight:700;color:#2d3748;margin-bottom:8px">Title: *</label>
            <input type="text" name="title" class="admin-input" value="{{ old('title', $committee->title) }}" required style="background:#E6F3F4;border-radius:30px;border:1px solid #cbd5e0;padding:12px 20px;font-size:14px">
        </div>

        <div class="admin-form-group">
            <label class="admin-form-label" style="font-weight:700;color:#2d3748;margin-bottom:8px">Description:</label>
            <textarea name="description" class="admin-input" rows="4" placeholder="Enter committee description..." style="background:#E6F3F4;border-radius:20px;border:1px solid #cbd5e0;padding:14px 20px;font-size:14px">{{ old('description', $committee->description) }}</textarea>
            @if($isMain)
                <small style="color:var(--text-muted);display:block;margin-top:6px">This description dynamically appears on the About Us page and the Verticals overview section.</small>
            @endif
        </div>

        {{-- Member Management: Available for Executive Committee and Sub Working Committees --}}
        @if($committee->type === 'executive' || !$isMain)
        <div style="margin-top:28px;margin-bottom:28px">
            <h4 style="color:var(--teal);font-size:18px;font-weight:800;margin-bottom:16px">Members</h4>

            <div id="memberRows">
                @forelse($committee->members as $member)
                <div class="member-row" style="margin-bottom:14px;display:flex;gap:12px;align-items:flex-end">
                    <div class="admin-form-group" style="margin-bottom:0;flex:1">
                        <label class="admin-form-label" style="font-size:13px;font-weight:600;color:#2d3748;margin-bottom:6px">Member Name:</label>
                        <input type="text" name="member_names[]" class="admin-input" value="{{ $member->member_name }}" placeholder="Member Name" style="background:#E6F3F4;border-radius:30px;border:1px solid #cbd5e0;padding:10px 18px;font-size:13px">
                    </div>
                    <div class="admin-form-group" style="margin-bottom:0;flex:1">
                        <label class="admin-form-label" style="font-size:13px;font-weight:600;color:#2d3748;margin-bottom:6px">Member URL (Optional):</label>
                        <input type="text" name="member_urls[]" class="admin-input" value="{{ $member->member_url }}" placeholder="https://..." style="background:#E6F3F4;border-radius:30px;border:1px solid #cbd5e0;padding:10px 18px;font-size:13px">
                    </div>
                    <button type="button" onclick="removeMemberRow(this)" class="btn-delete" title="Remove Row" style="background:none;border:none;color:#e74c3c;cursor:pointer;font-size:18px;padding:10px 4px;flex-shrink:0">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="3 6 5 6 21 6"/><path d="M19 6l-1 14H6L5 6"/><path d="M10 11v6"/><path d="M14 11v6"/><path d="M9 6V4h6v2"/></svg>
                    </button>
                </div>
                @empty
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
                @endforelse
            </div>

            <div style="margin-top:10px">
                <a href="#" onclick="addMemberRow();return false" style="font-size:14px;color:var(--teal);font-weight:700;text-decoration:none;display:inline-flex;align-items:center;gap:4px">
                    + Add row
                </a>
            </div>
        </div>
        @else
        <div style="margin-top:20px;padding:14px 18px;background:#f8fafc;border-radius:12px;border:1px dashed #cbd5e0;color:#64748b;font-size:13px">
            ℹ️ Members for Working Committees are organized inside their individual Sub Working Committees.
        </div>
        @endif

        <div class="btn-action-row" style="margin-top:36px;display:flex;justify-content:center;gap:16px">
            <button type="submit" class="btn-teal" style="padding:12px 48px;font-size:15px;border-radius:30px">Save</button>
            <a href="{{ route('admin.cms.verticals') }}" class="btn-outline-red" style="padding:12px 48px;font-size:15px;border-radius:30px;text-decoration:none">Cancel</a>
        </div>
    </form>
</div>

@push('scripts')
<script>
function addMemberRow() {
    const container = document.getElementById('memberRows');
    if (!container) return;
    container.insertAdjacentHTML('beforeend', `
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
