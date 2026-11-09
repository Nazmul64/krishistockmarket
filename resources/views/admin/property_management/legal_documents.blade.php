@extends('layouts.backend.app')

@section('content')
<div class="content-wrapper">
    <div class="container-full">
        <div class="content-header d-flex align-items-center justify-content-between mb-3">
            <div>
                <h3 class="page-title text-dark fw-bold mb-1">
                    <i class="fa fa-file-pdf-o text-danger me-2"></i> লিগ্যাল ও ডকুমেন্ট ভল্ট (Legal & Documents Vault)
                </h3>
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('admin.landshare.hub') }}">LandShare ERP</a></li>
                    <li class="breadcrumb-item active">দলিল ও আইনি রেকর্ড ভল্ট</li>
                </ol>
            </div>
            <div>
                <button class="btn btn-danger fw-bold" data-bs-toggle="modal" data-bs-target="#modalAddDocument">
                    <i class="fa fa-upload me-1"></i> + নতুন আইনি দলিল আপলোড
                </button>
            </div>
        </div>

        <section class="content">
            <div class="row g-3 mb-4">
                <div class="col-md-3">
                    <div class="box bg-white border-0 shadow-sm rounded-4 p-3 mb-0">
                        <span class="text-muted small">মোট সংরক্ষিত দলিল</span>
                        <h4 class="fw-bold mb-0 text-dark">১৮৪ টি</h4>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="box bg-white border-0 shadow-sm rounded-4 p-3 mb-0">
                        <span class="text-muted small">আইনজীবী কর্তৃক যাচাইকৃত (Vetted)</span>
                        <h4 class="fw-bold mb-0 text-success">১৬২ টি</h4>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="box bg-white border-0 shadow-sm rounded-4 p-3 mb-0">
                        <span class="text-muted small">আইনি পর্যালোচনায় আছে</span>
                        <h4 class="fw-bold mb-0 text-warning">১৮ টি</h4>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="box bg-white border-0 shadow-sm rounded-4 p-3 mb-0">
                        <span class="text-muted small">বিতর্কিত / লকড (Disputed)</span>
                        <h4 class="fw-bold mb-0 text-danger">৪ টি (বিক্রয় স্থগিত)</h4>
                    </div>
                </div>
            </div>

            <!-- Legal Documents Table -->
            <div class="box bg-white border-0 shadow-sm rounded-4 overflow-hidden">
                <div class="box-header with-border px-4 py-3 bg-light d-flex justify-content-between align-items-center">
                    <h5 class="box-title fw-bold mb-0">মূল আইনি দলিল, খতিয়ান ও খারিজ পরচা ভল্ট</h5>
                </div>
                <div class="box-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>ডকুমেন্ট আইডি</th>
                                    <th>দলিলের ধরণ</th>
                                    <th>প্রপার্টি / প্রজেক্ট রেফারেন্স</th>
                                    <th>দলিল / খতিয়ান নম্বর</th>
                                    <th>আপলোড ও মেয়াদ</th>
                                    <th>আইনি ভেটিং স্ট্যাটাস</th>
                                    <th class="text-end">অ্যাকশন</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td><strong class="font-monospace text-primary">DOC-2026-081</strong></td>
                                    <td><span class="badge bg-danger bg-opacity-10 text-danger">মূল সাফ-কবলা দলিল</span></td>
                                    <td>
                                        <div class="fw-bold">পূর্বাচল রিভারভিউ গ্রিন ফেজ-১</div>
                                        <small class="text-muted">দাগ নং: ৫১২, খতিয়ান: ১২০</small>
                                    </td>
                                    <td class="font-monospace fw-bold">DEED-774921/2025</td>
                                    <td>
                                        <div>১২ অক্টো ২০২৫</div>
                                        <small class="text-muted">মেয়াদ: চিরস্থায়ী</small>
                                    </td>
                                    <td><span class="badge bg-success bg-opacity-25 text-success">আইনজীবী কর্তৃক প্রত্যায়িত</span></td>
                                    <td class="text-end">
                                        <button class="btn btn-sm btn-outline-danger me-1"><i class="fa fa-download"></i> PDF</button>
                                        <button class="btn btn-sm btn-outline-secondary"><i class="fa fa-eye"></i> ভিউ</button>
                                    </td>
                                </tr>
                                <tr>
                                    <td><strong class="font-monospace text-primary">DOC-2026-082</strong></td>
                                    <td><span class="badge bg-primary bg-opacity-10 text-primary">নামজারি ও ডি.সি.আর পরচা</span></td>
                                    <td>
                                        <div class="fw-bold">উত্তরা মডার্ন হাইটস</div>
                                        <small class="text-muted">মৌজা: বাউনিয়া, খতিয়ান: ৩০৮</small>
                                    </td>
                                    <td class="font-monospace fw-bold">MUT-994102</td>
                                    <td>
                                        <div>০৫ জানু ২০২৬</div>
                                        <small class="text-muted">খাজনা হালনাগাদ: ২০২৬</small>
                                    </td>
                                    <td><span class="badge bg-success bg-opacity-25 text-success">বৈধ ও হালনাগাদ</span></td>
                                    <td class="text-end">
                                        <button class="btn btn-sm btn-outline-danger me-1"><i class="fa fa-download"></i> PDF</button>
                                        <button class="btn btn-sm btn-outline-secondary"><i class="fa fa-eye"></i> ভিউ</button>
                                    </td>
                                </tr>
                                <tr>
                                    <td><strong class="font-monospace text-primary">DOC-2026-083</strong></td>
                                    <td><span class="badge bg-warning bg-opacity-10 text-warning">রাজউক অনুমোদন ও প্ল্যান</span></td>
                                    <td>
                                        <div class="fw-bold">ব্লুমিং লোটাস টাওয়ার (১৬ তলা)</div>
                                        <small class="text-muted">ভবন নির্মাণ ছাড়পত্র</small>
                                    </td>
                                    <td class="font-monospace fw-bold">RAJUK-B-2025-4412</td>
                                    <td>
                                        <div>২০ নভে ২০২৫</div>
                                        <small class="text-muted">মেয়াদ: ২০ নভে ২০২৮</small>
                                    </td>
                                    <td><span class="badge bg-warning bg-opacity-25 text-warning">রিভিউধীন (পরবর্তী ধাপ)</span></td>
                                    <td class="text-end">
                                        <button class="btn btn-sm btn-outline-danger me-1"><i class="fa fa-download"></i> PDF</button>
                                        <button class="btn btn-sm btn-outline-secondary"><i class="fa fa-eye"></i> ভিউ</button>
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

