@extends('layouts.frontend.app')

@section('content')
<!-- Floating Animated Toast Notification for Booking / Inquiries -->
<div id="propertyToastNotification" class="property-toast-wrap" style="display: none;">
    <div class="property-toast-box">
        <div class="toast-icon-wrap">
            <i class="fa fa-check-circle toast-check-icon"></i>
        </div>
        <div class="toast-content-wrap">
            <div class="toast-title">🎉 আবেদন সফলভাবে গৃহীত হয়েছে!</div>
            <div class="toast-message" id="toastMessageText">আমাদের কাস্টমার রিপ্রেজেন্টেটিভ খুব শীঘ্রই যোগাযোগ করবেন।</div>
            <div class="toast-tracking-badge" id="toastTrackingCode"></div>
        </div>
        <button type="button" class="toast-close-btn" onclick="hidePropertyToast()">
            <i class="fa fa-times"></i>
        </button>
        <div class="toast-progress-bar">
            <div class="toast-progress-fill" id="toastProgressFill"></div>
        </div>
    </div>
</div>

<!-- Hero Section for Properties -->
<div class="properties-hero-banner py-5" style="background: linear-gradient(135deg, #0f172a 0%, #1e293b 50%, #064e3b 100%); color: #ffffff; position: relative;">
    <div class="container py-4">
        <div class="row align-items-center">
            <div class="col-lg-8">
                <span class="badge bg-warning text-dark px-3 py-2 fw-bold text-uppercase mb-3 rounded-pill">
                    <i class="fa fa-shield me-1"></i> ১০০% নিষ্কণ্টক ও আইনি যাচাইকৃত প্রপার্টি
                </span>
                <h1 class="fw-extrabold display-5 mb-3 text-white">
                    জমি, প্লট ও প্রপার্টি ক্যাটালগ
                </h1>
                <p class="lead mb-4" style="color: #cbd5e1; font-size: 1.15rem;">
                    নিরাপদ ও লাভজনক রিয়েল এস্টেট বিনিয়োগ। যাচাইকৃত জমি, আবাসিক প্লট, ফ্ল্যাট ও বাণিজ্যিক স্পেস কিনুন অথবা আকর্ষণীয় কিস্তিতে বুকিং দিন।
                </p>
                <div class="d-flex flex-wrap gap-2 pt-2">
                    <div style="display: inline-flex; align-items: center; gap: 8px; background: rgba(255, 255, 255, 0.16) !important; border: 1.5px solid rgba(255, 255, 255, 0.35) !important; padding: 7px 18px; border-radius: 50px;">
                        <i class="fa fa-check-circle text-warning" style="font-size: 16px;"></i>
                        <span style="color: #ffffff !important; font-size: 14px; font-weight: 700;">খতিয়ান ও দাগ ভেরিফাইড</span>
                    </div>
                    <div style="display: inline-flex; align-items: center; gap: 8px; background: rgba(255, 255, 255, 0.16) !important; border: 1.5px solid rgba(255, 255, 255, 0.35) !important; padding: 7px 18px; border-radius: 50px;">
                        <i class="fa fa-check-circle text-warning" style="font-size: 16px;"></i>
                        <span style="color: #ffffff !important; font-size: 14px; font-weight: 700;">সহজ কিস্তি সুবিধা</span>
                    </div>
                    <div style="display: inline-flex; align-items: center; gap: 8px; background: rgba(255, 255, 255, 0.16) !important; border: 1.5px solid rgba(255, 255, 255, 0.35) !important; padding: 7px 18px; border-radius: 50px;">
                        <i class="fa fa-check-circle text-warning" style="font-size: 16px;"></i>
                        <span style="color: #ffffff !important; font-size: 14px; font-weight: 700;">দ্রুত সাফ-কবলা রেজিস্ট্রি</span>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 d-none d-lg-block text-end">
                <div class="bg-white p-4 rounded-4 shadow-lg text-dark text-start" style="border: 2px solid #f59e0b;">
                    <div class="small text-muted fw-bold text-uppercase mb-1">মোট উপলব্ধ প্রপার্টি</div>
                    <h2 class="fw-bold text-success mb-2">{{ $totalProperties }}+ টি সাইট</h2>
                    <p class="small text-muted mb-3">ঢাকা, পূর্বাচল, উত্তরা, মিরপুর ও সাভারে প্রস্তুত প্রপার্টি সরাসরি পরিদর্শন করুন।</p>
                    <a href="#propertyListSection" class="btn btn-warning w-100 fw-bold py-2">
                        <i class="fa fa-arrow-down me-1"></i> প্রপার্টি সমূহ দেখুন
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Search & Category Filter Navigation Bar -->
<div class="property-search-section py-3 bg-white border-bottom shadow-sm sticky-top" style="top: 74px; z-index: 99;" id="propertyListSection">
    <div class="container">
        <!-- Integrated Modern Search Bar Box -->
        <form action="{{ route('properties.index') }}" method="GET" class="property-search-form" id="propertySearchFilterForm">
            <!-- Hidden input to preserve category filter -->
            <input type="hidden" name="type" id="filterPropertyTypeInput" value="{{ request('type', 'all') }}">

            <div class="row g-2 align-items-center">
                <!-- Search Input with icon, clear button, and embedded submit -->
                <div class="col-lg-6 col-md-6 col-12">
                    <div class="property-search-input-wrap">
                        <i class="fa fa-search property-search-icon"></i>
                        <input type="text" 
                               name="q" 
                               id="propertySearchInput"
                               value="{{ request('q') }}" 
                               class="property-search-input" 
                               placeholder="প্রপার্টির নাম, এলাকা (ঢাকা/পূর্বাচল), দাগ বা খতিয়ান নম্বর লিখুন..." 
                               autocomplete="off">
                        @if(request('q'))
                            <a href="{{ route('properties.index', ['type' => request('type'), 'district' => request('district'), 'sort' => request('sort')]) }}" 
                               class="property-search-clear-btn" 
                               title="মুছে ফেলুন">
                                <i class="fa fa-times-circle"></i>
                            </a>
                        @endif
                        <button type="submit" class="property-search-submit-btn">
                            <i class="fa fa-search me-1"></i> <span>অনুসন্ধান</span>
                        </button>
                    </div>
                </div>

                <!-- District Dropdown -->
                <div class="col-lg-3 col-md-3 col-6">
                    <div class="property-filter-select-wrap">
                        <i class="fa fa-map-marker property-select-icon text-danger"></i>
                        <select name="district" class="property-filter-select" onchange="document.getElementById('propertySearchFilterForm').submit()">
                            <option value="all">সকল জেলা / এলাকা</option>
                            @foreach($districts as $dst)
                                <option value="{{ $dst }}" {{ request('district') == $dst ? 'selected' : '' }}>{{ $dst }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <!-- Sort Dropdown -->
                <div class="col-lg-3 col-md-3 col-6">
                    <div class="property-filter-select-wrap">
                        <i class="fa fa-sort-amount-desc property-select-icon text-success"></i>
                        <select name="sort" class="property-filter-select" onchange="document.getElementById('propertySearchFilterForm').submit()">
                            <option value="latest" {{ request('sort') == 'latest' ? 'selected' : '' }}>নতুন প্রপার্টি আগে</option>
                            <option value="price_asc" {{ request('sort') == 'price_asc' ? 'selected' : '' }}>মূল্য: কম থেকে বেশি</option>
                            <option value="price_desc" {{ request('sort') == 'price_desc' ? 'selected' : '' }}>মূল্য: বেশি থেকে কম</option>
                            <option value="area_desc" {{ request('sort') == 'area_desc' ? 'selected' : '' }}>আয়তন: বড় থেকে ছোট</option>
                        </select>
                    </div>
                </div>
            </div>

            <!-- Category Filter Pills Bar (Horizontal Scrollable) -->
            <div class="d-flex align-items-center justify-content-between mt-3 pt-2 border-top flex-wrap gap-2">
                <div class="d-flex align-items-center gap-2 overflow-auto py-1 property-category-scroll" style="white-space: nowrap;">
                    <button type="button" 
                            onclick="selectPropertyCategory('all')" 
                            class="property-cat-pill {{ !request('type') || request('type') == 'all' ? 'active' : '' }}">
                        <i class="fa fa-th-large me-1"></i> সব প্রপার্টি
                    </button>
                    <button type="button" 
                            onclick="selectPropertyCategory('প্লট')" 
                            class="property-cat-pill {{ request('type') == 'প্লট' ? 'active' : '' }}">
                        <i class="fa fa-map-signs me-1"></i> আবাসিক প্লট
                    </button>
                    <button type="button" 
                            onclick="selectPropertyCategory('জমি')" 
                            class="property-cat-pill {{ request('type') == 'জমি' ? 'active' : '' }}">
                        <i class="fa fa-tree me-1"></i> জমি ও কৃষি খামার
                    </button>
                    <button type="button" 
                            onclick="selectPropertyCategory('ফ্ল্যাট')" 
                            class="property-cat-pill {{ request('type') == 'ফ্ল্যাট' ? 'active' : '' }}">
                        <i class="fa fa-building-o me-1"></i> ফ্ল্যাট / অ্যাপার্টমেন্ট
                    </button>
                    <button type="button" 
                            onclick="selectPropertyCategory('বাণিজ্যিক')" 
                            class="property-cat-pill {{ request('type') == 'বাণিজ্যিক' ? 'active' : '' }}">
                        <i class="fa fa-briefcase me-1"></i> বাণিজ্যিক স্পেস
                    </button>
                </div>

                @if(request('q') || (request('type') && request('type') !== 'all') || (request('district') && request('district') !== 'all') || (request('sort') && request('sort') !== 'latest'))
                    <a href="{{ route('properties.index') }}" class="btn btn-sm btn-outline-danger rounded-pill px-3 py-1 fw-bold">
                        <i class="fa fa-refresh me-1"></i> ফিল্টার রিসেট
                    </a>
                @endif
            </div>
        </form>
    </div>
</div>

<!-- Main Property Listing Section -->
<div class="py-5" style="background-color: #f8fafc; min-height: 600px;">
    <div class="container">
        <div class="d-flex align-items-center justify-content-between mb-4">
            <div>
                <h4 class="fw-bold text-dark mb-1">উপলব্ধ জমি ও প্রপার্টি তালিকা</h4>
                <p class="text-muted small mb-0">সর্বমোট {{ $properties->total() }} টি সাইট প্রদর্শিত হচ্ছে</p>
            </div>
            @if(request('q') || request('type'))
                <a href="{{ route('properties.index') }}" class="btn btn-sm btn-outline-danger">
                    <i class="fa fa-times me-1"></i> ফিল্টার মুছুন
                </a>
            @endif
        </div>

        <div class="row g-4">
            @forelse($properties as $item)
                <div class="col-lg-4 col-md-6 col-12">
                    <div class="card h-100 border-0 rounded-4 shadow-sm overflow-hidden property-card-hover" style="transition: transform 0.25s ease, box-shadow 0.25s ease;">
                        <!-- Property Image with Badges -->
                        <div class="position-relative" style="height: 220px; overflow: hidden; background: #e2e8f0;">
                            <img src="{{ $item->featured_image ?: 'https://images.unsplash.com/photo-1500382017468-9049fed747ef?w=800&auto=format&fit=crop&q=60' }}" 
                                 alt="{{ $item->property_name }}" 
                                 class="w-100 h-100" 
                                 style="object-fit: cover; transition: transform 0.4s ease;">
                            
                            <!-- Badges -->
                            <div class="position-absolute top-0 start-0 m-3 d-flex flex-column gap-1">
                                <span class="badge px-2 py-1 rounded" style="background: rgba(15, 23, 42, 0.85) !important; color: #ffffff !important;">
                                    {{ $item->property_type_name }}
                                </span>
                                @if($item->is_featured)
                                    <span class="badge bg-warning text-dark px-2 py-1 rounded fw-bold">
                                        <i class="fa fa-star me-1"></i> স্পেশাল অফার
                                    </span>
                                @endif
                            </div>

                            <div class="position-absolute top-0 end-0 m-3">
                                @if($item->status === 'available')
                                    <span class="badge bg-success text-white px-2 py-1 rounded-pill shadow-sm">
                                        বিক্রয়যোগ্য
                                    </span>
                                @elseif($item->status === 'reserved')
                                    <span class="badge bg-warning text-dark px-2 py-1 rounded-pill shadow-sm">
                                        রিজার্ভড
                                    </span>
                                @endif
                            </div>

                            <!-- Area Badge at Bottom-Right of Image -->
                            <div class="position-absolute bottom-0 end-0 m-3 px-3 py-1 rounded-3 small fw-bold" style="background: rgba(15, 23, 42, 0.85) !important; color: #ffffff !important;">
                                <i class="fa fa-expand me-1 text-warning"></i> {{ number_format($item->total_area, 2) }} {{ $item->measurement_unit }}
                            </div>
                        </div>

                        <!-- Card Body -->
                        <div class="card-body p-4 d-flex flex-column">
                            <div class="small text-muted font-monospace mb-1">{{ $item->property_code }}</div>
                            <h5 class="fw-bold text-dark mb-2" style="font-size: 1.15rem; line-height: 1.4;">
                                <a href="{{ route('properties.show', $item->id) }}" class="text-dark text-decoration-none hover-primary">
                                    {{ $item->property_name }}
                                </a>
                            </h5>

                            <!-- Location Info -->
                            <div class="d-flex align-items-center text-muted small mb-3">
                                <i class="fa fa-map-marker text-danger me-2 fs-6"></i>
                                <span>{{ $item->upazila ? $item->upazila . ', ' : '' }}{{ $item->district }}</span>
                                @if($item->dag_number)
                                    <span class="mx-1">•</span>
                                    <span>দাগ: {{ $item->dag_number }}</span>
                                @endif
                            </div>

                            <!-- Highlights / Description -->
                            @if($item->features)
                                <div class="bg-light p-2 rounded-3 mb-3 small text-slate-700 text-truncate">
                                    <i class="fa fa-check text-success me-1"></i> {{ $item->features }}
                                </div>
                            @elseif($item->description)
                                <p class="text-muted small mb-3 text-truncate-2" style="display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden;">
                                    {{ $item->description }}
                                </p>
                            @endif

                            <div class="mt-auto pt-3 border-top">
                                <!-- Price Row -->
                                <div class="d-flex align-items-end justify-content-between mb-3">
                                    <div>
                                        <div class="small text-muted">সর্বমোট বিক্রয়মূল্য:</div>
                                        <div class="fw-bold font-monospace text-success fs-5">
                                            ৳ {{ number_format($item->selling_price) }}
                                        </div>
                                    </div>
                                    @if($item->booking_amount > 0)
                                        <div class="text-end">
                                            <div class="small text-muted">বুকিং ডিপোজিট:</div>
                                            <div class="fw-bold font-monospace text-primary">
                                                ৳ {{ number_format($item->booking_amount) }}
                                            </div>
                                        </div>
                                    @endif
                                </div>

                                <!-- Action Buttons -->
                                <div class="row g-2">
                                    <div class="col-6">
                                        <a href="{{ route('properties.show', $item->id) }}" class="btn btn-outline-dark btn-sm w-100 py-2 fw-bold">
                                            <i class="fa fa-eye me-1"></i> বিস্তারিত
                                        </a>
                                    </div>
                                    <div class="col-6">
                                        <button type="button" 
                                                class="btn btn-success btn-sm w-100 py-2 fw-bold"
                                                onclick="openBookingModal('{{ $item->id }}', '{{ addslashes($item->property_name) }}', '{{ number_format($item->selling_price) }}')">
                                            <i class="fa fa-paper-plane me-1"></i> বুকিং / সাইট
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12 py-5 text-center">
                    <div class="p-5 bg-white rounded-4 shadow-sm">
                        <i class="fa fa-map-o fa-4x text-muted mb-3 opacity-50"></i>
                        <h4 class="fw-bold text-dark">কোনো প্রপার্টি খুঁজে পাওয়া যায়নি!</h4>
                        <p class="text-muted">আপনার খোঁজের সাথে মিল রেখে বর্তমানে কোনো প্রপার্টি নেই। অনুগ্রহ করে অন্য ফিল্টার চেষ্টা করুন।</p>
                        <a href="{{ route('properties.index') }}" class="btn btn-success fw-bold px-4">সকল প্রপার্টি দেখুন</a>
                    </div>
                </div>
            @endforelse
        </div>

        <!-- Pagination -->
        @if($properties->hasPages())
            <div class="d-flex justify-content-center mt-5">
                {{ $properties->links() }}
            </div>
        @endif
    </div>
</div>

<!-- Customer Site Visit / Booking Modal -->
<div class="modal fade" id="customerInquiryModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg rounded-4">
            <div class="modal-header bg-success text-white py-3 px-4">
                <h5 class="modal-title fw-bold mb-0">
                    <i class="fa fa-calendar-check-o me-2"></i> সাইট পরিদর্শন বা বুকিং আবেদন
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="propertyBookingModalForm" action="{{ route('properties.inquiry') }}" method="POST" onsubmit="handleAjaxBookingSubmit(event, this)">
                @csrf
                <input type="hidden" name="property_id" id="modalPropertyId">
                <div class="modal-body p-4">
                    <div class="alert alert-light border mb-3 p-3 rounded-3">
                        <div class="small text-muted">নির্বাচিত প্রপার্টি:</div>
                        <div class="fw-bold text-dark" id="modalPropertyName">প্রপার্টি লোড হচ্ছে...</div>
                        <div class="small text-success fw-bold mt-1" id="modalPropertyPrice">মূল্য: ৳ ০</div>
                    </div>

                    <div class="row g-3">
                        <div class="col-12">
                            <label class="form-label small fw-bold"><i class="fa fa-user text-success me-1"></i> আপনার নাম <span class="text-danger">*</span></label>
                            <input type="text" name="customer_name" required class="form-control rounded-3" value="{{ Auth::check() ? Auth::user()->name : '' }}" placeholder="আপনার পূর্ণ নাম লিখুন">
                        </div>
                        <div class="col-12">
                            <label class="form-label small fw-bold"><i class="fa fa-phone text-success me-1"></i> মোবাইল নম্বর <span class="text-danger">*</span></label>
                            <input type="text" name="customer_phone" required class="form-control rounded-3" value="{{ Auth::check() ? Auth::user()->phone : '' }}" placeholder="০১xxxxxxxxx">
                        </div>
                        <div class="col-12">
                            <label class="form-label small fw-bold"><i class="fa fa-envelope-o text-success me-1"></i> ইমেইল (যদি থাকে)</label>
                            <input type="email" name="customer_email" class="form-control rounded-3" value="{{ Auth::check() ? Auth::user()->email : '' }}" placeholder="email@example.com">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-bold"><i class="fa fa-tags text-success me-1"></i> আবেদনের ধরন <span class="text-danger">*</span></label>
                            <select name="inquiry_type" class="form-select rounded-3" required>
                                <option value="site_visit" selected>📍 সাইট পরিদর্শন (Site Visit)</option>
                                <option value="booking_request">📝 সরাসরি বুকিং আবেদন (Booking)</option>
                                <option value="price_inquiry">💬 বিস্তারিত ও মূল্য আলোচনা (Inquiry)</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-bold"><i class="fa fa-calendar text-success me-1"></i> পছন্দের তারিখ</label>
                            <input type="date" name="preferred_date" class="form-control rounded-3" min="{{ date('Y-m-d') }}">
                        </div>
                        <div class="col-12">
                            <label class="form-label small fw-bold"><i class="fa fa-commenting-o text-success me-1"></i> আপনার কোনো প্রশ্ন বা বিশেষ নোট</label>
                            <textarea name="message" rows="2" class="form-control rounded-3" placeholder="যেমন: শুক্রবারে সাইট ভিজিট করতে চাই অথবা কিস্তি সুবিধা জানতে চাই..."></textarea>
                        </div>
                    </div>
                </div>
                <div class="modal-footer bg-light py-3 px-4">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">বাতিল</button>
                    <button type="submit" id="modalSubmitBtn" class="btn btn-success fw-bold px-4">
                        <i class="fa fa-send me-1"></i> আবেদন সাবমিট করুন
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
var toastTimer = null;

function showPropertyToast(message, trackingCode) {
    var toastWrap = document.getElementById('propertyToastNotification');
    var msgEl = document.getElementById('toastMessageText');
    var codeEl = document.getElementById('toastTrackingCode');
    var progressEl = document.getElementById('toastProgressFill');

    if (!toastWrap || !msgEl) return;

    msgEl.innerText = message || 'আপনার আবেদনটি সফলভাবে গৃহীত হয়েছে। আমাদের প্রতিনিধি দ্রুত যোগাযোগ করবেন।';
    if (codeEl) {
        if (trackingCode) {
            codeEl.innerText = 'ট্র্যাকিং কোড: ' + trackingCode;
            codeEl.style.display = 'inline-block';
        } else {
            codeEl.style.display = 'none';
        }
    }

    if (progressEl) {
        progressEl.style.transition = 'none';
        progressEl.style.width = '100%';
        setTimeout(function() {
            progressEl.style.transition = 'width 5s linear';
            progressEl.style.width = '0%';
        }, 50);
    }

    toastWrap.style.display = 'block';
    toastWrap.style.animation = 'toastSlideIn 0.4s cubic-bezier(0.16, 1, 0.3, 1) forwards';

    if (toastTimer) clearTimeout(toastTimer);
    toastTimer = setTimeout(function() {
        hidePropertyToast();
    }, 5200);
}

function hidePropertyToast() {
    var toastWrap = document.getElementById('propertyToastNotification');
    if (toastWrap) {
        toastWrap.style.animation = 'toastSlideOut 0.4s cubic-bezier(0.16, 1, 0.3, 1) forwards';
        setTimeout(function() {
            toastWrap.style.display = 'none';
        }, 400);
    }
}

function openBookingModal(id, name, price) {
    document.getElementById('modalPropertyId').value = id;
    document.getElementById('modalPropertyName').innerText = name;
    document.getElementById('modalPropertyPrice').innerText = 'মূল্য: ৳ ' + price;
    var modal = new bootstrap.Modal(document.getElementById('customerInquiryModal'));
    modal.show();
}

function handleAjaxBookingSubmit(e, form) {
    e.preventDefault();
    var btn = form.querySelector('button[type="submit"]');
    var originalBtnHtml = btn ? btn.innerHTML : '';
    if (btn) {
        btn.disabled = true;
        btn.innerHTML = '<i class="fa fa-spinner fa-spin me-1"></i> আবেদন পাঠানো হচ্ছে...';
    }

    var formData = new FormData(form);

    fetch(form.action, {
        method: 'POST',
        body: formData,
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(function(res) {
        return res.json();
    })
    .then(function(data) {
        if (btn) {
            btn.disabled = false;
            btn.innerHTML = originalBtnHtml;
        }

        // Close modal if open
        var modalEl = document.getElementById('customerInquiryModal');
        if (modalEl) {
            var modalInstance = bootstrap.Modal.getInstance(modalEl);
            if (modalInstance) modalInstance.hide();
        }

        form.reset();

        // Trigger Toaster Notification
        var msg = data.message || 'আপনার আবেদনটি সফলভাবে গৃহীত হয়েছে!';
        var code = data.inquiry_code || (data.inquiry ? data.inquiry.inquiry_code : '');
        showPropertyToast(msg, code);
    })
    .catch(function(err) {
        if (btn) {
            btn.disabled = false;
            btn.innerHTML = originalBtnHtml;
        }
        form.submit(); // fallback to standard submit
    });
}

function selectPropertyCategory(cat) {
    var input = document.getElementById('filterPropertyTypeInput');
    if (input) {
        input.value = cat;
    }
    var form = document.getElementById('propertySearchFilterForm');
    if (form) {
        form.submit();
    }
}

document.addEventListener('DOMContentLoaded', function() {
    @if(session('success'))
        showPropertyToast("{{ session('success') }}", '');
    @endif
});
</script>

<style>
/* Modern Unified Real Estate Search & Filter Styles */
.property-search-section {
    background: #ffffff !important;
    border-bottom: 1px solid #e2e8f0;
}
.property-search-input-wrap {
    display: flex;
    align-items: center;
    background: #f8fafc;
    border: 2px solid #e2e8f0;
    border-radius: 50px;
    padding: 4px 6px 4px 18px;
    transition: all 0.25s ease;
}
.property-search-input-wrap:focus-within {
    background: #ffffff;
    border-color: #16a34a;
    box-shadow: 0 0 0 4px rgba(22, 163, 74, 0.15);
}
.property-search-icon {
    color: #64748b;
    font-size: 16px;
    margin-right: 10px;
    flex-shrink: 0;
}
.property-search-input {
    border: none;
    outline: none;
    background: transparent;
    width: 100%;
    font-size: 14.5px;
    color: #0f172a;
    font-weight: 500;
}
.property-search-input::placeholder {
    color: #94a3b8;
    font-size: 13.5px;
}
.property-search-clear-btn {
    color: #94a3b8;
    background: none;
    border: none;
    padding: 0 10px;
    cursor: pointer;
    font-size: 16px;
    line-height: 1;
    text-decoration: none;
}
.property-search-clear-btn:hover {
    color: #ef4444;
}
.property-search-submit-btn {
    background: linear-gradient(135deg, #16a34a 0%, #15803d 100%);
    color: #ffffff;
    border: none;
    padding: 8px 22px;
    border-radius: 50px;
    font-size: 14px;
    font-weight: 700;
    cursor: pointer;
    box-shadow: 0 2px 8px rgba(22, 163, 74, 0.3);
    white-space: nowrap;
    display: inline-flex;
    align-items: center;
    transition: all 0.2s ease;
}
.property-search-submit-btn:hover {
    background: linear-gradient(135deg, #15803d 0%, #166534 100%);
    transform: translateY(-1px);
    box-shadow: 0 4px 14px rgba(22, 163, 74, 0.4);
}
.property-filter-select-wrap {
    display: flex;
    align-items: center;
    background: #f8fafc;
    border: 1.5px solid #e2e8f0;
    border-radius: 50px;
    padding: 7px 14px;
    transition: all 0.25s ease;
}
.property-filter-select-wrap:focus-within {
    background: #ffffff;
    border-color: #16a34a;
    box-shadow: 0 0 0 3px rgba(22, 163, 74, 0.12);
}
.property-select-icon {
    font-size: 15px;
    margin-right: 8px;
    flex-shrink: 0;
}
.property-filter-select {
    border: none;
    outline: none;
    background: transparent;
    width: 100%;
    font-size: 13.5px;
    color: #1e293b;
    font-weight: 600;
    cursor: pointer;
}
.property-category-scroll::-webkit-scrollbar {
    height: 4px;
}
.property-category-scroll::-webkit-scrollbar-thumb {
    background: #cbd5e1;
    border-radius: 4px;
}
.property-cat-pill {
    background: #f1f5f9;
    color: #334155;
    border: 1.5px solid #e2e8f0;
    padding: 7px 18px;
    border-radius: 50px;
    font-size: 13.5px;
    font-weight: 700;
    cursor: pointer;
    display: inline-flex;
    align-items: center;
    transition: all 0.2s ease;
}
.property-cat-pill:hover {
    background: #e2e8f0;
    color: #0f172a;
    border-color: #cbd5e1;
}
.property-cat-pill.active {
    background: linear-gradient(135deg, #16a34a 0%, #15803d 100%) !important;
    color: #ffffff !important;
    border-color: #15803d !important;
    box-shadow: 0 3px 10px rgba(22, 163, 74, 0.35);
}
.property-toast-wrap {
    position: fixed;
    top: 95px;
    right: 25px;
    z-index: 9999999;
    max-width: 440px;
    width: calc(100% - 40px);
    pointer-events: none;
}
.property-toast-box {
    pointer-events: auto;
    background: #ffffff;
    border-radius: 16px;
    box-shadow: 0 16px 45px rgba(0, 0, 0, 0.2), 0 2px 10px rgba(0, 0, 0, 0.08);
    border: 1.5px solid #86efac;
    border-left: 6px solid #16a34a;
    padding: 16px 20px 20px 18px;
    display: flex;
    align-items: flex-start;
    gap: 14px;
    position: relative;
    overflow: hidden;
}
.toast-icon-wrap {
    flex-shrink: 0;
    width: 40px;
    height: 40px;
    border-radius: 50%;
    background: #dcfce7;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #16a34a;
    font-size: 22px;
}
.toast-content-wrap {
    flex-grow: 1;
}
.toast-title {
    font-size: 15px;
    font-weight: 800;
    color: #0f172a;
    margin-bottom: 4px;
}
.toast-message {
    font-size: 13px;
    color: #334155;
    line-height: 1.45;
    margin-bottom: 6px;
}
.toast-tracking-badge {
    display: inline-block;
    background: #f1f5f9;
    color: #0f172a;
    font-family: monospace;
    font-size: 12px;
    font-weight: 700;
    padding: 2px 8px;
    border-radius: 6px;
    border: 1px solid #cbd5e1;
}
.toast-close-btn {
    background: transparent;
    border: none;
    color: #94a3b8;
    cursor: pointer;
    font-size: 16px;
    padding: 0;
    margin-left: 6px;
    line-height: 1;
}
.toast-close-btn:hover {
    color: #0f172a;
}
.toast-progress-bar {
    position: absolute;
    bottom: 0;
    left: 0;
    right: 0;
    height: 4px;
    background: #f1f5f9;
}
.toast-progress-fill {
    height: 100%;
    background: #16a34a;
    width: 100%;
}
@keyframes toastSlideIn {
    from {
        transform: translateX(110%);
        opacity: 0;
    }
    to {
        transform: translateX(0);
        opacity: 1;
    }
}
@keyframes toastSlideOut {
    from {
        transform: translateX(0);
        opacity: 1;
    }
    to {
        transform: translateX(110%);
        opacity: 0;
    }
}
.property-card-hover:hover {
    transform: translateY(-5px);
    box-shadow: 0 12px 25px rgba(0,0,0,0.12) !important;
}
.hover-primary:hover {
    color: #16a34a !important;
}
</style>
@endsection
