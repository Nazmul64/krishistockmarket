@extends('layouts.backend.app')

@section('content')
<div class="content-wrapper">
    <div class="container-full">
        <!-- Content Header -->
        <div class="content-header">
            <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
                <div>
                    <h4 class="page-title text-primary"><i class="fa fa-calculator me-2"></i> Reports & Analytics (কেন্দ্রীয় আর্থিক ও বাণিজ্যিক হিসাব)</h4>
                    <p class="text-muted mb-0">দৈনিক, সাপ্তাহিক, মাসিক এবং কাস্টম ফিল্টার অনুযায়ী কেনাবেচা, প্রপার্টি, স্টক ও প্ল্যাটফর্ম ক্যাশ-ফ্লো রিপোর্ট</p>
                </div>
            </div>
        </div>

        <!-- Main content -->
        <section class="content">
            <!-- Filter Bar -->
            <div class="box shadow-sm mb-4">
                <div class="box-body py-3">
                    <form method="GET" action="{{ route('admin.reports.index') }}" class="row align-items-center g-2">
                        <div class="col-auto">
                            <span class="fw-bold me-2"><i class="fa fa-filter text-primary me-1"></i> রিপোর্ট ফিল্টার:</span>
                            <div class="btn-group">
                                <a href="{{ route('admin.reports.index') }}" class="btn btn-sm btn-outline-secondary {{ !request('filter') && !request('start_date') ? 'active' : '' }}">সর্বমোট (All Time)</a>
                                <a href="{{ route('admin.reports.index', ['filter' => 'today']) }}" class="btn btn-sm btn-outline-success {{ request('filter') == 'today' ? 'active' : '' }}">আজকের (Today)</a>
                                <a href="{{ route('admin.reports.index', ['filter' => 'weekly']) }}" class="btn btn-sm btn-outline-info {{ request('filter') == 'weekly' ? 'active' : '' }}">এই সপ্তাহের (Weekly)</a>
                                <a href="{{ route('admin.reports.index', ['filter' => 'monthly']) }}" class="btn btn-sm btn-outline-primary {{ request('filter') == 'monthly' ? 'active' : '' }}">এই মাসের (Monthly)</a>
                            </div>
                        </div>

                        <div class="col-auto ms-auto d-flex align-items-center">
                            <input type="date" name="start_date" class="form-control form-control-sm me-2" value="{{ request('start_date') }}">
                            <span class="me-2">থেকে</span>
                            <input type="date" name="end_date" class="form-control form-control-sm me-2" value="{{ request('end_date') }}">
                            <button type="submit" class="btn btn-sm btn-dark"><i class="fa fa-search me-1"></i> ফিল্টার করুন</button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Grand Financial Summary Hero Banner -->
            <div class="row mb-4">
                <div class="col-12">
                    <div class="card border-0 shadow-sm text-white" style="background: linear-gradient(135deg, #0d3b66 0%, #001e3d 100%) !important; border-radius: 14px;">
                        <div class="card-body p-4">
                            <div class="row align-items-center">
                                <div class="col-md-4 border-end-md mb-3 mb-md-0">
                                    <small class="text-white-50 text-uppercase fw-bold">প্ল্যাটফর্ম সার্বিক টার্নওভার (Grand Turnover)</small>
                                    <h2 class="fw-bold text-warning mb-1">৳{{ number_format($grandTotalTurnover, 2) }}</h2>
                                    <small class="text-white-50">ডিপোজিট + স্টক কেনা + বাজার বিক্রয় + প্রপার্টি বুকিং</small>
                                </div>
                                <div class="col-md-4 border-end-md mb-3 mb-md-0 ps-md-4">
                                    <small class="text-white-50 text-uppercase fw-bold">নেট ওয়ালেট রিজার্ভ (Vault Reserve)</small>
                                    <h2 class="fw-bold {{ $netVaultReserve >= 0 ? 'text-success' : 'text-danger' }} mb-1">৳{{ number_format($netVaultReserve, 2) }}</h2>
                                    <small class="text-white-50">অনুমোদিত ডিপোজিট - অনুমোদিত উইথড্র</small>
                                </div>
                                <div class="col-md-4 ps-md-4">
                                    <small class="text-white-50 text-uppercase fw-bold">জমি ও প্রপার্টি পোর্টফোলিও মূল্য</small>
                                    <h2 class="fw-bold text-info mb-1">৳{{ number_format($totalPropertyValuation, 2) }}</h2>
                                    <small class="text-white-50">মোট বুকিং ভলিউম: ৳{{ number_format($totalPropertyBookings, 2) }}</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Financial & System Metrics Cards -->
            <div class="row mb-4">
                <div class="col-xl-3 col-md-6 mb-3">
                    <div class="card border-0 shadow-sm bg-primary text-white h-100">
                        <div class="card-body p-3 d-flex align-items-center justify-content-between">
                            <div>
                                <small class="text-uppercase opacity-75 d-block">মোট অনুমোদিত ডিপোজিট</small>
                                <h3 class="fw-bold mb-0">৳{{ number_format($totalApprovedDeposits, 2) }}</h3>
                            </div>
                            <i class="fa fa-university fa-3x opacity-50"></i>
                        </div>
                    </div>
                </div>

                <div class="col-xl-3 col-md-6 mb-3">
                    <div class="card border-0 shadow-sm bg-danger text-white h-100">
                        <div class="card-body p-3 d-flex align-items-center justify-content-between">
                            <div>
                                <small class="text-uppercase opacity-75 d-block">মোট অনুমোদিত উত্তোলন</small>
                                <h3 class="fw-bold mb-0">৳{{ number_format($totalApprovedWithdraws, 2) }}</h3>
                            </div>
                            <i class="fa fa-credit-card fa-3x opacity-50"></i>
                        </div>
                    </div>
                </div>

                <div class="col-xl-3 col-md-6 mb-3">
                    <div class="card border-0 shadow-sm bg-success text-white h-100">
                        <div class="card-body p-3 d-flex align-items-center justify-content-between">
                            <div>
                                <small class="text-uppercase opacity-75 d-block">স্টক মার্কেট ক্রয় লেনদেন</small>
                                <h3 class="fw-bold mb-0">৳{{ number_format($totalStockPurchases, 2) }}</h3>
                            </div>
                            <i class="fa fa-cubes fa-3x opacity-50"></i>
                        </div>
                    </div>
                </div>

                <div class="col-xl-3 col-md-6 mb-3">
                    <div class="card border-0 shadow-sm bg-info text-white h-100">
                        <div class="card-body p-3 d-flex align-items-center justify-content-between">
                            <div>
                                <small class="text-uppercase opacity-75 d-block">মাসিক বাজার মোট বিক্রয়</small>
                                <h3 class="fw-bold mb-0">৳{{ number_format($totalMonthlyBazaarSales, 2) }}</h3>
                            </div>
                            <i class="fa fa-shopping-basket fa-3x opacity-50"></i>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Customer & Commercial Overview Row -->
            <div class="row mb-4">
                <div class="col-md-6">
                    <div class="box shadow-sm h-100">
                        <div class="box-header with-border bg-light">
                            <h5 class="box-title text-dark mb-0"><i class="fa fa-users me-2 text-info"></i> কাস্টমার সমারি (Customer Overview)</h5>
                        </div>
                        <div class="box-body">
                            <ul class="list-group list-group-flush">
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    সর্বমোট নিবন্ধিত গ্রাহক:
                                    <span class="badge bg-primary rounded-pill fs-6">{{ $totalCustomers }} জন</span>
                                </li>
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    নির্ধারিত সময়ের নতুন কাস্টমার:
                                    <span class="badge bg-success rounded-pill fs-6">{{ $newCustomers }} জন</span>
                                </li>
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    গ্রাহকদের বর্তমান মোট ওয়ালেট ব্যালেন্স:
                                    <strong class="text-dark fs-6">৳{{ number_format($totalCustomerBalance, 2) }}</strong>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="box shadow-sm h-100">
                        <div class="box-header with-border bg-light">
                            <h5 class="box-title text-dark mb-0"><i class="fa fa-calculator me-2 text-warning"></i> বাণিজ্যিক খাত অনুযায়ী লেনদেন</h5>
                        </div>
                        <div class="box-body">
                            <ul class="list-group list-group-flush">
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    খাদ্যপণ্য/মাসিক বাজার বিক্রয় সংগ্রহ:
                                    <strong class="text-success fs-6">৳{{ number_format($totalMonthlyBazaarSales, 2) }}</strong>
                                </li>
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    জমি ও প্রপার্টি বুকিং সংগ্রহ:
                                    <strong class="text-primary fs-6">৳{{ number_format($totalPropertyBookings, 2) }}</strong>
                                </li>
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    স্টক বিক্রয়/ক্রয় বাণিজ্য:
                                    <strong class="text-info fs-6">৳{{ number_format($totalStockPurchases, 2) }}</strong>
                                </li>
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    বিপণন এজেন্ট বিক্রয়:
                                    <strong class="text-dark fs-6">৳{{ number_format($totalAgentSales, 2) }}</strong>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Financial Ledger Table -->
            <div class="row">
                <div class="col-12">
                    <div class="box shadow-sm">
                        <div class="box-header with-border bg-light">
                            <h4 class="box-title text-dark"><i class="fa fa-list-alt me-2 text-primary"></i> সাম্প্রতিক সেন্ট্রাল ওয়ালেট লেজার হিস্ট্রি</h4>
                        </div>
                        <div class="box-body p-0">
                            <div class="table-responsive">
                                <table class="table table-hover align-middle mb-0">
                                    <thead class="table-light">
                                        <tr>
                                            <th>SN.</th>
                                            <th>Transaction ID</th>
                                            <th>কাস্টমার নাম & Card</th>
                                            <th>লেনদেনের ধরন</th>
                                            <th class="text-center">ক্রেডিট (+ ৳)</th>
                                            <th class="text-center">ডেবিট (- ৳)</th>
                                            <th class="text-center">নতুন ব্যালেন্স</th>
                                            <th>পেমেন্ট মেথড</th>
                                            <th class="text-center">তারিখ ও সময়</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($recentTransactions as $key => $tx)
                                            <tr>
                                                <td>{{ $key + 1 }}</td>
                                                <td><code class="text-dark fw-bold">{{ $tx->transaction_id }}</code></td>
                                                <td>
                                                    <strong>{{ $tx->user->name ?? 'N/A' }}</strong><br>
                                                    <small class="text-muted"><i class="fa fa-credit-card me-1"></i>{{ $tx->card_number ?? 'N/A' }}</small>
                                                </td>
                                                <td><span class="badge bg-secondary">{{ $tx->transaction_type }}</span></td>
                                                <td class="text-center">
                                                    @if($tx->credit_amount > 0)
                                                        <span class="fw-bold text-success">+ ৳{{ number_format($tx->credit_amount, 2) }}</span>
                                                    @else
                                                        <span class="text-muted">-</span>
                                                    @endif
                                                </td>
                                                <td class="text-center">
                                                    @if($tx->debit_amount > 0)
                                                        <span class="fw-bold text-danger">- ৳{{ number_format($tx->debit_amount, 2) }}</span>
                                                    @else
                                                        <span class="text-muted">-</span>
                                                    @endif
                                                </td>
                                                <td class="text-center fw-bold text-dark">৳{{ number_format($tx->new_balance, 2) }}</td>
                                                <td>{{ $tx->payment_method }}</td>
                                                <td class="text-center">{{ \Carbon\Carbon::parse($tx->created_at)->format('d/m/Y h:i A') }}</td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="9" class="text-center py-4 text-muted">কোনো ওয়ালেট লেজার রেকর্ড পাওয়া যায়নি।</td>
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
