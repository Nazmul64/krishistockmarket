@extends('layouts.backend.app')

@section('content')
<div class="content-wrapper">
    <div class="container-full">
        <div class="content-header d-flex align-items-center justify-content-between mb-3">
            <div>
                <h3 class="page-title text-dark fw-bold mb-1">
                    <i class="fa fa-handshake-o text-info me-2"></i> ব্রোকার ও সেলস এজেন্ট নেটওয়ার্ক (Broker & Agent Management)
                </h3>
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('admin.landshare.hub') }}">LandShare ERP</a></li>
                    <li class="breadcrumb-item active">ব্রোকার ও এজেন্ট তালিকা</li>
                </ol>
            </div>
            <div>
                <button class="btn btn-info text-white fw-bold" data-bs-toggle="modal" data-bs-target="#modalAddBroker">
                    <i class="fa fa-plus-circle me-1"></i> + নতুন ব্রোকার / এজেন্ট যুক্ত করুন
                </button>
            </div>
        </div>

        <section class="content">
            <div class="row g-3 mb-4">
                <div class="col-md-3">
                    <div class="box bg-white border-0 shadow-sm rounded-4 p-3 mb-0">
                        <span class="text-muted small">সক্রিয় ব্রোকার / এজেন্ট</span>
                        <h4 class="fw-bold mb-0 text-dark">৪২ জন</h4>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="box bg-white border-0 shadow-sm rounded-4 p-3 mb-0">
                        <span class="text-muted small">মোট বিক্রয় রেফারেল ভলিউম</span>
                        <h4 class="fw-bold mb-0 text-success">৳ ১৮,৪০,০০,০০০</h4>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="box bg-white border-0 shadow-sm rounded-4 p-3 mb-0">
                        <span class="text-muted small">পরিশোধিত কমিশন</span>
                        <h4 class="fw-bold mb-0 text-primary">৳ ৩৭,২০,০০০</h4>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="box bg-white border-0 shadow-sm rounded-4 p-3 mb-0">
                        <span class="text-muted small">অপেক্ষমান কমিশন (Payable)</span>
                        <h4 class="fw-bold mb-0 text-warning">৳ ৮,৫০,০০০</h4>
                    </div>
                </div>
            </div>

            <!-- Brokers Table -->
            <div class="box bg-white border-0 shadow-sm rounded-4 overflow-hidden">
                <div class="box-header with-border px-4 py-3 bg-light d-flex justify-content-between align-items-center">
                    <h5 class="box-title fw-bold mb-0">ব্রোকার তালিকা ও পারফরম্যান্স মেট্রিক্স</h5>
                </div>
                <div class="box-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>এজেন্ট কোড</th>
                                    <th>নাম ও যোগাযোগ</th>
                                    <th>অ্যাসাইনড রিজিয়ন</th>
                                    <th>কমিশন রেট</th>
                                    <th>মোট বিক্রয় রেফারেল</th>
                                    <th>কমিশন প্রদেয়</th>
                                    <th>স্ট্যাটাস</th>
                                    <th class="text-end">অ্যাকশন</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td><strong class="font-monospace text-primary">AGT-401</strong></td>
                                    <td>
                                        <div class="fw-bold">কাজী শফিউল আলম</div>
                                        <small class="text-muted">০১৭৫০-৫৫৬৬৭৭ • shafiul@agent.com</small>
                                    </td>
                                    <td>উত্তরা ও মিরপুর জোন</td>
                                    <td><span class="badge bg-secondary">২.০%</span></td>
                                    <td class="font-monospace fw-bold text-dark">৳ ৪,২০,০০,০০০ (৫টি বুকিং)</td>
                                    <td class="font-monospace fw-bold text-success">৳ ৮,৪০,০০০</td>
                                    <td><span class="badge bg-success bg-opacity-25 text-success">সক্রিয় টপ পারফর্মার</span></td>
                                    <td class="text-end">
                                        <button class="btn btn-sm btn-outline-info me-1"><i class="fa fa-list"></i> লিড হিস্ট্রি</button>
                                        <button class="btn btn-sm btn-success"><i class="fa fa-check"></i> কমিশন পে</button>
                                    </td>
                                </tr>
                                <tr>
                                    <td><strong class="font-monospace text-primary">AGT-402</strong></td>
                                    <td>
                                        <div class="fw-bold">মেহরাব হোসেন রাসেল</div>
                                        <small class="text-muted">০১৮৩০-২২৩৩৪৪ • mehrab@broker.com</small>
                                    </td>
                                    <td>পূর্বাচল ও বসুন্ধরা জোন</td>
                                    <td><span class="badge bg-secondary">২.৫%</span></td>
                                    <td class="font-monospace fw-bold text-dark">৳ ৩,০০,০০,০০০ (৩টি বুকিং)</td>
                                    <td class="font-monospace fw-bold text-warning">৳ ৭,৫০,০০০</td>
                                    <td><span class="badge bg-success bg-opacity-25 text-success">সক্রিয়</span></td>
                                    <td class="text-end">
                                        <button class="btn btn-sm btn-outline-info me-1"><i class="fa fa-list"></i> লিড হিস্ট্রি</button>
                                        <button class="btn btn-sm btn-success"><i class="fa fa-check"></i> কমিশন পে</button>
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

<!-- Modal: Add Broker -->
<div class="modal fade" id="modalAddBroker" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content rounded-4 border-0 shadow">
            <div class="modal-header bg-info bg-opacity-25 border-0">
                <h5 class="modal-title fw-bold text-dark"><i class="fa fa-handshake-o me-2"></i> নতুন ব্রোকার / এজেন্ট রেজিস্ট্রেশন</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form onsubmit="event.preventDefault(); alert('ব্রোকার সফলভাবে নিবন্ধিত হয়েছে!'); location.reload();">
                <div class="modal-body p-4">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label fw-bold">এজেন্টের পূর্ণ নাম</label>
                            <input type="text" class="form-control" required placeholder="পূর্ণ নাম">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold">মোবাইল নম্বর</label>
                            <input type="text" class="form-control" required placeholder="০১xxxxxxxxx">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold">ইমেইল</label>
                            <input type="email" class="form-control" placeholder="agent@example.com">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold">অ্যাসাইনড সেলস রিজিয়ন / প্রজেক্ট</label>
                            <input type="text" class="form-control" required placeholder="যেমন: পূর্বাচল গ্রিন সিটি">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold">চুক্তিবদ্ধ কমিশন রেট (%)</label>
                            <input type="number" step="0.1" class="form-control" required placeholder="যেমন: ২.০">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold">ব্যাংক অ্যাকাউন্ট নম্বর ও ব্যাংক নাম</label>
                            <input type="text" class="form-control" placeholder="Dutch Bangla Bank • A/C: 1029...">
                        </div>
                        <div class="col-12">
                            <label class="form-label fw-bold">এজেন্ট চুক্তিপত্র ও NID স্ক্যান কপি</label>
                            <input type="file" class="form-control">
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-0 bg-light">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">বাতিল</button>
                    <button type="submit" class="btn btn-info text-white fw-bold">এজেন্ট সক্রিয় করুন</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

