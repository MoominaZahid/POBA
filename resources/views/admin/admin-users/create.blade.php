{{-- FILE: resources/views/admin/admin-users/create.blade.php --}}
@extends('layouts.admin')
@section('title', isset($admin) ? 'Edit Admin User - POBA' : 'Add Admin User - POBA')
@section('page-title', isset($admin) ? 'Edit Admin User' : 'Add Admin User')
@section('content')

<div style="margin-bottom:16px">
    <a href="{{ route('admin.admin-users.index') }}" class="btn-back" title="Back to Admin Users" aria-label="Back to Admin Users">
        <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
            <line x1="20" y1="12" x2="4" y2="12"></line>
            <polyline points="10 18 4 12 10 6"></polyline>
        </svg>
        <span>Back</span>
    </a>
</div>

<div class="admin-form-page" style="max-width:780px">
    <h2>{{ isset($admin) ? 'Edit Admin User' : 'Add Admin User' }}</h2>

    <form method="POST"
          action="{{ isset($admin)
                     ? route('admin.admin-users.update', $admin->id)
                     : route('admin.admin-users.store') }}">
        @csrf
        @if(isset($admin)) @method('PUT') @endif

        {{-- Basic Info --}}
        <div class="admin-form-row">
            <div class="admin-form-group">
                <label class="admin-form-label">Name: *</label>
                <input type="text" name="name" class="admin-input"
                       value="{{ old('name', $admin->name ?? '') }}"
                       placeholder="Full Name" required>
            </div>
            <div class="admin-form-group">
                <label class="admin-form-label">Gender: *</label>
                <select name="gender" class="admin-input" required>
                    <option value="">Select Gender</option>
                    @foreach(['Male','Female','Other'] as $g)
                    <option value="{{ $g }}"
                        {{ old('gender', $admin->gender ?? '') === $g ? 'selected' : '' }}>
                        {{ $g }}
                    </option>
                    @endforeach
                </select>
            </div>
        </div>

        <div class="admin-form-group">
            <label class="admin-form-label">Email: *</label>
            <input type="email" name="email" class="admin-input"
                   value="{{ old('email', $admin->email ?? '') }}"
                   placeholder="admin@poba.com" required>
        </div>

        <div class="admin-form-group">
            <label class="admin-form-label">
                {{ isset($admin) ? 'New Password:' : 'Password:' }}
                @if(isset($admin))
                    <small style="color:var(--text-muted);font-weight:400">
                        (leave blank to keep current)
                    </small>
                @endif
            </label>
            <input type="password" name="password" class="admin-input"
                   placeholder="••••••••••"
                   {{ isset($admin) ? '' : 'required' }}>
        </div>

        {{-- Role --}}
        <div class="admin-form-group">
            <label class="admin-form-label">Role: *</label>
            <div style="display:flex;gap:24px;margin-top:10px">
                <label style="display:flex;align-items:center;gap:10px;cursor:pointer;
                              padding:14px 22px;border-radius:10px;border:2px solid var(--border);
                              flex:1;transition:all .2s" id="roleAdminLabel">
                    <input type="radio" name="role" value="admin" id="roleAdmin"
                           {{ old('role', $admin->role ?? 'admin') === 'admin' ? 'checked' : '' }}
                           onchange="toggleRoleUI()"
                           style="accent-color:var(--teal);width:18px;height:18px">
                    <div>
                        <div style="font-weight:700;font-size:15px;color:var(--text-dark)">
                            Admin
                        </div>
                        <div style="font-size:12px;color:var(--text-muted)">
                            Limited access — you choose which sections
                        </div>
                    </div>
                </label>
                <label style="display:flex;align-items:center;gap:10px;cursor:pointer;
                              padding:14px 22px;border-radius:10px;border:2px solid var(--border);
                              flex:1;transition:all .2s" id="roleSuperLabel">
                    <input type="radio" name="role" value="superadmin" id="roleSuperAdmin"
                           {{ old('role', $admin->role ?? '') === 'superadmin' ? 'checked' : '' }}
                           onchange="toggleRoleUI()"
                           style="accent-color:var(--orange);width:18px;height:18px">
                    <div>
                        <div style="font-weight:700;font-size:15px;color:var(--text-dark)">
                            ★ Super Admin
                        </div>
                        <div style="font-size:12px;color:var(--text-muted)">
                            Full access to everything — no restrictions
                        </div>
                    </div>
                </label>
            </div>
        </div>

        {{-- Permissions — hidden when SuperAdmin is selected --}}
        <div id="permissionsSection">
            <div style="margin-bottom:16px">
                <div style="display:flex;justify-content:space-between;align-items:center;
                            margin-bottom:14px">
                    <label class="admin-form-label" style="margin:0">
                        Permissions:
                        <small style="color:var(--text-muted);font-weight:400">
                            Select which sections this admin can access
                        </small>
                    </label>
                    <div style="display:flex;gap:10px">
                        <button type="button" onclick="selectAll()"
                                style="font-size:12px;color:var(--teal);background:none;
                                       border:1px solid var(--teal);padding:4px 12px;
                                       border-radius:20px;cursor:pointer">
                            Select All
                        </button>
                        <button type="button" onclick="clearAll()"
                                style="font-size:12px;color:var(--text-muted);background:none;
                                       border:1px solid var(--border);padding:4px 12px;
                                       border-radius:20px;cursor:pointer">
                            Clear All
                        </button>
                    </div>
                </div>

                <div style="display:grid;grid-template-columns:1fr 1fr;gap:10px">
                    @php
                        $currentPerms = old('permissions', isset($admin) ? ($admin->permissions ?? []) : []);
                    @endphp

                    @foreach($allPermissions as $key => $label)
                    <label class="perm-card {{ in_array($key, $currentPerms) ? 'checked' : '' }}"
                           style="display:flex;align-items:flex-start;gap:12px;padding:14px 16px;
                                  border-radius:10px;border:2px solid {{ in_array($key, $currentPerms) ? 'var(--teal)' : 'var(--border)' }};
                                  cursor:pointer;transition:all .2s;background:{{ in_array($key, $currentPerms) ? 'var(--teal-light)' : '#fff' }}"
                           onclick="togglePerm(this)">
                        <input type="checkbox" name="permissions[]" value="{{ $key }}"
                               class="perm-check"
                               {{ in_array($key, $currentPerms) ? 'checked' : '' }}
                               style="accent-color:var(--teal);width:16px;height:16px;
                                      margin-top:2px;flex-shrink:0">
                        <div>
                            <div style="font-size:14px;font-weight:600;color:var(--text-dark)">
                                {{ explode(' (', $label)[0] }}
                            </div>
                            @if(str_contains($label, '('))
                            <div style="font-size:11px;color:var(--text-muted);margin-top:2px">
                                {{ trim(explode('(', $label)[1], ')') }}
                            </div>
                            @endif
                        </div>
                    </label>
                    @endforeach
                </div>
            </div>
        </div>

        <div style="display:flex;gap:14px;margin-top:24px">
            <button type="submit" class="btn-teal" style="padding:13px 44px;font-size:15px">
                {{ isset($admin) ? 'Update Admin' : 'Create Admin' }}
            </button>
            <a href="{{ route('admin.admin-users.index') }}"
               class="btn-outline-red" style="padding:13px 44px;font-size:15px">
                Cancel
            </a>
        </div>
    </form>
