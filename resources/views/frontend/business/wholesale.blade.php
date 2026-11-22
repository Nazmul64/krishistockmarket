@php
    $siteLang = request()->cookie('ekrishi_lang', 'bn');
    $isBn = ($siteLang === 'bn');
@endphp

@extends('layouts.frontend.app')

@section('content')
<div class="wholesale-program-page" style="background: #f8fafc; min-height: 85vh;">

    <!-- Top Orange Gradient Hero Banner -->
    <div class="py-5" style="background: linear-gradient(135deg, #1e293b 0%, #3b1d06 50%, #c2410c 100%); color: #ffffff;">
        <div class="container text-center py-3">
            <h1 class="fw-bold mb-2 text-white" style="font-size: clamp(24px, 4vw, 36px);">
                হোলসেল পণ্য কিনুন, <span style="color: #fb923c;">সাশ্রয়ী দামে পান</span>
            </h1>
            <p class="text-white-50 mx-auto mb-0" style="max-width: 650px; font-size: 15px;">
                পাইকারি মূল্যে কৃষিপণ্য ও গ্রোসারি সামগ্রী কিনুন এবং আপনার ব্যবসাকে এগিয়ে নিন। নিচের ফর্মটি পূরণ করুন – আমরা শীঘ্রই যোগাযোগ করব।
            </p>
        </div>
    </div>

    <div class="container py-5">

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

        <div class="row g-4 align-items-stretch">
            <!-- Left Column: Poster & Features -->
            <div class="col-12 col-lg-6">
                <div class="card border-0 rounded-4 shadow-sm h-100 overflow-hidden bg-white p-4" style="border: 1px solid #fed7aa !important;">
                    <div class="text-center pb-3 border-bottom mb-4">
                        <img src="{{ asset('upload/images/backend/logo') }}/{{ setting('logo') }}" alt="{{ setting('title') }}" style="max-height: 50px;" class="mb-2">
                        <h4 class="fw-bold text-dark mb-1" style="font-size: 20px;">Your Trusted Wholesale Partner</h4>
                        <div class="badge px-3 py-1 text-white fw-bold" style="background: #ea580c; font-size: 13px; letter-spacing: 1px;">
                            WHOLESALE & BULK SUPPLY
                        </div>
                    </div>

                    <!-- Highlighted points from screenshot -->
                    <div class="d-flex flex-column gap-3 mb-4">
                        <div class="d-flex align-items-start gap-3 p-3 rounded-3" style="background: #fff7ed; border-left: 4px solid #ea580c;">
                            <i class="fa fa-tag text-warning fs-4 mt-1"></i>
                            <div>
                                <h6 class="fw-bold text-dark mb-1 font-size-15">বিশেষ হোলসেল মূল্য</h6>
                                <p class="text-muted small mb-0">নির্ধারিত পরিমাণের উপর সর্বোচ্চ আকর্ষণীয় পাইকারি মূল্য ও ছাড় পাবেন।</p>
                            </div>
                        </div>

                        <div class="d-flex align-items-start gap-3 p-3 rounded-3" style="background: #f0fdf4; border-left: 4px solid #16a34a;">
                            <i class="fa fa-truck text-success fs-4 mt-1"></i>
                            <div>
                                <h6 class="fw-bold text-dark mb-1 font-size-15">দ্রুত ডেলিভারি</h6>
                                <p class="text-muted small mb-0">সারা বাংলাদেশে নিজস্ব ও পার্টনার লজিস্টিকসে দ্রুত ও নিরাপদ ডেলিভারি ব্যবস্থা।</p>
                            </div>
                        </div>

                        <div class="d-flex align-items-start gap-3 p-3 rounded-3" style="background: #eff6ff; border-left: 4px solid #2563eb;">
                            <i class="fa fa-headphones text-primary fs-4 mt-1"></i>
                            <div>
                                <h6 class="fw-bold text-dark mb-1 font-size-15">ডেডিকেটেড সাপোর্ট</h6>
                                <p class="text-muted small mb-0">হোলসেল গ্রাহকদের জন্য আলাদা অ্যাকাউন্ট ম্যানেজার ও সার্বক্ষণিক সহায়তা।</p>
                            </div>
                        </div>

                        <div class="d-flex align-items-start gap-3 p-3 rounded-3" style="background: #fdf2f8; border-left: 4px solid #db2777;">
                            <i class="fa fa-certificate text-danger fs-4 mt-1"></i>
                            <div>
                                <h6 class="fw-bold text-dark mb-1 font-size-15">খাঁটি ও নিশ্চিত পণ্য</h6>
                                <p class="text-muted small mb-0">প্রিমিয়াম কোয়ালিটির পণ্য সরাসরি ফার্ম ও উৎপাদক থেকে খাঁটি নিশ্চয়তায় সরবরাহ।</p>
                            </div>
                        </div>
                    </div>

                    <div class="mt-auto p-3 rounded-3 text-center" style="background: #f1f5f9;">
                        <small class="text-muted">জরুরী তথ্যের জন্য কল করুন: <strong class="text-dark">{{ setting('phone1') ?: '+880 1805020348' }}</strong></small>
                    </div>
                </div>
            </div>

            <!-- Right Column: Form -->
            <div class="col-12 col-lg-6">
                <div class="card border-0 rounded-4 shadow-sm h-100 overflow-hidden bg-white" style="border: 1px solid #fed7aa !important;">
                    <div class="p-4 text-white" style="background: linear-gradient(135deg, #c2410c 0%, #ea580c 100%);">
                        <h4 class="fw-bold mb-1 text-white font-size-20">
                            <i class="fa fa-shopping-cart me-2"></i>Wholesale আবেদন ফর্ম
                        </h4>
                        <p class="small text-white-50 mb-0">নিচের ফর্মে সঠিক তথ্য দিন, আমাদের হোলসেল টিম সরাসরি যোগাযোগ করবে</p>
                    </div>

                    <div class="card-body p-4 p-md-5">
                        <form action="{{ route('business.wholesale.apply') }}" method="POST">
                            @csrf

                            <!-- Full Name -->
                            <div class="mb-3">
                                <label class="form-label fw-bold text-dark small mb-1">
                                    <i class="fa fa-user text-muted me-1"></i> আপনার নাম <span class="text-danger">*</span>
                                </label>
                                <input type="text" name="name" class="form-control form-control-lg rounded-3 @error('name') is-invalid @enderror" value="{{ old('name') }}" placeholder="আপনার পূর্ণ নাম লিখুন" required style="font-size: 15px; border-color: #cbd5e1;">
                                @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>

                            <!-- Phone -->
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
                                <textarea name="address" rows="2" class="form-control rounded-3 @error('address') is-invalid @enderror" placeholder="দোকান বা ব্যবসা প্রতিষ্ঠানের বিস্তারিত ঠিকানা লিখুন" required style="font-size: 14px; border-color: #cbd5e1;">{{ old('address') }}</textarea>
                                @error('address')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>

                            <div class="row g-3 mb-4">
                                <!-- Profession -->
                                <div class="col-12 col-md-6">
                                    <label class="form-label fw-bold text-dark small mb-1">
                                        <i class="fa fa-briefcase text-muted me-1"></i> পেশা
                                    </label>
                                    <input type="text" name="profession" class="form-control rounded-3" value="{{ old('profession') }}" placeholder="যেমন: ব্যবসায়ী, সুপারশপ মালিক" style="font-size: 14px; border-color: #cbd5e1;">
                                </div>

                                <!-- Quantity -->
                                <div class="col-12 col-md-6">
                                    <label class="form-label fw-bold text-dark small mb-1">
                                        <i class="fa fa-cubes text-muted me-1"></i> পণ্যের পরিমাণ
                                    </label>
                                    <input type="text" name="quantity" class="form-control rounded-3" value="{{ old('quantity') }}" placeholder="যেমন: ২০ কার্টুন, ৫০ কেজি" style="font-size: 14px; border-color: #cbd5e1;">
                                </div>
                            </div>

                            <!-- Submit Button -->
                            <button type="submit" class="btn btn-warning w-100 text-white rounded-3 py-3 fw-bold shadow-sm d-flex align-items-center justify-content-center gap-2" style="background: #ea580c; border-color: #ea580c; font-size: 16px;">
                                আবেদন জমা দিন <i class="fa fa-paper-plane"></i>
                            </button>

                            <p class="text-muted text-center small mt-3 mb-0">
                                <i class="fa fa-lock text-success me-1"></i> আপনার তথ্য সম্পূর্ণ সুরক্ষিত থাকবে।
                            </p>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
