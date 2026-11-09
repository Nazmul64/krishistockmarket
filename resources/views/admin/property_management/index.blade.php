@extends('layouts.backend.app')

@section('content')
<div class="content-wrapper">
    <div class="container-full">
        <!-- Content Header -->
        <div class="content-header">
            <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
                <div class="me-auto">
                    <h3 class="page-title text-dark fw-bold mb-1">
                        <i class="fa fa-building text-success me-2"></i> রিয়েল এস্টেট ও প্রপার্টি ম্যানেজমেন্ট (LandShare ERP)
                    </h3>
                    <div class="d-inline-block align-items-center">
                        <nav>
                            <ol class="breadcrumb mb-0 p-0 bg-transparent">
                                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}"><i class="mdi mdi-home-outline"></i> ড্যাশবোর্ড</a></li>
                                <li class="breadcrumb-item active" aria-current="page">রিয়েল এস্টেট ম্যানেজমেন্ট</li>
                            </ol>
                        </nav>
                    </div>
                </div>

                <div class="d-flex gap-2">
                    <a href="{{ route('admin.properties.index') }}" class="btn btn-success btn-sm shadow-sm">
                        <i class="fa fa-plus-circle me-1"></i> নতুন প্রপার্টি এন্ট্রি
                    </a>
                    <a href="{{ route('admin.projects.index') }}" class="btn btn-primary btn-sm shadow-sm">
                        <i class="fa fa-folder-open me-1"></i> নতুন প্রজেক্ট এন্ট্রি
                    </a>
                </div>
            </div>
        </div>

        <!-- Main Content -->
        <section class="content">
            
            <!-- Quick Summary KPI Cards -->
            <div class="row">
                <div class="col-xl-3 col-md-6 col-12">
                    <div class="box bg-white border-0 shadow-sm rounded-4 border-top border-4 border-success">
                        <div class="box-body p-4">
                            <div class="d-flex align-items-center justify-content-between">
                                <div>
                                    <span class="text-muted text-uppercase fw-bold font-size-11">মোট নিবন্ধিত প্রপার্টি</span>
                                    <h3 class="fw-bold text-dark my-1">২৪ টি</h3>
                                    <small class="text-success"><i class="fa fa-check-circle me-1"></i> জমি, প্লট ও ভবন</small>
                                </div>
                                <div class="rounded-circle bg-success bg-opacity-10 text-success p-3 text-center" style="width: 52px; height: 52px;">
                                    <i class="fa fa-building-o font-size-22"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-xl-3 col-md-6 col-12">
                    <div class="box bg-white border-0 shadow-sm rounded-4 border-top border-4 border-primary">
                        <div class="box-body p-4">
                            <div class="d-flex align-items-center justify-content-between">
                                <div>
                                    <span class="text-muted text-uppercase fw-bold font-size-11">চলমান প্রকল্প (Projects)</span>
                                    <h3 class="fw-bold text-primary my-1">৬ টি</h3>
                                    <small class="text-primary"><i class="fa fa-clock-o me-1"></i> ৩টি কনস্ট্রাকশন চলমান</small>
                                </div>
                                <div class="rounded-circle bg-primary bg-opacity-10 text-primary p-3 text-center" style="width: 52px; height: 52px;">
                                    <i class="fa fa-folder-open-o font-size-22"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-xl-3 col-md-6 col-12">
                    <div class="box bg-white border-0 shadow-sm rounded-4 border-top border-4 border-warning">
                        <div class="box-body p-4">
                            <div class="d-flex align-items-center justify-content-between">
                                <div>
                                    <span class="text-muted text-uppercase fw-bold font-size-11">মোট সংগৃহীত বিনিয়োগ</span>
                                    <h3 class="fw-bold text-warning my-1 font-monospace">৳ ৪২.৫০ কোটি</h3>
                                    <small class="text-muted">৮৫ জন সক্রিয় ইনভেস্টর</small>
                                </div>
                                <div class="rounded-circle bg-warning bg-opacity-10 text-warning p-3 text-center" style="width: 52px; height: 52px;">
                                    <i class="fa fa-line-chart font-size-22"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-xl-3 col-md-6 col-12">
                    <div class="box bg-white border-0 shadow-sm rounded-4 border-top border-4 border-danger">
                        <div class="box-body p-4">
                            <div class="d-flex align-items-center justify-content-between">
                                <div>
                                    <span class="text-muted text-uppercase fw-bold font-size-11">মোট ফ্ল্যাট / প্লট বিক্রি</span>
                                    <h3 class="fw-bold text-danger my-1 font-monospace">৳ ৬৭.৭০ কোটি</h3>
                                    <small class="text-muted">১৪২ টি সফল বুকিং</small>
                                </div>
                                <div class="rounded-circle bg-danger bg-opacity-10 text-danger p-3 text-center" style="width: 52px; height: 52px;">
                                    <i class="fa fa-shopping-cart font-size-22"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Fast Action Navigation Grid -->
            <div class="box bg-white border-0 shadow-sm rounded-4 mb-4">
                <div class="box-header with-border py-3 px-4">
                    <h5 class="box-title fw-bold text-dark">
                        <i class="fa fa-th-large text-warning me-2"></i> রিয়েল এস্টেট কন্ট্রোল হাব ও সকল সাব-মডিউল
                    </h5>
                    <p class="text-muted small mb-0">অ্যাডমিন প্যানেল থেকে জমি, ফ্ল্যাট, ইনভেস্টমেন্ট ও হিসাব পরিচালনার জন্য সরাসরি নিচের অপশনগুলো ব্যবহার করুন:</p>
                </div>
                <div class="box-body p-4">
                    <div class="row g-3">
                        
                        <div class="col-xl-3 col-md-4 col-sm-6">
                            <a href="{{ route('admin.properties.index') }}" class="card border p-3 rounded-3 text-decoration-none hover-shadow text-dark h-100 d-block transition-all">
                                <div class="d-flex align-items-center gap-3">
                                    <div class="rounded p-2 bg-success bg-opacity-10 text-success">
                                        <i class="fa fa-map-marker font-size-24"></i>
                                    </div>
                                    <div>
                                        <h6 class="fw-bold mb-1">জমি ও প্রপার্টি ক্যাটালগ</h6>
                                        <small class="text-muted">জমি, প্লট, ফ্ল্যাট ও ভবন তৈরি</small>
                                    </div>
                                </div>
                            </a>
                        </div>

                        <div class="col-xl-3 col-md-4 col-sm-6">
                            <a href="{{ route('admin.properties.units') }}" class="card border p-3 rounded-3 text-decoration-none hover-shadow text-dark h-100 d-block transition-all">
                                <div class="d-flex align-items-center gap-3">
                                    <div class="rounded p-2 bg-primary bg-opacity-10 text-primary">
                                        <i class="fa fa-cubes font-size-24"></i>
                                    </div>
                                    <div>
                                        <h6 class="fw-bold mb-1">ফ্ল্যাট ও একক ইউনিট</h6>
                                        <small class="text-muted">ফ্লোর, সাইজ, বেডরুম ও রেট</small>
                                    </div>
                                </div>
                            </a>
                        </div>

                        <div class="col-xl-3 col-md-4 col-sm-6">
                            <a href="{{ route('admin.projects.index') }}" class="card border p-3 rounded-3 text-decoration-none hover-shadow text-dark h-100 d-block transition-all">
                                <div class="d-flex align-items-center gap-3">
                                    <div class="rounded p-2 bg-warning bg-opacity-10 text-warning">
                                        <i class="fa fa-tasks font-size-24"></i>
                                    </div>
                                    <div>
                                        <h6 class="fw-bold mb-1">প্রকল্প ব্যবস্থাপনা (Projects)</h6>
                                        <small class="text-muted">বাজেট, অগ্রগতি ও মাইলস্টোন</small>
                                    </div>
                                </div>
                            </a>
                        </div>

                        <div class="col-xl-3 col-md-4 col-sm-6">
                            <a href="{{ route('admin.investments.offers') }}" class="card border p-3 rounded-3 text-decoration-none hover-shadow text-dark h-100 d-block transition-all">
                                <div class="d-flex align-items-center gap-3">
                                    <div class="rounded p-2 bg-info bg-opacity-10 text-info">
                                        <i class="fa fa-bullhorn font-size-24"></i>
                                    </div>
                                    <div>
                                        <h6 class="fw-bold mb-1">বিনিয়োগ অফারসমূহ</h6>
                                        <small class="text-muted">ইনভেস্টমেন্ট ক্যাম্পেইন প্রকাশ</small>
                                    </div>
                                </div>
                            </a>
                        </div>

                        <div class="col-xl-3 col-md-4 col-sm-6">
                            <a href="{{ route('admin.investors.index') }}" class="card border p-3 rounded-3 text-decoration-none hover-shadow text-dark h-100 d-block transition-all">
                                <div class="d-flex align-items-center gap-3">
                                    <div class="rounded p-2 bg-secondary bg-opacity-10 text-secondary">
                                        <i class="fa fa-users font-size-24"></i>
                                    </div>
                                    <div>
                                        <h6 class="fw-bold mb-1">বিনিয়োগকারী ও KYC</h6>
                                        <small class="text-muted">এনআইডি ও ব্যাংক অনুমোদন</small>
                                    </div>
                                </div>
                            </a>
                        </div>

                        <div class="col-xl-3 col-md-4 col-sm-6">
                            <a href="{{ route('admin.sales.bookings') }}" class="card border p-3 rounded-3 text-decoration-none hover-shadow text-dark h-100 d-block transition-all">
                                <div class="d-flex align-items-center gap-3">
                                    <div class="rounded p-2 bg-danger bg-opacity-10 text-danger">
                                        <i class="fa fa-handshake-o font-size-24"></i>
                                    </div>
                                    <div>
                                        <h6 class="fw-bold mb-1">বিক্রি, বুকিং ও কিস্তি</h6>
                                        <small class="text-muted">ডাউন পেমেন্ট ও হ্যান্ডওভার</small>
                                    </div>
                                </div>
                            </a>
                        </div>

                        <div class="col-xl-3 col-md-4 col-sm-6">
                            <a href="{{ route('admin.profit.reconciliation') }}" class="card border p-3 rounded-3 text-decoration-none hover-shadow text-dark h-100 d-block transition-all">
                                <div class="d-flex align-items-center gap-3">
                                    <div class="rounded p-2 bg-success bg-opacity-10 text-success">
                                        <i class="fa fa-percent font-size-24"></i>
                                    </div>
                                    <div>
                                        <h6 class="fw-bold mb-1">মুনাফা বণ্টন ইঞ্জিন</h6>
                                        <small class="text-muted">Maker-Checker ডুয়েল অনুমোদন</small>
                                    </div>
                                </div>
                            </a>
                        </div>

                        <div class="col-xl-3 col-md-4 col-sm-6">
                            <a href="{{ route('admin.property.accounting') }}" class="card border p-3 rounded-3 text-decoration-none hover-shadow text-dark h-100 d-block transition-all">
                                <div class="d-flex align-items-center gap-3">
                                    <div class="rounded p-2 bg-primary bg-opacity-10 text-primary">
                                        <i class="fa fa-calculator font-size-24"></i>
                                    </div>
                                    <div>
                                        <h6 class="fw-bold mb-1">ডাবল-এন্ট্রি হিসাব</h6>
                                        <small class="text-muted">Chart of Accounts ও জার্নাল</small>
                                    </div>
                                </div>
                            </a>
                        </div>

                        <div class="col-xl-3 col-md-4 col-sm-6">
                            <a href="{{ route('admin.property.payments') }}" class="card border p-3 rounded-3 text-decoration-none hover-shadow text-dark h-100 d-block transition-all">
                                <div class="d-flex align-items-center gap-3">
                                    <div class="rounded p-2 bg-info bg-opacity-10 text-info">
                                        <i class="fa fa-credit-card font-size-24"></i>
                                    </div>
                                    <div>
                                        <h6 class="fw-bold mb-1">পেমেন্ট ভেরিফিকেশন</h6>
                                        <small class="text-muted">স্বয়ংক্রিয় মানি রিসিপ্ট জেনারেট</small>
                                    </div>
                                </div>
                            </a>
                        </div>

                        <div class="col-xl-3 col-md-4 col-sm-6">
                            <a href="{{ route('admin.rentals.index') }}" class="card border p-3 rounded-3 text-decoration-none hover-shadow text-dark h-100 d-block transition-all">
                                <div class="d-flex align-items-center gap-3">
                                    <div class="rounded p-2 bg-warning bg-opacity-10 text-warning">
                                        <i class="fa fa-key font-size-24"></i>
                                    </div>
                                    <div>
                                        <h6 class="fw-bold mb-1">ভাড়া ও ভাড়াটিয়া লিজ</h6>
                                        <small class="text-muted">ভাড়া ইনভয়েস ও মেইনটেন্যান্স</small>
                                    </div>
                                </div>
                            </a>
                        </div>

                        <div class="col-xl-3 col-md-4 col-sm-6">
                            <a href="{{ route('admin.land_owners.index') }}" class="card border p-3 rounded-3 text-decoration-none hover-shadow text-dark h-100 d-block transition-all">
                                <div class="d-flex align-items-center gap-3">
                                    <div class="rounded p-2 bg-success bg-opacity-10 text-success">
                                        <i class="fa fa-id-card-o font-size-24"></i>
                                    </div>
                                    <div>
                                        <h6 class="fw-bold mb-1">জমি মালিক পার্টনারশিপ</h6>
                                        <small class="text-muted">যৌথ উদ্যোগ (JV) ও পাওয়ার</small>
                                    </div>
                                </div>
                            </a>
                        </div>

                        <div class="col-xl-3 col-md-4 col-sm-6">
                            <a href="{{ route('admin.legal.documents') }}" class="card border p-3 rounded-3 text-decoration-none hover-shadow text-dark h-100 d-block transition-all">
                                <div class="d-flex align-items-center gap-3">
                                    <div class="rounded p-2 bg-danger bg-opacity-10 text-danger">
                                        <i class="fa fa-file-text-o font-size-24"></i>
                                    </div>
                                    <div>
                                        <h6 class="fw-bold mb-1">আইনি দলিল ও পরচা ভল্ট</h6>
                                        <small class="text-muted">সাফ-কবলা, নামজারি ও নকশা</small>
                                    </div>
                                </div>
                            </a>
                        </div>

                    </div>
                </div>
            </div>

        </section>
    </div>
</div>
@endsection
