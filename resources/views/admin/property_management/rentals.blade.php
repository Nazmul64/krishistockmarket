@extends('layouts.backend.app')

@section('content')
<div class="content-wrapper">
    <div class="container-full">
        <div class="content-header d-flex align-items-center justify-content-between mb-3">
            <div>
                <h3 class="page-title text-dark fw-bold mb-1">
                    <i class="fa fa-key text-warning me-2"></i> রেন্টাল ও ভাড়াটিয়া ব্যবস্থাপনা (Rental & Tenant Management)
                </h3>
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('admin.landshare.hub') }}">LandShare ERP</a></li>
                    <li class="breadcrumb-item active">রেন্টাল ও লিজ চুক্তি</li>
                </ol>
            </div>
            <div>
                <button class="btn btn-warning text-dark fw-bold" data-bs-toggle="modal" data-bs-target="#modalAddRental">
                    <i class="fa fa-plus-circle me-1"></i> + নতুন ভাড়াটিয়া ও লিজ চুক্তি
                </button>
            </div>
        </div>

        <section class="content">
            <!-- Summary Stats -->
            <div class="row g-3 mb-4">
                <div class="col-md-3">
                    <div class="box bg-white border-0 shadow-sm rounded-4 p-3 mb-0">
                        <span class="text-muted small">মোট সক্রিয় ভাড়াটিয়া</span>
                        <h4 class="fw-bold mb-0 text-dark">২৮ জন</h4>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="box bg-white border-0 shadow-sm rounded-4 p-3 mb-0">
                        <span class="text-muted small">চলতি মাসের ভাড়া আদায়</span>
                        <h4 class="fw-bold mb-0 text-success">৳ ৯,৮০,০০০</h4>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="box bg-white border-0 shadow-sm rounded-4 p-3 mb-0">
                        <span class="text-muted small">বকেয়া ভাড়া (Overdue)</span>
                        <h4 class="fw-bold mb-0 text-danger">৳ ১,২০,০০০</h4>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="box bg-white border-0 shadow-sm rounded-4 p-3 mb-0">
                        <span class="text-muted small">সিকিউরিটি ডিপোজিট ফান্ড</span>
                        <h4 class="fw-bold mb-0 text-primary">৳ ২৫,৫০,০০০</h4>
                    </div>
                </div>
            </div>

            <!-- Rental Agreements Table -->
            <div class="box bg-white border-0 shadow-sm rounded-4 overflow-hidden">
                <div class="box-header with-border px-4 py-3 bg-light d-flex justify-content-between align-items-center">
                    <h5 class="box-title fw-bold mb-0">সক্রিয় লিজ ও ভাড়া চুক্তি তালিকা</h5>
                </div>
                <div class="box-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>চুক্তি কোড</th>
                                    <th>ভাড়াটিয়া (Tenant)</th>
                                    <th>প্রপার্টি ও ফ্ল্যাট</th>
                                    <th>মাসিক ভাড়া</th>
                                    <th>জামানত (Deposit)</th>
                                    <th>চুক্তির মেয়াদ</th>
                                    <th>স্ট্যাটাস</th>
                                    <th class="text-end">অ্যাকশন</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td><strong class="font-monospace text-primary">LSE-2026-019</strong></td>
                                    <td>
                                        <div class="fw-bold">ইমরান হোসাইন</div>
                                        <small class="text-muted">০১৭৭৮-৯৯১০১১</small>
                                    </td>
                                    <td>
                                        <div>ব্লুমিং লোটাস টাওয়ার</div>
                                        <small class="text-muted">ফ্ল্যাট ৪বি (১৩৫০ বর্গফুট)</small>
                                    </td>
                                    <td class="fw-bold font-monospace text-success">৳ ৩৫,০০০</td>
                                    <td class="font-monospace text-muted">৳ ১,০৫,০০০</td>
                                    <td>০১ জানু ২০২৬ - ৩১ ডিসে ২০২৬</td>
                                    <td><span class="badge bg-success bg-opacity-25 text-success">সক্রিয় লিজ</span></td>
                                    <td class="text-end">
                                        <button class="btn btn-sm btn-outline-primary me-1"><i class="fa fa-file-text-o"></i> ইনভয়েস</button>
                                        <button class="btn btn-sm btn-outline-success"><i class="fa fa-money"></i> ভাড়া সংগ্রহ</button>
                                    </td>
                                </tr>
                                <tr>
                                    <td><strong class="font-monospace text-primary">LSE-2026-020</strong></td>
                                    <td>
                                        <div class="fw-bold">ফারহানা ইয়াসমিন</div>
                                        <small class="text-muted">০১৮১২-৩৪৫৬৭৮</small>
                                    </td>
                                    <td>
                                        <div>গ্রিন ভ্যালি হাইটস</div>
                                        <small class="text-muted">কমার্শিয়াল স্পেস ২এ (১৮৫০ বর্গফুট)</small>
                                    </td>
                                    <td class="fw-bold font-monospace text-success">৳ ৬৫,০০০</td>
                                    <td class="font-monospace text-muted">৳ ২,০০,০০০</td>
                                    <td>১৫ ফেব্রু ২০২৬ - ১৪ ফেব্রু ২০২৭</td>
                                    <td><span class="badge bg-success bg-opacity-25 text-success">সক্রিয় লিজ</span></td>
                                    <td class="text-end">
                                        <button class="btn btn-sm btn-outline-primary me-1"><i class="fa fa-file-text-o"></i> ইনভয়েস</button>
                                        <button class="btn btn-sm btn-outline-success"><i class="fa fa-money"></i> ভাড়া সংগ্রহ</button>
                                    </td>
                                </tr>
                                <tr>
                                    <td><strong class="font-monospace text-primary">LSE-2026-021</strong></td>
                                    <td>
                                        <div class="fw-bold">আরিফুল ইসলাম</div>
                                        <small class="text-muted">০১৯১১-২২৩৩৪৪</small>
                                    </td>
                                    <td>
                                        <div>সানরাইজ রেসিডেন্স</div>
                                        <small class="text-muted">ফ্ল্যাট ৫সি (১২০০ বর্গফুট)</small>
                                    </td>
                                    <td class="fw-bold font-monospace text-danger">৳ ২৮,০০০</td>
                                    <td class="font-monospace text-muted">৳ ৮৪,০০০</td>
                                    <td>০১ মার্চ ২০২৬ - ২৮ ফেব্রু ২০২৭</td>
                                    <td><span class="badge bg-danger bg-opacity-25 text-danger">বকেয়া (১০ দিন)</span></td>
                                    <td class="text-end">
                                        <button class="btn btn-sm btn-outline-danger me-1"><i class="fa fa-bell"></i> নোটিশ পাঠান</button>
                                        <button class="btn btn-sm btn-success"><i class="fa fa-money"></i> আদায় এন্ট্রি</button>
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

