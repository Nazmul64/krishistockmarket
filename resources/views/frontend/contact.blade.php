@extends('layouts.frontend.app')

@section('content')
<div class="main-container">

    <!-- Modern Brand Page Banner -->
    <div class="page-modern-hero-banner text-center py-5">
        <div class="container">
            <span class="section-pill-badge mb-2">
                <i class="fa fa-envelope-o me-1"></i> আমাদের সাথে যোগাযোগ
            </span>
            <h1 class="hero-page-title text-white">যোগাযোগ করুন (Contact Us)</h1>
            <p class="hero-page-desc text-slate-300">কৃষি পরিবার এর সাথে যেকোনো প্রয়োজনে যেকোনো সময় সরাসরি যোগাযোগ করুন</p>
        </div>
    </div>

    <main class="site-main py-5 bg-slate-50">
        <div class="container">
            <div class="row g-4">

                <!-- Contact Info Cards -->
                <div class="col-lg-4 col-md-5 mb-4">
                    <div class="card border-0 shadow-sm rounded-4 mb-4 bg-white border">
                        <div class="card-body p-4">
                            <h4 class="fw-bold mb-4 text-slate-800">
                                <i class="fa fa-address-book-o text-warning me-2"></i> যোগাযোগের তথ্য
                            </h4>

                            <div class="d-flex align-items-start mb-4">
                                <div class="rounded-circle text-primary p-3 me-3 text-center d-flex align-items-center justify-content-center" style="width: 48px; height: 48px; min-width: 48px; background: #eff6ff;">
                                    <i class="fa fa-map-marker fs-4 text-primary"></i>
                                </div>
                                <div>
                                    <h6 class="fw-bold mb-1 text-slate-700">ঠিকানা</h6>
                                    <p class="text-muted mb-0" style="font-size: 14px;">
                                        {{ setting('address1') }}@if(setting('address2') && setting('address2') !== setting('address1')), {{ setting('address2') }}@endif
                                    </p>
                                </div>
                            </div>

                            <div class="d-flex align-items-start mb-4">
                                <div class="rounded-circle text-success p-3 me-3 text-center d-flex align-items-center justify-content-center" style="width: 48px; height: 48px; min-width: 48px; background: #ecfdf5;">
                                    <i class="fa fa-phone fs-4 text-success"></i>
                                </div>
                                <div>
                                    <h6 class="fw-bold mb-1 text-slate-700">ফোন নম্বর</h6>
                                    <p class="text-muted mb-0" style="font-size: 14px;">
                                        {{ setting('phone1') }}@if(setting('phone2') && setting('phone2') !== setting('phone1')) / {{ setting('phone2') }}@endif
                                    </p>
                                </div>
                            </div>

                            <div class="d-flex align-items-start mb-4">
                                <div class="rounded-circle text-warning p-3 me-3 text-center d-flex align-items-center justify-content-center" style="width: 48px; height: 48px; min-width: 48px; background: #fffbeb;">
                                    <i class="fa fa-envelope fs-4 text-warning"></i>
                                </div>
                                <div>
                                    <h6 class="fw-bold mb-1 text-slate-700">ইমেইল ঠিকানা</h6>
                                    <p class="text-muted mb-0" style="font-size: 14px; word-break: break-all;">
                                        {{ setting('email1') }}
                                    </p>
                                </div>
                            </div>

                            <div class="d-flex align-items-start">
                                <div class="rounded-circle text-info p-3 me-3 text-center d-flex align-items-center justify-content-center" style="width: 48px; height: 48px; min-width: 48px; background: #f0f9ff;">
                                    <i class="fa fa-clock-o fs-4 text-info"></i>
                                </div>
                                <div>
                                    <h6 class="fw-bold mb-1 text-slate-700">কর্মঘণ্টা</h6>
                                    <p class="text-muted mb-0" style="font-size: 14px;">
                                        শনিবার - বৃহস্পতিবার: সকাল ৯:০০ - সন্ধ্যা ৬:০০
                                    </p>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>

                <!-- Contact Form Card -->
                <div class="col-lg-8 col-md-7 mb-4">
                    <div class="card border-0 shadow-sm rounded-4 bg-white border">
                        <div class="card-body p-4 p-md-5">
                            <h4 class="fw-bold mb-2 text-slate-800">সরাসরি বার্তা পাঠান</h4>
                            <p class="text-muted mb-4" style="font-size: 14.5px;">আপনার যেকোনো জিজ্ঞাসা, পরামর্শ বা সহায়তার জন্য বার্তা লিখুন।</p>

                            @if(session('success'))
                                <div class="alert alert-success alert-dismissible fade show rounded-3" role="alert">
                                    <i class="fa fa-check-circle me-2"></i> {{ session('success') }}
                                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                </div>
                            @endif

                            <form action="{{ route('contact.send') }}" method="POST">
                                @csrf
                                <div class="row g-3">
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label fw-bold text-slate-700">আপনার নাম <span class="text-danger">*</span></label>
                                        <input type="text" name="name" class="form-control" placeholder="আপনার পূর্ণ নাম লিখুন" required style="padding: 12px 16px; border-radius: 10px;">
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label fw-bold text-slate-700">ফোন নম্বর <span class="text-danger">*</span></label>
                                        <input type="tel" name="phone" class="form-control" placeholder="০১৭xxxxxxxx" required style="padding: 12px 16px; border-radius: 10px;">
                                    </div>
                                    <div class="col-12 mb-3">
                                        <label class="form-label fw-bold text-slate-700">ইমেইল ঠিকানা</label>
                                        <input type="email" name="email" class="form-control" placeholder="example@gmail.com" style="padding: 12px 16px; border-radius: 10px;">
                                    </div>
                                    <div class="col-12 mb-3">
                                        <label class="form-label fw-bold text-slate-700">বার্তার বিষয়</label>
                                        <input type="text" name="subject" class="form-control" placeholder="বিষয় লিখুন..." style="padding: 12px 16px; border-radius: 10px;">
                                    </div>
                                    <div class="col-12 mb-4">
                                        <label class="form-label fw-bold text-slate-700">আপনার বার্তা <span class="text-danger">*</span></label>
                                        <textarea name="message" class="form-control" rows="5" placeholder="বিস্তারিত লিখুন..." required style="padding: 12px 16px; border-radius: 10px;"></textarea>
                                    </div>
                                    <div class="col-12">
                                        <button type="submit" class="btn-contact-submit">
                                            <span>বার্তা পাঠিয়ে দিন</span>
                                            <i class="fa fa-paper-plane ms-2"></i>
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </main>
</div>
@endsection
