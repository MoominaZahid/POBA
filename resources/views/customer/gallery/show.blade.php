{{-- FILE: resources/views/customer/gallery/show.blade.php --}}
@extends('layouts.app')
@section('title', $folder->folder_name . ' - POBA')

@push('styles')
<style>
    /* ── Page Title ── */
    .show-header {
        text-align: center;
        padding: 8px 20px 14px;
    }
    .show-header h1 {
        font-size: 1.85rem;
        font-weight: 700;
        color: var(--teal, #1a7a7a);
        margin: 0;
        line-height: 1.2;
    }
    .show-header h1 span {
        text-decoration: underline;
        text-decoration-color: var(--orange, #e87722);
        text-underline-offset: 4px;
    }
    .show-orange-line {
        height: 3px;
        width: 60px;
        background: var(--orange, #e87722);
        border-radius: 2px;
        margin: 6px auto 10px;
    }
    .show-header p {
        font-size: 13.5px;
        color: #6b7280;
        max-width: 680px;
        margin: 0 auto;
        line-height: 1.5;
    }
    .show-header p a { color: var(--orange, #e87722); }

    /* ── Images Grid (4 columns, rounded, full-cover) ── */
    .show-gallery-grid {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 14px;
        margin-bottom: 32px;
    }
    @media (max-width: 900px)  { .show-gallery-grid { grid-template-columns: repeat(3, 1fr); } }
    @media (max-width: 600px)  { .show-gallery-grid { grid-template-columns: repeat(2, 1fr); } }

    .show-thumb {
        aspect-ratio: 1 / 1;
        border-radius: 14px;
        overflow: hidden;
        cursor: pointer;
        background: #e5e7eb;
        position: relative;
    }
    .show-thumb img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        display: block;
        transition: transform .25s;
    }
    .show-thumb:hover img { transform: scale(1.04); }

    /* ── Load More ── */
    .load-more-wrap {
        text-align: center;
        margin-bottom: 24px;
    }
    .btn-load-more {
        display: inline-block;
        padding: 10px 36px;
        border: 1.5px solid var(--teal, #1a7a7a);
        border-radius: 999px;
        color: var(--teal, #1a7a7a);
        background: transparent;
        font-size: 14px;
        font-weight: 600;
        cursor: pointer;
        transition: background .2s, color .2s;
    }
    .btn-load-more:hover {
        background: var(--teal, #1a7a7a);
        color: #fff;
    }



    /* ══ Lightbox ══ */
    .lbx-overlay {
        display: none;
        position: fixed;
        inset: 0;
        z-index: 9999;
        background: rgba(0, 0, 0, .88);
        align-items: center;
        justify-content: center;
        flex-direction: column;
        padding: 20px;
        box-sizing: border-box;
    }
    .lbx-overlay.open { display: flex; }

    .lbx-img-wrap {
        position: relative;
        max-width: 90vw;
        max-height: 80vh;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    .lbx-img-wrap img {
        max-width: 90vw;
        max-height: 80vh;
        object-fit: contain;
        border-radius: 8px;
        display: block;
        box-shadow: 0 8px 40px rgba(0,0,0,.5);
    }

    /* Controls container */
    .lbx-controls {
        display: flex;
        align-items: center;
        justify-content: center;
        margin-top: 14px;
        position: relative;
        z-index: 10000;
    }

    /* Counter */
    .lbx-counter {
        color: rgba(255,255,255,.8);
        font-size: 13.5px;
        letter-spacing: .04em;
        text-align: center;
    }

    /* Desktop Nav buttons */
    .lbx-btn {
        position: fixed;
        top: 50%;
        transform: translateY(-50%);
        background: rgba(255,255,255,.14);
        border: none;
        color: #fff;
        font-size: 2.4rem;
        line-height: 1;
        width: 52px;
        height: 52px;
        border-radius: 50%;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: background .18s;
        z-index: 10000;
        user-select: none;
    }
    .lbx-btn:hover { background: rgba(255,255,255,.28); }
    .lbx-prev { left: 24px; }
    .lbx-next { right: 24px; }

    /* Close + Download */
    .lbx-close {
        position: fixed;
        top: 16px;
        right: 16px;
        background: rgba(255,255,255,.15);
        border: none;
        color: #fff;
        font-size: 1.3rem;
        width: 42px;
        height: 42px;
        border-radius: 50%;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: background .18s;
        z-index: 10001;
    }
    .lbx-close:hover { background: rgba(255,255,255,.3); }
    .lbx-download {
        position: fixed;
        top: 16px;
        right: 68px;
        background: rgba(255,255,255,.15);
        border: none;
        color: #fff;
        font-size: 1.1rem;
        width: 42px;
        height: 42px;
        border-radius: 50%;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        text-decoration: none;
        transition: background .18s;
        z-index: 10001;
    }
    .lbx-download:hover { background: rgba(255,255,255,.3); }

    /* ── Mobile View: Move arrows below image so they NEVER overlap ── */
    @media (max-width: 768px) {
        .lbx-img-wrap {
            max-width: 94vw;
            max-height: 68vh;
        }
        .lbx-img-wrap img {
            max-width: 94vw;
            max-height: 68vh;
        }
        .lbx-controls {
            gap: 24px;
            margin-top: 18px;
        }
        .lbx-btn {
            position: static;
            top: auto;
            transform: none;
            width: 46px;
            height: 46px;
            font-size: 2.1rem;
            background: rgba(255, 255, 255, .18);
            box-shadow: 0 2px 10px rgba(0,0,0,.3);
            flex-shrink: 0;
        }
        .lbx-btn:active {
            background: rgba(255, 255, 255, .35);
        }
        .lbx-prev { left: auto; }
        .lbx-next { right: auto; }
        .lbx-counter {
            font-size: 14px;
            font-weight: 600;
            min-width: 65px;
        }
        .lbx-close {
            top: 12px;
            right: 12px;
            width: 38px;
            height: 38px;
        }
        .lbx-download {
            top: 12px;
            right: 58px;
            width: 38px;
            height: 38px;
        }
    }
</style>
@endpush

@section('content')

<div class="container" style="max-width:1200px;margin:0 auto;padding:16px 20px 0;">
    <a href="{{ route('gallery.index') }}" class="btn-back" title="Back to Gallery" aria-label="Back to Gallery">
        <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
            <line x1="20" y1="12" x2="4" y2="12"></line>
            <polyline points="10 18 4 12 10 6"></polyline>
        </svg>
        <span>Back to Gallery</span>
    </a>
</div>

<div class="show-header">
    <h1>{{ $folder->folder_name }}</h1>
    <div class="show-orange-line"></div>
    @if(!empty($folder->description))
    <p>
        {{ Str::limit($folder->description, 180) }}
        @if(strlen($folder->description) > 180)
            <a href="#" onclick="document.getElementById('fullDesc').style.display='inline';this.style.display='none';return false">see more</a>
            <span id="fullDesc" style="display:none">{{ substr($folder->description, 180) }}</span>
        @endif
    </p>
    @endif
</div>

<section class="section-pad" style="padding-top:0">
    <div class="container">

        <div class="show-gallery-grid" id="galleryGrid">
            @forelse($folder->images->take(12) as $i => $image)
                <div class="show-thumb" onclick="openLightbox({{ $i }})">
                    <img
                        src="{{ media_url($image->image_path) }}"
                        alt="Image {{ $i + 1 }}"
                        loading="lazy"
                        onerror="this.src='https://placehold.co/400x400/1a7a7a/fff?text=Image'">
                </div>
            @empty
                <div style="grid-column:1/-1;text-align:center;padding:60px;color:#9ca3af">
                    No images in this album.
                </div>
            @endforelse
        </div>

        @if($folder->images->count() > 12)
        <div class="load-more-wrap">
            <button class="btn-load-more" id="loadMoreBtn" onclick="loadMore()">Load More</button>
        </div>
        @endif

    </div>
</section>

{{-- ══ Lightbox ══ --}}
<div class="lbx-overlay" id="lbxOverlay" onclick="handleOverlayClick(event)">
    <button class="lbx-close"    onclick="closeLightbox()" title="Close" aria-label="Close">✕</button>
    <a      class="lbx-download" id="lbxDownload" href="#" download title="Download image" aria-label="Download image">⬇</a>
    
    <div class="lbx-img-wrap">
        <img id="lbxImg" src="" alt="Gallery image">
    </div>

    <div class="lbx-controls">
        <button class="lbx-btn lbx-prev" onclick="shiftImage(-1)" title="Previous image" aria-label="Previous image">&#8249;</button>
        <div class="lbx-counter" id="lbxCounter"></div>
        <button class="lbx-btn lbx-next" onclick="shiftImage(1)" title="Next image" aria-label="Next image">&#8250;</button>
    </div>
</div>

@push('scripts')
<script>
    // ── Data ──────────────────────────────────────────────
    const BASE    = '{{ media_url('') }}/';
    const ALL     = @json($folder->images->map(fn($img) => array_merge($img->toArray(), ['url' => media_url($img->image_path)]))->values());   // full collection (id, image_path …)
    let current   = 0;
    let shownCount = Math.min(12, ALL.length);

    // ── Lightbox ──────────────────────────────────────────
    function openLightbox(idx) {
        current = idx;
        document.getElementById('lbxOverlay').classList.add('open');
        updateLightbox();
    }

    function closeLightbox() {
        document.getElementById('lbxOverlay').classList.remove('open');
    }

    function shiftImage(dir) {
        current = (current + dir + ALL.length) % ALL.length;
        updateLightbox();
    }

    function updateLightbox() {
        const src = ALL[current].url || (BASE + ALL[current].image_path);
        document.getElementById('lbxImg').src        = src;
        document.getElementById('lbxDownload').href  = src;
        document.getElementById('lbxCounter').textContent =
            (current + 1) + ' / ' + ALL.length;
    }

    // Close when clicking the dark backdrop (not the image or controls)
    function handleOverlayClick(e) {
        if (e.target === document.getElementById('lbxOverlay')) closeLightbox();
    }

    // Keyboard navigation
    document.addEventListener('keydown', function(e) {
        const open = document.getElementById('lbxOverlay').classList.contains('open');
        if (!open) return;
        if (e.key === 'Escape')     closeLightbox();
        if (e.key === 'ArrowLeft')  shiftImage(-1);
        if (e.key === 'ArrowRight') shiftImage(1);
    });

    // Touch swipe support for mobile devices
    (function initLightboxSwipe() {
        let touchStartX = 0;
        let touchEndX = 0;
        const lbx = document.getElementById('lbxOverlay');
        if (!lbx) return;

        lbx.addEventListener('touchstart', function(e) {
            if (e.changedTouches && e.changedTouches.length > 0) {
                touchStartX = e.changedTouches[0].screenX;
            }
        }, { passive: true });

        lbx.addEventListener('touchend', function(e) {
            if (e.changedTouches && e.changedTouches.length > 0) {
                touchEndX = e.changedTouches[0].screenX;
                const diff = touchEndX - touchStartX;
                if (Math.abs(diff) > 40) {
                    if (diff < 0) {
                        shiftImage(1);  // swipe left -> next image
                    } else {
                        shiftImage(-1); // swipe right -> previous image
                    }
                }
            }
        }, { passive: true });
    })();

    // ── Load More ─────────────────────────────────────────
    function loadMore() {
        const grid  = document.getElementById('galleryGrid');
        const batch = ALL.slice(shownCount, shownCount + 8);

        batch.forEach((img, i) => {
            const globalIdx = shownCount + i;
            const div = document.createElement('div');
            div.className   = 'show-thumb';
            div.onclick     = () => openLightbox(globalIdx);

            const im        = document.createElement('img');
            im.src          = img.url || (BASE + img.image_path);
            im.alt          = 'Image ' + (globalIdx + 1);
            im.loading      = 'lazy';
            im.onerror      = () => { im.src = 'https://placehold.co/400x400/1a7a7a/fff?text=Image'; };

            div.appendChild(im);
            grid.appendChild(div);
        });

        shownCount += batch.length;
        if (shownCount >= ALL.length) {
            document.getElementById('loadMoreBtn').style.display = 'none';
        }
    }
</script>
@endpush

@endsection