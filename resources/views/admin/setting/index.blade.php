@extends('layouts.backend.app')

@section('content')
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">

    <div class="container-full">
        <!-- Content Header (Page header) -->
        <div class="content-header">
            <div class="d-flex align-items-center">
                <div class="me-auto">
                    <h4 class="page-title">Setting</h4>
                </div>

            </div>
        </div>

        <!-- Main content -->
        <section class="content">
            <div class="row">


                <!-- /.col -->
                <div class="col-xl-12 col-lg-12">
                    <div class="box">
                        <div class="box-header with-border">
                            <h3 class="box-title">Site Setting</h3>
                        </div>
                        <!-- /.box-header -->
                        <div class="box-body">
                            <form class="form-horizontal form-bordered" method="POST"
                                action="{{ route('setting.update') }}" enctype="multipart/form-data">

                                @csrf

                                <div class="row">

                                    <!-- ============================================================== -->
                                    <!-- 🎨 Theme, Colors & Display Control (হেডার, বাটন ও ডিসপ্লে সেটিংস) -->
                                    <!-- ============================================================== -->
                                    <div class="col-12 mb-4" id="theme_display_settings_card">
                                        <div class="box box-solid box-inverse" style="background: linear-gradient(135deg, #ffffff 0%, #f8fafc 100%); border: 2.5px solid #ea580c; border-radius: 16px; box-shadow: 0 10px 30px rgba(234, 88, 12, 0.15); overflow: hidden;">
                                            <div class="box-header with-border" style="background: linear-gradient(135deg, #ea580c 0%, #d97706 100%); color: #ffffff; padding: 18px 24px;">
                                                <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
                                                    <div class="d-flex align-items-center">
                                                        <span class="avatar avatar-lg rounded-circle bg-white text-danger me-3 d-flex align-items-center justify-content-center shadow-sm" style="width: 44px; height: 44px; font-size: 20px;">
                                                            <i class="fa fa-paint-brush text-warning"></i>
                                                        </span>
                                                        <div>
                                                            <h4 class="box-title mb-0 fw-bold text-white" style="font-size: 18px;">
                                                                থিম কালার, হেডার, বাটন ও ডিসপ্লে নিয়ন্ত্রণ (Theme Colors & Dynamic Display)
                                                            </h4>
                                                            <p class="mb-0 text-white-50 small">
                                                                এখান থেকে কালার এবং অপশন পরিবর্তন করলে ওয়েবসাইট এবং মোবাইল অ্যাপ — উভয় জায়গায় তাৎক্ষণিকভাবে কার্যকর হবে।
                                                            </p>
                                                        </div>
                                                    </div>
                                                    <span class="badge bg-white text-dark fw-bold px-3 py-2 shadow-sm rounded-pill font-size-12">
                                                        <i class="fa fa-mobile-phone text-success me-1"></i> অ্যাপ & ওয়েবসাইট সিঙ্ক
                                                    </span>
                                                </div>
                                            </div>

                                            <div class="box-body p-4">
                                                <div class="row g-4">
                                                    <!-- 1. Header Background Color -->
                                                    <div class="col-xl-6 col-lg-6 col-12">
                                                        <div class="p-3 rounded-3 h-100" style="background: #ffffff; border: 1.5px solid #fed7aa; box-shadow: 0 4px 14px rgba(0,0,0,0.03);">
                                                            <div class="d-flex align-items-center justify-content-between mb-2">
                                                                <label class="fw-bold text-dark mb-0" style="font-size: 15px;">
                                                                    <i class="fa fa-header text-warning me-1"></i> ১. হেডারের ব্যাকগ্রাউন্ড কালার (Header Color)
                                                                </label>
                                                                <span class="badge bg-warning text-dark font-size-11">App & Web Sync</span>
                                                            </div>
                                                            <p class="text-muted small mb-3">
                                                                হেডারের যে কালার দিবেন, ওয়েবসাইট ও অ্যাপস দুই জায়গায় হুবহু সেই কালার শো করবে।
                                                            </p>

                                                            @php
                                                                $currentHeaderColor = setting('header_bg_color') ?: (setting('header_color') ?: '#ea580c');
                                                            @endphp

                                                            <div class="d-flex align-items-center gap-3 mb-3">
                                                                <input type="color" class="form-control form-control-color border-0 p-1 rounded-3 shadow-sm cursor-pointer"
                                                                    id="header_bg_color_picker" style="width: 65px; height: 46px; cursor: pointer;"
                                                                    value="{{ $currentHeaderColor }}">
                                                                <div class="input-group">
                                                                    <span class="input-group-text bg-light fw-bold text-muted">HEX</span>
                                                                    <input type="text" class="form-control fw-bold" id="header_bg_color" name="header_bg_color"
                                                                        value="{{ $currentHeaderColor }}" style="height: 46px; font-size: 15px; letter-spacing: 0.5px;">
                                                                </div>
                                                            </div>

                                                            <!-- Presets -->
                                                            <div class="mb-3">
                                                                <small class="fw-bold text-muted d-block mb-2">জনপ্রিয় কালার প্যালেট (ক্লিক করে সিলেক্ট করুন):</small>
                                                                <div class="d-flex flex-wrap gap-2" id="header_color_presets">
                                                                    <button type="button" class="btn btn-sm text-white px-2 py-1 preset-color-btn" data-color="#ea580c" style="background: #ea580c; border-radius: 8px;">🟠 অরেঞ্জ (ডিফল্ট)</button>
                                                                    <button type="button" class="btn btn-sm text-white px-2 py-1 preset-color-btn" data-color="#007e8a" style="background: #007e8a; border-radius: 8px;">🟢 রয়েল টিল</button>
                                                                    <button type="button" class="btn btn-sm text-white px-2 py-1 preset-color-btn" data-color="#0284c7" style="background: #0284c7; border-radius: 8px;">🔵 সাইয়ান ব্লু</button>
                                                                    <button type="button" class="btn btn-sm text-white px-2 py-1 preset-color-btn" data-color="#059669" style="background: #059669; border-radius: 8px;">🌿 ডিপ এমারেল্ড</button>
                                                                    <button type="button" class="btn btn-sm text-white px-2 py-1 preset-color-btn" data-color="#7c3aed" style="background: #7c3aed; border-radius: 8px;">🟣 রয়্যাল পার্পল</button>
                                                                    <button type="button" class="btn btn-sm text-white px-2 py-1 preset-color-btn" data-color="#0f172a" style="background: #0f172a; border-radius: 8px;">🌑 মিডনাইট নেভি</button>
                                                                    <button type="button" class="btn btn-sm text-white px-2 py-1 preset-color-btn" data-color="#dc2626" style="background: #dc2626; border-radius: 8px;">🔴 রুবি রেড</button>
                                                                    <button type="button" class="btn btn-sm text-white px-2 py-1 preset-color-btn" data-color="#d97706" style="background: #d97706; border-radius: 8px;">🟡 গোল্ডেন</button>
                                                                </div>
                                                            </div>

                                                            <!-- Live Header Mockup Preview -->
                                                            <div class="rounded-3 p-3 text-white" id="admin_header_preview_box"
                                                                style="background: {{ $currentHeaderColor }}; border-radius: 12px; transition: background 0.25s ease; box-shadow: 0 4px 15px rgba(0,0,0,0.15);">
                                                                <div class="d-flex align-items-center justify-content-between mb-2">
                                                                    <div class="d-flex align-items-center">
                                                                        <span class="badge bg-white text-dark me-2 px-2 py-1 font-size-11 fw-bold">Live Header</span>
                                                                        <small class="opacity-75">127.0.0.1:8000</small>
                                                                    </div>
                                                                    <div class="d-flex gap-1">
                                                                        <span class="badge bg-white text-dark font-size-10">EN | বাংলা</span>
                                                                        <span class="badge bg-dark text-white font-size-10">🌙</span>
                                                                    </div>
                                                                </div>
                                                                <div class="d-flex align-items-center justify-content-between gap-2">
                                                                    <span class="fw-bold fs-6 text-white text-truncate"><i class="fa fa-leaf me-1"></i> ekrishisheba</span>
                                                                    <div class="bg-white rounded-pill px-3 py-1 text-muted small flex-grow-1 mx-2 text-truncate" style="max-width: 200px; font-size: 11px;">
                                                                        <i class="fa fa-search me-1"></i> Search stocks, packages...
                                                                    </div>
                                                                    <span class="btn btn-sm btn-light py-0 px-2 fw-bold text-dark font-size-11 rounded-pill">Login</span>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <!-- 2. Button Color & Animation -->
                                                    <div class="col-xl-6 col-lg-6 col-12">
                                                        <div class="p-3 rounded-3 h-100" style="background: #ffffff; border: 1.5px solid #99f6e4; box-shadow: 0 4px 14px rgba(0,0,0,0.03);">
                                                            <div class="d-flex align-items-center justify-content-between mb-2">
                                                                <label class="fw-bold text-dark mb-0" style="font-size: 15px;">
                                                                    <i class="fa fa-mouse-pointer text-teal me-1"></i> ২. বাটনের কালার ও অ্যানিমেশন (Button Style)
                                                                </label>
                                                                <span class="badge bg-info text-white font-size-11">App & Web Sync</span>
                                                            </div>
                                                            <p class="text-muted small mb-3">
                                                                অর্ডার বাটন ('Order Now' / 'অর্ডার করুন') এবং প্রধান একশন বাটনের রঙ ও অ্যানিমেশন সেট করুন।
                                                            </p>

                                                            @php
                                                                $currentBtnColor = setting('button_color') ?: '#007e8a';
                                                                $currentBtnAnim = setting('button_animation', '1') !== '0';
                                                            @endphp

                                                            <div class="d-flex align-items-center gap-3 mb-3">
                                                                <input type="color" class="form-control form-control-color border-0 p-1 rounded-3 shadow-sm cursor-pointer"
                                                                    id="button_color_picker" style="width: 65px; height: 46px; cursor: pointer;"
                                                                    value="{{ $currentBtnColor }}">
                                                                <div class="input-group">
                                                                    <span class="input-group-text bg-light fw-bold text-muted">HEX</span>
                                                                    <input type="text" class="form-control fw-bold" id="button_color" name="button_color"
                                                                        value="{{ $currentBtnColor }}" style="height: 46px; font-size: 15px; letter-spacing: 0.5px;">
                                                                </div>
                                                            </div>

                                                            <!-- Presets for buttons -->
                                                            <div class="mb-3">
                                                                <small class="fw-bold text-muted d-block mb-2">জনপ্রিয় বাটন কালার প্যালেট:</small>
                                                                <div class="d-flex flex-wrap gap-2" id="button_color_presets">
                                                                    <button type="button" class="btn btn-sm text-white px-2 py-1 preset-btn-color" data-color="#007e8a" style="background: #007e8a; border-radius: 8px;">🟢 রয়েল টিল (ডিফল্ট)</button>
                                                                    <button type="button" class="btn btn-sm text-white px-2 py-1 preset-btn-color" data-color="#ea580c" style="background: #ea580c; border-radius: 8px;">🟠 ব্র্যান্ড অরেঞ্জ</button>
                                                                    <button type="button" class="btn btn-sm text-white px-2 py-1 preset-btn-color" data-color="#0284c7" style="background: #0284c7; border-radius: 8px;">🔵 সাইয়ান ব্লু</button>
                                                                    <button type="button" class="btn btn-sm text-white px-2 py-1 preset-btn-color" data-color="#10b981" style="background: #10b981; border-radius: 8px;">🌿 এমারেল্ড গ্রিন</button>
                                                                    <button type="button" class="btn btn-sm text-white px-2 py-1 preset-btn-color" data-color="#8b5cf6" style="background: #8b5cf6; border-radius: 8px;">🟣 ভাইব্রেন্ট পার্পল</button>
                                                                    <button type="button" class="btn btn-sm text-white px-2 py-1 preset-btn-color" data-color="#e11d48" style="background: #e11d48; border-radius: 8px;">🔴 রুবি রেড</button>
                                                                </div>
                                                            </div>

                                                            <!-- Button Animation Switch -->
                                                            <div class="p-3 rounded-3 mb-3" style="background: #f1f5f9; border: 1px solid #cbd5e1;">
                                                                <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
                                                                    <div>
                                                                        <span class="fw-bold text-dark font-size-14 d-block">
                                                                            <i class="fa fa-magic text-warning me-1"></i> বাটন অ্যানিমেশন (Button Animation)
                                                                        </span>
                                                                        <small class="text-muted">বাটনটি পালস ও শিমার গ্লো অ্যানিমেশন করবে কিনা সেট করুন।</small>
                                                                    </div>
                                                                    <div class="btn-group" role="group">
                                                                        <input type="radio" class="btn-check" name="button_animation" id="anim_on" value="1" {{ $currentBtnAnim ? 'checked' : '' }}>
                                                                        <label class="btn btn-sm btn-outline-success px-3 fw-bold" for="anim_on">
                                                                            <i class="fa fa-play me-1"></i> চালু (ON)
                                                                        </label>

                                                                        <input type="radio" class="btn-check" name="button_animation" id="anim_off" value="0" {{ !$currentBtnAnim ? 'checked' : '' }}>
                                                                        <label class="btn btn-sm btn-outline-danger px-3 fw-bold" for="anim_off">
                                                                            <i class="fa fa-pause me-1"></i> বন্ধ (OFF)
                                                                        </label>
                                                                    </div>
                                                                </div>
                                                            </div>

                                                            <!-- Live Button Preview -->
                                                            <div class="p-3 rounded-3 text-center" style="background: #f8fafc; border: 1.5px dashed #cbd5e1;">
                                                                <small class="text-muted fw-bold d-block mb-2 font-size-11 text-uppercase">লাইভ বাটন প্রিভিউ (Live Preview):</small>
                                                                <button type="button" id="admin_live_preview_btn"
                                                                    class="btn text-white rounded-pill px-4 py-2 fw-bold shadow-sm {{ $currentBtnAnim ? 'admin-preview-pulsing' : '' }}"
                                                                    style="background: {{ $currentBtnColor }}; border-color: {{ $currentBtnColor }}; font-size: 14px; min-width: 200px;">
                                                                    <i class="fa fa-shopping-bag me-1"></i> অর্ডার করুন (Order Now)
                                                                </button>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <!-- 3. Homepage Preloader Switch -->
                                                    <div class="col-xl-6 col-lg-6 col-12">
                                                        <div class="p-3 rounded-3 h-100" style="background: #ffffff; border: 1.5px solid #bfdbfe; box-shadow: 0 4px 14px rgba(0,0,0,0.03);">
                                                            <div class="d-flex align-items-center justify-content-between mb-2">
                                                                <label class="fw-bold text-dark mb-0" style="font-size: 15px;">
                                                                    <i class="fa fa-spinner text-primary me-1"></i> ৩. হোমপেজ লোডিং প্রি-লোডার (Homepage Preloader)
                                                                </label>
                                                                @php
                                                                    $currentPreloader = setting('preloader_status', '1') !== '0';
                                                                @endphp
                                                                <span class="badge {{ $currentPreloader ? 'bg-success' : 'bg-secondary' }}" id="preloader_badge">
                                                                    {{ $currentPreloader ? 'সক্রিয় (Active)' : 'নিষ্ক্রিয় (Disabled)' }}
                                                                </span>
                                                            </div>
                                                            <p class="text-muted small mb-3">
                                                                হোমপেজে প্রবেশের পর যে সিনেমাটিক লোডিং প্রি-লোডার স্ক্রিন শো করে, তা অন বা অফ করে রাখতে পারবেন।
                                                            </p>

                                                            <div class="p-3 rounded-3" style="background: #f8fafc; border: 1px solid #e2e8f0;">
                                                                <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
                                                                    <div>
                                                                        <span class="fw-bold text-dark font-size-14 d-block">প্রি-লোডার প্রদর্শন স্ট্যাটাস</span>
                                                                        <small class="text-muted">অফ করে রাখলে ওয়েবসাইট ও অ্যাপে কোনো লোডিং স্ক্রিন ছাড়াই সরাসরি কন্টেন্ট দ্রুত লোড হবে।</small>
                                                                    </div>
                                                                    <div class="btn-group" role="group">
                                                                        <input type="radio" class="btn-check" name="preloader_status" id="preloader_on" value="1" {{ $currentPreloader ? 'checked' : '' }}>
                                                                        <label class="btn btn-sm btn-outline-success px-3 fw-bold" for="preloader_on">
                                                                            <i class="fa fa-check me-1"></i> অন (ON)
                                                                        </label>

                                                                        <input type="radio" class="btn-check" name="preloader_status" id="preloader_off" value="0" {{ !$currentPreloader ? 'checked' : '' }}>
                                                                        <label class="btn btn-sm btn-outline-secondary px-3 fw-bold" for="preloader_off">
                                                                            <i class="fa fa-times me-1"></i> অফ (OFF)
                                                                        </label>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <!-- 4. Why Choose Us Section Switch -->
                                                    <div class="col-xl-6 col-lg-6 col-12">
                                                        <div class="p-3 rounded-3 h-100" style="background: #ffffff; border: 1.5px solid #fed7aa; box-shadow: 0 4px 14px rgba(0,0,0,0.03);">
                                                            <div class="d-flex align-items-center justify-content-between mb-2">
                                                                <label class="fw-bold text-dark mb-0" style="font-size: 15px;">
                                                                    <i class="fa fa-shield text-warning me-1"></i> ৪. 'Why Choose Us' সেকশন (কেন আমাদের বেছে নেবেন)
                                                                </label>
                                                                @php
                                                                    $currentWhyChoose = setting('why_choose_us_status', '1') !== '0';
                                                                @endphp
                                                                <span class="badge {{ $currentWhyChoose ? 'bg-success' : 'bg-danger' }}" id="why_choose_badge">
                                                                    {{ $currentWhyChoose ? 'শো হচ্ছে (Visible)' : 'হাইড করা (Hidden)' }}
                                                                </span>
                                                            </div>
                                                            <p class="text-muted small mb-3">
                                                                হোমপেজ ও মোবাইল অ্যাপে 'Why Choose ekrishisheba' বেনিফিটস সেকশনটি অন করে রাখলে শো হবে, অফ রাখলে হাইড থাকবে।
                                                            </p>

                                                            <div class="p-3 rounded-3" style="background: #f8fafc; border: 1px solid #e2e8f0;">
                                                                <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
                                                                    <div>
                                                                        <span class="fw-bold text-dark font-size-14 d-block">Why Choose Us প্রদর্শন স্ট্যাটাস</span>
                                                                        <small class="text-muted">অন থাকলে ওয়েবসাইটে ও অ্যাপে সেকশনটি শো করবে, অফ থাকলে সম্পূর্ণ হাইড থাকবে।</small>
                                                                    </div>
                                                                    <div class="btn-group" role="group">
                                                                        <input type="radio" class="btn-check" name="why_choose_us_status" id="why_choose_on" value="1" {{ $currentWhyChoose ? 'checked' : '' }}>
                                                                        <label class="btn btn-sm btn-outline-success px-3 fw-bold" for="why_choose_on">
                                                                            <i class="fa fa-eye me-1"></i> অন (ON)
                                                                        </label>

                                                                        <input type="radio" class="btn-check" name="why_choose_us_status" id="why_choose_off" value="0" {{ !$currentWhyChoose ? 'checked' : '' }}>
                                                                        <label class="btn btn-sm btn-outline-danger px-3 fw-bold" for="why_choose_off">
                                                                            <i class="fa fa-eye-slash me-1"></i> অফ (OFF)
                                                                        </label>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <!-- 5. Coming Soon Mode (শীঘ্রই উদ্বোধন / কামিং সুন মোড) -->
                                                    <div class="col-12">
                                                        <div class="p-3 rounded-3" style="background: linear-gradient(135deg, #090d16 0%, #1e293b 100%); border: 2px solid #007e8a; color: #ffffff; box-shadow: 0 4px 18px rgba(0, 126, 138, 0.2);">
                                                            <div class="d-flex align-items-center justify-content-between flex-wrap gap-2 mb-3 border-bottom border-secondary pb-2">
                                                                <div>
                                                                    <label class="fw-bold text-white mb-0" style="font-size: 16px;">
                                                                        <i class="fa fa-clock-o text-warning me-1"></i> ৫. কামিং সুন / শীঘ্রই উদ্বোধন মোড (Coming Soon Mode)
                                                                    </label>
                                                                    <p class="text-white-50 small mb-0">
                                                                        অন (ON) করে রাখলে সাধারণ ভিজিটররা পূর্ণাঙ্গ কামিং সুন ও কাউন্টডাউন পেজ দেখতে পাবেন। এডমিনরা স্বাভাবিকভাবেই প্যানেলে কাজ করতে পারবেন।
                                                                    </p>
                                                                </div>
                                                                @php
                                                                    $currentComingSoon = setting('coming_soon_status') === '1';
                                                                @endphp
                                                                <div class="d-flex align-items-center gap-2">
                                                                    <a href="{{ route('coming_soon') }}" target="_blank" class="btn btn-xs btn-outline-light px-2 py-1">
                                                                        <i class="fa fa-external-link me-1"></i> প্রিভিউ দেখুন
                                                                    </a>
                                                                    <span class="badge {{ $currentComingSoon ? 'bg-danger' : 'bg-success' }}">
                                                                        {{ $currentComingSoon ? 'কামিং সুন সক্রিয় (ACTIVE)' : 'ওয়েবসাইট লাইভ (LIVE)' }}
                                                                    </span>
                                                                </div>
                                                            </div>

                                                            <div class="row g-3">
                                                                <div class="col-md-3 col-12">
                                                                    <label class="small text-white-50 fw-bold mb-1">মোড অন / অফ (Status):</label>
                                                                    <div class="btn-group w-100" role="group">
                                                                        <input type="radio" class="btn-check" name="coming_soon_status" id="coming_soon_on" value="1" {{ $currentComingSoon ? 'checked' : '' }}>
                                                                        <label class="btn btn-sm btn-outline-danger fw-bold" for="coming_soon_on">
                                                                            <i class="fa fa-clock-o me-1"></i> অন (ON)
                                                                        </label>

                                                                        <input type="radio" class="btn-check" name="coming_soon_status" id="coming_soon_off" value="0" {{ !$currentComingSoon ? 'checked' : '' }}>
                                                                        <label class="btn btn-sm btn-outline-success fw-bold" for="coming_soon_off">
                                                                            <i class="fa fa-check me-1"></i> অফ (OFF)
                                                                        </label>
                                                                    </div>
                                                                </div>

                                                                <div class="col-md-3 col-12">
                                                                    <label class="small text-white-50 fw-bold mb-1">উদ্বোধন তারিখ ও সময় (Target Date):</label>
                                                                    <input type="text" class="form-control form-control-sm bg-dark text-white border-secondary" name="coming_soon_date" value="{{ setting('coming_soon_date') ?: '2026-12-31 00:00:00' }}" placeholder="YYYY-MM-DD HH:MM:SS">
                                                                </div>

                                                                <div class="col-md-3 col-12">
                                                                    <label class="small text-white-50 fw-bold mb-1">টাইটেল (Main Title):</label>
                                                                    <input type="text" class="form-control form-control-sm bg-dark text-white border-secondary" name="coming_soon_title" value="{{ setting('coming_soon_title') ?: 'COMING SOON' }}" placeholder="COMING SOON">
                                                                </div>

                                                                <div class="col-md-3 col-12">
                                                                    <label class="small text-white-50 fw-bold mb-1">সাবটাইটেল (Subtitle):</label>
                                                                    <input type="text" class="form-control form-control-sm bg-dark text-white border-secondary" name="coming_soon_subtitle" value="{{ setting('coming_soon_subtitle') ?: 'আমাদের ওয়েবসাইট ও ডিজিটাল কৃষিপণ্য সেবা খুব শীঘ্রই উদ্বোধন হতে যাচ্ছে!' }}" placeholder="সাবটাইটেল লিখুন...">
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-xl-6">


                                        <div class="form-group pb-4">
                                            <div class="row ">
                                                <label class="col-lg-6 control-label text-lg-start pt-2"
                                                    for="title">Website Title (টাইটেল)</label>
                                                <div class="col-lg-6">
                                                    <input type="text" class="form-control" id="title" name="title"
                                                        title="Enter Site Title" value="{{ setting('title') }}">
                                                    <span class="text-danger">
                                                        @error('title')
                                                        {{ $message }}
                                                        @enderror
                                                    </span>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="form-group pb-4">
                                            <div class="row ">
                                                <label class="col-lg-6 control-label text-lg-start pt-2 text-primary fw-bold"
                                                    for="top_notice"><i class="fa fa-bullhorn text-warning me-1"></i> Top Marquee Notice (শীর্ষ স্ক্রোলিং নোটিশ)</label>
                                                <div class="col-lg-6">
                                                    <textarea class="form-control" id="top_notice" name="top_notice" rows="2"
                                                        placeholder="ওয়েবসাইটের শীর্ষে স্ক্রোল করার নোটিশ বা ঘোষণা লিখুন...">{{ setting('top_notice') ?: (setting('notice') ?: 'কৃষি পরিবারে স্বাগতম! আধুনিক ও নিরাপদ কৃষি বিনিয়োগের নির্ভরযোগ্য ডিজিটাল প্ল্যাটফর্ম।') }}</textarea>
                                                    <small class="text-muted">এই নোটিশটি ওয়েবসাইটের একদম উপরে স্বয়ংক্রিয়ভাবে স্ক্রোল করবে।</small>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- ============================================== -->
                                        <!-- 🧭 Header Navigation & Homepage Sections Control: On/Off & Titles -->
                                        <!-- ============================================== -->
                                        <div id="header_menus_control_card" class="form-group pb-4 p-4 mb-4 rounded-3" style="background: linear-gradient(135deg, #ffffff 0%, #f0fdfa 100%); border: 2.5px solid #007e8a; box-shadow: 0 8px 24px rgba(0, 126, 138, 0.12); border-radius: 16px;">
                                            <div class="d-flex align-items-center justify-content-between mb-3 border-bottom pb-3">
                                                <div class="d-flex align-items-center">
                                                    <span class="avatar avatar-md rounded-circle text-white me-3 d-flex align-items-center justify-content-center shadow-sm" style="background: #007e8a; width: 42px; height: 42px;">
                                                        <i class="fa fa-sliders font-size-18"></i>
                                                    </span>
                                                    <div>
                                                        <h4 class="fw-bold text-dark mb-0 font-size-17">
                                                            হেডার মেনু ও হোমপেজ সেকশন নিয়ন্ত্রণ (Header Navigation & Homepage Sections Control)
                                                        </h4>
                                                        <p class="text-muted small mb-0">প্যাকেজ, স্টক মার্কেট, মেম্বারশিপ কার্ড ও মাসিক বাজার মেনু এবং হোমপেজের সংশ্লিষ্ট সেকশন অন/অফ করুন ও কাস্টম নাম দিন। অফ করলে হেডার মেনু ও হোমপেজ সেকশন উভয়ই সম্পূর্ণ হাইড থাকবে।</p>
                                                    </div>
                                                </div>
                                                <span class="badge bg-success font-size-12 px-3 py-2 rounded-pill">
                                                    <i class="fa fa-sliders me-1"></i> লাইভ কন্ট্রোল
                                                </span>
                                            </div>

                                            <div class="row g-4">
                                                <!-- Menu 1: Our Packages -->
                                                <div class="col-xl-3 col-md-6 col-12">
                                                    <div class="p-3 bg-white rounded-3 border h-100 shadow-sm" style="border-left: 4px solid #ea580c !important;">
                                                        <div class="d-flex align-items-center justify-content-between mb-2">
                                                            <span class="fw-bold text-dark"><i class="fa fa-gift text-warning me-1"></i> ১. আওয়ার প্যাকেজ</span>
                                                            <div class="form-check form-switch m-0">
                                                                <input type="hidden" name="menu_packages_status" value="0">
                                                                <input class="form-check-input cursor-pointer" type="checkbox" name="menu_packages_status" id="menu_packages_status" value="1" {{ setting('menu_packages_status', '1') !== '0' ? 'checked' : '' }}>
                                                                <label class="form-check-label small fw-bold text-{{ setting('menu_packages_status', '1') !== '0' ? 'success' : 'danger' }}" for="menu_packages_status">
                                                                    {{ setting('menu_packages_status', '1') !== '0' ? 'অন' : 'অফ' }}
                                                                </label>
                                                            </div>
                                                        </div>
                                                        <div class="mb-2">
                                                            <label class="form-label small text-muted mb-1 fw-bold">বাংলা নাম (Bangla Title):</label>
                                                            <input type="text" class="form-control form-control-sm fw-bold" name="menu_packages_name_bn" value="{{ setting('menu_packages_name_bn') ?: 'প্যাকেজসমূহ' }}" placeholder="প্যাকেজসমূহ">
                                                        </div>
                                                        <div>
                                                            <label class="form-label small text-muted mb-1 fw-bold">English Name:</label>
                                                            <input type="text" class="form-control form-control-sm fw-bold" name="menu_packages_name_en" value="{{ setting('menu_packages_name_en') ?: 'Our Packages' }}" placeholder="Our Packages">
                                                        </div>
                                                    </div>
                                                </div>

                                                <!-- Menu 2: Stock Market -->
                                                <div class="col-xl-3 col-md-6 col-12">
                                                    <div class="p-3 bg-white rounded-3 border h-100 shadow-sm" style="border-left: 4px solid #007e8a !important;">
                                                        <div class="d-flex align-items-center justify-content-between mb-2">
                                                            <span class="fw-bold text-dark"><i class="fa fa-line-chart text-info me-1"></i> ২. স্টক মার্কেট</span>
                                                            <div class="form-check form-switch m-0">
                                                                <input type="hidden" name="menu_stock_status" value="0">
                                                                <input class="form-check-input cursor-pointer" type="checkbox" name="menu_stock_status" id="menu_stock_status" value="1" {{ setting('menu_stock_status', '1') !== '0' ? 'checked' : '' }}>
                                                                <label class="form-check-label small fw-bold text-{{ setting('menu_stock_status', '1') !== '0' ? 'success' : 'danger' }}" for="menu_stock_status">
                                                                    {{ setting('menu_stock_status', '1') !== '0' ? 'অন' : 'অফ' }}
                                                                </label>
                                                            </div>
                                                        </div>
                                                        <div class="mb-2">
                                                            <label class="form-label small text-muted mb-1 fw-bold">বাংলা নাম (Bangla Title):</label>
                                                            <input type="text" class="form-control form-control-sm fw-bold" name="menu_stock_name_bn" value="{{ setting('menu_stock_name_bn') ?: 'স্টক মার্কেট' }}" placeholder="স্টক মার্কেট">
                                                        </div>
                                                        <div>
                                                            <label class="form-label small text-muted mb-1 fw-bold">English Name:</label>
                                                            <input type="text" class="form-control form-control-sm fw-bold" name="menu_stock_name_en" value="{{ setting('menu_stock_name_en') ?: 'Stock Market' }}" placeholder="Stock Market">
                                                        </div>
                                                    </div>
                                                </div>

                                                <!-- Menu 3: Membership Cards -->
                                                <div class="col-xl-3 col-md-6 col-12">
                                                    <div class="p-3 bg-white rounded-3 border h-100 shadow-sm" style="border-left: 4px solid #3b82f6 !important;">
                                                        <div class="d-flex align-items-center justify-content-between mb-2">
                                                            <span class="fw-bold text-dark"><i class="fa fa-credit-card text-primary me-1"></i> ৩. মেম্বারশিপ কার্ড</span>
                                                            <div class="form-check form-switch m-0">
                                                                <input type="hidden" name="menu_membership_status" value="0">
                                                                <input class="form-check-input cursor-pointer" type="checkbox" name="menu_membership_status" id="menu_membership_status" value="1" {{ setting('menu_membership_status', '1') !== '0' ? 'checked' : '' }}>
                                                                <label class="form-check-label small fw-bold text-{{ setting('menu_membership_status', '1') !== '0' ? 'success' : 'danger' }}" for="menu_membership_status">
                                                                    {{ setting('menu_membership_status', '1') !== '0' ? 'অন' : 'অফ' }}
                                                                </label>
                                                            </div>
                                                        </div>
                                                        <div class="mb-2">
                                                            <label class="form-label small text-muted mb-1 fw-bold">বাংলা নাম (Bangla Title):</label>
                                                            <input type="text" class="form-control form-control-sm fw-bold" name="menu_membership_name_bn" value="{{ setting('menu_membership_name_bn') ?: 'মেম্বারশিপ কার্ড' }}" placeholder="মেম্বারশিপ কার্ড">
                                                        </div>
                                                        <div>
                                                            <label class="form-label small text-muted mb-1 fw-bold">English Name:</label>
                                                            <input type="text" class="form-control form-control-sm fw-bold" name="menu_membership_name_en" value="{{ setting('menu_membership_name_en') ?: 'Membership Cards' }}" placeholder="Membership Cards">
                                                        </div>
                                                    </div>
                                                </div>

                                                <!-- Menu 4: Monthly Bazar -->
                                                <div class="col-xl-3 col-md-6 col-12">
                                                    <div class="p-3 bg-white rounded-3 border h-100 shadow-sm" style="border-left: 4px solid #10b981 !important;">
                                                        <div class="d-flex align-items-center justify-content-between mb-2">
                                                            <span class="fw-bold text-dark"><i class="fa fa-shopping-basket text-success me-1"></i> ৪. মাসিক বাজার (Deals)</span>
                                                            <div class="form-check form-switch m-0">
                                                                <input type="hidden" name="monthly_bazar_status" value="0">
                                                                <input class="form-check-input cursor-pointer" type="checkbox" name="monthly_bazar_status" id="monthly_bazar_status" value="1" {{ setting('monthly_bazar_status', '1') !== '0' ? 'checked' : '' }}>
                                                                <label class="form-check-label small fw-bold text-{{ setting('monthly_bazar_status', '1') !== '0' ? 'success' : 'danger' }}" for="monthly_bazar_status">
                                                                    {{ setting('monthly_bazar_status', '1') !== '0' ? 'অন' : 'অফ' }}
                                                                </label>
                                                            </div>
                                                        </div>
                                                        <p class="small text-muted mb-0">হোমপেজে স্লাইডারের নিচের 'মাসিক বাজার' সুপারশপ ডিল সেকশন প্রদর্শন নিয়ন্ত্রণ করুন। বন্ধ রাখলে হোমপেজ থেকে হাইড থাকবে।</p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- ============================================== -->
                                        <!-- 100 Font Families Selector & Live Font Preview -->
                                        <!-- ============================================== -->
                                        <div id="site_font_family_card" class="form-group pb-4 p-3 mb-4 rounded-3" style="background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%); border: 2px solid #ea580c; box-shadow: 0 6px 20px rgba(234, 88, 12, 0.12); border-radius: 14px;">
                                            <div class="row align-items-center mb-2">
                                                <div class="col-12">
                                                    <div class="d-flex align-items-center justify-content-between mb-2">
                                                        <label class="control-label text-dark fw-bold mb-0" for="site_font_family" style="font-size: 15px;">
                                                            <i class="fa fa-font text-warning me-2"></i> Website Font Family (১০০+ ফন্ট ফ্যামিলি সিলেক্টর)
                                                        </label>
                                                        <span class="badge bg-primary px-3 py-1 font-size-12">১০০ প্রকার ফন্ট উপলভ্য</span>
                                                    </div>
                                                    <p class="text-muted font-size-12 mb-3">
                                                        এডমিন থেকে যে কোনো ফন্ট সিলেক্ট করলে ওয়েবসাইটের বাংলা ও ইংরেজি সমস্ত টাইপোগ্রাফি সাথে সাথে সেই ফন্টে পরিবর্তিত হবে।
                                                    </p>
                                                </div>
                                                <div class="col-12">
                                                    @php
                                                        $currentFont = setting('site_font_family') ?: 'Hind Siliguri';
                                                        $fontList = config('fonts.families', []);
                                                        $groupedFonts = [];
                                                        foreach ($fontList as $fName => $fMeta) {
                                                            $groupedFonts[$fMeta['category']][$fName] = $fMeta;
                                                        }
                                                    @endphp
                                                    <select class="form-select form-control font-select-picker" id="site_font_family" name="site_font_family" style="font-size: 15px; height: 46px; border-radius: 10px; border: 1.5px solid #94a3b8; font-weight: 600;">
                                                        @foreach($groupedFonts as $category => $items)
                                                            <optgroup label="── {{ $category }} ({{ count($items) }}) ──">
                                                                @foreach($items as $fName => $fMeta)
                                                                    <option value="{{ $fName }}" {{ $currentFont === $fName ? 'selected' : '' }} data-query="{{ $fMeta['query'] ?? '' }}" data-fallback="{{ $fMeta['fallback'] }}">
                                                                        {{ $fName }} {{ !empty($fMeta['bengali']) ? '★ [বাংলা সাপোর্টেড]' : '' }}
                                                                    </option>
                                                                @endforeach
                                                            </optgroup>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>

                                            <!-- Live Font Preview Box -->
                                            <div class="mt-3 p-3 rounded-2" id="fontPreviewCard" style="background: #ffffff; border: 1.5px dashed #ea580c; border-radius: 10px;">
                                                <div class="d-flex justify-content-between align-items-center mb-2">
                                                    <small class="text-muted fw-bold font-size-11 text-uppercase">
                                                        <i class="fa fa-eye text-info me-1"></i> লাইভ ফন্ট প্রিভিউ (Live Preview):
                                                    </small>
                                                    <span id="previewFontBadge" class="badge bg-warning text-dark font-size-11">{{ $currentFont }}</span>
                                                </div>
                                                <div id="previewSampleText" style="font-size: 18px; line-height: 1.5; color: #1e293b; transition: font-family 0.2s ease;">
                                                    আমরা আধুনিক ও নিরাপদ কৃষি সেবায় বিশ্বাসী - ekrishisheba Digital Platform 2026
                                                </div>
                                                <div id="previewSampleNumbers" class="text-muted font-size-14 mt-1">
                                                    ১২৩৪৫৬৭৮৯০ / 1234567890 (Aa Bb Cc Dd Ee Ff Gg)
                                                </div>
                                            </div>
                                        </div>


                                        <div class="form-group pb-4">
                                            <div class="row ">
                                                <label class="col-lg-3 control-label text-lg-start pt-2"
                                                    for="logo">Logo</label>
                                                <div class="col-lg-5">
                                                    <input type="file" class="form-control" id="logo" name="logo">
                                                    <span class="text-danger">
                                                        @error('logo')
                                                        {{ $message }}
                                                        @enderror
                                                    </span>
                                                </div>
                                                <div class="col-lg-4">
                                                    <img src="{{ asset('upload/images/backend/logo') }}/{{ setting('logo') }}"
                                                        alt="" style="width:30%; margin-top:5px">
                                                </div>
                                            </div>
                                        </div>

                                        <div class="form-group pb-4">
                                            <div class="row ">
                                                <label class="col-lg-3 control-label text-lg-start pt-2"
                                                    for="favicon">Favicon</label>
                                                <div class="col-lg-5">
                                                    <input type="file" class="form-control" id="favicon" name="favicon">
                                                    <span class="text-danger">
                                                        @error('favicon')
                                                        {{ $message }}
                                                        @enderror
                                                    </span>
                                                </div>
                                                <div class="col-lg-4">
                                                    <img src="{{ asset('upload/images/backend/logo') }}/{{ setting('favicon') }}"
                                                        alt="" style="width:30%; margin-top:5px">
                                                </div>
                                            </div>
                                        </div>

                                        <div class="form-group pb-4">
                                            <div class="row">
                                                <label class="col-lg-3 control-label text-lg-start pt-2"
                                                    for="ssl_payment_logo">
                                                    <i class="fa fa-credit-card text-success me-1"></i> এসএসএল / পেমেন্ট গেটওয়ে ব্যানার (SSL / Payment Banner)
                                                </label>
                                                <div class="col-lg-5">
                                                    <input type="file" class="form-control" id="ssl_payment_logo" name="ssl_payment_logo" accept="image/*">
                                                    <small class="text-muted d-block mt-1">ফুটারের নিচে সম্পূর্ণ অংশ জুড়ে এই এসএসএল ব্যানার লোগো ইমেজটি শো করবে। (PNG, JPG, WebP)</small>
                                                    <span class="text-danger">
                                                        @error('ssl_payment_logo')
                                                        {{ $message }}
                                                        @enderror
                                                    </span>
                                                </div>
                                                <div class="col-lg-4">
                                                    @if(setting('ssl_payment_logo'))
                                                        <img src="{{ asset('upload/images/backend/logo') }}/{{ setting('ssl_payment_logo') }}"
                                                            alt="SSL Banner" style="max-width: 100%; max-height: 55px; object-fit: contain; background: #0f172a; padding: 4px; border-radius: 6px;">
                                                    @else
                                                        <img src="https://securepay.sslcommerz.com/public/image/SSLCommerz-Pay-With-logo-All-Size-01.png"
                                                            alt="SSLCommerz Banner" style="max-width: 100%; max-height: 50px; object-fit: contain; background: #0f172a; padding: 4px; border-radius: 6px;">
                                                        <small class="text-muted d-block mt-1">ডিফল্ট SSLCommerz ব্যানার সক্রিয়</small>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>

                                        <!-- About Corporate Support PDF Upload -->
                                        <div class="form-group pb-4 border-bottom">
                                            <div class="row align-items-center">
                                                <label class="col-lg-3 control-label text-lg-start pt-2 fw-bold"
                                                    for="corporate_support_pdf">
                                                    <i class="fa fa-file-pdf-o text-danger me-1"></i> অ্যাবাউট কর্পোরেট সাপোর্ট PDF (Corporate Support PDF)
                                                </label>
                                                <div class="col-lg-5">
                                                    <input type="file" class="form-control" id="corporate_support_pdf" name="corporate_support_pdf" accept="application/pdf">
                                                    <small class="text-muted d-block mt-1">ফুটারে "About Corporate Support" এ ক্লিক করলে এই PDF ডকুমেন্টটি ভিউয়ারে ওপেন হবে। (শুধুমাত্র .PDF ফাইল, সর্বোচ্চ 10MB)</small>
                                                    <span class="text-danger">
                                                        @error('corporate_support_pdf')
                                                        {{ $message }}
                                                        @enderror
                                                    </span>
                                                </div>
                                                <div class="col-lg-4">
                                                    @if(setting('corporate_support_pdf'))
                                                        <div class="d-flex align-items-center gap-2 mb-1">
                                                            <a href="{{ route('corporate.support.pdf') }}" target="_blank" class="btn btn-sm btn-outline-danger">
                                                                <i class="fa fa-file-pdf-o me-1"></i> বর্তমান PDF দেখুন (View PDF)
                                                            </a>
                                                            <span class="badge bg-success">আপলোড করা আছে</span>
                                                        </div>
                                                        <small class="text-muted d-block">{{ setting('corporate_support_pdf') }}</small>
                                                    @else
                                                        <a href="{{ route('corporate.support.pdf') }}" target="_blank" class="btn btn-sm btn-outline-secondary">
                                                            <i class="fa fa-file-pdf-o me-1"></i> ডিফল্ট PDF দেখুন
                                                        </a>
                                                        <small class="text-muted d-block mt-1">কাস্টম PDF দিলে এটি প্রতিস্থাপিত হবে</small>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>









                                        <div class="form-group pb-4">
                                            <div class="row ">
                                                <label class="col-lg-5 control-label text-lg-start pt-2"
                                                    for="address1">Address Line one</label>
                                                <div class="col-lg-7">
                                                    <input type="text" class="form-control" id="address1"
                                                        name="address1" title="Enter Address"
                                                        value="{{ setting('address1') }}">
                                                    <span class="text-danger">
                                                        @error('address1')
                                                        {{ $message }}
                                                        @enderror
                                                    </span>
                                                </div>
                                            </div>
                                        </div>




                                        <div class="form-group pb-4">
                                            <div class="row ">
                                                <label class="col-lg-5 control-label text-lg-start pt-2"
                                                    for="address2">Address Line tow</label>
                                                <div class="col-lg-7">
                                                    <input type="text" class="form-control" id="address2"
                                                        name="address2" title="Enter Address"
                                                        value="{{ setting('address2') }}">
                                                    <span class="text-danger">
                                                        @error('address2')
                                                        {{ $message }}
                                                        @enderror
                                                    </span>
                                                </div>
                                            </div>
                                        </div>




                                        <div class="form-group pb-4">
                                            <div class="row ">
                                                <label class="col-lg-5 control-label text-lg-start pt-2"
                                                    for="phone1">Phone one</label>
                                                <div class="col-lg-7">
                                                    <input type="text" class="form-control" id="phone1"
                                                        name="phone1" title="Enter Phone one"
                                                        value="{{ setting('phone1') }}">
                                                    <span class="text-danger">
                                                        @error('phone1')
                                                        {{ $message }}
                                                        @enderror
                                                    </span>
                                                </div>
                                            </div>
                                        </div>




                                        <div class="form-group pb-4">
                                            <div class="row ">
                                                <label class="col-lg-5 control-label text-lg-start pt-2"
                                                    for="facbook_link">Facebook Link</label>
                                                <div class="col-lg-7">
                                                    <input type="text" class="form-control" id="facbook_link"
                                                        name="facbook_link" title="Facebook Link"
                                                        value="{{ setting('facbook_link') }}">
                                                    <span class="text-danger">
                                                        @error('facbook_link')
                                                        {{ $message }}
                                                        @enderror
                                                    </span>
                                                </div>
                                            </div>
                                        </div>


                                        <div class="form-group pb-4">
                                            <div class="row ">
                                                <label class="col-lg-5 control-label text-lg-start pt-2"
                                                    for="linkedin_link">Linkedin Link</label>
                                                <div class="col-lg-7">
                                                    <input type="text" class="form-control" id="linkedin_link"
                                                        name="linkedin_link" title="Enter Phone one"
                                                        value="{{ setting('linkedin_link') }}">
                                                    <span class="text-danger">
                                                        @error('linkedin_link')
                                                        {{ $message }}
                                                        @enderror
                                                    </span>
                                                </div>
                                            </div>
                                        </div>


                                        <div class="form-group pb-4">
                                            <div class="row ">
                                                <label class="col-lg-5 control-label text-lg-start pt-2"
                                                    for="twitter_link">Twitter Link</label>
                                                <div class="col-lg-7">
                                                    <input type="text" class="form-control" id="twitter_link"
                                                        name="twitter_link" title="Enter Phone one"
                                                        value="{{ setting('twitter_link') }}">
                                                    <span class="text-danger">
                                                        @error('twitter_link')
                                                        {{ $message }}
                                                        @enderror
                                                    </span>
                                                </div>
                                            </div>
                                        </div>


                                    </div>
                                    <!-- /.col -->









                                    <div class="col-xl-6">
                                        <div class="form-group pb-4">
                                            <div class="row ">
                                                <label class="col-lg-4 control-label text-lg-start pt-2"
                                                    for="phone2">Phone tow</label>
                                                <div class="col-lg-7">
                                                    <input type="text" class="form-control" id="phone2"
                                                        name="phone2" title="Enter Phone tow"
                                                        value="{{ setting('phone2') }}">
                                                    <span class="text-danger">
                                                        @error('phone2')
                                                        {{ $message }}
                                                        @enderror
                                                    </span>
                                                </div>
                                            </div>
                                        </div>








                                        <div class="form-group pb-4">
                                            <div class="row ">
                                                <label class="col-lg-4 control-label text-lg-start pt-2"
                                                    for="email1">Email Address one</label>
                                                <div class="col-lg-7">
                                                    <input type="text" class="form-control" id="email1"
                                                        name="email1" title="Enter Email Address"
                                                        value="{{ setting('email1') }}">
                                                    <span class="text-danger">
                                                        @error('email1')
                                                        {{ $message }}
                                                        @enderror
                                                    </span>
                                                </div>
                                            </div>
                                        </div>






                                        <div class="form-group pb-4">
                                            <div class="row ">
                                                <label class="col-lg-4 control-label text-lg-start pt-2"
                                                    for="email2">Enter Address</label>
                                                <div class="col-lg-7">
                                                    <input type="text" class="form-control" id="email2"
                                                        name="email2" title="Enter eMAIL Address"
                                                        value="{{ setting('email2') }}">
                                                    <span class="text-danger">
                                                        @error('email2')
                                                        {{ $message }}
                                                        @enderror
                                                    </span>
                                                </div>
                                            </div>
                                        </div>


                                        <div class="form-group pb-4">
                                            <div class="row ">
                                                <label class="col-lg-4 control-label text-lg-start pt-2"
                                                    for="about_us_text">About text</label>
                                                <div class="col-lg-7">
                                                    <input type="text" class="form-control" id="about_us_text"
                                                        name="about_us_text" title="About text"
                                                        value="{{ setting('about_us_text') }}">
                                                    <span class="text-danger">
                                                        @error('about_us_text')
                                                        {{ $message }}
                                                        @enderror
                                                    </span>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="form-group pb-4">
                                            <div class="row ">
                                                <label class="col-lg-4 control-label text-lg-start pt-2"
                                                    for="about_us_text2">About text tow</label>
                                                <div class="col-lg-7">
                                                    <input type="text" class="form-control" id="about_us_text2"
                                                        name="about_us_text2" title="About text tow"
                                                        value="{{ setting('about_us_text2') }}">
                                                    <span class="text-danger">
                                                        @error('about_us_text2')
                                                        {{ $message }}
                                                        @enderror
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- /.col (right column) -->

                                    <!-- ============================================== -->
                                    <!-- Google Map Embed Location Setting Card -->
                                    <!-- ============================================== -->
                                    <div id="google_map_card" class="col-12 my-3">
                                        <div class="card border-2 shadow-sm rounded-4" style="border: 2px solid #ea580c !important; border-radius: 14px; background: linear-gradient(135deg, #ffffff 0%, #fffbf5 100%);">
                                            <div class="card-header bg-white py-3 px-4 d-flex align-items-center justify-content-between border-bottom" style="border-radius: 14px 14px 0 0;">
                                                <div class="d-flex align-items-center">
                                                    <div class="rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 44px; height: 44px; min-width: 44px; background: #fee2e2; color: #dc2626;">
                                                        <i class="fa fa-map-marker fs-4"></i>
                                                    </div>
                                                    <div>
                                                        <h5 class="fw-bold mb-1 text-dark">
                                                            গুগল ম্যাপ লোকেশন সেটিংস (Google Map Embed Location)
                                                        </h5>
                                                        <p class="text-muted mb-0 font-size-12">
                                                            কন্টাক্ট পেইজে (Contact Us) আপনার অফিসের সুনির্দিষ্ট লোকেশন ম্যাপে প্রদর্শনের জন্য এখানে গুগল ম্যাপের এমবেড কোড (Embed HTML Code) দিন।
                                                        </p>
                                                    </div>
                                                </div>
                                                <span class="badge bg-danger px-3 py-2 font-size-12">
                                                    <i class="fa fa-globe me-1"></i> কন্টাক্ট পেইজ ম্যাপ
                                                </span>
                                            </div>

                                            <div class="card-body p-4">
                                                <div class="row g-4 align-items-start">
                                                    <div class="col-lg-7">
                                                        <label class="form-label fw-bold text-dark mb-2" for="google_map_embed">
                                                            <i class="fa fa-code text-danger me-1"></i> গুগল ম্যাপ এমবেড কোড (Iframe Embed Code / Map URL):
                                                        </label>
                                                        <textarea
                                                            class="form-control font-monospace"
                                                            id="google_map_embed"
                                                            name="google_map_embed"
                                                            rows="5"
                                                            placeholder='<iframe src="https://www.google.com/maps/embed?pb=..." width="600" height="450" style="border:0;" allowfullscreen="" loading="lazy"></iframe>'
                                                            style="font-size: 13px; line-height: 1.5; border: 1.5px solid #cbd5e1; border-radius: 10px;"
                                                        >{{ setting('google_map_embed') }}</textarea>

                                                        <div class="mt-3 p-3 rounded-3" style="background: #f8fafc; border: 1px dashed #cbd5e1;">
                                                            <h6 class="fw-bold text-slate-800 mb-2 font-size-13">
                                                                <i class="fa fa-question-circle text-primary me-1"></i> গুগল ম্যাপ থেকে এমবেড কোড পাওয়ার নিয়ম:
                                                            </h6>
                                                            <ol class="mb-0 text-muted font-size-12 ps-3" style="line-height: 1.8;">
                                                                <li><a href="https://www.google.com/maps" target="_blank" class="fw-bold text-primary">Google Maps (maps.google.com)</a> ওয়েবসাইটে গিয়ে আপনার অফিস/প্রতিষ্ঠানের ঠিকানা সার্চ করুন।</li>
                                                                <li>লোকেশন আসার পর <strong>Share (শেয়ার)</strong> বাটনে ক্লিক করুন।</li>
                                                                <li>পপ-আপ থেকে <strong>Embed a map (ম্যাপ এমবেড করুন)</strong> ট্যাবে যান।</li>
                                                                <li><strong>COPY HTML</strong> বাটনে ক্লিক করে কোডটি কপি করুন এবং উপরের বক্সে পেস্ট করে নিচে <strong>Update Settings</strong> চাপুন।</li>
                                                            </ol>
                                                        </div>
                                                    </div>

                                                    <div class="col-lg-5">
                                                        <label class="form-label fw-bold text-dark mb-2">
                                                            <i class="fa fa-eye text-success me-1"></i> লাইভ ম্যাপ প্রিভিউ (Live Map Preview):
                                                        </label>
                                                        <div id="admin_map_preview_box" class="rounded-3 border overflow-hidden" style="min-height: 250px; height: 250px; background: #f8fafc; position: relative;">
                                                            <div class="d-flex flex-column align-items-center justify-content-center h-100 p-4 text-center text-muted">
                                                                <i class="fa fa-map-o fs-1 text-secondary mb-2"></i>
                                                                <span>লোকেশন লোড হচ্ছে...</span>
                                                            </div>
                                                        </div>
                                                        <small class="text-muted d-block mt-2">
                                                            বামে কোড পেস্ট করলে এখানে সরাসরি লাইভ প্রিভিউ দেখতে পাবেন।
                                                        </small>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-12 my-3">
                                        <hr>
                                        <h4 class="text-primary fw-bold mb-3"><i class="fa fa-sliders me-2"></i> Footer & Branding Settings (ফুটার ও ব্র্যান্ডিং সেটিংস)</h4>
                                    </div>

                                    <div class="col-xl-6 mb-3">
                                        <div class="form-group pb-3">
                                            <label class="form-label fw-bold" for="footer_about">Footer About Text (ফুটারে কোম্পানি পরিচিতি)</label>
                                            <textarea class="form-control" id="footer_about" name="footer_about" rows="3" placeholder="ফুটার সম্পর্কে টেক্সট...">{{ setting('footer_about', 'কৃষি পরিবার - আপনার কৃষি সেবা, স্টকের সঠিক মূল্য এবং আর্থিক সমৃদ্ধির নির্ভরযোগ্য ডিজিটাল মাধ্যম।') }}</textarea>
                                        </div>
                                    </div>

                                    <div class="col-xl-6 mb-3">
                                        <div class="form-group pb-3">
                                            <label class="form-label fw-bold" for="footer_copyright">Footer Copyright Text (কপিরাইট টেক্সট)</label>
                                            <input type="text" class="form-control" id="footer_copyright" name="footer_copyright" value="{{ setting('footer_copyright', '© '.date('Y').' iKrishiPoribar. All Rights Reserved.') }}" placeholder="© 2026 iKrishiPoribar. All Rights Reserved.">
                                        </div>
                                    </div>

                                    <div class="col-xl-6 mb-3">
                                        <div class="form-group pb-3">
                                            <label class="form-label fw-bold" for="footer_developed_by">Developed By / Sub-tagline (ডেভেলপড বাই / সাব-ট্যাগলাইন)</label>
                                            <input type="text" class="form-control" id="footer_developed_by" name="footer_developed_by" value="{{ setting('footer_developed_by', 'Smart Agriculture & Stock Market Solutions') }}" placeholder="Developed by or Tagline">
                                        </div>
                                    </div>

                                    <div class="col-xl-6 mb-3">
                                        <div class="form-group pb-3">
                                            <label class="form-label fw-bold" for="footer_developed_by_url">Developed By Link URL (ডেভেলপারের ওয়েবসাইট/লিঙ্ক)</label>
                                            <input type="url" class="form-control" id="footer_developed_by_url" name="footer_developed_by_url" value="{{ setting('footer_developed_by_url', '') }}" placeholder="https://yourwebsite.com">
                                        </div>
                                    </div>

                                    <div class="col-xl-6 mb-3">
                                        <div class="form-group pb-3">
                                            <label class="form-label fw-bold" for="instagram_link">Instagram Link</label>
                                            <input type="url" class="form-control" id="instagram_link" name="instagram_link" value="{{ setting('instagram_link', '') }}" placeholder="https://instagram.com/...">
                                        </div>
                                    </div>

                                    <div class="col-xl-6 mb-3">
                                        <div class="form-group pb-3">
                                            <label class="form-label fw-bold" for="youtube_link">YouTube Link</label>
                                            <input type="url" class="form-control" id="youtube_link" name="youtube_link" value="{{ setting('youtube_link', '') }}" placeholder="https://youtube.com/...">
                                        </div>
                                    </div>

                                    <div class="col-12 my-3">
                                        <hr>
                                        <h4 class="text-primary fw-bold mb-3"><i class="fa fa-file-text-o me-2"></i> Page Content Settings (এবাউট, প্রাইভেসি ও টার্মস সেটিংস)</h4>
                                    </div>

                                    <div class="col-xl-12 mb-3">
                                        <div class="form-group pb-3">
                                            <label class="form-label fw-bold" for="about_us_full_text">About Us Details (আমাদের সম্পর্কে বিস্তারিত)</label>
                                            <textarea class="form-control editor" id="about_us_full_text" name="about_us_full_text" rows="5" placeholder="Enter About Us details content...">{{ setting('about_us_full_text') }}</textarea>
                                        </div>
                                    </div>

                                    <div class="col-xl-12 mb-3">
                                        <div class="form-group pb-3">
                                            <label class="form-label fw-bold" for="terms_conditions">Terms & Conditions (টার্মস এন্ড কন্ডিশনস)</label>
                                            <textarea class="form-control editor" id="terms_conditions" name="terms_conditions" rows="6" placeholder="Enter Terms & Conditions content...">{{ setting('terms_conditions') }}</textarea>
                                        </div>
                                    </div>

                                    <div class="col-xl-12 mb-3">
                                        <div class="form-group pb-3">
                                            <label class="form-label fw-bold" for="privacy_policy">Privacy Policy (প্রাইভেসি পলিসি)</label>
                                            <textarea class="form-control editor" id="privacy_policy" name="privacy_policy" rows="6" placeholder="Enter Privacy Policy content...">{{ setting('privacy_policy') }}</textarea>
                                        </div>
                                    </div>

                                    <div class="col-12">
                                        <div class="form-group pb-4 text-center">
                                            <button type="submit" class="btn btn-success btn-lg px-5"><i class="fa fa-save me-2"></i> Update Settings</button>
                                        </div>
                                    </div>


                                </div>
                                <!-- /.row -->
                            </form>
                        </div>
                        <!-- /.box-body -->
                    </div>
                    <!-- /.box -->

                </div>
                <!-- /.col -->


            </div>
        </section>
        <!-- /.content -->

    </div>


