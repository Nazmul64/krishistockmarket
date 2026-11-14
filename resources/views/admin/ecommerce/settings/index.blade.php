@extends('layouts.backend.app')

@section('content')
<div class="content-wrapper">
    <div class="container-full">
        <div class="content-header">
            <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
                <div>
                    <h3 class="page-title text-dark fw-bold mb-1"><i class="ti-settings text-primary me-2"></i>ই-কমার্স জেনারেল সেটিংস</h3>
                    <p class="text-muted mb-0">দোকানের নাম, যোগাযোগের নম্বর, নোটিশ বার ও সোশ্যাল মিডিয়া লিঙ্ক কনফিগারেশন</p>
                </div>
            </div>
        </div>

        <section class="content">
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="fa fa-check-circle me-1"></i> {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <form action="{{ route('admin.ecommerce.settings.update') }}" method="POST">
                @csrf
                <div class="row">
                    <!-- Basic Shop Info -->
                    <div class="col-lg-6 col-12">
                        <div class="box shadow-sm mb-4">
                            <div class="box-header with-border">
                                <h4 class="box-title"><i class="ti-home me-1 text-primary"></i>মৌলিক তথ্য (Basic Info)</h4>
                            </div>
                            <div class="box-body">
                                <div class="form-group mb-3">
                                    <label class="form-label fw-bold">শপের নাম</label>
                                    <input type="text" name="shop_name" class="form-control" value="{{ $settings['shop_name'] ?? 'ASMI Supershop' }}">
                                </div>

                                <div class="form-group mb-3">
                                    <label class="form-label fw-bold">হটলাইন টেলিফোন নম্বর</label>
                                    <input type="text" name="shop_hotline" class="form-control" value="{{ $settings['shop_hotline'] ?? '09617900200' }}">
                                </div>

                                <div class="form-group mb-3">
                                    <label class="form-label fw-bold">হোয়াটসঅ্যাপ কন্টাক্ট নম্বর</label>
                                    <input type="text" name="shop_whatsapp" class="form-control" value="{{ $settings['shop_whatsapp'] ?? '01805020340' }}">
                                </div>

                                <div class="form-group mb-3">
                                    <label class="form-label fw-bold">সাপোর্ট ইমেইল</label>
                                    <input type="email" name="shop_email" class="form-control" value="{{ $settings['shop_email'] ?? 'support@asmishop.com' }}">
                                </div>

                                <div class="form-group mb-3">
                                    <label class="form-label fw-bold">অফিস / স্টোর ঠিকানা</label>
                                    <textarea name="shop_address" rows="2" class="form-control">{{ $settings['shop_address'] ?? 'Plot #45, Probal Tower, Ring Road, Mohammadpur, Dhaka-1207' }}</textarea>
                                </div>

                                <div class="form-group mb-3">
                                    <label class="form-label fw-bold">মুদ্রা প্রতীক (Currency Symbol)</label>
                                    <input type="text" name="currency_symbol" class="form-control" value="{{ $settings['currency_symbol'] ?? '৳' }}">
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Announcements & App Links -->
                    <div class="col-lg-6 col-12">
                        <div class="box shadow-sm mb-4">
                            <div class="box-header with-border">
                                <h4 class="box-title"><i class="ti-announcement me-1 text-warning"></i>শীর্ষ নোটিশ ও প্রচার বার্তা</h4>
                            </div>
                            <div class="box-body">
                                <div class="form-group mb-3">
                                    <label class="form-label fw-bold">টপ নোটিশ বার টেক্সট</label>
                                    <input type="text" name="top_notice_bar" class="form-control" value="{{ $settings['top_notice_bar'] ?? 'এখন ঢাকাজুড়ে ফ্রি হোম ডেলিভারি! সীমিত সময়ের অফার চলছে।' }}">
                                    <small class="text-muted">ওয়েবসাইটের একেবারে উপরে প্রদর্শিত হবে</small>
                                </div>

                                <div class="form-group mb-3">
                                    <label class="form-label fw-bold">ডেলিভারি অফার ব্যানার টেক্সট</label>
                                    <input type="text" name="free_shipping_notice" class="form-control" value="{{ $settings['free_shipping_notice'] ?? 'এখন ঢাকাজুড়ে ফ্রি হোম ডেলিভারি' }}">
                                </div>

                                <div class="form-group mb-3">
                                    <label class="form-label fw-bold">Google Play Store অ্যাপ লিঙ্ক</label>
                                    <input type="text" name="app_play_store_url" class="form-control" value="{{ $settings['app_play_store_url'] ?? '#' }}" placeholder="https://play.google.com/store/apps/...">
                                </div>

                                <div class="form-group mb-3">
                                    <label class="form-label fw-bold">Apple App Store অ্যাপ লিঙ্ক</label>
                                    <input type="text" name="app_apple_store_url" class="form-control" value="{{ $settings['app_apple_store_url'] ?? '#' }}" placeholder="https://apps.apple.com/...">
                                </div>
                            </div>
                        </div>

                        <!-- Social Media Links -->
                        <div class="box shadow-sm mb-4">
                            <div class="box-header with-border">
                                <h4 class="box-title"><i class="ti-sharethis me-1 text-info"></i>সোশ্যাল মিডিয়া প্রোফাইল লিঙ্ক</h4>
                            </div>
                            <div class="box-body">
                                <div class="row g-2">
                                    <div class="col-6 mb-2">
                                        <label class="form-label small fw-bold"><i class="ti-facebook text-primary me-1"></i>Facebook</label>
                                        <input type="text" name="social_facebook" class="form-control form-control-sm" value="{{ $settings['social_facebook'] ?? '#' }}">
                                    </div>
                                    <div class="col-6 mb-2">
                                        <label class="form-label small fw-bold"><i class="ti-instagram text-danger me-1"></i>Instagram</label>
                                        <input type="text" name="social_instagram" class="form-control form-control-sm" value="{{ $settings['social_instagram'] ?? '#' }}">
                                    </div>
                                    <div class="col-6 mb-2">
                                        <label class="form-label small fw-bold"><i class="ti-youtube text-danger me-1"></i>YouTube</label>
                                        <input type="text" name="social_youtube" class="form-control form-control-sm" value="{{ $settings['social_youtube'] ?? '#' }}">
                                    </div>
                                    <div class="col-6 mb-2">
                                        <label class="form-label small fw-bold"><i class="ti-linkedin text-info me-1"></i>LinkedIn</label>
                                        <input type="text" name="social_linkedin" class="form-control form-control-sm" value="{{ $settings['social_linkedin'] ?? '#' }}">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-12">
                        <div class="box shadow-sm mb-4">
                            <div class="box-body text-end">
                                <button type="submit" class="btn btn-primary btn-lg px-5">
                                    <i class="ti-save me-1"></i> সমস্ত সেটিংস সংরক্ষণ করুন
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </section>
    </div>
</div>
@endsection

