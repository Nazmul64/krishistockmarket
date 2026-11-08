@extends('layouts.frontend.app')

@section('content')
<!-- React Home Application Root Container -->
<div id="react-home-root" data-props='{!! json_encode($data, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP | JSON_UNESCAPED_UNICODE) !!}'>
    <!-- Server-rendered placeholder for SEO and initial load -->
    <div class="py-5 text-center bg-light">
        <div class="spinner-border text-warning" role="status">
            <span class="visually-hidden">লোড হচ্ছে...</span>
        </div>
        <p class="mt-2 text-muted fw-bold">ekrishisheba লোড হচ্ছে...</p>
    </div>
</div>
@endsection
