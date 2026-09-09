{{-- FILE: resources/views/auth/forgot-password.blade.php --}}
@extends('layouts.app')
@section('title','Forgot Password - POBA')
@section('content')
<div class="login-wrap">
    <div class="login-container">
        <h1 class="login-heading">Forgot Password</h1>
        <div class="login-heading-underline"></div>
        <p class="login-subtitle" style="text-decoration:none">Enter your email to receive a password reset link</p>

        <div class="login-box">
            @if(session('status'))
                <div class="alert alert-success">{{ session('status') }}</div>
            @endif
            @if($errors->any())
                <div class="alert alert-danger">{{ $errors->first() }}</div>
            @endif

            <form method="POST" action="{{ route('password.email') }}">
                @csrf
                <div class="login-field-group">
                    <label class="login-field-label">Email</label>
                    <input type="email" name="email" class="login-input" placeholder="mail@abc.com" value="{{ old('email') }}" required autofocus>
                </div>
                <button type="submit" class="btn-login">Send Reset Link</button>
            </form>
            <p style="text-align:center;margin-top:20px;font-size:14px">
                <a href="{{ route('login') }}" style="color:var(--teal);font-weight:600">Back to Login</a>
            </p>
        </div>
    </div>
</div>
@endsection
