@extends('layouts.frontend.app')

@section('content')
<div class="py-4 bg-light border-bottom">
    <div class="container">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="{{ route('/') }}" class="text-decoration-none">হোম</a></li>
                <li class="breadcrumb-item"><a href="{{ route('properties.index') }}" class="text-decoration-none">জমি ও প্রপার্টি</a></li>
                <li class="breadcrumb-item active" aria-current="page">{{ $property->property_name }}</li>
            </ol>
        </nav>
    </div>
</div>

<div class="py-5" style="background-color: #f8fafc;">
    <div class="container">
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show rounded-4 shadow-sm mb-4" role="alert">
                <i class="fa fa-check-circle me-2"></i> {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <div class="row g-4">
            <!-- Left Column: Media & Specifications -->
            <div class="col-lg-8">
                <div class="bg-white rounded-4 p-4 shadow-sm mb-4">
                    <!-- Featured Image -->
                    <div class="rounded-4 overflow-hidden position-relative mb-4" style="max-height: 460px;">
                        <img src="{{ $property->featured_image ?: 'https://images.unsplash.com/photo-1500382017468-9049fed747ef?w=800&auto=format&fit=crop&q=60' }}" 
                             alt="{{ $property->property_name }}" 
                             class="w-100 h-100" 
                             style="object-fit: cover;">
                        <span class="position-absolute top-0 start-0 m-3 badge bg-dark px-3 py-2 fs-6">
                            {{ $property->property_type_name }}
                        </span>
                        <span class="position-absolute top-0 end-0 m-3 badge bg-success px-3 py-2 fs-6">
                            {{ ucfirst($property->status) }}
                        </span>
                    </div>

                    <!-- Title & Code -->
                    <div class="d-flex align-items-center justify-content-between flex-wrap gap-2 mb-2">
                        <span class="badge bg-light text-muted border font-monospace">{{ $property->property_code }}</span>
                        <span class="text-muted small"><i class="fa fa-calendar me-1"></i> তালিকাভুক্ত: {{ $property->created_at->format('d M, Y') }}</span>
                    </div>
                    <h2 class="fw-bold text-dark mb-3">{{ $property->property_name }}</h2>
                    <p class="text-muted fs-6 mb-4">
                        <i class="fa fa-map-marker text-danger me-2"></i>
                        {{ $property->mouza ? 'মৌজা: ' . $property->mouza . ', ' : '' }}
                        {{ $property->upazila ? $property->upazila . ', ' : '' }}
                        {{ $property->district }}, {{ $property->division }}
                    </p>

                    <!-- Key Land / Property Records Grid -->
                    <h5 class="fw-bold text-dark mb-3 border-bottom pb-2">
                        <i class="fa fa-file-text-o text-success me-2"></i> জমি ও দলিলের আইনি রেকর্ডসমূহ
                    </h5>
                    <div class="row g-3 mb-4">
                        <div class="col-md-3 col-6">
                            <div class="bg-light p-3 rounded-3 text-center border">
                                <small class="text-muted d-block">মোট আয়তন</small>
                                <span class="fw-bold fs-6 text-dark">{{ number_format($property->total_area, 2) }} {{ $property->measurement_unit }}</span>
                            </div>
                        </div>
                        <div class="col-md-3 col-6">
                            <div class="bg-light p-3 rounded-3 text-center border">
                                <small class="text-muted d-block">দাগ নম্বর</small>
                                <span class="fw-bold fs-6 text-dark">{{ $property->dag_number ?: 'যাচাইকৃত' }}</span>
                            </div>
                        </div>
                        <div class="col-md-3 col-6">
                            <div class="bg-light p-3 rounded-3 text-center border">
                                <small class="text-muted d-block">খতিয়ান নম্বর</small>
                                <span class="fw-bold fs-6 text-dark">{{ $property->khatian_number ?: 'নামজারি রেডি' }}</span>
                            </div>
                        </div>
                        <div class="col-md-3 col-6">
                            <div class="bg-light p-3 rounded-3 text-center border">
                                <small class="text-muted d-block">জেএল / মৌজা</small>
                                <span class="fw-bold fs-6 text-dark">{{ $property->jl_number ?: ($property->mouza ?: 'অনুমোদিত') }}</span>
                            </div>
                        </div>
                    </div>

                    <!-- Description -->
                    <h5 class="fw-bold text-dark mb-3 border-bottom pb-2">
                        <i class="fa fa-align-left text-success me-2"></i> বিস্তারিত বিবরণ
                    </h5>
                    <p class="text-slate-700 lead mb-4" style="line-height: 1.8; font-size: 1.05rem;">
                        {{ $property->description ?: 'প্রপার্টিটি ১০০% ভেটিং ও লিগ্যাল ক্লিয়ারেন্স সম্পন্ন। বিদ্যুৎ, রাস্তা ও ইউটিলিটি সংযোগ বিদ্যমান। সাইট পরিদর্শনের জন্য বুকিং আবেদন করুন।' }}
                    </p>

                    <!-- Amenities & Features -->
                    @if($property->features)
                        <h5 class="fw-bold text-dark mb-3 border-bottom pb-2">
                            <i class="fa fa-check-square-o text-success me-2"></i> সুযোগ-সুবিধা ও বৈশিষ্ট্য
                        </h5>
                        <div class="d-flex flex-wrap gap-2 mb-4">
                            @foreach(explode(',', $property->features) as $feature)
                                <span class="badge bg-success bg-opacity-10 text-success border border-success border-opacity-25 px-3 py-2 rounded-pill">
                                    <i class="fa fa-check-circle me-1"></i> {{ trim($feature) }}
                                </span>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>

            <!-- Right Column: Pricing & Booking Application Card -->
            <div class="col-lg-4">
                <div class="bg-white rounded-4 p-4 shadow-sm sticky-top" style="top: 90px; border: 2px solid #10b981;">
                    <div class="text-center pb-3 border-bottom mb-3">
                        <div class="small text-muted fw-bold text-uppercase">নির্ধারিত বিক্রয় মূল্য</div>
                        <h2 class="fw-extrabold text-success font-monospace mb-1">
                            ৳ {{ number_format($property->selling_price) }}
                        </h2>
                        @if($property->booking_amount > 0)
                            <span class="badge bg-warning text-dark px-3 py-1 font-monospace">
                                বুকিং মানি: ৳ {{ number_format($property->booking_amount) }}
                            </span>
                        @endif
                    </div>

                    <!-- Direct Inquiry / Booking Form -->
                    <h5 class="fw-bold text-dark mb-3">
                        <i class="fa fa-send text-primary me-2"></i> সরাসরি বুকিং বা সাইট ভিজিট আবেদন
                    </h5>
                    <form action="{{ route('properties.inquiry') }}" method="POST">
                        @csrf
                        <input type="hidden" name="property_id" value="{{ $property->id }}">

                        <div class="mb-3">
                            <label class="form-label small fw-bold">আপনার নাম <span class="text-danger">*</span></label>
                            <input type="text" name="customer_name" required class="form-control" value="{{ Auth::check() ? Auth::user()->name : '' }}" placeholder="আপনার নাম লিখুন">
                        </div>

                        <div class="mb-3">
                            <label class="form-label small fw-bold">মোবাইল নম্বর <span class="text-danger">*</span></label>
                            <input type="text" name="customer_phone" required class="form-control" value="{{ Auth::check() ? Auth::user()->phone : '' }}" placeholder="০১xxxxxxxxx">
                        </div>

                        <div class="mb-3">
                            <label class="form-label small fw-bold">আবেদনের ধরন <span class="text-danger">*</span></label>
                            <select name="inquiry_type" class="form-select" required>
                                <option value="site_visit" selected>সাইট পরিদর্শন (Site Visit)</option>
                                <option value="booking_request">সরাসরি বুকিং আবেদন (Booking)</option>
                                <option value="price_inquiry">মূল্য ও কিস্তি আলোচনা</option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label small fw-bold">পরিদর্শনের পছন্দের তারিখ</label>
                            <input type="date" name="preferred_date" class="form-control" min="{{ date('Y-m-d') }}">
                        </div>

                        <div class="mb-3">
                            <label class="form-label small fw-bold">নোট / বার্তা</label>
                            <textarea name="message" rows="2" class="form-control" placeholder="আপনার কোনো নির্দিষ্ট জিজ্ঞাসা থাকলে লিখুন..."></textarea>
                        </div>

                        <button type="submit" class="btn btn-success w-100 py-2 fw-bold shadow">
                            <i class="fa fa-check me-1"></i> আবেদন সাবমিট করুন
                        </button>
                    </form>

                    <div class="mt-4 pt-3 border-top text-center">
                        <small class="text-muted d-block mb-1">জরুরী প্রয়োজনে হেল্পলাইন:</small>
                        <a href="tel:01700000000" class="fw-bold text-dark text-decoration-none">
                            <i class="fa fa-phone text-warning me-1"></i> হটলাইন: ০১৭০০-০০০০০০
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
