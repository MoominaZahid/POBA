{{-- FILE: resources/views/customer/events/_event_cards.blade.php --}}
{{--
     Variables expected:
       $events           – paginator
       $myEventIds       – array of event IDs the alumni is registered for
       $myParticipations – array of event_id => status
       $isPrevious       – bool: true when rendering previous events tab
--}}

@foreach($events as $event)

@php
    $isRegistered  = in_array($event->id, $myEventIds ?? []);
    $canRegister   = !$isPrevious && $event->registration_required;
    $isEligible    = true;
    $ineligibleMsg = '';

    if ($canRegister && !empty($event->entry_batches) && Auth::guard('alumni')->check()) {
        $alumniEntry = (int) Auth::guard('alumni')->user()->entry;
        if (!in_array($alumniEntry, $event->entry_batches)) {
            $isEligible    = false;
            $ineligibleMsg = 'Open to batches: ' . implode(', ', $event->entry_batches);
        }
    }

    $myStatus  = ($myParticipations ?? [])[$event->id] ?? '';
    $startTime = $event->start_time ? \Carbon\Carbon::parse($event->start_time)->format('g:i A') : '';
    $endTime   = $event->end_time   ? \Carbon\Carbon::parse($event->end_time)->format('g:i A')   : '';

    $startDateFmt = \Carbon\Carbon::parse($event->start_date)->format('d/m/Y');
    $endDateFmt   = \Carbon\Carbon::parse($event->end_date)->format('d/m/Y');
    $dayNum       = \Carbon\Carbon::parse($event->start_date)->format('d');
    $monthYear    = \Carbon\Carbon::parse($event->start_date)->format('M Y');
@endphp

<div class="ec-card">

    {{-- Left: date badge (transparent, teal text) --}}
    <div class="ec-date-badge">
        <span class="ec-day">{{ $dayNum }}</span>
        <span class="ec-my">{{ $monthYear }}</span>
    </div>

    {{-- Thumbnail --}}
    <img class="ec-thumb"
         src="{{ $event->logo ? asset('storage/'.$event->logo) : 'https://placehold.co/80x90/086666/ffffff?text=Event' }}"
         alt="{{ $event->title }}">

    {{-- Main info --}}
    <div class="ec-body">
        <h4 class="ec-title">{{ $event->title }}</h4>

        <div class="ec-meta">
            <span>📍 {{ $event->location }}</span>
            <span>📅 {{ $startDateFmt }}{{ $startDateFmt !== $endDateFmt ? ' – ' . $endDateFmt : '' }}</span>
            @if($startTime)
                <span>🕐 {{ $startTime }}{{ $endTime ? ' – ' . $endTime : '' }}</span>
            @endif
        </div>

        <div class="ec-focal">
            <strong>Focal Person</strong><br>
            {{ $event->focal_person_name }} – {{ $event->focal_person_number }}
        </div>

        {{-- Collapsible details --}}
        <div class="ec-collapse" id="desc-{{ $event->id }}">
            <div class="ec-collapse-inner">
                @if(!empty($event->entry_batches))
                    <p><strong>Eligible Batches:</strong> {{ implode(', ', $event->entry_batches) }}</p>
                @else
                    <p><strong>Eligible Batches:</strong> Open to all</p>
                @endif
                @if($event->description)
                    <p>{{ $event->description }}</p>
                @endif
                @if($event->gallery_link)
                    <a href="{{ $event->gallery_link }}" target="_blank" class="ec-gallery-link">🖼 View Gallery →</a>
                @endif
            </div>
        </div>

        {{-- "See More" – right-aligned --}}
        <button class="ec-toggle" id="seeMore-{{ $event->id }}"
                onclick="toggleDesc('{{ $event->id }}')">See More</button>
    </div>

    {{-- Actions --}}
    <div class="ec-actions">
        @if($isPrevious)
            @if($event->gallery_link)
                <a href="{{ $event->gallery_link }}" target="_blank" class="ec-btn ec-btn-outline">View Gallery</a>
            @else
                <a href="{{ route('gallery.index') }}" class="ec-btn ec-btn-outline">View Gallery</a>
            @endif

        @elseif(!$canRegister)
            <span class="ec-no-reg">No registration needed</span>

        @elseif($isRegistered)
            <div style="text-align:center;margin-bottom:10px">
                @if($myStatus === 'confirmed')
                    <span class="ec-badge ec-badge-confirmed">✓ Confirmed</span>
                @else
                    <span class="ec-badge ec-badge-pending">⏳ Pending</span>
                @endif
            </div>
            <form method="POST" action="{{ route('events.cancel', $event->id) }}">
                @csrf
                <button type="submit" class="ec-btn ec-btn-cancel"
                        onclick="return confirm('Cancel your registration for this event?')">
                    Cancel Registration
                </button>
            </form>

        @elseif(!Auth::guard('alumni')->check())
            <a href="{{ route('login') }}" class="ec-btn ec-btn-primary">Register Now</a>

        @elseif(!$isEligible)
            <div style="text-align:center">
                <span class="ec-badge ec-badge-ineligible">Not Eligible</span>
                <p class="ec-ineligible-msg">{{ $ineligibleMsg }}</p>
            </div>

        @else
            <form method="POST" action="{{ route('events.register', $event->id) }}">
                @csrf
                <button type="submit" class="ec-btn ec-btn-primary">Register Now</button>
            </form>
        @endif
    </div>

</div>
@endforeach