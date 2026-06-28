{{-- FILE: resources/views/admin/cms/verticals_edit.blade.php --}}
@extends('layouts.admin')
@section('title','Edit Committee - Admin')
@section('page-title','Content Management')
@section('content')

@include('admin.cms._tabs', ['active' => 'verticals'])

<div style="margin-bottom:16px">
    <a href="{{ route('admin.cms.verticals') }}" style="color:var(--text-muted);font-size:14px;text-decoration:none">← Back to Verticals</a>
</div>

<div class="admin-form-page">
    <h2>Edit Committee: {{ $committee->title }}</h2>
    <form method="POST" action="{{ route('admin.cms.verticals.update', $committee->id) }}">
        @csrf @method('PUT')

        <div class="admin-form-group">
            <label class="admin-form-label">Title:</label>
            <input type="text" name="title" class="admin-input" value="{{ old('title', $committee->title) }}" required>
        </div>

        <div class="admin-form-group">
            <label class="admin-form-label">Description:</label>
            <textarea name="description" class="admin-input" rows="4">{{ old('description', $committee->description) }}</textarea>
        </div>

        <div style="margin-bottom:20px">
            <label class="admin-form-label" style="color:var(--teal);font-size:15px;margin-bottom:12px;display:block">Members</label>
            <div id="memberRows">
                @forelse($committee->members as $member)
                <div class="admin-form-row member-row" style="margin-bottom:10px">
                    <div class="admin-form-group">
                        <label class="admin-form-label">Member Name:</label>
                        <input type="text" name="member_names[]" class="admin-input" value="{{ $member->member_name }}" placeholder="Member Name">
                    </div>
                    <div class="admin-form-group">
                        <label class="admin-form-label">Member URL:</label>
                        <div style="display:flex;gap:8px;align-items:center">
                            <input type="text" name="member_urls[]" class="admin-input" value="{{ $member->member_url }}" placeholder="https://www.example.com">
                            <button type="button" onclick="this.closest('.member-row').remove()" style="background:none;border:none;color:#e74c3c;cursor:pointer;font-size:18px;flex-shrink:0">🗑</button>
                        </div>
                    </div>
                </div>
                @empty
                <div class="admin-form-row member-row" style="margin-bottom:10px">
                    <div class="admin-form-group">
                        <label class="admin-form-label">Member Name:</label>
                        <input type="text" name="member_names[]" class="admin-input" placeholder="Member Name">
                    </div>
                    <div class="admin-form-group">
                        <label class="admin-form-label">Member URL:</label>
                        <div style="display:flex;gap:8px;align-items:center">
                            <input type="text" name="member_urls[]" class="admin-input" placeholder="https://www.example.com">
                            <button type="button" onclick="this.closest('.member-row').remove()" style="background:none;border:none;color:#e74c3c;cursor:pointer;font-size:18px;flex-shrink:0">🗑</button>
                        </div>
                    </div>
                </div>
                @endforelse
            </div>
            <a href="#" onclick="addMemberRow();return false" style="font-size:13px;color:var(--teal);font-weight:600">+ Add row</a>
        </div>

        <div class="btn-action-row">
            <button type="submit" class="btn-teal" style="padding:12px 40px">Save</button>
            <a href="{{ route('admin.cms.verticals') }}" class="btn-outline-red" style="padding:12px 40px">Cancel</a>
        </div>
    </form>
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
