{{-- FILE: resources/views/customer/news/index.blade.php --}}
@extends('layouts.app')
@section('title','Latest News - POBA')
@section('content')

<style>
    .news-grid-card {
        text-decoration: none;
        color: inherit;
        display: flex;
        flex-direction: column;
        height: 100%;
        cursor: pointer;
        background: #fff;
        border-radius: 20px;
        box-shadow: 0 4px 15px rgba(0,0,0,0.05);
        overflow: hidden;
        transition: transform .2s, box-shadow .2s;
        border: 1px solid #edf2f7;
    }

    .news-grid-card:hover {
        text-decoration: none;
        color: inherit;
        transform: translateY(-4px);
        box-shadow: 0 8px 25px rgba(26,122,122,0.12);
    }

    .news-card-body {
        padding: 20px;
        display: flex;
        flex-direction: column;
        flex-grow: 1;
    }

    .news-read-more {
        margin-top: auto;
        padding-top: 14px;
        font-size: 14px;
        color: var(--teal);
        font-weight: 700;
        display: inline-flex;
        align-items: center;
        gap: 4px;
    }
</style>

<section class="section-pad">
    <div class="container">

        {{-- Heading with orange underline matching text width --}}
        <div style="text-align: center; margin-bottom: 40px;">
            <h2 style="display: inline-block; font-size: 2.2rem; font-weight: 700; color: #086666; border-bottom: 3px solid #e87722; padding-bottom: 8px; margin: 0;">
                Latest News
            </h2>
        </div>

        <div class="grid-4" style="align-items: stretch;">
            @forelse($news as $item)
            <a href="{{ route('news.show', $item->id) }}" class="news-grid-card">
                <img class="card-img" src="{{ $item->image ? asset('storage/'.$item->image) : 'https://placehold.co/400x200/1a7a7a/fff?text=News' }}" alt="{{ $item->title }}" style="width:100%;height:180px;object-fit:cover">
                <div class="news-card-body">
                    <div class="card-type" style="font-size:12px;font-weight:700;color:var(--orange);text-transform:uppercase;margin-bottom:6px">{{ strtoupper($item->type ?? 'NEWS') }}</div>
                    <h3 class="card-title" style="font-size:16px;font-weight:700;color:var(--text-dark);line-height:1.4;margin-bottom:8px">{{ $item->title }}</h3>
                    <div class="card-date" style="font-size:12px;color:var(--text-muted);font-weight:600;margin-bottom:10px">
                        {{ $item->published_at ? $item->published_at->format('d M Y') : ($item->created_at ? $item->created_at->format('d M Y') : '') }}
                    </div>
                    <p class="card-text" style="font-size:13px;color:var(--text-muted);line-height:1.6;margin-bottom:0">{{ Str::limit(strip_tags($item->description), 100) }}</p>
                    <span class="news-read-more">Read More →</span>
                </div>
            </a>
            @empty
            <div style="grid-column:1/-1;text-align:center;padding:60px;color:var(--text-muted)">No news available yet.</div>
            @endforelse
        </div>

        <div style="text-align:center;margin-top:40px">
            {{ $news->links('vendor.pagination.simple-default') }}
        </div>
    </div>
</section>
@endsection