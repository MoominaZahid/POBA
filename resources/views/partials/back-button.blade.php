{{-- FILE: resources/views/partials/back-button.blade.php --}}
@php
    $href = $href ?? url()->previous();
    $label = $label ?? 'Back';
    $extraClass = $class ?? '';
@endphp
<a href="{{ $href }}" title="{{ $label }}" aria-label="{{ $label }}" class="btn-back {{ $extraClass }}">
    <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
        <line x1="20" y1="12" x2="4" y2="12"></line>
        <polyline points="10 18 4 12 10 6"></polyline>
    </svg>
    <span>{{ $label }}</span>
</a>
