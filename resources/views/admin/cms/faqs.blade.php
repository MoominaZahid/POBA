{{-- FILE: resources/views/admin/cms/faqs.blade.php --}}
@extends('layouts.admin')
@section('title','CMS FAQs - Admin')
@section('page-title','Content Management')
@section('content')
@include('admin.cms._tabs', ['active' => 'faqs'])

<div style="background:#fff;border-radius:var(--radius);padding:28px;box-shadow:var(--shadow)">
    <div class="cms-section-title">Frequently Asked Questions</div>

    <form method="POST" action="{{ route('admin.cms.faqs.save') }}">
        @csrf

        <div id="faqRows">
            @forelse($faqs as $i => $faq)
            <div class="faq-row" id="faqRow-{{ $i }}" style="border:1.5px solid var(--border);border-radius:10px;padding:20px;margin-bottom:16px">
                <div style="display:flex;justify-content:space-between;align-items:flex-start">
                    <div style="flex:1;margin-right:12px">
                        <div class="admin-form-group">
                            <label class="admin-form-label">Question:</label>
                            <input type="text" name="questions[]" class="admin-input" value="{{ $faq->question }}" placeholder="Enter question..." required>
                        </div>
                        <div class="admin-form-group">
                            <label class="admin-form-label">Answer:</label>
                            <textarea name="answers[]" class="admin-input" rows="4" placeholder="Enter answer...">{{ $faq->answer }}</textarea>
                        </div>
                    </div>
                    <button type="button" onclick="this.closest('.faq-row').remove()" style="background:none;border:none;color:#e74c3c;cursor:pointer;font-size:20px;margin-top:4px">🗑</button>
                </div>
            </div>
            @empty
            {{-- empty default row --}}
            <div class="faq-row" id="faqRow-0" style="border:1.5px solid var(--border);border-radius:10px;padding:20px;margin-bottom:16px">
                <div style="display:flex;justify-content:space-between;align-items:flex-start">
                    <div style="flex:1;margin-right:12px">
                        <div class="admin-form-group">
                            <label class="admin-form-label">Question:</label>
                            <input type="text" name="questions[]" class="admin-input" placeholder="Enter question...">
                        </div>
                        <div class="admin-form-group">
                            <label class="admin-form-label">Answer:</label>
                            <textarea name="answers[]" class="admin-input" rows="4" placeholder="Enter answer..."></textarea>
                        </div>
                    </div>
                    <button type="button" onclick="this.closest('.faq-row').remove()" style="background:none;border:none;color:#e74c3c;cursor:pointer;font-size:20px;margin-top:4px">🗑</button>
                </div>
            </div>
            @endforelse
        </div>

        <a href="#" onclick="addFaqRow();return false" style="font-size:13px;color:var(--teal);font-weight:600;display:inline-block;margin-bottom:24px">+ Add row</a>

        <div class="btn-action-row">
            <button type="submit" class="btn-teal" style="padding:12px 40px">Save</button>
            <button type="reset" class="btn-outline-red" style="padding:12px 40px">Cancel</button>
        </div>
    </form>
</div>

@push('scripts')
<script>
let faqCount = {{ $faqs->count() ?: 1 }};
function addFaqRow() {
    const i = faqCount++;
    document.getElementById('faqRows').insertAdjacentHTML('beforeend', `
        <div class="faq-row" id="faqRow-${i}" style="border:1.5px solid var(--border);border-radius:10px;padding:20px;margin-bottom:16px">
            <div style="display:flex;justify-content:space-between;align-items:flex-start">
                <div style="flex:1;margin-right:12px">
                    <div class="admin-form-group">
                        <label class="admin-form-label">Question:</label>
                        <input type="text" name="questions[]" class="admin-input" placeholder="Enter question...">
                    </div>
                    <div class="admin-form-group">
                        <label class="admin-form-label">Answer:</label>
                        <textarea name="answers[]" class="admin-input" rows="4" placeholder="Enter answer..."></textarea>
                    </div>
                </div>
                <button type="button" onclick="this.closest('.faq-row').remove()" style="background:none;border:none;color:#e74c3c;cursor:pointer;font-size:20px;margin-top:4px">🗑</button>
            </div>
        </div>`);
}
</script>
@endpush
@endsection
