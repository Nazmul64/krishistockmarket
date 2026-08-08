@extends('layouts.frontend.app')

@section('content')
<div class="main-container">

    <!-- Page Banner -->
    <div class="container-fluid no-left-padding no-right-padding page-banner" style="background: linear-gradient(135deg, #1b88ce, #1469a0); padding: 60px 0;">
        <div class="container text-center text-white">
            <h2 style="color: #fff; font-size: 38px; font-weight: 700; margin-bottom: 10px;">আমাদের সম্পর্কে</h2>
            <p style="color: #e2e8f0; font-size: 16px; margin: 0;">কৃষি পরিবার - আপনার কৃষি সেবা ও শেয়ার বাজারের নির্ভরযোগ্য প্ল্যাটফর্ম</p>
        </div>
    </div>

    <main class="site-main py-5">
        <div class="container">
            <div class="row align-items-center my-4">
                <div class="col-lg-6 mb-4 mb-lg-0">
                    <div class="about-img text-center p-4 bg-white rounded shadow-sm" style="border: 1px solid #eee;">
                        <img src="{{ asset('upload/images/backend/logo') }}/{{ setting('logo') }}" alt="About Us" class="img-fluid" style="max-height: 220px;">
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="about-content ps-lg-4">
                        <h3 class="mb-3" style="color: #1b88ce; font-weight: 700;">স্বাগতম কৃষি পরিবারে</h3>
                        @if(setting('about_us_full_text'))
                            <div style="font-size: 15px; line-height: 1.8; color: #555;">
                                {!! setting('about_us_full_text') !!}
                            </div>
                        @else
                            <p style="font-size: 15px; line-height: 1.8; color: #555;">
                                কৃষি পরিবার একটি আধুনিক ও বিশ্বস্ত ডিজিটাল প্ল্যাটফর্ম। আমাদের মূল লক্ষ্য হল কৃষকদের সর্বোচ্চ সুবিধা প্রদান করা, মানসম্মত কৃষি পণ্য সরবরাহ করা এবং স্টকের সঠিক ও হালনাগাদ বাজারমূল্য নির্ধারণ ও পর্যবেক্ষণে সহায়তা করা।
                            </p>
                            <ul class="list-unstyled mt-3" style="line-height: 2.2; color: #444; font-size: 15px;">
                                <li><i class="fa fa-check-circle text-success me-2"></i> স্টকের রিয়েল-টাইম সঠিক ক্রয় ও বিক্রয় মূল্য প্রদর্শন।</li>
                                <li><i class="fa fa-check-circle text-success me-2"></i> কৃষিজ পণ্য ও সেরা মানের বীজ/সার সরবরাহ।</li>
                                <li><i class="fa fa-check-circle text-success me-2"></i> স্বচ্ছ ও দ্রুত লেনদেন সেবা।</li>
                            </ul>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Features Cards -->
            <div class="row mt-5">
                <div class="col-md-4 mb-4">
                    <div class="p-4 text-center rounded shadow-sm bg-white" style="border: 1px solid #e2e8f0;">
                        <i class="fa fa-bullseye text-info mb-3" style="font-size: 36px;"></i>
                        <h5 class="fw-bold mb-2">আমাদের ভিশন</h5>
                        <p style="font-size: 14px; color: #64748b; margin: 0;">কৃষি ও ব্যবসা খাতে প্রযুক্তির ব্যবহার নিশ্চিত করে প্রতিটি স্টকের সর্বোচ্চ মান ধরে রাখা।</p>
                    </div>
                </div>
                <div class="col-md-4 mb-4">
                    <div class="p-4 text-center rounded shadow-sm bg-white" style="border: 1px solid #e2e8f0;">
                        <i class="fa fa-line-chart text-success mb-3" style="font-size: 36px;"></i>
                        <h5 class="fw-bold mb-2">মার্কেট ট্রেন্ড</h5>
                        <p style="font-size: 14px; color: #64748b; margin: 0;">গ্রাফ চার্টের মাধ্যমে প্রতিটি স্টকের দৈনিক ও মাসিক প্রাইজ পরিবর্তন সহজে অনুধাবন।</p>
                    </div>
                </div>
                <div class="col-md-4 mb-4">
                    <div class="p-4 text-center rounded shadow-sm bg-white" style="border: 1px solid #e2e8f0;">
                        <i class="fa fa-handshake-o text-warning mb-3" style="font-size: 36px;"></i>
                        <h5 class="fw-bold mb-2">গ্রাহক সেবা</h5>
                        <p style="font-size: 14px; color: #64748b; margin: 0;">আমাদের টিম সপ্তাহের ৬ দিন গ্রাহক ও শেয়ারহোল্ডারদের সার্বক্ষণিক সেবা দিতে প্রস্তুত।</p>
                    </div>
                </div>
            </div>
        </div>
    </main>
</div>
@endsection
