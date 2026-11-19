@extends('layouts.backend.app')

@section('content')
<div class="content-wrapper">
    <div class="container-full">
        <!-- Content Header -->
        <div class="content-header">
            <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
                <div>
                    <h3 class="page-title text-dark fw-bold mb-1">
                        <i class="ti-bar-chart text-success me-2"></i>ব্রাঞ্চ বিক্রয়, তারিখ, মাস ও লাভ-ক্ষতি অ্যানালিটিক্স
                    </h3>
                    <p class="text-muted mb-0">সকল ব্রাঞ্চের বিক্রয় হিসাব, মাস ও তারিখভিত্তিক হিসাব, টপ পণ্য, পরিচালন ব্যয় (বিদ্যুৎ বিল/ভাড়া) ও নিট লাভ</p>
                </div>
                <div class="d-flex gap-2">
                    <button type="button" class="btn btn-sm btn-outline-secondary" onclick="window.print()">
                        <i class="ti-printer me-1"></i> রিপোর্ট প্রিন্ট
                    </button>
                    <a href="{{ route('admin.supershop.dashboard') }}" class="btn btn-sm btn-primary">
                        <i class="ti-arrow-left me-1"></i> ড্যাশবোর্ড
                    </a>
                </div>
            </div>
        </div>

        <!-- Main Content -->
        <section class="content">
            <!-- Filter Bar -->
            <div class="box shadow-sm mb-4">
                <div class="box-body py-2">
                    <form method="GET" action="{{ route('admin.supershop.reports') }}" class="row g-2 align-items-center">
                        <div class="col-md-3 col-12">
                            <label class="form-label small text-muted mb-0">ব্রাঞ্চ ফিল্টার:</label>
                            <select name="branch_id" class="form-select form-select-sm" onchange="this.form.submit()">
                                <option value="">🏢 সকল ব্রাঞ্চ সমন্বিত (Consolidated)</option>
                                @foreach($branches as $b)
                                    <option value="{{ $b->id }}" {{ $selectedBranchId == $b->id ? 'selected' : '' }}>
                                        {{ $b->code }} - {{ $b->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-3 col-6">
                            <label class="form-label small text-muted mb-0">সময়কাল ফিল্টার:</label>
                            <select name="period" class="form-select form-select-sm" onchange="this.form.submit()">
                                <option value="daily" {{ $period == 'daily' ? 'selected' : '' }}>আজকের দিন (Daily)</option>
                                <option value="weekly" {{ $period == 'weekly' ? 'selected' : '' }}>গত ৭ দিন (Weekly)</option>
                                <option value="monthly" {{ $period == 'monthly' ? 'selected' : '' }}>চলতি মাস (Monthly)</option>
                                <option value="yearly" {{ $period == 'yearly' ? 'selected' : '' }}>চলতি বছর (Yearly)</option>
                                <option value="custom" {{ $period == 'custom' ? 'selected' : '' }}>কাস্টম রেঞ্জ (Custom)</option>
                            </select>
                        </div>

                        <div class="col-md-2 col-6">
                            <label class="form-label small text-muted mb-0">শুরুর তারিখ:</label>
                            <input type="date" name="from_date" class="form-control form-control-sm" value="{{ $startDate->toDateString() }}">
                        </div>

                        <div class="col-md-2 col-6">
                            <label class="form-label small text-muted mb-0">শেষের তারিখ:</label>
                            <input type="date" name="to_date" class="form-control form-control-sm" value="{{ $endDate->toDateString() }}">
                        </div>

                        <div class="col-md-2 col-6 text-end">
                            <label class="form-label small d-none d-md-block mb-0">&nbsp;</label>
                            <button type="submit" class="btn btn-sm btn-primary w-100"><i class="ti-filter me-1"></i> ফিল্টার প্রয়োগ</button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Top Summary KPI Cards -->
            <div class="row g-3 mb-4">
                <div class="col-xl-3 col-md-6 col-12">
                    <div class="box shadow-sm border-start border-4 border-success mb-0 h-100">
                        <div class="box-body py-3">
                            <div class="d-flex align-items-center justify-content-between">
                                <div>
                                    <p class="text-muted small mb-1 fw-bold text-uppercase">মোট রিটেল নিট বিক্রয়</p>
                                    <h3 class="fw-bold text-success mb-0">৳ {{ number_format($totalNetSales, 2) }}</h3>
                                    <span class="badge bg-success-light text-success mt-1">গ্রস: ৳ {{ number_format($totalGrossSales, 2) }}</span>
                                </div>
                                <div class="bg-success-light text-success rounded-circle p-3 fs-3">
                                    <i class="ti-shopping-cart"></i>
                                </div>
                            </div>
                            <div class="mt-2 text-muted small">
                                <i class="ti-receipt text-info me-1"></i> মোট সফল অর্ডার: <strong>{{ number_format($totalTransactions) }} টি</strong>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-xl-3 col-md-6 col-12">
                    <div class="box shadow-sm border-start border-4 border-primary mb-0 h-100">
                        <div class="box-body py-3">
                            <div class="d-flex align-items-center justify-content-between">
                                <div>
                                    <p class="text-muted small mb-1 fw-bold text-uppercase">নিট অপারেটিং প্রফিট (Net Profit)</p>
                                    <h3 class="fw-bold {{ $netProfit >= 0 ? 'text-primary' : 'text-danger' }} mb-0">৳ {{ number_format($netProfit, 2) }}</h3>
                                    <span class="badge {{ $netProfit >= 0 ? 'bg-primary-light text-primary' : 'bg-danger-light text-danger' }} mt-1">
                                        নিট মার্জিন: {{ $totalNetSales > 0 ? round(($netProfit / $totalNetSales) * 100, 1) : 0 }}%
                                    </span>
                                </div>
                                <div class="bg-primary-light text-primary rounded-circle p-3 fs-3">
                                    <i class="ti-stats-up"></i>
                                </div>
                            </div>
                            <div class="mt-2 text-muted small">
                                <i class="ti-tag text-success me-1"></i> গ্রস প্রফিট: <strong>৳ {{ number_format($grossProfit, 2) }}</strong>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-xl-3 col-md-6 col-12">
                    <div class="box shadow-sm border-start border-4 border-danger mb-0 h-100">
                        <div class="box-body py-3">
                            <div class="d-flex align-items-center justify-content-between">
                                <div>
                                    <p class="text-muted small mb-1 fw-bold text-uppercase">ব্রাঞ্চ পরিচালন ও ইউটিলিটি খরচ</p>
                                    <h3 class="fw-bold text-danger mb-0">৳ {{ number_format($totalExpenses, 2) }}</h3>
                                    <span class="badge bg-danger-light text-danger mt-1">বিদ্যুৎ, ভাড়া, বেতন ইত্যাদি</span>
                                </div>
                                <div class="bg-danger-light text-danger rounded-circle p-3 fs-3">
                                    <i class="ti-wallet"></i>
                                </div>
                            </div>
                            <div class="mt-2 text-muted small">
                                <i class="ti-bolt text-warning me-1"></i> COGS (ক্রয়মূল্য): <strong>৳ {{ number_format($totalCogs, 2) }}</strong>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-xl-3 col-md-6 col-12">
                    <div class="box shadow-sm border-start border-4 border-warning mb-0 h-100">
                        <div class="box-body py-3">
                            <div class="d-flex align-items-center justify-content-between">
                                <div>
                                    <p class="text-muted small mb-1 fw-bold text-uppercase">বাতিল ও রিটার্ন হিসাব</p>
                                    <h3 class="fw-bold text-warning mb-0">{{ number_format($cancelledCount) }} টি অর্ডার</h3>
                                    <span class="badge bg-warning-light text-warning mt-1">মূল্য: ৳ {{ number_format($cancelledAmount, 2) }}</span>
                                </div>
                                <div class="bg-warning-light text-warning rounded-circle p-3 fs-3">
                                    <i class="ti-alert"></i>
                                </div>
                            </div>
                            <div class="mt-2 text-muted small">
                                <i class="ti-world text-info me-1"></i> অনলাইন সেল: <strong>৳ {{ number_format($totalOnlineSales, 2) }}</strong> ({{ $totalOnlineOrders }} অর্ডার)
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Tabbed Navigation for Clean Visual Flow -->
            <div class="box shadow-sm">
                <div class="box-header with-border">
                    <ul class="nav nav-pills custom-pills" id="reportTabs" role="tablist">
                        <li class="nav-item">
                            <button class="nav-link active fw-bold" id="tab-overview" data-bs-toggle="pill" data-bs-target="#overview" type="button" role="tab">
                                <i class="ti-bar-chart me-1"></i> ১. আয় বিবরণী ও ব্রাঞ্চ ম্যাট্রিক্স
                            </button>
                        </li>
                        <li class="nav-item">
                            <button class="nav-link fw-bold" id="tab-products" data-bs-toggle="pill" data-bs-target="#products" type="button" role="tab">
                                <i class="ti-package me-1"></i> ২. শীর্ষ বিক্রিত পণ্য (Top Products)
                            </button>
                        </li>
                        <li class="nav-item">
                            <button class="nav-link fw-bold" id="tab-monthly" data-bs-toggle="pill" data-bs-target="#monthly" type="button" role="tab">
                                <i class="ti-calendar me-1"></i> ৩. মাসভিত্তিক বিক্রয় হিসাব (Monthly)
                            </button>
                        </li>
                        <li class="nav-item">
                            <button class="nav-link fw-bold" id="tab-daily" data-bs-toggle="pill" data-bs-target="#daily" type="button" role="tab">
                                <i class="ti-time me-1"></i> ৪. তারিখভিত্তিক বিক্রয় হিসাব (Daily)
                            </button>
                        </li>
                        <li class="nav-item">
                            <button class="nav-link fw-bold" id="tab-expenses" data-bs-toggle="pill" data-bs-target="#expenses" type="button" role="tab">
                                <i class="ti-money me-1"></i> ৫. পরিচালন ব্যয় ও ইউটিলিটি (Expenses)
                            </button>
                        </li>
                    </ul>
                </div>

                <div class="box-body p-0">
                    <div class="tab-content" id="reportTabsContent">

                        <!-- TAB 1: P&L Statement & Branch Matrix -->
                        <div class="tab-pane fade show active p-3" id="overview" role="tabpanel">
                            <!-- Income Statement / P&L Table -->
                            <div class="card border mb-4">
                                <div class="card-header bg-light d-flex justify-content-between align-items-center">
                                    <h5 class="fw-bold text-dark mb-0">
                                        <i class="ti-money text-success me-2"></i>আয় বিবরণী ও সামগ্রিক লাভ-ক্ষতি (Consolidated P&L)
                                    </h5>
                                    <span class="badge bg-white text-muted border">
                                        রেঞ্জ: {{ $startDate->format('d M Y') }} - {{ $endDate->format('d M Y') }}
                                    </span>
                                </div>
                                <div class="card-body p-0">
                                    <div class="table-responsive">
                                        <table class="table table-bordered mb-0 align-middle">
                                            <tbody>
                                                <tr>
                                                    <td class="fw-bold fs-6">১. মোট গ্রস রিটেল বিক্রয় (Gross Retail Sales)</td>
                                                    <td class="text-end fw-bold fs-6">৳ {{ number_format($totalGrossSales, 2) }}</td>
                                                </tr>
                                                <tr>
                                                    <td class="text-muted ps-4">(-) বিক্রয় ডিসকাউন্ট ও অফার রিবেট</td>
                                                    <td class="text-end text-danger">- ৳ {{ number_format($totalDiscounts, 2) }}</td>
                                                </tr>
                                                <tr class="table-light">
                                                    <td class="fw-bold">২. নিট রিটেল বিক্রয় আয় (Net Retail Revenue)</td>
                                                    <td class="text-end fw-bold text-primary fs-5">৳ {{ number_format($totalNetSales, 2) }}</td>
                                                </tr>
                                                <tr>
                                                    <td class="text-danger ps-4">(-) বিক্রিত পণ্যের মোট ক্রয়মূল্য (Cost of Goods Sold - COGS)</td>
                                                    <td class="text-end text-danger">- ৳ {{ number_format($totalCogs, 2) }}</td>
                                                </tr>
                                                <tr class="table-success">
                                                    <td class="fw-bold fs-5 text-success">৩. গ্রস প্রফিট / মোট লাভ (Gross Profit)</td>
                                                    <td class="text-end fw-bold fs-5 text-success">
                                                        ৳ {{ number_format($grossProfit, 2) }}
                                                        <span class="small text-muted ms-2">({{ $totalNetSales > 0 ? round(($grossProfit / $totalNetSales) * 100, 1) : 0 }}% Margin)</span>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td class="text-muted ps-4">(-) শাখা পরিচালন ও অপারেটিং ব্যয় (Operating Expenses: Rent, Electricity, Staff Wages)</td>
                                                    <td class="text-end text-danger">- ৳ {{ number_format($totalExpenses, 2) }}</td>
                                                </tr>
                                                <tr class="{{ $netProfit >= 0 ? 'bg-gradient-success text-white' : 'bg-gradient-danger text-white' }}">
                                                    <td class="fw-bold fs-4 text-white">৪. চূড়ান্ত নিট অপারেটিং লাভ (Net Operating Profit)</td>
                                                    <td class="text-end fw-bold fs-4 text-white">
                                                        ৳ {{ number_format($netProfit, 2) }}
                                                        <span class="small text-white-50 ms-2">({{ $totalNetSales > 0 ? round(($netProfit / $totalNetSales) * 100, 1) : 0 }}% Net Margin)</span>
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>

                            <!-- Branch Financial Matrix -->
                            <div class="card border">
                                <div class="card-header bg-light d-flex justify-content-between align-items-center">
                                    <h5 class="fw-bold text-dark mb-0">
                                        <i class="ti-layout-grid2 text-primary me-2"></i>শাখাভিত্তিক পৃথক আর্থিক পারফরম্যান্স ম্যাট্রিক্স (Branch Performance Matrix)
                                    </h5>
                                    <span class="badge bg-primary text-white">প্রত্যেক ব্রাঞ্চের আলাদা হিসাব</span>
                                </div>
                                <div class="card-body p-0">
                                    <div class="table-responsive">
                                        <table class="table table-hover table-bordered mb-0 align-middle">
                                            <thead class="table-light">
                                                <tr>
                                                    <th>ব্রাঞ্চ কোড ও নাম</th>
                                                    <th class="text-center">লেনদেন সংখ্যা</th>
                                                    <th class="text-end">নিট বিক্রয়</th>
                                                    <th class="text-end">COGS (ক্রয়মূল্য)</th>
                                                    <th class="text-end">গ্রস প্রফিট</th>
                                                    <th class="text-end">পরিচালন খরচ</th>
                                                    <th class="text-end">নিট লাভ (Net Profit)</th>
                                                    <th class="text-center">মার্জিন (%)</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @forelse($branchReports as $bRep)
                                                    <tr>
                                                        <td>
                                                            <span class="badge bg-dark">{{ $bRep['branch']->code }}</span>
                                                            <strong class="ms-1 text-dark">{{ $bRep['branch']->name }}</strong>
                                                            <br><small class="text-muted"><i class="ti-location-pin"></i> {{ $bRep['branch']->city ?? 'বাংলাদেশ' }}</small>
                                                        </td>
                                                        <td class="text-center">
                                                            <span class="badge bg-light text-dark border">{{ number_format($bRep['transactions']) }} টি</span>
                                                        </td>
                                                        <td class="text-end fw-bold text-dark">
                                                            ৳ {{ number_format($bRep['net_sales'], 2) }}
                                                        </td>
                                                        <td class="text-end text-muted">
                                                            ৳ {{ number_format($bRep['cogs'], 2) }}
                                                        </td>
                                                        <td class="text-end fw-bold text-success">
                                                            ৳ {{ number_format($bRep['gross_profit'], 2) }}
                                                        </td>
                                                        <td class="text-end text-danger">
                                                            ৳ {{ number_format($bRep['expenses'], 2) }}
                                                        </td>
                                                        <td class="text-end fw-bold {{ $bRep['net_profit'] >= 0 ? 'text-success' : 'text-danger' }}">
                                                            ৳ {{ number_format($bRep['net_profit'], 2) }}
                                                        </td>
                                                        <td class="text-center">
                                                            <span class="badge {{ $bRep['profit_margin'] >= 10 ? 'bg-success' : ($bRep['profit_margin'] >= 0 ? 'bg-warning text-dark' : 'bg-danger') }}">
                                                                {{ $bRep['profit_margin'] }}%
                                                            </span>
                                                        </td>
                                                    </tr>
                                                @empty
                                                    <tr>
                                                        <td colspan="8" class="text-center py-4 text-muted">কোনো ব্রাঞ্চ পারফরম্যান্স তথ্য পাওয়া যায়নি।</td>
                                                    </tr>
                                                @endforelse
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- TAB 2: Top Selling Products -->
                        <div class="tab-pane fade p-3" id="products" role="tabpanel">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <div>
                                    <h5 class="fw-bold text-dark mb-0">
                                        <i class="ti-crown text-warning me-2"></i>শীর্ষ বিক্রিত পণ্যের হিসাব ও মার্জিন (Top 25 Selling Products)
                                    </h5>
                                    <p class="text-muted small mb-0">কোন পণ্যটি কত পিস বিক্রি হয়েছে এবং মোট কত টাকার রাজস্ব ও মুনাফা এসেছে</p>
                                </div>
                            </div>
                            <div class="table-responsive">
                                <table class="table table-hover table-bordered mb-0 align-middle">
                                    <thead class="table-light">
                                        <tr>
                                            <th>#</th>
                                            <th>পণ্যের নাম ও বিবরণ</th>
                                            <th>SKU / বারকোড</th>
                                            <th class="text-center">মোট বিক্রিত পরিমাণ (Qty)</th>
                                            <th class="text-end">গড় বিক্রয়মূল্য</th>
                                            <th class="text-end">মোট বিক্রয় রাজস্ব (Revenue)</th>
                                            <th class="text-end">আনুমানিক নিট লাভ</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($topSellingProducts as $idx => $prod)
                                            <tr>
                                                <td class="text-center text-muted">{{ $idx + 1 }}</td>
                                                <td>
                                                    <span class="fw-bold text-dark">{{ $prod->product_name }}</span>
                                                    @if($prod->product_id)
                                                        <br><small class="text-muted">ID: #{{ $prod->product_id }}</small>
                                                    @endif
                                                </td>
                                                <td>
                                                    <code>{{ $prod->sku ?: 'N/A' }}</code>
                                                </td>
                                                <td class="text-center">
                                                    <span class="badge bg-primary fs-6">{{ number_format($prod->total_qty, 0) }} ইউনিট</span>
                                                </td>
                                                <td class="text-end text-muted">
                                                    ৳ {{ $prod->total_qty > 0 ? number_format($prod->total_revenue / $prod->total_qty, 2) : '0.00' }}
                                                </td>
                                                <td class="text-end fw-bold text-success fs-6">
                                                    ৳ {{ number_format($prod->total_revenue, 2) }}
                                                </td>
                                                <td class="text-end fw-bold text-primary">
                                                    ৳ {{ number_format($prod->est_profit, 2) }}
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="7" class="text-center py-5 text-muted">
                                                    <i class="ti-package fs-1 d-block mb-2 text-secondary"></i>
                                                    নির্বাচিত সময়সীমায় কোনো বিক্রিত পণ্যের ডেটা পাওয়া যায়নি।
                                                </td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <!-- TAB 3: Monthly Sales Breakdown -->
                        <div class="tab-pane fade p-3" id="monthly" role="tabpanel">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <div>
                                    <h5 class="fw-bold text-dark mb-0">
                                        <i class="ti-calendar text-info me-2"></i>মাসভিত্তিক মোট বিক্রয় হিসাব (Monthly Sales Trend - Last 12 Months)
                                    </h5>
                                    <p class="text-muted small mb-0">প্রতি মাসে কতটি অর্ডার হয়েছে এবং কত টাকার মোট বিক্রয় এসেছে</p>
                                </div>
                            </div>
                            <div class="table-responsive">
                                <table class="table table-hover table-bordered mb-0 align-middle">
                                    <thead class="table-light">
                                        <tr>
                                            <th>বছর ও মাস (Year-Month)</th>
                                            <th class="text-center">মোট সম্পন্ন অর্ডার</th>
                                            <th class="text-end">মোট বিক্রয় (৳)</th>
                                            <th class="text-end">মোট ডিসকাউন্ট (৳)</th>
                                            <th class="text-end">গড় অর্ডার মূল্য (AOV)</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($monthlySalesTrend as $mTrend)
                                            <tr>
                                                <td class="fw-bold text-dark">
                                                    <i class="ti-calendar text-primary me-2"></i>{{ \Carbon\Carbon::parse($mTrend->ym . '-01')->format('F Y') }}
                                                    <span class="badge bg-light text-muted ms-2">{{ $mTrend->ym }}</span>
                                                </td>
                                                <td class="text-center">
                                                    <span class="badge bg-primary-light text-primary fs-6">{{ number_format($mTrend->total_orders) }} টি</span>
                                                </td>
                                                <td class="text-end fw-bold text-success fs-6">
                                                    ৳ {{ number_format($mTrend->total_sales, 2) }}
                                                </td>
                                                <td class="text-end text-danger">
                                                    - ৳ {{ number_format($mTrend->total_discounts, 2) }}
                                                </td>
                                                <td class="text-end text-muted fw-bold">
                                                    ৳ {{ $mTrend->total_orders > 0 ? number_format($mTrend->total_sales / $mTrend->total_orders, 2) : '0.00' }}
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="5" class="text-center py-5 text-muted">কোনো মাসিক বিক্রয় হিস্টোরি পাওয়া যায়নি।</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <!-- TAB 4: Daily Sales Breakdown -->
                        <div class="tab-pane fade p-3" id="daily" role="tabpanel">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <div>
                                    <h5 class="fw-bold text-dark mb-0">
                                        <i class="ti-time text-warning me-2"></i>তারিখভিত্তিক দৈনিক বিক্রয় রিপোর্ট (Daily Sales Breakdown)
                                    </h5>
                                    <p class="text-muted small mb-0">ফিল্টারকৃত সময়কালের মধ্যে প্রতিদিনের বিক্রয় ও অর্ডার হিসাব</p>
                                </div>
                            </div>
                            <div class="table-responsive">
                                <table class="table table-hover table-bordered mb-0 align-middle">
                                    <thead class="table-light">
                                        <tr>
                                            <th>তারিখ (Date)</th>
                                            <th>বার (Day of Week)</th>
                                            <th class="text-center">দৈনিক সম্পন্ন অর্ডার সংখ্যা</th>
                                            <th class="text-end">দৈনিক মোট বিক্রয় (৳)</th>
                                            <th class="text-end">গড় অর্ডার মূল্য</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($dailySalesTrend as $dTrend)
                                            @php $dObj = \Carbon\Carbon::parse($dTrend->sale_date); @endphp
                                            <tr>
                                                <td class="fw-bold text-dark">
                                                    <i class="ti-alarm-clock text-info me-2"></i>{{ $dObj->format('d M, Y') }}
                                                </td>
                                                <td>
                                                    <span class="badge {{ $dObj->isFriday() ? 'bg-danger' : 'bg-light text-dark border' }}">
                                                        {{ $dObj->format('l') }}
                                                    </span>
                                                </td>
                                                <td class="text-center">
                                                    <span class="badge bg-info-light text-info fs-6">{{ number_format($dTrend->total_orders) }} টি</span>
                                                </td>
                                                <td class="text-end fw-bold text-success fs-6">
                                                    ৳ {{ number_format($dTrend->total_sales, 2) }}
                                                </td>
                                                <td class="text-end text-muted">
                                                    ৳ {{ $dTrend->total_orders > 0 ? number_format($dTrend->total_sales / $dTrend->total_orders, 2) : '0.00' }}
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="5" class="text-center py-5 text-muted">নির্বাচিত তারিখে কোনো বিক্রয় নেই।</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <!-- TAB 5: Expenses & Utilities Breakdown -->
                        <div class="tab-pane fade p-3" id="expenses" role="tabpanel">
                            <div class="row g-3">
                                <div class="col-lg-7 col-12">
                                    <div class="card border mb-0 h-100">
                                        <div class="card-header bg-light d-flex justify-content-between align-items-center">
                                            <h5 class="fw-bold text-dark mb-0">
                                                <i class="ti-bolt text-warning me-2"></i>ক্যাটাগরিভিত্তিক পরিচালন ব্যয় (Electricity, Rent, Staff Wages)
                                            </h5>
                                            <a href="{{ route('admin.supershop.expenses') }}" class="btn btn-xs btn-outline-danger">
                                                <i class="ti-plus me-1"></i> খরচ যুক্ত করুন
                                            </a>
                                        </div>
                                        <div class="card-body p-0">
                                            <div class="table-responsive">
                                                <table class="table table-hover table-bordered mb-0 align-middle">
                                                    <thead class="table-light">
                                                        <tr>
                                                            <th>ব্যয়ের ক্যাটাগরি</th>
                                                            <th class="text-center">ভাউচার / এন্ট্রি সংখ্যা</th>
                                                            <th class="text-end">মোট খরচ (৳)</th>
                                                            <th class="text-end">অনুপাত (%)</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @forelse($expensesByCategory as $exp)
                                                            <tr>
                                                                <td class="fw-bold text-dark">
                                                                    <i class="ti-receipt text-danger me-2"></i>{{ $exp->category_name }}
                                                                </td>
                                                                <td class="text-center">
                                                                    <span class="badge bg-light text-dark border">{{ $exp->entry_count }} টি</span>
                                                                </td>
                                                                <td class="text-end fw-bold text-danger fs-6">
                                                                    ৳ {{ number_format($exp->total_amount, 2) }}
                                                                </td>
                                                                <td class="text-end text-muted">
                                                                    {{ $totalExpenses > 0 ? round(($exp->total_amount / $totalExpenses) * 100, 1) : 0 }}%
                                                                </td>
                                                            </tr>
                                                        @empty
                                                            <tr>
                                                                <td colspan="4" class="text-center py-4 text-muted">কোনো পরিচালন ব্যয়ের তথ্য নেই।</td>
                                                            </tr>
                                                        @endforelse
                                                        @if($totalExpenses > 0)
                                                            <tr class="table-danger">
                                                                <td colspan="2" class="fw-bold text-dark">সর্বমোট অপারেটিং ও পরিচালন খরচ:</td>
                                                                <td class="text-end fw-bold fs-5 text-danger">৳ {{ number_format($totalExpenses, 2) }}</td>
                                                                <td class="text-end fw-bold">100%</td>
                                                            </tr>
                                                        @endif
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-lg-5 col-12">
                                    <div class="card border mb-0 h-100">
                                        <div class="card-header bg-light">
                                            <h5 class="fw-bold text-dark mb-0">
                                                <i class="ti-credit-card text-success me-2"></i>পেমেন্ট মেথড সংগ্রহ (Cash vs Digital)
                                            </h5>
                                        </div>
                                        <div class="card-body p-0">
                                            <div class="table-responsive">
                                                <table class="table table-hover table-bordered mb-0 align-middle">
                                                    <thead class="table-light">
                                                        <tr>
                                                            <th>পেমেন্ট মেথড</th>
                                                            <th class="text-center">ট্রানজাকশন</th>
                                                            <th class="text-end">মোট সংগৃহীত টাকা</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @forelse($paymentMethodBreakdown as $pm)
                                                            <tr>
                                                                <td>
                                                                    <span class="badge {{ $pm->payment_method == 'cash' ? 'bg-success' : 'bg-primary' }} text-uppercase">
                                                                        {{ $pm->payment_method }}
                                                                    </span>
                                                                </td>
                                                                <td class="text-center">
                                                                    <span class="badge bg-light text-dark border">{{ $pm->count }}</span>
                                                                </td>
                                                                <td class="text-end fw-bold text-dark">
                                                                    ৳ {{ number_format($pm->total, 2) }}
                                                                </td>
                                                            </tr>
                                                        @empty
                                                            <tr>
                                                                <td colspan="3" class="text-center py-4 text-muted">কোনো পেমেন্ট তথ্য নেই।</td>
                                                            </tr>
                                                        @endforelse
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </section>
    </div>
</div>
@endsection
