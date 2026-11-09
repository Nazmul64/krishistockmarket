@extends('layouts.backend.app')

@section('content')
<div class="content-wrapper">
    <div class="container-full">
        <div class="content-header">
            <h3 class="page-title text-dark fw-bold mb-1">
                <i class="fa fa-calculator text-primary me-2"></i> ডাবল-এন্ট্রি হিসাব ও চার্ট অব একাউন্টস (Chart of Accounts)
            </h3>
        </div>

        <section class="content">
            <div class="row g-4">
                <div class="col-md-6 col-12">
                    <div class="box bg-white border-0 shadow-sm rounded-4 p-4">
                        <h5 class="fw-bold text-success mb-3"><i class="fa fa-arrow-down me-1"></i> সম্পদ (Assets - 1000 Series)</h5>
                        <ul class="list-group list-group-flush small">
                            <li class="list-group-item d-flex justify-content-between"><span>1010 - ক্যাশ ইন হ্যান্ড (Cash)</span><strong>৳ ৫,৫০,০০০</strong></li>
                            <li class="list-group-item d-flex justify-content-between"><span>1020 - ব্যাংক একাউন্টস (City, BRAC, EBL)</span><strong>৳ ৮,৮০,৫০,০০০</strong></li>
                            <li class="list-group-item d-flex justify-content-between"><span>1030 - অ্যাকাউন্টস রিসিভেবল (বকেয়া কিস্তি)</span><strong>৳ ৩৫,৫২,০০,০০০</strong></li>
                            <li class="list-group-item d-flex justify-content-between"><span>1040 - প্রপার্টি ও জমি ইনভেন্টরি</span><strong>৳ ৬৫,০০,০০,০০০</strong></li>
                        </ul>
                    </div>
                </div>

                <div class="col-md-6 col-12">
                    <div class="box bg-white border-0 shadow-sm rounded-4 p-4">
                        <h5 class="fw-bold text-danger mb-3"><i class="fa fa-arrow-up me-1"></i> দায় ও মূলধন (Liabilities & Equity)</h5>
                        <ul class="list-group list-group-flush small">
                            <li class="list-group-item d-flex justify-content-between"><span>2010 - অ্যাকাউন্টস পেয়েবল (ভেন্ডর বিল)</span><strong>৳ ৩,২০,০০,০০০</strong></li>
                            <li class="list-group-item d-flex justify-content-between"><span>2020 - ইনভেস্টর ক্যাপিটাল পেয়েবল</span><strong>৳ ৪২,৫০,০০,০০০</strong></li>
                            <li class="list-group-item d-flex justify-content-between"><span>3010 - শেয়ারহোল্ডার ইকুইটি ও রিটেইনড আর্নিং</span><strong>৳ ৫২,৭০,৫০,০০০</strong></li>
                        </ul>
                    </div>
                </div>
            </div>
        </section>
    </div>
</div>
@endsection
