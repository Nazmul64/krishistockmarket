@extends('layouts.backend.app')

@section('content')
<div class="content-wrapper">
    <div class="container-full">
        <!-- Content Header -->
        <div class="content-header">
            <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
                <div class="me-auto">
                    <h3 class="page-title text-dark fw-bold mb-1">
                        <i class="fa fa-map-marker text-success me-2"></i> জমি, প্লট ও প্রপার্টি ক্যাটালগ
                    </h3>
                    <div class="d-inline-block align-items-center">
                        <nav>
                            <ol class="breadcrumb mb-0 p-0 bg-transparent">
                                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">ড্যাশবোর্ড</a></li>
                                <li class="breadcrumb-item"><a href="{{ route('admin.landshare.hub') }}">রিয়েল এস্টেট</a></li>
                                <li class="breadcrumb-item active" aria-current="page">জমি ও প্রপার্টি</li>
                            </ol>
                        </nav>
                    </div>
                </div>

                <button type="button" class="btn btn-success btn-sm shadow-sm px-3 py-2 fw-bold" data-bs-toggle="modal" data-bs-target="#modalAddProperty">
                    <i class="fa fa-plus-circle me-1"></i> নতুন সম্পত্তি যোগ করুন (Add Property)
                </button>
            </div>
        </div>

        <!-- Main Content -->
        <section class="content">
            <div class="box bg-white border-0 shadow-sm rounded-4 overflow-hidden">
                <div class="box-header with-border py-3 px-4 d-flex align-items-center justify-content-between flex-wrap gap-2">
                    <h5 class="box-title fw-bold text-dark mb-0">নিবন্ধিত জমি, প্লট ও ভবনের তালিকা</h5>
                    <div class="d-flex gap-2">
                        <input type="text" class="form-control form-control-sm" placeholder="নাম, দাগ বা খতিয়ান দিয়ে খুঁজুন..." style="width: 250px;">
                        <button class="btn btn-sm btn-outline-secondary"><i class="fa fa-search"></i></button>
                    </div>
                </div>

                <div class="box-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>কোড ও প্রপার্টির নাম</th>
                                    <th>ধরন (Type)</th>
                                    <th>অবস্থান (Location)</th>
                                    <th>মোট আয়তন</th>
                                    <th>ক্রয় মূল্য</th>
                                    <th>বিক্রয় মূল্য</th>
                                    <th>স্ট্যাটাস</th>
                                    <th class="text-end">অ্যাকশন</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>
                                        <span class="badge bg-dark font-monospace">PRP-PUR-1024</span>
                                        <div class="fw-bold text-dark mt-1">পূর্বাচল সেক্টর ২১ বাণিজ্যিক প্লট</div>
                                        <small class="text-muted">দাগ নং: ১৮৪, খতিয়ান নং: ৯২০</small>
                                    </td>
                                    <td><span class="badge bg-light text-dark border">বাণিজ্যিক প্লট (Plot)</span></td>
                                    <td><i class="fa fa-map-marker text-danger me-1"></i> রূপগঞ্জ, পূর্বাচল, ঢাকা</td>
                                    <td><strong>১০ কাঠা</strong></td>
                                    <td class="font-monospace">৳ ২,৫০,০০,০০০</td>
                                    <td class="font-monospace fw-bold text-success">৳ ৩,৫০,০০,০০০</td>
                                    <td><span class="badge bg-success bg-opacity-25 text-success">অনুমোদিত (Approved)</span></td>
                                    <td class="text-end">
                                        <button class="btn btn-sm btn-outline-secondary me-1">দলিল</button>
                                        <button class="btn btn-sm btn-outline-success">ভিউ</button>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <span class="badge bg-dark font-monospace">PRP-UTT-2048</span>
                                        <div class="fw-bold text-dark mt-1">উত্তরা সেক্টর ১৮ রয়েল হাইটস</div>
                                        <small class="text-muted">দাগ নং: ৫১২, খতিয়ান নং: ৩৪০</small>
                                    </td>
                                    <td><span class="badge bg-light text-dark border">আবাসিক ভবন (Building)</span></td>
                                    <td><i class="fa fa-map-marker text-danger me-1"></i> উত্তরা, ঢাকা</td>
                                    <td><strong>২৪,০০০ বর্গফুট</strong> (১৪ তলা)</td>
                                    <td class="font-monospace">৳ ১৩,০০,০০,০০০</td>
                                    <td class="font-monospace fw-bold text-success">৳ ১৮,০০,০০,০০০</td>
                                    <td><span class="badge bg-success bg-opacity-25 text-success">অনুমোদিত (Approved)</span></td>
                                    <td class="text-end">
                                        <button class="btn btn-sm btn-outline-secondary me-1">দলিল</button>
                                        <button class="btn btn-sm btn-outline-success">ভিউ</button>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <span class="badge bg-dark font-monospace">PRP-BAR-3012</span>
                                        <div class="fw-bold text-dark mt-1">বারিধারা লেকভিউ বাণিজ্যিক টাওয়ার</div>
                                        <small class="text-muted">দাগ নং: ৭২৮, খতিয়ান নং: ১২১</small>
                                    </td>
                                    <td><span class="badge bg-light text-dark border">বাণিজ্যিক ভবন (Commercial)</span></td>
                                    <td><i class="fa fa-map-marker text-danger me-1"></i> বারিধারা, ঢাকা</td>
                                    <td><strong>৩৫,০০০ বর্গফুট</strong></td>
                                    <td class="font-monospace">৳ ২২,০০,০০,০০০</td>
                                    <td class="font-monospace fw-bold text-success">৳ ৩১,০০,০০,০০০</td>
                                    <td><span class="badge bg-warning bg-opacity-25 text-warning">চলমান (Ongoing)</span></td>
                                    <td class="text-end">
                                        <button class="btn btn-sm btn-outline-secondary me-1">দলিল</button>
                                        <button class="btn btn-sm btn-outline-success">ভিউ</button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </section>
    </div>
