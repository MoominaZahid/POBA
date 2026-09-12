{{-- FILE: resources/views/customer/promotions.blade.php --}}
@extends('layouts.app')
@section('title','Promotions - POBA')
@section('content')

<div class="page-header">
    <h1>Promotions</h1>
    <div class="underline"></div>
</div>

<section class="section-pad">
    <div class="container">
        @forelse($promos as $promo)
        <div class="promo-card">
            <img src="{{ $promo->image ? media_url($promo->image) : 'https://placehold.co/280x180/1a7a7a/fff?text=Promo' }}" alt="{{ $promo->title }}">
            <div class="promo-body">
                <h4>{{ $promo->title }}</h4>
                <p>{{ $promo->description }}</p>
                @if($promo->url)
                <a href="{{ $promo->url }}" target="_blank">{{ $promo->url }}</a>
                @endif
            </div>
        </div>
        @empty
        <div style="text-align:center;padding:80px;color:var(--text-muted)">No active promotions at the moment.</div>
        @endforelse
    </div>
</section>
@endsection
