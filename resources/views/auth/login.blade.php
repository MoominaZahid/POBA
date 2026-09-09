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
            <div class="login-field-group" style="position:relative;margin-bottom:0">
                <label class="login-field-label">Password</label>
                <input type="password" name="password" class="login-input" id="pwField" placeholder="••••••••••••••••" required>
                <button type="button" onclick="togglePw()" id="pwToggleBtn" aria-label="Show password" style="position:absolute;right:14px;top:38px;background:none;border:none;cursor:pointer;color:#888">
                    <svg id="pwIconShow" width="18" height="18" viewBox="0 0 16 16" fill="currentColor" style="display:inline"><path d="M10.5 8a2.5 2.5 0 1 1-5 0 2.5 2.5 0 0 1 5 0z"/><path d="M0 8s3-5.5 8-5.5S16 8 16 8s-3 5.5-8 5.5S0 8 0 8zm8 3.5a3.5 3.5 0 1 0 0-7 3.5 3.5 0 0 0 0 7z"/></svg>
                    <svg id="pwIconHide" width="18" height="18" viewBox="0 0 16 16" fill="currentColor" style="display:none"><path d="M10.79 12.912l-1.614-1.615a3.5 3.5 0 0 1-4.474-4.474l-2.06-2.06C.938 6.278 0 8 0 8s3 5.5 8 5.5a7.03 7.03 0 0 0 2.79-.588zM5.21 3.088A7.03 7.03 0 0 1 8 2.5c5 0 8 5.5 8 5.5s-.939 1.721-2.641 3.238l-2.062-2.062a3.5 3.5 0 0 0-4.474-4.474L5.21 3.089z"/><path d="M5.525 7.646a2.5 2.5 0 0 0 2.829 2.829l-2.83-2.829zm4.95.708-2.829-2.83a2.5 2.5 0 0 1 2.829 2.829zm3.171 6-12-12 .708-.708 12 12-.708.708z"/></svg>
                </button>
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
@push('scripts')
<script>
function togglePw(){
    const f = document.getElementById('pwField');
    const show = document.getElementById('pwIconShow');
    const hide = document.getElementById('pwIconHide');
    const btn = document.getElementById('pwToggleBtn');
    const isHidden = f.type === 'password';
    f.type = isHidden ? 'text' : 'password';
    show.style.display = isHidden ? 'none' : 'inline';
    hide.style.display = isHidden ? 'inline' : 'none';
    btn.setAttribute('aria-label', isHidden ? 'Hide password' : 'Show password');
}
</script>
@endpush
@endsection
