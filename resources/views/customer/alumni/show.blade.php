{{-- FILE: resources/views/customer/alumni/show.blade.php --}}
@extends('layouts.app')
@section('title', $alumni->full_name . ' - POBA')
@section('content')

    <section class="section-pad">
        <div class="container">
            <div style="display:flex;gap:40px;align-items:flex-start;flex-wrap:wrap">
                {{-- Sidebar --}}
                <div style="min-width:200px;text-align:center">
                    <img src="{{ $alumni->profile_photo ? asset('storage/' . $alumni->profile_photo) : 'https://placehold.co/160x160/1a7a7a/fff?text=' . urlencode(substr($alumni->full_name, 0, 1)) }}"
                        alt="{{ $alumni->full_name }}"
                        style="width:160px;height:160px;border-radius:12px;object-fit:cover;margin-bottom:14px">
                </div>

                {{-- Main Info --}}
                <div style="flex:1;min-width:300px">
                    @if ($isOwnProfile)
                        <div
                            style="background:#f0fdfa;border:1px solid #0d9488;border-radius:8px;padding:10px 16px;margin-bottom:16px;font-size:13px;color:#0d9488;font-weight:600;">
                            👤 You are viewing your own profile. All fields are visible to you regardless of privacy
                            settings.
                        </div>
                    @endif
                    </h1>
                    @if ($visibleFields['current_designation'] || $visibleFields['current_organization'])
                        <p style="font-weight:700;font-size:16px;margin-bottom:4px">
                            @if ($visibleFields['current_designation'])
                                {{ $alumni->current_designation }}
                            @endif
                            @if ($visibleFields['current_designation'] && $visibleFields['current_organization'])
                                -
                            @endif
                            @if ($visibleFields['current_organization'])
                                {{ $alumni->current_organization }}
                            @endif
                        </p>
                    @endif
                    <p style="color:var(--text-muted);margin-bottom:14px">Class of {{ $alumni->class_year }}</p>

                    <div
                        style="display:flex;justify-content:space-between;flex-wrap:wrap;gap:10px;margin-bottom:20px;padding-bottom:20px;border-bottom:1px solid var(--border)">
                        @if ($visibleFields['phone_number'])
                            <span style="font-size:14px;display:flex;align-items:center;gap:6px">📞
                                {{ $alumni->phone_number }}</span>
                        @endif
                        @if ($visibleFields['email'])
                            <span style="font-size:14px;display:flex;align-items:center;gap:6px">✉️
                                {{ $alumni->email }}</span>
                        @endif
                    </div>

                    @if ($alumni->featured_text)
                        <div style="margin-bottom:20px">
                            <h3 style="font-size:15px;font-weight:700;margin-bottom:8px">Featured Text</h3>
                            <p style="font-size:14px;color:var(--text-muted);line-height:1.8">{{ $alumni->featured_text }}
                            </p>
                        </div>
                    @endif

                    <div class="grid-4" style="gap:20px;margin-bottom:20px">
                        <div>
                            <p style="font-size:12px;color:var(--text-muted);margin-bottom:2px">Entry</p>
                            <p style="font-weight:600">{{ $alumni->entry }}</p>
                        </div>
                        <div>
                            <p style="font-size:12px;color:var(--text-muted);margin-bottom:2px">CCP No.</p>
                            <p style="font-weight:600">{{ $alumni->ccp_no }}</p>
                        </div>
                        <div>
                            <p style="font-size:12px;color:var(--text-muted);margin-bottom:2px">House</p>
                            <p style="font-weight:600">{{ $alumni->house }}</p>
                        </div>
                        <div>
                            <p style="font-size:12px;color:var(--text-muted);margin-bottom:2px">Education</p>
                            <p style="font-weight:600">{{ $alumni->education }}</p>
                        </div>
                        <div>
                            <p style="font-size:12px;color:var(--text-muted);margin-bottom:2px">Field of Study</p>
                            <p style="font-weight:600">{{ $alumni->field_of_study }}</p>
                        </div>
                        <div>
                            <p style="font-size:12px;color:var(--text-muted);margin-bottom:2px">Field of Work</p>
                            <p style="font-weight:600">{{ $alumni->field_of_work }}</p>
                        </div>
                        @if ($visibleFields['current_city'])
                            <div>
                                <p style="font-size:12px;color:var(--text-muted);margin-bottom:2px">Current City</p>
                                <p style="font-weight:600">{{ $alumni->current_city }}</p>
                            </div>
                        @endif
                        <div>
                            <p style="font-size:12px;color:var(--text-muted);margin-bottom:2px">Current Country</p>
                            <p style="font-weight:600">{{ $alumni->current_country }}</p>
                        </div>
                    </div>

                    @if ($alumni->achievements && $visibleFields['achievements'])
                        <div>
                            <h3 style="font-size:15px;font-weight:700;margin-bottom:8px">Achievements</h3>
                            <p style="font-size:14px;color:var(--text-muted);line-height:1.8">{{ $alumni->achievements }}
                            </p>
                        </div>
                    @endif
                </div>
            </div>

            <div style="margin-top:30px">
                <a href="{{ url()->previous() }}" class="btn-outline-teal">← Back</a>
            </div>
        </div>
    </section>
@endsection
