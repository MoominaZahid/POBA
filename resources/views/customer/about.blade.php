{{-- FILE: resources/views/customer/about.blade.php --}}
@extends('layouts.app')
@section('title', 'About Us - POBA')
@section('content')

    {{-- Mission --}}
    <section class="section-pad">
        <div class="container">
            <div class="grid-2 mission-grid" style="align-items:flex-start">
                <div>
                    <img src="{{ !empty($settings['mission_image']) ? media_url($settings['mission_image']) : asset('images/mission.jpg') }}"
                        alt="Our Mission" style="border-radius:30px;width:100%;aspect-ratio:541/409;object-fit:cover"
                        onerror="this.src='https://placehold.co/600x360/1a7a7a/fff?text=Our+Mission'">
                </div>
                <div>
                    <h2 class="section-title section-title-left" style="text-align:left">{{ $settings['mission_title'] ?? 'Our Mission' }}</h2>
                    <p class="text-see-more" id="missionDescText" style="color:#000;font-size:16px !important;line-height:1.8;margin-bottom:8px">
                        {{ $settings['mission_description'] ?? "Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book." }}
                    </p>
                    <button type="button" class="see-more-toggle" data-target="missionDescText" onclick="toggleSeeMore(this)" hidden>See More</button>
                    @php
                        $missionStats = json_decode($settings['mission_stats'] ?? '[]', true) ?: [];
                        $missionStats = array_filter($missionStats, function ($stat) {
                            return !empty($stat['heading']);
                        });
                    @endphp
                    @if (!empty($missionStats))
                    <div class="stats-grid-custom">
                        @foreach ($missionStats as $stat)
                            <div class="stat-item-custom">
                                <div class="stat-icon-custom">
                                    @if (!empty($stat['icon']))
                                        <img src="{{ media_url($stat['icon']) }}" alt=""
                                            style="width:50px;height:50px;object-fit:contain">
                                    @endif
                                </div>
                                <div>
                                    <div class="stat-heading-custom">{{ $stat['heading'] }}</div>
                                    <div class="stat-subheading-custom">{{ $stat['subheading'] }}</div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    @endif
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
                @php
                    $defaultTimeline = [
                        ['year' => '1947', 'heading' => 'Foundation Era', 'description' => 'Establishment of Pakistan Navy and the beginning of naval education traditions.'],
                        ['year' => '1965', 'heading' => 'First Alumni Network', 'description' => 'Formation of the first organized alumni association.'],
                        ['year' => '1980', 'heading' => 'Formal Constitution', 'description' => 'POBA officially constituted with formal structure and governance framework.'],
                        ['year' => '1995', 'heading' => 'Modernization Phase', 'description' => 'Introduction of modern communication systems.'],
                        ['year' => '2010', 'heading' => 'Digital Transformation', 'description' => 'Launch of digital platforms for better alumni connectivity.'],
                        ['year' => '2025', 'heading' => 'New Horizons', 'description' => 'Comprehensive website launch and enhanced alumni engagement initiatives.']
                    ];
                    $timelineItems = $timeline && count($timeline) ? $timeline : $defaultTimeline;
                    $endTileText = trim($settings['history_end_tile_text'] ?? '');
                    $endTileYear = trim($settings['history_end_tile_year'] ?? '') ?: 'Present';
                    $endTileHeading = trim($settings['history_end_tile_heading'] ?? '');
                @endphp
                @foreach ($timelineItems as $index => $item)
                    <div class="timeline-item">
                        <div class="timeline-content" id="timelineTile-{{ $index }}">
                            <div class="timeline-ribbon"><span class="timeline-year">{{ $item['year'] }}</span></div>
                            <div class="timeline-connector"></div>
                            <div class="timeline-box">
                                <div class="timeline-heading">{{ $item['heading'] }}</div>
                                <div class="timeline-desc">{{ $item['description'] }}</div>
                            </div>
                        </div>
                    </div>
                @endforeach
                @if (!empty($endTileText))
                    @php $endIndex = count($timelineItems); @endphp
                    <div class="timeline-item timeline-item-end">
                        <div class="timeline-content timeline-content-end" id="timelineTile-{{ $endIndex }}">
                            <div class="timeline-ribbon"><span class="timeline-year">{{ $endTileYear }}</span></div>
                            <div class="timeline-connector"></div>
                            <div class="timeline-box timeline-box-end">
                                @if (!empty($endTileHeading))
                                    <div class="timeline-heading">{{ $endTileHeading }}</div>
                                @endif
                                <div class="timeline-desc timeline-desc-end">{{ $endTileText }}</div>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </section>

    {{-- Our Verticals --}}
    <section class="section-pad">
        <div class="container">
            <h2 class="section-title">Our Verticals</h2>
            <div class="verticals-grid">
                <div class="vertical-card">
                    <h4>{{ !empty(trim($executive->title ?? '')) ? $executive->title : 'Executive Committee' }}</h4>
                    <p>{{ !empty(trim($executive->description ?? '')) ? $executive->description : 'Executive Committee coordinates leadership, governance, and overall strategy.' }}</p>
                    <a href="{{ route('verticals.executive') }}" class="btn-teal" style="font-size:13px;padding:9px 22px">View Details</a>
                </div>
                <div class="vertical-card">
                    <h4>{{ !empty(trim($workingMain->title ?? '')) ? $workingMain->title : 'Working Committees' }}</h4>
                    <p>{{ !empty(trim($workingMain->description ?? '')) ? $workingMain->description : 'Working committees manage active operational programs and alumni initiatives.' }}</p>
                    <a href="{{ route('verticals.working') }}" class="btn-teal" style="font-size:13px;padding:9px 22px">View Details</a>
                </div>
            </div>
        </div>
    </section>

@endsection
