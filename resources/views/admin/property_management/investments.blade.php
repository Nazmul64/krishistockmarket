@extends('layouts.backend.app')

@section('content')
<div class="content-wrapper">
    <div class="container-full">
        <div class="content-header">
            <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
                <div class="me-auto">
                    <h3 class="page-title text-dark fw-bold mb-1">
                        <i class="fa fa-bullhorn text-info me-2"></i> উন্মুক্ত বিনিয়োগ অফার ও ক্যাম্পেইন (Investment Offers)
                    </h3>
                    <div class="d-inline-block align-items-center">
                        <nav>
                            <ol class="breadcrumb mb-0 p-0 bg-transparent">
                                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">ড্যাশবোর্ড</a></li>
                                <li class="breadcrumb-item"><a href="{{ route('admin.landshare.hub') }}">রিয়েল এস্টেট</a></li>
                                <li class="breadcrumb-item active" aria-current="page">বিনিয়োগ অফার</li>
                            </ol>
                        </nav>
                    </div>
                </div>

                <button type="button" class="btn btn-info btn-sm shadow-sm px-3 py-2 fw-bold text-white" data-bs-toggle="modal" data-bs-target="#modalAddOffer">
                    <i class="fa fa-plus-circle me-1"></i> নতুন অফার তৈরি করুন (Create Offer)
                </button>
            </div>
        </div>

        <section class="content">
            <div class="row g-4">
                <div class="col-xl-6 col-12">
                    <div class="box bg-white border-0 shadow-sm rounded-4 border-top border-4 border-success p-4">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <span class="badge bg-success bg-opacity-10 text-success">Project-based Investment</span>
                            <span class="badge bg-warning text-dark">ঝুঁকি: LOW</span>
                        </div>
                        <h4 class="fw-bold text-dark mb-1">পূর্বাচল গ্রিন সিটি আবাসন প্রকল্প ১ম ধাপ</h4>
                        <p class="text-muted small mb-3">প্রকল্প: পূর্বাচল গ্রিন সিটি (পূর্বাচল, ঢাকা)</p>
                        
                        <div class="mb-3">
                            <div class="d-flex justify-content-between small text-muted mb-1">
                                <span>সংগৃহীত ফান্ড:</span>
                                <span class="fw-bold text-success">৬৪% সম্পূর্ণ</span>
                            </div>
                            <div class="progress" style="height: 10px;">
                                <div class="progress-bar bg-success rounded-pill" style="width: 64%;"></div>
                            </div>
                        </div>

                        <div class="bg-light p-3 rounded-3 mb-3 small">
                            <div class="row text-center">
                                <div class="col-4 border-end">
                                    <span class="text-muted d-block font-size-11">টার্গেট ফান্ড</span>
                                    <strong class="font-monospace text-dark">৳ ৫.০০ কোটি</strong>
                                </div>
                                <div class="col-4 border-end">
                                    <span class="text-muted d-block font-size-11">সর্বনিম্ন বিনিয়োগ</span>
                                    <strong class="font-monospace text-success">৳ ২,০০,০০০</strong>
                                </div>
                                <div class="col-4">
                                    <span class="text-muted d-block font-size-11">মেয়াদকাল</span>
                                    <strong class="font-monospace text-dark">২৪ মাস</strong>
                                </div>
                            </div>
                        </div>
                        <button class="btn btn-outline-success btn-sm w-100">আবেদন তালিকা ও চুক্তিপত্র</button>
                    </div>
                </div>

                <div class="col-xl-6 col-12">
                    <div class="box bg-white border-0 shadow-sm rounded-4 border-top border-4 border-primary p-4">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <span class="badge bg-primary bg-opacity-10 text-primary">Property Ownership Share</span>
                            <span class="badge bg-warning text-dark">ঝুঁকি: MEDIUM</span>
                        </div>
                        <h4 class="fw-bold text-dark mb-1">উত্তরা রয়েল হাইটস লাক্সারি ফ্ল্যাট শেয়ার</h4>
                        <p class="text-muted small mb-3">প্রকল্প: উত্তরা সেক্টর ১৮ রয়েল হাইটস</p>
                        
                        <div class="mb-3">
                            <div class="d-flex justify-content-between small text-muted mb-1">
                                <span>সংগৃহীত ফান্ড:</span>
                                <span class="fw-bold text-primary">৭৫% সম্পূর্ণ</span>
                            </div>
                            <div class="progress" style="height: 10px;">
                                <div class="progress-bar bg-primary rounded-pill" style="width: 75%;"></div>
                            </div>
                        </div>

                        <div class="bg-light p-3 rounded-3 mb-3 small">
                            <div class="row text-center">
                                <div class="col-4 border-end">
                                    <span class="text-muted d-block font-size-11">টার্গেট ফান্ড</span>
                                    <strong class="font-monospace text-dark">৳ ৮.০০ কোটি</strong>
                                </div>
                                <div class="col-4 border-end">
                                    <span class="text-muted d-block font-size-11">সর্বনিম্ন বিনিয়োগ</span>
                                    <strong class="font-monospace text-success">৳ ৫,০০,০০০</strong>
                                </div>
                                <div class="col-4">
                                    <span class="text-muted d-block font-size-11">মেয়াদকাল</span>
                                    <strong class="font-monospace text-dark">১৮ মাস</strong>
                                </div>
                            </div>
                        </div>
                        <button class="btn btn-outline-primary btn-sm w-100">আবেদন তালিকা ও চুক্তিপত্র</button>
                    </div>
                </div>
            </div>
        </section>
    </div>
</div>

<div class="modal fade" id="modalAddOffer" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg rounded-4">
            <div class="modal-header bg-info text-white py-3 px-4">
                <h5 class="modal-title fw-bold mb-0">নতুন বিনিয়োগ অফার তৈরি (Create Offer)</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form onsubmit="event.preventDefault(); alert('বিনিয়োগ অফার সফলভাবে প্রকাশিত হয়েছে!'); bootstrap.Modal.getInstance(document.getElementById('modalAddOffer')).hide(); location.reload();">
                <div class="modal-body p-4">
                    <div class="row g-3">
                        <div class="col-12">
                            <label class="form-label small fw-bold">অফারের শিরোনাম</label>
                            <input type="text" required class="form-control" placeholder="যেমন: পূর্বাচল গ্রিন সিটি ১ম ধাপ">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-bold">প্রকল্প নির্বাচন</label>
                            <select class="form-select" required>
                                <option value="1">পূর্বাচল গ্রিন সিটি আবাসন প্রকল্প</option>
                                <option value="2">উত্তরা সেক্টর ১৮ রয়েল হাইটস</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-bold">বিনিয়োগ মডেল</label>
                            <select class="form-select" required>
                                <option value="1">প্রকল্প-ভিত্তিক অংশীদারিত্ব (Project-Based)</option>
                                <option value="2">আইনগত মালিকানা শেয়ার (Ownership Share)</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label small fw-bold">সর্বনিম্ন বিনিয়োগ (৳)</label>
                            <input type="number" required class="form-control font-monospace" value="100000">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label small fw-bold">টার্গেট ফান্ড (৳)</label>
                            <input type="number" required class="form-control font-monospace text-success fw-bold" value="50000000">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label small fw-bold">মেয়াদকাল (মাস)</label>
                            <input type="number" required class="form-control" value="24">
                        </div>
                    </div>
                </div>
                <div class="modal-footer bg-light py-3 px-4">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">বাতিল</button>
                    <button type="submit" class="btn btn-info px-4 fw-bold text-white">অফার প্রকাশ করুন</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
