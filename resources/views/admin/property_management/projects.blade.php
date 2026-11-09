@extends('layouts.backend.app')

@section('content')
<div class="content-wrapper">
    <div class="container-full">
        <div class="content-header">
            <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
                <div class="me-auto">
                    <h3 class="page-title text-dark fw-bold mb-1">
                        <i class="fa fa-folder-open text-primary me-2"></i> প্রকল্প সমূহ (Project Management)
                    </h3>
                    <div class="d-inline-block align-items-center">
                        <nav>
                            <ol class="breadcrumb mb-0 p-0 bg-transparent">
                                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">ড্যাশবোর্ড</a></li>
                                <li class="breadcrumb-item"><a href="{{ route('admin.landshare.hub') }}">রিয়েল এস্টেট</a></li>
                                <li class="breadcrumb-item active" aria-current="page">প্রকল্প সমূহ</li>
                            </ol>
                        </nav>
                    </div>
                </div>

                <button type="button" class="btn btn-primary btn-sm shadow-sm px-3 py-2 fw-bold" data-bs-toggle="modal" data-bs-target="#modalAddProject">
                    <i class="fa fa-plus-circle me-1"></i> নতুন প্রজেক্ট তৈরি করুন (Create Project)
                </button>
            </div>
        </div>

        <section class="content">
            <div class="row g-4">
                <div class="col-xl-4 col-md-6 col-12">
                    <div class="box bg-white border-0 shadow-sm rounded-4 h-100 border-top border-4 border-success">
                        <div class="box-body p-4">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <span class="badge bg-dark font-monospace">PRJ-PUR-2026</span>
                                <span class="badge bg-success bg-opacity-25 text-success">চলমান (Ongoing)</span>
                            </div>
                            <h5 class="fw-bold text-dark mb-2">পূর্বাচল গ্রিন সিটি আবাসন প্রকল্প</h5>
                            <p class="text-muted small mb-3">১০০ বিঘা জমির উপর আধুনিক গেটেড সিটি ও বাণিজ্যিক হাব।</p>
                            
                            <div class="mb-3">
                                <div class="d-flex justify-content-between small text-muted mb-1">
                                    <span>প্রগ্রেস:</span>
                                    <span class="fw-bold text-success">৩৫%</span>
                                </div>
                                <div class="progress" style="height: 8px;">
                                    <div class="progress-bar bg-success rounded-pill" style="width: 35%;"></div>
                                </div>
                            </div>

                            <div class="bg-light p-3 rounded-3 mb-3 small">
                                <div class="d-flex justify-content-between mb-1">
                                    <span class="text-muted">বাজেট:</span>
                                    <span class="fw-bold text-dark font-monospace">৳ ১৫.০০ কোটি</span>
                                </div>
                                <div class="d-flex justify-content-between">
                                    <span class="text-muted">টার্গেট কালেকশন:</span>
                                    <span class="fw-bold text-success font-monospace">৳ ১৯.৫০ কোটি</span>
                                </div>
                            </div>
                            <button class="btn btn-outline-success btn-sm w-100">মাইলস্টোন ও অডিট ভিউ</button>
                        </div>
                    </div>
                </div>

                <div class="col-xl-4 col-md-6 col-12">
                    <div class="box bg-white border-0 shadow-sm rounded-4 h-100 border-top border-4 border-primary">
                        <div class="box-body p-4">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <span class="badge bg-dark font-monospace">PRJ-UTT-2026</span>
                                <span class="badge bg-primary bg-opacity-25 text-primary">প্ল্যানিং (Planning)</span>
                            </div>
                            <h5 class="fw-bold text-dark mb-2">উত্তরা সেক্টর ১৮ রয়েল হাইটস</h5>
                            <p class="text-muted small mb-3">১৪ তলা বিশিষ্ট লাক্সারি কন্ডোমিনিয়াম ও শপিং কমপ্লেক্স।</p>
                            
                            <div class="mb-3">
                                <div class="d-flex justify-content-between small text-muted mb-1">
                                    <span>প্রগ্রেস:</span>
                                    <span class="fw-bold text-primary">১৫%</span>
                                </div>
                                <div class="progress" style="height: 8px;">
                                    <div class="progress-bar bg-primary rounded-pill" style="width: 15%;"></div>
                                </div>
                            </div>

                            <div class="bg-light p-3 rounded-3 mb-3 small">
                                <div class="d-flex justify-content-between mb-1">
                                    <span class="text-muted">বাজেট:</span>
                                    <span class="fw-bold text-dark font-monospace">৳ ৮.৫০ কোটি</span>
                                </div>
                                <div class="d-flex justify-content-between">
                                    <span class="text-muted">টার্গেট কালেকশন:</span>
                                    <span class="fw-bold text-success font-monospace">৳ ১২.০০ কোটি</span>
                                </div>
                            </div>
                            <button class="btn btn-outline-primary btn-sm w-100">মাইলস্টোন ও অডিট ভিউ</button>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
