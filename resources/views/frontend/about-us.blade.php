@extends('layouts.frontend.app')

@section('content')
<div class="main-container">

    <!-- Modern Brand Page Banner -->
    <div class="page-modern-hero-banner text-center py-5">
        <div class="container">
            <span class="section-pill-badge mb-2">
                <i class="fa fa-info-circle me-1"></i> আমাদের পরিচিতি
            </span>
            <h1 class="hero-page-title text-white">আমাদের সম্পর্কে</h1>
            <p class="hero-page-desc text-slate-300">কৃষি পরিবার - আপনার কৃষি সেবা ও শেয়ার বাজারের নির্ভরযোগ্য বিশ্বস্ত প্ল্যাটফর্ম</p>
        </div>
    </div>

    <main class="site-main py-5 bg-slate-50">
        <div class="container">
            <div class="row align-items-center g-4 my-2">
                <div class="col-lg-5 text-center">
                    <div class="about-hero-logo-card p-4 bg-white rounded-4 shadow-sm border">
                        <img src="{{ asset('upload/images/backend/logo') }}/{{ setting('logo') }}" alt="About Us" class="img-fluid" style="max-height: 220px; object-fit: contain;">
                    </div>
                </div>
                <div class="col-lg-7">
                    <div class="about-content-box p-4 p-md-5 bg-white rounded-4 shadow-sm border">
                        <span class="section-pill-badge mb-2">
                            <i class="fa fa-leaf me-1"></i> এক নজরে
                        </span>
                        <h2 class="section-main-title mb-3">
                            <span class="text-gradient">স্বাগতম কৃষি পরিবারে</span>
                        </h2>
                        @if(setting('about_us_full_text'))
                            <div class="about-text-body" style="font-size: 15px; line-height: 1.85; color: #475569;">
                                {!! setting('about_us_full_text') !!}
                            </div>
                        @else
                            <p style="font-size: 15.5px; line-height: 1.8; color: #475569;">
                                কৃষি পরিবার একটি আধুনিক ও বিশ্বস্ত ডিজিটাল প্ল্যাটফর্ম। আমাদের মূল লক্ষ্য হল কৃষকদের সর্বোচ্চ সুবিধা প্রদান করা, মানসম্মত কৃষি পণ্য সরবরাহ করা এবং স্টকের সঠিক ও হালনাগাদ বাজারমূল্য নির্ধারণ ও পর্যবেক্ষণে সহায়তা করা।
                            </p>
                            <ul class="list-unstyled mt-3" style="line-height: 2.2; color: #334155; font-size: 15px;">
                                <li><i class="fa fa-check-circle text-success me-2"></i> স্টকের রিয়েল-টাইম সঠিক ক্রয় ও বিক্রয় মূল্য প্রদর্শন।</li>
                                <li><i class="fa fa-check-circle text-success me-2"></i> কৃষিজ পণ্য ও সেরা মানের বীজ/সার সরবরাহ।</li>
                                <li><i class="fa fa-check-circle text-success me-2"></i> ১০০% সুরক্ষিত, স্বচ্ছ ও দ্রুত লেনদেন সেবা।</li>
                            </ul>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Features Cards Grid -->
            <div class="row g-4 mt-4">
                <div class="col-md-4">
                    <div class="p-4 text-center rounded-4 shadow-sm bg-white h-100 border transition-all">
                        <div class="highlight-icon bg-gold mx-auto mb-3" style="width: 56px; height: 56px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 24px; color: #d97706; background: #fffbeb;">
                            <i class="fa fa-bullseye"></i>
                        </div>
                        <h4 class="fw-bold mb-2 text-slate-800">আমাদের ভিশন</h4>
                        <p style="font-size: 14.5px; color: #64748b; margin: 0; line-height: 1.6;">কৃষি ও ব্যবসা খাতে আধুনিক প্রযুক্তির ব্যবহার নিশ্চিত করে প্রতিটি কৃষিজ স্টকের সর্বোচ্চ মান ধরে রাখা।</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="p-4 text-center rounded-4 shadow-sm bg-white h-100 border transition-all">
                        <div class="highlight-icon bg-emerald mx-auto mb-3" style="width: 56px; height: 56px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 24px; color: #059669; background: #d1fae5;">
                            <i class="fa fa-line-chart"></i>
                        </div>
                        <h4 class="fw-bold mb-2 text-slate-800">মার্কেট ট্রেন্ড</h4>
                        <p style="font-size: 14.5px; color: #64748b; margin: 0; line-height: 1.6;">গ্রাফ চার্ট ও রিয়েল-টাইম ডাটার মাধ্যমে প্রতিটি পণ্যের দৈনিক ও সাপ্তাহিক প্রাইস ট্রেন্ড সহজে পর্যবেক্ষণ।</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="p-4 text-center rounded-4 shadow-sm bg-white h-100 border transition-all">
                        <div class="highlight-icon bg-amber mx-auto mb-3" style="width: 56px; height: 56px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 24px; color: #d97706; background: #fef3c7;">
                            <i class="fa fa-handshake-o"></i>
                        </div>
                        <h4 class="fw-bold mb-2 text-slate-800">গ্রাহক সেবা</h4>
                        <p style="font-size: 14.5px; color: #64748b; margin: 0; line-height: 1.6;">আমাদের নিবেদিত সাপোর্ট টিম সপ্তাহের ৬ দিন গ্রাহক ও শেয়ারহোল্ডারদের সার্বক্ষণিক সেবা দিতে প্রস্তুত।</p>
                    </div>
                </div>
            </div>
        </div>
    </main>
</div>
@endsection
