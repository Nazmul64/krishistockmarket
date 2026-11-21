@extends('layouts.backend.app')

@section('content')
<div class="content-wrapper">
    <div class="container-full">
        <!-- Content Header -->
        <div class="content-header">
            <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
                <div>
                    <h3 class="page-title fw-bold text-dark mb-1">
                        <i class="fa fa-book text-primary me-2"></i>Daily Customer Distribution Register (দৈনিক বিতরণ রেজিস্টার)
                    </h3>
                    <p class="text-muted mb-0">এজেন্ট পয়েন্ট: <strong>{{ $point->name }}</strong> ({{ $point->code }}) | প্রতিদিনের গ্রাহক সেবা ও পণ্য বিতরণ তালিকা</p>
                </div>
                <div class="d-flex gap-2">
                    <a href="{{ route('agent.distribution.entry') }}" class="btn btn-primary btn-sm px-3 fw-semibold shadow-sm">
                        <i class="fa fa-qrcode me-1"></i> + নতুন বাজার বিতরণ (Scan & Distribute)
                    </a>
                    <a href="{{ route('agent.distribution.daily_closing') }}" class="btn btn-outline-warning btn-sm text-dark">
                        <i class="fa fa-lock me-1"></i> দৈনিক ক্লোজিং
                    </a>
                </div>
            </div>
        </div>

        <section class="content">
            <!-- 5 Summary KPI Counters for Selected Date -->
            <div class="row g-3 mb-4">
                <div class="col-xl col-md-4 col-6">
                    <div class="card border-0 shadow-sm rounded-3 bg-white p-3 h-100">
                        <div class="text-muted font-size-11 text-uppercase fw-semibold">Customers Served</div>
                        <h3 class="fw-bold text-dark mb-0 mt-1">{{ number_format($todayServedCount) }}</h3>
                        <small class="text-muted">আজকে সেবা গ্রহণকারী</small>
                    </div>
                </div>
                <div class="col-xl col-md-4 col-6">
                    <div class="card border-0 shadow-sm rounded-3 bg-white p-3 h-100">
                        <div class="text-muted font-size-11 text-uppercase fw-semibold">Monthly Markets</div>
                        <h3 class="fw-bold text-primary mb-0 mt-1">{{ number_format($todayMonthlyCount) }}</h3>
                        <small class="text-muted">মাসিক বাজার গ্রহণ</small>
                    </div>
                </div>
                <div class="col-xl col-md-4 col-6">
                    <div class="card border-0 shadow-sm rounded-3 bg-white p-3 h-100">
                        <div class="text-muted font-size-11 text-uppercase fw-semibold">Weekly Markets</div>
                        <h3 class="fw-bold text-info mb-0 mt-1">{{ number_format($todayWeeklyCount) }}</h3>
                        <small class="text-muted">সাপ্তাহিক বাজার গ্রহণ</small>
                    </div>
                </div>
                <div class="col-xl col-md-6 col-6">
                    <div class="card border-0 shadow-sm rounded-3 bg-white p-3 h-100">
                        <div class="text-muted font-size-11 text-uppercase fw-semibold">Total Product Volume</div>
                        <h3 class="fw-bold text-success mb-0 mt-1">{{ number_format($todayTotalQty, 1) }}</h3>
                        <small class="text-muted">মোট বিতরণকৃত পণ্য (Kg/L)</small>
                    </div>
                </div>
                <div class="col-xl col-md-6 col-12">
                    <div class="card border-0 shadow-sm rounded-3 bg-white p-3 h-100">
                        <div class="text-muted font-size-11 text-uppercase fw-semibold">Total Distribution Value</div>
                        <h3 class="fw-bold text-purple mb-0 mt-1" style="color: #6f42c1;">৳{{ number_format($todayTotalValue, 2) }}</h3>
                        <small class="text-muted">মোট বিতরণকৃত পণ্যের মূল্য</small>
                    </div>
                </div>
            </div>

            <!-- Register Table Card -->
            <div class="card border-0 shadow-sm rounded-3">
                <div class="card-header bg-white border-bottom py-3">
                    <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
                        <h5 class="card-title fw-bold text-dark mb-0 font-size-15">
                            <i class="fa fa-list text-primary me-2"></i>তারিখ: <strong>{{ date('d F, Y', strtotime($date)) }}</strong> এর দৈনিক খতিয়ান
                        </h5>
                        <form method="GET" action="{{ route('agent.distribution.daily_register') }}" class="d-flex gap-2">
                            <input type="date" name="date" class="form-control form-control-sm" value="{{ $date }}" onchange="this.form.submit()">
                            <select name="market_type" class="form-select form-select-sm" onchange="this.form.submit()">
                                <option value="">-- All Market Types --</option>
                                <option value="monthly_market" {{ request('market_type') == 'monthly_market' ? 'selected' : '' }}>Monthly Market</option>
                                <option value="weekly_market" {{ request('market_type') == 'weekly_market' ? 'selected' : '' }}>Weekly Market</option>
                                <option value="special_market" {{ request('market_type') == 'special_market' ? 'selected' : '' }}>Special Market</option>
                            </select>
                        </form>
                    </div>
                </div>

                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0 font-size-13">
                            <thead class="table-light text-muted font-size-11 text-uppercase">
                                <tr>
                                    <th class="ps-4" style="width: 60px;">SL</th>
                                    <th>Customer Name</th>
                                    <th>Card No.</th>
                                    <th>Market Type</th>
                                    <th class="text-center">Product Count</th>
                                    <th class="text-end">Total Amount</th>
                                    <th>Time</th>
                                    <th class="text-end pe-4">Receipt & Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($collections as $index => $col)
                                <tr>
                                    <td class="ps-4 text-muted">{{ $collections->firstItem() + $index }}</td>
                                    <td>
                                        <div class="fw-bold text-dark font-size-14">{{ $col->customer_name }}</div>
                                        <div class="text-muted font-size-11">{{ $col->customer_phone ?? 'N/A' }}</div>
                                    </td>
                                    <td>
                                        <span class="badge bg-primary-light text-primary font-size-12 fw-bold">
                                            {{ $col->card_number }}
                                        </span>
                                    </td>
                                    <td>
                                        <span class="badge bg-light text-dark font-size-11">
                                            {{ ucfirst(str_replace('_', ' ', $col->market_type)) }}
                                        </span>
                                    </td>
                                    <td class="text-center">
                                        <span class="badge bg-info-light text-info font-size-12 px-2 py-1">
                                            {{ $col->items->count() }} টি পণ্য
                                        </span>
                                    </td>
                                    <td class="text-end fw-bold text-primary font-size-14">
                                        ৳{{ number_format($col->total_amount, 2) }}
                                    </td>
                                    <td class="text-muted font-size-12">
                                        {{ $col->collection_time ? date('h:i A', strtotime($col->collection_time)) : '-' }}
                                    </td>
                                    <td class="text-end pe-4">
                                        <a href="{{ route('agent.distribution.receipt', $col->id) }}" class="btn btn-outline-primary btn-sm py-0 font-size-11" title="View Receipt">
                                            <i class="fa fa-print me-1"></i> রসিদ
                                        </a>
                                        <a href="{{ route('agent.distribution.customer.history', $col->user_id) }}" class="btn btn-outline-secondary btn-sm py-0 font-size-11 ms-1" title="Customer History">
                                            <i class="fa fa-history"></i>
                                        </a>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="8" class="text-center py-5 text-muted">
                                        এই তারিখে কোনো বাজার বিতরণ রেকর্ড পাওয়া যায়নি।
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                @if($collections->hasPages())
                <div class="card-footer bg-white border-top py-3">
                    <div class="d-flex justify-content-between align-items-center flex-wrap">
                        <span class="text-muted font-size-13">Showing {{ $collections->firstItem() }} to {{ $collections->lastItem() }} of {{ $collections->total() }} records</span>
                        {{ $collections->links() }}
                    </div>
                </div>
                @endif
            </div>
        </section>
    </div>
</div>
@endsection
