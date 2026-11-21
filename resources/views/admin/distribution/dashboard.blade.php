@extends('layouts.backend.app')

@section('content')
<div class="content-wrapper">
    <div class="container-full">
        <!-- Content Header -->
        <div class="content-header">
            <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
                <div>
                    <h3 class="page-title fw-bold text-dark mb-1">
                        <i class="fa fa-dashboard text-primary me-2"></i>Central Customer Distribution Dashboard
                    </h3>
                    <p class="text-muted mb-0">কেন্দ্রীয় অ্যাডমিন প্যানেল: সকল এজেন্ট পয়েন্টের বাস্তব গ্রাহক সেবা, বাজার বিতরণ ও আর্থিক পর্যবেক্ষণ</p>
                </div>
                <div class="d-flex gap-2 flex-wrap">
                    <a href="{{ route('admin.distribution.daily_agent_report') }}" class="btn btn-outline-info btn-sm">
                        <i class="fa fa-calendar me-1"></i> দৈনিক পয়েন্ট রিপোর্ট
                    </a>
                    <a href="{{ route('admin.distribution.product_report') }}" class="btn btn-outline-success btn-sm">
                        <i class="fa fa-cubes me-1"></i> পণ্যভিত্তিক রিপোর্ট
                    </a>
                    <a href="{{ route('admin.distribution.comparison_report') }}" class="btn btn-outline-warning btn-sm text-dark">
                        <i class="fa fa-bar-chart me-1"></i> পয়েন্ট তুলনা
                    </a>
                    <a href="{{ route('admin.distribution.daily_closings') }}" class="btn btn-outline-primary btn-sm">
                        <i class="fa fa-lock me-1"></i> পয়েন্ট ক্লোজিং যাচাই
                    </a>
                </div>
            </div>
        </div>

        <section class="content">
            <!-- 11 Central KPIs (Section 12) -->
            <div class="row g-3 mb-4">
                <!-- KPI 1 -->
                <div class="col-xl-3 col-md-6">
                    <div class="card border-0 shadow-sm rounded-3 bg-white h-100">
                        <div class="card-body d-flex align-items-center justify-content-between p-3">
                            <div>
                                <span class="text-muted text-uppercase fw-semibold font-size-11">Total Agent Points</span>
                                <h3 class="fw-bold mb-0 text-dark mt-1">{{ number_format($totalAgentPoints) }}</h3>
                                <small class="text-muted">সক্রিয় বিতরণ কেন্দ্র</small>
                            </div>
                            <div class="rounded-circle bg-primary-light p-3 text-primary">
                                <i class="fa fa-building fa-2x"></i>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- KPI 2 -->
                <div class="col-xl-3 col-md-6">
                    <div class="card border-0 shadow-sm rounded-3 bg-white h-100">
                        <div class="card-body d-flex align-items-center justify-content-between p-3">
                            <div>
                                <span class="text-muted text-uppercase fw-semibold font-size-11">Registered Cardholders</span>
                                <h3 class="fw-bold mb-0 text-info mt-1">{{ number_format($totalRegisteredCardHolders) }}</h3>
                                <small class="text-muted">মোট কার্ডধারী গ্রাহক</small>
                            </div>
                            <div class="rounded-circle bg-info-light p-3 text-info">
                                <i class="fa fa-id-card fa-2x"></i>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- KPI 3 -->
                <div class="col-xl-3 col-md-6">
                    <div class="card border-0 shadow-sm rounded-3 bg-white h-100">
                        <div class="card-body d-flex align-items-center justify-content-between p-3">
                            <div>
                                <span class="text-muted text-uppercase fw-semibold font-size-11">Customers Served Today</span>
                                <h3 class="fw-bold mb-0 text-success mt-1">{{ number_format($totalCustomersServedToday) }}</h3>
                                <small class="text-success fw-semibold">আজকে বাজার গ্রহণকারী</small>
                            </div>
                            <div class="rounded-circle bg-success-light p-3 text-success">
                                <i class="fa fa-user-check fa-2x"></i>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- KPI 4 -->
                <div class="col-xl-3 col-md-6">
                    <div class="card border-0 shadow-sm rounded-3 bg-white h-100">
                        <div class="card-body d-flex align-items-center justify-content-between p-3">
                            <div>
                                <span class="text-muted text-uppercase fw-semibold font-size-11">Served This Month</span>
                                <h3 class="fw-bold mb-0 text-primary mt-1">{{ number_format($totalCustomersServedThisMonth) }}</h3>
                                <small class="text-muted">চলতি মাসে বিতরণকৃত</small>
                            </div>
                            <div class="rounded-circle bg-primary-light p-3 text-primary">
                                <i class="fa fa-calendar-check-o fa-2x"></i>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- KPI 5 & 6 -->
                <div class="col-xl-3 col-md-6">
                    <div class="card border-0 shadow-sm rounded-3 bg-white h-100">
                        <div class="card-body p-3">
                            <span class="text-muted text-uppercase fw-semibold font-size-11">Monthly vs Weekly Markets</span>
                            <div class="d-flex justify-content-between align-items-center mt-2">
                                <div>
                                    <div class="fw-bold text-dark font-size-16">{{ number_format($totalMonthlyMarketsDistributed) }}</div>
                                    <small class="text-muted">Monthly</small>
                                </div>
                                <div class="border-start ps-3">
                                    <div class="fw-bold text-info font-size-16">{{ number_format($totalWeeklyMarketsDistributed) }}</div>
                                    <small class="text-muted">Weekly</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- KPI 7 -->
                <div class="col-xl-3 col-md-6">
                    <div class="card border-0 shadow-sm rounded-3 bg-white h-100">
                        <div class="card-body d-flex align-items-center justify-content-between p-3">
                            <div>
                                <span class="text-muted text-uppercase fw-semibold font-size-11">Total Product Distributed</span>
                                <h3 class="fw-bold mb-0 text-danger mt-1">{{ number_format($totalProductDistributed, 1) }}</h3>
                                <small class="text-muted">মোট বিতরণ পরিমাণ (Kg/L)</small>
                            </div>
                            <div class="rounded-circle bg-danger-light p-3 text-danger">
                                <i class="fa fa-cubes fa-2x"></i>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- KPI 8 -->
                <div class="col-xl-3 col-md-6">
                    <div class="card border-0 shadow-sm rounded-3 bg-white h-100">
                        <div class="card-body d-flex align-items-center justify-content-between p-3">
                            <div>
                                <span class="text-muted text-uppercase fw-semibold font-size-11">Total Distribution Value</span>
                                <h3 class="fw-bold mb-0 text-purple mt-1" style="color: #6f42c1;">৳{{ number_format($totalDistributionValue, 2) }}</h3>
                                <small class="text-muted">বিতরণকৃত পণ্যের মোট মূল্য</small>
                            </div>
                            <div class="rounded-circle p-3" style="background: #f3ebfc; color: #6f42c1;">
                                <i class="fa fa-money fa-2x"></i>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- KPI 9, 10, 11 -->
                <div class="col-xl-3 col-md-6">
                    <div class="card border-0 shadow-sm rounded-3 bg-white h-100">
                        <div class="card-body p-3">
                            <span class="text-muted text-uppercase fw-semibold font-size-11">Collection Status Overview</span>
                            <div class="d-flex justify-content-between align-items-center mt-2 font-size-12">
                                <span class="badge bg-success-light text-success">Full: {{ number_format($totalFullyCollected) }}</span>
                                <span class="badge bg-warning-light text-warning">Partial: {{ number_format($totalPartialCollection) }}</span>
                                <span class="badge bg-danger-light text-danger">Pending: {{ number_format($totalPendingCustomers) }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Agent Point-wise Distribution Table & Recent Stream -->
            <div class="row g-4 mb-4">
                <!-- Agent Points Distribution Summary Table -->
                <div class="col-xl-7">
                    <div class="card border-0 shadow-sm rounded-3 h-100">
                        <div class="card-header bg-white border-bottom py-3 d-flex justify-content-between align-items-center">
                            <h5 class="card-title fw-bold text-dark mb-0 font-size-15">
                                <i class="fa fa-map-marker text-primary me-2"></i>এজেন্ট পয়েন্টভিত্তিক বিতরণ সারসংক্ষেপ (Agent Point Summaries)
                            </h5>
                            <a href="{{ route('admin.distribution.comparison_report') }}" class="btn btn-outline-primary btn-sm py-0 font-size-12">পূর্ণাঙ্গ তুলনা &rarr;</a>
                        </div>
                        <div class="card-body p-0">
                            <div class="table-responsive">
                                <table class="table table-hover align-middle mb-0 font-size-13">
                                    <thead class="table-light text-muted font-size-11 text-uppercase">
                                        <tr>
                                            <th class="ps-3">Agent Point</th>
                                            <th class="text-center">Total Members</th>
                                            <th class="text-center">Served Today</th>
                                            <th class="text-end">Distributed (Qty)</th>
                                            <th class="text-end">Total Value (৳)</th>
                                            <th class="text-end pe-3">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($agentPointSummaries as $ap)
                                        <tr>
                                            <td class="ps-3">
                                                <div class="fw-bold text-dark font-size-14">{{ $ap->name }}</div>
                                                <div class="text-muted font-size-11">{{ $ap->area }}, {{ $ap->district }}</div>
                                            </td>
                                            <td class="text-center">
                                                <span class="badge bg-light text-dark">{{ number_format($ap->total_cardholders) }}</span>
                                            </td>
                                            <td class="text-center">
                                                <span class="badge bg-success-light text-success font-size-12 fw-bold">
                                                    {{ number_format($ap->served_today) }}
                                                </span>
                                            </td>
                                            <td class="text-end fw-semibold text-dark">
                                                {{ number_format($ap->total_qty, 1) }}
                                            </td>
                                            <td class="text-end fw-bold text-primary">
                                                ৳{{ number_format($ap->total_amount, 2) }}
                                            </td>
                                            <td class="text-end pe-3">
                                                <a href="{{ route('admin.distribution.point_summary', $ap->id) }}" class="btn btn-outline-info btn-sm py-0 font-size-11">
                                                    পয়েন্ট রিপোর্ট
                                                </a>
                                            </td>
                                        </tr>
                                        @empty
                                        <tr>
                                            <td colspan="6" class="text-center py-4 text-muted">কোনো এজেন্ট পয়েন্টের ডাটা নেই</td>
                                        </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Recent Live Customer Collections Stream -->
                <div class="col-xl-5">
                    <div class="card border-0 shadow-sm rounded-3 h-100">
                        <div class="card-header bg-white border-bottom py-3 d-flex justify-content-between align-items-center">
                            <h5 class="card-title fw-bold text-dark mb-0 font-size-15">
                                <i class="fa fa-clock-o text-success me-2"></i>রিয়েল-টাইম বিতরণ ট্রানজেকশন (Live Stream)
                            </h5>
                            <a href="{{ route('admin.distribution.date_wise_report') }}" class="btn btn-outline-secondary btn-sm py-0 font-size-12">সকল রেকর্ড &rarr;</a>
                        </div>
                        <div class="card-body p-0">
                            <div class="table-responsive">
                                <table class="table table-hover align-middle mb-0 font-size-13">
                                    <thead class="table-light text-muted font-size-11 text-uppercase">
                                        <tr>
                                            <th class="ps-3">Customer & Card</th>
                                            <th>Agent Point</th>
                                            <th class="text-end">Value</th>
                                            <th class="text-end pe-3">View</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($recentCollections as $col)
                                        <tr>
                                            <td class="ps-3">
                                                <div class="fw-bold text-dark">{{ $col->customer_name }}</div>
                                                <span class="badge bg-primary-light text-primary font-size-11">{{ $col->card_number }}</span>
                                            </td>
                                            <td>
                                                <div class="text-dark font-size-12">{{ optional($col->agentPoint)->name ?? 'Point' }}</div>
                                                <div class="text-muted font-size-10">{{ date('d M, h:i A', strtotime($col->created_at)) }}</div>
                                            </td>
                                            <td class="text-end fw-bold text-primary font-size-13">
                                                ৳{{ number_format($col->total_amount, 2) }}
                                            </td>
                                            <td class="text-end pe-3">
                                                <a href="{{ route('admin.distribution.transaction_details', $col->id) }}" class="btn btn-outline-secondary btn-sm py-0 font-size-11">
                                                    <i class="fa fa-eye"></i>
                                                </a>
                                            </td>
                                        </tr>
                                        @empty
                                        <tr>
                                            <td colspan="4" class="text-center py-4 text-muted">কোনো সাম্প্রতিক লেনদেন পাওয়া যায়নি</td>
                                        </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
</div>
@endsection
