@extends(Auth::check() ? 'layouts.backend.app' : 'layouts.frontend.app')

@section('content')
@if(Auth::check())
<div class="content-wrapper">
    <div class="container-full">
        <section class="content py-3">
@endif
<div class="corporate-page-wrap py-5 bg-light">
    <div class="container">
        <!-- Hero Header -->
        <div class="text-center mb-5">
            <span class="badge bg-success text-white px-3 py-2 rounded-pill fw-bold font-size-14 mb-2">
                <i class="fa fa-building me-1"></i> প্রাতিষ্ঠানিক পরিচিতি
            </span>
            <h2 class="fw-bold display-6 text-dark mt-2">প্রতিষ্ঠানের পরিচিতি ও মূল দর্শন</h2>
            <p class="text-muted max-w-700 mx-auto font-size-16">
                প্রযুক্তিনির্ভর কৃষি সেবা, টেকসই খামার প্রকল্প এবং স্বচ্ছ যৌথ বিনিয়োগ ব্যবস্থাপনায় আমাদের গৌরবময় অভিযাত্রা।
            </p>
        </div>

        <!-- Section 1: Vision & Mission (Hero Cards) -->
        <div class="row g-4 mb-5">
            <div class="col-md-6">
                <div class="card h-100 border-0 shadow-sm rounded-4 p-4 text-white position-relative overflow-hidden" 
                     style="background: linear-gradient(135deg, #065f46 0%, #047857 100%);">
                    <div class="position-absolute end-0 bottom-0 opacity-10 p-3" style="font-size: 100px;">
                        <i class="fa fa-eye"></i>
                    </div>
                    <span class="badge bg-warning text-dark fw-bold px-3 py-1 rounded-pill mb-3 w-max">ভিশন (Vision)</span>
                    <h3 class="fw-bold mb-3 text-white">আমাদের দূরদর্শী লক্ষ্য</h3>
                    <p class="text-light opacity-90 leading-relaxed font-size-15 mb-4">
                        {{ $visionMission->content_bn ?? 'প্রযুক্তিনির্ভর কৃষি, নিরাপদ খাদ্য উৎপাদন এবং টেকসই সমবায়ভিত্তিক বিনিয়োগে বাংলাদেশের শীর্ষ নির্ভরযোগ্য ও দৃষ্টান্তমূলক করপোরেট প্রতিষ্ঠান হিসেবে আত্মপ্রকাশ করা।' }}
                    </p>
                    <ul class="list-unstyled text-light small mb-0">
                        @if(!empty($visionMission->extra_points))
                            @foreach($visionMission->extra_points as $pt)
                                <li class="mb-2"><i class="fa fa-check-circle text-warning me-2"></i> {{ $pt }}</li>
                            @endforeach
                        @endif
                    </ul>
                </div>
            </div>

            <div class="col-md-6">
                <div class="card h-100 border-0 shadow-sm rounded-4 p-4 text-white position-relative overflow-hidden" 
                     style="background: linear-gradient(135deg, #1e3a8a 0%, #2563eb 100%);">
                    <div class="position-absolute end-0 bottom-0 opacity-10 p-3" style="font-size: 100px;">
                        <i class="fa fa-bullseye"></i>
                    </div>
                    <span class="badge bg-warning text-dark fw-bold px-3 py-1 rounded-pill mb-3 w-max">মিশন (Mission)</span>
                    <h3 class="fw-bold mb-3 text-white">আমাদের কার্যব্রত ও প্রতিশ্রুতি</h3>
                    <p class="text-light opacity-90 leading-relaxed font-size-15 mb-4">
                        প্রতিটি কৃষককে আধুনিক প্রযুক্তি ও ন্যায্য বাজারমূল্যের আওতায় আনা, বিনিয়োগকারীদের জন্য সম্পূর্ণ স্বচ্ছ, জবাবদিহিতামূলক ও হালাল আয়ের প্ল্যাটফর্ম সৃষ্টি করা এবং দেশের খাদ্য নিরাপত্তায় সুদৃঢ় ভূমিকা রাখা।
                    </p>
                    <ul class="list-unstyled text-light small mb-0">
                        <li class="mb-2"><i class="fa fa-check-circle text-warning me-2"></i> কৃষক ও ভোক্তার সরাসরি মেলবন্ধন গড়ে তোলা</li>
                        <li class="mb-2"><i class="fa fa-check-circle text-warning me-2"></i> আধুনিক আইওটি প্রযুক্তি ও গ্রিনহাউস চাষাবাদ সম্প্রসারণ</li>
                        <li class="mb-2"><i class="fa fa-check-circle text-warning me-2"></i> সম্পূর্ণ নিরীক্ষিত ও আইনসম্মত যৌথ বিনিয়োগ প্ল্যাটফর্ম</li>
                    </ul>
                </div>
            </div>
        </div>

        <!-- Section 2: History & Background -->
        @if($history)
        <div class="card border-0 shadow-sm rounded-4 p-4 p-lg-5 mb-5 bg-white">
            <div class="row align-items-center g-4">
                <div class="col-lg-7">
                    <span class="badge bg-success-subtle text-success fw-bold px-3 py-1 rounded-pill mb-2">ইতিহাস ও পটভূমি</span>
                    <h3 class="fw-bold text-dark mb-3">{{ $history->title_bn }}</h3>
                    <p class="text-muted leading-relaxed font-size-15 mb-4" style="line-height: 1.8;">
                        {{ $history->content_bn }}
                    </p>
                    @if(!empty($history->extra_points))
                    <div class="row g-2">
                        @foreach($history->extra_points as $h)
                        <div class="col-sm-6">
                            <div class="p-3 bg-light rounded-3 border-start border-4 border-success">
                                <span class="badge bg-success text-white mb-1">{{ $h['year'] ?? '' }}</span>
                                <h6 class="fw-bold text-dark mb-1 font-size-14">{{ $h['title'] ?? '' }}</h6>
                                <p class="text-muted small mb-0">{{ $h['desc'] ?? '' }}</p>
                            </div>
                        </div>
                        @endforeach
                    </div>
                    @endif
                </div>
                <div class="col-lg-5 text-center">
                    <img src="{{ asset('frontend/assets/images/about/about-img.jpg') }}" alt="History" class="img-fluid rounded-4 shadow-sm" style="max-height: 380px; width: 100%; object-fit: cover;">
                </div>
            </div>
        </div>
        @endif

        <!-- Section 3: Goals, Objectives & Principles -->
        @if($goals)
        <div class="mb-5">
            <div class="text-center mb-4">
                <span class="badge bg-warning-subtle text-warning fw-bold px-3 py-1 rounded-pill">মূলনীতি ও স্তম্ভ</span>
                <h3 class="fw-bold text-dark mt-2">{{ $goals->title_bn }}</h3>
            </div>
            <div class="row g-3">
                @if(!empty($goals->extra_points))
                    @foreach($goals->extra_points as $gp)
                    <div class="col-md-6 col-lg-3">
                        <div class="card h-100 border-0 shadow-sm rounded-4 p-4 bg-white text-center">
                            <div class="mx-auto mb-3 text-success p-3 rounded-circle bg-success-subtle" style="width: 60px; height: 60px; display: flex; align-items: center; justify-content: center;">
                                <i class="fa fa-shield fa-2x"></i>
                            </div>
                            <h5 class="fw-bold text-dark mb-2 font-size-16">{{ $gp['principle'] ?? '' }}</h5>
                            <p class="text-muted small mb-0">{{ $gp['desc'] ?? '' }}</p>
                        </div>
                    </div>
                    @endforeach
                @endif
            </div>
        </div>
        @endif

        <!-- Section 4: Future Plans & Achievements -->
        <div class="row g-4">
            <!-- Future Plans -->
            <div class="col-lg-7">
                <div class="card h-100 border-0 shadow-sm rounded-4 p-4 p-lg-5 bg-white">
                    <span class="badge bg-primary-subtle text-primary fw-bold px-3 py-1 rounded-pill mb-2 w-max">ভবিষ্যৎ রূপরেখা</span>
                    <h4 class="fw-bold text-dark mb-3">প্রতিষ্ঠানের ভবিষ্যৎ পরিকল্পনা</h4>
                    <p class="text-muted leading-relaxed font-size-14 mb-4">
                        আগামী ৫ বছরে আধুনিক কৃষি, প্রপার্টি ও কোল্ড-চেইন সমবায়ের মাধ্যমে দেশব্যাপী ৬৪ জেলায় টেকসই অ্যাগ্রো-হাব স্থাপন এবং আন্তর্জাতিক মানে অর্গানিক খাদ্যপণ্য রপ্তানির মহাপরিকল্পনা গৃহীত হয়েছে।
                    </p>
                    <div class="row g-3">
                        <div class="col-12">
                            <div class="d-flex align-items-start gap-3 p-3 bg-light rounded-3">
                                <span class="badge bg-success rounded-circle p-2 mt-1"><i class="fa fa-check text-white"></i></span>
                                <div>
                                    <h6 class="fw-bold text-dark mb-1">আন্তর্জাতিক বাজারে খাদ্য রপ্তানি</h6>
                                    <p class="text-muted small mb-0">মধ্যপ্রাচ্য ও ইউরোপের বাজারে প্রক্রিয়াজাত শাকসবজি ও মসলা রপ্তানি হাব চালু।</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="d-flex align-items-start gap-3 p-3 bg-light rounded-3">
                                <span class="badge bg-primary rounded-circle p-2 mt-1"><i class="fa fa-check text-white"></i></span>
                                <div>
                                    <h6 class="fw-bold text-dark mb-1">১০০টি আধুনিক খামারবাড়ি ও রিসোর্ট</h6>
                                    <p class="text-muted small mb-0">সবুজ পাহাড়ি ও নদীসংলগ্ন অঞ্চলে যৌথ মালিকানায় আধুনিক এগ্রো-রিসোর্ট পল্লী নির্মাণ।</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="d-flex align-items-start gap-3 p-3 bg-light rounded-3">
                                <span class="badge bg-warning rounded-circle p-2 mt-1"><i class="fa fa-check text-dark"></i></span>
                                <div>
                                    <h6 class="fw-bold text-dark mb-1">স্মার্ট সৌরচালিত কোল্ড-স্টোরেজ নেটওয়ার্ক</h6>
                                    <p class="text-muted small mb-0">প্রত্যন্ত অঞ্চলের কৃষকের আলু, পেঁয়াজ ও টমেটো সংরক্ষণে স্বয়ংক্রিয় কোল্ড চেইন হাব।</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Achievements & Success -->
            <div class="col-lg-5">
                <div class="card h-100 border-0 shadow-sm rounded-4 p-4 p-lg-5 text-white" 
                     style="background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%);">
                    <span class="badge bg-warning text-dark fw-bold px-3 py-1 rounded-pill mb-2 w-max">সাফল্যের মাইলফলক</span>
                    <h4 class="fw-bold text-white mb-4">আমাদের অর্জন ও সাফল্য</h4>
                    <div class="row g-3 text-center">
                        <div class="col-6">
                            <div class="p-3 rounded-4" style="background: rgba(255,255,255,0.06); border: 1px solid rgba(255,255,255,0.1);">
                                <h3 class="fw-bold text-warning mb-1">৫,০০০+</h3>
                                <p class="text-light small mb-0">সক্রিয় সদস্য ও কৃষক</p>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="p-3 rounded-4" style="background: rgba(255,255,255,0.06); border: 1px solid rgba(255,255,255,0.1);">
                                <h3 class="fw-bold text-success mb-1">১৫+</h3>
                                <p class="text-light small mb-0">বাস্তবায়িত মেগা প্রজেক্ট</p>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="p-3 rounded-4" style="background: rgba(255,255,255,0.06); border: 1px solid rgba(255,255,255,0.1);">
                                <h3 class="fw-bold text-info mb-1">৯৮.৫%</h3>
                                <p class="text-light small mb-0">সন্তুষ্ট বিনিয়োগকারী</p>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="p-3 rounded-4" style="background: rgba(255,255,255,0.06); border: 1px solid rgba(255,255,255,0.1);">
                                <h3 class="fw-bold text-primary mb-1">১০০%</h3>
                                <p class="text-light small mb-0">নিয়মিত লভ্যাংশ বণ্টন রেকর্ড</p>
                            </div>
                        </div>
                    </div>

                    <div class="mt-4 pt-3 border-top border-secondary text-center">
                        <a href="{{ route('apply.card') }}" class="btn btn-warning text-dark rounded-pill px-4 fw-bold">
                            আমাদের সাথে যুক্ত হন <i class="fa fa-arrow-right ms-1"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>
@if(Auth::check())
        </section>
    </div>
</div>
@endif
@endsection

