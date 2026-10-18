{{-- FILE: resources/views/admin/alumni/approvals.blade.php --}}
@extends('layouts.admin')
@section('title','Approvals - Admin')
@section('page-title','Alumni Users')
@section('content')

<div class="admin-table-wrap">
    <div style="padding:20px 24px 0">
        <div class="tab-btns" style="margin-bottom:0">
            <a href="{{ route('admin.alumni.index') }}" class="tab-btn" style="text-decoration:none">User List</a>
            <a href="{{ route('admin.alumni.approvals') }}" class="tab-btn active" style="text-decoration:none">Approvals</a>
            <a href="{{ route('admin.alumni.rejected') }}" class="tab-btn" style="text-decoration:none">Rejected Users</a>
        </div>
    </div>

    <div class="admin-table-toolbar">
        <form method="GET" action="{{ route('admin.alumni.approvals') }}">
            <input type="text" name="search" class="search-input" placeholder="Search" value="{{ request('search') }}" style="width:260px">
        </form>
        <a href="{{ route('admin.alumni.export') }}" class="btn-outline-teal" style="font-size:13px;padding:8px 18px">⬆ Export</a>
    </div>

    <table class="admin-table">
        <thead>
            <tr>
                <th>Name <span class="sort-icon">⇅</span></th>
                <th>Email <span class="sort-icon">⇅</span></th>
                <th>Phone Number <span class="sort-icon">⇅</span></th>
                <th>CCP No. <span class="sort-icon">⇅</span></th>
                <th>City <span class="sort-icon">⇅</span></th>
                <th>Status <span class="sort-icon">⇅</span></th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse($users as $u)
            <tr>
                <td>{{ $u->full_name }}</td>
                <td>{{ $u->email }}</td>
                <td>{{ $u->phone_number }}</td>
                <td>{{ $u->ccp_no }}</td>
                <td>{{ $u->current_city }}</td>
                <td><span class="badge badge-warning">Pending</span></td>
                <td>
                    <div class="action-icons">
                        <a href="{{ route('admin.alumni.show', $u->id) }}" class="btn-view" title="View">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
                        </a>
                    </div>
                </td>
            </tr>
            @empty
            <tr><td colspan="7" style="text-align:center;padding:40px;color:var(--text-muted)">No pending approvals.</td></tr>
            @endforelse
        </tbody>
    </table>

    <div class="admin-table-footer">
        <div>Result per page <select style="border:1px solid var(--border);border-radius:6px;padding:4px 8px;margin-left:8px;font-size:13px"><option>10</option><option>25</option></select></div>
        <div>{{ $users->links('vendor.pagination.simple-default') }}</div>
        <div>{{ $users->firstItem() ?? 0 }}-{{ $users->lastItem() ?? 0 }} of {{ $users->total() }}</div>
    </div>
</div>
@endsection
