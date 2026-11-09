@extends('layouts.backend.app')

@section('content')
<div class="content-wrapper">
    <div class="container-full">
        <div class="content-header">
            <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
                <div class="me-auto">
                    <h3 class="page-title text-dark fw-bold mb-1">
                        <i class="fa fa-users text-primary me-2"></i> বিনিয়োগকারী ও কেওয়াইসি (Investors & KYC)
                    </h3>
                    <div class="d-inline-block align-items-center">
                        <nav>
                            <ol class="breadcrumb mb-0 p-0 bg-transparent">
                                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">ড্যাশবোর্ড</a></li>
                                <li class="breadcrumb-item"><a href="{{ route('admin.landshare.hub') }}">রিয়েল এস্টেট</a></li>
                                <li class="breadcrumb-item active" aria-current="page">বিনিয়োগকারী ডিরেক্টরি</li>
                            </ol>
                        </nav>
                    </div>
                </div>
            </div>
        </div>

        <section class="content">
            <div class="box bg-white border-0 shadow-sm rounded-4 overflow-hidden">
                <div class="box-header with-border py-3 px-4">
                    <h5 class="box-title fw-bold text-dark mb-0">বিনিয়োগকারী তালিকা ও এনআইডি ভেরিফিকেশন কিউ</h5>
                </div>
                <div class="box-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>বিনিয়োগকারী</th>
                                    <th>যোগাযোগ</th>
                                    <th>এনআইডি (NID)</th>
                                    <th>মোট বিনিয়োগ</th>
                                    <th>কেওয়াইসি স্ট্যাটাস</th>
                                    <th class="text-end">অ্যাকশন</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>
                                        <div class="fw-bold text-dark">তানভীর আহমেদ</div>
                                        <small class="text-muted">ID: INV-2026-001</small>
                                    </td>
                                    <td>
                                        <small class="d-block">+8801711223344</small>
                                        <small class="text-muted">investor@landshare.com</small>
                                    </td>
                                    <td><span class="font-monospace">1987541289654125</span></td>
                                    <td><strong class="text-success font-monospace">৳ ১৫,০০,০০০</strong></td>
                                    <td><span class="badge bg-success bg-opacity-25 text-success">Verified</span></td>
                                    <td class="text-end">
                                        <button class="btn btn-sm btn-outline-secondary">স্টেটমেন্ট</button>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <div class="fw-bold text-dark">মো. রফিকুল ইসলাম</div>
                                        <small class="text-muted">ID: INV-2026-002</small>
                                    </td>
                                    <td>
                                        <small class="d-block">+8801822334455</small>
                                        <small class="text-muted">rafiqul.invest@gmail.com</small>
                                    </td>
                                    <td><span class="font-monospace">2847596182746152</span></td>
                                    <td><strong class="text-success font-monospace">৳ ৫,০০,০০০</strong></td>
                                    <td><span class="badge bg-warning bg-opacity-25 text-warning">Pending Review</span></td>
                                    <td class="text-end">
                                        <button class="btn btn-sm btn-success" onclick="alert('কেওয়াইসি সফলভাবে অনুমোদিত হয়েছে!'); location.reload();">অনুমোদন</button>
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
@endsection
