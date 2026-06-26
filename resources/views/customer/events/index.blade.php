@extends('layouts.app')
@section('title','Events - POBA')

@push('styles')
<style>
/* ── Event Cards (Figma style) ────────────────────────────────── */

.ec-list {
    display: flex;
    flex-direction: column;
    gap: 16px;
}

.ec-card {
    display: flex;
    align-items: stretch;
    gap: 0;
    background: #e8f4f4;
    border-radius: 12px;
    overflow: hidden;
    padding: 0;
    box-shadow: 0 2px 8px rgba(8,102,102,.08);
    transition: box-shadow .2s;
}
.ec-card:hover {
    box-shadow: 0 4px 16px rgba(8,102,102,.14);
}

/* Date badge – transparent, teal text + subtle right divider */
.ec-date-badge {
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    min-width: 60px;
    padding: 12px 12px 12px 10px;
    background: transparent;
    color: #1a8a8a;
    flex-shrink: 0;
    align-self: stretch;
    border-right: 1px solid rgba(8,102,102,0.10);  /* thin visible divider */
}
.ec-day {
    font-size: 1.8rem;
    font-weight: 700;
    line-height: 1;
    color: #1a8a8a;
}
.ec-my {
    font-size: .7rem;
    font-weight: 500;
    margin-top: 2px;
    text-transform: uppercase;
    letter-spacing: .04em;
    color: #1a8a8a;
}

/* Thumbnail – fixed size, contain logo, with small left gap */
.ec-thumb {
    width: 80px;
    height: 90px;
    object-fit: contain;          /* logo fits without cropping */
    background: #f0f8f8;          /* light background for transparency */
    flex-shrink: 0;
    align-self: center;
    margin-left: 10px;            /* small gap from date divider */
    border-radius: 4px;           /* optional soft rounding */
}

/* Body */
.ec-body {
    flex: 1;
    padding: 14px 16px;
    min-width: 0;
    display: flex;
    flex-direction: column;
}

.ec-title {
    font-size: 1rem;
    font-weight: 700;
    color: #086666;
    margin: 0 0 6px;
}

.ec-meta {
    display: flex;
    flex-wrap: wrap;
    gap: 6px 18px;
    font-size: .8rem;
    color: #444;
    margin-bottom: 6px;
}
.ec-meta span {
    white-space: nowrap;
}

.ec-focal {
    font-size: .78rem;
    color: #555;
    line-height: 1.5;
    margin-bottom: 4px;
}
.ec-focal strong {
    color: #333;
}

/* Collapsible */
.ec-collapse {
    max-height: 0;
    overflow: hidden;
    transition: max-height .35s ease;
}
.ec-collapse.open {
    max-height: 600px;
}
.ec-collapse-inner {
    padding-top: 10px;
    font-size: .82rem;
    color: #444;
    line-height: 1.6;
    border-top: 1px dashed #b2d8d8;
    margin-top: 8px;
}
.ec-collapse-inner p {
    margin: 0 0 6px;
}

.ec-gallery-link {
    font-size: .8rem;
    color: #086666;
    font-weight: 600;
    text-decoration: none;
    display: inline-block;
    margin-top: 4px;
}
.ec-gallery-link:hover {
    text-decoration: underline;
}

/* Toggle button – right-aligned, orange */
.ec-toggle {
    background: none;
    border: none;
    cursor: pointer;
    color: #e87722;
    font-weight: 600;
    font-size: .8rem;
    padding: 4px 0 0;
    display: block;
    text-align: right;
    width: 100%;
    margin-top: auto;
}
.ec-toggle:hover {
    text-decoration: underline;
}

/* Actions panel – vertically centered */
.ec-actions {
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    min-width: 140px;
    padding: 16px 14px;
    flex-shrink: 0;
    border-left: 1px solid rgba(8,102,102,.08);
}

