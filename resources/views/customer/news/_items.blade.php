{{-- FILE: resources/views/customer/news/_items.blade.php --}}
@forelse($news as $item)
<a href="{{ route('news.show', $item->id) }}" class="news-grid-card fade-in-card">
    <img class="card-img" 
         src="{{ $item->image ? media_url($item->image) : 'https://placehold.co/400x200/1a7a7a/fff?text=News' }}" 
         alt="{{ $item->title }}" 
         loading="lazy"
         style="width:100%;height:180px;object-fit:cover">
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
@if(!request()->ajax())
<div style="grid-column:1/-1;text-align:center;padding:60px;color:var(--text-muted)">No news available yet.</div>
@endif
@endforelse