</div>

<!-- ========================================================
    ADD PROPERTY MODAL (SRS Section 5.2)
======================================================== -->
<div class="modal fade" id="modalAddProperty" tabindex="-1" aria-labelledby="modalAddPropertyLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg rounded-4">
            <div class="modal-header bg-success text-white py-3 px-4">
                <h5 class="modal-title fw-bold mb-0" id="modalAddPropertyLabel">
                    <i class="fa fa-plus-circle me-1"></i> নতুন সম্পত্তি এন্ট্রি ফরম (Add Property)
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form onsubmit="event.preventDefault(); alert('সম্পত্তি সফলভাবে অ্যাডমিন প্যানেলে সংরক্ষিত হয়েছে!'); bootstrap.Modal.getInstance(document.getElementById('modalAddProperty')).hide(); location.reload();">
                <div class="modal-body p-4">
                    <div class="row g-3">
                        <div class="col-md-8">
                            <label class="form-label small fw-bold">সম্পত্তির নাম (Property Title) <span class="text-danger">*</span></label>
                            <input type="text" required class="form-control" placeholder="যেমন: পূর্বাচল সেক্টর ২১ বাণিজ্যিক প্লট">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label small fw-bold">সম্পত্তির ধরন <span class="text-danger">*</span></label>
                            <select class="form-select" required>
                                <option value="land">জমি (Land / Agricultural)</option>
                                <option value="plot">প্লট (Residential / Commercial Plot)</option>
                                <option value="residential_building">আবাসিক ভবন (Residential Building)</option>
                                <option value="commercial_building">বাণিজ্যিক ভবন (Commercial Building)</option>
                                <option value="apartment">ফ্ল্যাট / অ্যাপার্টমেন্ট (Apartment / Flat)</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label small fw-bold">বিভাগ <span class="text-danger">*</span></label>
                            <input type="text" required class="form-control" value="ঢাকা">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label small fw-bold">জেলা <span class="text-danger">*</span></label>
                            <input type="text" required class="form-control" value="ঢাকা">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label small fw-bold">উপজেলা / থানা <span class="text-danger">*</span></label>
                            <input type="text" required class="form-control" value="উত্তরা">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label small fw-bold">মৌজা ও জেএল নম্বর</label>
                            <input type="text" class="form-control" placeholder="মৌজা ও JL No">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label small fw-bold">দাগ নম্বর (Dag Number)</label>
                            <input type="text" class="form-control" placeholder="সিএস / আরএস / বিএস দাগ">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label small fw-bold">খতিয়ান নম্বর (Khatian Number)</label>
                            <input type="text" class="form-control" placeholder="নামজারি খতিয়ান নম্বর">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label small fw-bold">মোট আয়তন <span class="text-danger">*</span></label>
                            <input type="number" step="0.01" required class="form-control" placeholder="যেমন: 10">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label small fw-bold">পরিমাপের একক <span class="text-danger">*</span></label>
                            <select class="form-select" required>
                                <option value="Decimal">শতাংশ / ডেসিমাল (Decimal)</option>
                                <option value="Katha" selected>কাঠা (Katha)</option>
                                <option value="Bigha">বিঘা (Bigha)</option>
                                <option value="Sqft">বর্গফুট (Sqft)</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label small fw-bold">ক্রয় মূল্য (৳) <span class="text-danger">*</span></label>
                            <input type="number" required class="form-control font-monospace" placeholder="কোম্পানির ক্রয় মূল্য">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-bold">বিক্রয় মূল্য (৳) <span class="text-danger">*</span></label>
                            <input type="number" required class="form-control font-monospace text-success fw-bold" placeholder="নির্ধারিত বিক্রয় মূল্য">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-bold">বর্তমান আনুমানিক বাজারমূল্য (৳)</label>
                            <input type="number" class="form-control font-monospace" placeholder="মার্কেট ভ্যালুয়েশন">
                        </div>
                        <div class="col-12">
                            <label class="form-label small fw-bold">সম্পত্তির বিবরণ ও সুযোগ-সুবিধা</label>
                            <textarea rows="3" class="form-control" placeholder="রোড কানেক্টিভিটি, ইউটিলিটি ও দলিল সংক্রান্ত বিবরণ লিখুন..."></textarea>
                        </div>
                    </div>
                </div>
                <div class="modal-footer bg-light py-3 px-4">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">বাতিল</button>
                    <button type="submit" class="btn btn-success px-4 fw-bold">
                        <i class="fa fa-save me-1"></i> সম্পত্তি সংরক্ষণ করুন (Save Property)
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
