{{-- FILE: resources/views/customer/verticals/index.blade.php --}}
@extends('layouts.app')
@section('title', 'Verticals - POBA')
@section('content')

<div class="page-header" style="background:#fff;padding:50px 0 30px;text-align:center">
    <div class="container">
        <h1 style="font-size:2.5rem;font-weight:800;color:var(--teal);margin-bottom:8px">POBA Verticals</h1>
        <div class="underline" style="width:140px;height:3.5px;background:var(--orange);margin:0 auto;border-radius:2px"></div>
    </div>
</div>

{{-- ── Executive Committee Section ────────────────────────────────────────────── --}}
<section class="section-pad" style="background:#fff;padding-top:20px;padding-bottom:50px">
    <div class="container">
        <div style="text-align:center;margin-bottom:32px">
            <h2 class="section-title" style="margin-bottom:12px">Executive Committee</h2>
            <p style="max-width:850px;margin:0 auto;color:#4a5568;font-size:15px;line-height:1.8">
                {{ $executive->description ?? "Lorem ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s." }}
            </p>
        </div>

        @if($executive && $executive->members && count($executive->members) > 0)
        <div style="display:grid;grid-template-columns:repeat(auto-fill, minmax(210px, 1fr));gap:16px;max-width:1050px;margin:0 auto">
            @foreach($executive->members as $m)
            <div style="background:#E6F3F4;border:1px solid #cbd5e0;border-radius:30px;padding:12px 22px;text-align:center;color:#02828e;font-weight:700;font-size:14px;white-space:nowrap;overflow:hidden;text-overflow:ellipsis">
                @if($m->member_url)
                    <a href="{{ $m->member_url }}" target="_blank" rel="noopener noreferrer" style="color:inherit;text-decoration:none">
                        {{ $m->member_name }}
                    </a>
                @else
                    {{ $m->member_name }}
                @endif
            </div>
            @endforeach
        </div>
        @else
        <p style="text-align:center;color:var(--text-muted)">No Executive Committee members added yet.</p>
        @endif
    </div>
</section>

{{-- ── Working Committees Section with Tabs ───────────────────────────────────── --}}
<section class="section-pad" style="background:var(--bg-light);padding-top:60px">
    <div class="container">
        <div style="text-align:center;margin-bottom:24px">
            <h2 class="section-title" style="margin-bottom:12px">Working Committees</h2>
            <p style="max-width:850px;margin:0 auto;color:#4a5568;font-size:15px;line-height:1.8">
                Lorem ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s.
            </p>
        </div>

        @if($working && count($working) > 0)
            {{-- Committee Tabs Bar --}}
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

            <div style="display:flex;flex-direction:column;gap:40px">
                @foreach($working as $c)
                <div class="working-committee-card" id="committee-{{ $c->id }}" style="background:#fff;border-radius:16px;padding:32px;box-shadow:0 4px 20px rgba(0,0,0,0.03);border:1px solid #edf2f7">
                    <h3 style="color:#db642a;font-size:22px;font-weight:800;margin-bottom:12px">{{ $c->title }}</h3>
                    <p style="color:#4a5568;font-size:14px;line-height:1.7;margin-bottom:24px">
                        {{ $c->description ?: "Lorem ipsum is simply dummy text of the printing and typesetting industry." }}
                    </p>

                    @if($c->members && count($c->members) > 0)
                    <div style="display:grid;grid-template-columns:repeat(auto-fill, minmax(210px, 1fr));gap:14px">
                        @foreach($c->members as $m)
                        <div style="background:#E6F3F4;border:1px solid #cbd5e0;border-radius:30px;padding:10px 20px;text-align:center;color:#02828e;font-weight:700;font-size:14px;white-space:nowrap;overflow:hidden;text-overflow:ellipsis">
                            @if($m->member_url)
                                <a href="{{ $m->member_url }}" target="_blank" rel="noopener noreferrer" style="color:inherit;text-decoration:none">
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
        @endif
    </div>
</section>

@push('scripts')
<script>
function filterWorkingTab(targetId, btnElement) {
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
