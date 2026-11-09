@extends('layouts.backend.app')

@section('content')
<div class="content-wrapper">
    <div class="container-full">
        <div class="content-header">
            <h3 class="page-title text-dark fw-bold mb-1">
                <i class="fa fa-credit-card text-info me-2"></i> পেমেন্ট ভেরিফিকেশন ও মানি রিসিপ্ট (Payments Queue)
            </h3>
        </div>

        <section class="content">
            <div class="box bg-white border-0 shadow-sm rounded-4 overflow-hidden">
                <div class="box-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>রিসিপ্ট ট্রানজেকশন</th>
                                    <th>প্রদানকারী</th>
                                    <th>উদ্দেশ্য</th>
                                    <th>মেথড ও ব্যাংক</th>
                                    <th>পরিমাণ</th>
                                    <th>স্ট্যাটাস</th>
                                    <th class="text-end">অ্যাকশন</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td><strong class="font-monospace">RCT-2026-0042</strong></td>
                                    <td>তানভীর আহমেদ</td>
                                    <td>পূর্বাচল গ্রিন সিটি ইনভেস্টমেন্ট ফান্ড</td>
                                    <td>City Bank • TRX-892174</td>
                                    <td class="font-monospace fw-bold text-success">৳ ৫,০০,০০০</td>
                                    <td><span class="badge bg-success bg-opacity-25 text-success">ভেরিফাইড (স্বয়ংক্রিয় জার্নাল সম্পন্ন)</span></td>
                                    <td class="text-end"><button class="btn btn-sm btn-outline-success">রিসিপ্ট ডাউনলোড</button></td>
                                </tr>
                                <tr>
                                    <td><strong class="font-monospace">RCT-2026-0043</strong></td>
                                    <td>মো. রোকনুজ্জামান</td>
                                    <td>ফ্ল্যাট ৪বি মার্চ কিস্তি পেমেন্ট</td>
                                    <td>BRAC Bank • CHQ-7782910</td>
                                    <td class="font-monospace fw-bold text-success">৳ ২,৫০,০০০</td>
                                    <td><span class="badge bg-warning bg-opacity-25 text-warning">Pending Review</span></td>
                                    <td class="text-end"><button class="btn btn-sm btn-success" onclick="alert('পেমেন্ট সফলভাবে ভেরিফাই ও ডেবিট ব্যাংক (1020) ক্রেডিট রেভিনিউ (4010) জার্নাল প্রস্তুত!'); location.reload();">অনুমোদন</button></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </section>
    </div>
</div>
@endsection
