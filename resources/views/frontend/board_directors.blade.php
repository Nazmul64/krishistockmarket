@extends(Auth::check() ? 'layouts.backend.app' : 'layouts.frontend.app')

@section('content')
@if(Auth::check())
<div class="content-wrapper">
    <div class="container-full">
        <section class="content py-3">
@endif
<div class="corporate-page-wrap py-5 bg-light">
    <div class="container">
        <!-- Page Breadcrumb / Header -->
        <div class="text-center mb-5">
            <span class="badge bg-success-subtle text-success px-3 py-2 rounded-pill fw-bold font-size-14 mb-2">
                <i class="fa fa-users me-1"></i> পরিচালনা পর্ষদ ও প্রশাসনিক পরিচিতি
            </span>
            <h2 class="fw-bold display-6 text-dark mt-2">বোর্ড অব ডিরেক্টরস (Board of Directors)</h2>
            <p class="text-muted max-w-700 mx-auto font-size-16">
                প্রতিষ্ঠানের কৌশলগত নীতি নির্ধারণ, করপোরেট সুশাসন ও গ্রাহকের বিনিয়োগের সার্বিক নিরাপত্তা বিধানে নিয়োজিত পরিচালনা পর্ষদ।
            </p>
            <div class="d-flex justify-content-center gap-2 mt-3">
                <a href="{{ route('corporate.leadership_messages') }}" class="btn btn-outline-success rounded-pill px-4 btn-sm fw-bold">
                    <i class="fa fa-quote-left me-1"></i> নেতৃবৃন্দের বাণী দেখুন
                </a>
                <a href="{{ route('corporate.employees') }}" class="btn btn-outline-primary rounded-pill px-4 btn-sm fw-bold">
                    <i class="fa fa-id-badge me-1"></i> কর্মকর্তা-কর্মচারী তালিকা
                </a>
            </div>
        </div>

        <!-- Chairman Featured Profile (Section 1.1) -->
        @if($chairman)
        <div class="row justify-content-center mb-5">
            <div class="col-lg-10">
                <div class="card border-0 shadow-lg rounded-4 overflow-hidden" style="background: linear-gradient(135deg, #064e3b 0%, #047857 100%); color: #ffffff;">
                    <div class="row g-0 align-items-center">
                        <div class="col-md-4 text-center p-4">
                            <div class="position-relative d-inline-block">
                                <img src="{{ !empty($chairman->photo) && file_exists(public_path($chairman->photo)) ? asset($chairman->photo) : asset('frontend/assets/images/team/member1.jpg') }}" 
                                     alt="{{ $chairman->name_bn }}" 
                                     class="rounded-circle shadow-lg border border-4 border-warning" 
                                     style="width: 200px; height: 200px; object-fit: cover;">
                                <span class="badge bg-warning text-dark position-absolute bottom-0 start-50 translate-middle-x px-3 py-1 rounded-pill fw-bold shadow">
                                    {{ $chairman->designation_bn ?: $chairman->designation }}
                                </span>
                            </div>
                        </div>
                        <div class="col-md-8 p-4 p-lg-5">
                            <span class="badge bg-light text-dark fw-bold px-3 py-1 rounded-pill mb-2">চেয়ারম্যানের প্রোফাইল</span>
                            <h3 class="fw-bold text-white mb-1">{{ $chairman->name_bn ?: $chairman->name }}</h3>
                            <p class="text-warning fw-semibold mb-3">{{ $chairman->designation_bn }} | {{ $chairman->name }}</p>
                            <p class="text-light opacity-90 mb-3" style="line-height: 1.7;">
                                {{ $chairman->bio }}
                            </p>
                            <div class="p-3 rounded-3 mb-3" style="background: rgba(255,255,255,0.1); border-left: 4px solid #f59e0b;">
                                <strong class="text-warning d-block font-size-14 mb-1"><i class="fa fa-shield me-1"></i> দায়িত্ব ও প্রশাসনিক ভূমিকা:</strong>
                                <span class="text-white font-size-14">{{ $chairman->administrative_role }}</span>
                            </div>
                            @if($chairman->speech_message)
                                <a href="{{ route('corporate.leadership_messages') }}#chairman" class="btn btn-warning text-dark rounded-pill px-4 fw-bold">
                                    চেয়ারম্যানের বাণী পড়ুন <i class="fa fa-arrow-right ms-1"></i>
                                </a>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endif

        <!-- Executive Directors (MD, CEO, Heads, Directors) -->
        <div class="row g-4">
            @foreach($members as $m)
            <div class="col-md-6 col-lg-4">
                <div class="card h-100 border-0 shadow-sm rounded-4 overflow-hidden transition-all hover-shadow bg-white">
                    <div class="card-body p-4 text-center">
                        <div class="mb-3 position-relative d-inline-block">
                            <img src="{{ !empty($m->photo) && file_exists(public_path($m->photo)) ? asset($m->photo) : asset('frontend/assets/images/team/member2.jpg') }}" 
                                 alt="{{ $m->name_bn }}" 
                                 class="rounded-circle shadow-sm border border-3 border-light" 
                                 style="width: 140px; height: 140px; object-fit: cover;">
                            <span class="badge {{ $m->role_type === 'md' || $m->role_type === 'ceo' ? 'bg-danger' : 'bg-primary' }} position-absolute bottom-0 start-50 translate-middle-x px-3 py-1 rounded-pill fw-bold shadow-sm" style="font-size: 11px;">
                                {{ $m->designation_bn ?: $m->designation }}
                            </span>
                        </div>
                        <h5 class="fw-bold text-dark mt-2 mb-1">{{ $m->name_bn ?: $m->name }}</h5>
                        <p class="text-muted small fw-medium mb-3">{{ $m->name }}</p>
                        <p class="text-secondary small mb-3" style="min-height: 48px; line-height: 1.6;">
                            {{ Str::limit($m->bio, 110) }}
                        </p>
                        <div class="bg-light p-2 rounded-3 text-start small mb-3">
                            <strong class="text-success d-block mb-1"><i class="fa fa-briefcase me-1"></i> ভূমিকা:</strong>
                            <span class="text-muted">{{ Str::limit($m->administrative_role, 80) }}</span>
                        </div>
                        @if($m->speech_message)
                            <a href="{{ route('corporate.leadership_messages') }}#{{ $m->role_type }}" class="btn btn-outline-success btn-sm rounded-pill w-100 fw-bold">
                                বাণী ও পূর্ণ প্রোফাইল <i class="fa fa-angle-right ms-1"></i>
                            </a>
                        @endif
                    </div>
                </div>
            </div>
            @endforeach
        </div>

    </div>
</div>
@if(Auth::check())
        </section>
    </div>
</div>
@endif
@endsection

