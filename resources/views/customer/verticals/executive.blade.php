{{-- FILE: resources/views/customer/verticals/executive.blade.php --}}
@extends('layouts.app')
@section('title', 'Executive Committee - POBA Verticals')
@section('content')

<div class="page-header" style="background:#fff;padding:50px 0 30px;text-align:center">
    <div class="container">
        <h1 class="vertical-detail-heading">Executive Committee</h1>
        <div class="vertical-detail-underline"></div>
    </div>
</div>

<section class="section-pad" style="background:#fff;padding-top:20px">
    <div class="container">
        <p class="vertical-detail-intro">
            {{ $executive->description ?? "Lorem ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged." }}
        </p>

        @if($executive && $executive->members && count($executive->members) > 0)
        <div class="vertical-member-grid">
            @foreach($executive->members as $m)
            <div class="vertical-member-card">
                @if($m->member_url)
                    <a href="{{ $m->member_url }}" target="_blank" rel="noopener noreferrer" title="{{ $m->member_name }}">
                        {{ $m->member_name }}
                    </a>
                @else
                    {{ $m->member_name }}
                @endif
            </div>
            @endforeach
        </div>
        @else
        <div style="text-align:center;padding:40px;color:var(--text-muted)">
            No Executive Committee members listed yet.
        </div>
        @endif
    </div>
</section>

@endsection
