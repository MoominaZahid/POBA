{{-- FILE: resources/views/customer/coming-soon.blade.php --}}
@extends('layouts.app')
@section('title', (request('feature') ?? 'Coming Soon') . ' - POBA')
@section('content')

<div class="page-header">
    <h1>{{ request('feature') ?? 'Coming Soon' }}</h1>
    <div class="underline"></div>
</div>

<section class="section-pad">
    <div class="container" style="text-align:center;padding:60px 20px">
        <h2 style="color:var(--teal-deep);font-size:1.6rem;margin-bottom:12px">This page is currently under construction</h2>
        <p style="color:var(--text-muted);font-size:15px;margin-bottom:28px">
            {{ request('feature') ?? 'This feature' }} is on its way. Check back soon.
        </p>
        <a href="{{ route('home') }}" class="btn-teal-capsule">Back to Home</a>
    </div>
</section>
@endsection
