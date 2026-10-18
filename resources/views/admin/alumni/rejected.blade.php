{{-- FILE: resources/views/admin/alumni/rejected.blade.php --}}
@extends('layouts.admin')
@section('title','Rejected Users - Admin')
@section('page-title','Alumni Users')
@section('content')

<div class="admin-table-wrap">
    <div style="padding:20px 24px 0">
        <div class="tab-btns" style="margin-bottom:0">
            <a href="{{ route('admin.alumni.index') }}" class="tab-btn" style="text-decoration:none">User List</a>
            <a href="{{ route('admin.alumni.approvals') }}" class="tab-btn" style="text-decoration:none">Approvals</a>
            <a href="{{ route('admin.alumni.rejected') }}" class="tab-btn active" style="text-decoration:none">Rejected Users</a>
        </div>
    </div>

    <div class="admin-table-toolbar">
        <form method="GET" action="{{ route('admin.alumni.rejected') }}" style="display:flex;gap:10px">
            <input type="text" name="search" class="search-input" placeholder="Search by name, email, CCP, or reason..." value="{{ request('search') }}" style="width:320px">
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
                <th>Rejection Reason <span class="sort-icon">⇅</span></th>
                <th>Date Rejected <span class="sort-icon">⇅</span></th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse($users as $u)
            <tr>
                <td><strong>{{ $u->full_name }}</strong></td>
                <td>{{ $u->email }}</td>
                <td>{{ $u->phone_number }}</td>
                <td>{{ $u->ccp_no }}</td>
                <td style="max-width:280px;">
                    <div style="background:#fee2e2;color:#991b1b;border-radius:6px;padding:4px 8px;font-size:12px;line-height:1.4;word-break:break-word;">
                        {{ $u->rejection_reason ?: 'No reason recorded' }}
                    </div>
                </td>
                <td style="font-size:12px;color:var(--text-muted)">{{ $u->updated_at?->format('M d, Y') ?? '—' }}</td>
                <td>
                    <div class="action-icons">
                        <a href="{{ route('admin.alumni.show', $u->id) }}" class="btn-view" title="View & Edit Details">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
                        </a>
                    </div>
                </td>
            </tr>
            @empty
            <tr><td colspan="7" style="text-align:center;padding:40px;color:var(--text-muted)">No rejected alumni users found.</td></tr>
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
