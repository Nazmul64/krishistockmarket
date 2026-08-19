@extends('layouts.frontend.app')

@section('content')
<div class="main-container">

    <!-- Page Banner -->
    <div class="container-fluid no-left-padding no-right-padding page-banner" style="background: linear-gradient(135deg, #1b88ce, #1469a0); padding: 60px 0;">
        <div class="container text-center text-white">
            <h2 style="color: #fff; font-size: 38px; font-weight: 700; margin-bottom: 10px;">যোগাযোগ করুন</h2>
            <p style="color: #e2e8f0; font-size: 16px; margin: 0;">কৃষি পরিবার এর সাথে যেকোনো প্রয়োজনে যেকোনো সময় যোগাযোগ করুন</p>
        </div>
    </div>

    <main class="site-main py-5">
        <div class="container">
            <div class="row">

                <!-- Contact Info Cards -->
                <div class="col-lg-4 col-md-5 mb-4">
                    <div class="card border-0 shadow-sm rounded mb-4" style="background: #ffffff; border: 1px solid #e2e8f0;">
                        <div class="card-body p-4">
                            <h4 class="fw-bold mb-4" style="color: #1b88ce;"><i class="fa fa-envelope-open-o me-2"></i> যোগাযোগের তথ্য</h4>

                            <div class="d-flex align-items-start mb-4">
                                <div class="bg-light rounded-circle text-primary p-3 me-3 text-center" style="width: 48px; height: 48px; min-width: 48px;">
                                    <i class="fa fa-map-marker fs-4"></i>
                                </div>
                                <div>
                                    <h6 class="fw-bold mb-1">ঠিকানা</h6>
                                    <p class="text-muted mb-0" style="font-size: 14px;">
                                        {{ setting('address1') }} {{ setting('address2') }}
                                    </p>
                                </div>
                            </div>

                            <div class="d-flex align-items-start mb-4">
                                <div class="bg-light rounded-circle text-success p-3 me-3 text-center" style="width: 48px; height: 48px; min-width: 48px;">
                                    <i class="fa fa-phone fs-4"></i>
                                </div>
                                <div>
                                    <h6 class="fw-bold mb-1">ফোন নম্বর</h6>
                                    <p class="text-muted mb-0" style="font-size: 14px;">
                                        {{ setting('phone1') }} @if(setting('phone2')) / {{ setting('phone2') }} @endif
                                    </p>
                                </div>
                            </div>

                            <div class="d-flex align-items-start mb-4">
                                <div class="bg-light rounded-circle text-info p-3 me-3 text-center" style="width: 48px; height: 48px; min-width: 48px;">
                                    <i class="fa fa-envelope fs-4"></i>
                                </div>
                                <div>
                                    <h6 class="fw-bold mb-1">ইমেইল ঠিকানা</h6>
                                    <p class="text-muted mb-0" style="font-size: 14px;">
                                        {{ setting('email1') }}
                                    </p>
                                </div>
                            </div>

                            <div class="d-flex align-items-start">
                                <div class="bg-light rounded-circle text-warning p-3 me-3 text-center" style="width: 48px; height: 48px; min-width: 48px;">
                                    <i class="fa fa-clock-o fs-4"></i>
                                </div>
                                <div>
                                    <h6 class="fw-bold mb-1">কর্মঘণ্টা</h6>
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
                    <div class="card border-0 shadow-sm rounded" style="background: #ffffff; border: 1px solid #e2e8f0;">
                        <div class="card-body p-4 p-md-5">
                            <h4 class="fw-bold mb-2" style="color: #1e293b;">বার্তা পাঠান</h4>
                            <p class="text-muted mb-4" style="font-size: 14px;">আপনার যেকোনো জিজ্ঞাসা বা মতামত নিচে লিখে আমাদের পাঠাতে পারেন।</p>

                            @if(session('success'))
                                <div class="alert alert-success alert-dismissible fade show" role="alert">
                                    <i class="fa fa-check-circle me-2"></i> {{ session('success') }}
                                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                </div>
                            @endif

                            <form action="{{ route('contact.send') }}" method="POST">
                                @csrf
                                <div class="row g-3">
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label fw-semibold">আপনার নাম <span class="text-danger">*</span></label>
                                        <input type="text" name="name" class="form-control" placeholder="আপনার নাম লিখুন" required style="padding: 10px 14px;">
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label fw-semibold">ফোন নম্বর <span class="text-danger">*</span></label>
                                        <input type="tel" name="phone" class="form-control" placeholder="০১৭xxxxxxxx" required style="padding: 10px 14px;">
                                    </div>
                                    <div class="col-12 mb-3">
                                        <label class="form-label fw-semibold">ইমেইল ঠিকানা (ঐচ্ছিক)</label>
                                        <input type="email" name="email" class="form-control" placeholder="example@email.com" style="padding: 10px 14px;">
                                    </div>
                                    <div class="col-12 mb-3">
                                        <label class="form-label fw-semibold">বিষয়</label>
                                        <input type="text" name="subject" class="form-control" placeholder="বার্তার বিষয়" style="padding: 10px 14px;">
                                    </div>
                                    <div class="col-12 mb-4">
                                        <label class="form-label fw-semibold">বার্তা <span class="text-danger">*</span></label>
                                        <textarea name="message" class="form-control" rows="5" placeholder="আপনার বিস্তারিত বার্তা লিখুন..." required style="padding: 10px 14px;"></textarea>
                                    </div>
                                    <div class="col-12">
                                        <button type="submit" class="btn btn-primary px-4 py-2 text-white fw-bold" style="background: #1b88ce; border: none; border-radius: 6px;">
                                            <i class="fa fa-paper-plane me-2"></i> বার্তা পাঠান
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