</div>
@endsection

@section('script')
<link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.js"></script>
<script type="text/javascript">
    $(document).ready(function() {
        $('.editor').summernote({
            height: 220,
            toolbar: [
                ['style', ['style']],
                ['font', ['bold', 'underline', 'italic', 'clear']],
                ['color', ['color']],
                ['para', ['ul', 'ol', 'paragraph']],
                ['table', ['table']],
                ['insert', ['link']],
                ['view', ['fullscreen', 'codeview']]
            ]
        });

        // Interactive Font Family Live Preview Handlers
        var fontSelect = document.getElementById('site_font_family');
        var previewBox = document.getElementById('previewSampleText');
        var previewNumbers = document.getElementById('previewSampleNumbers');
        var previewBadge = document.getElementById('previewFontBadge');

        function updateFontPreview(fontName) {
            if (!fontName || !fontSelect) return;
            var opt = fontSelect.querySelector('option[value="' + fontName + '"]');
            var query = opt ? opt.getAttribute('data-query') : null;
            var fallback = opt ? opt.getAttribute('data-fallback') : 'sans-serif';

            if (query) {
                var linkId = 'dyn-preview-' + fontName.replace(/[^a-zA-Z0-9]/g, '');
                if (!document.getElementById(linkId)) {
                    var link = document.createElement('link');
                    link.id = linkId;
                    link.rel = 'stylesheet';
                    link.href = 'https://fonts.googleapis.com/css2?' + query + '&display=swap';
                    document.head.appendChild(link);
                }
            }

            var fullFont = "'" + fontName + "', 'Hind Siliguri', " + fallback;
            if (previewBox) previewBox.style.fontFamily = fullFont;
            if (previewNumbers) previewNumbers.style.fontFamily = fullFont;
            if (previewBadge) previewBadge.textContent = fontName;
        }

        if (fontSelect) {
            fontSelect.addEventListener('change', function () {
                updateFontPreview(this.value);
            });
            updateFontPreview(fontSelect.value);
        }

        // Live Google Map Preview Handler
        var mapTextarea = document.getElementById('google_map_embed');
        var mapPreviewBox = document.getElementById('admin_map_preview_box');

        function renderAdminMapPreview() {
            if (!mapTextarea || !mapPreviewBox) return;
            var val = mapTextarea.value.trim();
            if (!val) {
                var defAddr = '{{ urlencode(setting("address1") ?: "Plot #45, Probal Tower, Ring Road, Mohammadpur, Dhaka- 1207") }}';
                mapPreviewBox.innerHTML = '<iframe src="https://maps.google.com/maps?q=' + defAddr + '&t=&z=13&ie=UTF8&iwloc=&output=embed" width="100%" height="100%" style="border:0; width:100%; height:100%; min-height:250px;" allowfullscreen="" loading="lazy"></iframe>';
                return;
            }
            if (val.indexOf('<iframe') !== -1) {
                // Ensure width and height are 100%
                var cleanHtml = val.replace(/width="[^"]*"/gi, 'width="100%"').replace(/height="[^"]*"/gi, 'height="100%"');
                mapPreviewBox.innerHTML = cleanHtml;
                var ifr = mapPreviewBox.querySelector('iframe');
                if (ifr) {
                    ifr.style.width = '100%';
                    ifr.style.height = '100%';
                    ifr.style.minHeight = '250px';
                    ifr.style.border = '0';
                }
            } else if (val.indexOf('http://') === 0 || val.indexOf('https://') === 0) {
                mapPreviewBox.innerHTML = '<iframe src="' + val + '" width="100%" height="100%" style="border:0; width:100%; height:100%; min-height:250px;" allowfullscreen="" loading="lazy"></iframe>';
            } else {
                mapPreviewBox.innerHTML = '<iframe src="https://maps.google.com/maps?q=' + encodeURIComponent(val) + '&t=&z=13&ie=UTF8&iwloc=&output=embed" width="100%" height="100%" style="border:0; width:100%; height:100%; min-height:250px;" allowfullscreen="" loading="lazy"></iframe>';
            }
        }

        if (mapTextarea) {
            renderAdminMapPreview();
            mapTextarea.addEventListener('input', renderAdminMapPreview);
        }

        // ==============================================================
        // 🎨 Theme & Display Control Handlers (Header, Button, Animation, Switches)
        // ==============================================================
        var headerColorPicker = document.getElementById('header_bg_color_picker');
        var headerColorHex = document.getElementById('header_bg_color');
        var headerPreviewBox = document.getElementById('admin_header_preview_box');

        function setHeaderColor(color) {
            if (!color) return;
            if (headerColorPicker) headerColorPicker.value = color;
            if (headerColorHex) headerColorHex.value = color;
            if (headerPreviewBox) headerPreviewBox.style.background = color;
        }

        if (headerColorPicker) {
            headerColorPicker.addEventListener('input', function () {
                setHeaderColor(this.value);
            });
        }
        if (headerColorHex) {
            headerColorHex.addEventListener('input', function () {
                var val = this.value.trim();
                if (val.charAt(0) !== '#') val = '#' + val;
                if (/^#[0-9A-Fa-f]{6}$/.test(val)) {
                    if (headerColorPicker) headerColorPicker.value = val;
                    if (headerPreviewBox) headerPreviewBox.style.background = val;
                }
            });
        }
        document.querySelectorAll('.preset-color-btn').forEach(function (btn) {
            btn.addEventListener('click', function () {
                var col = this.getAttribute('data-color');
                setHeaderColor(col);
            });
        });

        // Button Color & Animation
        var btnColorPicker = document.getElementById('button_color_picker');
        var btnColorHex = document.getElementById('button_color');
        var livePreviewBtn = document.getElementById('admin_live_preview_btn');

        function setButtonColor(color) {
            if (!color) return;
            if (btnColorPicker) btnColorPicker.value = color;
            if (btnColorHex) btnColorHex.value = color;
            if (livePreviewBtn) {
                livePreviewBtn.style.backgroundColor = color;
                livePreviewBtn.style.borderColor = color;
            }
        }

        if (btnColorPicker) {
            btnColorPicker.addEventListener('input', function () {
                setButtonColor(this.value);
            });
        }
        if (btnColorHex) {
            btnColorHex.addEventListener('input', function () {
                var val = this.value.trim();
                if (val.charAt(0) !== '#') val = '#' + val;
                if (/^#[0-9A-Fa-f]{6}$/.test(val)) {
                    if (btnColorPicker) btnColorPicker.value = val;
                    if (livePreviewBtn) {
                        livePreviewBtn.style.backgroundColor = val;
                        livePreviewBtn.style.borderColor = val;
                    }
                }
            });
        }
        document.querySelectorAll('.preset-btn-color').forEach(function (btn) {
            btn.addEventListener('click', function () {
                var col = this.getAttribute('data-color');
                setButtonColor(col);
            });
        });

        // Button Animation Radio
        $('input[name="button_animation"]').on('change', function () {
            if ($(this).val() === '1') {
                if (livePreviewBtn) livePreviewBtn.classList.add('admin-preview-pulsing');
            } else {
                if (livePreviewBtn) livePreviewBtn.classList.remove('admin-preview-pulsing');
            }
        });

        // Preloader Badge Update
        $('input[name="preloader_status"]').on('change', function () {
            var b = document.getElementById('preloader_badge');
            if (!b) return;
            if ($(this).val() === '1') {
                b.className = 'badge bg-success';
                b.textContent = 'সক্রিয় (Active)';
            } else {
                b.className = 'badge bg-secondary';
                b.textContent = 'নিষ্ক্রিয় (Disabled)';
            }
        });

        // Why Choose Us Badge Update
        $('input[name="why_choose_us_status"]').on('change', function () {
            var b = document.getElementById('why_choose_badge');
            if (!b) return;
            if ($(this).val() === '1') {
                b.className = 'badge bg-success';
                b.textContent = 'শো হচ্ছে (Visible)';
            } else {
                b.className = 'badge bg-danger';
                b.textContent = 'হাইড করা (Hidden)';
            }
        });
    });
</script>
<style>
@keyframes adminBtnPulse {
    0% { transform: scale(1); box-shadow: 0 0 0 0 rgba(0, 126, 138, 0.7); }
    70% { transform: scale(1.05); box-shadow: 0 0 0 10px rgba(0, 126, 138, 0); }
    100% { transform: scale(1); box-shadow: 0 0 0 0 rgba(0, 126, 138, 0); }
}
.admin-preview-pulsing {
    animation: adminBtnPulse 2s infinite ease-in-out !important;
}
.cursor-pointer {
    cursor: pointer !important;
}
</style>
@endsection
