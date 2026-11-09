@extends('layouts.backend.app')

@section('content')
<div class="content-wrapper">
    <div class="container-full">
        <div class="content-header">
            <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
                <div class="me-auto">
                    <h3 class="page-title text-dark fw-bold mb-1">
                        <i class="fa fa-shopping-cart text-danger me-2"></i> বিক্রি, বুকিং ও কিস্তি ব্যবস্থাপনা (Sales & Installments)
                    </h3>
                    <div class="d-inline-block align-items-center">
                        <nav>
                            <ol class="breadcrumb mb-0 p-0 bg-transparent">
                                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">ড্যাশবোর্ড</a></li>
                                <li class="breadcrumb-item"><a href="{{ route('admin.landshare.hub') }}">রিয়েল এস্টেট</a></li>
                                <li class="breadcrumb-item active" aria-current="page">বিক্রি ও কিস্তি</li>
                            </ol>
                        </nav>
                    </div>
                </div>

                <button type="button" class="btn btn-danger btn-sm shadow-sm px-3 py-2 fw-bold text-white" data-bs-toggle="modal" data-bs-target="#modalAddBooking">
                    <i class="fa fa-plus-circle me-1"></i> নতুন বিক্রয় বুকিং এন্ট্রি (Add Booking)
                </button>
            </div>
        </div>

        <section class="content">
            <div class="box bg-white border-0 shadow-sm rounded-4 overflow-hidden">
                <div class="box-header with-border py-3 px-4">
                    <h5 class="box-title fw-bold text-dark mb-0">গ্রাহক বুকিং ও কিস্তি আদায় ট্র্যাকিং</h5>
                </div>
                <div class="box-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>বুকিং নম্বর</th>
                                    <th>ক্রেতার নাম</th>
                                    <th>ফ্ল্যাট / প্লট</th>
                                    <th>মোট মূল্য</th>
                                    <th>পরিশোধ ও বকেয়া</th>
                                    <th>পরবর্তী কিস্তি</th>
                                    <th>স্ট্যাটাস</th>
                                    <th class="text-end">অ্যাকশন</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td><span class="badge bg-dark font-monospace">BKG-2026-0089</span></td>
                                    <td><strong class="text-dark">মো. রোকনুজ্জামান</strong></td>
                                    <td>উত্তরা রয়েল হাইটস - ফ্ল্যাট ৪বি</td>
                                    <td class="font-monospace fw-bold">৳ ১,৯২,০০,০০০</td>
                                    <td>
                                        <div class="small font-monospace">
                                            <span class="text-success d-block">পরিশোধ: ৳ ৭৬,৮০,০০০</span>
                                            <span class="text-danger">বকেয়া: ৳ ১,১৫,২০,০০০</span>
                                        </div>
                                    </td>
                                    <td>১০ এপ্রিল, ২০২৬</td>
                                    <td><span class="badge bg-warning bg-opacity-25 text-warning">চলমান কিস্তি</span></td>
                                    <td class="text-end"><button class="btn btn-sm btn-outline-secondary">কিস্তি সিডিউল</button></td>
                                </tr>
                                <tr>
                                    <td><span class="badge bg-dark font-monospace">BKG-2026-0045</span></td>
                                    <td><strong class="text-dark">তানজিমুল কবির</strong></td>
                                    <td>উত্তরা রয়েল হাইটস - ফ্ল্যাট ৪এ</td>
                                    <td class="font-monospace fw-bold">৳ ১,৮৫,০০,০০০</td>
                                    <td>
                                        <div class="small font-monospace">
                                            <span class="text-success d-block">পরিশোধ: ৳ ১,৮৫,০০,০০০</span>
                                            <span class="text-muted">বকেয়া: ৳ ০</span>
                                        </div>
                                    </td>
                                    <td>-</td>
                                    <td><span class="badge bg-success bg-opacity-25 text-success">সম্পূর্ণ পরিশোধিত</span></td>
                                    <td class="text-end"><button class="btn btn-sm btn-success">চাবি হস্তান্তর</button></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </section>
    </div>
</div>

<div class="modal fade" id="modalAddBooking" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg rounded-4">
            <div class="modal-header bg-danger text-white py-3 px-4">
                <h5 class="modal-title fw-bold mb-0">নতুন বিক্রয় ও বুকিং এন্ট্রি</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form onsubmit="event.preventDefault(); alert('বুকিং সফলভাবে সংরক্ষিত হয়েছে!'); bootstrap.Modal.getInstance(document.getElementById('modalAddBooking')).hide(); location.reload();">
                <div class="modal-body p-4">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label small fw-bold">ক্রেতার নাম</label>
                            <input type="text" required class="form-control" placeholder="যেমন: মো. রফিকুল ইসলাম">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-bold">মোবাইল ফোন নম্বর</label>
                            <input type="text" required class="form-control" placeholder="+88017XXXXXXXX">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-bold">ফ্ল্যাট / প্লট ইউনিট</label>
                            <select class="form-select" required>
                                <option value="1">উত্তরা রয়েল হাইটস - ফ্ল্যাট ৪বি</option>
                                <option value="2">পূর্বাচল গ্রিন সিটি - প্লট ১৫</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-bold">মোট বিক্রয় মূল্য (৳)</label>
                            <input type="number" required class="form-control font-monospace text-success fw-bold" placeholder="19200000">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-bold">ডাউন পেমেন্ট (৳)</label>
                            <input type="number" required class="form-control font-monospace" placeholder="3840000">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-bold">মোট কিস্তি সংখ্যা (মাস)</label>
                            <input type="number" required class="form-control" value="36">
                        </div>
                    </div>
                </div>
                <div class="modal-footer bg-light py-3 px-4">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">বাতিল</button>
                    <button type="submit" class="btn btn-danger px-4 fw-bold">বুকিং সংরক্ষণ করুন</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
