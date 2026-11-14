@extends('layouts.backend.app')

@section('content')
@php
    $totalEmployeeShare = $totalEmployeeShare ?? 0;
    $totalCompanyShare = $totalCompanyShare ?? 0;
    $grandTotalPf = $grandTotalPf ?? 0;
    $records = $records ?? collect([]);
    $user = $user ?? Auth::user();
@endphp
<div class="content-wrapper">
    <div class="container-full">
        <!-- Main content -->
        <section class="content py-3">

            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h4 class="fw-bold text-dark mb-1"><i class="fa fa-shield text-success me-2"></i> কর্মচারী প্রভিডেন্ট ফান্ড (Provident Fund - P/F)</h4>
                    <p class="text-muted small mb-0">আপনার মাসিক সঞ্চয়, প্রতিষ্ঠানের সমপরিমাণ অবদান এবং পুঞ্জীভূত পিএফ স্টেটমেন্ট।</p>
                </div>
                <button onclick="window.print()" class="btn btn-outline-primary btn-sm rounded-pill px-3">
                    <i class="fa fa-print me-1"></i> পিএফ স্টেটমেন্ট প্রিন্ট
                </button>
            </div>

            <!-- PF KPI Summary -->
            <div class="row g-3 mb-4">
                <div class="col-sm-6 col-lg-4">
                    <div class="card border-0 shadow-sm rounded-4 p-3 bg-white">
                        <span class="text-muted small d-block">কর্মচারীর নিজস্ব অবদান (Employee Share)</span>
                        <h4 class="fw-bold text-dark mt-1 mb-0">{{ number_format($totalEmployeeShare, 2) }} ৳</h4>
                    </div>
                </div>
                <div class="col-sm-6 col-lg-4">
                    <div class="card border-0 shadow-sm rounded-4 p-3 bg-white">
                        <span class="text-muted small d-block">প্রতিষ্ঠানের অবদান (Company Match)</span>
                        <h4 class="fw-bold text-primary mt-1 mb-0">{{ number_format($totalCompanyShare, 2) }} ৳</h4>
                    </div>
                </div>
                <div class="col-sm-6 col-lg-4">
                    <div class="card border-0 shadow-sm rounded-4 p-3 bg-white">
                        <span class="text-muted small d-block">মোট পিএফ স্থিতি (Total Accumulation)</span>
                        <h4 class="fw-bold text-success mt-1 mb-0">{{ number_format($grandTotalPf, 2) }} ৳</h4>
                    </div>
                </div>
            </div>

            <!-- PF Monthly Records -->
            <div class="card border-0 shadow-sm rounded-4 p-4 bg-white">
                <h5 class="fw-bold text-dark mb-3"><i class="fa fa-list text-primary me-2"></i> মাসিক প্রভিডেন্ট ফান্ড লেজার স্টেটমেন্ট</h5>

                <div class="table-responsive">
                    <table class="table table-hover align-middle small mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>মাস ও বছর</th>
                                <th>কর্মচারী কর্তন (টাকা)</th>
                                <th>প্রতিষ্ঠানের শেয়ার (টাকা)</th>
                                <th>মোট মাসিক জমা (টাকা)</th>
                                <th>স্ট্যাটাস</th>
                                <th>মন্তব্য</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($records as $rec)
                            <tr>
                                <td class="fw-bold text-dark">{{ $rec->month_year }}</td>
                                <td class="text-dark">{{ number_format($rec->employee_contribution, 2) }} ৳</td>
                                <td class="text-primary">{{ number_format($rec->company_contribution, 2) }} ৳</td>
                                <td class="text-success fw-bold font-size-14">{{ number_format($rec->total_amount, 2) }} ৳</td>
                                <td>
                                    <span class="badge {{ $rec->status === 'credited' ? 'bg-success' : 'bg-warning text-dark' }} px-2 py-1">
                                        {{ $rec->status === 'credited' ? 'ক্রেডিটেড' : $rec->status }}
                                    </span>
                                </td>
                                <td class="text-muted">{{ $rec->remarks ?: 'স্বয়ংক্রিয় পে-রোল সমন্বয়' }}</td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="text-center py-4 text-muted">কোনো পিএফ রেকর্ড পাওয়া যায়নি।</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

        </section>
    </div>
</div>
@endsection
