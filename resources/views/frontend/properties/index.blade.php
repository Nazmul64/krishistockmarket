@extends('layouts.frontend.app')

@section('content')
@php
    $isBn = $isBn ?? (request()->cookie('ekrishi_lang', 'en') === 'bn');
@endphp
<!-- Floating Animated Toast Notification for Booking / Inquiries -->
<div id="propertyToastNotification" class="property-toast-wrap" style="display: none;">
    <div class="property-toast-box">
        <div class="toast-icon-wrap">
            <i class="fa fa-check-circle toast-check-icon"></i>
        </div>
        <div class="toast-content-wrap">
            <div class="toast-title">🎉 {{ $isBn ? 'আবেদন সফলভাবে গৃহীত হয়েছে!' : 'Application Successfully Submitted!' }}</div>
            <div class="toast-message" id="toastMessageText">{{ $isBn ? 'আমাদের কাস্টমার রিপ্রেজেন্টেটিভ খুব শীঘ্রই যোগাযোগ করবেন।' : 'Our customer representative will reach out to you shortly.' }}</div>
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
                    <i class="fa fa-shield me-1"></i> {{ $isBn ? '১০০% নিষ্কণ্টক ও আইনি যাচাইকৃত প্রপার্টি' : '100% Verified & Legal Clearance Properties' }}
                </span>
                <h1 class="fw-extrabold display-5 mb-3 text-white">
                    {{ $isBn ? 'জমি, প্লট ও প্রপার্টি ক্যাটালগ' : 'Land, Plots & Properties Catalog' }}
                </h1>
                <p class="lead mb-4" style="color: #cbd5e1; font-size: 1.15rem;">
                    {{ $isBn ? 'নিরাপদ ও লাভজনক রিয়েল এস্টেট বিনিয়োগ। যাচাইকৃত জমি, আবাসিক প্লট, ফ্ল্যাট ও বাণিজ্যিক স্পেস কিনুন অথবা আকর্ষণীয় কিস্তিতে বুকিং দিন।' : 'Secure & profitable real estate investment. Buy verified land, residential plots, flats, and commercial spaces or book with easy installments.' }}
                </p>
                <div class="d-flex flex-wrap gap-2 pt-2">
                    <div style="display: inline-flex; align-items: center; gap: 8px; background: rgba(255, 255, 255, 0.16) !important; border: 1.5px solid rgba(255, 255, 255, 0.35) !important; padding: 7px 18px; border-radius: 50px;">
                        <i class="fa fa-check-circle text-warning" style="font-size: 16px;"></i>
                        <span style="color: #ffffff !important; font-size: 14px; font-weight: 700;">{{ $isBn ? 'খতিয়ান ও দাগ ভেরিফাইড' : 'Khatian & Dag Verified' }}</span>
                    </div>
                    <div style="display: inline-flex; align-items: center; gap: 8px; background: rgba(255, 255, 255, 0.16) !important; border: 1.5px solid rgba(255, 255, 255, 0.35) !important; padding: 7px 18px; border-radius: 50px;">
                        <i class="fa fa-check-circle text-warning" style="font-size: 16px;"></i>
                        <span style="color: #ffffff !important; font-size: 14px; font-weight: 700;">{{ $isBn ? 'সহজ কিস্তি সুবিধা' : 'Easy Installment Plans' }}</span>
                    </div>
                    <div style="display: inline-flex; align-items: center; gap: 8px; background: rgba(255, 255, 255, 0.16) !important; border: 1.5px solid rgba(255, 255, 255, 0.35) !important; padding: 7px 18px; border-radius: 50px;">
                        <i class="fa fa-check-circle text-warning" style="font-size: 16px;"></i>
                        <span style="color: #ffffff !important; font-size: 14px; font-weight: 700;">{{ $isBn ? 'দ্রুত সাফ-কবলা রেজিস্ট্রি' : 'Fast Registry & Mutation' }}</span>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 d-none d-lg-block text-end">
                <div class="property-stat-box bg-white p-4 rounded-4 shadow-lg text-dark text-start" style="border: 2px solid #f59e0b;">
                    <div class="small text-muted fw-bold text-uppercase mb-1">{{ $isBn ? 'মোট উপলব্ধ প্রপার্টি' : 'Total Available Properties' }}</div>
                    <h2 class="fw-bold text-success mb-2">{{ $totalProperties }}+ {{ $isBn ? 'টি সাইট' : 'Sites' }}</h2>
                    <p class="small text-muted mb-3">{{ $isBn ? 'ঢাকা, পূর্বাচল, উত্তরা, মিরপুর ও সাভারে প্রস্তুত প্রপার্টি সরাসরি পরিদর্শন করুন।' : 'Inspect ready verified properties across Dhaka, Purbachal, Uttara, Mirpur, and Savar.' }}</p>
                    <a href="#propertyListSection" class="btn btn-warning w-100 fw-bold py-2">
                        <i class="fa fa-arrow-down me-1"></i> {{ $isBn ? 'প্রপার্টি সমূহ দেখুন' : 'Explore Properties' }}
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
        <form action="{{ route('properties.index') }}" method="GET" class="property-search-form" id="propertySearchFilterForm" onsubmit="handleAjaxSearchSubmit(event)">
            <!-- Hidden input to preserve category filter -->
            <input type="hidden" name="type" id="filterPropertyTypeInput" value="{{ request('type', 'all') }}">

            <div class="row g-2 align-items-center">
                <!-- Search Input with icon, clear button, and embedded submit -->
                <div class="col-lg-6 col-md-6 col-12">
                    <div class="property-search-input-wrap">
                        <i class="fa fa-search property-search-icon" id="propertySearchIcon"></i>
                        <input type="text" 
                               name="q" 
                               id="propertySearchInput"
                               value="{{ request('q') }}" 
                               class="property-search-input" 
                               placeholder="{{ $isBn ? 'প্রপার্টির নাম, এলাকা (ঢাকা/পূর্বাচল), দাগ বা খতিয়ান নম্বর লিখুন...' : 'Search by property name, location, dag or khatian no...' }}" 
                               autocomplete="off">
                        <button type="button" 
                                id="propertySearchClearBtn" 
                                class="property-search-clear-btn" 
                                style="{{ request('q') ? 'display: block;' : 'display: none;' }}" 
                                onclick="clearPropertySearch()" 
                                title="{{ $isBn ? 'মুছে ফেলুন' : 'Clear' }}">
                            <i class="fa fa-times-circle"></i>
                        </button>
                        <button type="submit" class="property-search-submit-btn">
                            <i class="fa fa-search me-1"></i> <span>{{ $isBn ? 'অনুসন্ধান' : 'Search' }}</span>
                        </button>
                    </div>
                </div>

                <!-- District Dropdown -->
                <div class="col-lg-3 col-md-3 col-6">
                    <div class="property-filter-select-wrap">
                        <i class="fa fa-map-marker property-select-icon text-danger"></i>
                        <select name="district" id="propertyDistrictSelect" class="property-filter-select" onchange="fetchPropertiesViaAjax()">
                            <option value="all">{{ $isBn ? 'সকল জেলা / এলাকা' : 'All Districts / Areas' }}</option>
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
                        <select name="sort" id="propertySortSelect" class="property-filter-select" onchange="fetchPropertiesViaAjax()">
                            <option value="latest" {{ request('sort') == 'latest' ? 'selected' : '' }}>{{ $isBn ? 'নতুন প্রপার্টি আগে' : 'Newest First' }}</option>
                            <option value="price_asc" {{ request('sort') == 'price_asc' ? 'selected' : '' }}>{{ $isBn ? 'মূল্য: কম থেকে বেশি' : 'Price: Low to High' }}</option>
                            <option value="price_desc" {{ request('sort') == 'price_desc' ? 'selected' : '' }}>{{ $isBn ? 'মূল্য: বেশি থেকে কম' : 'Price: High to Low' }}</option>
                            <option value="area_desc" {{ request('sort') == 'area_desc' ? 'selected' : '' }}>{{ $isBn ? 'আয়তন: বড় থেকে ছোট' : 'Area: Largest First' }}</option>
                        </select>
                    </div>
                </div>
            </div>

            <!-- Category Filter Pills Bar (Horizontal Scrollable) -->
            <div class="d-flex align-items-center justify-content-between mt-3 pt-2 border-top flex-wrap gap-2">
                <div class="d-flex align-items-center gap-2 overflow-auto py-1 property-category-scroll" id="propertyCategoryPills" style="white-space: nowrap;">
                    <button type="button" 
                            data-cat="all"
                            onclick="selectPropertyCategory('all')" 
                            class="property-cat-pill {{ !request('type') || request('type') == 'all' ? 'active' : '' }}">
                        <i class="fa fa-th-large me-1"></i> {{ $isBn ? 'সব প্রপার্টি' : 'All Properties' }}
                    </button>
                    <button type="button" 
                            data-cat="প্লট"
                            onclick="selectPropertyCategory('প্লট')" 
                            class="property-cat-pill {{ request('type') == 'প্লট' ? 'active' : '' }}">
                        <i class="fa fa-map-signs me-1"></i> {{ $isBn ? 'আবাসিক প্লট' : 'Residential Plots' }}
                    </button>
                    <button type="button" 
                            data-cat="জমি"
                            onclick="selectPropertyCategory('জমি')" 
                            class="property-cat-pill {{ request('type') == 'জমি' ? 'active' : '' }}">
                        <i class="fa fa-tree me-1"></i> {{ $isBn ? 'জমি ও কৃষি খামার' : 'Agro & Farm Land' }}
                    </button>
                    <button type="button" 
                            data-cat="ফ্ল্যাট"
                            onclick="selectPropertyCategory('ফ্ল্যাট')" 
                            class="property-cat-pill {{ request('type') == 'ফ্ল্যাট' ? 'active' : '' }}">
                        <i class="fa fa-building-o me-1"></i> {{ $isBn ? 'ফ্ল্যাট / অ্যাপার্টমেন্ট' : 'Flats / Apartments' }}
                    </button>
                    <button type="button" 
                            data-cat="বাণিজ্যিক"
                            onclick="selectPropertyCategory('বাণিজ্যিক')" 
                            class="property-cat-pill {{ request('type') == 'বাণিজ্যিক' ? 'active' : '' }}">
                        <i class="fa fa-briefcase me-1"></i> {{ $isBn ? 'বাণিজ্যিক স্পেস' : 'Commercial Space' }}
                    </button>
                </div>

                <div id="propertyFilterResetWrap" style="{{ request('q') || (request('type') && request('type') !== 'all') || (request('district') && request('district') !== 'all') || (request('sort') && request('sort') !== 'latest') ? 'display: block;' : 'display: none;' }}">
                    <button type="button" onclick="resetAllPropertyFilters()" class="btn btn-sm btn-outline-danger rounded-pill px-3 py-1 fw-bold">
                        <i class="fa fa-refresh me-1"></i> {{ $isBn ? 'ফিল্টার রিসেট' : 'Reset Filters' }}
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Main Property Listing Section -->
<div class="property-main-listing-wrap py-5" style="background-color: #f8fafc; min-height: 600px;">
    <div class="container">
        <div class="d-flex align-items-center justify-content-between mb-4">
            <div>
                <h4 class="fw-bold text-dark mb-1">{{ $isBn ? 'উপলব্ধ জমি ও প্রপার্টি তালিকা' : 'Available Land & Property Listings' }}</h4>
                <p class="text-muted small mb-0" id="propertyCountText">{{ $isBn ? 'সর্বমোট ' . $properties->total() . ' টি সাইট প্রদর্শিত হচ্ছে' : 'Showing ' . $properties->total() . ' total available sites' }}</p>
            </div>
            <div id="propertyFilterClearWrap" style="{{ request('q') || (request('type') && request('type') !== 'all') ? 'display: block;' : 'display: none;' }}">
                <button type="button" onclick="resetAllPropertyFilters()" class="btn btn-sm btn-outline-danger rounded-pill px-3">
                    <i class="fa fa-times me-1"></i> {{ $isBn ? 'ফিল্টার মুছুন' : 'Clear Filters' }}
                </button>
            </div>
        </div>

        <div class="position-relative" id="propertiesListGridContainer" style="min-height: 380px;">
            <!-- Subtle Loading Overlay during AJAX search -->
            <div id="propertiesLoadingOverlay" style="display: none; position: absolute; inset: 0; background: rgba(248, 250, 252, 0.7); z-index: 10; backdrop-filter: blur(2px); border-radius: 16px; align-items: center; justify-content: center;">
                <div class="text-center p-4">
                    <div class="spinner-border text-success" role="status" style="width: 2.8rem; height: 2.8rem;">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                    <div class="mt-2 fw-bold text-dark">{{ $isBn ? 'প্রপার্টি তথ্য অনুসন্ধান হচ্ছে...' : 'Searching properties...' }}</div>
                </div>
            </div>

            <!-- Main Listing Grid -->
            <div class="row g-4" id="propertiesListGrid">
                @include('frontend.properties.partials.property_grid', ['properties' => $properties, 'isBn' => $isBn])
            </div>
        </div>

        <!-- Pagination Container -->
        <div id="propertyPaginationContainer" class="d-flex justify-content-center mt-5">
            @if($properties->hasPages())
                {{ $properties->links() }}
            @endif
        </div>
    </div>
