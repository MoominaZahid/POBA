{{-- FILE: resources/views/admin/cms/verticals.blade.php --}}
@extends('layouts.admin')
@section('title','CMS Verticals - Admin')
@section('page-title','Content Management')
@section('content')

@include('admin.cms._tabs', ['active' => 'verticals'])

<div class="admin-table-wrap">
    <div class="admin-table-toolbar">
        <form method="GET" action="{{ route('admin.cms.verticals') }}" style="display:flex;gap:12px;align-items:center;flex-wrap:wrap">
            <div style="position:relative">
                <input type="text" name="search" class="search-input" placeholder="Search" value="{{ request('search') }}" style="width:220px;padding:9px 14px;border:1.5px solid var(--border);border-radius:20px;background:#E6F3F4;font-size:13px;outline:none">
            </div>
            <div>
                <select name="type" onchange="this.form.submit()" style="padding:9px 32px 9px 16px;border:1.5px solid var(--border);border-radius:20px;background:#E6F3F4;font-size:13px;color:var(--text-dark);outline:none;cursor:pointer;appearance:none;background-image:url('data:image/svg+xml,%3Csvg xmlns=\'http://www.w3.org/2000/svg\' width=\'10\' height=\'7\'%3E%3Cpath d=\'M0 0l5 7 5-7z\' fill=\'%231a7a7a\'/%3E%3C/svg%3E');background-repeat:no-repeat;background-position:right 12px center">
                    <option value="all" {{ request('type') == 'all' || !request('type') ? 'selected' : '' }}>Type</option>
                    <option value="executive" {{ request('type') == 'executive' ? 'selected' : '' }}>Executive</option>
                    <option value="working" {{ request('type') == 'working' ? 'selected' : '' }}>Working</option>
                </select>
            </div>
        </form>

        <div style="display:flex;gap:12px;align-items:center">
            <a href="{{ route('admin.cms.verticals.export') }}" class="btn-outline-teal" style="font-size:13px;padding:8px 20px;display:flex;align-items:center;gap:6px">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" y1="15" x2="12" y2="3"/></svg>
                Export
            </a>
            <a href="{{ route('admin.cms.verticals.create') }}" class="btn-teal" style="font-size:13px;padding:9px 20px">+ Add Working Committee</a>
        </div>
    </div>

    <table class="admin-table">
        <thead>
            <tr>
                <th>Title <span class="sort-icon">⇅</span></th>
                <th>Description <span class="sort-icon">⇅</span></th>
                <th style="width:120px">Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse($committees as $c)
            @php
                $isExecutive = ($c->id == 1 || $c->type === 'executive' || strtolower(trim($c->title)) === 'executive committee');
            @endphp
            <tr>
                <td style="font-weight:600;color:var(--text-dark)">{{ $c->title }}</td>
                <td style="color:var(--text-muted)">{{ Str::limit($c->description, 95) }}</td>
                <td>
                    <div class="action-icons">
                        <a href="{{ route('admin.cms.verticals.show', $c->id) }}" class="btn-view" title="View Details">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
                        </a>
                        <a href="{{ route('admin.cms.verticals.edit', $c->id) }}" class="btn-edit" title="Edit">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
                        </a>
                        @if(!$isExecutive)
                        <form method="POST" action="{{ route('admin.cms.verticals.delete', $c->id) }}" onsubmit="return confirm('Delete this committee?')">
                            @csrf @method('DELETE')
                            <button type="submit" class="btn-delete" title="Delete">
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="3 6 5 6 21 6"/><path d="M19 6l-1 14H6L5 6"/><path d="M10 11v6"/><path d="M14 11v6"/><path d="M9 6V4h6v2"/></svg>
                            </button>
                        </form>
                        @endif
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
@endsection
