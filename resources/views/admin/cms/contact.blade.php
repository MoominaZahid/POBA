{{-- FILE: resources/views/admin/cms/contact.blade.php --}}
@extends('layouts.admin')
@section('title','CMS Contact - Admin')
@section('page-title','Content Management')
@section('content')
@include('admin.cms._tabs', ['active' => 'contact'])

<form method="POST" action="{{ route('admin.cms.contact.save') }}" enctype="multipart/form-data">
    @csrf

    {{-- Page Details --}}
    <div style="background:#fff;border-radius:var(--radius);padding:28px;margin-bottom:24px;box-shadow:var(--shadow)">
        <div class="cms-section-title">Page Details</div>
        <div class="admin-form-group">
            <label class="admin-form-label">Title: *</label>
            <input type="text" name="contact_page_title" class="admin-input" value="{{ $settings['contact_page_title'] ?? 'Contact Us' }}" placeholder="Contact Us" required>
        </div>
        <div class="admin-form-group">
            <label class="admin-form-label">Description:</label>
            <textarea name="contact_page_description" class="admin-input" rows="4" placeholder="Enter page description...">{{ $settings['contact_page_description'] ?? '' }}</textarea>
        </div>
    </div>

    {{-- Contact Details --}}
    <div style="background:#fff;border-radius:var(--radius);padding:28px;margin-bottom:24px;box-shadow:var(--shadow)">
        <div class="cms-section-title">Contact Details</div>
        <div class="admin-form-row">
            <div class="admin-form-group">
                <label class="admin-form-label">Contact Email:</label>
                <input type="email" name="contact_email" class="admin-input" value="{{ $settings['contact_email'] ?? '' }}" placeholder="info@poba.com">
            </div>
            <div class="admin-form-group">
                <label class="admin-form-label">Contact Number:</label>
                <input type="text" name="contact_number" class="admin-input" value="{{ $settings['contact_number'] ?? '' }}" placeholder="+92 21 123 4567">
            </div>
        </div>
        <div class="admin-form-group">
            <label class="admin-form-label">Location:</label>
            <input type="text" name="location" class="admin-input" value="{{ $settings['location'] ?? '' }}" placeholder="Cadet College Palandri">
        </div>
        <div class="admin-form-group">
            <label class="admin-form-label">Location Google Map Link:</label>
            <input type="url" name="google_map_link" class="admin-input" value="{{ $settings['google_map_link'] ?? '' }}" placeholder="https://maps.google.com/...">
        </div>
        <div class="admin-form-group">
            <label class="admin-form-label">Social Media Links:</label>
            <div style="display:grid;grid-template-columns:1fr 1fr;gap:14px">
                @foreach(['twitter'=>'Twitter','instagram'=>'Instagram','facebook'=>'Facebook','tiktok'=>'TikTok','linkedin'=>'LinkedIn','youtube'=>'YouTube'] as $key => $label)
                <div style="display:flex;align-items:center;gap:10px">
                    <label style="display:flex;align-items:center;gap:6px;min-width:100px;font-size:13px;cursor:pointer">
                        <input type="checkbox" name="social_enabled[]" value="{{ $key }}"
                               {{ !empty($settings['social_'.$key]) ? 'checked' : '' }}
                               style="accent-color:var(--teal)">
                        {{ $label }}
                    </label>
                    <input type="url" name="social_{{ $key }}" class="admin-input" value="{{ $settings['social_'.$key] ?? '' }}"
                           placeholder="https://{{ $key }}.com/..." style="flex:1">
                </div>
                @endforeach
            </div>
        </div>
    </div>

    {{-- Bank Details --}}
    <div style="background:#fff;border-radius:var(--radius);padding:28px;margin-bottom:24px;box-shadow:var(--shadow)">
        <div class="cms-section-title">Bank Details</div>
        <div class="admin-form-row">
            <div class="admin-form-group">
                <label class="admin-form-label">Bank Title:</label>
                <input type="text" name="bank_title" class="admin-input" value="{{ $settings['bank_title'] ?? '' }}" placeholder="Bank of AJK">
            </div>
            <div class="admin-form-group">
                <label class="admin-form-label">Account Title:</label>
                <input type="text" name="account_title" class="admin-input" value="{{ $settings['account_title'] ?? '' }}" placeholder="Palandarians Old Boys Association">
            </div>
        </div>
        <div class="admin-form-row">
            <div class="admin-form-group">
                <label class="admin-form-label">Account Number/IBAN:</label>
                <input type="text" name="account_number" class="admin-input" value="{{ $settings['account_number'] ?? '' }}" placeholder="PK01ABL0214567980">
            </div>
            <div class="admin-form-group">
                <label class="admin-form-label">Branch Number:</label>
                <input type="text" name="branch_number" class="admin-input" value="{{ $settings['branch_number'] ?? '' }}" placeholder="063">
            </div>
        </div>
        <div class="admin-form-group">
            <label class="admin-form-label">Upload QR Code:</label>
            <div style="display:flex;align-items:center;gap:15px;">
                <div onclick="document.getElementById('qrFile').click()"
                    style="flex:1;display:flex;align-items:center;gap:10px;background:#E6F3F4;border:1px solid #E6F3F4;border-radius:30px;padding:12px 18px;cursor:pointer;">
                    <span style="width:22px;height:22px;background:#0B8CA3;color:#fff;border-radius:4px;display:flex;align-items:center;justify-content:center;font-size:16px;font-weight:bold;">+</span>
                    <span id="qrFileName" style="font-size:13px;color:#666;">Drag & Drop files here or click to select file(s)</span>
                    <input type="file" id="qrFile" name="qr_code" accept="image/*" style="display:none"
                        onchange="document.getElementById('qrFileName').textContent='✓ '+this.files[0].name">
                </div>
                @if(!empty($settings['qr_code']))
                    <img src="{{ asset('storage/'.$settings['qr_code']) }}" alt="QR"
                        style="width:55px;height:55px;object-fit:cover;border-radius:8px;border:1px solid #ddd;">
                @endif
            </div>
        </div>
    </div>

    {{-- Footer Section --}}
    <div style="background:#fff;border-radius:var(--radius);padding:28px;margin-bottom:24px;box-shadow:var(--shadow)">
        <div class="cms-section-title">Footer Section</div>
        <div class="admin-form-row">
            <div class="admin-form-group">
                <label class="admin-form-label">Copyright Text:</label>
                <input type="text" name="footer_copyright" class="admin-input" value="{{ $settings['footer_copyright'] ?? '© 2025. All rights reserved.' }}" placeholder="© 2025. All rights reserved.">
            </div>
            <div class="admin-form-group">
                <label class="admin-form-label">Year: *</label>
                <select name="footer_year" class="admin-input" required>
                    @for($y = 2020; $y <= 2035; $y++)
                        <option value="{{ $y }}" {{ ($settings['footer_year'] ?? date('Y')) == $y ? 'selected' : '' }}>{{ $y }}</option>
                    @endfor
                </select>
            </div>
        </div>
    </div>

    <div class="btn-action-row">
        <button type="submit" class="btn-teal" style="padding:12px 40px">Save</button>
        <button type="reset" class="btn-outline-red" style="padding:12px 40px">Cancel</button>
    </div>
</form>
@endsection
