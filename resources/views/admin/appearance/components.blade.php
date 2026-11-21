@extends('layouts.backend.app')

@section('title', 'ইউআই কম্পোনেন্ট ও প্রোডাক্ট স্টাইলিং কাস্টমাইজার (UI Component Customizer)')

@section('content')
<div class="content-wrapper">
    <div class="container-full">
        <section class="content">

    <!-- Header Section -->
    <div class="d-flex flex-wrap justify-content-between align-items-center mb-4 gap-3">
        <div>
            <h3 class="page-title mb-1 fw-bold text-dark d-flex align-items-center">
                <span class="rounded-circle bg-success text-white me-2 d-inline-flex align-items-center justify-content-center shadow-sm" style="width: 40px; height: 40px;">
                    <i class="fa fa-sliders"></i>
                </span>
                ইউআই কম্পোনেন্ট ও প্রোডাক্ট স্টাইলিং কাস্টমাইজার
            </h3>
            <p class="text-muted small mb-0">
                প্রোডাক্ট টাইটেল, প্রাইস, ইমেজ সাইজ (ডেস্কটপ ও মোবাইল আলাদা), মাল্টিপল প্রিলোডার, কন্টাক্ট সেকশন, গ্লোবাল বাটন ও সার্চবারের রঙ রিয়েল-টাইমে নিয়ন্ত্রণ করুন।
            </p>
        </div>
        <div class="d-flex align-items-center gap-2">
            <a href="{{ route('admin.appearance.index') }}" class="btn btn-outline-secondary fw-bold shadow-sm">
                <i class="fa fa-th-large me-1"></i> টেমপ্লেট স্টুডিও
            </a>
            <a href="{{ route('api.ui.component_settings') }}" target="_blank" class="btn btn-outline-info fw-bold shadow-sm">
                <i class="fa fa-code me-1"></i> RESTful API JSON
            </a>
            <button type="button" onclick="document.getElementById('uiComponentForm').submit();" class="btn btn-success fw-bold px-4 shadow-sm">
                <i class="fa fa-save me-1"></i> সেটিংস সংরক্ষণ করুন
            </button>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success border-0 rounded-3 shadow-sm mb-4 d-flex align-items-center">
            <i class="fa fa-check-circle fa-2x me-3"></i>
            <div>
                <strong class="d-block text-success">{{ session('success') }}</strong>
                <small class="text-muted">পরিবর্তনসমূহ ওয়েবসাইট ও মোবাইল অ্যাপস এপিআই-তে অবিলম্বে প্রয়োগ হয়েছে।</small>
            </div>
        </div>
    @endif

    <form action="{{ route('admin.appearance.components.save') }}" method="POST" id="uiComponentForm">
        @csrf

        <div class="row g-4">

            <!-- ========================================================================= -->
            <!-- 1. PRODUCT CARD TYPOGRAPHY & SIZING (DESKTOP & MOBILE SEPARATE) -->
            <!-- ========================================================================= -->
            <div class="col-xl-8 col-lg-7">
                <div class="card border-0 shadow-sm rounded-4 mb-4 overflow-hidden">
                    <div class="card-header bg-white py-3 border-0 d-flex justify-content-between align-items-center">
                        <div class="d-flex align-items-center gap-2">
                            <span class="avatar avatar-sm rounded-circle bg-success-subtle text-success d-inline-flex align-items-center justify-content-center" style="width: 32px; height: 32px;">
                                <i class="fa fa-shopping-bag"></i>
                            </span>
                            <h5 class="fw-bold mb-0 text-dark">১. প্রোডাক্ট কার্ড ডিজাইন (ডেস্কটপ ও মোবাইল আলাদা নিয়ন্ত্রণ)</h5>
                        </div>
                        <span class="badge bg-primary-subtle text-primary fw-bold px-3 py-1">Desktop & Mobile Responsive</span>
                    </div>
                    <div class="card-body p-4 bg-light-subtle">

                        <!-- Desktop vs Mobile Nav Tabs for Product Card -->
                        <ul class="nav nav-pills bg-white p-2 rounded-3 shadow-sm border mb-4 gap-2" role="tablist">
                            <li class="nav-item flex-fill text-center">
                                <button class="nav-link active fw-bold py-2 px-3 w-100" data-bs-toggle="pill" data-bs-target="#prod-desktop" type="button">
                                    <i class="fa fa-desktop me-1 text-primary"></i> ডেস্কটপ সেটিংস (Desktop View)
                                </button>
                            </li>
                            <li class="nav-item flex-fill text-center">
                                <button class="nav-link fw-bold py-2 px-3 w-100" data-bs-toggle="pill" data-bs-target="#prod-mobile" type="button">
                                    <i class="fa fa-mobile-phone me-1 text-success fs-5"></i> মোবাইল সেটিংস (Mobile View & Apps)
                                </button>
                            </li>
                            <li class="nav-item flex-fill text-center">
                                <button class="nav-link fw-bold py-2 px-3 w-100" data-bs-toggle="pill" data-bs-target="#prod-general" type="button">
                                    <i class="fa fa-paint-brush me-1 text-warning"></i> কালার ও জেনারেল স্টাইল
                                </button>
                            </li>
                        </ul>

                        <div class="tab-content">
                            <!-- Desktop View Pane -->
                            <div class="tab-pane fade show active" id="prod-desktop">
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <div class="bg-white p-3 rounded-3 border shadow-sm">
                                            <label class="form-label fw-bold text-dark small mb-1">
                                                <i class="fa fa-font text-primary me-1"></i> প্রোডাক্ট টাইটেল ফন্ট সাইজ (ডেস্কটপ)
                                            </label>
                                            <div class="input-group">
                                                <input type="number" name="product_title_size_desktop" id="title_size_desk" 
                                                       value="{{ $cachedSettings['product_title_size_desktop'] ?? 15 }}" 
                                                       min="10" max="28" class="form-control fw-bold" oninput="updateLivePreview()">
                                                <span class="input-group-text bg-light fw-bold">px</span>
                                            </div>
                                            <small class="text-muted">পণ্য নামের অক্ষরের আকার (ডিফল্ট: 15px)</small>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="bg-white p-3 rounded-3 border shadow-sm">
                                            <label class="form-label fw-bold text-dark small mb-1">
                                                <i class="fa fa-tag text-success me-1"></i> প্রোডাক্ট মূল্য ফন্ট সাইজ (ডেস্কটপ)
                                            </label>
                                            <div class="input-group">
                                                <input type="number" name="product_price_size_desktop" id="price_size_desk" 
                                                       value="{{ $cachedSettings['product_price_size_desktop'] ?? 18 }}" 
                                                       min="12" max="32" class="form-control fw-bold" oninput="updateLivePreview()">
                                                <span class="input-group-text bg-light fw-bold">px</span>
                                            </div>
                                            <small class="text-muted">দাম বা টাকার ফন্ট সাইজ (ডিফল্ট: 18px)</small>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="bg-white p-3 rounded-3 border shadow-sm">
                                            <label class="form-label fw-bold text-dark small mb-1">
                                                <i class="fa fa-arrows-v text-info me-1"></i> প্রোডাক্ট ইমেজ উচ্চতা / Height (ডেস্কটপ)
                                            </label>
                                            <div class="input-group">
                                                <input type="number" name="product_img_height_desktop" id="img_h_desk" 
                                                       value="{{ $cachedSettings['product_img_height_desktop'] ?? 145 }}" 
                                                       min="80" max="300" class="form-control fw-bold" oninput="updateLivePreview()">
                                                <span class="input-group-text bg-light fw-bold">px</span>
                                            </div>
                                            <small class="text-muted">ইমেজ ফ্রেমের উচ্চতা (ডিফল্ট: 145px)</small>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="bg-white p-3 rounded-3 border shadow-sm">
                                            <label class="form-label fw-bold text-dark small mb-1">
                                                <i class="fa fa-arrows-h text-info me-1"></i> প্রোডাক্ট ইমেজ প্রস্থ / Width (ডেস্কটপ)
                                            </label>
                                            <div class="input-group">
                                                <input type="text" name="product_img_width_desktop" 
                                                       value="{{ $cachedSettings['product_img_width_desktop'] ?? '100%' }}" 
                                                       class="form-control fw-bold">
                                                <span class="input-group-text bg-light fw-bold">width</span>
                                            </div>
                                            <small class="text-muted">ইমেজের প্রস্থ (ডিফল্ট: 100%)</small>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="bg-white p-3 rounded-3 border shadow-sm">
                                            <label class="form-label fw-bold text-dark small mb-1">
                                                <i class="fa fa-hand-pointer-o text-warning me-1"></i> অর্ডার বাটন ফন্ট সাইজ (ডেস্কটপ)
                                            </label>
                                            <div class="input-group">
                                                <input type="number" name="product_order_btn_size_desktop" id="btn_size_desk" 
                                                       value="{{ $cachedSettings['product_order_btn_size_desktop'] ?? 14 }}" 
                                                       min="9" max="32" class="form-control fw-bold" oninput="updateLivePreview()">
                                                <span class="input-group-text bg-light fw-bold">px</span>
                                            </div>
                                            <small class="text-muted">বাটনের ভেতরের লেখার আকার (ডিফল্ট: 14px)</small>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Mobile View Pane -->
                            <div class="tab-pane fade" id="prod-mobile">
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <div class="bg-white p-3 rounded-3 border shadow-sm">
                                            <label class="form-label fw-bold text-dark small mb-1">
                                                <i class="fa fa-font text-primary me-1"></i> প্রোডাক্ট টাইটেল ফন্ট সাইজ (মোবাইল)
                                            </label>
                                            <div class="input-group">
                                                <input type="number" name="product_title_size_mobile" 
                                                       value="{{ $cachedSettings['product_title_size_mobile'] ?? 13 }}" 
                                                       min="10" max="22" class="form-control fw-bold">
                                                <span class="input-group-text bg-light fw-bold">px</span>
                                            </div>
                                            <small class="text-muted">মোবাইল ডিসপ্লেতে প্রোডাক্টের টাইটেল সাইজ (ডিফল্ট: 13px)</small>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="bg-white p-3 rounded-3 border shadow-sm">
                                            <label class="form-label fw-bold text-dark small mb-1">
                                                <i class="fa fa-tag text-success me-1"></i> প্রোডাক্ট মূল্য ফন্ট সাইজ (মোবাইল)
                                            </label>
                                            <div class="input-group">
                                                <input type="number" name="product_price_size_mobile" 
                                                       value="{{ $cachedSettings['product_price_size_mobile'] ?? 15 }}" 
                                                       min="10" max="24" class="form-control fw-bold">
                                                <span class="input-group-text bg-light fw-bold">px</span>
                                            </div>
                                            <small class="text-muted">মোবাইল ডিসপ্লেতে দামের ফন্ট সাইজ (ডিফল্ট: 15px)</small>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="bg-white p-3 rounded-3 border shadow-sm">
                                            <label class="form-label fw-bold text-dark small mb-1">
                                                <i class="fa fa-arrows-v text-info me-1"></i> প্রোডাক্ট ইমেজ উচ্চতা / Height (মোবাইল)
                                            </label>
                                            <div class="input-group">
                                                <input type="number" name="product_img_height_mobile" 
                                                       value="{{ $cachedSettings['product_img_height_mobile'] ?? 115 }}" 
                                                       min="60" max="220" class="form-control fw-bold">
                                                <span class="input-group-text bg-light fw-bold">px</span>
                                            </div>
                                            <small class="text-muted">মোবাইলে প্রোডাক্ট ইমেজের উচ্চতা (ডিফল্ট: 115px)</small>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="bg-white p-3 rounded-3 border shadow-sm">
                                            <label class="form-label fw-bold text-dark small mb-1">
                                                <i class="fa fa-arrows-h text-info me-1"></i> প্রোডাক্ট ইমেজ প্রস্থ / Width (মোবাইল)
                                            </label>
                                            <div class="input-group">
                                                <input type="text" name="product_img_width_mobile" 
                                                       value="{{ $cachedSettings['product_img_width_mobile'] ?? '100%' }}" 
                                                       class="form-control fw-bold">
                                                <span class="input-group-text bg-light fw-bold">width</span>
                                            </div>
                                            <small class="text-muted">মোবাইলে ইমেজের প্রস্থ (ডিফল্ট: 100%)</small>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="bg-white p-3 rounded-3 border shadow-sm">
                                            <label class="form-label fw-bold text-dark small mb-1">
                                                <i class="fa fa-hand-pointer-o text-warning me-1"></i> অর্ডার বাটন ফন্ট সাইজ (মোবাইল)
                                            </label>
                                            <div class="input-group">
                                                <input type="number" name="product_order_btn_size_mobile" 
                                                       value="{{ $cachedSettings['product_order_btn_size_mobile'] ?? 12 }}" 
                                                       min="9" max="28" class="form-control fw-bold">
                                                <span class="input-group-text bg-light fw-bold">px</span>
                                            </div>
                                            <small class="text-muted">মোবাইলে অর্ডার বাটনের ফন্ট সাইজ (ডিফল্ট: 12px)</small>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- General Colors & Styles -->
                            <div class="tab-pane fade" id="prod-general">
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <div class="bg-white p-3 rounded-3 border shadow-sm">
                                            <label class="form-label fw-bold text-dark small mb-1">
                                                <i class="fa fa-pencil text-primary me-1"></i> অর্ডার বাটন টাইটেল / লেখা (Button Text)
                                            </label>
                                            <input type="text" name="product_order_btn_text" id="order_btn_text_input" 
                                                   value="{{ $cachedSettings['product_order_btn_text'] ?? 'Order Now' }}" 
                                                   class="form-control fw-bold" oninput="updateLivePreview()">
                                            <small class="text-muted">বাটনে যা লেখা থাকবে (যেমন: Order Now বা অর্ডার করুন)</small>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="bg-white p-3 rounded-3 border shadow-sm">
                                            <label class="form-label fw-bold text-dark small mb-1">
                                                <i class="fa fa-paint-brush text-primary me-1"></i> প্রোডাক্ট টাইটেল রঙ (Color)
                                            </label>
                                            <div class="d-flex align-items-center gap-2">
                                                <input type="color" name="product_title_color" id="title_color_picker" 
                                                       value="{{ $cachedSettings['product_title_color'] ?? '#1e293b' }}" 
                                                       class="form-control form-control-color" oninput="updateLivePreview()">
                                                <input type="text" class="form-control fw-bold font-monospace" 
                                                       id="title_color_text" value="{{ $cachedSettings['product_title_color'] ?? '#1e293b' }}" readonly>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="bg-white p-3 rounded-3 border shadow-sm">
                                            <label class="form-label fw-bold text-dark small mb-1">
                                                <i class="fa fa-paint-brush text-success me-1"></i> প্রোডাক্ট মূল্যের রঙ (Color)
                                            </label>
                                            <div class="d-flex align-items-center gap-2">
                                                <input type="color" name="product_price_color" id="price_color_picker" 
                                                       value="{{ $cachedSettings['product_price_color'] ?? '#10b981' }}" 
                                                       class="form-control form-control-color" oninput="updateLivePreview()">
                                                <input type="text" class="form-control fw-bold font-monospace" 
                                                       id="price_color_text" value="{{ $cachedSettings['product_price_color'] ?? '#10b981' }}" readonly>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="bg-white p-3 rounded-3 border shadow-sm">
                                            <label class="form-label fw-bold text-dark small mb-1">
                                                <i class="fa fa-picture-o text-info me-1"></i> ইমেজ অবজেক্ট ফিট (Object Fit)
                                            </label>
                                            <select name="product_img_fit" class="form-select fw-bold" id="img_fit_select" onchange="updateLivePreview()">
                                                <option value="contain" {{ ($cachedSettings['product_img_fit'] ?? 'contain') === 'contain' ? 'selected' : '' }}>Contain (সম্পূর্ণ ফ্রেম ফিট)</option>
                                                <option value="cover" {{ ($cachedSettings['product_img_fit'] ?? 'contain') === 'cover' ? 'selected' : '' }}>Cover (ক্রপ ফিল)</option>
                                                <option value="scale-down" {{ ($cachedSettings['product_img_fit'] ?? 'contain') === 'scale-down' ? 'selected' : '' }}>Scale Down</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>

                <!-- ========================================================================= -->
                <!-- 2. MULTIPLE PRELOADERS & ON/OFF TOGGLE -->
                <!-- ========================================================================= -->
                <div class="card border-0 shadow-sm rounded-4 mb-4 overflow-hidden">
                    <div class="card-header bg-white py-3 border-0 d-flex justify-content-between align-items-center">
                        <div class="d-flex align-items-center gap-2">
                            <span class="avatar avatar-sm rounded-circle bg-warning-subtle text-warning d-inline-flex align-items-center justify-content-center" style="width: 32px; height: 32px;">
                                <i class="fa fa-spinner"></i>
                            </span>
                            <h5 class="fw-bold mb-0 text-dark">২. প্রিলোডার ম্যানেজমেন্ট (মাল্টিপল ডিজাইন ও অন/অফ সুইচ)</h5>
                        </div>
                        <div class="form-check form-switch fs-5 m-0">
                            <input class="form-check-input" type="checkbox" name="preloader_enabled" value="1" id="preloader_toggle" 
                                   {{ !empty($cachedSettings['preloader_enabled']) ? 'checked' : '' }}>
                            <label class="form-check-label fs-6 fw-bold text-dark" for="preloader_toggle">চালু (ON)</label>
                        </div>
                    </div>
                    <div class="card-body p-4 bg-light-subtle">
                        <p class="text-muted small mb-3">
                            <i class="fa fa-info-circle text-primary me-1"></i>
                            যদি আপনি অফ করে রাখেন তবে ওয়েবসাইট ও মোবাইল অ্যাপসের কোনো জায়গায় প্রিলোডার প্রদর্শন হবে না। অন রাখলে নির্বাচিত স্টাইলের প্রিলোডার লোড হবে।
                        </p>

                        <!-- Preloader Selection Cards -->
                        <div class="row g-3 mb-3">
                            @php
                                $selectedPl = $cachedSettings['preloader_type'] ?? 'cinematic_orbital';
                                $preloaders = [
                                    'cinematic_orbital' => [
                                        'title' => '১. অরবিটাল রিং (Cinematic)',
                                        'desc' => 'ঘূর্ণায়মান দ্বৈত অরবিটাল রিং ও সেন্ট্রাল লোগো গ্লো',
                                        'icon' => 'fa fa-circle-o-notch',
                                    ],
                                    'spinner_circular' => [
                                        'title' => '২. সার্কুলার স্পিনার',
                                        'desc' => 'মডার্ন মিনিমাল ডুয়াল-রিং ঘোরানো স্পিনার',
                                        'icon' => 'fa fa-spinner',
                                    ],
                                    'pulse_logo' => [
                                        'title' => '৩. পালস লোগো ওয়েভ',
                                        'desc' => 'লোগোর চারপাশ থেকে পালসিং তরঙ্গ তরঙ্গায়িত হবে',
                                        'icon' => 'fa fa-heartbeat',
                                    ],
                                    'leaf_agriculture' => [
                                        'title' => '৪. কৃষি পাতা ও গ্রোথ',
                                        'desc' => 'ইকো-ফ্রেন্ডলি এগ্রিকালচারাল লিফ মোশন ও ডট বাউন্স',
                                        'icon' => 'fa fa-leaf',
                                    ],
                                    'progress_bar' => [
                                        'title' => '৫. টপ প্রোগ্রেস লাইন',
                                        'desc' => 'স্ক্রিনের শীর্ষে স্মুথ রানিং প্রোগ্রেস বার (YouTube style)',
                                        'icon' => 'fa fa-minus',
                                    ],
                                ];
                            @endphp

                            @foreach($preloaders as $pKey => $pInfo)
                                <div class="col-md-4 col-sm-6">
                                    <label class="card h-100 border p-3 rounded-3 cursor-pointer preloader-card-option shadow-sm position-relative {{ $selectedPl === $pKey ? 'border-primary bg-primary-subtle' : 'bg-white' }}" style="cursor: pointer;">
                                        <div class="form-check position-absolute top-0 end-0 m-2">
                                            <input class="form-check-input" type="radio" name="preloader_type" value="{{ $pKey }}" {{ $selectedPl === $pKey ? 'checked' : '' }}>
                                        </div>
                                        <div class="d-flex align-items-center gap-2 mb-2">
                                            <i class="{{ $pInfo['icon'] }} fa-2x text-warning"></i>
                                            <h6 class="fw-bold mb-0 text-dark">{{ $pInfo['title'] }}</h6>
                                        </div>
                                        <p class="text-muted small mb-0">{{ $pInfo['desc'] }}</p>
                                    </label>
                                </div>
                            @endforeach
                        </div>

                        <div class="row g-3 align-items-center mt-2">
                            <div class="col-md-6">
                                <label class="form-label fw-bold text-dark small mb-1">প্রিলোডার প্রধান অ্যাকসেন্ট রঙ (Accent Color)</label>
                                <div class="d-flex align-items-center gap-2">
                                    <input type="color" name="preloader_primary_color" 
                                           value="{{ $cachedSettings['preloader_primary_color'] ?? '#ea580c' }}" 
                                           class="form-control form-control-color">
                                    <span class="small text-muted font-monospace">{{ $cachedSettings['preloader_primary_color'] ?? '#ea580c' }}</span>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>

                <!-- ========================================================================= -->
                <!-- 3. CONTACT SECTION ON/OFF TOGGLE -->
                <!-- ========================================================================= -->
                <div class="card border-0 shadow-sm rounded-4 mb-4 overflow-hidden">
                    <div class="card-header bg-white py-3 border-0 d-flex justify-content-between align-items-center">
                        <div class="d-flex align-items-center gap-2">
                            <span class="avatar avatar-sm rounded-circle bg-info-subtle text-info d-inline-flex align-items-center justify-content-center" style="width: 32px; height: 32px;">
                                <i class="fa fa-envelope"></i>
                            </span>
                            <h5 class="fw-bold mb-0 text-dark">৩. "Get in Touch With Our Team" সেকশন নিয়ন্ত্রণ (On/Off)</h5>
                        </div>
                        <div class="form-check form-switch fs-5 m-0">
                            <input class="form-check-input" type="checkbox" name="contact_section_enabled" value="1" id="contact_toggle" 
                                   {{ !empty($cachedSettings['contact_section_enabled']) ? 'checked' : '' }}>
                            <label class="form-check-label fs-6 fw-bold text-dark" for="contact_toggle">সক্রিয় (ON)</label>
                        </div>
                    </div>
                    <div class="card-body p-4 bg-light-subtle">
                        <div class="alert alert-secondary border-0 rounded-3 mb-0 d-flex align-items-center">
                            <i class="fa fa-eye-slash fa-2x text-muted me-3"></i>
                            <div>
                                <strong class="d-block text-dark">ওয়েবসাইট ও মোবাইল অ্যাপসে ইনস্ট্যান্ট সিঙ্ক</strong>
                                <span class="small text-muted">আপনি যদি এই সুইচটি অফ (OFF) করে রাখেন, তবে ওয়েবসাইট এবং মোবাইল অ্যাপস দুই জায়গাতেই "Get in Touch With Our Team" কন্টাক্ট সেকশনটি সম্পূর্ণভাবে লুকানো থাকবে।</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- ========================================================================= -->
                <!-- 4. GLOBAL SYSTEM BUTTONS COLOR -->
                <!-- ========================================================================= -->
                <div class="card border-0 shadow-sm rounded-4 mb-4 overflow-hidden">
                    <div class="card-header bg-white py-3 border-0 d-flex justify-content-between align-items-center">
                        <div class="d-flex align-items-center gap-2">
                            <span class="avatar avatar-sm rounded-circle bg-primary-subtle text-primary d-inline-flex align-items-center justify-content-center" style="width: 32px; height: 32px;">
                                <i class="fa fa-hand-pointer-o"></i>
                            </span>
                            <h5 class="fw-bold mb-0 text-dark">৪. সিস্টেম বাটন কালার (সকল বাটন একই রঙ)</h5>
                        </div>
                        <span class="badge bg-success-subtle text-success fw-bold px-3 py-1">Unified Button Theme</span>
                    </div>
                    <div class="card-body p-4 bg-light-subtle">
                        <p class="text-muted small mb-3">
                            <i class="fa fa-paint-brush text-primary me-1"></i>
                            আপনার রিকোয়ারমেন্ট অনুযায়ী সিস্টেমে যত বাটন আছে (Order Now, Search, View All, সাবমিট বাটন ইত্যাদি), সব বাটনের রঙ কেন্দ্রীয়ভাবে এখান থেকে এক হয়ে যাবে।
                        </p>

                        <div class="row g-3">
                            <div class="col-md-6">
                                <div class="bg-white p-3 rounded-3 border shadow-sm">
                                    <label class="form-label fw-bold text-dark small mb-1">বাটনের ব্যাকগ্রাউন্ড কালার (Background Color)</label>
                                    <div class="d-flex align-items-center gap-2">
                                        <input type="color" name="global_button_bg_color" id="btn_bg_picker" 
                                               value="{{ $cachedSettings['global_button_bg_color'] ?? '#007e8a' }}" 
                                               class="form-control form-control-color" oninput="updateLivePreview()">
                                        <input type="text" class="form-control fw-bold font-monospace" 
                                               id="btn_bg_text" value="{{ $cachedSettings['global_button_bg_color'] ?? '#007e8a' }}" readonly>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="bg-white p-3 rounded-3 border shadow-sm">
                                    <label class="form-label fw-bold text-dark small mb-1">বাটনের লেখার কালার (Text Color)</label>
                                    <div class="d-flex align-items-center gap-2">
                                        <input type="color" name="global_button_text_color" id="btn_text_picker" 
                                               value="{{ $cachedSettings['global_button_text_color'] ?? '#ffffff' }}" 
                                               class="form-control form-control-color" oninput="updateLivePreview()">
                                        <input type="text" class="form-control fw-bold font-monospace" 
                                               id="btn_text_color_text" value="{{ $cachedSettings['global_button_text_color'] ?? '#ffffff' }}" readonly>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="bg-white p-3 rounded-3 border shadow-sm">
                                    <label class="form-label fw-bold text-dark small mb-1">বাটন হোভার কালার (Hover Color)</label>
                                    <div class="d-flex align-items-center gap-2">
                                        <input type="color" name="global_button_hover_bg_color" 
                                               value="{{ $cachedSettings['global_button_hover_bg_color'] ?? '#005f69' }}" 
                                               class="form-control form-control-color">
                                        <span class="small text-muted font-monospace">{{ $cachedSettings['global_button_hover_bg_color'] ?? '#005f69' }}</span>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="bg-white p-3 rounded-3 border shadow-sm">
                                    <label class="form-label fw-bold text-dark small mb-1">বাটনের কোণ রাউন্ড / গোলত্ব (Radius px)</label>
                                    <div class="input-group">
                                        <input type="number" name="global_button_border_radius" id="btn_radius_input" 
                                               value="{{ $cachedSettings['global_button_border_radius'] ?? 50 }}" 
                                               min="0" max="50" class="form-control fw-bold" oninput="updateLivePreview()">
                                        <span class="input-group-text bg-light fw-bold">px</span>
                                    </div>
                                    <small class="text-muted">50px = সম্পূর্ণ পিল (Pill), 8px = সাধারণ রাউন্ডেড</small>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>

                <!-- ========================================================================= -->
                <!-- 5. SEARCH BAR & QSEARCH BUTTON COLOR -->
                <!-- ========================================================================= -->
                <div class="card border-0 shadow-sm rounded-4 mb-4 overflow-hidden">
                    <div class="card-header bg-white py-3 border-0 d-flex justify-content-between align-items-center">
                        <div class="d-flex align-items-center gap-2">
                            <span class="avatar avatar-sm rounded-circle bg-danger-subtle text-danger d-inline-flex align-items-center justify-content-center" style="width: 32px; height: 32px;">
                                <i class="fa fa-search"></i>
                            </span>
                            <h5 class="fw-bold mb-0 text-dark">৫. সার্চ বার ও QSearch বাটন কালার</h5>
                        </div>
                    </div>
                    <div class="card-body p-4 bg-light-subtle">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <div class="bg-white p-3 rounded-3 border shadow-sm">
                                    <label class="form-label fw-bold text-dark small mb-1">সার্চ বার ব্যাকগ্রাউন্ড কালার</label>
                                    <div class="d-flex align-items-center gap-2">
                                        <input type="color" name="search_bar_bg_color" 
                                               value="{{ $cachedSettings['search_bar_bg_color'] ?? '#ffffff' }}" 
                                               class="form-control form-control-color">
                                        <span class="small text-muted font-monospace">{{ $cachedSettings['search_bar_bg_color'] ?? '#ffffff' }}</span>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="bg-white p-3 rounded-3 border shadow-sm">
                                    <label class="form-label fw-bold text-dark small mb-1">সার্চ বার বর্ডার কালার</label>
                                    <div class="d-flex align-items-center gap-2">
                                        <input type="color" name="search_bar_border_color" 
                                               value="{{ $cachedSettings['search_bar_border_color'] ?? '#e2e8f0' }}" 
                                               class="form-control form-control-color">
                                        <span class="small text-muted font-monospace">{{ $cachedSettings['search_bar_border_color'] ?? '#e2e8f0' }}</span>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="bg-white p-3 rounded-3 border shadow-sm">
                                    <label class="form-label fw-bold text-dark small mb-1">সার্চ বাটন (QSearch) ব্যাকগ্রাউন্ড কালার</label>
                                    <div class="d-flex align-items-center gap-2">
                                        <input type="color" name="search_button_bg_color" 
                                               value="{{ $cachedSettings['search_button_bg_color'] ?? '#ea580c' }}" 
                                               class="form-control form-control-color">
                                        <span class="small text-muted font-monospace">{{ $cachedSettings['search_button_bg_color'] ?? '#ea580c' }}</span>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="bg-white p-3 rounded-3 border shadow-sm">
                                    <label class="form-label fw-bold text-dark small mb-1">সার্চ বাটন লেখার কালার</label>
                                    <div class="d-flex align-items-center gap-2">
                                        <input type="color" name="search_button_text_color" 
                                               value="{{ $cachedSettings['search_button_text_color'] ?? '#ffffff' }}" 
                                               class="form-control form-control-color">
                                        <span class="small text-muted font-monospace">{{ $cachedSettings['search_button_text_color'] ?? '#ffffff' }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- ========================================================================= -->
                <!-- 6. LIVE CHAT & SCROLL-TO-TOP ARROW BUTTON -->
                <!-- ========================================================================= -->
                <div class="card border-0 shadow-sm rounded-4 mb-4 overflow-hidden">
                    <div class="card-header bg-white py-3 border-0 d-flex justify-content-between align-items-center">
                        <div class="d-flex align-items-center gap-2">
                            <span class="avatar avatar-sm rounded-circle bg-dark-subtle text-dark d-inline-flex align-items-center justify-content-center" style="width: 32px; height: 32px;">
                                <i class="fa fa-comments"></i>
                            </span>
                            <h5 class="fw-bold mb-0 text-dark">৬. লাইভ চ্যাট ও স্ক্রল-টু-টপ অ্যারো বাটন কালার</h5>
                        </div>
                    </div>
                    <div class="card-body p-4 bg-light-subtle">
                        <div class="row g-4">
                            <!-- Live Chat Trigger Settings -->
                            <div class="col-md-6">
                                <div class="bg-white p-3 rounded-3 border shadow-sm h-100">
                                    <div class="d-flex justify-content-between align-items-center mb-2">
                                        <h6 class="fw-bold mb-0 text-dark"><i class="fa fa-commenting text-warning me-1"></i> লাইভ চ্যাট বাটন</h6>
                                        <div class="form-check form-switch m-0">
                                            <input class="form-check-input" type="checkbox" name="chat_button_enabled" value="1" 
                                                   {{ !empty($cachedSettings['chat_button_enabled']) ? 'checked' : '' }}>
                                        </div>
                                    </div>
                                    <div class="mb-2">
                                        <label class="form-label small fw-bold text-muted mb-1">চ্যাট বাটন ব্যাকগ্রাউন্ড কালার</label>
                                        <input type="color" name="chat_button_bg_color" 
                                               value="{{ $cachedSettings['chat_button_bg_color'] ?? '#ea580c' }}" 
                                               class="form-control form-control-color">
                                    </div>
                                    <div>
                                        <label class="form-label small fw-bold text-muted mb-1">চ্যাট আইকন কালার</label>
                                        <input type="color" name="chat_button_icon_color" 
                                               value="{{ $cachedSettings['chat_button_icon_color'] ?? '#ffffff' }}" 
                                               class="form-control form-control-color">
                                    </div>
                                </div>
                            </div>

                            <!-- Scroll-to-Top Trigger Settings -->
                            <div class="col-md-6">
                                <div class="bg-white p-3 rounded-3 border shadow-sm h-100">
                                    <div class="d-flex justify-content-between align-items-center mb-2">
                                        <h6 class="fw-bold mb-0 text-dark"><i class="fa fa-arrow-up text-primary me-1"></i> স্ক্রল-টু-টপ অ্যারো বাটন</h6>
                                        <div class="form-check form-switch m-0">
                                            <input class="form-check-input" type="checkbox" name="scroll_button_enabled" value="1" 
                                                   {{ !empty($cachedSettings['scroll_button_enabled']) ? 'checked' : '' }}>
                                        </div>
                                    </div>
                                    <div class="mb-2">
                                        <label class="form-label small fw-bold text-muted mb-1">স্ক্রল বাটন ব্যাকগ্রাউন্ড কালার</label>
                                        <input type="color" name="scroll_button_bg_color" 
                                               value="{{ $cachedSettings['scroll_button_bg_color'] ?? '#ea580c' }}" 
                                               class="form-control form-control-color">
                                    </div>
                                    <div>
                                        <label class="form-label small fw-bold text-muted mb-1">অ্যারো আইকন কালার</label>
                                        <input type="color" name="scroll_button_icon_color" 
                                               value="{{ $cachedSettings['scroll_button_icon_color'] ?? '#ffffff' }}" 
                                               class="form-control form-control-color">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>

            <!-- ========================================================================= -->
            <!-- LIVE INTERACTIVE PREVIEW SIDEBAR -->
            <!-- ========================================================================= -->
            <div class="col-xl-4 col-lg-5">
                <div class="card border-0 shadow-sm rounded-4 sticky-top overflow-hidden" style="top: 85px;">
                    <div class="card-header bg-dark text-white py-3 border-0 d-flex justify-content-between align-items-center">
                        <span class="fw-bold small d-flex align-items-center gap-2">
                            <span class="spinner-grow spinner-grow-sm text-success" role="status"></span>
                            লাইভ প্রিভিউ (Live Product Preview)
                        </span>
                        <span class="badge bg-success">Real-time Sync</span>
                    </div>
                    <div class="card-body p-4 bg-light text-center">

                        <!-- Preview Card Container -->
                        <div class="mx-auto text-start" style="max-width: 260px;">
                            <div class="card border shadow-sm rounded-3 overflow-hidden bg-white" id="preview_product_card">
                                <!-- Image Preview Wrap -->
                                <div class="p-2 text-center bg-light d-flex align-items-center justify-content-center" id="preview_img_wrap" style="height: 145px;">
                                    <img src="https://images.unsplash.com/photo-1586201375761-83865001e31c?auto=format&fit=crop&w=400&q=80" 
                                         alt="Preview Rice" id="preview_img" 
                                         style="width: 100%; height: 100%; object-fit: contain; transition: all 0.2s ease;">
                                </div>
                                <!-- Body -->
                                <div class="card-body p-3">
                                    <div class="text-muted small mb-1">
                                        <i class="fa fa-truck text-teal me-1"></i> Delivery 1-2 hours
                                    </div>
                                    <!-- Title -->
                                    <h6 class="fw-bold mb-2 text-truncate" id="preview_title" style="font-size: 15px; color: #1e293b;">
                                        Premium Miniket Loose Rice
                                    </h6>
                                    <!-- Price -->
                                    <div class="d-flex align-items-baseline justify-content-between mb-3 border-top pt-2">
                                        <span class="fw-bold" id="preview_price" style="font-size: 18px; color: #10b981;">৳ 85</span>
                                        <small class="text-muted fw-bold">/1kg</small>
                                    </div>
                                    <!-- Order Now Button -->
                                    <button type="button" class="btn w-100 fw-bold shadow-sm" id="preview_order_btn" 
                                            style="background: #007e8a; color: #ffffff; border-radius: 50px; font-size: 14px; padding: 8px 16px;">
                                        <i class="fa fa-shopping-bag me-1"></i> <span id="preview_btn_text">Order Now</span>
                                    </button>
                                </div>
                            </div>
                        </div>

                        <!-- Mini Search Bar Preview -->
                        <div class="mt-4 pt-3 border-top text-start">
                            <span class="small fw-bold text-muted d-block mb-2"><i class="fa fa-search me-1"></i> সার্চ বার প্রিভিউ</span>
                            <div class="d-flex align-items-center rounded-pill p-1 border shadow-sm" id="preview_search_bar" style="background: #fff;">
                                <input type="text" class="form-control border-0 bg-transparent py-1 px-3 small" placeholder="Search products..." readonly>
                                <button type="button" class="btn btn-sm rounded-pill px-3 text-white fw-bold" id="preview_search_btn" style="background: #ea580c;">
                                    QSearch
                                </button>
                            </div>
                        </div>

                        <!-- Sticky Submit Action -->
                        <div class="mt-4">
                            <button type="submit" class="btn btn-success btn-lg w-100 fw-bold shadow">
                                <i class="fa fa-check-circle me-1"></i> সকল পরিবর্তন সংরক্ষণ করুন
                            </button>
                        </div>

                    </div>
                </div>
            </div>

        </div>
    </form>

        </section>
    </div>
</div>

<script>
function updateLivePreview() {
    // 1. Title Size & Color
    const titleSize = document.getElementById('title_size_desk').value || 15;
    const titleColor = document.getElementById('title_color_picker').value || '#1e293b';
    document.getElementById('title_color_text').value = titleColor;

    const previewTitle = document.getElementById('preview_title');
    previewTitle.style.fontSize = titleSize + 'px';
    previewTitle.style.color = titleColor;

    // 2. Price Size & Color
    const priceSize = document.getElementById('price_size_desk').value || 18;
    const priceColor = document.getElementById('price_color_picker').value || '#10b981';
    document.getElementById('price_color_text').value = priceColor;

    const previewPrice = document.getElementById('preview_price');
    previewPrice.style.fontSize = priceSize + 'px';
    previewPrice.style.color = priceColor;

    // 3. Image Height & Fit
    const imgHeight = document.getElementById('img_h_desk').value || 145;
    const imgFit = document.getElementById('img_fit_select').value || 'contain';
    document.getElementById('preview_img_wrap').style.height = imgHeight + 'px';
    document.getElementById('preview_img').style.objectFit = imgFit;

    // 4. Order Button
    const btnBg = document.getElementById('btn_bg_picker').value || '#007e8a';
    const btnText = document.getElementById('btn_text_picker').value || '#ffffff';
    const btnRadius = document.getElementById('btn_radius_input').value || 50;
    const btnFontSize = document.getElementById('btn_size_desk').value || 14;
    const btnLabel = document.getElementById('order_btn_text_input').value || 'Order Now';

    document.getElementById('btn_bg_text').value = btnBg;
    document.getElementById('btn_text_color_text').value = btnText;

    const previewBtn = document.getElementById('preview_order_btn');
    previewBtn.style.backgroundColor = btnBg;
    previewBtn.style.color = btnText;
    previewBtn.style.borderRadius = btnRadius + 'px';
    previewBtn.style.fontSize = btnFontSize + 'px';
    document.getElementById('preview_btn_text').innerText = btnLabel;

    // 5. Search Bar Preview
    const searchBg = document.querySelector('input[name="search_bar_bg_color"]').value;
    const searchBorder = document.querySelector('input[name="search_bar_border_color"]').value;
    const searchBtnBg = document.querySelector('input[name="search_button_bg_color"]').value;
    const searchBtnText = document.querySelector('input[name="search_button_text_color"]').value;

    const previewSearchBar = document.getElementById('preview_search_bar');
    if (previewSearchBar) {
        previewSearchBar.style.backgroundColor = searchBg;
        previewSearchBar.style.borderColor = searchBorder;
    }
    const previewSearchBtn = document.getElementById('preview_search_btn');
    if (previewSearchBtn) {
        previewSearchBtn.style.backgroundColor = searchBtnBg;
        previewSearchBtn.style.color = searchBtnText;
    }
}

document.addEventListener('DOMContentLoaded', updateLivePreview);
</script>
@endsection
