{{-- FILE: resources/views/auth/login.blade.php --}}
@extends('layouts.app')
@section('title','Login - POBA')
@section('content')
<div class="login-wrap">
    <div class="login-container">
        <h1 class="login-heading">Login</h1>
        <div class="login-heading-underline"></div>
        <p class="login-subtitle">Login to access Alumni Section</p>

    <div class="login-box">
        @if($errors->any())
            <div class="alert alert-danger">{{ $errors->first() }}</div>
        @endif

        <form method="POST" action="{{ route('login.post') }}">
            @csrf
            <div class="login-field-group">
                <label class="login-field-label">Email</label>
                <input type="email" name="email" class="login-input" placeholder="mail@abc.com" value="{{ old('email') }}" required>
            </div>
            <div class="login-field-group" style="margin-bottom:0">
                <label class="login-field-label">Password</label>
                <div class="login-pw-wrap">
                    <input type="password" name="password" class="login-input" id="pwField" placeholder="••••••••••••••••" required>
                    <button type="button" onclick="togglePw()" id="pwToggleBtn" class="login-pw-toggle" aria-label="Show password" title="Toggle password visibility">
                        <svg id="pwIconShow" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="display:block">
                            <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
                            <circle cx="12" cy="12" r="3"></circle>
                        </svg>
                        <svg id="pwIconHide" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="display:none">
                            <path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19m-6.72-1.07a3 3 0 1 1-4.24-4.24"></path>
                            <line x1="1" y1="1" x2="23" y2="23"></line>
                        </svg>
                    </button>
                </div>
            </div>
            <div class="login-remember-row">
                <label style="display:flex;align-items:center;gap:8px;font-size:13px;cursor:pointer">
                    <input type="checkbox" name="remember" style="accent-color:var(--teal)"> Remember Me
                </label>
                <a href="{{ route('password.request') }}" style="font-size:13px;font-weight:600;color:var(--teal)">Forgot Password?</a>
            </div>
            <button type="submit" class="btn-login">Login</button>
        </form>
    </div>
    </div>
</div>

@if(session('blocked_modal'))
@php $blocked = session('blocked_modal'); @endphp
<div class="modal-overlay open" id="blockedModal" style="position:fixed;inset:0;background:rgba(0,0,0,0.55);z-index:9999;display:flex;align-items:center;justify-content:center;padding:16px;">
    <div style="background:#fff;border-radius:14px;max-width:480px;width:100%;box-shadow:0 20px 40px rgba(0,0,0,0.25);overflow:hidden;animation:popIn .2s ease;">
        <div style="background:{{ ($blocked['type'] ?? '') === 'rejected' ? '#fee2e2' : '#fef3c7' }};padding:20px 24px;border-bottom:1px solid {{ ($blocked['type'] ?? '') === 'rejected' ? '#fca5a5' : '#fcd34d' }};display:flex;align-items:center;gap:12px;">
            <div style="width:38px;height:38px;border-radius:50%;background:{{ ($blocked['type'] ?? '') === 'rejected' ? '#ef4444' : '#f59e0b' }};color:#fff;display:flex;align-items:center;justify-content:center;font-size:18px;font-weight:bold;flex-shrink:0;">
                !
            </div>
            <div>
                <h3 style="margin:0;font-size:17px;font-weight:700;color:{{ ($blocked['type'] ?? '') === 'rejected' ? '#991b1b' : '#92400e' }};">
                    {{ $blocked['title'] ?? 'Access Blocked' }}
                </h3>
                <span style="font-size:12px;color:#666;">Account Notice</span>
            </div>
        </div>
        <div style="padding:22px 24px;color:#333;font-size:14px;line-height:1.6;">
            <p style="margin:0 0 12px 0;">
                Your account has been <strong>{{ $blocked['type'] ?? 'restricted' }}</strong>.
            </p>
            @if(!empty($blocked['reason']))
            <div style="background:#f8fafc;border-left:4px solid {{ ($blocked['type'] ?? '') === 'rejected' ? '#ef4444' : '#f59e0b' }};padding:10px 14px;border-radius:4px;margin-bottom:14px;font-size:13px;color:#334155;">
                <strong>Reason:</strong> {{ $blocked['reason'] }}
            </div>
            @endif
            <p style="margin:0 0 8px 0;font-size:13px;color:#555;">
                Please contact support for assistance:
            </p>
            <div style="background:#f1f5f9;border-radius:8px;padding:12px;font-size:13px;display:flex;flex-direction:column;gap:6px;">
                @if(!empty($blocked['email']))
                <div><strong>Email:</strong> <a href="mailto:{{ $blocked['email'] }}" style="color:var(--teal,#0d9488);font-weight:600;text-decoration:none;">{{ $blocked['email'] }}</a></div>
                @endif
                @if(!empty($blocked['phone']))
                <div><strong>Phone:</strong> <a href="tel:{{ $blocked['phone'] }}" style="color:var(--teal,#0d9488);font-weight:600;text-decoration:none;">{{ $blocked['phone'] }}</a></div>
                @endif
            </div>
        </div>
        <div style="padding:14px 24px;background:#f8fafc;border-top:1px solid #e2e8f0;display:flex;justify-content:flex-end;">
            <button type="button" onclick="document.getElementById('blockedModal').remove()" style="padding:8px 20px;border:none;background:var(--teal,#0d9488);color:#fff;border-radius:6px;font-size:13px;font-weight:600;cursor:pointer;">
                Close
            </button>
        </div>
    </div>
</div>
@endif
@push('scripts')
<script>
function togglePw(){
    const f = document.getElementById('pwField');
    const show = document.getElementById('pwIconShow');
    const hide = document.getElementById('pwIconHide');
    const btn = document.getElementById('pwToggleBtn');
    const isHidden = f.type === 'password';
    f.type = isHidden ? 'text' : 'password';
    show.style.display = isHidden ? 'none' : 'block';
    hide.style.display = isHidden ? 'block' : 'none';
    btn.setAttribute('aria-label', isHidden ? 'Hide password' : 'Show password');
}
</script>
@endpush
@endsection
