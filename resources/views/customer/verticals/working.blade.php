{{-- FILE: resources/views/customer/verticals/working.blade.php --}}
@extends('layouts.app')
@section('title', 'Working Committees - POBA Verticals')
@section('content')

<div class="page-header" style="background:#fff;padding:50px 0 30px;text-align:center">
    <div class="container">
        <h1 style="font-size:2.5rem;font-weight:800;color:var(--teal);margin-bottom:8px">Working Committees</h1>
        <div class="underline" style="width:140px;height:3.5px;background:var(--orange);margin:0 auto;border-radius:2px"></div>
    </div>
</div>

<section class="section-pad" style="background:#fff;padding-top:20px">
    <div class="container">
        <p style="text-align:center;max-width:850px;margin:0 auto 30px;color:#4a5568;font-size:15px;line-height:1.8">
            Lorem ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book.
        </p>

        @if($working && count($working) > 0)
            {{-- ── Committee Tabs Bar (Image 1 Mockup) ────────────────────────── --}}
            <div class="committee-tabs-wrap" style="display:flex;align-items:center;justify-content:center;gap:14px;margin-bottom:40px;flex-wrap:wrap">
                <button type="button" class="committee-tab-btn active" data-target="all" onclick="filterWorkingTab('all', this)" style="background:var(--orange);color:#fff;border:none;padding:8px 24px;border-radius:30px;font-weight:700;font-size:15px;cursor:pointer;transition:all .2s;box-shadow:0 4px 12px rgba(232,119,34,0.25)">
                    All
                </button>
                @foreach($working as $idx => $c)
                    <span class="committee-tab-divider" style="color:#02828e;font-weight:400;font-size:16px;user-select:none">|</span>
                    <button type="button" class="committee-tab-btn" data-target="committee-{{ $c->id }}" onclick="filterWorkingTab('committee-{{ $c->id }}', this)" style="background:transparent;color:#52606d;border:none;padding:8px 24px;border-radius:30px;font-weight:700;font-size:15px;cursor:pointer;transition:all .2s">
                        {{ preg_replace('/\s+Committee$/i', '', $c->title) }}
                    </button>
                @endforeach
            </div>

            {{-- ── Working Committees Content Cards ────────────────────────────── --}}
            <div style="display:flex;flex-direction:column;gap:50px">
                @foreach($working as $c)
                <div class="working-committee-card" id="committee-{{ $c->id }}" style="background:#fff;border-radius:16px;padding:32px;box-shadow:0 4px 20px rgba(0,0,0,0.04);border:1px solid #edf2f7">
                    <h3 style="color:#db642a;font-size:22px;font-weight:800;margin-bottom:12px">{{ $c->title }}</h3>
                    <p style="color:#4a5568;font-size:14px;line-height:1.7;margin-bottom:24px">
                        {{ $c->description ?: "Lorem ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text." }}
                    </p>

                    @if($c->members && count($c->members) > 0)
                    <div style="display:grid;grid-template-columns:repeat(auto-fill, minmax(180px, 1fr));gap:18px">
                        @foreach($c->members as $m)
                        <div style="background:#fff;border:1px solid #d8dee3;border-radius:8px;padding:12px 18px;text-align:left;color:#02828e;font-weight:700;font-size:14px;white-space:nowrap;overflow:hidden;text-overflow:ellipsis">
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
                    <p style="color:#a0aec0;font-size:13px">No members listed yet.</p>
                    @endif
                </div>
                @endforeach
            </div>

        @else
            <div style="text-align:center;padding:50px;color:var(--text-muted)">
                No Working Committees available at the moment.
            </div>
        @endif
    </div>
</section>

@push('scripts')
<script>
function filterWorkingTab(targetId, btnElement) {
    // Update active tab button style
    document.querySelectorAll('.committee-tab-btn').forEach(btn => {
        btn.classList.remove('active');
        btn.style.background = 'transparent';
        btn.style.color = '#52606d';
        btn.style.boxShadow = 'none';
    });

    btnElement.classList.add('active');
    btnElement.style.background = 'var(--orange)';
    btnElement.style.color = '#ffffff';
    btnElement.style.boxShadow = '0 4px 12px rgba(232,119,34,0.25)';

    // Filter card visibility or scroll
    const cards = document.querySelectorAll('.working-committee-card');
    if (targetId === 'all') {
        cards.forEach(card => card.style.display = 'block');
    } else {
        cards.forEach(card => {
            if (card.id === targetId) {
                card.style.display = 'block';
                card.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
            } else {
                card.style.display = 'none';
            }
        });
    }
}
</script>
@endpush

@endsection
