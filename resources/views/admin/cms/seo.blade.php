{{-- FILE: resources/views/admin/cms/seo.blade.php --}}
@extends('layouts.admin')
@section('title','SEO Settings - Admin')
@section('page-title','SEO Settings')
@section('content')

<div class="admin-form-page">
    <h2>SEO Settings</h2>
    <form method="POST" action="{{ route('admin.cms.seo.save') }}">
        @csrf
        <div class="admin-form-group">
            <label class="admin-form-label">Meta Title:</label>
            <input type="text" name="meta_title" class="admin-input"
                   value="{{ $settings['seo_title'] ?? 'POBA - Pakistan Ocean & Bay Alumni | Official Alumni Network' }}"
                   placeholder="POBA - Pakistan Ocean & Bay Alumni | Official Alumni Network">
        </div>
        <div class="admin-form-group">
            <label class="admin-form-label">Keywords:</label>
            <textarea name="keywords" class="admin-input" rows="4"
                      placeholder="POBA, Pakistan Ocean Bay Alumni, Pakistan Navy Alumni, Naval Officers Network...">{{ $settings['seo_keywords'] ?? '' }}</textarea>
        </div>
        <div class="admin-form-group">
            <label class="admin-form-label">Meta Description:</label>
            <textarea name="meta_description" class="admin-input" rows="4"
                      placeholder="Official Pakistan Ocean & Bay Alumni (POBA) network connecting naval officers across generations...">{{ $settings['seo_description'] ?? '' }}</textarea>
        </div>
        <div class="btn-action-row">
            <button type="submit" class="btn-teal" style="padding:12px 40px">Save</button>
            <button type="reset" class="btn-outline-red" style="padding:12px 40px">Cancel</button>
        </div>
    </form>
</div>
@endsection
