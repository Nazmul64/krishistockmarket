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
            <span class="badge bg-success-subtle text-success px-3 py-2 rounded-pill fw-bold font-size-14 mb-2">
                <i class="fa fa-laptop me-1"></i> ডিজিটাল সার্ভিস পোর্টাল
            </span>
            <h2 class="fw-bold display-6 text-dark mt-2">অনলাইন সেবাসমূহ (Online Services)</h2>
            <p class="text-muted max-w-700 mx-auto font-size-16">
                সদস্য নিবন্ধন, ডিজিটাল কার্ড যাচাই, ওয়ালেট ব্যালেন্স, প্রকল্পের আপডেট এবং দ্রুততম অভিযোগ প্রতিকার সংক্রান্ত সকল ডিজিটাল সেবা।
            </p>
        </div>

        <!-- 6 Main Digital Services Cards -->
        <div class="row g-4 mb-5">
            <!-- 1. Membership Application -->
            <div class="col-md-6 col-lg-4">
                <div class="card h-100 border-0 shadow-sm rounded-4 p-4 bg-white transition-all hover-shadow">
                    <div class="mb-3 text-success p-3 rounded-circle bg-success-subtle d-inline-block">
                        <i class="fa fa-credit-card fa-2x"></i>
                    </div>
                    <h5 class="fw-bold text-dark mb-2">অনলাইন মেম্বারশিপ আবেদন</h5>
                    <p class="text-muted small mb-4 flex-grow-1">
                        SME, Gold, Property Card অথবা Shareholder একাউন্টের জন্য অনলাইনে ফর্ম পূরণ ও পেমেন্ট স্লিপ দিয়ে সরাসরি আবেদন করুন।
                    </p>
                    <a href="{{ route('apply.card') }}" class="btn btn-success btn-sm rounded-pill px-4 fw-bold w-100">
                        কার্ড আবেদন করুন <i class="fa fa-arrow-right ms-1"></i>
                    </a>
                </div>
            </div>

            <!-- 2. Digital Card & Verification -->
            <div class="col-md-6 col-lg-4">
                <div class="card h-100 border-0 shadow-sm rounded-4 p-4 bg-white transition-all hover-shadow">
                    <div class="mb-3 text-primary p-3 rounded-circle bg-primary-subtle d-inline-block">
                        <i class="fa fa-qrcode fa-2x"></i>
                    </div>
                    <h5 class="fw-bold text-dark mb-2">ডিজিটাল কার্ড ও কিউআর যাচাই</h5>
                    <p class="text-muted small mb-4 flex-grow-1">
                        আপনার কার্ডের Customer ID বা QR কোড স্ক্যান করে তাৎক্ষণিকভাবে মেম্বারশিপের সত্যতা ও বৈধতা যাচাই করুন।
                    </p>
                    <a href="{{ route('verify.card') }}" class="btn btn-primary btn-sm rounded-pill px-4 fw-bold w-100">
                        কার্ড যাচাই করুন <i class="fa fa-search ms-1"></i>
                    </a>
                </div>
            </div>

            <!-- 3. Balance & Wallet Statement -->
            <div class="col-md-6 col-lg-4">
                <div class="card h-100 border-0 shadow-sm rounded-4 p-4 bg-white transition-all hover-shadow">
                    <div class="mb-3 text-warning p-3 rounded-circle bg-warning-subtle d-inline-block">
                        <i class="fa fa-money fa-2x text-dark"></i>
                    </div>
                    <h5 class="fw-bold text-dark mb-2">ব্যালেন্স ও ওয়ালেট স্টেটমেন্ট</h5>
                    <p class="text-muted small mb-4 flex-grow-1">
                        সদস্য একাউন্টের মোট জমার পরিমাণ, অর্জিত লাভ, লেনদেনের বিস্তারিত ইতিহাস এবং পিডিএফ স্টেটমেন্ট ডাউনলোড।
                    </p>
                    @auth
                        <a href="{{ route('home') }}" class="btn btn-warning text-dark btn-sm rounded-pill px-4 fw-bold w-100">
                            ড্যাশবোর্ডে প্রবেশ করুন <i class="fa fa-dashboard ms-1"></i>
                        </a>
                    @else
                        <a href="{{ route('login') }}" class="btn btn-warning text-dark btn-sm rounded-pill px-4 fw-bold w-100">
                            লগইন করে ব্যালেন্স দেখুন <i class="fa fa-sign-in ms-1"></i>
                        </a>
                    @endauth
                </div>
            </div>

            <!-- 4. Projects & Investment Info -->
            <div class="col-md-6 col-lg-4">
                <div class="card h-100 border-0 shadow-sm rounded-4 p-4 bg-white transition-all hover-shadow">
                    <div class="mb-3 text-info p-3 rounded-circle bg-info-subtle d-inline-block">
                        <i class="fa fa-line-chart fa-2x text-info"></i>
                    </div>
                    <h5 class="fw-bold text-dark mb-2">বিনিয়োগ ও প্রকল্প পর্যবেক্ষণ</h5>
                    <p class="text-muted small mb-4 flex-grow-1">
                        ১০টি নির্ধারিত খাতে কৃষি, ডেইরি, গ্রিনহাউস ও প্রপার্টি প্রকল্পের মাঠপর্যায়ের অগ্রগতি প্রতিবেদন ও লভ্যাংশ হিসাব।
                    </p>
                    <a href="{{ route('investment.sectors') }}" class="btn btn-outline-info btn-sm rounded-pill px-4 fw-bold w-100">
                        প্রকল্পসমূহ দেখুন <i class="fa fa-arrow-right ms-1"></i>
                    </a>
                </div>
            </div>

            <!-- 5. Online Grievance & Feedback -->
            <div class="col-md-6 col-lg-4">
                <div class="card h-100 border-0 shadow-sm rounded-4 p-4 bg-white transition-all hover-shadow">
                    <div class="mb-3 text-danger p-3 rounded-circle bg-danger-subtle d-inline-block">
                        <i class="fa fa-comments-o fa-2x text-danger"></i>
                    </div>
                    <h5 class="fw-bold text-dark mb-2">অভিযোগ ও পরামর্শ প্রতিকার</h5>
                    <p class="text-muted small mb-4 flex-grow-1">
                        সেবা সংক্রান্ত যেকোনো অভিযোগ বা উন্নয়নমূলক পরামর্শ সরাসরি কর্তৃপক্ষের কাছে জমা দিন এবং ট্র্যাকিং কোড দিয়ে অগ্রগতি জানুন।
                    </p>
                    <a href="{{ route('grievance.form') }}" class="btn btn-danger btn-sm rounded-pill px-4 fw-bold w-100">
                        অভিযোগ / পরামর্শ দিন <i class="fa fa-paper-plane ms-1"></i>
                    </a>
                </div>
            </div>

            <!-- 6. Notice Board & Announcements -->
            <div class="col-md-6 col-lg-4">
                <div class="card h-100 border-0 shadow-sm rounded-4 p-4 bg-white transition-all hover-shadow">
                    <div class="mb-3 text-secondary p-3 rounded-circle bg-secondary-subtle d-inline-block">
                        <i class="fa fa-bullhorn fa-2x text-secondary"></i>
                    </div>
                    <h5 class="fw-bold text-dark mb-2">অফিসিয়াল নোটিশ বোর্ড</h5>
                    <p class="text-muted small mb-4 flex-grow-1">
                        প্রতিষ্ঠানের আর্থিক নিরীক্ষা, লভ্যাংশ ঘোষণা, সাইবার নিরাপত্তা অ্যালার্ট ও জরুরি প্রশাসনিক নোটিশের আর্কাইভ।
                    </p>
                    <a href="{{ route('corporate.notices') }}" class="btn btn-outline-secondary btn-sm rounded-pill px-4 fw-bold w-100">
                        নোটিশ বোর্ড দেখুন <i class="fa fa-bell ms-1"></i>
                    </a>
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

