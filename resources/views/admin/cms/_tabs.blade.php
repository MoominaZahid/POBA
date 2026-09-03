{{-- FILE: resources/views/admin/cms/_tabs.blade.php --}}
<div class="cms-tabs" style="margin-bottom:28px">
    <div class="cms-tab {{ $active === 'homepage' ? 'active' : '' }}">
        <a href="{{ route('admin.cms.homepage') }}">Homepage</a>
    </div>
    <div class="cms-tab {{ $active === 'about' ? 'active' : '' }}">
        <a href="{{ route('admin.cms.about') }}">About Us</a>
    </div>
    <div class="cms-tab {{ $active === 'news' ? 'active' : '' }}">
        <a href="{{ route('admin.cms.news') }}">News</a>
    </div>
    <div class="cms-tab {{ $active === 'verticals' ? 'active' : '' }}">
        <a href="{{ route('admin.cms.verticals') }}">Verticals</a>
    </div>
    <div class="cms-tab {{ $active === 'contact' ? 'active' : '' }}">
        <a href="{{ route('admin.cms.contact') }}">Contact Us</a>
    </div>
    <div class="cms-tab {{ $active === 'promotions' ? 'active' : '' }}">
        <a href="{{ route('admin.cms.promotions') }}">Promotions</a>
    </div>
    <div class="cms-tab {{ $active === 'faqs' ? 'active' : '' }}">
        <a href="{{ route('admin.cms.faqs') }}">FAQs</a>
    </div>
    <div class="cms-tab {{ $active === 'membership' ? 'active' : '' }}">
        <a href="{{ route('member.index') }}" target="_blank">Membership</a>
    </div>
</div>