</div>

<!-- Customer Site Visit / Booking Modal -->
<div class="modal fade" id="customerInquiryModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg rounded-4 overflow-hidden">
            <div class="modal-header bg-success text-white py-3 px-4">
                <h5 class="modal-title fw-bold mb-0">
                    <i class="fa fa-calendar-check-o me-2"></i> {{ $isBn ? 'সাইট পরিদর্শন বা বুকিং আবেদন' : 'Site Visit or Booking Application' }}
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="propertyBookingModalForm" action="{{ route('properties.inquiry') }}" method="POST" onsubmit="handleAjaxBookingSubmit(event, this)">
                @csrf
                <input type="hidden" name="property_id" id="modalPropertyId">
                <div class="modal-body p-4">
                    <div class="alert alert-light border mb-3 p-3 rounded-3 modal-property-preview">
                        <div class="small text-muted">{{ $isBn ? 'নির্বাচিত প্রপার্টি:' : 'Selected Property:' }}</div>
                        <div class="fw-bold text-dark" id="modalPropertyName">{{ $isBn ? 'প্রপার্টি লোড হচ্ছে...' : 'Loading property...' }}</div>
                        <div class="small text-success fw-bold mt-1" id="modalPropertyPrice">{{ $isBn ? 'মূল্য: ৳ ০' : 'Price: ৳ 0' }}</div>
                    </div>

                    <div class="row g-3">
                        <div class="col-12">
                            <label class="form-label small fw-bold"><i class="fa fa-user text-success me-1"></i> {{ $isBn ? 'আপনার নাম' : 'Your Name' }} <span class="text-danger">*</span></label>
                            <input type="text" name="customer_name" required class="form-control rounded-3" value="{{ Auth::check() ? Auth::user()->name : '' }}" placeholder="{{ $isBn ? 'আপনার পূর্ণ নাম লিখুন' : 'Enter your full name' }}">
                        </div>
                        <div class="col-12">
                            <label class="form-label small fw-bold"><i class="fa fa-phone text-success me-1"></i> {{ $isBn ? 'মোবাইল নম্বর' : 'Mobile Number' }} <span class="text-danger">*</span></label>
                            <input type="text" name="customer_phone" required class="form-control rounded-3" value="{{ Auth::check() ? Auth::user()->phone : '' }}" placeholder="০১xxxxxxxxx">
                        </div>
                        <div class="col-12">
                            <label class="form-label small fw-bold"><i class="fa fa-envelope-o text-success me-1"></i> {{ $isBn ? 'ইমেইল (যদি থাকে)' : 'Email (Optional)' }}</label>
                            <input type="email" name="customer_email" class="form-control rounded-3" value="{{ Auth::check() ? Auth::user()->email : '' }}" placeholder="email@example.com">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-bold"><i class="fa fa-tags text-success me-1"></i> {{ $isBn ? 'আবেদনের ধরন' : 'Inquiry Type' }} <span class="text-danger">*</span></label>
                            <select name="inquiry_type" class="form-select rounded-3" required>
                                <option value="site_visit" selected>📍 {{ $isBn ? 'সাইট পরিদর্শন (Site Visit)' : 'Site Visit' }}</option>
                                <option value="booking_request">📝 {{ $isBn ? 'সরাসরি বুকিং আবেদন (Booking)' : 'Direct Booking' }}</option>
                                <option value="price_inquiry">💬 {{ $isBn ? 'বিস্তারিত ও মূল্য আলোচনা (Inquiry)' : 'Pricing & EMI Inquiry' }}</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-bold"><i class="fa fa-calendar text-success me-1"></i> {{ $isBn ? 'পছন্দের তারিখ' : 'Preferred Date' }}</label>
                            <input type="date" name="preferred_date" class="form-control rounded-3" min="{{ date('Y-m-d') }}">
                        </div>
                        <div class="col-12">
                            <label class="form-label small fw-bold"><i class="fa fa-commenting-o text-success me-1"></i> {{ $isBn ? 'আপনার কোনো প্রশ্ন বা বিশেষ নোট' : 'Your Questions or Notes' }}</label>
                            <textarea name="message" rows="2" class="form-control rounded-3" placeholder="{{ $isBn ? 'যেমন: শুক্রবারে সাইট ভিজিট করতে চাই অথবা কিস্তি সুবিধা জানতে চাই...' : 'e.g. want to visit site on Friday or ask about installments...' }}"></textarea>
                        </div>
                    </div>
                </div>
                <div class="modal-footer bg-light py-3 px-4">
                    <button type="button" class="btn btn-secondary rounded-pill px-4" data-bs-dismiss="modal">{{ $isBn ? 'বাতিল' : 'Cancel' }}</button>
                    <button type="submit" id="modalSubmitBtn" class="btn btn-success fw-bold px-4 rounded-pill">
                        <i class="fa fa-send me-1"></i> {{ $isBn ? 'আবেদন সাবমিট করুন' : 'Submit Application' }}
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

    msgEl.innerText = message || '{{ $isBn ? "আপনার আবেদনটি সফলভাবে গৃহীত হয়েছে। আমাদের প্রতিনিধি দ্রুত যোগাযোগ করবেন।" : "Your application has been received successfully. Our representative will contact you shortly." }}';
    if (codeEl) {
        if (trackingCode) {
            codeEl.innerText = '{{ $isBn ? "ট্র্যাকিং কোড: " : "Tracking Code: " }}' + trackingCode;
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
    document.getElementById('modalPropertyPrice').innerText = '{{ $isBn ? "মূল্য: ৳ " : "Price: ৳ " }}' + price;
    var modal = new bootstrap.Modal(document.getElementById('customerInquiryModal'));
    modal.show();
}

function handleAjaxBookingSubmit(e, form) {
    e.preventDefault();
    var btn = form.querySelector('button[type="submit"]');
    var originalBtnHtml = btn ? btn.innerHTML : '';
    if (btn) {
        btn.disabled = true;
        btn.innerHTML = '<i class="fa fa-spinner fa-spin me-1"></i> {{ $isBn ? "আবেদন পাঠানো হচ্ছে..." : "Sending application..." }}';
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
        var msg = data.message || '{{ $isBn ? "আপনার আবেদনটি সফলভাবে গৃহীত হয়েছে!" : "Your application has been received successfully!" }}';
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
    if (input) input.value = cat;

    // Update active class on category pills
    var pills = document.querySelectorAll('#propertyCategoryPills .property-cat-pill');
    pills.forEach(function(pill) {
        if (pill.getAttribute('data-cat') === cat) {
            pill.classList.add('active');
        } else {
            pill.classList.remove('active');
        }
    });

    fetchPropertiesViaAjax();
}

var propertySearchTimer = null;
var activeFetchAbortController = null;

function fetchPropertiesViaAjax(targetUrl) {
    var loadingOverlay = document.getElementById('propertiesLoadingOverlay');
    var gridEl = document.getElementById('propertiesListGrid');
    var paginationContainer = document.getElementById('propertyPaginationContainer');
    var countTextEl = document.getElementById('propertyCountText');
    var searchIcon = document.getElementById('propertySearchIcon');
    var clearBtn = document.getElementById('propertySearchClearBtn');
    var resetWrap = document.getElementById('propertyFilterResetWrap');
    var clearWrap = document.getElementById('propertyFilterClearWrap');

    var searchInput = document.getElementById('propertySearchInput');
    var typeInput = document.getElementById('filterPropertyTypeInput');
    var districtSelect = document.getElementById('propertyDistrictSelect');
    var sortSelect = document.getElementById('propertySortSelect');

    var qVal = searchInput ? searchInput.value.trim() : '';
    var typeVal = typeInput ? typeInput.value : 'all';
    var districtVal = districtSelect ? districtSelect.value : 'all';
    var sortVal = sortSelect ? sortSelect.value : 'latest';

    // Show/hide clear search input button
    if (clearBtn) {
        clearBtn.style.display = qVal.length > 0 ? 'block' : 'none';
    }

    // Show/hide reset buttons if any filter active
    var hasActiveFilters = qVal.length > 0 || (typeVal && typeVal !== 'all') || (districtVal && districtVal !== 'all') || (sortVal && sortVal !== 'latest');
    if (resetWrap) resetWrap.style.display = hasActiveFilters ? 'block' : 'none';
    if (clearWrap) clearWrap.style.display = (qVal.length > 0 || (typeVal && typeVal !== 'all')) ? 'block' : 'none';

    // Show loading overlay and spinner
    if (loadingOverlay) loadingOverlay.style.display = 'flex';
    if (searchIcon) {
        searchIcon.className = 'fa fa-circle-o-notch fa-spin property-search-icon text-success';
    }

    var fetchUrl = targetUrl;
    if (!fetchUrl) {
        var params = new URLSearchParams();
        if (qVal) params.append('q', qVal);
        if (typeVal && typeVal !== 'all') params.append('type', typeVal);
        if (districtVal && districtVal !== 'all') params.append('district', districtVal);
        if (sortVal && sortVal !== 'latest') params.append('sort', sortVal);

        var queryString = params.toString();
        fetchUrl = "{{ route('properties.index') }}" + (queryString ? '?' + queryString : '');
    }

    if (activeFetchAbortController) {
        activeFetchAbortController.abort();
    }
    activeFetchAbortController = new AbortController();

    fetch(fetchUrl, {
        method: 'GET',
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'Accept': 'application/json'
        },
        signal: activeFetchAbortController.signal
    })
    .then(function(res) {
        return res.json();
    })
    .then(function(data) {
        if (loadingOverlay) loadingOverlay.style.display = 'none';
        if (searchIcon) {
            searchIcon.className = 'fa fa-search property-search-icon';
        }

        if (data && data.status === 'success') {
            if (gridEl) {
                gridEl.innerHTML = data.html;
            }
            if (paginationContainer) {
                paginationContainer.innerHTML = data.pagination || '';
                bindPaginationClicks();
            }
            if (countTextEl && data.count_text) {
                countTextEl.innerText = data.count_text;
            }

            // Sync URL in address bar without reload
            if (window.history && window.history.pushState) {
                window.history.pushState({ path: fetchUrl }, '', fetchUrl);
            }
        }
    })
    .catch(function(err) {
        if (err.name === 'AbortError') return;
        if (loadingOverlay) loadingOverlay.style.display = 'none';
        if (searchIcon) {
            searchIcon.className = 'fa fa-search property-search-icon';
        }
    });
}

function clearPropertySearch() {
    var searchInput = document.getElementById('propertySearchInput');
    if (searchInput) {
        searchInput.value = '';
    }
    fetchPropertiesViaAjax();
}

function resetAllPropertyFilters() {
    var searchInput = document.getElementById('propertySearchInput');
    if (searchInput) searchInput.value = '';

    var typeInput = document.getElementById('filterPropertyTypeInput');
    if (typeInput) typeInput.value = 'all';

    var districtSelect = document.getElementById('propertyDistrictSelect');
    if (districtSelect) districtSelect.value = 'all';

    var sortSelect = document.getElementById('propertySortSelect');
    if (sortSelect) sortSelect.value = 'latest';

    var pills = document.querySelectorAll('#propertyCategoryPills .property-cat-pill');
    pills.forEach(function(pill) {
        if (pill.getAttribute('data-cat') === 'all') {
            pill.classList.add('active');
        } else {
            pill.classList.remove('active');
        }
    });

    fetchPropertiesViaAjax();
}

function handleAjaxSearchSubmit(e) {
    if (e) e.preventDefault();
    fetchPropertiesViaAjax();
}

function bindPaginationClicks() {
    var container = document.getElementById('propertyPaginationContainer');
    if (!container) return;
    var links = container.querySelectorAll('a');
    links.forEach(function(link) {
        link.addEventListener('click', function(e) {
            e.preventDefault();
            var href = link.getAttribute('href');
            if (href && href !== '#' && !href.startsWith('javascript:')) {
                var listSec = document.getElementById('propertyListSection');
                if (listSec) {
                    listSec.scrollIntoView({ behavior: 'smooth', block: 'start' });
                }
                fetchPropertiesViaAjax(href);
            }
        });
    });
}

document.addEventListener('DOMContentLoaded', function() {
    @if(session('success'))
        showPropertyToast("{{ session('success') }}", '');
    @endif

    // Live search debouncing
    var searchInput = document.getElementById('propertySearchInput');
    if (searchInput) {
        searchInput.addEventListener('input', function() {
            if (propertySearchTimer) clearTimeout(propertySearchTimer);
            propertySearchTimer = setTimeout(function() {
                fetchPropertiesViaAjax();
            }, 300);
        });
    }

    bindPaginationClicks();

    // Browser back/forward navigation support
    window.addEventListener('popstate', function() {
        fetchPropertiesViaAjax(window.location.href);
    });
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
.property-title-link {
    color: #0f172a;
}
.property-card-hover:hover {
    transform: translateY(-5px);
    box-shadow: 0 12px 25px rgba(0,0,0,0.12) !important;
}
.hover-primary:hover {
    color: #16a34a !important;
}

/* Toast Styles */
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

/* ==========================================================================
   DARK MODE FOR PROPERTIES CATALOG PAGE
   ========================================================================== */
body.dark-theme .property-search-section,
html[data-theme="dark"] .property-search-section {
    background: #0f172a !important;
    border-color: #1e293b !important;
}
body.dark-theme .property-search-input-wrap,
html[data-theme="dark"] .property-search-input-wrap {
    background: #1e293b !important;
    border-color: #334155 !important;
}
body.dark-theme .property-search-input,
html[data-theme="dark"] .property-search-input {
    color: #f8fafc !important;
}
body.dark-theme .property-search-input::placeholder,
html[data-theme="dark"] .property-search-input::placeholder {
    color: #64748b !important;
}
body.dark-theme .property-filter-select-wrap,
html[data-theme="dark"] .property-filter-select-wrap {
    background: #1e293b !important;
    border-color: #334155 !important;
}
body.dark-theme .property-filter-select,
html[data-theme="dark"] .property-filter-select {
    color: #f8fafc !important;
}
body.dark-theme .property-filter-select option,
html[data-theme="dark"] .property-filter-select option {
    background: #1e293b !important;
    color: #f8fafc !important;
}
body.dark-theme .property-cat-pill,
html[data-theme="dark"] .property-cat-pill {
    background: #1e293b !important;
    color: #cbd5e1 !important;
    border-color: #334155 !important;
}
body.dark-theme .property-cat-pill:hover,
html[data-theme="dark"] .property-cat-pill:hover {
    background: #334155 !important;
    color: #ffffff !important;
}
body.dark-theme .property-cat-pill.active,
html[data-theme="dark"] .property-cat-pill.active {
    background: linear-gradient(135deg, #16a34a, #15803d) !important;
    color: #ffffff !important;
    border-color: #15803d !important;
}
body.dark-theme .property-main-listing-wrap,
html[data-theme="dark"] .property-main-listing-wrap {
    background: #0b1120 !important;
}
body.dark-theme .property-card,
html[data-theme="dark"] .property-card {
    background: #0f172a !important;
    border: 1px solid #1e293b !important;
}
body.dark-theme .property-title-link,
html[data-theme="dark"] .property-title-link {
    color: #f8fafc !important;
}
body.dark-theme .property-card-body,
html[data-theme="dark"] .property-card-body {
    background: #0f172a !important;
    color: #cbd5e1 !important;
}
body.dark-theme .property-stat-box,
html[data-theme="dark"] .property-stat-box {
    background: #0f172a !important;
    border-color: #f59e0b !important;
    color: #f8fafc !important;
}
body.dark-theme .property-feature-preview,
html[data-theme="dark"] .property-feature-preview {
    background: rgba(16, 185, 129, 0.15) !important;
    color: #6ee7b7 !important;
    border-color: rgba(16, 185, 129, 0.3) !important;
}
body.dark-theme .property-empty-box,
html[data-theme="dark"] .property-empty-box {
    background: #0f172a !important;
    color: #f8fafc !important;
    border: 1px solid #1e293b !important;
}
body.dark-theme .modal-content,
html[data-theme="dark"] .modal-content {
    background: #0f172a !important;
    color: #f8fafc !important;
    border: 1px solid #1e293b !important;
}
body.dark-theme .modal-property-preview,
html[data-theme="dark"] .modal-property-preview {
    background: #1e293b !important;
    border-color: #334155 !important;
    color: #f8fafc !important;
}
body.dark-theme .modal-body .form-control,
body.dark-theme .modal-body .form-select,
html[data-theme="dark"] .modal-body .form-control,
html[data-theme="dark"] .modal-body .form-select {
    background: #1e293b !important;
    border-color: #334155 !important;
    color: #f8fafc !important;
}
body.dark-theme .modal-footer,
html[data-theme="dark"] .modal-footer {
    background: #0b1120 !important;
    border-color: #1e293b !important;
}
body.dark-theme #propertiesLoadingOverlay,
html[data-theme="dark"] #propertiesLoadingOverlay {
    background: rgba(15, 23, 42, 0.78) !important;
}
body.dark-theme #propertiesLoadingOverlay .text-dark,
html[data-theme="dark"] #propertiesLoadingOverlay .text-dark {
    color: #f8fafc !important;
}
</style>
@endsection
