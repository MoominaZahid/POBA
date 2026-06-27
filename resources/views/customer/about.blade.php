{{-- FILE: resources/views/customer/about.blade.php --}}
@extends('layouts.app')
@section('title', 'About Us - POBA')
@section('content')

    {{-- Mission --}}
    <section class="section-pad">
        <div class="container">
            <div class="grid-2" style="align-items:center;gap:50px">
                <div>
                    <img src="{{ isset($settings['mission_image']) ? asset('storage/' . $settings['mission_image']) : asset('images/mission.jpg') }}"
                        alt="Our Mission" style="border-radius:16px;width:100%;object-fit:cover;max-height:360px"
                        onerror="this.src='https://placehold.co/600x360/1a7a7a/fff?text=Our+Mission'">
                </div>
                <div>
                    <h2 class="section-title section-title-left" style="text-align:left">{{ $settings['mission_title'] ?? 'Our Mission' }}</h2>
                    <p style="color:var(--text-muted);font-size:15px;line-height:1.8;margin-bottom:28px">
                        {{ $settings['mission_description'] ?? 'Lorem Ipsum is simply dummy text of the printing and typesetting industry.' }}
                    </p>
                    @php
                        $missionStats = json_decode($settings['mission_stats'] ?? '[]', true) ?: [];
                        if (empty($missionStats)) {
                            $missionStats = [
                                ['icon' => null, 'heading' => 'Excellence', 'subheading' => 'In Service & Leadership'],
                                ['icon' => null, 'heading' => 'Community', 'subheading' => 'Strong Alumni Network'],
                                ['icon' => null, 'heading' => 'Global Reach', 'subheading' => 'Worldwide Presence'],
                                ['icon' => null, 'heading' => 'Integrity', 'subheading' => 'Honor & Commitment'],
                            ];
                        }
                    @endphp
                    <div class="stats-grid-custom">
                        @foreach ($missionStats as $stat)
                            <div class="stat-item-custom">
                                <div class="stat-icon-custom">
                                    @if (!empty($stat['icon']))
                                        <img src="{{ asset('storage/' . $stat['icon']) }}" alt=""
                                            style="width:40px;height:40px;object-fit:contain">
                                    @else
                                        <svg width="40" height="40" viewBox="0 0 40 40" fill="none"
                                            xmlns="http://www.w3.org/2000/svg">
                                            <path d="M13 2L20 14L27 2H22L20 6L18 2H13Z" fill="#C0392B" />
                                            <path d="M14 8L20 18L26 8H22L20 12L18 8H14Z" fill="#E74C3C" />
                                            <circle cx="20" cy="24" r="11" fill="#F4B731" stroke="#D4920A"
                                                stroke-width="1.5" />
                                            <circle cx="20" cy="24" r="7.5" fill="#FBCB4A" stroke="#F4B731"
                                                stroke-width="1" />
                                            <path
                                                d="M20 19.5L21.3 22.2L24.3 22.6L22.1 24.7L22.7 27.6L20 26.1L17.3 27.6L17.9 24.7L15.7 22.6L18.7 22.2L20 19.5Z"
                                                fill="#fff" />
                                        </svg>
                                    @endif
                                </div>
                                <div>
                                    <div class="stat-heading-custom">{{ $stat['heading'] }}</div>
                                    <div class="stat-subheading-custom">{{ $stat['subheading'] }}</div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- History Timeline --}}
    <section class="section-pad" style="background:var(--bg-light)">
        <div class="container">
            <h2 class="section-title">{{ $settings['history_title'] ?? 'Our History' }}</h2>
            <p style="text-align:center;color:var(--text-muted);margin-bottom:50px">
                {{ $settings['history_description'] ?? 'Milestones in POBA\'s journey of excellence' }}</p>
            <div class="timeline">
                @php $defaultTimeline = [['year' => '1947', 'heading' => 'Foundation Era', 'description' => 'Establishment of Pakistan Navy and the beginning of naval education traditions.'], ['year' => '1965', 'heading' => 'First Alumni Network', 'description' => 'Formation of the first organized alumni association.'], ['year' => '1980', 'heading' => 'Formal Constitution', 'description' => 'POBA officially constituted with formal structure and governance framework.'], ['year' => '1995', 'heading' => 'Modernization Phase', 'description' => 'Introduction of modern communication systems.'], ['year' => '2010', 'heading' => 'Digital Transformation', 'description' => 'Launch of digital platforms for better alumni connectivity.'], ['year' => '2025', 'heading' => 'New Horizons', 'description' => 'Comprehensive website launch and enhanced alumni engagement initiatives.']]; @endphp
                @foreach ($timeline && count($timeline) ? $timeline : $defaultTimeline as $item)
                    <div class="timeline-item">
                        <div class="timeline-content">
                            <div class="timeline-year">{{ $item['year'] }}</div>
                            <div class="timeline-heading">{{ $item['heading'] }}</div>
                            <div class="timeline-desc">{{ $item['description'] }}</div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    {{-- Our Verticals --}}
    <section class="section-pad">
        <div class="container">
            <h2 class="section-title">Our Verticals</h2>
            <div class="verticals-grid">
                <div class="vertical-card">
                    <h4>Executive Committee</h4>
                    <p>Lorem Ipsum is simply dummy text of the printing and typesetting industry.</p>
                    <a href="#" class="btn-teal" style="font-size:13px;padding:9px 22px">View Details</a>
                </div>
                <div class="vertical-card">
                    <h4>Working Committees</h4>
                    <p>Lorem Ipsum is simply dummy text of the printing and typesetting industry.</p>
                    <a href="#" class="btn-teal" style="font-size:13px;padding:9px 22px">View Details</a>
                </div>
            </div>
            <div style="text-align:center;margin-top:40px">
                <p style="color:var(--text-muted);margin-bottom:14px">View photos from past committees and events</p>
                <a href="{{ route('gallery.index') }}" class="btn-outline-teal">📷 Photo Gallery</a>
            </div>
        </div>
    </section>

@endsection
