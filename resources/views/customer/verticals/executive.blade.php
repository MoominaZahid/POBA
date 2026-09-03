{{-- FILE: resources/views/customer/verticals/executive.blade.php --}}
@extends('layouts.app')
@section('title', 'Executive Committee - POBA Verticals')
@section('content')

<div class="page-header" style="background:#fff;padding:50px 0 30px;text-align:center">
    <div class="container">
        <h1 style="font-size:2.5rem;font-weight:800;color:var(--teal);margin-bottom:8px">Executive Committee</h1>
        <div class="underline" style="width:140px;height:3.5px;background:var(--orange);margin:0 auto;border-radius:2px"></div>
    </div>
</div>

<section class="section-pad" style="background:#fff;padding-top:20px">
    <div class="container">
        <p style="text-align:center;max-width:850px;margin:0 auto 40px;color:#4a5568;font-size:15px;line-height:1.8">
            {{ $executive->description ?? "Lorem ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged." }}
        </p>

        @if($executive && $executive->members && count($executive->members) > 0)
        <div style="display:grid;grid-template-columns:repeat(auto-fill, minmax(210px, 1fr));gap:16px;max-width:1050px;margin:0 auto">
            @foreach($executive->members as $m)
            <div style="background:#E6F3F4;border:1px solid #cbd5e0;border-radius:30px;padding:12px 22px;text-align:center;color:#02828e;font-weight:700;font-size:14px;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;box-shadow:0 2px 6px rgba(0,0,0,0.02)">
                @if($m->member_url)
                    <a href="{{ $m->member_url }}" target="_blank" rel="noopener noreferrer" style="color:inherit;text-decoration:none" title="{{ $m->member_name }}">
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
