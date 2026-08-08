@extends('layouts.frontend.app')

@section('content')
<div class="main-container">

    <!-- Page Banner -->
    <div class="container-fluid no-left-padding no-right-padding page-banner" style="background: linear-gradient(135deg, #1b88ce, #1469a0); padding: 60px 0;">
        <div class="container text-center text-white">
            <h2 style="color: #fff; font-size: 38px; font-weight: 700; margin-bottom: 10px;">Terms & Conditions</h2>
            <p style="color: #e2e8f0; font-size: 16px; margin: 0;">আমাদের সেবাসমূহ ব্যবহারের শর্তাবলী</p>
        </div>
    </div>

    <main class="site-main py-5">
        <div class="container">
            <div class="bg-white p-4 p-md-5 rounded shadow-sm" style="border: 1px solid #e2e8f0; line-height: 1.8; color: #444;">
                <h3 class="mb-4" style="color: #1b88ce; font-weight: 700;">টার্মস এন্ড কন্ডিশনস</h3>
                @if(setting('terms_conditions'))
                    <div>{!! setting('terms_conditions') !!}</div>
                @else
                    <p>কৃষি পরিবারে আপনাকে স্বাগতম। আমাদের ওয়েবসাইট ব্যবহার করার মাধ্যমে আপনি নিম্নলিখিত শর্তাবলী মেনে নিতে সম্মত হচ্ছেন:</p>
                    <ul>
                        <li>সকল স্টক সংক্রান্ত তথ্য ও লেনদেন নিয়মানুযায়ী সম্পন্ন হবে।</li>
                        <li>স্টকের ক্রয় ও বিক্রয় আবেদন অ্যাডমিন অনুমোদনের পর চূড়ান্ত হবে।</li>
                        <li>কোনো ধরনের ভুল তথ্য প্রদান বা অবৈধ কার্যক্রম গ্রহণযোগ্য নয়।</li>
                    </ul>
                @endif
            </div>
        </div>
    </main>

</div>
@endsection
