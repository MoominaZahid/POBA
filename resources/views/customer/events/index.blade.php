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

.ec-mobile-title-wrap {
    display: none;
}
.ec-title-desktop {
    display: block;
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
.ec-badge-declined   { background: #fcebeb; color: #a32d2d; border: 1px solid #f09595; }

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
.ec-declined-msg {
    font-size: .75rem;
    color: #a32d2d;
    margin: 4px 0 0;
    text-align: center;
    line-height: 1.4;
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

@media (max-width: 768px) {
    .ec-card {
        flex-wrap: wrap;
    }
    .ec-date-badge {
        min-width: 65px;
        padding: 12px 14px;
        border-right: 1px solid rgba(8,102,102,0.10);
        align-self: stretch;
    }
    .ec-mobile-title-wrap {
        display: flex;
        align-items: center;
        flex: 1;
        min-width: 0;
        padding: 12px 16px 12px 14px;
    }
    .ec-title-mobile {
        font-size: 1.05rem;
        font-weight: 700;
        color: #086666;
        margin: 0;
        line-height: 1.35;
        word-break: break-word;
    }
    .ec-title-desktop {
        display: none !important;
    }
    .ec-thumb {
        width: 100%;
        height: 160px;
        margin-left: 0;
    }
    .ec-body {
        width: 100%;
    }
    .ec-actions {
        width: 100%;
        border-left: none;
        border-top: 1px solid rgba(8,102,102,.08);
    }
}

/* ── Custom Event Confirmation Modal ──────────────────────────── */
.ec-modal-overlay {
    display: none;
    position: fixed;
    inset: 0;
    background: rgba(4, 30, 30, 0.6);
    backdrop-filter: blur(5px);
    -webkit-backdrop-filter: blur(5px);
    z-index: 9999;
    align-items: center;
    justify-content: center;
    padding: 16px;
    animation: ecFadeIn 0.2s ease-out;
}
.ec-modal-overlay.open {
    display: flex;
}
.ec-modal-box {
    background: #ffffff;
    border-radius: 24px;
    padding: 32px 28px 26px;
    width: 440px;
    max-width: 100%;
    box-shadow: 0 20px 50px rgba(0, 0, 0, 0.22);
    text-align: center;
    position: relative;
    animation: ecPopIn 0.25s cubic-bezier(0.16, 1, 0.3, 1);
    border: 1px solid rgba(8, 102, 102, 0.1);
}
@keyframes ecFadeIn {
    from { opacity: 0; }
    to { opacity: 1; }
}
@keyframes ecPopIn {
    from { opacity: 0; transform: scale(0.92); }
    to { opacity: 1; transform: scale(1); }
}
.ec-modal-close {
    position: absolute;
    top: 14px;
    right: 16px;
    background: #f1f5f9;
    border: none;
    width: 32px;
    height: 32px;
    border-radius: 50%;
    font-size: 15px;
    color: #64748b;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: background 0.15s, color 0.15s;
}
.ec-modal-close:hover {
    background: #e2e8f0;
    color: #0f172a;
}
.ec-modal-icon-wrap {
    width: 60px;
    height: 60px;
    border-radius: 50%;
    margin: 0 auto 16px;
    display: flex;
    align-items: center;
    justify-content: center;
}
.ec-modal-icon-wrap.success {
    background: #e1f5ee;
}
.ec-modal-icon-wrap.danger {
    background: #fef3c7;
}
.ec-modal-title {
    font-family: 'Cairo', sans-serif;
    font-size: 1.28rem;
    font-weight: 700;
    color: #086666;
    margin: 0 0 6px;
}
.ec-modal-desc {
    font-size: 0.88rem;
    color: #64748b;
    margin: 0 0 16px;
    line-height: 1.5;
}
.ec-modal-event-preview {
    background: #f8fafc;
    border: 1px solid #e2e8f0;
    border-radius: 14px;
    padding: 12px 16px;
    margin-bottom: 22px;
    text-align: left;
}
.ec-modal-event-preview .ev-p-title {
    font-weight: 700;
    color: #0f172a;
    font-size: 0.95rem;
    margin-bottom: 4px;
}
.ec-modal-event-preview .ev-p-meta {
    font-size: 0.82rem;
    color: #64748b;
    line-height: 1.4;
}
.ec-modal-actions {
    display: flex;
    gap: 12px;
    justify-content: center;
}
.btn-modal-cancel {
    flex: 1;
    padding: 11px 18px;
    border-radius: 30px;
    font-size: 0.88rem;
    font-weight: 600;
    border: 1.5px solid #cbd5e1;
    background: #fff;
    color: #475569;
    cursor: pointer;
    transition: background 0.15s, border-color 0.15s;
}
.btn-modal-cancel:hover {
    background: #f1f5f9;
    border-color: #94a3b8;
}
.btn-modal-confirm {
    flex: 1;
    padding: 11px 18px;
    border-radius: 30px;
    font-size: 0.88rem;
    font-weight: 700;
    border: none;
    background: #086666;
    color: #fff;
    cursor: pointer;
    transition: background 0.15s, transform 0.15s;
    box-shadow: 0 4px 14px rgba(8, 102, 102, 0.28);
}
.btn-modal-confirm:hover {
    background: #054d4d;
    transform: translateY(-1px);
}
.btn-modal-confirm.danger {
    background: #e87722;
    box-shadow: 0 4px 14px rgba(232, 119, 34, 0.28);
}
.btn-modal-confirm.danger:hover {
    background: #cf6211;
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

{{-- ── Custom Event Confirmation Modal ── --}}
<div class="ec-modal-overlay" id="eventConfirmModal" role="dialog" aria-modal="true" aria-labelledby="modalTitle">
    <div class="ec-modal-box">
        <button type="button" class="ec-modal-close" onclick="closeEventConfirmModal()" aria-label="Close modal">✕</button>
        
        <div class="ec-modal-icon-wrap" id="modalIconWrap"></div>

        <h3 id="modalTitle" class="ec-modal-title">Confirm Registration</h3>
        <p id="modalDesc" class="ec-modal-desc">Are you sure you want to register for this event?</p>

        <div class="ec-modal-event-preview">
            <div id="modalEventTitle" class="ev-p-title"></div>
            <div id="modalEventMeta" class="ev-p-meta"></div>
        </div>

        <div class="ec-modal-actions">
            <button type="button" class="btn-modal-cancel" onclick="closeEventConfirmModal()">Cancel</button>
            <button type="button" id="btnModalConfirm" class="btn-modal-confirm">Yes, Register</button>
        </div>
    </div>
</div>

@push('scripts')
<script>
// ── Custom Event Confirmation Modal Controller ────────────────────
let currentConfirmFormId = null;

function showEventConfirmModal(opts) {
    currentConfirmFormId = opts.formId;
    
    document.getElementById('modalTitle').textContent = opts.title;
    document.getElementById('modalDesc').textContent = opts.desc;
    document.getElementById('modalEventTitle').textContent = opts.eventTitle;
    document.getElementById('modalEventMeta').innerHTML = opts.eventMeta;

    const confirmBtn = document.getElementById('btnModalConfirm');
    confirmBtn.disabled = false;
    confirmBtn.textContent = opts.confirmText || 'Confirm';
    confirmBtn.className = 'btn-modal-confirm' + (opts.isDanger ? ' danger' : '');

    const iconWrap = document.getElementById('modalIconWrap');
    if (opts.isDanger) {
        iconWrap.className = 'ec-modal-icon-wrap danger';
        iconWrap.innerHTML = `
            <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="#d97706" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"></path>
                <line x1="12" y1="9" x2="12" y2="13"></line>
                <line x1="12" y1="17" x2="12.01" y2="17"></line>
            </svg>`;
    } else {
        iconWrap.className = 'ec-modal-icon-wrap success';
        iconWrap.innerHTML = `
            <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="#086666" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                <rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect>
                <line x1="16" y1="2" x2="16" y2="6"></line>
                <line x1="8" y1="2" x2="8" y2="6"></line>
                <line x1="3" y1="10" x2="21" y2="10"></line>
                <path d="M9 16l2 2 4-4"></path>
            </svg>`;
    }

    const modal = document.getElementById('eventConfirmModal');
    modal.classList.add('open');
    document.body.style.overflow = 'hidden';
}

function closeEventConfirmModal() {
    const modal = document.getElementById('eventConfirmModal');
    if (modal) {
        modal.classList.remove('open');
        document.body.style.overflow = '';
    }
    currentConfirmFormId = null;
}

document.addEventListener('DOMContentLoaded', function () {
    const confirmBtn = document.getElementById('btnModalConfirm');
    if (confirmBtn) {
        confirmBtn.addEventListener('click', function () {
            if (currentConfirmFormId) {
                const form = document.getElementById(currentConfirmFormId);
                if (form) {
                    confirmBtn.disabled = true;
                    confirmBtn.textContent = 'Processing...';
                    form.submit();
                }
            }
        });
    }

    const modal = document.getElementById('eventConfirmModal');
    if (modal) {
        modal.addEventListener('click', function (e) {
            if (e.target === modal) {
                closeEventConfirmModal();
            }
        });
    }

    document.addEventListener('keydown', function (e) {
        if (e.key === 'Escape') {
            closeEventConfirmModal();
        }
    });
});

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