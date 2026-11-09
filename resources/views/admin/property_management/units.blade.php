@extends('layouts.backend.app')

@section('content')
<div class="content-wrapper">
    <div class="container-full">
        <div class="content-header">
            <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
                <div class="me-auto">
                    <h3 class="page-title text-dark fw-bold mb-1">
                        <i class="fa fa-cubes text-primary me-2"></i> ফ্ল্যাট ও একক ইউনিট ব্যবস্থাপনা (Flats & Units)
                    </h3>
                    <div class="d-inline-block align-items-center">
                        <nav>
                            <ol class="breadcrumb mb-0 p-0 bg-transparent">
                                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">ড্যাশবোর্ড</a></li>
                                <li class="breadcrumb-item"><a href="{{ route('admin.landshare.hub') }}">রিয়েল এস্টেট</a></li>
                                <li class="breadcrumb-item active" aria-current="page">ফ্ল্যাট ও ইউনিট</li>
                            </ol>
                        </nav>
                    </div>
                </div>

                <button type="button" class="btn btn-primary btn-sm shadow-sm px-3 py-2 fw-bold" data-bs-toggle="modal" data-bs-target="#modalAddUnit">
                    <i class="fa fa-plus-circle me-1"></i> নতুন ইউনিট যোগ করুন (Add Unit / Flat)
                </button>
            </div>
        </div>

        <section class="content">
            <div class="box bg-white border-0 shadow-sm rounded-4 overflow-hidden">
                <div class="box-header with-border py-3 px-4">
                    <h5 class="box-title fw-bold text-dark mb-0">ভবন ও ফ্লোর অনুযায়ী ফ্ল্যাট/প্লট তালিকা</h5>
                </div>
                <div class="box-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>ইউনিট নম্বর</th>
                                    <th>প্রপার্টি / ভবন</th>
                                    <th>ফ্লোর ও সাইজ</th>
                                    <th>বেড ও বাথ</th>
                                    <th>বিক্রয় মূল্য</th>
                                    <th>স্ট্যাটাস</th>
                                    <th class="text-end">অ্যাকশন</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td><strong class="text-dark">ফ্ল্যাট ৪এ (Flat 4A)</strong></td>
                                    <td>উত্তরা রয়েল হাইটস</td>
                                    <td>৪র্থ তলা • ১৮৫০ বর্গফুট</td>
                                    <td>৩ বেড, ৩ বাথ</td>
                                    <td class="font-monospace fw-bold text-success">৳ ১,৮৫,০০,০০০</td>
                                    <td><span class="badge bg-success bg-opacity-25 text-success">Available</span></td>
                                    <td class="text-end"><button class="btn btn-sm btn-outline-primary">এডিট</button></td>
                                </tr>
                                <tr>
                                    <td><strong class="text-dark">ফ্ল্যাট ৪বি (Flat 4B)</strong></td>
                                    <td>উত্তরা রয়েল হাইটস</td>
                                    <td>৪র্থ তলা • ১৯২০ বর্গফুট</td>
                                    <td>৪ বেড, ৪ বাথ</td>
                                    <td class="font-monospace fw-bold text-success">৳ ১,৯২,০০,০০০</td>
                                    <td><span class="badge bg-warning bg-opacity-25 text-warning">Booked</span></td>
                                    <td class="text-end"><button class="btn btn-sm btn-outline-primary">এডিট</button></td>
                                </tr>
                                <tr>
                                    <td><strong class="text-dark">প্লট নং ১২, রোড ৩</strong></td>
                                    <td>পূর্বাচল গ্রিন সিটি</td>
                                    <td>গ্রাউন্ড • ৫ কাঠা</td>
                                    <td>-</td>
                                    <td class="font-monospace fw-bold text-success">৳ ২,৫০,০০,০০০</td>
                                    <td><span class="badge bg-danger bg-opacity-25 text-danger">Sold</span></td>
                                    <td class="text-end"><button class="btn btn-sm btn-outline-primary">এডিট</button></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </section>
    </div>
</div>

<div class="modal fade" id="modalAddUnit" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg rounded-4">
            <div class="modal-header bg-primary text-white py-3 px-4">
                <h5 class="modal-title fw-bold mb-0">নতুন ফ্ল্যাট বা ইউনিট এন্ট্রি</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form onsubmit="event.preventDefault(); alert('ইউনিট সফলভাবে সংরক্ষিত হয়েছে!'); bootstrap.Modal.getInstance(document.getElementById('modalAddUnit')).hide(); location.reload();">
                <div class="modal-body p-4">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label small fw-bold">প্রপার্টি / ভবন</label>
                            <select class="form-select" required>
                                <option value="1">উত্তরা রয়েল হাইটস</option>
                                <option value="2">পূর্বাচল গ্রিন সিটি</option>
                                <option value="3">বারিধারা লেকভিউ বাণিজ্যিক টাওয়ার</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-bold">ইউনিট বা ফ্ল্যাট নম্বর</label>
                            <input type="text" required class="form-control" placeholder="যেমন: Flat 5A">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label small fw-bold">ফ্লোর নম্বর</label>
                            <input type="text" class="form-control" placeholder="যেমন: ৫ম তলা">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label small fw-bold">আয়তন (বর্গফুট / কাঠা)</label>
                            <input type="number" required class="form-control" placeholder="যেমন: 1850">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label small fw-bold">বিক্রয় মূল্য (৳)</label>
                            <input type="number" required class="form-control font-monospace text-success fw-bold" placeholder="যেমন: 18500000">
                        </div>
                    </div>
                </div>
                <div class="modal-footer bg-light py-3 px-4">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">বাতিল</button>
                    <button type="submit" class="btn btn-primary px-4 fw-bold">সংরক্ষণ করুন</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
