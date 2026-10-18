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

    @keyframes spin { 0% { transform: rotate(0deg); } 100% { transform: rotate(360deg); } }
    @keyframes fadeInCard { from { opacity: 0; transform: translateY(12px); } to { opacity: 1; transform: translateY(0); } }
    .fade-in-card { animation: fadeInCard 0.35s ease forwards; }
</style>

<section class="section-pad">
    <div class="container">

        {{-- Heading with orange underline matching text width --}}
        <div style="text-align: center; margin-bottom: 40px;">
            <h2 style="display: inline-block; font-size: 2.2rem; font-weight: 700; color: var(--teal, #086666); border-bottom: 3px solid var(--orange, #e87722); padding-bottom: 8px; margin: 0;">
                Latest News
            </h2>
        </div>

        <div class="grid-4" id="newsGrid" style="align-items: stretch;">
            @include('customer.news._items')
        </div>

        <div id="loadMoreContainer" style="text-align:center;margin-top:40px;{{ $news->hasMorePages() ? '' : 'display:none;' }}">
            <button id="btnLoadMore" 
                    type="button" 
                    class="btn-teal" 
                    data-next-page="{{ $news->currentPage() + 1 }}"
                    style="font-size:14px;font-weight:700;padding:12px 36px;border-radius:30px;cursor:pointer;display:inline-flex;align-items:center;gap:8px;border:none">
                <span id="loadMoreText">Load More News</span>
                <span id="loadMoreSpinner" style="display:none;vertical-align:middle">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" style="animation:spin 0.8s linear infinite;display:block">
                        <circle cx="12" cy="12" r="10" stroke-opacity="0.25"></circle>
                        <path d="M12 2a10 10 0 0 1 10 10"></path>
                    </svg>
                </span>
            </button>
        </div>

        {{-- Fallback pagination for crawlers / non-JS browsers --}}
        <noscript>
            <div style="text-align:center;margin-top:40px">
                {{ $news->links('vendor.pagination.simple-default') }}
            </div>
        </noscript>
    </div>
</section>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const btn = document.getElementById('btnLoadMore');
    const container = document.getElementById('loadMoreContainer');
    const grid = document.getElementById('newsGrid');
    const textSpan = document.getElementById('loadMoreText');
    const spinner = document.getElementById('loadMoreSpinner');

    if (!btn || !grid) return;

    btn.addEventListener('click', function () {
        const nextPage = btn.getAttribute('data-next-page');
        if (!nextPage) return;

        btn.disabled = true;
        textSpan.textContent = 'Loading...';
        spinner.style.display = 'inline-block';

        fetch(`{{ route('news.index') }}?page=${nextPage}`, {
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json'
            }
        })
        .then(response => {
            if (!response.ok) throw new Error('Network error');
            return response.json();
        })
        .then(data => {
            if (data.html) {
                grid.insertAdjacentHTML('beforeend', data.html);
            }
            if (data.hasMore && data.nextPage) {
                btn.setAttribute('data-next-page', data.nextPage);
                btn.disabled = false;
                textSpan.textContent = 'Load More News';
                spinner.style.display = 'none';
            } else {
                container.style.display = 'none';
            }
        })
        .catch(err => {
            console.error(err);
            textSpan.textContent = 'Retry Loading';
            spinner.style.display = 'none';
            btn.disabled = false;
        });
    });
});
</script>
@endpush
@endsection