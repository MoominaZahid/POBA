{{-- FILE: resources/views/customer/star-alumni/index.blade.php --}}
@extends('layouts.app')
@section('title','Star Alumni - POBA')
@section('content')

<section class="section-pad" style="padding-top: 40px;">
    <div class="container">
        
        {{-- Clean & Centered Page Title with Full Text Underline --}}
        <div style="text-align: center; margin-bottom: 40px;">
            <h1 style="font-size: 2.5rem; font-weight: 700; color: #086666; display: inline-block; padding-bottom: 8px; border-bottom: 4px solid var(--orange); line-height: 1.2;">
                Star Alumni
            </h1>
        </div>

        <form method="GET" action="{{ route('star.alumni') }}">
            <div class="search-bar">
                <input type="text" name="search" class="search-input" placeholder="Search by Name" value="{{ request('search') }}">
                <select name="class_year" class="filter-select">
                    <option value="">Class Year</option>
                    @foreach(range(date('Y'), 1947, -1) as $y)<option value="{{ $y }}" {{ request('class_year')==$y ? 'selected' : '' }}>{{ $y }}</option>@endforeach
                </select>
                <select name="field_work" class="filter-select">
                    <option value="">Field of Work</option>
                    @foreach(['Navy','Engineering','Medicine','Law','Business','Education','IT','Other'] as $f)
                    <option value="{{ $f }}" {{ request('field_work')==$f ? 'selected' : '' }}>{{ $f }}</option>
                    @endforeach
                </select>
                <select name="city" class="filter-select">
                    <option value="">City</option>
                    @foreach(['Karachi','Lahore','Islamabad','Rawalpindi','Peshawar','Quetta','Jeddah','Dubai','London'] as $c)
                    <option value="{{ $c }}" {{ request('city')==$c ? 'selected' : '' }}>{{ $c }}</option>
                    @endforeach
                </select>
                <button type="submit" class="btn-teal" style="padding:10px 24px">Search</button>
                <a href="{{ route('star.alumni') }}" class="btn-outline-teal" style="padding:8px 22px">Reset</a>
            </div>
        </form>

        {{-- Auto-submit dropdowns for real-time filtering --}}
        <script>
            document.querySelectorAll('.filter-select').forEach(function(sel) {
                sel.addEventListener('change', function() {
                    this.closest('form').submit();
                });
            });
        </script>

        <div class="grid-4">
            @forelse($alumni as $a)
            <div class="alumni-card">
                <img src="{{ $a->profile_photo ? asset('storage/'.$a->profile_photo) : 'https://placehold.co/120x120/1a7a7a/fff?text='.urlencode(substr($a->full_name,0,1)) }}" alt="{{ $a->full_name }}">
                <h4>{{ $a->full_name }}</h4>
                <div class="position">{{ $a->current_designation ?? $a->field_of_work }}</div>
                <div class="desc">{{ Str::limit($a->star_description ?? $a->achievements, 80) }}</div>
                @if($a->class_year)<div class="class-year">Class of {{ $a->class_year }}</div>@endif
                <a href="{{ route('alumni.show', $a->id) }}" class="btn-teal" style="font-size:13px;padding:8px 20px">View Details</a>
            </div>
            @empty
            <div style="grid-column:1/-1;text-align:center;padding:60px;color:var(--text-muted)">No star alumni yet.</div>
            @endforelse
        </div>

        <div style="text-align:center;margin-top:40px">{{ $alumni->appends(request()->query())->links('vendor.pagination.simple-default') }}</div>
    </div>
</section>
@endsection