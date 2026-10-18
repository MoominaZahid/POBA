{{-- FILE: resources/views/admin/cms/verticals_show.blade.php --}}
@extends('layouts.admin')
@section('title','Committee Details - Admin')
@section('page-title','Content Management')
@section('content')

@include('admin.cms._tabs', ['active' => 'verticals'])

<div style="margin-bottom:16px;display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:12px">
    <a href="{{ route('admin.cms.verticals') }}" class="btn-back" title="Back to Verticals" aria-label="Back to Verticals">
        <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
            <line x1="20" y1="12" x2="4" y2="12"></line>
            <polyline points="10 18 4 12 10 6"></polyline>
        </svg>
        <span>Back to Verticals</span>
    </a>
    <a href="{{ route('admin.cms.verticals.edit', $committee->id) }}" class="btn-teal" style="font-size:13px;padding:9px 24px">
        Edit Committee
    </a>
</div>

<div class="admin-form-page" style="max-width:850px;background:#fff;padding:36px;border-radius:16px;box-shadow:0 4px 15px rgba(0,0,0,0.03);border:1px solid #e2e8f0">
    <div style="margin-bottom:24px">
        <div style="display:flex;align-items:center;gap:12px;margin-bottom:12px">
            <h2 style="font-size:24px;font-weight:800;color:var(--text-dark);margin:0">{{ $committee->title }}</h2>
            @if($committee->isMain())
                <span style="font-size:12px;font-weight:700;padding:4px 12px;border-radius:20px;background:var(--orange);color:#fff">
                    Main Committee
                </span>
            @else
                <span style="font-size:12px;font-weight:700;padding:4px 12px;border-radius:20px;background:var(--teal);color:#fff">
                    Sub Working Committee
                </span>
            @endif
        </div>
        <p style="font-size:15px;color:var(--text-muted);line-height:1.7;white-space:pre-line">{{ $committee->description ?: 'No description provided.' }}</p>
    </div>

    <hr style="border:none;border-top:1px solid #edf2f7;margin:24px 0">

    @if($committee->type === 'working' && $committee->isMain())
    <div>
        <h4 style="color:var(--teal);font-size:18px;font-weight:800;margin-bottom:18px">Sub Working Committees ({{ $committee->subCommittees->count() }})</h4>
        @if($committee->subCommittees->count() > 0)
        <div style="display:flex;flex-direction:column;gap:12px">
            @foreach($committee->subCommittees as $sub)
            <div style="display:flex;align-items:center;justify-content:space-between;padding:14px 20px;background:#f8fafc;border-radius:14px;border:1px solid #cbd5e0">
                <div>
                    <strong style="font-size:14px;color:var(--text-dark)">{{ $sub->title }}</strong>
                    <div style="font-size:12px;color:var(--text-muted);margin-top:2px">{{ Str::limit($sub->description, 80) }}</div>
                </div>
                <div style="display:flex;gap:8px">
                    <a href="{{ route('admin.cms.verticals.show', $sub->id) }}" class="btn-outline-teal" style="font-size:12px;padding:6px 14px">View</a>
                    <a href="{{ route('admin.cms.verticals.edit', $sub->id) }}" class="btn-teal" style="font-size:12px;padding:6px 14px">Edit</a>
                </div>
            </div>
            @endforeach
        </div>
        @else
        <p style="color:var(--text-muted);font-size:14px">No sub working committees created yet.</p>
        @endif
    </div>
    @else
    <div>
        <h4 style="color:var(--teal);font-size:18px;font-weight:800;margin-bottom:18px">Committee Members ({{ $committee->members->count() }})</h4>

        @if($committee->members->count() > 0)
        <div style="display:flex;flex-direction:column;gap:12px">
            @foreach($committee->members as $member)
            <div style="display:flex;align-items:center;justify-content:space-between;padding:14px 20px;background:#E6F3F4;border-radius:14px;border:1px solid #cbd5e0">
                <div>
                    <strong style="font-size:14px;color:var(--text-dark)">{{ $member->member_name }}</strong>
                </div>
                <div>
                    @if($member->member_url)
                    <a href="{{ $member->member_url }}" target="_blank" rel="noopener noreferrer" style="font-size:13px;color:var(--teal);font-weight:600;display:inline-flex;align-items:center;gap:4px">
                        {{ $member->member_url }} ↗
                    </a>
                    @else
                    <span style="font-size:13px;color:var(--text-muted)">—</span>
                    @endif
                </div>
            </div>
            @endforeach
        </div>
        @else
        <p style="color:var(--text-muted);font-size:14px">No members added to this committee yet.</p>
        @endif
    </div>
    @endif
</div>
@endsection
