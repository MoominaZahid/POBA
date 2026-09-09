{{-- FILE: resources/views/customer/verticals/working.blade.php --}}
@extends('layouts.app')
@section('title', 'Working Committees - POBA Verticals')
@section('content')

<div class="page-header" style="background:#fff;padding:50px 0 30px;text-align:center">
    <div class="container">
        <h1 class="vertical-detail-heading">Working Committees</h1>
        <div class="vertical-detail-underline"></div>
    </div>
</div>

<section class="section-pad" style="background:#fff;padding-top:20px">
    <div class="container">
        <p class="vertical-detail-intro">
            Lorem ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.
        </p>

        @if($working && count($working) > 0)
            <div class="working-committees-stack">
                @foreach($working as $c)
                <div class="working-committee-block">
                    <h3 class="working-committee-title">{{ $c->title }}</h3>
                    <p class="working-committee-desc">
                        {{ $c->description ?: "Lorem ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book." }}
                    </p>

                    @if($c->members && count($c->members) > 0)
                    <div class="vertical-member-grid">
                        @foreach($c->members as $m)
                        <div class="vertical-member-card">
                            @if($m->member_url)
                                <a href="{{ $m->member_url }}" target="_blank" rel="noopener noreferrer" title="{{ $m->member_name }}">
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

@endsection
