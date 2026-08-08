@extends('layouts.frontend.app')

@section('content')
<div class="main-container">

    <!-- Page Banner -->
    <div class="container-fluid no-left-padding no-right-padding page-banner" style="background: linear-gradient(135deg, #1b88ce, #1469a0); padding: 60px 0;">
        <div class="container text-center text-white">
            <h2 style="color: #fff; font-size: 38px; font-weight: 700; margin-bottom: 10px;">Privacy Policy</h2>
            <p style="color: #e2e8f0; font-size: 16px; margin: 0;">আপনার তথ্যের সুরক্ষা ও গোপনীয়তা নীতি</p>
        </div>
    </div>

    <main class="site-main py-5">
        <div class="container">
            <div class="bg-white p-4 p-md-5 rounded shadow-sm" style="border: 1px solid #e2e8f0; line-height: 1.8; color: #444;">
                <h3 class="mb-4" style="color: #1b88ce; font-weight: 700;">প্রাইভেসি পলিসি</h3>
                @if(setting('privacy_policy'))
                    <div>{!! setting('privacy_policy') !!}</div>
                @else
                    <p>কৃষি পরিবার আপনার ব্যাক্তিগত তথ্যের সর্বোচ্চ নিরাপত্তা প্রদানে প্রতিশ্রুতিবদ্ধ।</p>
                    <ul>
                        <li>আপনার নাম, ইমেইল এবং মোবাইল নম্বর শুধুমাত্র অ্যাকাউন্ট সনাক্তকরণ ও যোগাযোগের জন্য ব্যবহৃত হয়।</li>
                        <li>আমরা কোনো তৃতীয় পক্ষের কাছে গ্রাহকের গোপনীয় তথ্য শেয়ার করি না।</li>
                        <li>যেকোনো সময় গ্রাহক তার অ্যাকাউন্ট তথ্য আপডেট করতে পারেন।</li>
                    </ul>
                @endif
            </div>
        </div>
    </main>

</div>
@endsection
