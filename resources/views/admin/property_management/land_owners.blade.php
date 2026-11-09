@extends('layouts.backend.app')

@section('content')
<div class="content-wrapper">
    <div class="container-full">
        <div class="content-header d-flex align-items-center justify-content-between mb-3">
            <div>
                <h3 class="page-title text-dark fw-bold mb-1">
                    <i class="fa fa-user-circle text-success me-2"></i> জমির মালিক ও বিক্রেতা ব্যবস্থাপনা (Land Owners & Sellers)
                </h3>
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('admin.real_estate.hub') }}">LandShare ERP</a></li>
                    <li class="breadcrumb-item active">জমির মালিক তালিকা</li>
                </ol>
            </div>
            <div>
                <button class="btn btn-success fw-bold" data-bs-toggle="modal" data-bs-target="#modalAddLandOwner">
                    <i class="fa fa-plus-circle me-1"></i> + নতুন জমির মালিক/বিক্রেতা নিবন্ধন
                </button>
            </div>
        </div>

        <section class="content">
            <div class="row g-3 mb-4">
                <div class="col-md-3">
                    <div class="box bg-white border-0 shadow-sm rounded-4 p-3 mb-0">
                        <span class="text-muted small">মোট নিবন্ধিত মালিক</span>
                        <h4 class="fw-bold mb-0 text-dark">১৪ জন</h4>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="box bg-white border-0 shadow-sm rounded-4 p-3 mb-0">
                        <span class="text-muted small">যৌথ উদ্যোগ (Joint Venture)</span>
                        <h4 class="fw-bold mb-0 text-primary">৬ টি প্রজেক্ট</h4>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="box bg-white border-0 shadow-sm rounded-4 p-3 mb-0">
                        <span class="text-muted small">সরাসরি ক্রয়কৃত জমি</span>
                        <h4 class="fw-bold mb-0 text-success">৮ টি সাইট</h4>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="box bg-white border-0 shadow-sm rounded-4 p-3 mb-0">
                        <span class="text-muted small">পাওয়ার অব অ্যাটর্নি সম্পন্ন</span>
                        <h4 class="fw-bold mb-0 text-info">১২ টি দলিল</h4>
                    </div>
                </div>
            </div>

            <!-- Land Owners Table -->
            <div class="box bg-white border-0 shadow-sm rounded-4 overflow-hidden">
                <div class="box-header with-border px-4 py-3 bg-light d-flex justify-content-between align-items-center">
                    <h5 class="box-title fw-bold mb-0">জমির মালিক ও জয়েন্ট ভেঞ্চার পার্টনার তালিকা</h5>
                </div>
                <div class="box-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>মালিক কোড</th>
                                    <th>মালিকের নাম ও পরিচয়</th>
                                    <th>জমি ও মৌজা</th>
                                    <th>চুক্তির ধরণ</th>
                                    <th>শেয়ারিং অনুপাত / মূল্য</th>
                                    <th>আইনি অবস্থা</th>
                                    <th class="text-end">অ্যাকশন</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td><strong class="font-monospace text-primary">LNO-1001</strong></td>
                                    <td>
                                        <div class="fw-bold">হাজী মোহাম্মদ সামসুল হক</div>
                                        <small class="text-muted">০১৭২২-৩৩৪৪৫৫ • NID: 1982749102</small>
                                    </td>
                                    <td>
                                        <div>পূর্বাচল সেক্টর ২১</div>
                                        <small class="text-muted">মৌজা: কালিয়াকৈর • দাগ: ৫৪২১</small>
                                    </td>
                                    <td><span class="badge bg-primary bg-opacity-10 text-primary">Joint Venture (JV)</span></td>
                                    <td class="fw-bold font-monospace">৫০% : ৫০% রেশিও</td>
                                    <td><span class="badge bg-success bg-opacity-25 text-success">পাওয়ার অব অ্যাটর্নি সম্পন্ন</span></td>
                                    <td class="text-end">
                                        <button class="btn btn-sm btn-outline-primary me-1"><i class="fa fa-folder-open"></i> দলিলপত্র</button>
                                        <button class="btn btn-sm btn-outline-success"><i class="fa fa-money"></i> লেজার</button>
                                    </td>
                                </tr>
                                <tr>
                                    <td><strong class="font-monospace text-primary">LNO-1002</strong></td>
                                    <td>
                                        <div class="fw-bold">বেগম নুরুন্নাহার</div>
                                        <small class="text-muted">০১৮১৯-১১২২৩৩ • NID: 2849102941</small>
                                    </td>
                                    <td>
                                        <div>মিরপুর ডিওএইচএস সংলগ্ন প্লট</div>
                                        <small class="text-muted">মৌজা: পাইকপাড়া • দাগ: ১২০৪</small>
                                    </td>
                                    <td><span class="badge bg-success bg-opacity-10 text-success">সরাসরি জমি ক্রয় (Outright)</span></td>
                                    <td class="fw-bold font-monospace text-success">৳ ১,৮৫,০০,০০০</td>
                                    <td><span class="badge bg-success bg-opacity-25 text-success">রেজিস্ট্রি সাফ-কবলা সম্পন্ন</span></td>
                                    <td class="text-end">
                                        <button class="btn btn-sm btn-outline-primary me-1"><i class="fa fa-folder-open"></i> দলিলপত্র</button>
                                        <button class="btn btn-sm btn-outline-success"><i class="fa fa-money"></i> লেজার</button>
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

<!-- Modal: Add Land Owner -->
<div class="modal fade" id="modalAddLandOwner" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content rounded-4 border-0 shadow">
            <div class="modal-header bg-success bg-opacity-25 border-0">
                <h5 class="modal-title fw-bold text-dark"><i class="fa fa-user-circle me-2"></i> নতুন জমির মালিক বা বিক্রেতা নিবন্ধন</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form onsubmit="event.preventDefault(); alert('জমির মালিক সফলভাবে নিবন্ধিত হয়েছে!'); location.reload();">
                <div class="modal-body p-4">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label fw-bold">মালিকের পূর্ণ নাম</label>
                            <input type="text" class="form-control" required placeholder="দলিলে উল্লিখিত নাম">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold">মোবাইল নম্বর</label>
                            <input type="text" class="form-control" required placeholder="০১xxxxxxxxx">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold">জাতীয় পরিচয়পত্র (NID)</label>
                            <input type="text" class="form-control" required placeholder="জাতীয় পরিচয়পত্র নম্বর">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold">চুক্তির ধরণ</label>
                            <select class="form-select" required>
                                <option>Joint Venture (JV) শেয়ারিং</option>
                                <option>সরাসরি জমি ক্রয় (Outright Purchase)</option>
                                <option>পাওয়ার অব অ্যাটর্নি ডেভেলপমেন্ট</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold">মৌজা ও জেলা</label>
                            <input type="text" class="form-control" required placeholder="যেমন: বাড্ডা, ঢাকা">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold">জমির পরিমাণ (শতাংশ/কাঠা)</label>
                            <input type="text" class="form-control" required placeholder="যেমন: ২০ শতাংশ">
                        </div>
                        <div class="col-12">
                            <label class="form-label fw-bold">দলিল, পর্চা ও খারিজের কপি আপলোড</label>
                            <input type="file" class="form-control" multiple>
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-0 bg-light">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">বাতিল</button>
                    <button type="submit" class="btn btn-success fw-bold">নিবন্ধন সম্পন্ন করুন</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
