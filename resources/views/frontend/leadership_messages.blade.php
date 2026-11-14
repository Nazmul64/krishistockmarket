@extends(Auth::check() ? 'layouts.backend.app' : 'layouts.frontend.app')

@section('content')
@if(Auth::check())
<div class="content-wrapper">
    <div class="container-full">
        <section class="content py-3">
@endif
<div class="corporate-page-wrap py-5 bg-light">
    <div class="container">
        <!-- Header -->
        <div class="text-center mb-5">
            <span class="badge bg-success-subtle text-success px-3 py-2 rounded-pill fw-bold font-size-14 mb-2">
                <i class="fa fa-quote-left me-1"></i> দূরদর্শী দিকনির্দেশনা
            </span>
            <h2 class="fw-bold display-6 text-dark mt-2">নেতৃবৃন্দের বাণী (Leadership Messages)</h2>
            <p class="text-muted max-w-700 mx-auto font-size-16">
                প্রতিষ্ঠানের রূপকল্প, প্রযুক্তিগত উৎকর্ষ, বাজার সম্প্রসারণ এবং অর্থনৈতিক স্বচ্ছতা নিশ্চিতে ঊর্ধ্বতন নেতৃত্বের দিকনির্দেশনামূলক বক্তব্য।
            </p>
        </div>

        <!-- 5 Prominent Leadership Messages -->
        <div class="row g-4 justify-content-center">
            @foreach($messages as $msg)
            <div class="col-lg-10" id="{{ $msg->role_type }}">
                <div class="card border-0 shadow-sm rounded-4 overflow-hidden bg-white mb-2 transition-all hover-shadow">
                    <div class="row g-0 align-items-center">
                        <div class="col-md-4 text-center p-4 bg-light d-flex flex-column align-items-center justify-content-center">
                            <img src="{{ !empty($msg->photo) && file_exists(public_path($msg->photo)) ? asset($msg->photo) : asset('frontend/assets/images/team/member1.jpg') }}" 
                                 alt="{{ $msg->name_bn }}" 
                                 class="rounded-circle shadow-md border border-4 border-white mb-3" 
                                 style="width: 150px; height: 150px; object-fit: cover;">
                            <h5 class="fw-bold text-dark mb-1">{{ $msg->name_bn ?: $msg->name }}</h5>
                            <span class="badge bg-success-subtle text-success fw-bold px-3 py-1 rounded-pill mb-1">
                                {{ $msg->designation_bn ?: $msg->designation }}
                            </span>
                            <small class="text-muted">{{ $msg->name }}</small>
                            @if(!empty($msg->email))
                                <small class="text-secondary mt-1"><i class="fa fa-envelope me-1 text-muted"></i>{{ $msg->email }}</small>
                            @endif
                        </div>
                        <div class="col-md-8 p-4 p-lg-5">
                            <div class="d-flex align-items-center gap-2 mb-3">
                                <span class="badge bg-warning text-dark fw-bold px-3 py-1 rounded-pill">
                                    {{ match($msg->role_type) {
                                        'chairman' => '১. চেয়ারম্যানের বাণী',
                                        'md' => '২. ম্যানেজিং ডিরেক্টর (MD)-এর বাণী',
                                        'ceo' => '৩. চিফ এক্সিকিউটিভ অফিসার (CEO)-এর বাণী',
                                        'marketing_head' => '৪. হেড অব মার্কেটিংয়ের বাণী',
                                        'finance_head' => '৫. হেড অব ফাইন্যান্সের বাণী',
                                        default => 'নেতৃত্বের বাণী',
                                    } }}
                                </span>
                            </div>
                            <h4 class="fw-bold text-dark mb-3">
                                {{ $msg->speech_title ?: 'টেকসই উন্নয়ন ও সমৃদ্ধির পথে আমাদের অঙ্গীকার' }}
                            </h4>
                            <div class="position-relative ps-4" style="border-left: 4px solid #10b981;">
                                <p class="text-secondary leading-relaxed font-size-15 mb-0" style="line-height: 1.8; text-align: justify;">
                                    "{{ $msg->speech_message }}"
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>

        <div class="text-center mt-5">
            <a href="{{ route('corporate.board') }}" class="btn btn-outline-success rounded-pill px-4 fw-bold">
                <i class="fa fa-arrow-left me-1"></i> পরিচালনা পর্ষদের সম্পূর্ণ তালিকা দেখুন
            </a>
        </div>
    </div>
</div>
@if(Auth::check())
        </section>
    </div>
</div>
@endif
@endsection

