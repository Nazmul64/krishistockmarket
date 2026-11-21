@extends('layouts.backend.app')

@section('content')
<div class="content-wrapper">
    <div class="container-full">
        <!-- Content Header -->
        <div class="content-header">
            <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
                <div>
                    <h3 class="page-title fw-bold text-dark mb-1">
                        <i class="fa fa-calendar-check-o text-primary me-2"></i>Daily Agent Point Distribution Report
                    </h3>
                    <p class="text-muted mb-0">কেন্দ্রীয় অ্যাডমিন প্যানেল: প্রতিদিনের সকল এজেন্ট পয়েন্টের তুলনামূলক বাজার বিতরণ খতিয়ান</p>
                </div>
                <div class="d-flex gap-2">
                    <a href="{{ route('admin.distribution.dashboard') }}" class="btn btn-outline-secondary btn-sm">
                        <i class="fa fa-arrow-left me-1"></i> ড্যাশবোর্ডে ফিরে যান
                    </a>
                </div>
            </div>
        </div>

        <section class="content">
            <!-- Filter Bar -->
            <div class="card border-0 shadow-sm rounded-3 mb-4">
                <div class="card-body p-3">
                    <form method="GET" action="{{ route('admin.distribution.daily_agent_report') }}" class="row g-2 align-items-center">
                        <div class="col-md-4">
                            <label class="form-label font-size-12 fw-semibold mb-1">তারিখ নির্বাচন করুন:</label>
                            <input type="date" name="date" class="form-control form-control-sm" value="{{ $date }}" onchange="this.form.submit()">
                        </div>
                        <div class="col-md-2 d-flex align-items-end">
                            <button type="submit" class="btn btn-primary btn-sm w-100">
                                <i class="fa fa-filter me-1"></i> রিপোর্ট দেখুন
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- 3 Summary Metrics -->
            <div class="row g-3 mb-4">
                <div class="col-md-4">
                    <div class="card border-0 shadow-sm rounded-3 bg-white p-3">
                        <div class="text-muted font-size-11 text-uppercase fw-semibold">Total Customers Served</div>
                        <h3 class="fw-bold text-dark mb-0 mt-1">{{ number_format($grandTotalCustomers) }} জন</h3>
                        <small class="text-muted">তারিখ: {{ date('d F, Y', strtotime($date)) }}</small>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card border-0 shadow-sm rounded-3 bg-white p-3">
                        <div class="text-muted font-size-11 text-uppercase fw-semibold">Total Product Volume</div>
                        <h3 class="fw-bold text-success mb-0 mt-1">{{ number_format($grandTotalProduct, 1) }}</h3>
                        <small class="text-muted">সকল পয়েন্টে মোট বিতরণ (Kg/L)</small>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card border-0 shadow-sm rounded-3 bg-white p-3">
                        <div class="text-muted font-size-11 text-uppercase fw-semibold">Total Value Distributed</div>
                        <h3 class="fw-bold text-primary mb-0 mt-1">৳{{ number_format($grandTotalAmount, 2) }}</h3>
                        <small class="text-muted">সকল পয়েন্টে মোট বিতরণ মূল্য</small>
                    </div>
                </div>
            </div>

            <!-- Table (Section 16) -->
            <div class="card border-0 shadow-sm rounded-3">
                <div class="card-header bg-white border-bottom py-3">
                    <h5 class="card-title fw-bold text-dark mb-0 font-size-15">
                        <i class="fa fa-table text-primary me-2"></i>এজেন্ট পয়েন্টভিত্তিক দৈনিক রিপোর্ট ({{ date('d M, Y', strtotime($date)) }})
                    </h5>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0 font-size-13">
                            <thead class="table-light text-muted font-size-11 text-uppercase">
                                <tr>
                                    <th class="ps-4">Agent Point</th>
                                    <th class="text-center">Total Customer Served</th>
                                    <th class="text-center">Monthly Market</th>
                                    <th class="text-center">Weekly Market</th>
                                    <th class="text-end">Total Product (Volume)</th>
                                    <th class="text-end">Distribution Amount (৳)</th>
                                    <th class="text-end pe-4">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($dailyStats as $stat)
                                <tr>
                                    <td class="ps-4">
                                        <div class="fw-bold text-dark font-size-14">{{ $stat->point->name }}</div>
                                        <div class="text-muted font-size-11">{{ $stat->point->area }}, {{ $stat->point->district }}</div>
                                    </td>
                                    <td class="text-center">
                                        <span class="badge bg-light text-dark font-size-12 px-3 py-1">
                                            {{ number_format($stat->total_customer) }} জন
                                        </span>
                                    </td>
                                    <td class="text-center fw-semibold text-primary">
                                        {{ number_format($stat->monthly) }}
                                    </td>
                                    <td class="text-center fw-semibold text-info">
                                        {{ number_format($stat->weekly) }}
                                    </td>
                                    <td class="text-end fw-bold text-dark font-size-14">
                                        {{ number_format($stat->total_product, 1) }}
                                    </td>
                                    <td class="text-end fw-bold text-success font-size-14">
                                        ৳{{ number_format($stat->amount, 2) }}
                                    </td>
                                    <td class="text-end pe-4">
                                        <a href="{{ route('admin.distribution.point_summary', $stat->point->id) }}" class="btn btn-outline-info btn-sm py-0 font-size-11">
                                            পয়েন্ট বিস্তারিত
                                        </a>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="7" class="text-center py-5 text-muted">কোনো এজেন্ট পয়েন্টের ডাটা নেই</td>
                                </tr>
                                @endforelse
                            </tbody>
                            <tfoot class="table-light fw-bold font-size-14">
                                <tr>
                                    <td class="ps-4 text-dark">সর্বমোট (Grand Total):</td>
                                    <td class="text-center text-dark">{{ number_format($grandTotalCustomers) }} জন</td>
                                    <td colspan="2"></td>
                                    <td class="text-end text-dark">{{ number_format($grandTotalProduct, 1) }}</td>
                                    <td class="text-end text-success font-size-16">৳{{ number_format($grandTotalAmount, 2) }}</td>
                                    <td></td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
        </section>
    </div>
</div>
@endsection
