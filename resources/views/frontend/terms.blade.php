@extends('layouts.frontend.app')

@section('content')
<div class="main-container">

    <!-- Modern Brand Page Banner -->
    <div class="page-modern-hero-banner text-center py-5">
        <div class="container">
            <span class="section-pill-badge mb-2">
                <i class="fa fa-file-text-o me-1"></i> নির্দেশিকা ও শর্তাবলী
            </span>
            <h1 class="hero-page-title text-white">টার্মস এন্ড কন্ডিশন (Terms & Conditions)</h1>
            <p class="hero-page-desc text-slate-300">কৃষি পরিবার প্ল্যাটফর্ম ও সেবাসমূহ ব্যবহারের সাধারণ নিয়মনীতি</p>
        </div>
    </div>

    <main class="site-main py-5 bg-slate-50">
        <div class="container">
            <div class="bg-white p-4 p-md-5 rounded-4 shadow-sm border" style="line-height: 1.85; color: #334155;">
                <div class="d-flex align-items-center gap-2 mb-4 pb-3 border-bottom">
                    <i class="fa fa-shield text-warning fa-2x"></i>
                    <h3 class="mb-0 fw-bold text-slate-800">ব্যবহারকারীর ব্যবহারের শর্তাবলী</h3>
                </div>

                @if(setting('terms_conditions'))
                    <div class="terms-html-content">
                        {!! setting('terms_conditions') !!}
                    </div>
                @else
                    <p class="lead" style="font-size: 16px;">
                        কৃষি পরিবারে আপনাকে স্বাগতম। আমাদের ওয়েবসাইট ও ডিজিটাল সার্ভিসসমূহ ব্যবহার করার মাধ্যমে আপনি নিম্নলিখিত শর্তাবলী মেনে নিতে সম্মত হচ্ছেন:
                    </p>
                    <ul class="list-unstyled d-flex flex-column gap-3 mt-3">
                        <li class="d-flex align-items-start">
                            <i class="fa fa-check-circle text-success me-2 mt-1"></i>
                            <span>সকল স্টক সংক্রান্ত তথ্য, ক্রয়-বিক্রয় রেট ও লেনদেন সম্পূর্ণ স্বচ্ছতা ও নিয়মানুযায়ী সম্পন্ন হবে।</span>
                        </li>
                        <li class="d-flex align-items-start">
                            <i class="fa fa-check-circle text-success me-2 mt-1"></i>
                            <span>স্টকের ক্রয় ও বিক্রয় আবেদন অ্যাডমিন অনুমোদনের পর চূড়ান্তভাবে কার্যকর হবে।</span>
                        </li>
                        <li class="d-flex align-items-start">
                            <i class="fa fa-check-circle text-success me-2 mt-1"></i>
                            <span>গ্রাহকের একাউন্টে কোনো ধরনের ভুল বা বিভ্রান্তিকর তথ্য প্রদান গ্রহণযোগ্য নয়।</span>
                        </li>
                    </ul>
                @endif
            </div>
        </div>
    </main>

</div>
@endsection