</div>

<!-- ========================================================
    ADD PROJECT MODAL (SRS Section 6)
======================================================== -->
<div class="modal fade" id="modalAddProject" tabindex="-1" aria-labelledby="modalAddProjectLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg rounded-4">
            <div class="modal-header bg-primary text-white py-3 px-4">
                <h5 class="modal-title fw-bold mb-0" id="modalAddProjectLabel">
                    <i class="fa fa-plus-circle me-1"></i> নতুন প্রকল্প এন্ট্রি ফরম (Create New Project)
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form onsubmit="event.preventDefault(); alert('প্রকল্প সফলভাবে তৈরি করা হয়েছে!'); bootstrap.Modal.getInstance(document.getElementById('modalAddProject')).hide(); location.reload();">
                <div class="modal-body p-4">
                    <div class="row g-3">
                        <div class="col-md-8">
                            <label class="form-label small fw-bold">প্রকল্পের নাম (Project Name) <span className="text-danger">*</span></label>
                            <input type="text" required class="form-control" placeholder="যেমন: পূর্বাচল গ্রিন সিটি আবাসন প্রকল্প">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label small fw-bold">প্রকল্পের ধরন <span className="text-danger">*</span></label>
                            <select class="form-select" required>
                                <option value="Residential Building">আবাসিক ভবন (Residential)</option>
                                <option value="Commercial Building">বাণিজ্যিক কমপ্লেক্স (Commercial)</option>
                                <option value="Land Development">জমি উন্নয়ন ও প্লট (Land Development)</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-bold">লোকেশন / সাইটের ঠিকানা <span className="text-danger">*</span></label>
                            <input type="text" required class="form-control" placeholder="যেমন: পূর্বাচল সেক্টর ২১, রূপগঞ্জ">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-bold">মোট প্রকল্প বাজেট (৳) <span className="text-danger">*</span></label>
                            <input type="number" required class="form-control font-monospace" placeholder="যেমন: 150000000">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-bold">টার্গেট ইনভেস্টমেন্ট / বিক্রি (৳) <span className="text-danger">*</span></label>
                            <input type="number" required class="form-control font-monospace text-success fw-bold" placeholder="যেমন: 195000000">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label small fw-bold">শুরুর তারিখ <span className="text-danger">*</span></label>
                            <input type="date" required class="form-control">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label small fw-bold">সমাপ্তির তারিখ <span className="text-danger">*</span></label>
                            <input type="date" required class="form-control">
                        </div>
                        <div class="col-12">
                            <label class="form-label small fw-bold">প্রকল্পের বিবরণ ও সুবিধা</label>
                            <textarea rows="3" class="form-control" placeholder="প্রকল্পের সুযোগ-সুবিধা ও বিনিয়োগের সারসংক্ষেপ..."></textarea>
                        </div>
                    </div>
                </div>
                <div class="modal-footer bg-light py-3 px-4">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">বাতিল</button>
                    <button type="submit" class="btn btn-primary px-4 fw-bold">
                        <i class="fa fa-save me-1"></i> প্রকল্প সংরক্ষণ করুন (Save Project)
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