</div>

@push('scripts')
<script>
function toggleRoleUI() {
    const isSuperAdmin = document.getElementById('roleSuperAdmin').checked;

    // Toggle permissions section visibility
    document.getElementById('permissionsSection').style.display =
        isSuperAdmin ? 'none' : 'block';

    // Highlight selected role card
    document.getElementById('roleAdminLabel').style.borderColor =
        !isSuperAdmin ? 'var(--teal)' : 'var(--border)';
    document.getElementById('roleSuperLabel').style.borderColor =
        isSuperAdmin ? 'var(--orange)' : 'var(--border)';
    document.getElementById('roleAdminLabel').style.background =
        !isSuperAdmin ? 'var(--teal-light)' : '#fff';
    document.getElementById('roleSuperLabel').style.background =
        isSuperAdmin ? '#fff8f0' : '#fff';
}

function togglePerm(label) {
    const cb = label.querySelector('.perm-check');
    // Let the checkbox change naturally; update UI on change
    setTimeout(() => {
        const checked = cb.checked;
        label.style.borderColor = checked ? 'var(--teal)' : 'var(--border)';
        label.style.background  = checked ? 'var(--teal-light)' : '#fff';
    }, 0);
}

function selectAll() {
    document.querySelectorAll('.perm-check').forEach(cb => {
        cb.checked = true;
        const label = cb.closest('label');
        label.style.borderColor = 'var(--teal)';
        label.style.background  = 'var(--teal-light)';
    });
}

function clearAll() {
    document.querySelectorAll('.perm-check').forEach(cb => {
        cb.checked = false;
        const label = cb.closest('label');
        label.style.borderColor = 'var(--border)';
        label.style.background  = '#fff';
    });
}

// Run on page load to set correct initial state
toggleRoleUI();
document.getElementById('roleAdmin').addEventListener('change', toggleRoleUI);
document.getElementById('roleSuperAdmin').addEventListener('change', toggleRoleUI);
</script>
@endpush
@endsection
