@php
    $siteLang = request()->cookie('ekrishi_lang', 'bn');
    $isBn = ($siteLang === 'bn');
@endphp

@extends('layouts.frontend.app')

@section('content')
<div class="investor-program-page" style="background: #f8fafc; min-height: 85vh; padding: 40px 0 70px;">
    <div class="container">

        <!-- Top Breadcrumb -->
        <nav aria-label="breadcrumb" class="mb-4">
            <ol class="breadcrumb" style="background: transparent; padding: 0; font-size: 14px;">
                <li class="breadcrumb-item"><a href="{{ route('/') }}" class="text-decoration-none text-muted"><i class="fa fa-home me-1"></i>{{ $isBn ? 'হোম' : 'Home' }}</a></li>
                <li class="breadcrumb-item"><a href="#" class="text-decoration-none text-muted">{{ $isBn ? 'বিজনেস' : 'Business' }}</a></li>
                <li class="breadcrumb-item active fw-bold text-dark" aria-current="page">{{ $isBn ? 'ইনভেস্টর আবেদন' : 'Investor Program' }}</li>
            </ol>
        </nav>

        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show rounded-3 shadow-sm border-0 mb-4 p-4" style="background: #ecfdf5; border-left: 5px solid #10b981 !important;">
                <div class="d-flex align-items-center">
                    <span class="avatar avatar-md rounded-circle text-white me-3 d-flex align-items-center justify-content-center" style="background: #10b981; width: 44px; height: 44px; min-width: 44px;">
                        <i class="fa fa-check fs-5"></i>
                    </span>
                    <div>
                        <h5 class="fw-bold text-dark mb-1">{{ $isBn ? 'আবেদন সফল হয়েছে!' : 'Application Submitted!' }}</h5>
                        <p class="mb-0 text-muted">{{ session('success') }}</p>
                    </div>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <div class="row g-4 align-items-start">
            <!-- Left Column: Form -->
            <div class="col-12 col-lg-7">
                <div class="card border-0 shadow-sm rounded-4 overflow-hidden" style="background: #ffffff; border: 1px solid #e2e8f0;">
                    
                    <!-- Dark Header Header -->
                    <div class="p-4 text-white" style="background: linear-gradient(135deg, #091e3a 0%, #102a4e 100%);">
                        <span class="badge px-3 py-1 rounded-pill mb-2 fw-bold" style="background: #f59e0b; color: #000; font-size: 11px; letter-spacing: 0.5px;">
                            ★ Exclusive Program
                        </span>
                        <h3 class="fw-bold mb-1 text-white" style="font-size: 24px;">
                            <i class="fa fa-line-chart text-warning me-2"></i>Investment আবেদন ফর্ম
                        </h3>
                        <p class="small text-white-50 mb-0">নিয়ম অনুযায়ী সঠিকভাবে পূরণ করুন, আমাদের টিম আপনার সাথে যোগাযোগ করবে</p>
                    </div>

                    <div class="card-body p-4 p-md-5">
                        <form action="{{ route('business.investor.apply') }}" method="POST">
                            @csrf

                            <!-- Full Name -->
                            <div class="mb-3">
                                <label class="form-label fw-bold text-dark small mb-1">
                                    <i class="fa fa-user text-muted me-1"></i> আপনার নাম <span class="text-danger">*</span>
                                </label>
                                <input type="text" name="name" class="form-control form-control-lg rounded-3 @error('name') is-invalid @enderror" value="{{ old('name') }}" placeholder="পূর্ণ নাম লিখুন" required style="font-size: 15px; border-color: #cbd5e1;">
                                @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>

                            <!-- Phone Number -->
                            <div class="mb-3">
                                <label class="form-label fw-bold text-dark small mb-1">
                                    <i class="fa fa-phone text-muted me-1"></i> মোবাইল নম্বর <span class="text-danger">*</span>
                                </label>
                                <input type="tel" name="phone" class="form-control form-control-lg rounded-3 @error('phone') is-invalid @enderror" value="{{ old('phone') }}" placeholder="01XXXXXXXXX" required style="font-size: 15px; border-color: #cbd5e1;">
                                @error('phone')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>

                            <!-- Address -->
                            <div class="mb-3">
                                <label class="form-label fw-bold text-dark small mb-1">
                                    <i class="fa fa-map-marker text-muted me-1"></i> আপনার ঠিকানা <span class="text-danger">*</span>
                                </label>
                                <textarea name="address" rows="2" class="form-control rounded-3 @error('address') is-invalid @enderror" placeholder="বিস্তারিত ঠিকানা লিখুন (বাসা/রোড, থানা, জেলা)" required style="font-size: 14px; border-color: #cbd5e1;">{{ old('address') }}</textarea>
                                @error('address')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>

                            <!-- Profession -->
                            <div class="mb-4">
                                <div class="d-flex align-items-center justify-content-between">
                                    <label class="form-label fw-bold text-dark small mb-1">
                                        <i class="fa fa-briefcase text-muted me-1"></i> পেশা
                                    </label>
                                    <span class="badge bg-light text-muted fw-normal" style="font-size: 11px;">Optional</span>
                                </div>
                                <input type="text" name="profession" class="form-control rounded-3" value="{{ old('profession') }}" placeholder="যেমন: ব্যবসায়ী, চাকুরীজীবী, প্রবাসী" style="font-size: 14px; border-color: #cbd5e1;">
                            </div>

                            <!-- Investment Amount Tiles -->
                            <div class="mb-4">
                                <label class="form-label fw-bold text-dark small mb-2 d-block">
                                    <i class="fa fa-money text-success me-1"></i> বিনিয়োগের পরিমাণ <span class="text-danger">*</span>
                                </label>
                                <div class="row g-2">
                                    @php
                                        $tiers = [
                                            '১ থেকে ২ লাখ',
                                            '২ থেকে ৫ লাখ',
                                            '৫ থেকে ৭ লাখ',
                                            '৭ থেকে ১০ লাখ',
                                            '১০ লাখের বেশি'
                                        ];
                                    @endphp
                                    @foreach($tiers as $key => $tier)
                                        <div class="col-6 col-md-4">
                                            <input type="radio" class="btn-check" name="amount" id="amount_{{ $key }}" value="{{ $tier }}" {{ (old('amount') == $tier || $key == 0) ? 'checked' : '' }} required>
                                            <label class="btn btn-outline-secondary w-100 py-2 rounded-3 text-start d-flex align-items-center justify-content-between investor-amount-card" for="amount_{{ $key }}" style="border-color: #cbd5e1; font-size: 13px; font-weight: 600;">
                                                <span>{{ $tier }}</span>
                                                <i class="fa fa-check-circle-o text-muted check-icon"></i>
                                            </label>
                                        </div>
                                    @endforeach
                                </div>
                            </div>

                            <!-- Remarks -->
                            <div class="mb-4">
                                <div class="d-flex align-items-center justify-content-between">
                                    <label class="form-label fw-bold text-dark small mb-1">
                                        <i class="fa fa-comment-o text-muted me-1"></i> মন্তব্য
                                    </label>
                                    <span class="badge bg-light text-muted fw-normal" style="font-size: 11px;">Optional</span>
                                </div>
                                <textarea name="remarks" rows="2" class="form-control rounded-3" placeholder="আপনার কোনো বিশেষ মতামত বা বক্তব্য থাকলে লিখুন..." style="font-size: 14px; border-color: #cbd5e1;">{{ old('remarks') }}</textarea>
                            </div>

                            <!-- Submit & WhatsApp Action Buttons -->
                            <div class="d-flex flex-wrap gap-2 pt-2">
                                @if(setting('phone1'))
                                    <a href="https://wa.me/88{{ preg_replace('/[^0-9]/', '', setting('phone1')) }}?text={{ urlencode('Hello, I am interested in the Investor Program of ekrishisheba.') }}" target="_blank" class="btn btn-success rounded-3 px-3 py-2 fw-bold d-inline-flex align-items-center gap-2 shadow-sm" style="background: #25D366; border-color: #25D366; font-size: 14px;">
                                        <i class="fa fa-whatsapp fs-5"></i> বিস্তারিত জানতে মেসেজ করুন
                                    </a>
                                @endif
                                <button type="submit" class="btn btn-primary rounded-3 px-4 py-2 fw-bold flex-grow-1 shadow-sm d-inline-flex align-items-center justify-content-center gap-2" style="background: #1d4ed8; border-color: #1d4ed8; font-size: 15px;">
                                    আবেদন জমা দিন <i class="fa fa-paper-plane"></i>
                                </button>
                            </div>

                            <p class="text-muted text-center small mt-3 mb-0">
                                <i class="fa fa-lock text-success me-1"></i> সকল তথ্য সম্পূর্ণ গোপনীয় থাকবে। নির্বাচিত প্রার্থীদের ফোনে জানানো হবে।
                            </p>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Right Column: Visual Banner & Highlights -->
            <div class="col-12 col-lg-5">
                <div class="sticky-top" style="top: 90px; z-index: 10;">
                    <!-- Banner Poster Card -->
                    <div class="card border-0 rounded-4 overflow-hidden shadow-sm mb-4" style="background: linear-gradient(135deg, #ffffff 0%, #fff7ed 100%); border: 2px solid #ea580c !important;">
                        <div class="p-4">
                            <span class="badge bg-danger rounded-pill px-3 py-1 font-size-12 mb-3">
                                <i class="fa fa-star me-1"></i> সুদ মুক্ত থাকুন, হালাল পথে আয় করুন
                            </span>
                            <h3 class="fw-bold text-dark mb-3" style="font-size: 26px; line-height: 1.3;">
                                কৃষি ও খাদ্যপণ্যের সম্ভাবনাময় খাতে <span style="color: #ea580c;">বিনিয়োগের সুবর্ণ সুযোগ</span>
                            </h3>

                            <div class="p-3 rounded-3 bg-white border mb-3 shadow-sm" style="border-left: 4px solid #ea580c !important;">
                                <div class="d-flex align-items-start gap-2 mb-2">
                                    <i class="fa fa-map-marker text-danger mt-1 fs-5"></i>
                                    <div>
                                        <div class="fw-bold text-dark" style="font-size: 14px;">ঠিকানা:</div>
                                        <div class="text-muted small">Plot #45, Probal Tower, Ring Road, Mohammadpur, Dhaka - 1207, Bangladesh</div>
                                    </div>
                                </div>
                                <div class="d-flex align-items-center gap-2">
                                    <i class="fa fa-phone text-success fs-5"></i>
                                    <div>
                                        <div class="fw-bold text-dark" style="font-size: 14px;">হেল্পলাইন:</div>
                                        <div class="text-muted small">{{ setting('phone1') ?: '+880 1805020348' }}</div>
                                    </div>
                                </div>
                            </div>

                            <div class="text-center p-3 rounded-3" style="background: rgba(234, 88, 12, 0.08);">
                                <span class="fw-bold" style="color: #ea580c;">ekrishisheba Enterprise Digital Network</span>
                            </div>
                        </div>
                    </div>

                    <!-- 4 Highlighted Value Cards -->
                    <div class="row g-3">
                        <div class="col-6">
                            <div class="p-3 bg-white rounded-3 border h-100 shadow-sm" style="border-left: 3px solid #10b981 !important;">
                                <i class="fa fa-line-chart text-success fs-4 mb-2"></i>
                                <h6 class="fw-bold text-dark mb-1 font-size-14">উচ্চ রিটার্ন</h6>
                                <p class="text-muted small mb-0" style="font-size: 12px;">প্রতি মাসে আকর্ষণীয় হালাল মুনাফা বন্টন</p>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="p-3 bg-white rounded-3 border h-100 shadow-sm" style="border-left: 3px solid #3b82f6 !important;">
                                <i class="fa fa-shield text-primary fs-4 mb-2"></i>
                                <h6 class="fw-bold text-dark mb-1 font-size-14">নিরাপদ বিনিয়োগ</h6>
                                <p class="text-muted small mb-0" style="font-size: 12px;">চুক্তিনির্ভর শতভাগ নিরাপদ ও স্বচ্ছ ব্যবস্থা</p>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="p-3 bg-white rounded-3 border h-100 shadow-sm" style="border-left: 3px solid #f59e0b !important;">
                                <i class="fa fa-users text-warning fs-4 mb-2"></i>
                                <h6 class="fw-bold text-dark mb-1 font-size-14">অভিজ্ঞ টিম</h6>
                                <p class="text-muted small mb-0" style="font-size: 12px;">দক্ষ প্রফেশনাল ম্যানেজমেন্ট পরিচালনায়</p>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="p-3 bg-white rounded-3 border h-100 shadow-sm" style="border-left: 3px solid #8b5cf6 !important;">
                                <i class="fa fa-headphones text-purple fs-4 mb-2" style="color: #8b5cf6;"></i>
                                <h6 class="fw-bold text-dark mb-1 font-size-14">দ্রুত সাপোর্ট</h6>
                                <p class="text-muted small mb-0" style="font-size: 12px;">২৪/৭ ডেডিকেটেড সাপোর্ট ও মনিটরিং</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>

<style>
.investor-amount-card {
    transition: all 0.2s ease;
    cursor: pointer;
}
.btn-check:checked + .investor-amount-card {
    background-color: #eff6ff !important;
    border-color: #3b82f6 !important;
    color: #1d4ed8 !important;
}
.btn-check:checked + .investor-amount-card .check-icon {
    color: #3b82f6 !important;
}
</style>
@endsection