<!-- Modal: Add Rental Agreement -->
<div class="modal fade" id="modalAddRental" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content rounded-4 border-0 shadow">
            <div class="modal-header bg-warning bg-opacity-25 border-0">
                <h5 class="modal-title fw-bold text-dark"><i class="fa fa-key me-2"></i> নতুন ভাড়াটিয়া ও লিজ চুক্তি এন্ট্রি</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form onsubmit="event.preventDefault(); alert('ভাড়াটিয়া এবং লিজ চুক্তি সফলভাবে নিবন্ধিত হয়েছে!'); location.reload();">
                <div class="modal-body p-4">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label fw-bold">ভাড়াটিয়ার নাম</label>
                            <input type="text" class="form-control" required placeholder="ভাড়াটিয়ার পূর্ণ নাম">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold">মোবাইল নম্বর</label>
                            <input type="text" class="form-control" required placeholder="০১xxxxxxxxx">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold">জাতীয় পরিচয়পত্র (NID)</label>
                            <input type="text" class="form-control" required placeholder="১০ বা ১৭ ডিজিটের NID">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold">ইমেইল (ঐচ্ছিক)</label>
                            <input type="email" class="form-control" placeholder="tenant@example.com">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold">প্রপার্টি ও ফ্ল্যাট নির্বাচন</label>
                            <select class="form-select" required>
                                <option value="">নির্বাচন করুন</option>
                                <option>ব্লুমিং লোটাস টাওয়ার - ফ্ল্যাট ৪বি</option>
                                <option>গ্রিন ভ্যালি হাইটস - কমার্শিয়াল ২এ</option>
                                <option>সানরাইজ রেসিডেন্স - ফ্ল্যাট ৫সি</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold">মাসিক ভাড়া (BDT)</label>
                            <input type="number" class="form-control" required placeholder="৳ ৩৫,০০০">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold">সিকিউরিটি জামানত (Deposit)</label>
                            <input type="number" class="form-control" required placeholder="৳ ১,০৫,০০০">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label fw-bold">চুক্তি শুরুর তারিখ</label>
                            <input type="date" class="form-control" required>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label fw-bold">মেয়াদ সমাপ্তির তারিখ</label>
                            <input type="date" class="form-control" required>
                        </div>
                        <div class="col-12">
                            <label class="form-label fw-bold">চুক্তিনামা কপি ও NID স্ক্যান আপলোড</label>
                            <input type="file" class="form-control" multiple>
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-0 bg-light">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">বাতিল</button>
                    <button type="submit" class="btn btn-warning text-dark fw-bold">সংরক্ষণ ও লিজ অনুমোদন</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