/* Buttons */
.ec-btn {
    display: inline-block;
    padding: 8px 18px;
    border-radius: 24px;
    font-size: .8rem;
    font-weight: 600;
    cursor: pointer;
    border: none;
    text-align: center;
    text-decoration: none;
    white-space: nowrap;
    transition: opacity .15s, background .15s;
    width: 100%;
}
.ec-btn:hover {
    opacity: .88;
}

.ec-btn-primary  { background: #086666; color: #fff; }
.ec-btn-outline  { background: transparent; color: #086666; border: 2px solid #086666; }
.ec-btn-cancel   { background: transparent; color: #e87722; border: 2px solid #e87722; width: 100%; }

/* Badges */
.ec-badge {
    display: inline-block;
    padding: 3px 14px;
    border-radius: 20px;
    font-size: .75rem;
    font-weight: 600;
}
.ec-badge-confirmed  { background: #e1f5ee; color: #0f6e56; border: 1px solid #5dcaa5; }
.ec-badge-pending    { background: #faeeda; color: #854f0b; border: 1px solid #fac775; }
.ec-badge-ineligible { background: #fcebeb; color: #a32d2d; border: 1px solid #f09595; }

.ec-no-reg {
    font-size: .75rem;
    color: #888;
    font-style: italic;
    text-align: center;
    display: block;
}
.ec-ineligible-msg {
    font-size: .7rem;
    color: #888;
    margin: 4px 0 0;
    text-align: center;
}

/* Load more */
.ec-load-more-wrap {
    text-align: center;
    margin-top: 28px;
}
.ec-load-more-btn {
    background: #086666;
    color: #fff;
    border: none;
    padding: 10px 36px;
    border-radius: 24px;
    font-size: .88rem;
    font-weight: 600;
    cursor: pointer;
    transition: opacity .15s;
}
.ec-load-more-btn:hover    { opacity: .85; }
.ec-load-more-btn:disabled { opacity: .5; cursor: not-allowed; }

/* Tab buttons */
.tab-btns {
    display: flex;
    gap: 12px;
    margin-bottom: 28px;
}
.tab-btn {
    padding: 8px 28px;
    border-radius: 24px;
    font-size: .88rem;
    font-weight: 600;
    cursor: pointer;
    border: 2px solid #ccc;
    background: #fff;
    color: #888;
    transition: all .2s;
}
.tab-btn.active {
    background: #086666;
    border-color: #086666;
    color: #fff;
}

/* Flash messages */
.flash-success {
    margin-bottom: 20px;
    padding: 12px 18px;
    background: #e1f5ee;
    border: 1px solid #5dcaa5;
    border-radius: 8px;
    color: #0f6e56;
    font-size: .88rem;
}
.flash-error {
    margin-bottom: 20px;
    padding: 12px 18px;
    background: #fcebeb;
    border: 1px solid #f09595;
    border-radius: 8px;
    color: #a32d2d;
    font-size: .88rem;
}

/* Empty state */
.ec-empty {
    text-align: center;
    padding: 48px 0;
    color: #888;
    font-size: .95rem;
}

@media (max-width: 640px) {
    .ec-card    { flex-wrap: wrap; }
    .ec-thumb   { width: 100%; height: 160px; margin-left: 0; }
    .ec-actions { width: 100%; border-left: none; border-top: 1px solid rgba(8,102,102,.08); }
    .ec-date-badge { min-width: 50px; border-right: none; padding-right: 10px; }
}
</style>
@endpush

@section('content')

<section class="section-pad" style="padding-top:40px">
    <div class="container">

        <div style="text-align:center;margin-bottom:40px">
            <h1 style="font-size:2.5rem;font-weight:700;color:#086666;display:inline-block;padding-bottom:8px;border-bottom:4px solid #e87722;line-height:1.2">
                Events
            </h1>
        </div>

        {{-- Flash messages --}}
        @if(session('success'))
            <div class="flash-success">✓ {{ session('success') }}</div>
        @endif
        @if(session('error'))
            <div class="flash-error">✕ {{ session('error') }}</div>
        @endif

        {{-- Tab buttons --}}
        <div class="tab-btns">
            <button class="tab-btn active" id="btnUpcoming" data-tab="upcoming">Upcoming</button>
            <button class="tab-btn"        id="btnPrevious" data-tab="previous">Previous</button>
        </div>

        {{-- ── Upcoming tab ── --}}
        <div id="tabUpcoming">
            <div class="ec-list" id="upcoming-events">
                @include('customer.events._event_cards', [
                    'events'           => $upcoming,
                    'myEventIds'       => $myEventIds,
                    'myParticipations' => $myParticipations,
                    'isPrevious'       => false,
                ])
            </div>

            @if($upcoming->isEmpty())
                <div class="ec-empty">No upcoming events at the moment. Check back soon!</div>
            @endif

            @if($upcoming->hasMorePages())
                <div class="ec-load-more-wrap" id="upcoming-load-more">
                    <button class="ec-load-more-btn load-more-btn"
                            data-tab="upcoming"
                            data-page="{{ $upcoming->currentPage() + 1 }}">
                        Load More
                    </button>
                </div>
            @endif
        </div>

        {{-- ── Previous tab ── --}}
        <div id="tabPrevious" style="display:none">
            <div class="ec-list" id="previous-events">
                @include('customer.events._event_cards', [
                    'events'           => $previous,
                    'myEventIds'       => $myEventIds,
                    'myParticipations' => $myParticipations,
                    'isPrevious'       => true,
                ])
            </div>

            @if($previous->isEmpty())
                <div class="ec-empty">No previous events yet.</div>
            @endif

            @if($previous->hasMorePages())
                <div class="ec-load-more-wrap" id="previous-load-more">
                    <button class="ec-load-more-btn load-more-btn"
                            data-tab="previous"
                            data-page="{{ $previous->currentPage() + 1 }}">
                        Load More
                    </button>
                </div>
            @endif
        </div>

    </div>
</section>

@push('scripts')
<script>
// ── Tab switching ──────────────────────────────────────────────────
function showTab(tab) {
    document.getElementById('tabUpcoming').style.display = tab === 'upcoming' ? 'block' : 'none';
    document.getElementById('tabPrevious').style.display = tab === 'previous' ? 'block' : 'none';
    document.getElementById('btnUpcoming').classList.toggle('active', tab === 'upcoming');
    document.getElementById('btnPrevious').classList.toggle('active', tab === 'previous');
}

document.querySelectorAll('.tab-btn').forEach(btn => {
    btn.addEventListener('click', function () { showTab(this.dataset.tab); });
});

// ── Collapse / expand (See More / See Less) ───────────────────────
function toggleDesc(id) {
    const collapse = document.getElementById('desc-' + id);
    const btn      = document.getElementById('seeMore-' + id);
    const isOpen   = collapse.classList.contains('open');
    collapse.classList.toggle('open', !isOpen);
    btn.textContent = isOpen ? 'See More' : 'See Less';
}

// ── Load More (AJAX) ──────────────────────────────────────────────
document.addEventListener('click', function (e) {
    if (!e.target.classList.contains('load-more-btn')) return;

    const btn         = e.target;
    const tab         = btn.dataset.tab;
    const page        = parseInt(btn.dataset.page);
    const containerId = tab === 'upcoming' ? 'upcoming-events' : 'previous-events';
    const wrapperId   = tab === 'upcoming' ? 'upcoming-load-more' : 'previous-load-more';

    btn.disabled    = true;
    btn.textContent = 'Loading…';

    fetch(`?tab=${tab}&${tab}_page=${page}`, {
        headers: { 'X-Requested-With': 'XMLHttpRequest' }
    })
    .then(r => r.json())
    .then(data => {
        document.getElementById(containerId).insertAdjacentHTML('beforeend', data.html);

        if (data.hasMore) {
            btn.dataset.page = page + 1;
            btn.disabled     = false;
            btn.textContent  = 'Load More';
        } else {
            document.getElementById(wrapperId).remove();
        }
    })
    .catch(() => {
        btn.disabled    = false;
        btn.textContent = 'Load More';
        alert('Something went wrong. Please try again.');
    });
});
</script>
@endpush
@endsection