<!-- Modal: Add Document -->
<div class="modal fade" id="modalAddDocument" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content rounded-4 border-0 shadow">
            <div class="modal-header bg-danger bg-opacity-25 border-0">
                <h5 class="modal-title fw-bold text-dark"><i class="fa fa-file-pdf-o me-2"></i> নতুন আইনি দলিল ও রেকর্ড আপলোড</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form onsubmit="event.preventDefault(); alert('আইনি দলিল সফলভাবে ভল্টে সংরক্ষিত হয়েছে!'); location.reload();">
                <div class="modal-body p-4">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label fw-bold">দলিলের ধরণ</label>
                            <select class="form-select" required>
                                <option>মূল সাফ-কবলা দলিল (Title Deed)</option>
                                <option>নামজারি ও জমাভাগ পরচা (Mutation)</option>
                                <option>ভূমি উন্নয়ন কর দাখিলা (DCR & Tax Receipt)</option>
                                <option>সিএস / এসএ / আরএস / বিএস খতিয়ান</option>
                                <option>রাজউক / সিডিএ বিল্ডিং অনুমোদন প্ল্যান</option>
                                <option>পাওয়ার অব অ্যাটর্নি দলিল</option>
                                <option>আইনজীবী ভেটিং রিপোর্ট (Legal Vetting)</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold">প্রপার্টি / প্রজেক্ট রেফারেন্স</label>
                            <input type="text" class="form-control" required placeholder="যেমন: পূর্বাচল রিভারভিউ গ্রিন">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold">দলিল / খতিয়ান / ছাড়পত্র নম্বর</label>
                            <input type="text" class="form-control" required placeholder="DEED-xxxxx বা MUT-xxxxx">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold">মেয়াদ বা খাজনা পরিশোধের সাল</label>
                            <input type="text" class="form-control" placeholder="যেমন: ২০২৬ সন পর্যন্ত পরিশোধিত">
                        </div>
                        <div class="col-12">
                            <label class="form-label fw-bold">দলিল / স্ক্যান ফাইল (PDF, JPG, PNG)</label>
                            <input type="file" class="form-control" required>
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-0 bg-light">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">বাতিল</button>
                    <button type="submit" class="btn btn-danger fw-bold">ভল্টে সংরক্ষণ করুন</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

