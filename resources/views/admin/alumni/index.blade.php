{{-- FILE: resources/views/admin/alumni/index.blade.php --}}
@extends('layouts.admin')
@section('title','Alumni Users - Admin')
@section('page-title','Alumni Users')
@section('content')

<style>
.modal-overlay {
    display: none; position: fixed; inset: 0; background: rgba(0,0,0,.45);
    z-index: 1000; align-items: center; justify-content: center;
}
.modal-overlay.open { display: flex; }
.modal-box {
    background: #fff; border-radius: 14px; padding: 28px 32px; width: 480px;
    max-width: 95vw; position: relative; box-shadow: 0 10px 30px rgba(0,0,0,.15);
}
.modal-close {
    position: absolute; top: 14px; right: 16px; background: none; border: none;
    font-size: 20px; cursor: pointer; color: #888;
}
</style>

<div class="admin-table-wrap">
    <div style="padding:20px 24px 0">
        <div class="tab-btns" style="margin-bottom:0">
            <a href="{{ route('admin.alumni.index') }}" class="tab-btn {{ request()->routeIs('admin.alumni.index') ? 'active' : '' }}" style="text-decoration:none">User List</a>
            <a href="{{ route('admin.alumni.approvals') }}" class="tab-btn {{ request()->routeIs('admin.alumni.approvals') ? 'active' : '' }}" style="text-decoration:none">Approvals</a>
            <a href="{{ route('admin.alumni.rejected') }}" class="tab-btn {{ request()->routeIs('admin.alumni.rejected') ? 'active' : '' }}" style="text-decoration:none">Rejected Users</a>
        </div>
    </div>

    <div class="admin-table-toolbar">
        <form method="GET" action="{{ route('admin.alumni.index') }}" style="display:flex;gap:12px;align-items:center;flex-wrap:wrap;">
            <input type="text" name="search" class="search-input" placeholder="Search by name, email, or CCP..." value="{{ request('search') }}" style="width:260px">
            <select name="status" onchange="this.form.submit()" class="filter-select"
                    style="min-width:160px;height:42px;padding:9px 36px 9px 16px;font-size:13px;border-radius:30px;border:1.5px solid var(--border);background-color:#fff;cursor:pointer;outline:none;appearance:none;-webkit-appearance:none;box-sizing:border-box;">
                <option value="" {{ request('status') == '' ? 'selected' : '' }}>All Statuses</option>
                <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
                <option value="deactivated" {{ request('status') == 'deactivated' ? 'selected' : '' }}>Deactivated</option>
            </select>
            @if(request('search') || request('status'))
                <a href="{{ route('admin.alumni.index') }}" style="font-size:12px;color:var(--text-muted);text-decoration:none;">Clear Filters</a>
            @endif
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
            <tr id="alumni-row-{{ $u->id }}">
                <td>{{ $u->full_name }}</td>
                <td>{{ $u->email }}</td>
                <td>{{ $u->phone_number }}</td>
                <td>{{ $u->ccp_no }}</td>
                <td>{{ $u->current_city }}</td>
                <td>
                    <div style="display:flex;align-items:center;gap:8px;">
                        <label class="toggle" style="margin:0;">
                            <input type="checkbox"
                                   id="toggle-{{ $u->id }}"
                                   {{ $u->is_active ? 'checked' : '' }}
                                   onchange="handleToggleClick({{ $u->id }}, @js($u->full_name), this)">
                            <span class="toggle-slider"></span>
                        </label>
                        <span id="badge-{{ $u->id }}"
                              class="badge {{ $u->is_active ? 'badge-success' : 'badge-danger' }}"
                              style="font-size:11px;"
                              title="{{ !$u->is_active && $u->deactivation_reason ? 'Reason: ' . $u->deactivation_reason : '' }}">
                            {{ $u->is_active ? 'Active' : 'Deactivated' }}
                        </span>
                    </div>
                    @if(!$u->is_active && $u->deactivation_reason)
                        <div id="reason-text-{{ $u->id }}" style="font-size:11px;color:#dc2626;margin-top:2px;max-width:200px;line-height:1.2;">
                            {{ Str::limit($u->deactivation_reason, 45) }}
                        </div>
                    @else
                        <div id="reason-text-{{ $u->id }}" style="display:none;font-size:11px;color:#dc2626;margin-top:2px;max-width:200px;line-height:1.2;"></div>
                    @endif
                </td>
                <td>
                    <div class="action-icons">
                        <a href="{{ route('admin.alumni.show', $u->id) }}" class="btn-view" title="View">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
                        </a>
                    </div>
                </td>
            </tr>
            @empty
            <tr><td colspan="7" style="text-align:center;padding:40px;color:var(--text-muted)">No alumni users found.</td></tr>
            @endforelse
        </tbody>
    </table>

    <div class="admin-table-footer">
        <div>
            <span>Result per page</span>
            <select style="border:1px solid var(--border);border-radius:6px;padding:4px 8px;margin-left:8px;font-size:13px">
                <option>10</option><option>25</option><option>50</option>
            </select>
        </div>
        <div>{{ $users->links('vendor.pagination.simple-default') }}</div>
        <div>{{ $users->firstItem() ?? 0 }}-{{ $users->lastItem() ?? 0 }} of {{ $users->total() }}</div>
    </div>
</div>

