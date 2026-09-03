{{-- FILE: resources/views/customer/news/show.blade.php --}}
@extends('layouts.app')
@section('title', $item->title . ' - POBA')
@section('content')

<section class="section-pad">
    <div class="container">
        <div class="grid-2" style="gap:50px;align-items:flex-start">
            <div>
                <h1 style="font-size:2rem;font-weight:700;color:var(--teal);margin-bottom:12px;line-height:1.3">{{ $item->title }}</h1>
                <p style="font-size:14px;color:var(--text-muted);margin-bottom:20px;padding-bottom:20px;border-bottom:1px solid var(--border)">
                    Published on {{ $item->published_at ? $item->published_at->format('F j, Y') : ($item->created_at ? $item->created_at->format('F j, Y') : 'N/A') }}
                </p>
                <div style="font-size:15px;line-height:1.9;color:var(--text-dark)">{!! $item->description !!}</div>
            </div>
            <div>
                <img src="{{ $item->image ? asset('storage/'.$item->image) : 'https://placehold.co/600x400/1a7a7a/fff?text=News' }}"
                     alt="{{ $item->title }}" style="border-radius:16px;width:100%;object-fit:cover;max-height:420px">
            </div>
        </div>
        <div style="margin-top:30px">
            <a href="{{ route('news.index') }}" class="btn-outline-teal">← Back to News</a>
        </div>
    </div>
</section>
@endsection
