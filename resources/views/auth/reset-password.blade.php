{{-- FILE: resources/views/auth/reset-password.blade.php --}}
@extends('layouts.app')
@section('title','Reset Password - POBA')
@section('content')
<div class="login-wrap">
    <div class="login-container">
        <h1 class="login-heading">Reset Password</h1>
        <div class="login-heading-underline"></div>
        <p class="login-subtitle" style="text-decoration:none">Choose a new password for your account</p>

        <div class="login-box">
            @if($errors->any())
                <div class="alert alert-danger">{{ $errors->first() }}</div>
            @endif

            <form method="POST" action="{{ route('password.update') }}">
                @csrf
                <input type="hidden" name="token" value="{{ $token }}">
                <div class="login-field-group">
                    <label class="login-field-label">Email</label>
                    <input type="email" name="email" class="login-input" placeholder="mail@abc.com" value="{{ old('email', $email) }}" required autofocus>
                </div>
                <div class="login-field-group">
                    <label class="login-field-label">New Password</label>
                    <input type="password" name="password" class="login-input" placeholder="••••••••••••••••" required>
                </div>
                <div class="login-field-group">
                    <label class="login-field-label">Confirm New Password</label>
                    <input type="password" name="password_confirmation" class="login-input" placeholder="••••••••••••••••" required>
                </div>
                <button type="submit" class="btn-login">Reset Password</button>
            </form>
        </div>
    </div>
</div>
@endsection
