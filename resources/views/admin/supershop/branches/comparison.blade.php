@extends('layouts.backend.app')

@section('content')
<div class="content-wrapper">
    <div class="container-full">
        <!-- Content Header -->
        <div class="content-header">
            <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
                <div>
                    <div class="d-flex align-items-center gap-2 mb-1">
                        <a href="{{ route('admin.supershop.branches') }}" class="btn btn-sm btn-outline-secondary rounded-circle py-1 px-2" title="সকল ব্রাঞ্চে ফিরুন">
                            <i class="ti-arrow-left"></i>
                        </a>
                        <h3 class="page-title text-dark fw-bold mb-0">
                            <i class="ti-stats-up text-primary me-2"></i>ব্রাঞ্চ পারফর্ম্যান্স তুলনা (Branch Performance Comparison)
                        </h3>
                    </div>
                    <p class="text-muted mb-0">সকল শাখার বিক্রয়, খরচ, মুনাফা, অর্ডার ভলিউম এবং মার্জিনের সামগ্রিক ক্রস-ব্রাঞ্চ অ্যানালিটিক্স</p>
                </div>
                <div class="d-flex gap-2">
                    <a href="{{ route('admin.supershop.branches') }}" class="btn btn-sm btn-outline-dark fw-bold">
                        <i class="ti-layout-grid2 me-1"></i> ব্রাঞ্চ কার্ড ওভারভিউ
                    </a>
                    <button type="button" class="btn btn-sm btn-outline-secondary" onclick="window.print()">
                        <i class="ti-printer me-1"></i> প্রিন্ট রিপোর্ট
                    </button>
                </div>
            </div>
        </div>

        <!-- Main Content -->
        <section class="content">
            <!-- Filter Bar (Section 17) -->
            <div class="card border-0 shadow-sm rounded-4 mb-4 bg-white">
                <div class="card-body p-3">
                    <form method="GET" action="{{ route('admin.supershop.branches.comparison') }}" class="row g-2 align-items-center">
                        <div class="col-md-4 col-12 d-flex align-items-center gap-2">
                            <span class="fw-bold small text-muted text-nowrap"><i class="ti-calendar text-primary me-1"></i>তুলনার সময়কাল:</span>
                            <select name="date_filter" id="compDateFilter" class="form-select form-select-sm">
                                <option value="today" {{ $dateFilter == 'today' ? 'selected' : '' }}>আজ (Today)</option>
                                <option value="yesterday" {{ $dateFilter == 'yesterday' ? 'selected' : '' }}>গতকাল (Yesterday)</option>
                                <option value="this_week" {{ $dateFilter == 'this_week' ? 'selected' : '' }}>চলতি সপ্তাহ (This Week)</option>
                                <option value="this_month" {{ $dateFilter == 'this_month' ? 'selected' : '' }}>চলতি মাস (This Month)</option>
                                <option value="this_year" {{ $dateFilter == 'this_year' ? 'selected' : '' }}>এই বছর (This Year)</option>
                                <option value="custom" {{ $dateFilter == 'custom' ? 'selected' : '' }}>কাস্টম রেঞ্জ (Custom Range)</option>
                            </select>
                        </div>

                        <div class="col-md-5 col-12 d-flex align-items-center gap-2 {{ $dateFilter != 'custom' ? 'd-none' : '' }}" id="compCustomDateFields">
                            <input type="date" name="from_date" class="form-control form-control-sm" value="{{ request('from_date', $startDate->toDateString()) }}">
                            <span class="text-muted small">থেকে</span>
                            <input type="date" name="to_date" class="form-control form-control-sm" value="{{ request('to_date', $endDate->toDateString()) }}">
                        </div>

                        <div class="col-md-3 col-12 d-flex gap-2 justify-content-md-end">
                            <button type="submit" class="btn btn-sm btn-primary fw-bold px-3">
                                <i class="ti-filter me-1"></i> ফিল্টার প্রয়োগ করুন
                            </button>
                            <a href="{{ route('admin.supershop.branches.comparison') }}" class="btn btn-sm btn-light border px-3">
                                রিসেট
                            </a>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Top Network Summary KPI Cards (Franchise & StoresAce Style) -->
            <div class="row g-3 mb-4">
                <div class="col-xl-3 col-md-6 col-12">
                    <div class="card border-0 shadow-sm rounded-4 h-100 p-3 bg-white">
                        <div class="d-flex align-items-center justify-content-between mb-1">
                            <span class="text-muted small fw-semibold">নেটওয়ার্ক মোট বিক্রয় (Revenue)</span>
                            <span class="badge bg-success-light text-success rounded-pill px-2 py-0">সকল শাখা</span>
                        </div>
                        <div class="fs-4 fw-bold text-success">৳ {{ number_format($totalNetworkSales, 2) }}</div>
                        <div class="small text-muted mt-1"><i class="fa fa-shopping-cart text-success me-1"></i>{{ $totalNetworkOrders }} টি সম্পন্ন অর্ডার</div>
                    </div>
                </div>

                <div class="col-xl-3 col-md-6 col-12">
                    <div class="card border-0 shadow-sm rounded-4 h-100 p-3 bg-white">
                        <div class="d-flex align-items-center justify-content-between mb-1">
                            <span class="text-muted small fw-semibold">নেটওয়ার্ক মোট ব্যয় (Expenses)</span>
                            <span class="badge bg-danger-light text-danger rounded-pill px-2 py-0">পরিচালন</span>
                        </div>
                        <div class="fs-4 fw-bold text-danger">৳ {{ number_format($totalNetworkExpenses, 2) }}</div>
                        <div class="small text-muted mt-1"><i class="fa fa-credit-card text-danger me-1"></i>শাখা ভিত্তিক পরিচালন খরচ</div>
                    </div>
                </div>

                <div class="col-xl-3 col-md-6 col-12">
                    <div class="card border-0 shadow-sm rounded-4 h-100 p-3 bg-white">
                        <div class="d-flex align-items-center justify-content-between mb-1">
                            <span class="text-muted small fw-semibold">মোট নিট লাভ (Net Profit)</span>
                            <span class="badge bg-{{ $totalNetworkNetProfit >= 0 ? 'success' : 'danger' }}-light text-{{ $totalNetworkNetProfit >= 0 ? 'success' : 'danger' }} rounded-pill px-2 py-0">মুনাফা</span>
                        </div>
                        <div class="fs-4 fw-bold text-{{ $totalNetworkNetProfit >= 0 ? 'success' : 'danger' }}">৳ {{ number_format($totalNetworkNetProfit, 2) }}</div>
                        <div class="small text-muted mt-1"><i class="fa fa-line-chart text-success me-1"></i>COGS ও ব্যয় বাদে প্রকৃত লাভ</div>
                    </div>
                </div>

                <div class="col-xl-3 col-md-6 col-12">
                    <div class="card border-0 shadow-sm rounded-4 h-100 p-3 bg-white">
                        <div class="d-flex align-items-center justify-content-between mb-1">
                            <span class="text-muted small fw-semibold">গড় নেটওয়ার্ক মার্জিন (%)</span>
                            <span class="badge bg-info-light text-info rounded-pill px-2 py-0">মার্জিন</span>
                        </div>
                        @php
                            $avgNetworkMargin = $totalNetworkSales > 0 ? round(($totalNetworkNetProfit / $totalNetworkSales) * 100, 1) : 0;
                        @endphp
                        <div class="fs-4 fw-bold text-primary">{{ $avgNetworkMargin }}%</div>
                        <div class="small text-muted mt-1"><i class="fa fa-pie-chart text-info me-1"></i>সার্বিক লাভের অনুপাত</div>
                    </div>
                </div>
            </div>

            <!-- Comparison Charts Row (Matching Bipp, Franchise & HashMicro screenshots) -->
            <div class="row g-4 mb-4">
                <div class="col-xl-8 col-12">
                    <div class="card border-0 shadow-sm rounded-4 p-4 bg-white h-100">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <div>
                                <h5 class="fw-bold text-dark mb-0">শাখাভিত্তিক বিক্রয়, খরচ ও মুনাফা তুলনা (Branch Sales vs Expense vs Profit)</h5>
                                <small class="text-muted">প্রতিটি ব্রাঞ্চের সমান্তরাল পারফর্ম্যান্স বার চার্ট</small>
                            </div>
                            <span class="badge bg-light text-dark border">৳ বিডিটি মুদ্রা</span>
                        </div>
                        <div style="height: 340px; position: relative;">
                            <canvas id="branchComparisonBarChart"></canvas>
                        </div>
                    </div>
                </div>

                <div class="col-xl-4 col-12">
                    <div class="card border-0 shadow-sm rounded-4 p-4 bg-white h-100">
                        <h5 class="fw-bold text-dark mb-1">রেভিনিউ শেয়ার বণ্টন (Revenue Share)</h5>
                        <small class="text-muted mb-3 d-block">মোট বিক্রয়ে প্রতিটি শাখার অংশীদারি</small>

                        <div style="height: 240px; position: relative;">
                            <canvas id="branchShareDonutChart"></canvas>
                        </div>

                        <div class="mt-3">
                            <ul class="list-group list-group-flush smaller">
                                @foreach($comparisonData as $cd)
                                    @php
                                        $sharePct = $totalNetworkSales > 0 ? round(($cd['sales'] / $totalNetworkSales) * 100, 1) : 0;
                                    @endphp
                                    <li class="list-group-item d-flex justify-content-between align-items-center px-0 py-1 bg-transparent">
                                        <span><i class="fa fa-building text-primary me-1"></i>{{ $cd['branch']->name }}</span>
                                        <span class="fw-bold text-dark">{{ $sharePct }}% (৳ {{ number_format($cd['sales']) }})</span>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Detailed Cross-Branch Comparison Table (StoresAce Style, Section 17) -->
            <div class="card border-0 shadow-sm rounded-4 bg-white mb-4">
                <div class="card-header bg-white border-0 py-3 px-4 d-flex justify-content-between align-items-center flex-wrap gap-2">
                    <div>
                        <h5 class="fw-bold text-dark mb-0">সকল ব্রাঞ্চের সামগ্রিক তুলনামূলক ম্যাট্রিক্স (Multi-Store Matrix)</h5>
                        <small class="text-muted">বিক্রয়, কস্ট, পরিচালন ব্যয়, নিট প্রফিট এবং মার্জিনের পরিপূর্ণ তুলনা</small>
                    </div>
                    <span class="badge bg-success-light text-success fw-bold px-3 py-1">মোট {{ count($comparisonData) }} টি সক্রিয় শাখা</span>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th class="ps-4">শাখার নাম ও কোড</th>
                                    <th>ম্যানেজার</th>
                                    <th class="text-end">আজকের বিক্রয়</th>
                                    <th class="text-end">নির্বাচিত বিক্রয়</th>
                                    <th class="text-end">পণ্যের কস্ট (COGS)</th>
                                    <th class="text-end">পরিচালন ব্যয়</th>
                                    <th class="text-end text-success">প্রকৃত নিট লাভ</th>
                                    <th class="text-center">মার্জিন (%)</th>
                                    <th class="text-center">অর্ডার সংখ্যা</th>
                                    <th class="text-center">স্টাফ</th>
                                    <th class="text-end pe-4">অ্যাকশন</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($comparisonData as $cd)
                                    <tr>
                                        <td class="ps-4">
                                            <a href="{{ route('admin.supershop.branches.show', $cd['branch']->id) }}" class="fw-bold text-dark text-decoration-none hover-primary">
                                                {{ $cd['branch']->name }}
                                            </a>
                                            <div class="smaller text-muted"><span class="badge bg-dark">{{ $cd['branch']->code }}</span> | {{ Str::limit($cd['branch']->address, 20) }}</div>
                                        </td>
                                        <td>
                                            <div class="small fw-semibold text-dark">{{ $cd['branch']->manager ? $cd['branch']->manager->name : 'N/A' }}</div>
                                            <div class="smaller text-muted">{{ $cd['branch']->phone }}</div>
                                        </td>
                                        <td class="text-end fw-bold text-dark">৳ {{ number_format($cd['daily_sales'], 2) }}</td>
                                        <td class="text-end fw-bold text-primary fs-6">৳ {{ number_format($cd['sales'], 2) }}</td>
                                        <td class="text-end text-muted">৳ {{ number_format($cd['cogs'], 2) }}</td>
                                        <td class="text-end text-danger fw-semibold">৳ {{ number_format($cd['expenses'], 2) }}</td>
                                        <td class="text-end fw-bold text-{{ $cd['net_profit'] >= 0 ? 'success' : 'danger' }} fs-6">
                                            ৳ {{ number_format($cd['net_profit'], 2) }}
                                        </td>
                                        <td class="text-center">
                                            <span class="badge bg-{{ $cd['margin'] >= 15 ? 'success' : ($cd['margin'] >= 0 ? 'warning' : 'danger') }} rounded-pill px-2 py-1">
                                                {{ $cd['margin'] }}%
                                            </span>
                                        </td>
                                        <td class="text-center"><span class="badge bg-light text-dark border">{{ $cd['orders'] }}</span></td>
                                        <td class="text-center"><span class="badge bg-light text-dark border">{{ $cd['staff'] }} জন</span></td>
                                        <td class="text-end pe-4">
                                            <a href="{{ route('admin.supershop.branches.show', $cd['branch']->id) }}" class="btn btn-xs btn-outline-success rounded-pill px-3 fw-bold">
                                                হিসাব দেখুন ➔
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </section>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const compFilter = document.getElementById('compDateFilter');
    const compFields = document.getElementById('compCustomDateFields');
    if (compFilter && compFields) {
        compFilter.addEventListener('change', function() {
            if (this.value === 'custom') {
                compFields.classList.remove('d-none');
            } else {
                compFields.classList.add('d-none');
            }
        });
    }

    const comparisonData = @json($comparisonData);
    const branchNames = comparisonData.map(c => c.branch.code);
    const salesArr = comparisonData.map(c => c.sales);
    const expensesArr = comparisonData.map(c => c.expenses);
    const profitArr = comparisonData.map(c => c.net_profit);

    // 1. Comparison Bar Chart (Matching Bipp Analytics screenshot)
    const barCtx = document.getElementById('branchComparisonBarChart');
    if (barCtx) {
        new Chart(barCtx, {
            type: 'bar',
            data: {
                labels: branchNames,
                datasets: [
                    {
                        label: 'বিক্রয় (Sales ৳)',
                        data: salesArr,
                        backgroundColor: '#3b82f6',
                        borderRadius: 6
                    },
                    {
                        label: 'খরচ (Expenses ৳)',
                        data: expensesArr,
                        backgroundColor: '#ef4444',
                        borderRadius: 6
                    },
                    {
                        label: 'নিট লাভ (Net Profit ৳)',
                        data: profitArr,
                        backgroundColor: '#10b981',
                        borderRadius: 6
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            callback: function(val) { return '৳' + val.toLocaleString(); }
                        }
                    }
                }
            }
        });
    }

    // 2. Revenue Share Donut Chart
    const donutCtx = document.getElementById('branchShareDonutChart');
    if (donutCtx) {
        new Chart(donutCtx, {
            type: 'doughnut',
            data: {
                labels: branchNames,
                datasets: [{
                    data: salesArr.map(s => s > 0 ? s : 1),
                    backgroundColor: ['#10b981', '#3b82f6', '#f59e0b', '#8b5cf6', '#ec4899', '#06b6d4', '#64748b']
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
