@extends(Auth::check() ? 'layouts.backend.app' : 'layouts.frontend.app')

@section('content')
@if(Auth::check())
<div class="content-wrapper">
    <div class="container-full">
        <section class="content py-3">
@endif
<div class="corporate-page-wrap py-5 bg-light">
    <div class="container">
        <!-- Header -->
        <div class="text-center mb-5">
            <span class="badge bg-primary-subtle text-primary px-3 py-2 rounded-pill fw-bold font-size-14 mb-2">
                <i class="fa fa-book me-1"></i> সাধারণ নিয়মাবলী ও সার্ভিস পলিসি
            </span>
            <h2 class="fw-bold display-6 text-dark mt-2">আবেদন নির্দেশিকা (Application Guidelines)</h2>
            <p class="text-muted max-w-700 mx-auto font-size-16">
                এজেন্ট, কর্মচারী নিয়োগ, মেম্বারশিপ কার্ড, প্রপার্টি ইনভেস্টর ও শেয়ারহোল্ডার আবেদনের ধাপে ধাপে নিয়মাবলী ও অনুমোদন প্রক্রিয়া।
            </p>
        </div>

        <!-- 5 Key Guidelines Accordion / Cards -->
        <div class="row g-4 justify-content-center">
            <div class="col-lg-10">

                <!-- 1. Agent Application -->
                <div class="card border-0 shadow-sm rounded-4 p-4 mb-4 bg-white">
                    <div class="d-flex align-items-center gap-3 mb-3">
                        <span class="badge bg-success rounded-circle p-3 font-size-16"><i class="fa fa-shopping-bag text-white"></i></span>
                        <div>
                            <span class="badge bg-light text-muted fw-bold">নির্দেশিকা ০১</span>
                            <h4 class="fw-bold text-dark mb-0">এজেন্ট ও ডিলার আবেদন প্রক্রিয়া (Agent & Dealer Application)</h4>
                        </div>
                    </div>
                    <p class="text-muted font-size-15 leading-relaxed">
                        দোকানদার, ডিলার ও অনুমোদিত বিক্রয় প্রতিনিধিরা প্রতিষ্ঠানের সাধারণ দোকান অথবা নিজস্ব প্রজেক্ট পণ্য বিক্রয় একাউন্টের জন্য আবেদন করতে পারেন।
                    </p>
                    <div class="row g-3 small">
                        <div class="col-md-6">
                            <div class="p-3 bg-light rounded-3">
                                <strong class="text-dark d-block mb-1">প্রয়োজনীয় নথিপত্র:</strong>
                                <ul class="mb-0 text-muted ps-3">
                                    <li>দোকান বা ব্যবসা প্রতিষ্ঠানের ট্রেড লাইসেন্স</li>
                                    <li>এজেন্টের জাতীয় পরিচয়পত্র (NID) ও পাসপোর্ট সাইজ ছবি</li>
                                    <li>দোকানের ছবি ও সঠিক ব্যবসায়িক ঠিকানা</li>
                                </ul>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="p-3 bg-light rounded-3">
                                <strong class="text-dark d-block mb-1">অনুমোদন ও সুবিধা:</strong>
                                <ul class="mb-0 text-muted ps-3">
                                    <li>ফিল্ড ভেরিফিকেশন সাপেক্ষে ৪৮ ঘণ্টার মধ্যে অনুমোদন</li>
                                    <li>লাইভ স্টক ইনভেন্টরি ও প্রোডাক্ট ব্যালেন্স ড্যাশবোর্ড</li>
                                    <li>কমিশন হিসাব ও নিয়মিত সেটেলমেন্ট সুবিধা</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- 2. Employee Recruitment -->
                <div class="card border-0 shadow-sm rounded-4 p-4 mb-4 bg-white">
                    <div class="d-flex align-items-center gap-3 mb-3">
                        <span class="badge bg-primary rounded-circle p-3 font-size-16"><i class="fa fa-briefcase text-white"></i></span>
                        <div>
                            <span class="badge bg-light text-muted fw-bold">নির্দেশিকা ০২</span>
                            <h4 class="fw-bold text-dark mb-0">কর্মচারী নিয়োগ ও ক্যারিয়ার আবেদন (HR Recruitment Guidelines)</h4>
                        </div>
                    </div>
                    <p class="text-muted font-size-15 leading-relaxed">
                        প্রতিষ্ঠানের কৃষি ফার্ম, প্রপার্টি প্রজেক্ট, আইটি, হিসাব ও বিপণন বিভাগে মেধা ও সততার ভিত্তিতে নিয়মিত নিয়োগ প্রদান করা হয়।
                    </p>
                    <div class="row g-3 small">
                        <div class="col-md-6">
                            <div class="p-3 bg-light rounded-3">
                                <strong class="text-dark d-block mb-1">আবেদন প্রক্রিয়া:</strong>
                                <ul class="mb-0 text-muted ps-3">
                                    <li>অনলাইন ক্যারিয়ার পোর্টাল থেকে সরাসরি পদের জন্য আবেদন</li>
                                    <li>আপডেটেড সিভি (CV/Resume) ও শিক্ষাগত সনদ আপলোড</li>
                                    <li>প্রয়োজনে প্রাথমিক লিখিত বা অনলাইন ইন্টারভিউ</li>
                                </ul>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="p-3 bg-light rounded-3">
                                <strong class="text-dark d-block mb-1">সুবিধাসমূহ:</strong>
                                <ul class="mb-0 text-muted ps-3">
                                    <li>আকর্ষণীয় বেতন কাঠামো ও উৎসব বোনাস</li>
                                    <li>মাসিক প্রভিডেন্ট ফান্ড (P/F) সুবিধা ও প্রাতিষ্ঠানিক ম্যাচিং</li>
                                    <li>চিকিৎসা ও পারফরম্যান্স প্রণোদনা</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="mt-3">
                        <a href="{{ route('career') }}" class="btn btn-outline-primary btn-sm rounded-pill px-4 fw-bold">
                            বর্তমান চাকুরির সুযোগ দেখুন <i class="fa fa-arrow-right ms-1"></i>
                        </a>
                    </div>
                </div>

                <!-- 3. SME & Gold Card Application -->
                <div class="card border-0 shadow-sm rounded-4 p-4 mb-4 bg-white">
                    <div class="d-flex align-items-center gap-3 mb-3">
                        <span class="badge bg-warning rounded-circle p-3 font-size-16"><i class="fa fa-credit-card text-dark"></i></span>
                        <div>
                            <span class="badge bg-light text-muted fw-bold">নির্দেশিকা ০৩</span>
                            <h4 class="fw-bold text-dark mb-0">SME ও Gold Card আবেদন নির্দেশিকা (Membership Cards)</h4>
                        </div>
                    </div>
                    <p class="text-muted font-size-15 leading-relaxed">
                        ডিজিটাল মেম্বারশিপ কার্ড গ্রাহককে উপহার দেয় বিশেষ মূল্যছাড়, প্রোডাক্ট অর্ডার সুবিধা এবং অগ্রাধিকারভিত্তিক সাপোর্ট।
                    </p>
                    <div class="table-responsive">
                        <table class="table table-bordered small mb-3">
                            <thead class="table-light">
                                <tr>
                                    <th>কার্ডের ধরন</th>
                                    <th>নির্ধারিত ফি</th>
                                    <th>প্রয়োজনীয় তথ্য</th>
                                    <th>প্রধান সুবিধা</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td class="fw-bold text-success">SME Card</td>
                                    <td>৩০০ টাকা (প্রাথমিক বিকল্প: ৫০ টাকা)</td>
                                    <td>নাম, মোবাইল, ঠিকানা ও পরিচিতি</td>
                                    <td>ডিজিটাল কার্ড, ওয়ালেট ব্যালেন্স ট্র্যাকিং, অর্ডার সুবিধা</td>
                                </tr>
                                <tr>
                                    <td class="fw-bold text-warning">SME Gold Card</td>
                                    <td>১,০০০ টাকা</td>
                                    <td>নাম, NID, সাম্প্রতিক ছবি, মোবাইল, ঠিকানা</td>
                                    <td>লাক্সারি কার্ড, ইনভেস্টমেন্ট ট্র্যাকিং, সর্বোচ্চ অগ্রাধিকার</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <a href="{{ route('apply.card') }}" class="btn btn-warning text-dark btn-sm rounded-pill px-4 fw-bold">
                        কার্ডের জন্য আবেদন করুন <i class="fa fa-arrow-right ms-1"></i>
                    </a>
                </div>

                <!-- 4. Property Card & Shareholder Guidelines -->
                <div class="card border-0 shadow-sm rounded-4 p-4 mb-4 bg-white">
                    <div class="d-flex align-items-center gap-3 mb-3">
                        <span class="badge bg-info rounded-circle p-3 font-size-16"><i class="fa fa-pie-chart text-white"></i></span>
                        <div>
                            <span class="badge bg-light text-muted fw-bold">নির্দেশিকা ০৪</span>
                            <h4 class="fw-bold text-dark mb-0">Property Card, Shareholder ও Partnership আবেদন</h4>
                        </div>
                    </div>
                    <p class="text-muted font-size-15 leading-relaxed">
                        বৃহৎ যৌথ বিনিয়োগ প্রকল্প এবং প্রতিষ্ঠানের ১০০টি মূল শেয়ারের অংশীদার হওয়ার সুবর্ণ সুযোগ।
                    </p>
                    <div class="p-3 bg-light rounded-3 small mb-3">
                        <ul class="mb-0 text-muted ps-3">
                            <li class="mb-1"><strong>Property Card (রেজিস্ট্রেশন ফি ৩,০০০ টাকা):</strong> ১০টি নির্ধারিত খাতে বিনিয়োগ, মুনাফা নিরীক্ষা ও নিয়মিত উত্তোলন ড্যাশবোর্ড। নমিনি তথ্য ও এনআইডি বাধ্যতামূলক।</li>
                            <li class="mb-1"><strong>Shareholder Profile (ফি ২,০০০ টাকা + প্রতি শেয়ার ৫,০০০ টাকা):</strong> মোট ১০০টি শেয়ারের বিপরীতে নির্দিষ্ট শেয়ার বরাদ্দ এবং প্রতি শেয়ারের অফিস খরচ ৩,০০০ টাকা।</li>
                            <li class="mb-1"><strong>Partnership Membership:</strong> যৌথ ভেঞ্চার চুক্তিপত্র, মূলধন অবদান এবং লাভ-লোকসান অনুপাতভিত্তিক কেন্দ্রীয় একাউন্ট সুবিধা।</li>
                        </ul>
                    </div>
                </div>

                <!-- 5. Verification & Approval Workflow -->
                <div class="card border-0 shadow-sm rounded-4 p-4 bg-white text-center">
                    <h5 class="fw-bold text-dark mb-3">আবেদন যাচাই ও অনুমোদন ধাপসমূহ</h5>
                    <div class="row g-3 text-start small">
                        <div class="col-md-3">
                            <div class="p-3 border rounded-3 text-center h-100">
                                <span class="badge bg-secondary rounded-circle p-2 mb-2">১</span>
                                <strong class="d-block text-dark">অনলাইন আবেদন</strong>
                                <span class="text-muted">ফর্ম পূরণ ও পেমেন্ট স্লিপ প্রদান।</span>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="p-3 border rounded-3 text-center h-100">
                                <span class="badge bg-primary rounded-circle p-2 mb-2">২</span>
                                <strong class="d-block text-dark">নথি ও পেমেন্ট যাচাই</strong>
                                <span class="text-muted">অ্যাকাউন্টস বিভাগ কর্তৃক TrxID যাচাই।</span>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="p-3 border rounded-3 text-center h-100">
                                <span class="badge bg-warning rounded-circle p-2 mb-2 text-dark">৩</span>
                                <strong class="d-block text-dark">প্রশাসনিক অনুমোদন</strong>
                                <span class="text-muted">ইউনিক Customer/Member ID প্রদান।</span>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="p-3 border rounded-3 text-center h-100">
                                <span class="badge bg-success rounded-circle p-2 mb-2">৪</span>
                                <strong class="d-block text-dark">ডিজিটাল সেবা চালু</strong>
                                <span class="text-muted">ড্যাশবোর্ড ও ডিজিটাল কার্ড অ্যাক্টিভেশন।</span>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>
@if(Auth::check())
        </section>
    </div>
</div>
@endif
@endsection

