{{-- FILE: resources/views/customer/gallery/index.blade.php --}}
@extends('layouts.app')
@section('title','Gallery - POBA')

@push('styles')
<style>
/* ── Page Header: tight, centred ── */
.gal-header {
    text-align: center;
    padding: 36px 0 0;
}
.gal-header h1 {
    font-size: 2rem;
    font-weight: 700;
    color: var(--teal, #1a7a7a);
    margin: 0;
    line-height: 1.2;
    display: inline-block;
}
/* Orange bar sits flush under the heading text */
.gal-heading-wrap {
    display: flex;
    flex-direction: column;
    align-items: center;
    margin-bottom: 0;
}
.gal-orange-line {
    height: 3px;
    width: 70px;
    background: var(--orange, #e87722);
    border-radius: 2px;
    margin-top: 5px;
}

/* ── Filter bar: sits just under heading ── */
.gal-filters {
    display: flex;
    flex-wrap: wrap;
    gap: 10px;
    align-items: center;
    padding: 16px 0 28px;
}
/* mint/teal-tinted inputs */
.gal-filters .fi-wrap {
    position: relative;
    flex: 0 1 230px;
    min-width: 0;
}
.gal-filters .fi-icon {
    position: absolute;
    left: 11px;
    top: 50%;
    transform: translateY(-50%);
    color: var(--teal, #1a7a7a);
    opacity: .7;
    pointer-events: none;
    line-height: 1;
}
.gal-filters input[type="text"],
.gal-filters select {
    width: 100%;
    box-sizing: border-box;
    height: 36px;
    border: 1.5px solid #b2d8d8;
    border-radius: 999px;          /* fully pill-shaped */
    font-size: 13px;
    color: #374151;
    background: #e8f5f5;           /* mint teal tint */
    outline: none;
    transition: border-color .2s, background .2s;
}
.gal-filters input[type="text"] {
    padding: 0 16px 0 36px;
}
.gal-filters select {
    padding: 0 32px 0 16px;
    appearance: none;
    background-color: #e8f5f5;
    background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 24 24' fill='none' stroke='%231a7a7a' stroke-width='2.5'%3E%3Cpath d='M6 9l6 6 6-6'/%3E%3C/svg%3E");
    background-repeat: no-repeat;
    background-position: right 12px center;
    cursor: pointer;
}
.gal-filters input[type="text"]:focus,
.gal-filters select:focus {
    border-color: var(--teal, #1a7a7a);
    background: #d6eeee;
}

/* ── 4-col card grid ── */
.gal-grid {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 20px;
    margin-bottom: 40px;
}
@media (max-width: 1024px) { .gal-grid { grid-template-columns: repeat(3,1fr); } }
@media (max-width: 700px)  { .gal-grid { grid-template-columns: repeat(2,1fr); } }
@media (max-width: 440px)  { .gal-grid { grid-template-columns: 1fr; } }

.gal-card-link { text-decoration: none; color: inherit; display: block; }

.gal-card {
    background: #fff;
    border-radius: 10px;
    box-shadow: 0 2px 10px rgba(0,0,0,.08);
    overflow: hidden;
    transition: transform .2s, box-shadow .2s;
}
.gal-card:hover {
    transform: translateY(-3px);
    box-shadow: 0 8px 22px rgba(0,0,0,.13);
}

/* Image area — full image visible, no crop */
.gal-card-img {
    width: 100%;
    /* fixed height so cards align; image contained inside */
    height: 190px;
    background: #f0f9f9;
    display: flex;
    align-items: center;
    justify-content: center;
    overflow: hidden;
}
.gal-card-img img {
    max-width: 100%;
    max-height: 100%;
    width: 100%;
    height: 100%;
    object-fit: contain;   /* ← full image, no crop */
    display: block;
}

.gal-card-body {
    padding: 14px 16px 18px;
}
.gal-card-title {
    font-size: 14.5px;
    font-weight: 700;
    color: var(--teal, #1a7a7a);
    margin: 0 0 7px;
}
.gal-card-desc {
    font-size: 12.5px;
    color: #6b7280;
    line-height: 1.55;
    margin: 0;
}

/* empty state */
.gal-empty {
    grid-column: 1/-1;
    text-align: center;
    padding: 60px;
    color: #9ca3af;
}

/* pagination */
.gal-pagination { text-align: center; margin-top: 10px; }
</style>
@endpush

@section('content')

<div class="gal-header">
    <div class="gal-heading-wrap">
        <h1>Gallery</h1>
        <div class="gal-orange-line"></div>
    </div>
</div>

<section class="section-pad" style="padding-top:0">
<div class="container">

    {{-- Filters sit tight under heading --}}
    <form method="GET" action="{{ route('gallery.index') }}">
        <div class="gal-filters">
            <div class="fi-wrap">
                <span class="fi-icon">
                    <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <circle cx="11" cy="11" r="8"/><path d="m21 21-4.35-4.35"/>
                    </svg>
                </span>
                <input type="text" name="search" placeholder="Search by Name" value="{{ request('search') }}">
            </div>

            <div class="fi-wrap" style="flex:0 1 140px">
                <select name="class_year">
                    <option value="">Class Year</option>
                    @foreach(range(date('Y'), 1947, -1) as $y)
                        <option value="{{ $y }}" {{ request('class_year')==$y?'selected':'' }}>{{ $y }}</option>
                    @endforeach
                </select>
            </div>

            <div class="fi-wrap" style="flex:0 1 140px">
                <select name="event_type">
                    <option value="">Event Type</option>
                    <option value="Conference" {{ request('event_type')=='Conference'?'selected':'' }}>Conference</option>
                    <option value="Public"     {{ request('event_type')=='Public'    ?'selected':'' }}>Public</option>
                </select>
            </div>

            {{-- hidden submit: pressing Enter or changing select submits --}}
            <button type="submit" style="display:none"></button>
        </div>

        {{-- auto-submit selects --}}
        <script>
            document.querySelectorAll('.gal-filters select').forEach(s => {
                s.addEventListener('change', () => s.closest('form').submit());
            });
        </script>
    </form>

    {{-- Cards --}}
    <div class="gal-grid">
        @forelse($folders as $folder)
            <a href="{{ route('gallery.show', $folder->id) }}" class="gal-card-link">
                <div class="gal-card">
                    <div class="gal-card-img">
                        <img
                            src="{{ $folder->images->first() ? asset('storage/'.$folder->images->first()->image_path) : 'https://placehold.co/400x220/e8f5f5/1a7a7a?text=Gallery' }}"
                            alt="{{ $folder->folder_name }}"
                            loading="lazy"
                            onerror="this.src='https://placehold.co/400x220/e8f5f5/1a7a7a?text=Gallery'">
                    </div>
                    <div class="gal-card-body">
                        <div class="gal-card-title">{{ $folder->folder_name }}</div>
                        <p class="gal-card-desc">{{ Str::limit($folder->description, 110) }}</p>
                    </div>
                </div>
            </a>
        @empty
            <div class="gal-empty">No gallery albums found.</div>
        @endforelse
    </div>

    <div class="gal-pagination">
        {{ $folders->appends(request()->query())->links('vendor.pagination.simple-default') }}
    </div>

</div>
</section>
@endsection