{{-- ── Deactivation Confirmation Modal ── --}}
<div class="modal-overlay" id="deactivateModal">
    <div class="modal-box">
        <button type="button" class="modal-close" onclick="closeDeactivateModal()">&times;</button>
        <h3 style="font-size:17px;font-weight:700;color:#dc2626;margin-bottom:8px;display:flex;align-items:center;gap:8px;">
            Deactivate User
        </h3>
        <p style="font-size:13px;color:#555;margin-bottom:16px;">
            Are you sure you want to deactivate <strong id="deactivateUserName">this user</strong>? They will be logged out immediately and blocked from logging in.
        </p>

        <form id="deactivateForm" onsubmit="submitDeactivation(event)">
            <input type="hidden" id="deactivateUserId" value="">
            <div style="margin-bottom:18px;">
                <label style="display:block;font-size:12px;font-weight:600;margin-bottom:6px;color:#333;">
                    Deactivation Reason <span style="color:#e53e3e;">*</span>
                </label>
                <textarea id="deactivateReason" rows="3" required
                          placeholder="Provide a mandatory reason for deactivation..."
                          style="width:100%;border:1px solid #cbd5e1;border-radius:8px;padding:10px;font-size:13px;box-sizing:border-box;outline:none;resize:vertical;"></textarea>
                <div id="deactivateError" style="display:none;color:#dc2626;font-size:11px;margin-top:4px;"></div>
            </div>

            <div style="display:flex;justify-content:flex-end;gap:10px;">
                <button type="button" onclick="closeDeactivateModal()"
                        style="padding:8px 18px;background:#f1f5f9;color:#475569;border:1px solid #cbd5e1;border-radius:6px;font-size:13px;font-weight:600;cursor:pointer;">
                    Cancel
                </button>
                <button type="submit" id="deactivateSubmitBtn"
                        style="padding:8px 20px;background:#dc2626;color:#fff;border:none;border-radius:6px;font-size:13px;font-weight:600;cursor:pointer;">
                    Deactivate User
                </button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
let currentToggleCheckbox = null;

function handleToggleClick(id, name, checkbox) {
    if (!checkbox.checked) {
        // User was active, admin wants to deactivate -> revert view and open modal
        checkbox.checked = true;
        currentToggleCheckbox = checkbox;
        document.getElementById('deactivateUserId').value = id;
        document.getElementById('deactivateUserName').textContent = name;
        document.getElementById('deactivateReason').value = '';
        document.getElementById('deactivateError').style.display = 'none';
        document.getElementById('deactivateModal').classList.add('open');
        document.getElementById('deactivateReason').focus();
    } else {
        // User was deactivated, admin wants to reactivate
        fetch(`/admin/alumni-users/${id}/toggle-status`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Content-Type': 'application/json',
                'Accept': 'application/json'
            },
            body: JSON.stringify({})
        })
        .then(r => r.json())
        .then(data => {
            if (data.success) {
                checkbox.checked = data.is_active;
                const badge = document.getElementById(`badge-${id}`);
                const reasonDiv = document.getElementById(`reason-text-${id}`);
                if (badge) {
                    badge.className = 'badge badge-success';
                    badge.textContent = 'Active';
                    badge.title = '';
                }
                if (reasonDiv) {
                    reasonDiv.textContent = '';
                    reasonDiv.style.display = 'none';
                }
            } else {
                checkbox.checked = false;
                alert(data.message || 'Could not reactivate user.');
            }
        })
        .catch(() => {
            checkbox.checked = false;
            alert('A network error occurred. Please try again.');
        });
    }
}

function closeDeactivateModal() {
    document.getElementById('deactivateModal').classList.remove('open');
    currentToggleCheckbox = null;
}

function submitDeactivation(e) {
    e.preventDefault();
    const id = document.getElementById('deactivateUserId').value;
    const reason = document.getElementById('deactivateReason').value.trim();
    const errorDiv = document.getElementById('deactivateError');
    const submitBtn = document.getElementById('deactivateSubmitBtn');

    if (!reason) {
        errorDiv.textContent = 'A deactivation reason is required.';
        errorDiv.style.display = 'block';
        return;
    }

    submitBtn.disabled = true;
    submitBtn.textContent = 'Deactivating...';

    fetch(`/admin/alumni-users/${id}/toggle-status`, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Content-Type': 'application/json',
            'Accept': 'application/json'
        },
        body: JSON.stringify({ reason: reason })
    })
    .then(r => r.json())
    .then(data => {
        submitBtn.disabled = false;
        submitBtn.textContent = 'Deactivate User';

        if (data.success) {
            if (currentToggleCheckbox) {
                currentToggleCheckbox.checked = false;
            }
            const badge = document.getElementById(`badge-${id}`);
            const reasonDiv = document.getElementById(`reason-text-${id}`);
            if (badge) {
                badge.className = 'badge badge-danger';
                badge.textContent = 'Deactivated';
                badge.title = 'Reason: ' + reason;
            }
            if (reasonDiv) {
                reasonDiv.textContent = reason.length > 45 ? reason.substring(0, 45) + '...' : reason;
                reasonDiv.style.display = 'block';
            }
            closeDeactivateModal();
        } else {
            errorDiv.textContent = data.message || 'Failed to deactivate user.';
            errorDiv.style.display = 'block';
        }
    })
    .catch(() => {
        submitBtn.disabled = false;
        submitBtn.textContent = 'Deactivate User';
        errorDiv.textContent = 'A network error occurred. Please try again.';
        errorDiv.style.display = 'block';
    });
}

// Close on Escape or click outside
document.addEventListener('keydown', e => {
    if (e.key === 'Escape') closeDeactivateModal();
});
document.getElementById('deactivateModal').addEventListener('click', function(e) {
    if (e.target === this) closeDeactivateModal();
});
</script>
@endpush
@endsection
