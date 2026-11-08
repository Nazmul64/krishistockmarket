@extends('layouts.frontend.app')

@section('content')
<div class="main-container">

    <!-- Modern Brand Page Banner -->
    <div class="page-modern-hero-banner text-center py-5">
        <div class="container">
            <span class="section-pill-badge mb-2">
                <i class="fa fa-user-secret me-1"></i> তথ্যের নিরাপত্তা
            </span>
            <h1 class="hero-page-title text-white">প্রাইভেসি পলিসি (Privacy Policy)</h1>
            <p class="hero-page-desc text-slate-300">আপনার ব্যক্তিগত তথ্যের পূর্ণ সুরক্ষা ও গোপনীয়তা নীতিমালা</p>
        </div>
    </div>

    <main class="site-main py-5 bg-slate-50">
        <div class="container">
            <div class="bg-white p-4 p-md-5 rounded-4 shadow-sm border" style="line-height: 1.85; color: #334155;">
                <div class="d-flex align-items-center gap-2 mb-4 pb-3 border-bottom">
                    <i class="fa fa-lock text-warning fa-2x"></i>
                    <h3 class="mb-0 fw-bold text-slate-800">গোপনীয়তা ও সুরক্ষা অঙ্গীকার</h3>
                </div>

                @if(setting('privacy_policy'))
                    <div class="privacy-html-content">
                        {!! setting('privacy_policy') !!}
                    </div>
                @else
                    <p class="lead" style="font-size: 16px;">
                        কৃষি পরিবার আপনার ব্যক্তিগত ও আর্থিক তথ্যের সর্বোচ্চ সুরক্ষা নিশ্চিত করতে সর্বদা প্রতিশ্রুতিবদ্ধ।
                    </p>
                    <ul class="list-unstyled d-flex flex-column gap-3 mt-3">
                        <li class="d-flex align-items-start">
                            <i class="fa fa-check-circle text-success me-2 mt-1"></i>
                            <span>আপনার নাম, ইমেইল এবং মোবাইল নম্বর শুধুমাত্র অ্যাকাউন্ট সনাক্তকরণ ও জরুরী নোটিফিকেশন পাঠানোর জন্য ব্যবহৃত হয়।</span>
                        </li>
                        <li class="d-flex align-items-start">
                            <i class="fa fa-check-circle text-success me-2 mt-1"></i>
                            <span>আমরা কোনো তৃতীয় পক্ষ বা বাণিজ্যিক প্রতিষ্ঠানের কাছে গ্রাহকের সংবেদনশীল তথ্য হস্তান্তর বা বিক্রয় করি না।</span>
                        </li>
                        <li class="d-flex align-items-start">
                            <i class="fa fa-check-circle text-success me-2 mt-1"></i>
                            <span>যেকোনো সময় ব্যবহারকারী ড্যাশবোর্ড থেকে তার তথ্য যাচাই ও হালনাগাদ করার পূর্ণ নিয়ন্ত্রণ রাখেন।</span>
                        </li>
                    </ul>
                @endif
            </div>
        </div>
    </main>

</div>
@endsection
