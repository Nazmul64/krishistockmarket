@extends('layouts.backend.app')

@section('content')
<div class="content-wrapper">
    <div class="container-full">
        <!-- Content Header with Quick Branch Switcher -->
        <div class="content-header pb-2">
            <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
                <div>
                    <div class="d-flex align-items-center gap-2 mb-1">
                        <a href="{{ route('admin.supershop.branches') }}" class="btn btn-sm btn-outline-secondary rounded-circle py-1 px-2" title="সকল ব্রাঞ্চে ফিরুন">
                            <i class="ti-arrow-left"></i>
                        </a>
                        <h3 class="page-title text-dark fw-bold mb-0">
                            {{ $branch->name }}
                        </h3>
                        <span class="badge bg-dark fw-bold px-2 py-1">{{ $branch->code }}</span>
                        @if($branch->status == 'active')
                            <span class="badge bg-success-light text-success rounded-pill px-2 py-1"><i class="fa fa-circle text-success me-1 small"></i>সক্রিয়</span>
                        @else
                            <span class="badge bg-secondary rounded-pill px-2 py-1">নিষ্ক্রিয়</span>
                        @endif
                    </div>
                    <p class="text-muted mb-0 small">
                        <i class="ti-location-pin text-danger me-1"></i>{{ $branch->address ?: 'ঠিকানা নেই' }} |
                        <i class="ti-user text-primary me-1"></i>ম্যানেজার: <strong class="text-dark">{{ $branch->manager ? $branch->manager->name : 'অ্যাসাইন করা নেই' }}</strong> |
                        <i class="ti-mobile text-success me-1"></i>{{ $branch->phone ?: 'মোবাইল নেই' }} |
                        <i class="ti-time text-info me-1"></i>সময়: {{ $branch->opening_time ?: '08:00' }} - {{ $branch->closing_time ?: '22:30' }}
                    </p>
                </div>

                <!-- Action & Branch Switcher Dropdown -->
                <div class="d-flex align-items-center gap-2 flex-wrap">
                    <div class="dropdown">
                        <button class="btn btn-sm btn-outline-dark dropdown-toggle fw-bold" type="button" data-bs-toggle="dropdown">
                            <i class="ti-exchange-vertical me-1"></i> ব্রাঞ্চ পরিবর্তন করুন
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end shadow border-0 rounded-3" style="max-height: 280px; overflow-y: auto;">
                            @foreach($allOtherBranches as $ob)
                                <li>
                                    <a class="dropdown-item py-2" href="{{ route('admin.supershop.branches.show', $ob->id) }}">
                                        <span class="badge bg-dark me-1">{{ $ob->code }}</span> {{ $ob->name }}
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                    </div>

                    <a href="{{ route('admin.supershop.pos', ['branch_id' => $branch->id]) }}" class="btn btn-sm btn-success fw-bold shadow-sm">
                        <i class="ti-shopping-cart me-1"></i> POS কাউন্টার টার্মিনাল
                    </a>

                    <a href="{{ route('admin.supershop.branches.comparison') }}" class="btn btn-sm btn-outline-primary fw-bold">
                        <i class="ti-stats-up me-1"></i> পারফর্ম্যান্স তুলনা
                    </a>
                </div>
            </div>
        </div>

        <!-- Main Content -->
        <section class="content">
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show rounded-3 shadow-sm" role="alert">
                    <i class="fa fa-check-circle me-1"></i> {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif
            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show rounded-3 shadow-sm" role="alert">
                    <i class="fa fa-exclamation-triangle me-1"></i> {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <!-- Section 3: Branch Top KPI Highlights -->
            <div class="row g-3 mb-3">
                <div class="col-xl-2 col-md-4 col-6">
                    <div class="card border-0 shadow-sm rounded-4 h-100 p-3 bg-white">
                        <span class="text-muted smaller fw-semibold">আজকের বিক্রয়</span>
                        <div class="fs-5 fw-bold text-success mt-1">৳ {{ number_format($todaySales, 2) }}</div>
                        <span class="smallest text-muted">আজকের নিট লেনদেন</span>
                    </div>
                </div>

                <div class="col-xl-2 col-md-4 col-6">
                    <div class="card border-0 shadow-sm rounded-4 h-100 p-3 bg-white">
                        <span class="text-muted smaller fw-semibold">আজকের খরচ</span>
                        <div class="fs-5 fw-bold text-danger mt-1">৳ {{ number_format($todayExpenses, 2) }}</div>
                        <span class="smallest text-muted">আউটলেট পরিচালন ব্যয়</span>
                    </div>
                </div>

                <div class="col-xl-2 col-md-4 col-6">
                    <div class="card border-0 shadow-sm rounded-4 h-100 p-3 bg-white">
                        <span class="text-muted smaller fw-semibold">মাসিক মোট বিক্রয়</span>
                        <div class="fs-5 fw-bold text-primary mt-1">৳ {{ number_format($monthlySales, 2) }}</div>
                        <span class="smallest text-muted">চলতি মাসের টার্নওভার</span>
                    </div>
                </div>

                <div class="col-xl-2 col-md-4 col-6">
                    <div class="card border-0 shadow-sm rounded-4 h-100 p-3 bg-white">
                        <span class="text-muted smaller fw-semibold">মাসিক মোট খরচ</span>
                        <div class="fs-5 fw-bold text-dark mt-1">৳ {{ number_format($monthlyExpenses, 2) }}</div>
                        <span class="smallest text-muted">ভাড়া, বিদ্যুৎ, স্টাফ খাবার</span>
                    </div>
                </div>

                <div class="col-xl-2 col-md-4 col-6">
                    <div class="card border-0 shadow-sm rounded-4 h-100 p-3 bg-white">
                        <span class="text-muted smaller fw-semibold">ইনভেন্টরি ও পণ্য</span>
                        <div class="fs-5 fw-bold text-dark mt-1">{{ number_format($totalStockUnits) }} <span class="smaller fw-normal">ইউনিট</span></div>
                        <span class="smallest text-muted">{{ $totalProductsCount }} টি পণ্য ম্যাপড</span>
                    </div>
                </div>

                <div class="col-xl-2 col-md-4 col-6">
                    <div class="card border-0 shadow-sm rounded-4 h-100 p-3 bg-white">
                        <span class="text-muted smaller fw-semibold">লো-স্টক অ্যালার্ট</span>
                        <div class="fs-5 fw-bold text-{{ $lowStockCount > 0 ? 'warning' : 'success' }} mt-1">
                            {{ $lowStockCount }} <span class="smaller fw-normal">টি পণ্য</span>
                        </div>
                        <span class="smallest text-muted">স্টক রিঅর্ডার প্রয়োজন</span>
                    </div>
                </div>
            </div>

            <!-- Global Date Filter System (Section 23 & Screenshot 4) -->
            <div class="card border-0 shadow-sm rounded-4 mb-4 bg-white">
                <div class="card-body p-3">
                    <form method="GET" action="{{ route('admin.supershop.branches.show', $branch->id) }}" class="row g-2 align-items-center">
                        <input type="hidden" name="tab" value="{{ $activeTab }}">
                        
                        <div class="col-md-3 col-12 d-flex align-items-center gap-2">
                            <span class="fw-bold small text-muted text-nowrap"><i class="ti-calendar text-primary me-1"></i>সময়কাল:</span>
                            <select name="date_filter" id="globalDateFilterSelect" class="form-select form-select-sm">
                                <option value="today" {{ $dateFilter == 'today' ? 'selected' : '' }}>আজ (Today)</option>
                                <option value="yesterday" {{ $dateFilter == 'yesterday' ? 'selected' : '' }}>গতকাল (Yesterday)</option>
                                <option value="last_7_days" {{ $dateFilter == 'last_7_days' ? 'selected' : '' }}>গত ৭ দিন (Last 7 Days)</option>
                                <option value="last_30_days" {{ $dateFilter == 'last_30_days' ? 'selected' : '' }}>গত ৩০ দিন (Last 30 Days)</option>
                                <option value="this_week" {{ $dateFilter == 'this_week' ? 'selected' : '' }}>চলতি সপ্তাহ (This Week)</option>
                                <option value="last_week" {{ $dateFilter == 'last_week' ? 'selected' : '' }}>গত সপ্তাহ (Last Week)</option>
                                <option value="this_month" {{ $dateFilter == 'this_month' ? 'selected' : '' }}>চলতি মাস (This Month)</option>
                                <option value="last_month" {{ $dateFilter == 'last_month' ? 'selected' : '' }}>গত মাস (Last Month)</option>
                                <option value="this_year" {{ $dateFilter == 'this_year' ? 'selected' : '' }}>এই বছর (This Year)</option>
                                <option value="custom" {{ $dateFilter == 'custom' ? 'selected' : '' }}>কাস্টম রেঞ্জ (Custom Range)</option>
                            </select>
                        </div>

                        <div class="col-md-5 col-12 d-flex align-items-center gap-2 {{ $dateFilter != 'custom' ? 'd-none' : '' }}" id="customDateFields">
                            <input type="date" name="from_date" class="form-control form-control-sm" value="{{ request('from_date', $startDate->toDateString()) }}">
                            <span class="text-muted small">থেকে</span>
                            <input type="date" name="to_date" class="form-control form-control-sm" value="{{ request('to_date', $endDate->toDateString()) }}">
                        </div>

                        <div class="col-md-4 col-12 d-flex gap-2 justify-content-md-end">
                            <button type="submit" class="btn btn-sm btn-success fw-bold px-3">
                                <i class="ti-reload me-1"></i> রিপোর্ট দেখুন (Generate)
                            </button>
                            <a href="{{ route('admin.supershop.branches.show', ['id' => $branch->id, 'tab' => $activeTab]) }}" class="btn btn-sm btn-light border px-3">
                                রিসেট
                            </a>
                            <button type="button" class="btn btn-sm btn-outline-secondary" onclick="window.print()">
                                <i class="ti-printer me-1"></i> প্রিন্ট
                            </button>
                        </div>
                    </form>
                    <div class="small text-muted mt-2 ps-1">
                        <i class="ti-info-alt text-primary me-1"></i>ফিল্টার সময়কাল: <strong>{{ $startDate->format('d M, Y') }}</strong> থেকে <strong>{{ $endDate->format('d M, Y') }}</strong> (ড্যাশবোর্ডের সেলস, খরচ, স্টক এবং লাভ-লোকসান এই তারিখ অনুযায়ী প্রদর্শিত হচ্ছে)
                    </div>
                </div>
            </div>

            <!-- Branch Details Navigation Tabs (Section 3) -->
            <ul class="nav nav-pills nav-fill bg-white p-2 rounded-4 shadow-sm mb-4 border flex-nowrap overflow-auto" id="branchDetailsTab" role="tablist" style="white-space: nowrap;">
                <li class="nav-item">
                    <a class="nav-link {{ $activeTab == 'overview' ? 'active' : '' }} fw-bold py-2 px-3" data-bs-toggle="pill" href="#tab-overview">
                        <i class="ti-dashboard me-1"></i> ওভারভিউ
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ $activeTab == 'sales' ? 'active' : '' }} fw-bold py-2 px-3" data-bs-toggle="pill" href="#tab-sales">
                        <i class="ti-shopping-cart me-1"></i> ব্রাঞ্চ সেলস
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ $activeTab == 'orders' ? 'active' : '' }} fw-bold py-2 px-3" data-bs-toggle="pill" href="#tab-orders">
                        <i class="ti-truck me-1"></i> ই-কমার্স অর্ডার্স
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ $activeTab == 'products' ? 'active' : '' }} fw-bold py-2 px-3" data-bs-toggle="pill" href="#tab-products">
                        <i class="ti-package me-1"></i> প্রোডাক্ট ও স্টক
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ $activeTab == 'movements' ? 'active' : '' }} fw-bold py-2 px-3" data-bs-toggle="pill" href="#tab-movements">
                        <i class="ti-exchange-vertical me-1"></i> স্টক মুভমেন্ট
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ $activeTab == 'transfers' ? 'active' : '' }} fw-bold py-2 px-3" data-bs-toggle="pill" href="#tab-transfers">
                        <i class="ti-share-alt me-1"></i> স্টক ট্রান্সফার
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ $activeTab == 'expenses' ? 'active' : '' }} fw-bold py-2 px-3" data-bs-toggle="pill" href="#tab-expenses">
                        <i class="ti-money me-1"></i> খরচ ব্যবস্থাপনা
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ $activeTab == 'cash' ? 'active' : '' }} fw-bold py-2 px-3" data-bs-toggle="pill" href="#tab-cash">
                        <i class="ti-wallet me-1"></i> ক্যাশ ও ট্রানজেকশন
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ $activeTab == 'staff' ? 'active' : '' }} fw-bold py-2 px-3" data-bs-toggle="pill" href="#tab-staff">
                        <i class="ti-user me-1"></i> স্টাফ ডিরেক্টরি
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ $activeTab == 'payroll' ? 'active' : '' }} fw-bold py-2 px-3" data-bs-toggle="pill" href="#tab-payroll">
                        <i class="ti-credit-card me-1"></i> বেতন ও পেরোল
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ $activeTab == 'attendance' ? 'active' : '' }} fw-bold py-2 px-3" data-bs-toggle="pill" href="#tab-attendance">
                        <i class="ti-check-box me-1"></i> উপস্থিতি ও ছুটি
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ $activeTab == 'profit_loss' ? 'active' : '' }} fw-bold py-2 px-3" data-bs-toggle="pill" href="#tab-profit-loss">
                        <i class="ti-pie-chart me-1"></i> লাভ-লোকসান (P&L)
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ $activeTab == 'activity' ? 'active' : '' }} fw-bold py-2 px-3" data-bs-toggle="pill" href="#tab-activity">
                        <i class="ti-notepad me-1"></i> অ্যাক্টিভিটি লগ
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ $activeTab == 'settings' ? 'active' : '' }} fw-bold py-2 px-3" data-bs-toggle="pill" href="#tab-settings">
                        <i class="ti-settings me-1"></i> সেটিংস
                    </a>
                </li>
            </ul>

            <!-- Tab Contents -->
            <div class="tab-content" id="branchDetailsTabContent">

                <!-- ========================================== -->
                <!-- 1. OVERVIEW & ANALYTICS TAB                -->
                <!-- ========================================== -->
                <div class="tab-pane fade {{ $activeTab == 'overview' ? 'show active' : '' }}" id="tab-overview" role="tabpanel">
                    <!-- Top KPI Badges (Section 15 & Screenshot 4 Style with Bangladeshi Taka ৳) -->
                    <div class="row g-3 mb-4">
                        <div class="col-xl-2 col-md-4 col-6">
                            <div class="card border-0 shadow-sm rounded-4 p-3 bg-white text-center">
                                <span class="smaller text-muted fw-bold">গ্রস রেভিনিউ (Gross Revenue)</span>
                                <div class="fs-4 fw-bold text-success mt-1">৳ {{ number_format($filteredSalesTotal, 2) }}</div>
                                <span class="smallest text-muted">{{ $filteredOrdersCount }} টি সেলস অর্ডার</span>
                            </div>
                        </div>
                        <div class="col-xl-2 col-md-4 col-6">
                            <div class="card border-0 shadow-sm rounded-4 p-3 bg-white text-center">
                                <span class="smaller text-muted fw-bold">পণ্য ক্রয়মূল্য (COGS)</span>
                                <div class="fs-4 fw-bold text-muted mt-1">৳ {{ number_format($costOfSoldGoods, 2) }}</div>
                                <span class="smallest text-muted">বিক্রিত পণ্যের আসল কস্ট</span>
                            </div>
                        </div>
                        <div class="col-xl-2 col-md-4 col-6">
                            <div class="card border-0 shadow-sm rounded-4 p-3 bg-white text-center">
                                <span class="smaller text-muted fw-bold">গ্রস প্রফিট (Gross Profit)</span>
                                <div class="fs-4 fw-bold text-primary mt-1">৳ {{ number_format($grossProfit, 2) }}</div>
                                <span class="smallest text-muted">বিক্রয় - কস্ট মূল্য</span>
                            </div>
                        </div>
                        <div class="col-xl-2 col-md-4 col-6">
                            <div class="card border-0 shadow-sm rounded-4 p-3 bg-white text-center">
                                <span class="smaller text-muted fw-bold">মোট খরচ (Expenses)</span>
                                <div class="fs-4 fw-bold text-danger mt-1">৳ {{ number_format($filteredTotalExpenses, 2) }}</div>
                                <span class="smallest text-muted">ভাড়া ও অন্যান্য বিল</span>
                            </div>
                        </div>
                        <div class="col-xl-2 col-md-4 col-6">
                            <div class="card border-0 shadow-sm rounded-4 p-3 bg-white text-center">
                                <span class="smaller text-muted fw-bold">প্রকৃত নিট লাভ (Net Profit)</span>
                                <div class="fs-4 fw-bold text-{{ $netProfit >= 0 ? 'success' : 'danger' }} mt-1">৳ {{ number_format($netProfit, 2) }}</div>
                                <span class="smallest text-muted">মার্জিন: {{ $profitMargin }}%</span>
                            </div>
                        </div>
                        <div class="col-xl-2 col-md-4 col-6">
                            <div class="card border-0 shadow-sm rounded-4 p-3 bg-white text-center">
                                <span class="smaller text-muted fw-bold">গড় অর্ডার ভ্যালু (AOV)</span>
                                <div class="fs-4 fw-bold text-info mt-1">৳ {{ number_format($avgOrderValue, 2) }}</div>
                                <span class="smallest text-muted">প্রতি ইনভয়েস গড়</span>
                            </div>
                        </div>
                    </div>

                    <!-- Charts Row (Revenue vs Expenses Trend & Expense by Category Donut) -->
                    <div class="row g-4 mb-4">
                        <div class="col-xl-8 col-12">
                            <div class="card border-0 shadow-sm rounded-4 p-4 bg-white h-100">
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <div>
                                        <h5 class="fw-bold text-dark mb-0">দৈনিক রাজস্ব বনাম খরচ প্রবণতা (Revenue vs Expenses Trend)</h5>
                                        <small class="text-muted">নির্বাচিত সময়কালের বিক্রয় ও খরচের সম্মিলিত তুলনা</small>
                                    </div>
                                    <span class="badge bg-success-light text-success fw-bold px-3 py-1">৳ বিডিটি মুদ্রা</span>
                                </div>
                                <div style="height: 320px; position: relative;">
                                    <canvas id="revenueExpenseTrendChart"></canvas>
                                </div>
                            </div>
                        </div>

                        <div class="col-xl-4 col-12">
                            <div class="card border-0 shadow-sm rounded-4 p-4 bg-white h-100">
                                <h5 class="fw-bold text-dark mb-1">ক্যাটাগরি অনুযায়ী ব্যয় (By Category)</h5>
                                <small class="text-muted mb-3 d-block">ব্রাঞ্চের খরচের শতকরা অনুপাত</small>
                                
                                <div style="height: 230px; position: relative;">
                                    <canvas id="expenseCategoryDonutChart"></canvas>
                                </div>

                                <div class="mt-3">
                                    <ul class="list-group list-group-flush smaller">
                                        @foreach($expenseCategoriesBreakdown as $ec)
                                            <li class="list-group-item d-flex justify-content-between align-items-center px-0 py-1 bg-transparent">
                                                <span><i class="fa fa-circle text-primary me-1" style="font-size: 8px;"></i>{{ $ec['name'] }}</span>
                                                <span class="fw-bold text-dark">৳ {{ number_format($ec['amount']) }} ({{ $ec['percentage'] }}%)</span>
                                            </li>
                                        @endforeach
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Top Selling Products & Low Stock Alerts Row -->
                    <div class="row g-4 mb-4">
                        <div class="col-xl-7 col-12">
                            <div class="card border-0 shadow-sm rounded-4 p-4 bg-white h-100">
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <h5 class="fw-bold text-dark mb-0">সর্বোচ্চ বিক্রিত পণ্য (Top Selling Products)</h5>
                                    <span class="badge bg-light text-dark border">টপ পারফর্মিং</span>
                                </div>
                                <div class="table-responsive">
                                    <table class="table table-hover align-middle mb-0">
                                        <thead class="table-light smaller">
                                            <tr>
                                                <th>প্রোডাক্টের নাম</th>
                                                <th class="text-center">বিক্রিত পরিমাণ</th>
                                                <th class="text-end">মোট রেভিনিউ</th>
                                                <th class="text-end">পণ্যের কস্ট</th>
                                                <th class="text-end">অর্জিত লাভ</th>
                                            </tr>
                                        </thead>
                                        <tbody class="small">
                                            @forelse($topProducts as $tp)
                                                @php $pProfit = $tp->total_rev - $tp->total_cost; @endphp
                                                <tr>
                                                    <td class="fw-semibold text-dark">{{ $tp->product_name }}</td>
                                                    <td class="text-center"><span class="badge bg-primary-light text-primary fw-bold">{{ $tp->total_qty }} টি</span></td>
                                                    <td class="text-end fw-bold text-success">৳ {{ number_format($tp->total_rev, 2) }}</td>
                                                    <td class="text-end text-muted">৳ {{ number_format($tp->total_cost, 2) }}</td>
                                                    <td class="text-end fw-bold text-{{ $pProfit >= 0 ? 'success' : 'danger' }}">৳ {{ number_format($pProfit, 2) }}</td>
                                                </tr>
                                            @empty
                                                <tr>
                                                    <td colspan="5" class="text-center text-muted py-3">এই সময়কালে কোনো বিক্রয় রেকর্ড পাওয়া যায়নি।</td>
                                                </tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>

                        <div class="col-xl-5 col-12">
                            <div class="card border-0 shadow-sm rounded-4 p-4 bg-white h-100">
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <h5 class="fw-bold text-dark mb-0">লো-স্টক সতর্কতা (Low Stock Alerts)</h5>
                                    <span class="badge bg-warning-light text-warning fw-bold px-2 py-1">রিঅর্ডার লেভেল</span>
                                </div>
                                <div class="table-responsive">
                                    <table class="table table-sm table-hover align-middle mb-0">
                                        <thead class="table-light smaller">
                                            <tr>
                                                <th>প্রোডাক্ট</th>
                                                <th class="text-center">স্টক</th>
                                                <th class="text-center">রিঅর্ডার লিমিট</th>
                                                <th class="text-end">অ্যাকশন</th>
                                            </tr>
                                        </thead>
                                        <tbody class="smaller">
                                            @php
                                                $lowStockList = $branchProducts->filter(function($i) use ($lowStockThreshold) {
                                                    return $i->stock_quantity <= $lowStockThreshold;
                                                })->take(5);
                                            @endphp
                                            @forelse($lowStockList as $lItem)
                                                <tr>
                                                    <td>
                                                        <strong class="text-dark">{{ Str::limit($lItem->product->name, 22) }}</strong>
                                                        <div class="smallest text-muted">SKU: {{ $lItem->product->sku ?: 'N/A' }}</div>
                                                    </td>
                                                    <td class="text-center">
                                                        <span class="badge bg-{{ $lItem->stock_quantity <= 0 ? 'danger' : 'warning' }} text-white fw-bold">
                                                            {{ $lItem->stock_quantity }} টি
                                                        </span>
                                                    </td>
                                                    <td class="text-center text-muted">{{ $lItem->reorder_level }} টি</td>
                                                    <td class="text-end">
                                                        <button type="button" class="btn btn-xs btn-outline-success" data-bs-toggle="modal" data-bs-target="#stockAdjustModal{{ $lItem->id }}">
                                                            স্টক ইন
                                                        </button>
                                                    </td>
                                                </tr>
                                            @empty
                                                <tr>
                                                    <td colspan="4" class="text-center text-success py-3"><i class="fa fa-check-circle me-1"></i>সকল প্রোডাক্টের পর্যাপ্ত স্টক রয়েছে।</td>
                                                </tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- ========================================== -->
                <!-- 2. BRANCH SALES TAB (Section 4)            -->
                <!-- ========================================== -->
                <div class="tab-pane fade {{ $activeTab == 'sales' ? 'show active' : '' }}" id="tab-sales" role="tabpanel">
                    <div class="card border-0 shadow-sm rounded-4 p-4 bg-white mb-4">
                        <!-- Sales Summary Cards -->
                        <div class="row g-3 mb-4 text-center">
                            <div class="col-md-3 col-6">
                                <div class="p-3 border rounded-3 bg-light">
                                    <div class="smaller text-muted fw-bold">মোট বিক্রয় (Total Sales)</div>
                                    <div class="fs-4 fw-bold text-success mt-1">৳ {{ number_format($filteredSalesTotal, 2) }}</div>
                                    <div class="smallest text-muted">{{ $filteredOrdersCount }} টি ইনভয়েস</div>
                                </div>
                            </div>
                            <div class="col-md-3 col-6">
                                <div class="p-3 border rounded-3 bg-light">
                                    <div class="smaller text-muted fw-bold">নগদ বিক্রয় (Cash Sales)</div>
                                    <div class="fs-4 fw-bold text-primary mt-1">৳ {{ number_format($cashSales, 2) }}</div>
                                    <div class="smallest text-muted">হাতে পাওয়া টাকা</div>
                                </div>
                            </div>
                            <div class="col-md-3 col-6">
                                <div class="p-3 border rounded-3 bg-light">
                                    <div class="smaller text-muted fw-bold">ডিজিটাল ও মোবাইল ব্যাংকিং</div>
                                    <div class="fs-4 fw-bold text-info mt-1">৳ {{ number_format($digitalSales, 2) }}</div>
                                    <div class="smallest text-muted">bKash, Nagad, Card</div>
                                </div>
                            </div>
                            <div class="col-md-3 col-6">
                                <div class="p-3 border rounded-3 bg-light">
                                    <div class="smaller text-muted fw-bold">বকেয়া বিক্রয় (Due Sales)</div>
                                    <div class="fs-4 fw-bold text-danger mt-1">৳ {{ number_format($dueSales, 2) }}</div>
                                    <div class="smallest text-muted">অনাদায়ী পাওনা</div>
                                </div>
                            </div>
                        </div>

                        <!-- Sales Table (Section 4.3) -->
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h5 class="fw-bold text-dark mb-0">বিক্রয় ইনভয়েস তালিকা (Sales Transactions)</h5>
                            <a href="{{ route('admin.supershop.pos', ['branch_id' => $branch->id]) }}" class="btn btn-sm btn-success fw-bold">
                                <i class="ti-plus me-1"></i> নতুন বিক্রয় এন্ট্রি (POS)
                            </a>
                        </div>

                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th>তারিখ ও সময়</th>
                                        <th>ইনভয়েস নম্বর</th>
                                        <th>কাস্টমার</th>
                                        <th>ক্যাশিয়ার</th>
                                        <th class="text-center">আইটেম সংখ্যা</th>
                                        <th class="text-end">মোট মূল্য</th>
                                        <th class="text-center">পেমেন্ট মেথড</th>
                                        <th class="text-center">স্ট্যাটাস</th>
                                        <th class="text-end pe-3">রসিদ</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($salesList as $sale)
                                        <tr>
                                            <td>{{ $sale->sale_time ? ($sale->sale_time instanceof \Carbon\Carbon ? $sale->sale_time->format('d M, Y h:i A') : date('d M, Y h:i A', strtotime($sale->sale_time))) : '-' }}</td>
                                            <td><span class="badge bg-dark fw-bold">{{ $sale->invoice_no }}</span></td>
                                            <td>
                                                <div class="fw-semibold text-dark">{{ $sale->customer_name ?: 'সাধারণ গ্রাহক' }}</div>
                                                <div class="smaller text-muted">{{ $sale->customer_phone ?: '' }}</div>
                                            </td>
                                            <td><span class="small text-muted">{{ $sale->cashier ? $sale->cashier->name : 'N/A' }}</span></td>
                                            <td class="text-center"><span class="badge bg-light text-dark border">{{ $sale->items->count() }} আইটেম</span></td>
                                            <td class="text-end fw-bold text-success fs-6">৳ {{ number_format($sale->grand_total, 2) }}</td>
                                            <td class="text-center">
                                                <span class="badge bg-info-light text-info fw-bold text-uppercase">{{ $sale->payment_method }}</span>
                                            </td>
                                            <td class="text-center">
                                                <span class="badge bg-{{ $sale->sale_status == 'completed' ? 'success' : 'secondary' }} rounded-pill px-2 py-1">
                                                    {{ $sale->sale_status == 'completed' ? 'সম্পন্ন' : $sale->sale_status }}
                                                </span>
                                            </td>
                                            <td class="text-end pe-3">
                                                <button type="button" class="btn btn-xs btn-outline-primary" data-bs-toggle="modal" data-bs-target="#saleDetailModal{{ $sale->id }}">
                                                    <i class="ti-receipt me-1"></i> ইনভয়েস
                                                </button>
                                            </td>
                                        </tr>

                                        <!-- Sale Items Detail Modal -->
                                        <div class="modal fade" id="saleDetailModal{{ $sale->id }}" tabindex="-1" aria-hidden="true">
                                            <div class="modal-dialog modal-lg">
                                                <div class="modal-content rounded-4 border-0 shadow">
                                                    <div class="modal-header bg-dark text-white rounded-top-4 py-3">
                                                        <h5 class="modal-title fw-bold text-white">ইনভয়েস #{{ $sale->invoice_no }} এর আইটেম বিবরণ</h5>
                                                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                                                    </div>
                                                    <div class="modal-body p-4">
                                                        <div class="d-flex justify-content-between mb-3 border-bottom pb-2">
                                                            <div>
                                                                <strong>ব্রাঞ্চ:</strong> {{ $branch->name }}<br>
                                                                <strong>তারিখ:</strong> {{ $sale->sale_time ? ($sale->sale_time instanceof \Carbon\Carbon ? $sale->sale_time->format('d M, Y h:i A') : date('d M, Y h:i A', strtotime($sale->sale_time))) : '-' }}
                                                            </div>
                                                            <div class="text-end">
                                                                <strong>কাস্টমার:</strong> {{ $sale->customer_name ?: 'সাধারণ গ্রাহক' }} ({{ $sale->customer_phone }})<br>
                                                                <strong>পেমেন্ট:</strong> {{ strtoupper($sale->payment_method) }} ({{ $sale->payment_status }})
                                                            </div>
                                                        </div>
                                                        <table class="table table-bordered align-middle">
                                                            <thead class="table-light smaller">
                                                                <tr>
                                                                    <th>প্রোডাক্টের নাম</th>
                                                                    <th class="text-center">পরিমাণ</th>
                                                                    <th class="text-end">একক মূল্য</th>
                                                                    <th class="text-end">মোট মূল্য</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                @foreach($sale->items as $it)
                                                                    <tr>
                                                                        <td>{{ $it->product_name }}</td>
                                                                        <td class="text-center">{{ $it->quantity }}</td>
                                                                        <td class="text-end">৳ {{ number_format($it->unit_price, 2) }}</td>
                                                                        <td class="text-end fw-bold">৳ {{ number_format($it->total_price, 2) }}</td>
                                                                    </tr>
                                                                @endforeach
                                                            </tbody>
                                                            <tfoot>
                                                                <tr class="fw-bold bg-light">
                                                                    <td colspan="3" class="text-end">সর্বমোট পরিশোধযোগ্য:</td>
                                                                    <td class="text-end text-success">৳ {{ number_format($sale->grand_total, 2) }}</td>
                                                                </tr>
                                                            </tfoot>
                                                        </table>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @empty
                                        <tr>
                                            <td colspan="9" class="text-center text-muted py-4">কোনো বিক্রয় রেকর্ড খুঁজে পাওয়া যায়নি।</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                        <div class="mt-3">
                            {{ $salesList->appends(['tab' => 'sales', 'date_filter' => $dateFilter, 'from_date' => request('from_date'), 'to_date' => request('to_date')])->links() }}
                        </div>
                    </div>
                </div>

                <!-- ========================================== -->
                <!-- 3. E-COMMERCE & BRANCH ORDERS (Section 13) -->
                <!-- ========================================== -->
                <div class="tab-pane fade {{ $activeTab == 'orders' ? 'show active' : '' }}" id="tab-orders" role="tabpanel">
                    <div class="card border-0 shadow-sm rounded-4 p-4 bg-white mb-4">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <div>
                                <h5 class="fw-bold text-dark mb-0">ই-কমার্স অর্ডার ফুলফিলমেন্ট (Assigned Branch Orders)</h5>
                                <small class="text-muted">কেন্দ্রীয় অনলাইন শপ থেকে এই ব্রাঞ্চে বরাদ্দ করা ডেলিভারি অর্ডারসমূহ</small>
                            </div>
                        </div>

                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th>অর্ডার নম্বর</th>
                                        <th>গ্রাহকের নাম ও ফোন</th>
                                        <th>ডেলিভারি ঠিকানা</th>
                                        <th class="text-center">আইটেম</th>
                                        <th class="text-end">অর্ডার মূল্য</th>
                                        <th class="text-center">পেমেন্ট</th>
                                        <th class="text-center">ফুলফিলমেন্ট স্ট্যাটাস</th>
                                        <th class="text-end pe-3">অ্যাকশন</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($shopOrders as $so)
                                        <tr>
                                            <td><span class="badge bg-primary fw-bold">{{ $so->order_number }}</span></td>
                                            <td>
                                                <div class="fw-semibold text-dark">{{ $so->first_name }} {{ $so->last_name }}</div>
                                                <div class="smaller text-success"><i class="ti-mobile me-1"></i>{{ $so->phone }}</div>
                                            </td>
                                            <td class="small text-muted">{{ Str::limit($so->address, 32) }}</td>
                                            <td class="text-center"><span class="badge bg-light text-dark border">{{ $so->items->count() }} টি পণ্য</span></td>
                                            <td class="text-end fw-bold text-success fs-6">৳ {{ number_format($so->total_amount, 2) }}</td>
                                            <td class="text-center">
                                                <span class="badge bg-info-light text-info text-uppercase">{{ $so->payment_method }}</span>
                                            </td>
                                            <td class="text-center">
                                                <span class="badge bg-warning-light text-dark rounded-pill px-3 py-1 fw-bold">
                                                    {{ $so->branch_fulfillment_status ?: 'Assigned' }}
                                                </span>
                                            </td>
                                            <td class="text-end pe-3">
                                                <button type="button" class="btn btn-xs btn-outline-success">প্রস্তুত করুন</button>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="8" class="text-center text-muted py-4">বর্তমানে এই ব্রাঞ্চে বরাদ্দকৃত কোনো অনলাইন অর্ডার নেই।</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- ========================================== -->
                <!-- 4. PRODUCTS & INVENTORY TAB (Section 5 & 6)-->
                <!-- ========================================== -->
                <div class="tab-pane fade {{ $activeTab == 'products' ? 'show active' : '' }}" id="tab-products" role="tabpanel">
                    <div class="card border-0 shadow-sm rounded-4 p-4 bg-white mb-4">
                        <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-2">
                            <div>
                                <h5 class="fw-bold text-dark mb-0">ব্রাঞ্চ প্রোডাক্ট ও ইনভেন্টরি তালিকা (Branch Products & Stock)</h5>
                                <small class="text-muted">মূল সেন্ট্রাল ক্যাটালগের সাথে সমন্বিত ব্রাঞ্চের স্বকীয় স্টক ও মূল্য ব্যবস্থাপনা</small>
                            </div>
                            <div class="d-flex gap-2">
                                <span class="badge bg-success-light text-success fw-bold px-3 py-2">
                                    <i class="ti-check me-1"></i> সেন্ট্রাল ক্যাটালগ কানেক্টেড (ডুপ্লিকেট প্রোডাক্ট নেই)
                                </span>
                            </div>
                        </div>

                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th>পণ্য ও SKU</th>
                                        <th>ক্যাটাগরি</th>
                                        <th class="text-end">ক্রয়মূল্য (Cost)</th>
                                        <th class="text-end">বিক্রয়মূল্য (Price)</th>
                                        <th class="text-center">বর্তমান স্টক</th>
                                        <th class="text-center">রিঅর্ডার লেভেল</th>
                                        <th class="text-center">স্টক স্ট্যাটাস</th>
                                        <th class="text-end pe-3">অ্যাকশন</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($branchProducts as $inv)
                                        <tr>
                                            <td>
                                                <div class="d-flex align-items-center gap-2">
                                                    <div class="rounded border p-1 bg-light" style="width: 40px; height: 40px; min-width: 40px;">
                                                        <i class="ti-package text-secondary fs-4 d-flex align-items-center justify-content-center h-100"></i>
                                                    </div>
                                                    <div>
                                                        <strong class="text-dark">{{ $inv->product ? $inv->product->name : 'N/A' }}</strong>
                                                        <div class="smaller text-muted">SKU: {{ $inv->product->sku ?: 'N/A' }} | বারকোড: {{ $inv->product->barcode ?: 'N/A' }}</div>
                                                    </div>
                                                </div>
                                            </td>
                                            <td><span class="badge bg-light text-dark border">{{ $inv->product->category ? $inv->product->category->name : 'সুপারশপ' }}</span></td>
                                            <td class="text-end text-muted">৳ {{ number_format($inv->purchase_price ?: ($inv->product->price * 0.75), 2) }}</td>
                                            <td class="text-end fw-bold text-success">৳ {{ number_format($inv->selling_price ?: $inv->product->price, 2) }}</td>
                                            <td class="text-center">
                                                <span class="fs-6 fw-bold text-{{ $inv->stock_quantity <= 0 ? 'danger' : ($inv->stock_quantity <= $inv->reorder_level ? 'warning' : 'dark') }}">
                                                    {{ $inv->stock_quantity }}
                                                </span> <span class="smaller text-muted">টি</span>
                                            </td>
                                            <td class="text-center text-muted">{{ $inv->reorder_level }} টি</td>
                                            <td class="text-center">
                                                @if($inv->stock_quantity <= 0)
                                                    <span class="badge bg-danger rounded-pill px-2 py-1">Out of Stock</span>
                                                @elseif($inv->stock_quantity <= $inv->reorder_level)
                                                    <span class="badge bg-warning rounded-pill px-2 py-1 text-dark">Low Stock</span>
                                                @else
                                                    <span class="badge bg-success rounded-pill px-2 py-1">In Stock</span>
                                                @endif
                                            </td>
                                            <td class="text-end pe-3">
                                                <button type="button" class="btn btn-xs btn-outline-primary" data-bs-toggle="modal" data-bs-target="#stockAdjustModal{{ $inv->id }}">
                                                    <i class="ti-pencil me-1"></i> স্টক সমন্বয়
                                                </button>
                                            </td>
                                        </tr>

                                        <!-- Stock Adjust Modal -->
                                        <div class="modal fade" id="stockAdjustModal{{ $inv->id }}" tabindex="-1" aria-hidden="true">
                                            <div class="modal-dialog">
                                                <div class="modal-content rounded-4 border-0 shadow">
                                                    <div class="modal-header bg-primary text-white rounded-top-4 py-3">
                                                        <h5 class="modal-title fw-bold text-white">স্টক সমন্বয় – {{ $inv->product->name }}</h5>
                                                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                                                    </div>
                                                    <form action="{{ route('admin.supershop.branches.stock_adjust', $branch->id) }}" method="POST">
                                                        @csrf
                                                        <input type="hidden" name="inventory_id" value="{{ $inv->id }}">
                                                        <div class="modal-body p-4">
                                                            <div class="mb-3">
                                                                <label class="form-label small fw-bold">সমন্বয়ের ধরন (Adjustment Type)</label>
                                                                <select name="type" class="form-select" required>
                                                                    <option value="stock_in">স্টক বৃদ্ধি (Stock In / Add Stock)</option>
                                                                    <option value="damage_out">ড্যামেজ স্টক আউট (Damaged Stock)</option>
                                                                    <option value="adjustment">স্টক হ্রাস / অ্যাডজাস্টমেন্ট (Stock Out)</option>
                                                                    <option value="correction">সরাসরি স্টক সংশোধন (Exact Correction)</option>
                                                                </select>
                                                            </div>
                                                            <div class="mb-3">
                                                                <label class="form-label small fw-bold">পরিমাণ (Quantity)</label>
                                                                <input type="number" name="quantity" class="form-control" required min="1" placeholder="সংখ্যা লিখুন...">
                                                            </div>
                                                            <div class="mb-3">
                                                                <label class="form-label small fw-bold">কারণ বা রেফারেন্স (Reason)</label>
                                                                <textarea name="reason" rows="2" class="form-control" required placeholder="কেন পরিবর্তন করছেন তা লিখুন..."></textarea>
                                                            </div>
                                                        </div>
                                                        <div class="modal-footer bg-light rounded-bottom-4 py-3">
                                                            <button type="button" class="btn btn-secondary rounded-pill" data-bs-dismiss="modal">বাতিল</button>
                                                            <button type="submit" class="btn btn-primary rounded-pill fw-bold">স্টক আপডেট করুন</button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    @empty
                                        <tr>
                                            <td colspan="8" class="text-center text-muted py-4">কোনো প্রোডাক্ট পাওয়া যায়নি।</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                        <div class="mt-3">
                            {{ $branchProducts->appends(['tab' => 'products'])->links() }}
                        </div>
                    </div>
                </div>

                <!-- ========================================== -->
                <!-- 5. STOCK MOVEMENT TAB (Section 6.4)        -->
                <!-- ========================================== -->
                <div class="tab-pane fade {{ $activeTab == 'movements' ? 'show active' : '' }}" id="tab-movements" role="tabpanel">
                    <div class="card border-0 shadow-sm rounded-4 p-4 bg-white mb-4">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <div>
                                <h5 class="fw-bold text-dark mb-0">স্টক মুভমেন্ট অডিট লগ (Stock Movement History)</h5>
                                <small class="text-muted">প্রতিটি পণ্যের ইন, আউট, বিক্রয়, স্থানান্তর ও সমন্বয়ের অপরিবর্তনীয় হিস্ট্রি</small>
                            </div>
                        </div>

                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th>তারিখ ও সময়</th>
                                        <th>প্রোডাক্টের নাম</th>
                                        <th class="text-center">মুভমেন্টের ধরন</th>
                                        <th class="text-center">পরিমাণ</th>
                                        <th class="text-center">ব্যালেন্স আফটার</th>
                                        <th>রেফারেন্স নম্বর</th>
                                        <th>সম্পাদনকারী (User)</th>
                                        <th>বিবরণ ও কারণ</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($stockMovements as $sm)
                                        <tr>
                                            <td>{{ $sm->created_at->format('d M, Y h:i A') }}</td>
                                            <td class="fw-bold text-dark">{{ $sm->product ? $sm->product->name : 'N/A' }}</td>
                                            <td class="text-center">
                                                <span class="badge bg-light text-dark border">{{ strtoupper($sm->type) }}</span>
                                            </td>
                                            <td class="text-center fw-bold text-{{ $sm->quantity > 0 ? 'success' : 'danger' }}">
                                                {{ $sm->quantity > 0 ? '+' : '' }}{{ $sm->quantity }}
                                            </td>
                                            <td class="text-center fw-bold">{{ $sm->balance_after }}</td>
                                            <td><span class="badge bg-light text-muted">{{ $sm->reference_no ?: 'N/A' }}</span></td>
                                            <td><span class="small text-muted">{{ $sm->user ? $sm->user->name : 'System' }}</span></td>
                                            <td class="small text-muted">{{ $sm->notes ?: '-' }}</td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="8" class="text-center text-muted py-4">কোনো স্টক মুভমেন্ট লগ নেই।</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- ========================================== -->
                <!-- 6. STOCK TRANSFERS TAB (Section 7)         -->
                <!-- ========================================== -->
                <div class="tab-pane fade {{ $activeTab == 'transfers' ? 'show active' : '' }}" id="tab-transfers" role="tabpanel">
                    <div class="card border-0 shadow-sm rounded-4 p-4 bg-white mb-4">
                        <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-2">
                            <div>
                                <h5 class="fw-bold text-dark mb-0">ব্রাঞ্চ টু ব্রাঞ্চ স্টক ট্রান্সফার (Branch Stock Transfers)</h5>
                                <small class="text-muted">এক শাখা থেকে অন্য শাখায় পণ্য পাঠানোর অনুমোদন ও রিসিভিং সিস্টেম</small>
                            </div>
                            <button type="button" class="btn btn-sm btn-success fw-bold" data-bs-toggle="modal" data-bs-target="#newTransferModal">
                                <i class="ti-plus me-1"></i> নতুন ট্রান্সফার রিকোয়েস্ট
                            </button>
                        </div>

                        <!-- Outgoing Transfers Sent -->
                        <h6 class="fw-bold text-dark mt-2 mb-2"><i class="ti-arrow-up text-danger me-1"></i>এই ব্রাঞ্চ থেকে পাঠানো স্টক (Sent Transfers)</h6>
                        <div class="table-responsive mb-4">
                            <table class="table table-hover align-middle mb-0">
                                <thead class="table-light smaller">
                                    <tr>
                                        <th>ট্রান্সফার নম্বর</th>
                                        <th>গন্তব্য ব্রাঞ্চ (Destination)</th>
                                        <th class="text-center">আইটেম সংখ্যা</th>
                                        <th>আবেদনের তারিখ</th>
                                        <th class="text-center">স্ট্যাটাস</th>
                                        <th class="text-end pe-3">অ্যাকশন</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($transfersSent as $ts)
                                        <tr>
                                            <td><span class="badge bg-dark">{{ $ts->transfer_number }}</span></td>
                                            <td class="fw-bold text-dark">{{ $ts->destinationBranch ? $ts->destinationBranch->name : 'N/A' }}</td>
                                            <td class="text-center"><span class="badge bg-light text-dark border">{{ $ts->items->count() }} আইটেম</span></td>
                                            <td>{{ $ts->request_date }}</td>
                                            <td class="text-center">
                                                <span class="badge bg-{{ $ts->status == 'received' ? 'success' : ($ts->status == 'in_transit' ? 'warning' : 'secondary') }} rounded-pill px-3 py-1 text-uppercase">
                                                    {{ $ts->status }}
                                                </span>
                                            </td>
                                            <td class="text-end pe-3">
                                                @if($ts->status == 'pending_approval')
                                                    <form action="{{ route('admin.supershop.branches.transfers.dispatch', $ts->id) }}" method="POST" class="d-inline">
                                                        @csrf
                                                        <button type="submit" class="btn btn-xs btn-primary fw-bold" onclick="return confirm('ডিসপ্যাচ করতে চান? উৎস ব্রাঞ্চের স্টক কমে যাবে।')">
                                                            ডিসপ্যাচ করুন
                                                        </button>
                                                    </form>
                                                @endif
                                            </td>
                                        </tr>
                                    @empty
                                        <tr><td colspan="6" class="text-center text-muted py-3">কোনো প্রেরিত স্টক ট্রান্সফার নেই।</td></tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>

                        <!-- Incoming Transfers Received -->
                        <h6 class="fw-bold text-dark mt-3 mb-2"><i class="ti-arrow-down text-success me-1"></i>অন্য ব্রাঞ্চ থেকে আগত স্টক (Incoming Transfers)</h6>
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0">
                                <thead class="table-light smaller">
                                    <tr>
                                        <th>ট্রান্সফার নম্বর</th>
                                        <th>উৎস ব্রাঞ্চ (Source)</th>
                                        <th class="text-center">আইটেম সংখ্যা</th>
                                        <th>আগমনের তারিখ</th>
                                        <th class="text-center">স্ট্যাটাস</th>
                                        <th class="text-end pe-3">অ্যাকশন</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($transfersReceived as $tr)
                                        <tr>
                                            <td><span class="badge bg-dark">{{ $tr->transfer_number }}</span></td>
                                            <td class="fw-bold text-dark">{{ $tr->sourceBranch ? $tr->sourceBranch->name : 'N/A' }}</td>
                                            <td class="text-center"><span class="badge bg-light text-dark border">{{ $tr->items->count() }} আইটেম</span></td>
                                            <td>{{ $tr->request_date }}</td>
                                            <td class="text-center">
                                                <span class="badge bg-{{ $tr->status == 'received' ? 'success' : ($tr->status == 'in_transit' ? 'warning' : 'secondary') }} rounded-pill px-3 py-1 text-uppercase">
                                                    {{ $tr->status }}
                                                </span>
                                            </td>
                                            <td class="text-end pe-3">
                                                @if($tr->status == 'in_transit')
                                                    <form action="{{ route('admin.supershop.branches.transfers.receive', $tr->id) }}" method="POST" class="d-inline">
                                                        @csrf
                                                        <button type="submit" class="btn btn-xs btn-success fw-bold" onclick="return confirm('পণ্য রিসিভ নিশ্চিত করতে চান? এই ব্রাঞ্চের স্টক বৃদ্ধি পাবে।')">
                                                            রিসিভ ও স্টক ইন
                                                        </button>
                                                    </form>
                                                @endif
                                            </td>
                                        </tr>
                                    @empty
                                        <tr><td colspan="6" class="text-center text-muted py-3">কোনো আগত স্টক ট্রান্সফার নেই।</td></tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- New Transfer Modal -->
                    <div class="modal fade" id="newTransferModal" tabindex="-1" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content rounded-4 border-0 shadow">
                                <div class="modal-header bg-success text-white rounded-top-4 py-3">
                                    <h5 class="modal-title fw-bold text-white">নতুন স্টক ট্রান্সফার রিকোয়েস্ট</h5>
                                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                                </div>
                                <form action="{{ route('admin.supershop.branches.transfers.store') }}" method="POST">
                                    @csrf
                                    <input type="hidden" name="source_branch_id" value="{{ $branch->id }}">
                                    <div class="modal-body p-4">
                                        <div class="mb-3">
                                            <label class="form-label small fw-bold">উৎস ব্রাঞ্চ (Source Branch)</label>
                                            <input type="text" class="form-control bg-light" value="{{ $branch->name }}" readonly>
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label small fw-bold">গন্তব্য ব্রাঞ্চ নির্বাচন করুন <span class="text-danger">*</span></label>
                                            <select name="destination_branch_id" class="form-select" required>
                                                <option value="">-- গন্তব্য ব্রাঞ্চ বাছাই করুন --</option>
                                                @foreach($allOtherBranches as $ob)
                                                    <option value="{{ $ob->id }}">{{ $ob->name }} ({{ $ob->code }})</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label small fw-bold">প্রোডাক্ট নির্বাচন করুন <span class="text-danger">*</span></label>
                                            <select name="product_id" class="form-select" required>
                                                <option value="">-- প্রোডাক্ট বাছাই করুন --</option>
                                                @foreach($branchProducts as $bp)
                                                    <option value="{{ $bp->product_id }}">{{ $bp->product->name }} (মজুদ: {{ $bp->stock_quantity }} টি)</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label small fw-bold">ট্রান্সফার পরিমাণ (Quantity) <span class="text-danger">*</span></label>
                                            <input type="number" name="quantity" class="form-control" required min="1" placeholder="কতটি পণ্য পাঠাবেন...">
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label small fw-bold">বিবরণ / নোট</label>
                                            <textarea name="notes" rows="2" class="form-control" placeholder="মন্তব্য বা চালানের কারণ..."></textarea>
                                        </div>
                                    </div>
                                    <div class="modal-footer bg-light rounded-bottom-4 py-3">
                                        <button type="button" class="btn btn-secondary rounded-pill" data-bs-dismiss="modal">বাতিল</button>
                                        <button type="submit" class="btn btn-success rounded-pill fw-bold">ট্রান্সফার রিকোয়েস্ট তৈরি করুন</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- ========================================== -->
                <!-- 7. BRANCH EXPENSES TAB (Section 8)         -->
                <!-- ========================================== -->
                <div class="tab-pane fade {{ $activeTab == 'expenses' ? 'show active' : '' }}" id="tab-expenses" role="tabpanel">
                    <div class="card border-0 shadow-sm rounded-4 p-4 bg-white mb-4">
                        <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-2">
                            <div>
                                <h5 class="fw-bold text-dark mb-0">ব্রাঞ্চ পরিচালন ব্যয় (Branch Expense Management)</h5>
                                <small class="text-muted">ভাড়া, বিদ্যুৎ, স্টাফ খাবার, ট্রাসপোর্ট ও অন্যান্য খরচের হিসাব</small>
                            </div>
                            <button type="button" class="btn btn-sm btn-danger fw-bold" data-bs-toggle="modal" data-bs-target="#newExpenseModal">
                                <i class="ti-plus me-1"></i> নতুন খরচ এন্ট্রি করুন
                            </button>
                        </div>

                        <!-- Expense Table -->
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th>তারিখ</th>
                                        <th>ক্যাটাগরি</th>
                                        <th>খরচের বিবরণ / টাইটেল</th>
                                        <th class="text-end">টাকার পরিমাণ</th>
                                        <th class="text-center">পেমেন্ট মেথড</th>
                                        <th class="text-center">স্ট্যাটাস</th>
                                        <th class="text-center">রসিদ / ভাউচার</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($expenses as $exp)
                                        <tr>
                                            <td>{{ $exp->expense_date ? ($exp->expense_date instanceof \Carbon\Carbon ? $exp->expense_date->format('d M, Y') : date('d M, Y', strtotime($exp->expense_date))) : '-' }}</td>
                                            <td><span class="badge bg-light text-dark border">{{ $exp->category ? $exp->category->name : 'সাধারণ' }}</span></td>
                                            <td class="fw-semibold text-dark">{{ $exp->title }}</td>
                                            <td class="text-end fw-bold text-danger fs-6">৳ {{ number_format($exp->amount, 2) }}</td>
                                            <td class="text-center"><span class="badge bg-info-light text-info text-uppercase">{{ $exp->payment_method }}</span></td>
                                            <td class="text-center">
                                                <span class="badge bg-{{ $exp->status == 'paid' ? 'success' : 'warning' }} rounded-pill px-2 py-1 text-uppercase">
                                                    {{ $exp->status }}
                                                </span>
                                            </td>
                                            <td class="text-center">
                                                @if($exp->receipt_attachment)
                                                    <a href="{{ asset($exp->receipt_attachment) }}" target="_blank" class="badge bg-primary text-white text-decoration-none">
                                                        <i class="ti-clip me-1"></i> দেখুন
                                                    </a>
                                                @else
                                                    <span class="text-muted smaller">নেই</span>
                                                @endif
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="7" class="text-center text-muted py-4">এই সময়কালে কোনো খরচ রেকর্ড নেই।</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- New Expense Modal -->
                    <div class="modal fade" id="newExpenseModal" tabindex="-1" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content rounded-4 border-0 shadow">
                                <div class="modal-header bg-danger text-white rounded-top-4 py-3">
                                    <h5 class="modal-title fw-bold text-white">নতুন খরচ এন্ট্রি করুন – {{ $branch->name }}</h5>
                                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                                </div>
                                <form action="{{ route('admin.supershop.branches.expenses.store') }}" method="POST" enctype="multipart/form-data">
                                    @csrf
                                    <input type="hidden" name="branch_id" value="{{ $branch->id }}">
                                    <div class="modal-body p-4">
                                        <div class="mb-3">
                                            <label class="form-label small fw-bold">খরচের ক্যাটাগরি <span class="text-danger">*</span></label>
                                            <select name="category_id" class="form-select" required>
                                                @foreach($expenseCategories as $ec)
                                                    <option value="{{ $ec->id }}">{{ $ec->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label small fw-bold">খরচের শিরোনাম (Title) <span class="text-danger">*</span></label>
                                            <input type="text" name="title" class="form-control" placeholder="যেমন: দোকান ভাড়া বা ইলেকট্রিসিটি বিল" required>
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label small fw-bold">টাকার পরিমাণ (৳) <span class="text-danger">*</span></label>
                                            <input type="number" step="0.01" name="amount" class="form-control" placeholder="যেমন: 5000" required min="1">
                                        </div>
                                        <div class="row g-2 mb-3">
                                            <div class="col-6">
                                                <label class="form-label small fw-bold">তারিখ <span class="text-danger">*</span></label>
                                                <input type="date" name="expense_date" class="form-control" value="{{ date('Y-m-d') }}" required>
                                            </div>
                                            <div class="col-6">
                                                <label class="form-label small fw-bold">পেমেন্ট মেথড</label>
                                                <select name="payment_method" class="form-select">
                                                    <option value="cash">নগদ (Cash)</option>
                                                    <option value="bkash">বিকাশ (bKash)</option>
                                                    <option value="nagad">নগদ (Nagad)</option>
                                                    <option value="bank">ব্যাংক ট্রান্সফার (Bank)</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label small fw-bold">রসিদ / ভাউচার ফাইল</label>
                                            <input type="file" name="receipt" class="form-control" accept="image/*,.pdf">
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label small fw-bold">বিবরণ (Description)</label>
                                            <textarea name="description" rows="2" class="form-control" placeholder="অতিরিক্ত বিবরণ লিখুন..."></textarea>
                                        </div>
                                    </div>
                                    <div class="modal-footer bg-light rounded-bottom-4 py-3">
                                        <button type="button" class="btn btn-secondary rounded-pill" data-bs-dismiss="modal">বাতিল</button>
                                        <button type="submit" class="btn btn-danger rounded-pill fw-bold">খরচ সংরক্ষণ করুন</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- ========================================== -->
                <!-- 8. CASH & TRANSACTIONS TAB (Section 9)     -->
                <!-- ========================================== -->
                <div class="tab-pane fade {{ $activeTab == 'cash' ? 'show active' : '' }}" id="tab-cash" role="tabpanel">
                    <div class="card border-0 shadow-sm rounded-4 p-4 bg-white mb-4">
                        <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-2">
                            <div>
                                <h5 class="fw-bold text-dark mb-0">ক্যাশ ম্যানেজমেন্ট ও ট্রানজেকশন লেজার (Cash & Financial Ledger)</h5>
                                <small class="text-muted">শাখার দৈনিক ক্যাশ ব্যালেন্স, ইন-আউট ও ক্লোজিং হিসাব</small>
                            </div>
                            <div class="d-flex gap-2">
                                <button type="button" class="btn btn-sm btn-outline-primary fw-bold" data-bs-toggle="modal" data-bs-target="#newTransactionModal">
                                    <i class="ti-plus me-1"></i> ম্যানুয়াল ট্রানজেকশন
                                </button>
                                <button type="button" class="btn btn-sm btn-dark fw-bold" data-bs-toggle="modal" data-bs-target="#cashClosingModal">
                                    <i class="ti-lock me-1"></i> দৈনিক ক্যাশ ক্লোজিং
                                </button>
                            </div>
                        </div>

                        <!-- Cash Account Summary -->
                        <div class="row g-3 mb-4 text-center">
                            <div class="col-md-3 col-6">
                                <div class="p-3 border rounded-3 bg-light">
                                    <div class="smaller text-muted">ওপেনিং ক্যাশ ব্যালেন্স</div>
                                    <div class="fs-5 fw-bold text-dark">৳ {{ number_format($branch->opening_cash ?: 10000, 2) }}</div>
                                </div>
                            </div>
                            <div class="col-md-3 col-6">
                                <div class="p-3 border rounded-3 bg-light">
                                    <div class="smaller text-muted">আজকের নগদ বিক্রয়</div>
                                    <div class="fs-5 fw-bold text-success">৳ {{ number_format($todaySales, 2) }}</div>
                                </div>
                            </div>
                            <div class="col-md-3 col-6">
                                <div class="p-3 border rounded-3 bg-light">
                                    <div class="smaller text-muted">আজকের নগদ খরচ</div>
                                    <div class="fs-5 fw-bold text-danger">৳ {{ number_format($todayExpenses, 2) }}</div>
                                </div>
                            </div>
                            <div class="col-md-3 col-6">
                                <div class="p-3 border rounded-3 bg-light">
                                    <div class="smaller text-muted">বর্তমান আনুমানিক ক্যাশ</div>
                                    <div class="fs-5 fw-bold text-primary">৳ {{ number_format(($branch->opening_cash ?: 10000) + $todaySales - $todayExpenses, 2) }}</div>
                                </div>
                            </div>
                        </div>

                        <!-- Transaction Ledger Table (Section 9.2) -->
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th>তারিখ</th>
                                        <th>লেনদেনের ধরন</th>
                                        <th>রেফারেন্স</th>
                                        <th class="text-end text-success">ইন (In +)</th>
                                        <th class="text-end text-danger">আউট (Out -)</th>
                                        <th class="text-center">মেথড</th>
                                        <th>সম্পাদনকারী</th>
                                        <th>বিবরণ</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($transactions as $tx)
                                        <tr>
                                            <td>{{ $tx->transaction_date ? ($tx->transaction_date instanceof \Carbon\Carbon ? $tx->transaction_date->format('d M, Y') : date('d M, Y', strtotime($tx->transaction_date))) : '-' }}</td>
                                            <td><span class="badge bg-light text-dark border text-uppercase">{{ str_replace('_', ' ', $tx->transaction_type) }}</span></td>
                                            <td><span class="badge bg-dark">{{ $tx->reference_no ?: 'TXN-#' . $tx->id }}</span></td>
                                            <td class="text-end fw-bold text-success">{{ $tx->in_amount > 0 ? '৳ ' . number_format($tx->in_amount, 2) : '-' }}</td>
                                            <td class="text-end fw-bold text-danger">{{ $tx->out_amount > 0 ? '৳ ' . number_format($tx->out_amount, 2) : '-' }}</td>
                                            <td class="text-center"><span class="badge bg-info-light text-info text-uppercase">{{ $tx->payment_method }}</span></td>
                                            <td><span class="small text-muted">{{ $tx->user ? $tx->user->name : 'System' }}</span></td>
                                            <td class="small text-muted">{{ $tx->description }}</td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="8" class="text-center text-muted py-4">কোনো লেনদেনের রেকর্ড নেই।</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- Modal Manual Transaction -->
                    <div class="modal fade" id="newTransactionModal" tabindex="-1" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content rounded-4 border-0 shadow">
                                <div class="modal-header bg-primary text-white rounded-top-4 py-3">
                                    <h5 class="modal-title fw-bold text-white">ম্যানুয়াল আর্থিক লেনদেন এন্ট্রি</h5>
                                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                                </div>
                                <form action="{{ route('admin.supershop.branches.transactions.store') }}" method="POST">
                                    @csrf
                                    <input type="hidden" name="branch_id" value="{{ $branch->id }}">
                                    <div class="modal-body p-4">
                                        <div class="mb-3">
                                            <label class="form-label small fw-bold">লেনদেনের ধরন</label>
                                            <select name="transaction_type" class="form-select" required>
                                                <option value="cash_deposit">ক্যাশ জমা (Cash In / Deposit)</option>
                                                <option value="cash_withdrawal">ক্যাশ উত্তোলন (Cash Out / Withdrawal)</option>
                                                <option value="adjustment">ক্যাশ সমন্বয় (Adjustment)</option>
                                            </select>
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label small fw-bold">টাকার পরিমাণ (৳)</label>
                                            <input type="number" step="0.01" name="amount" class="form-control" required min="1" placeholder="টাকা লিখুন...">
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label small fw-bold">পেমেন্ট মেথড</label>
                                            <select name="payment_method" class="form-select">
                                                <option value="cash">নগদ (Cash)</option>
                                                <option value="bank">ব্যাংক (Bank)</option>
                                                <option value="bkash">বিকাশ (bKash)</option>
                                            </select>
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label small fw-bold">বিবরণ / কারণ <span class="text-danger">*</span></label>
                                            <textarea name="description" rows="2" class="form-control" required placeholder="কেন এই লেনদেন করা হলো..."></textarea>
                                        </div>
                                    </div>
                                    <div class="modal-footer bg-light rounded-bottom-4 py-3">
                                        <button type="button" class="btn btn-secondary rounded-pill" data-bs-dismiss="modal">বাতিল</button>
                                        <button type="submit" class="btn btn-primary rounded-pill fw-bold">লেনদেন সংরক্ষণ করুন</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>

                    <!-- Modal Cash Closing (Section 9.3) -->
                    <div class="modal fade" id="cashClosingModal" tabindex="-1" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content rounded-4 border-0 shadow">
                                <div class="modal-header bg-dark text-white rounded-top-4 py-3">
                                    <h5 class="modal-title fw-bold text-white">দৈনিক ব্রাঞ্চ ক্যাশ ক্লোজিং (Daily Closing)</h5>
                                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                                </div>
                                <form action="{{ route('admin.supershop.branches.cash_closing') }}" method="POST">
                                    @csrf
                                    <input type="hidden" name="branch_id" value="{{ $branch->id }}">
                                    <div class="modal-body p-4">
                                        <div class="alert alert-info py-2 small">
                                            <i class="ti-info-alt me-1"></i>প্রতিদিন ব্রাঞ্চ বন্ধ করার সময় ড্রয়ারে থাকা বাস্তব ক্যাশ গণনা করে এন্ট্রি করুন।
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label small fw-bold">হাতে থাকা আসল ক্যাশ টাকা (Actual Cash Count) <span class="text-danger">*</span></label>
                                            <input type="number" step="0.01" name="actual_cash" class="form-control fs-5 fw-bold text-success" required placeholder="৳ 0.00">
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label small fw-bold">ক্লোজিং মন্তব্য / ক্যাশ অমিল কারণ (যদি থাকে)</label>
                                            <textarea name="closing_notes" rows="2" class="form-control" placeholder="কোনো টাকা শর্ট বা বেশি থাকলে কারণ উল্লেখ করুন..."></textarea>
                                        </div>
                                    </div>
                                    <div class="modal-footer bg-light rounded-bottom-4 py-3">
                                        <button type="button" class="btn btn-secondary rounded-pill" data-bs-dismiss="modal">বাতিল</button>
                                        <button type="submit" class="btn btn-dark rounded-pill fw-bold">ক্লোজিং সম্পন্ন করুন</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- ========================================== -->
                <!-- 9. STAFF MANAGEMENT (Section 10 & Images)  -->
                <!-- ========================================== -->
                <div class="tab-pane fade {{ $activeTab == 'staff' ? 'show active' : '' }}" id="tab-staff" role="tabpanel">
                    <div class="card border-0 shadow-sm rounded-4 p-4 bg-white mb-4">
                        <!-- Top Staff KPI Cards (Matching Pheromonix & Targetym Annuaire UI) -->
                        <div class="row g-3 mb-4">
                            <div class="col-md-3 col-6">
                                <div class="p-3 border rounded-3 bg-light text-center">
                                    <span class="smaller text-muted fw-bold">মোট স্টাফ (Total Staff)</span>
                                    <div class="fs-4 fw-bold text-dark mt-1">{{ $totalStaffCount }} জন</div>
                                    <span class="smallest text-muted">এই শাখায় কর্মরত</span>
                                </div>
                            </div>
                            <div class="col-md-3 col-6">
                                <div class="p-3 border rounded-3 bg-light text-center">
                                    <span class="smaller text-muted fw-bold">সক্রিয় স্টাফ (Active)</span>
                                    <div class="fs-4 fw-bold text-success mt-1">{{ $staffMembers->where('status', 'active')->count() }} জন</div>
                                    <span class="smallest text-muted">ডিউটিতে বিদ্যমান</span>
                                </div>
                            </div>
                            <div class="col-md-3 col-6">
                                <div class="p-3 border rounded-3 bg-light text-center">
                                    <span class="smaller text-muted fw-bold">ছুটিতে (On Leave)</span>
                                    <div class="fs-4 fw-bold text-warning mt-1">{{ $staffMembers->where('status', 'on_leave')->count() }} জন</div>
                                    <span class="smallest text-muted">অনুমোদিত ছুটি</span>
                                </div>
                            </div>
                            <div class="col-md-3 col-6">
                                <div class="p-3 border rounded-3 bg-light text-center">
                                    <span class="smaller text-muted fw-bold">মাসিক বেতন খরচ (Payroll)</span>
                                    <div class="fs-4 fw-bold text-primary mt-1">৳ {{ number_format($monthlySalaryExpense, 2) }}</div>
                                    <span class="smallest text-muted">বাজেট প্রতি মাস</span>
                                </div>
                            </div>
                        </div>

                        <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-2">
                            <div>
                                <h5 class="fw-bold text-dark mb-0">স্টাফ ডিরেক্টরি (Staff Directory)</h5>
                                <small class="text-muted">কর্মচারীদের পদবী, যোগাযোগ ও বেতনের কাঠামো</small>
                            </div>
                            <button type="button" class="btn btn-sm btn-success fw-bold" data-bs-toggle="modal" data-bs-target="#newStaffModal">
                                <i class="ti-plus me-1"></i> নতুন কর্মচারী যুক্ত করুন
                            </button>
                        </div>

                        <!-- Staff Table with Side Drawer Preview trigger -->
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th>কর্মচারীর নাম ও কোড</th>
                                        <th>পদবী (Designation)</th>
                                        <th>মোবাইল নম্বর</th>
                                        <th>যোগদানের তারিখ</th>
                                        <th class="text-end">মাসিক বেতন</th>
                                        <th class="text-center">স্ট্যাটাস</th>
                                        <th class="text-end pe-3">প্রোফাইল</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($staffMembers as $emp)
                                        <tr>
                                            <td>
                                                <div class="d-flex align-items-center gap-2">
                                                    <div class="rounded-circle bg-primary-light text-primary fw-bold d-flex align-items-center justify-content-center" style="width: 38px; height: 38px; min-width: 38px;">
                                                        {{ strtoupper(substr($emp->name, 0, 2)) }}
                                                    </div>
                                                    <div>
                                                        <strong class="text-dark">{{ $emp->name }}</strong>
                                                        <div class="smaller text-muted"><span class="badge bg-dark">{{ $emp->employee_code }}</span></div>
                                                    </div>
                                                </div>
                                            </td>
                                            <td><span class="badge bg-light text-dark border">{{ $emp->designation }}</span></td>
                                            <td><span class="small text-muted"><i class="ti-mobile text-success me-1"></i>{{ $emp->phone }}</span></td>
                                            <td><span class="small text-muted">{{ $emp->joining_date ? date('d M, Y', strtotime($emp->joining_date)) : 'N/A' }}</span></td>
                                            <td class="text-end fw-bold text-success fs-6">৳ {{ number_format($emp->salary, 2) }}</td>
                                            <td class="text-center">
                                                <span class="badge bg-{{ $emp->status == 'active' ? 'success' : 'secondary' }} rounded-pill px-3 py-1">
                                                    {{ $emp->status == 'active' ? 'সক্রিয়' : 'নিষ্ক্রিয়' }}
                                                </span>
                                            </td>
                                            <td class="text-end pe-3">
                                                <button type="button" class="btn btn-xs btn-outline-info" data-bs-toggle="modal" data-bs-target="#staffProfileModal{{ $emp->id }}">
                                                    <i class="ti-eye me-1"></i> বিবরণ
                                                </button>
                                            </td>
                                        </tr>

                                        <!-- Employee Profile Drawer / Modal (Matching Targetym style) -->
                                        <div class="modal fade" id="staffProfileModal{{ $emp->id }}" tabindex="-1" aria-hidden="true">
                                            <div class="modal-dialog">
                                                <div class="modal-content rounded-4 border-0 shadow">
                                                    <div class="modal-header bg-dark text-white rounded-top-4 py-3">
                                                        <h5 class="modal-title fw-bold text-white">কর্মচারী প্রোফাইল কার্ড – {{ $emp->name }}</h5>
                                                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                                                    </div>
                                                    <div class="modal-body p-4 text-center">
                                                        <div class="rounded-circle bg-primary text-white fs-3 fw-bold d-inline-flex align-items-center justify-content-center mb-3 shadow" style="width: 72px; height: 72px;">
                                                            {{ strtoupper(substr($emp->name, 0, 2)) }}
                                                        </div>
                                                        <h4 class="fw-bold text-dark mb-1">{{ $emp->name }}</h4>
                                                        <p class="text-muted small mb-3">{{ $emp->designation }} | <code>{{ $emp->employee_code }}</code></p>
                                                        <span class="badge bg-success-light text-success px-3 py-1 rounded-pill mb-3">সক্রিয় কর্মচারী</span>

                                                        <div class="text-start bg-light rounded-3 p-3 small">
                                                            <div class="mb-2"><strong>শাখা:</strong> {{ $branch->name }}</div>
                                                            <div class="mb-2"><strong>মোবাইল:</strong> {{ $emp->phone }}</div>
                                                            <div class="mb-2"><strong>ইমেইল:</strong> {{ $emp->email ?: 'N/A' }}</div>
                                                            <div class="mb-2"><strong>যোগদান:</strong> {{ $emp->joining_date }}</div>
                                                            <div class="mb-2"><strong>মাসিক মোট বেতন:</strong> ৳ {{ number_format($emp->salary, 2) }}</div>
                                                            @if($emp->salaryStructure)
                                                                <hr class="my-2">
                                                                <div><strong>মূল বেতন (Basic):</strong> ৳ {{ number_format($emp->salaryStructure->basic_salary, 2) }}</div>
                                                                <div><strong>ভাতা (Allowances):</strong> ৳ {{ number_format($emp->salaryStructure->house_allowance + $emp->salaryStructure->transport_allowance + $emp->salaryStructure->food_allowance + $emp->salaryStructure->medical_allowance, 2) }}</div>
                                                            @endif
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @empty
                                        <tr>
                                            <td colspan="7" class="text-center text-muted py-4">এই শাখায় কোনো কর্মচারী তালিকাভুক্ত নেই।</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- Modal Add Staff -->
                    <div class="modal fade" id="newStaffModal" tabindex="-1" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content rounded-4 border-0 shadow">
                                <div class="modal-header bg-success text-white rounded-top-4 py-3">
                                    <h5 class="modal-title fw-bold text-white">নতুন কর্মচারী যুক্ত করুন</h5>
                                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                                </div>
                                <form action="{{ route('admin.supershop.branches.staff.store') }}" method="POST">
                                    @csrf
                                    <input type="hidden" name="branch_id" value="{{ $branch->id }}">
                                    <div class="modal-body p-4">
                                        <div class="mb-3">
                                            <label class="form-label small fw-bold">কর্মচারীর পূর্ণ নাম <span class="text-danger">*</span></label>
                                            <input type="text" name="name" class="form-control" required placeholder="যেমন: মো: কামরুল হাসান">
                                        </div>
                                        <div class="row g-2 mb-3">
                                            <div class="col-6">
                                                <label class="form-label small fw-bold">এমপ্লয়ী কোড <span class="text-danger">*</span></label>
                                                <input type="text" name="employee_code" class="form-control text-uppercase" required placeholder="EMP-{{ $branch->code }}-06">
                                            </div>
                                            <div class="col-6">
                                                <label class="form-label small fw-bold">পদবী (Designation) <span class="text-danger">*</span></label>
                                                <input type="text" name="designation" class="form-control" required placeholder="যেমন: Cashier, Salesman">
                                            </div>
                                        </div>
                                        <div class="row g-2 mb-3">
                                            <div class="col-6">
                                                <label class="form-label small fw-bold">মোবাইল নম্বর <span class="text-danger">*</span></label>
                                                <input type="text" name="phone" class="form-control" required placeholder="01XXXXXXXXX">
                                            </div>
                                            <div class="col-6">
                                                <label class="form-label small fw-bold">মাসিক বেতন (৳) <span class="text-danger">*</span></label>
                                                <input type="number" step="0.01" name="salary" class="form-control" required min="0" placeholder="যেমন: 15000">
                                            </div>
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label small fw-bold">ইমেইল (যদি থাকে)</label>
                                            <input type="email" name="email" class="form-control" placeholder="employee@ekrishisheba.com">
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label small fw-bold">যোগদানের তারিখ</label>
                                            <input type="date" name="joining_date" class="form-control" value="{{ date('Y-m-d') }}">
                                        </div>
                                    </div>
                                    <div class="modal-footer bg-light rounded-bottom-4 py-3">
                                        <button type="button" class="btn btn-secondary rounded-pill" data-bs-dismiss="modal">বাতিল</button>
                                        <button type="submit" class="btn btn-success rounded-pill fw-bold">কর্মচারী সংরক্ষণ করুন</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- ========================================== -->
                <!-- 10. SALARY & PAYROLL (Section 11)          -->
                <!-- ========================================== -->
                <div class="tab-pane fade {{ $activeTab == 'payroll' ? 'show active' : '' }}" id="tab-payroll" role="tabpanel">
                    <div class="card border-0 shadow-sm rounded-4 p-4 bg-white mb-4">
                        <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-2">
                            <div>
                                <h5 class="fw-bold text-dark mb-0">মাসিক বেতন ও পেরোল ব্যবস্থাপনা (Salary & Payroll)</h5>
                                <small class="text-muted">কর্মচারীদের বেতন পরিশোধ, বকেয়া এবং অগ্রিম বেতন সমন্বয়</small>
                            </div>
                            <div class="d-flex gap-2">
                                <button type="button" class="btn btn-sm btn-outline-warning fw-bold" data-bs-toggle="modal" data-bs-target="#salaryAdvanceModal">
                                    <i class="ti-hand-point-right me-1"></i> বেতন অগ্রিম (Advance)
                                </button>
                                <button type="button" class="btn btn-sm btn-success fw-bold" data-bs-toggle="modal" data-bs-target="#paySalaryModal">
                                    <i class="ti-check me-1"></i> বেতন পরিশোধ করুন (Pay Salary)
                                </button>
                            </div>
                        </div>

                        <!-- Salary Payment History Table (Section 11.2 & 11.3) -->
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th>পরিশোধের তারিখ</th>
                                        <th>কর্মচারীর নাম</th>
                                        <th>বেতনের মাস</th>
                                        <th class="text-end">মূল বেতন</th>
                                        <th class="text-end">ভাতা ও বোনাস</th>
                                        <th class="text-end text-success">পরিশোধিত টাকা</th>
                                        <th class="text-center">মেথড</th>
                                        <th class="text-center">স্ট্যাটাস</th>
                                        <th>মন্তব্য</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($salaryPayments as $sp)
                                        <tr>
                                            <td>{{ $sp->payment_date ? ($sp->payment_date instanceof \Carbon\Carbon ? $sp->payment_date->format('d M, Y') : date('d M, Y', strtotime($sp->payment_date))) : '-' }}</td>
                                            <td class="fw-bold text-dark">{{ $sp->employee ? $sp->employee->name : 'N/A' }}</td>
                                            <td><span class="badge bg-dark">{{ $sp->salary_month }}</span></td>
                                            <td class="text-end text-muted">৳ {{ number_format($sp->basic_salary, 2) }}</td>
                                            <td class="text-end text-muted">৳ {{ number_format($sp->allowance + $sp->bonus, 2) }}</td>
                                            <td class="text-end fw-bold text-success fs-6">৳ {{ number_format($sp->paid_amount, 2) }}</td>
                                            <td class="text-center"><span class="badge bg-info-light text-info text-uppercase">{{ $sp->payment_method }}</span></td>
                                            <td class="text-center"><span class="badge bg-success rounded-pill px-2 py-1 text-uppercase">{{ $sp->status }}</span></td>
                                            <td class="small text-muted">{{ $sp->remarks ?: '-' }}</td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="9" class="text-center text-muted py-4">কোনো বেতন পরিশোধের রেকর্ড নেই।</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- Modal Pay Salary -->
                    <div class="modal fade" id="paySalaryModal" tabindex="-1" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content rounded-4 border-0 shadow">
                                <div class="modal-header bg-success text-white rounded-top-4 py-3">
                                    <h5 class="modal-title fw-bold text-white">বেতন পরিশোধ এন্ট্রি (Pay Salary)</h5>
                                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                                </div>
                                <form action="{{ route('admin.supershop.branches.salary.pay') }}" method="POST">
                                    @csrf
                                    <div class="modal-body p-4">
                                        <div class="mb-3">
                                            <label class="form-label small fw-bold">কর্মচারী নির্বাচন করুন <span class="text-danger">*</span></label>
                                            <select name="employee_id" class="form-select" required>
                                                <option value="">-- স্টাফ বাছাই করুন --</option>
                                                @foreach($staffMembers as $sm)
                                                    <option value="{{ $sm->id }}">{{ $sm->name }} ({{ $sm->designation }}) - মাসিক বেতন: ৳{{ number_format($sm->salary) }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="row g-2 mb-3">
                                            <div class="col-6">
                                                <label class="form-label small fw-bold">বেতনের মাস (Salary Month) <span class="text-danger">*</span></label>
                                                <input type="text" name="salary_month" class="form-control" value="{{ date('Y-m') }}" placeholder="2026-11" required>
                                            </div>
                                            <div class="col-6">
                                                <label class="form-label small fw-bold">পরিশোধের পরিমাণ (৳) <span class="text-danger">*</span></label>
                                                <input type="number" step="0.01" name="paid_amount" class="form-control" required min="1" placeholder="টাকা...">
                                            </div>
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label small fw-bold">পেমেন্ট মেথড</label>
                                            <select name="payment_method" class="form-select">
                                                <option value="cash">নগদ (Cash)</option>
                                                <option value="bank">ব্যাংক ট্রান্সফার (Bank)</option>
                                                <option value="bkash">বিকাশ (bKash)</option>
                                            </select>
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label small fw-bold">মন্তব্য / ট্রানজেকশন রেফারেন্স</label>
                                            <textarea name="remarks" rows="2" class="form-control" placeholder="মন্তব্য লিখুন..."></textarea>
                                        </div>
                                    </div>
                                    <div class="modal-footer bg-light rounded-bottom-4 py-3">
                                        <button type="button" class="btn btn-secondary rounded-pill" data-bs-dismiss="modal">বাতিল</button>
                                        <button type="submit" class="btn btn-success rounded-pill fw-bold">বেতন পরিশোধ সম্পন্ন করুন</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>

                    <!-- Modal Salary Advance (Section 11.5) -->
                    <div class="modal fade" id="salaryAdvanceModal" tabindex="-1" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content rounded-4 border-0 shadow">
                                <div class="modal-header bg-warning text-dark rounded-top-4 py-3">
                                    <h5 class="modal-title fw-bold">বেতন অগ্রিম অনুমোদন (Salary Advance)</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                </div>
                                <form action="{{ route('admin.supershop.branches.salary.advance') }}" method="POST">
                                    @csrf
                                    <div class="modal-body p-4">
                                        <div class="mb-3">
                                            <label class="form-label small fw-bold">কর্মচারী নির্বাচন করুন <span class="text-danger">*</span></label>
                                            <select name="employee_id" class="form-select" required>
                                                <option value="">-- স্টাফ বাছাই করুন --</option>
                                                @foreach($staffMembers as $sm)
                                                    <option value="{{ $sm->id }}">{{ $sm->name }} ({{ $sm->designation }})</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="row g-2 mb-3">
                                            <div class="col-6">
                                                <label class="form-label small fw-bold">অগ্রিম টাকার পরিমাণ (৳) <span class="text-danger">*</span></label>
                                                <input type="number" step="0.01" name="advance_amount" class="form-control" required min="1" placeholder="যেমন: 3000">
                                            </div>
                                            <div class="col-6">
                                                <label class="form-label small fw-bold">সমন্বয়ের মাস (Deduction Month)</label>
                                                <input type="text" name="deduction_month" class="form-control" value="{{ date('Y-m', strtotime('+1 month')) }}" placeholder="2026-12">
                                            </div>
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label small fw-bold">অগ্রিম গ্রহণের কারণ <span class="text-danger">*</span></label>
                                            <textarea name="reason" rows="2" class="form-control" required placeholder="জরুরি চিকিৎসা, পারিবারিক প্রয়োজন ইত্যাদি..."></textarea>
                                        </div>
                                    </div>
                                    <div class="modal-footer bg-light rounded-bottom-4 py-3">
                                        <button type="button" class="btn btn-secondary rounded-pill" data-bs-dismiss="modal">বাতিল</button>
                                        <button type="submit" class="btn btn-warning rounded-pill fw-bold">অগ্রিম অনুমোদন করুন</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- ========================================== -->
                <!-- 11. ATTENDANCE & LEAVES (Section 12)       -->
                <!-- ========================================== -->
                <div class="tab-pane fade {{ $activeTab == 'attendance' ? 'show active' : '' }}" id="tab-attendance" role="tabpanel">
                    <div class="card border-0 shadow-sm rounded-4 p-4 bg-white mb-4">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h5 class="fw-bold text-dark mb-0">স্টাফ উপস্থিতি ও ছুটির লগ (Attendance & Leaves)</h5>
                            <span class="badge bg-light text-dark border">দৈনিক হাজিরা</span>
                        </div>

                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th>তারিখ</th>
                                        <th>কর্মচারীর নাম</th>
                                        <th>প্রবেশ সময় (Check In)</th>
                                        <th>প্রস্থান সময় (Check Out)</th>
                                        <th class="text-center">স্ট্যাটাস</th>
                                        <th>নোট / মন্তব্য</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($attendances as $att)
                                        <tr>
                                            <td>{{ $att->date }}</td>
                                            <td class="fw-bold text-dark">{{ $att->employee ? $att->employee->name : 'N/A' }}</td>
                                            <td>{{ $att->check_in ?: 'N/A' }}</td>
                                            <td>{{ $att->check_out ?: 'N/A' }}</td>
                                            <td class="text-center">
                                                <span class="badge bg-{{ $att->status == 'present' ? 'success' : ($att->status == 'late' ? 'warning' : 'danger') }} rounded-pill px-3 py-1 text-uppercase">
                                                    {{ $att->status }}
                                                </span>
                                            </td>
                                            <td class="small text-muted">{{ $att->notes ?: '-' }}</td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="6" class="text-center text-muted py-4">এই সময়কালে কোনো উপস্থিতির রেকর্ড নেই।</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- ========================================== -->
                <!-- 12. PROFIT & LOSS REPORT (Section 15 & 4)  -->
                <!-- ========================================== -->
                <div class="tab-pane fade {{ $activeTab == 'profit_loss' ? 'show active' : '' }}" id="tab-profit-loss" role="tabpanel">
                    <div class="card border-0 shadow-sm rounded-4 p-4 bg-white mb-4">
                        <!-- Top Statement Summary matching Screenshot 4 with Bangladeshi Taka ৳ -->
                        <div class="row g-3 mb-4 text-center">
                            <div class="col-md-2 col-4">
                                <div class="p-3 border rounded-3 bg-light">
                                    <div class="smaller text-muted fw-bold">গ্রস রাজস্ব (Revenue)</div>
                                    <div class="fs-5 fw-bold text-success mt-1">৳ {{ number_format($filteredSalesTotal, 2) }}</div>
                                </div>
                            </div>
                            <div class="col-md-2 col-4">
                                <div class="p-3 border rounded-3 bg-light">
                                    <div class="smaller text-muted fw-bold">পণ্যের ক্রয়মূল্য (COGS)</div>
                                    <div class="fs-5 fw-bold text-muted mt-1">৳ {{ number_format($costOfSoldGoods, 2) }}</div>
                                </div>
                            </div>
                            <div class="col-md-2 col-4">
                                <div class="p-3 border rounded-3 bg-light">
                                    <div class="smaller text-muted fw-bold">গ্রস প্রফিট (Gross Profit)</div>
                                    <div class="fs-5 fw-bold text-primary mt-1">৳ {{ number_format($grossProfit, 2) }}</div>
                                </div>
                            </div>
                            <div class="col-md-2 col-4">
                                <div class="p-3 border rounded-3 bg-light">
                                    <div class="smaller text-muted fw-bold">মোট পরিচালন ব্যয়</div>
                                    <div class="fs-5 fw-bold text-danger mt-1">৳ {{ number_format($filteredTotalExpenses, 2) }}</div>
                                </div>
                            </div>
                            <div class="col-md-2 col-4">
                                <div class="p-3 border rounded-3 bg-light">
                                    <div class="smaller text-muted fw-bold">প্রকৃত নিট লাভ (Net)</div>
                                    <div class="fs-5 fw-bold text-{{ $netProfit >= 0 ? 'success' : 'danger' }} mt-1">৳ {{ number_format($netProfit, 2) }}</div>
                                </div>
                            </div>
                            <div class="col-md-2 col-4">
                                <div class="p-3 border rounded-3 bg-light">
                                    <div class="smaller text-muted fw-bold">প্রফিট মার্জিন (%)</div>
                                    <div class="fs-5 fw-bold text-{{ $profitMargin >= 0 ? 'success' : 'danger' }} mt-1">{{ $profitMargin }}%</div>
                                </div>
                            </div>
                        </div>

                        <!-- Section 15: Detailed Profit & Loss Statement Table -->
                        <h5 class="fw-bold text-dark mb-3">লাভ-লোকসান আর্থিক বিবরণী (Profit & Loss Statement)</h5>
                        <div class="table-responsive">
                            <table class="table table-bordered align-middle">
                                <thead class="table-light">
                                    <tr>
                                        <th>আর্থিক আইটেম (Financial Item)</th>
                                        <th class="text-end">পরিকল্পিত / বাজেট (Planned)</th>
                                        <th class="text-end">প্রকৃত হিসাব (Actual Amount)</th>
                                        <th class="text-center">স্ট্যাটাস</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td class="fw-bold">১. মোট বিক্রয় রাজস্ব (Total Sales Revenue)</td>
                                        <td class="text-end text-muted">৳ {{ number_format($filteredSalesTotal * 1.1, 2) }}</td>
                                        <td class="text-end fw-bold text-success">৳ {{ number_format($filteredSalesTotal, 2) }}</td>
                                        <td class="text-center"><i class="fa fa-arrow-up text-success"></i></td>
                                    </tr>
                                    <tr>
                                        <td>২. বিক্রিত পণ্যের খরচ / ইনভেন্টরি কস্ট (Cost of Goods Sold - COGS)</td>
                                        <td class="text-end text-muted">৳ {{ number_format($costOfSoldGoods, 2) }}</td>
                                        <td class="text-end fw-bold text-muted">৳ {{ number_format($costOfSoldGoods, 2) }}</td>
                                        <td class="text-center"><i class="fa fa-minus text-muted"></i></td>
                                    </tr>
                                    <tr class="table-success">
                                        <td class="fw-bold">৩. গ্রস প্রফিট / মোট লাভ (Gross Profit = Revenue - COGS)</td>
                                        <td class="text-end fw-bold">৳ {{ number_format($grossProfit * 1.05, 2) }}</td>
                                        <td class="text-end fw-bold text-success fs-6">৳ {{ number_format($grossProfit, 2) }}</td>
                                        <td class="text-center"><i class="fa fa-check text-success"></i></td>
                                    </tr>
                                    <tr>
                                        <td>৪. পরিচালন ব্যয়: দোকান ভাড়া (Branch Rent)</td>
                                        <td class="text-end text-muted">৳ 20,000.00</td>
                                        <td class="text-end text-danger">৳ {{ number_format($expenses->where('category.name', 'Branch Rent')->sum('amount'), 2) }}</td>
                                        <td class="text-center"><i class="fa fa-arrow-down text-danger"></i></td>
                                    </tr>
                                    <tr>
                                        <td>৫. পরিচালন ব্যয়: বিদ্যুৎ ও ইউটিলিটি বিল (Electricity & Utility)</td>
                                        <td class="text-end text-muted">৳ 4,000.00</td>
                                        <td class="text-end text-danger">৳ {{ number_format($expenses->where('category.name', 'Electricity Bill')->sum('amount'), 2) }}</td>
                                        <td class="text-center"><i class="fa fa-arrow-down text-danger"></i></td>
                                    </tr>
                                    <tr>
                                        <td>৬. কর্মচারীদের মাসিক বেতন ব্যয় (Staff Salary Expense)</td>
                                        <td class="text-end text-muted">৳ {{ number_format($monthlySalaryExpense, 2) }}</td>
                                        <td class="text-end text-danger">৳ {{ number_format($monthlySalaryExpense, 2) }}</td>
                                        <td class="text-center"><i class="fa fa-arrow-down text-danger"></i></td>
                                    </tr>
                                    <tr>
                                        <td>৭. অন্যান্য বিবিধ খরচ (Maintenance & Miscellaneous)</td>
                                        <td class="text-end text-muted">৳ 3,000.00</td>
                                        <td class="text-end text-danger">৳ {{ number_format($expenses->whereNotIn('category.name', ['Branch Rent', 'Electricity Bill'])->sum('amount'), 2) }}</td>
                                        <td class="text-center"><i class="fa fa-arrow-down text-danger"></i></td>
                                    </tr>
                                    <tr class="table-light fw-bold">
                                        <td>৮. মোট পরিচালন ব্যয় (Total Operating Expenses)</td>
                                        <td class="text-end text-danger">৳ {{ number_format($filteredTotalExpenses * 1.05, 2) }}</td>
                                        <td class="text-end text-danger fs-6">৳ {{ number_format($filteredTotalExpenses, 2) }}</td>
                                        <td class="text-center"><i class="fa fa-minus text-danger"></i></td>
                                    </tr>
                                    <tr class="table-{{ $netProfit >= 0 ? 'success' : 'danger' }} fw-bold fs-5">
                                        <td>৯. প্রকৃত নিট লাভ (Net Profit = Gross Profit - Operating Expenses)</td>
                                        <td class="text-end">৳ {{ number_format($netProfit * 1.05, 2) }}</td>
                                        <td class="text-end text-{{ $netProfit >= 0 ? 'success' : 'danger' }}">৳ {{ number_format($netProfit, 2) }}</td>
                                        <td class="text-center"><i class="fa fa-trophy text-{{ $netProfit >= 0 ? 'success' : 'danger' }}"></i></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- ========================================== -->
                <!-- 13. ACTIVITY LOG TAB (Section 20)          -->
                <!-- ========================================== -->
                <div class="tab-pane fade {{ $activeTab == 'activity' ? 'show active' : '' }}" id="tab-activity" role="tabpanel">
                    <div class="card border-0 shadow-sm rounded-4 p-4 bg-white mb-4">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h5 class="fw-bold text-dark mb-0">শাখা কার্যক্রম ও অডিট ট্রেইল (Branch Activity Logs)</h5>
                            <span class="badge bg-light text-dark border">অপরিবর্তনীয় সিকিউরিটি লগ</span>
                        </div>

                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th>তারিখ ও সময়</th>
                                        <th>ব্যবহারকারী (User)</th>
                                        <th>অ্যাকশন (Action)</th>
                                        <th>এনটিটি (Entity)</th>
                                        <th>কার্যক্রমের বিবরণ (Description)</th>
                                        <th>আইপি অ্যাড্রেস</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($activityLogs as $al)
                                        <tr>
                                            <td>{{ $al->created_at->format('d M, Y h:i A') }}</td>
                                            <td><span class="fw-semibold text-dark">{{ $al->user ? $al->user->name : 'System' }}</span></td>
                                            <td><span class="badge bg-dark">{{ $al->action }}</span></td>
                                            <td><span class="badge bg-light text-dark border">{{ $al->entity_type ?: 'N/A' }}</span></td>
                                            <td class="small text-muted">{{ $al->description }}</td>
                                            <td><code class="smaller">{{ $al->ip_address ?: '127.0.0.1' }}</code></td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="6" class="text-center text-muted py-4">কোনো অ্যাক্টিভিটি লগ পাওয়া যায়নি।</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- ========================================== -->
                <!-- 14. SETTINGS TAB (Section 21)              -->
                <!-- ========================================== -->
                <div class="tab-pane fade {{ $activeTab == 'settings' ? 'show active' : '' }}" id="tab-settings" role="tabpanel">
                    <div class="card border-0 shadow-sm rounded-4 p-4 bg-white mb-4">
                        <h5 class="fw-bold text-dark mb-3"><i class="ti-settings me-1 text-primary"></i>ব্রাঞ্চের কনফিগারেশন সেটিংস ও অনুমোদন সীমা</h5>
                        
                        <form action="{{ route('admin.supershop.branches.update', $branch->id) }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="row g-3">
                                <div class="col-md-6 col-12">
                                    <label class="form-label small fw-bold">শাখার নাম</label>
                                    <input type="text" name="name" class="form-control" value="{{ $branch->name }}" required>
                                </div>
                                <div class="col-md-3 col-6">
                                    <label class="form-label small fw-bold">শাখা কোড</label>
                                    <input type="text" name="code" class="form-control text-uppercase" value="{{ $branch->code }}" required>
                                </div>
                                <div class="col-md-3 col-6">
                                    <label class="form-label small fw-bold">স্ট্যাটাস</label>
                                    <select name="status" class="form-select">
                                        <option value="active" {{ $branch->status == 'active' ? 'selected' : '' }}>সক্রিয়</option>
                                        <option value="inactive" {{ $branch->status == 'inactive' ? 'selected' : '' }}>নিষ্ক্রিয়</option>
                                    </select>
                                </div>
                                <div class="col-md-6 col-12">
                                    <label class="form-label small fw-bold">মোবাইল</label>
                                    <input type="text" name="phone" class="form-control" value="{{ $branch->phone }}">
                                </div>
                                <div class="col-md-6 col-12">
                                    <label class="form-label small fw-bold">ইমেইল</label>
                                    <input type="email" name="email" class="form-control" value="{{ $branch->email }}">
                                </div>
                                <div class="col-12">
                                    <label class="form-label small fw-bold">ঠিকানা</label>
                                    <textarea name="address" rows="2" class="form-control" required>{{ $branch->address }}</textarea>
                                </div>
                                <div class="col-md-4 col-12">
                                    <label class="form-label small fw-bold">ওপেনিং ক্যাশ ব্যালেন্স (৳)</label>
                                    <input type="number" name="opening_cash" class="form-control" value="{{ $branch->opening_cash ?: 10000 }}">
                                </div>
                                <div class="col-md-4 col-6">
                                    <label class="form-label small fw-bold">লো-স্টক অ্যালার্ট লিমিট</label>
                                    <input type="number" name="low_stock_threshold" class="form-control" value="{{ $branch->low_stock_threshold ?: 10 }}">
                                </div>
                                <div class="col-md-4 col-6">
                                    <label class="form-label small fw-bold">খরচ অনুমোদন সীমা (৳)</label>
                                    <input type="number" name="expense_approval_limit" class="form-control" value="{{ $branch->expense_approval_limit ?: 15000 }}">
                                </div>
                                <div class="col-12 text-end">
                                    <button type="submit" class="btn btn-primary fw-bold px-4 rounded-pill">
                                        <i class="ti-save me-1"></i> সেটিংস সংরক্ষণ করুন
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>

            </div>
        </section>
    </div>
</div>

<!-- Chart.js Scripts for interactive charts -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // 1. Toggle custom date fields on dropdown change
    const filterSelect = document.getElementById('globalDateFilterSelect');
    const customFields = document.getElementById('customDateFields');
    if (filterSelect && customFields) {
        filterSelect.addEventListener('change', function() {
            if (this.value === 'custom') {
                customFields.classList.remove('d-none');
            } else {
                customFields.classList.add('d-none');
            }
        });
    }

    // 2. Revenue vs Expenses Combined Trend Chart (Matching Screenshot 4)
    const trendCtx = document.getElementById('revenueExpenseTrendChart');
    if (trendCtx) {
        const trendData = @json($dailyTrends);
        const labels = trendData.map(d => d.day);
        const revData = trendData.map(d => d.revenue);
        const expData = trendData.map(d => d.expense);
        const orderData = trendData.map(d => d.orders);

        new Chart(trendCtx, {
            type: 'bar',
            data: {
                labels: labels,
                datasets: [
                    {
                        type: 'line',
                        label: 'অর্ডার সংখ্যা (Orders)',
                        data: orderData,
                        borderColor: '#2563eb',
                        borderWidth: 2,
                        pointBackgroundColor: '#2563eb',
                        yAxisID: 'y1',
                        tension: 0.3
                    },
                    {
                        type: 'bar',
                        label: 'রাজস্ব (Revenue ৳)',
                        data: revData,
                        backgroundColor: 'rgba(16, 185, 129, 0.4)',
                        borderColor: '#10b981',
                        borderWidth: 1.5,
                        borderRadius: 6,
                        yAxisID: 'y'
                    },
                    {
                        type: 'bar',
                        label: 'খরচ (Expenses ৳)',
                        data: expData,
                        backgroundColor: 'rgba(239, 68, 68, 0.4)',
                        borderColor: '#ef4444',
                        borderWidth: 1.5,
                        borderRadius: 6,
                        yAxisID: 'y'
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: {
                        beginAtZero: true,
                        position: 'left',
                        ticks: {
                            callback: function(val) { return '৳' + val.toLocaleString(); }
                        }
                    },
                    y1: {
                        beginAtZero: true,
                        position: 'right',
                        grid: { drawOnChartArea: false },
                        ticks: { precision: 0 }
                    }
                }
            }
        });
    }

    // 3. Expense Breakdown Donut Chart
    const donutCtx = document.getElementById('expenseCategoryDonutChart');
    if (donutCtx) {
        const catBreakdown = @json($expenseCategoriesBreakdown);
        const catNames = catBreakdown.map(c => c.name);
        const catAmounts = catBreakdown.map(c => c.amount);

        new Chart(donutCtx, {
            type: 'doughnut',
            data: {
                labels: catNames.length > 0 ? catNames : ['কোনো খরচ নেই'],
                datasets: [{
                    data: catAmounts.length > 0 ? catAmounts : [1],
                    backgroundColor: ['#3b82f6', '#f97316', '#10b981', '#ef4444', '#8b5cf6', '#ec4899', '#64748b']
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false }
                }
            }
        });
    }
});
</script>
@endsection
