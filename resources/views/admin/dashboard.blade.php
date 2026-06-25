{{-- FILE: resources/views/admin/dashboard.blade.php --}}
@extends('layouts.admin')
@section('title','Dashboard - Admin')
@section('page-title','Dashboard')
@section('content')

<div class="grid-4" style="gap:20px;margin-bottom:30px">

    <a href="{{ route('admin.alumni.index') }}" style="text-decoration:none;display:block">
        <div style="background:#fff;border-radius:var(--radius);padding:24px;box-shadow:var(--shadow);border-left:4px solid var(--teal);cursor:pointer;transition:box-shadow .2s"
             onmouseover="this.style.boxShadow='0 8px 25px rgba(26,122,122,0.2)'"
             onmouseout="this.style.boxShadow='var(--shadow)'">
            <p style="font-size:13px;color:var(--text-muted);margin-bottom:8px">Total Alumni</p>
            <div style="font-size:2.2rem;font-weight:800;color:var(--teal)">{{ $totalAlumni }}</div>
        </div>
    </a>

    <a href="{{ route('admin.alumni.approvals') }}" style="text-decoration:none;display:block">
        <div style="background:#fff;border-radius:var(--radius);padding:24px;box-shadow:var(--shadow);border-left:4px solid var(--orange);cursor:pointer;transition:box-shadow .2s"
             onmouseover="this.style.boxShadow='0 8px 25px rgba(232,119,34,0.2)'"
             onmouseout="this.style.boxShadow='var(--shadow)'">
            <p style="font-size:13px;color:var(--text-muted);margin-bottom:8px">Pending Approvals</p>
            <div style="font-size:2.2rem;font-weight:800;color:var(--orange)">{{ $pendingAlumni }}</div>
        </div>
    </a>

    <a href="{{ route('admin.events.index') }}" style="text-decoration:none;display:block">
        <div style="background:#fff;border-radius:var(--radius);padding:24px;box-shadow:var(--shadow);border-left:4px solid #3498db;cursor:pointer;transition:box-shadow .2s"
             onmouseover="this.style.boxShadow='0 8px 25px rgba(52,152,219,0.2)'"
             onmouseout="this.style.boxShadow='var(--shadow)'">
            <p style="font-size:13px;color:var(--text-muted);margin-bottom:8px">Total Events</p>
            <div style="font-size:2.2rem;font-weight:800;color:#3498db">{{ $totalEvents }}</div>
        </div>
    </a>

    <a href="{{ route('admin.events.index') }}" style="text-decoration:none;display:block">
        <div style="background:#fff;border-radius:var(--radius);padding:24px;box-shadow:var(--shadow);border-left:4px solid #27ae60;cursor:pointer;transition:box-shadow .2s"
             onmouseover="this.style.boxShadow='0 8px 25px rgba(39,174,96,0.2)'"
             onmouseout="this.style.boxShadow='var(--shadow)'">
            <p style="font-size:13px;color:var(--text-muted);margin-bottom:8px">Upcoming Events</p>
            <div style="font-size:2.2rem;font-weight:800;color:#27ae60">{{ $upcomingEvents }}</div>
        </div>
    </a>

</div>

<div class="grid-2" style="gap:24px">
    <div style="background:#fff;border-radius:var(--radius);padding:24px;box-shadow:var(--shadow)">
        <h3 style="font-size:17px;font-weight:700;margin-bottom:18px;color:var(--text-dark)">Quick Actions</h3>
        <div style="display:flex;flex-direction:column;gap:12px">
            <a href="{{ route('admin.alumni.approvals') }}" class="btn-teal" style="text-align:center;padding:12px">
                Review Pending Approvals ({{ $pendingAlumni }})
            </a>
            <a href="{{ route('admin.events.create') }}" class="btn-outline-teal" style="text-align:center;padding:12px">
                Create New Event
            </a>
            <a href="{{ route('admin.gallery.create') }}" class="btn-outline-teal" style="text-align:center;padding:12px">
                Add Gallery Folder
            </a>
            <a href="{{ route('admin.cms.news') }}" class="btn-outline-teal" style="text-align:center;padding:12px">
                Manage News
            </a>
        </div>
    </div>

    <div style="background:#fff;border-radius:var(--radius);padding:24px;box-shadow:var(--shadow)">
        <h3 style="font-size:17px;font-weight:700;margin-bottom:18px;color:var(--text-dark)">Navigation</h3>
        <div style="display:grid;grid-template-columns:1fr 1fr;gap:12px">
            @foreach([
                ['label'=>'Alumni Users',   'route'=>'admin.alumni.index',        'icon'=>'👥'],
                ['label'=>'Events',         'route'=>'admin.events.index',         'icon'=>'📅'],
                ['label'=>'CMS',            'route'=>'admin.cms.homepage',         'icon'=>'📝'],
                ['label'=>'Gallery',        'route'=>'admin.gallery.index',        'icon'=>'🖼'],
                ['label'=>'Admin Users',    'route'=>'admin.admin-users.index',    'icon'=>'👤'],
                ['label'=>'SEO Settings',   'route'=>'admin.cms.seo',              'icon'=>'🔍'],
            ] as $item)
            <a href="{{ route($item['route']) }}"
               style="display:flex;align-items:center;gap:10px;padding:14px;border:1.5px solid var(--border);border-radius:10px;color:var(--text-dark);font-size:14px;font-weight:500;text-decoration:none;transition:all .2s"
               onmouseover="this.style.borderColor='var(--teal)';this.style.background='var(--teal-light)'"
               onmouseout="this.style.borderColor='var(--border)';this.style.background='#fff'">
                <span style="font-size:22px">{{ $item['icon'] }}</span>
                {{ $item['label'] }}
            </a>
            @endforeach
        </div>
    </div>
</div>
@endsection
