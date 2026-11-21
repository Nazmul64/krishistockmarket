@extends('layouts.backend.app')

@section('content')
<div class="content-wrapper">
    <div class="container-full">
        <!-- Content Header -->
        <div class="content-header">
            <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
                <div>
                    <h3 class="page-title text-dark fw-bold mb-1">
                        <i class="ti-layout-grid2 text-success me-2"></i>মাল্টি-ব্রাঞ্চ বিজনেস ওভারভিউ (Multi-Branch Overview)
                    </h3>
                    <p class="text-muted mb-0">সকল শাখা, বিক্রয়, ইনভেন্টরি, খরচ, স্টাফ, বেতন ও লাভ-লোকসানের সমন্বিত কেন্দ্রীয় কন্ট্রোল ড্যাশবোর্ড</p>
                </div>
                <div class="d-flex gap-2 flex-wrap">
                    <a href="{{ route('admin.supershop.branches.comparison') }}" class="btn btn-sm btn-outline-primary fw-bold shadow-sm">
                        <i class="ti-stats-up me-1"></i> ব্রাঞ্চ পারফর্ম্যান্স তুলনা (Comparison)
                    </a>
                    <a href="{{ route('admin.supershop.inventory') }}" class="btn btn-sm btn-outline-info fw-bold shadow-sm">
                        <i class="ti-archive me-1"></i> সেন্ট্রাল ইনভেন্টরি
                    </a>
                    <button type="button" class="btn btn-sm btn-success fw-bold shadow-sm" data-bs-toggle="modal" data-bs-target="#createBranchModal">
                        <i class="ti-plus me-1"></i> নতুন ব্রাঞ্চ তৈরি করুন
                    </button>
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
            @if(session('warning'))
                <div class="alert alert-warning alert-dismissible fade show rounded-3 shadow-sm" role="alert">
                    <i class="fa fa-info-circle me-1"></i> {{ session('warning') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <!-- Section 2.2: Central Combined KPIs across all branches -->
            <div class="row g-3 mb-4">
                <div class="col-xl-2 col-md-4 col-6">
                    <div class="card border-0 shadow-sm rounded-4 h-100 p-3 bg-white">
                        <div class="d-flex align-items-center justify-content-between mb-1">
                            <span class="text-muted small fw-semibold">মোট ব্রাঞ্চ</span>
                            <span class="badge bg-primary-light text-primary rounded-pill px-2 py-0">সক্রিয়: {{ $activeBranches }}</span>
                        </div>
                        <div class="fs-4 fw-bold text-dark">{{ $totalBranches }} <span class="fs-6 text-muted fw-normal">টি</span></div>
                        <div class="small text-muted mt-1"><i class="fa fa-building text-primary me-1"></i>সকল আউটলেট</div>
                    </div>
                </div>

                <div class="col-xl-2 col-md-4 col-6">
                    <div class="card border-0 shadow-sm rounded-4 h-100 p-3 bg-white">
                        <div class="d-flex align-items-center justify-content-between mb-1">
                            <span class="text-muted small fw-semibold">আজকের মোট বিক্রয়</span>
                            <span class="badge bg-success-light text-success rounded-pill px-2 py-0">আজ</span>
                        </div>
                        <div class="fs-4 fw-bold text-success">৳ {{ number_format($todayAllBranchesSales, 2) }}</div>
                        <div class="small text-muted mt-1"><i class="fa fa-line-chart text-success me-1"></i>{{ $todayAllOrders }} টি অর্ডার</div>
                    </div>
                </div>

                <div class="col-xl-2 col-md-4 col-6">
                    <div class="card border-0 shadow-sm rounded-4 h-100 p-3 bg-white">
                        <div class="d-flex align-items-center justify-content-between mb-1">
                            <span class="text-muted small fw-semibold">চলতি মাসের বিক্রয়</span>
                            <span class="badge bg-info-light text-info rounded-pill px-2 py-0">এই মাস</span>
                        </div>
                        <div class="fs-4 fw-bold text-info">৳ {{ number_format($monthAllBranchesSales, 2) }}</div>
                        <div class="small text-muted mt-1"><i class="fa fa-calendar text-info me-1"></i>মাসিক টার্নওভার</div>
                    </div>
                </div>

                <div class="col-xl-2 col-md-4 col-6">
                    <div class="card border-0 shadow-sm rounded-4 h-100 p-3 bg-white">
                        <div class="d-flex align-items-center justify-content-between mb-1">
                            <span class="text-muted small fw-semibold">মোট খরচ (মাসিক)</span>
                            <span class="badge bg-danger-light text-danger rounded-pill px-2 py-0">ব্যয়</span>
                        </div>
                        <div class="fs-4 fw-bold text-danger">৳ {{ number_format($totalAllExpenses, 2) }}</div>
                        <div class="small text-muted mt-1"><i class="fa fa-credit-card text-danger me-1"></i>ভাড়া, বিদ্যুৎ ও অন্যান্য</div>
                    </div>
                </div>

                <div class="col-xl-2 col-md-4 col-6">
                    <div class="card border-0 shadow-sm rounded-4 h-100 p-3 bg-white">
                        <div class="d-flex align-items-center justify-content-between mb-1">
                            <span class="text-muted small fw-semibold">স্টাফ ও মাসিক বেতন</span>
                            <span class="badge bg-secondary-light text-dark rounded-pill px-2 py-0">{{ $totalStaff }} জন</span>
                        </div>
                        <div class="fs-4 fw-bold text-dark">৳ {{ number_format($monthlyPayroll, 2) }}</div>
                        <div class="small text-muted mt-1"><i class="fa fa-users text-secondary me-1"></i>সকল ব্রাঞ্চের পেরোল</div>
                    </div>
                </div>

                <div class="col-xl-2 col-md-4 col-6">
                    <div class="card border-0 shadow-sm rounded-4 h-100 p-3 bg-white">
                        <div class="d-flex align-items-center justify-content-between mb-1">
                            <span class="text-muted small fw-semibold">প্রকৃত লাভ (Net Profit)</span>
                            <span class="badge bg-{{ $netProfit >= 0 ? 'success' : 'danger' }}-light text-{{ $netProfit >= 0 ? 'success' : 'danger' }} rounded-pill px-2 py-0">নিট লাভ</span>
                        </div>
                        <div class="fs-4 fw-bold text-{{ $netProfit >= 0 ? 'success' : 'danger' }}">৳ {{ number_format($netProfit, 2) }}</div>
                        <div class="small text-muted mt-1"><i class="fa fa-pie-chart text-success me-1"></i>COGS ও ব্যয় বাদে</div>
                    </div>
                </div>
            </div>

            <!-- Filter & Search Bar -->
            <div class="box shadow-sm rounded-4 border-0 mb-4">
                <div class="box-body py-3 px-4">
                    <form method="GET" action="{{ route('admin.supershop.branches') }}" class="row g-2 align-items-center">
                        <div class="col-md-5 col-12">
                            <div class="input-group">
                                <span class="input-group-text bg-white border-end-0"><i class="ti-search text-muted"></i></span>
                                <input type="text" name="search" class="form-control border-start-0" placeholder="ব্রাঞ্চের নাম, কোড, ঠিকানা বা মোবাইল খুঁজুন..." value="{{ request('search') }}">
                            </div>
                        </div>
                        <div class="col-md-3 col-6">
                            <select name="status" class="form-select">
                                <option value="">সকল স্ট্যাটাস (All Status)</option>
                                <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>সক্রিয় (Active)</option>
                                <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>নিষ্ক্রিয় (Inactive)</option>
                            </select>
                        </div>
                        <div class="col-md-4 col-6 d-flex gap-2 justify-content-end">
                            <button type="submit" class="btn btn-primary fw-bold px-3">
                                <i class="ti-filter me-1"></i> ফিল্টার
                            </button>
                            <a href="{{ route('admin.supershop.branches') }}" class="btn btn-light border px-3">
                                রিসেট
                            </a>
                            <div class="btn-group" role="group">
                                <button type="button" class="btn btn-outline-secondary active" id="btnViewCards" title="কার্ড ভিউ"><i class="ti-layout-grid2"></i></button>
                                <button type="button" class="btn btn-outline-secondary" id="btnViewTable" title="টেবিল ভিউ"><i class="ti-view-list-alt"></i></button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Section 2.1 & 2.3: Branch Cards Grid View (Clicking opens branch details) -->
            <div id="branchCardsContainer" class="row g-4 mb-4">
                @forelse($branches as $branch)
                    <div class="col-xl-4 col-md-6 col-12 branch-card-item">
                        <div class="card border-0 shadow-sm rounded-4 h-100 overflow-hidden branch-card-hover bg-white transition-all position-relative" style="border-top: 4px solid #10b981 !important;">
                            <!-- Top Card Header -->
                            <div class="card-body p-4">
                                <div class="d-flex align-items-start justify-content-between mb-3">
                                    <div class="d-flex align-items-center gap-3">
                                        <div class="rounded-3 border overflow-hidden d-flex align-items-center justify-content-center bg-light shadow-sm" style="width: 56px; height: 56px; min-width: 56px;">
                                            @if($branch->logo && file_exists(public_path($branch->logo)))
                                                <img src="{{ asset($branch->logo) }}" alt="{{ $branch->name }}" style="width: 100%; height: 100%; object-fit: cover;">
                                            @else
                                                <i class="ti-home text-success fs-3"></i>
                                            @endif
                                        </div>
                                        <div>
                                            <a href="{{ route('admin.supershop.branches.show', $branch->id) }}" class="text-decoration-none">
                                                <h5 class="fw-bold text-dark mb-1 hover-primary">{{ $branch->name }}</h5>
                                            </a>
                                            <div class="d-flex align-items-center gap-2">
                                                <span class="badge bg-dark text-white fw-bold px-2 py-1">{{ $branch->code }}</span>
                                                @if($branch->status == 'active')
                                                    <span class="badge bg-success-light text-success rounded-pill px-2 py-0"><i class="fa fa-circle text-success me-1 small"></i>সক্রিয়</span>
                                                @else
                                                    <span class="badge bg-secondary-light text-muted rounded-pill px-2 py-0"><i class="fa fa-circle text-muted me-1 small"></i>নিষ্ক্রিয়</span>
                                                @endif
                                                <span class="badge bg-light text-dark border">{{ $branch->type == 'super_shop' ? 'সুপারশপ' : 'আউটলেট' }}</span>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="dropdown">
                                        <button class="btn btn-sm btn-light border-0 rounded-circle" type="button" data-bs-toggle="dropdown">
                                            <i class="ti-more-alt text-muted"></i>
                                        </button>
                                        <ul class="dropdown-menu dropdown-menu-end shadow border-0 rounded-3">
                                            <li>
                                                <a class="dropdown-item py-2" href="{{ route('admin.supershop.branches.show', $branch->id) }}">
                                                    <i class="ti-dashboard text-success me-2"></i>বিস্তারিত ড্যাশবোর্ড
                                                </a>
                                            </li>
                                            <li>
                                                <a class="dropdown-item py-2" href="{{ route('admin.supershop.pos', ['branch_id' => $branch->id]) }}">
                                                    <i class="ti-shopping-cart text-warning me-2"></i>POS কাউন্টার খুলুন
                                                </a>
                                            </li>
                                            <li>
                                                <a class="dropdown-item py-2" href="{{ route('admin.supershop.inventory', ['branch_id' => $branch->id]) }}">
                                                    <i class="ti-archive text-info me-2"></i>স্টক ও ইনভেন্টরি
                                                </a>
                                            </li>
                                            <li><hr class="dropdown-divider"></li>
                                            <li>
                                                <button type="button" class="dropdown-item py-2 text-primary" data-bs-toggle="modal" data-bs-target="#editBranchModal{{ $branch->id }}">
                                                    <i class="ti-pencil me-2"></i>ব্রাঞ্চ সেটিংস এডিট
                                                </button>
                                            </li>
                                            @if($branch->manager)
                                                <li>
                                                    <button type="button" class="dropdown-item py-2 text-info" data-bs-toggle="modal" data-bs-target="#managerCredentialsModal{{ $branch->id }}">
                                                        <i class="ti-key me-2"></i>ম্যানেজার পাসওয়ার্ড
                                                    </button>
                                                </li>
                                            @endif
                                        </ul>
                                    </div>
                                </div>

                                <!-- Location & Manager Info -->
                                <div class="bg-light rounded-3 p-3 mb-3">
                                    <div class="row g-2 small">
                                        <div class="col-12 text-truncate text-muted">
                                            <i class="ti-location-pin text-danger me-1"></i>
                                            <span>{{ $branch->address ?: 'ঠিকানা নির্ধারিত হয়নি' }}</span>
                                        </div>
                                        <div class="col-6 text-muted">
                                            <i class="ti-user text-primary me-1"></i>
                                            <span class="text-dark fw-semibold">{{ $branch->manager ? $branch->manager->name : 'ম্যানেজার নেই' }}</span>
                                        </div>
                                        <div class="col-6 text-muted text-end">
                                            <i class="ti-mobile text-success me-1"></i>
                                            <span>{{ $branch->phone ?: ($branch->manager ? $branch->manager->phone : 'N/A') }}</span>
                                        </div>
                                    </div>
                                </div>

                                <!-- Key Metrics Grid (Section 2.1) -->
                                <div class="row g-2 mb-3 text-center">
                                    <div class="col-4">
                                        <div class="p-2 border rounded-3 bg-white">
                                            <div class="smaller text-muted">আজকের বিক্রয়</div>
                                            <div class="fs-6 fw-bold text-success">৳ {{ number_format($branch->today_sales) }}</div>
                                            <div class="smallest text-muted">{{ $branch->today_orders }} টি অর্ডার</div>
                                        </div>
                                    </div>
                                    <div class="col-4">
                                        <div class="p-2 border rounded-3 bg-white">
                                            <div class="smaller text-muted">আজকের খরচ</div>
                                            <div class="fs-6 fw-bold text-danger">৳ {{ number_format($branch->today_expenses) }}</div>
                                            <div class="smallest text-muted">আউটলেট ব্যয়</div>
                                        </div>
                                    </div>
                                    <div class="col-4">
                                        <div class="p-2 border rounded-3 bg-white">
                                            <div class="smaller text-muted">ক্যাশ কালেকশন</div>
                                            <div class="fs-6 fw-bold text-info">৳ {{ number_format($branch->today_cash) }}</div>
                                            <div class="smallest text-muted">নগদ গ্রহণ</div>
                                        </div>
                                    </div>
                                </div>

                                <div class="row g-2 mb-3 text-center">
                                    <div class="col-4">
                                        <div class="p-2 border rounded-3 bg-white">
                                            <div class="smaller text-muted">মোট স্টাফ</div>
                                            <div class="fs-6 fw-bold text-dark">{{ $branch->employees_count }} জন</div>
                                            <div class="smallest text-muted">বেতন: ৳{{ number_format($branch->monthly_payroll) }}</div>
                                        </div>
                                    </div>
                                    <div class="col-4">
                                        <div class="p-2 border rounded-3 bg-white">
                                            <div class="smaller text-muted">পণ্য ও স্টক</div>
                                            <div class="fs-6 fw-bold text-dark">{{ $branch->total_stock_units }} <span class="smallest">টি</span></div>
                                            <div class="smallest text-muted">{{ $branch->total_products_assigned }} টি পণ্য</div>
                                        </div>
                                    </div>
                                    <div class="col-4">
                                        <div class="p-2 border rounded-3 bg-white">
                                            <div class="smaller text-muted">মাসিক টার্নওভার</div>
                                            <div class="fs-6 fw-bold text-primary">৳ {{ number_format($branch->monthly_sales) }}</div>
                                            <div class="smallest text-muted">চলতি মাস</div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Action Button: Click opens detailed dashboard -->
                                <a href="{{ route('admin.supershop.branches.show', $branch->id) }}" class="btn btn-outline-success w-100 fw-bold rounded-pill py-2 d-flex align-items-center justify-content-center gap-2 shadow-sm">
                                    <span>ব্রাঞ্চের সম্পূর্ণ হিসাব ও ড্যাশবোর্ড দেখুন</span>
                                    <i class="ti-arrow-right"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-12">
                        <div class="card border-0 shadow-sm rounded-4 p-5 text-center bg-white">
                            <i class="ti-home text-muted fs-1 mb-3"></i>
                            <h4 class="fw-bold text-dark">কোনো ব্রাঞ্চ খুঁজে পাওয়া যায়নি!</h4>
                            <p class="text-muted">আপনার খোঁজের সাথে মেলেনি অথবা এখনো কোনো শাখা তৈরি করা হয়নি।</p>
                            <div class="mt-2">
                                <button type="button" class="btn btn-success fw-bold px-4 rounded-pill" data-bs-toggle="modal" data-bs-target="#createBranchModal">
                                    <i class="ti-plus me-1"></i> নতুন ব্রাঞ্চ যোগ করুন
                                </button>
                            </div>
                        </div>
                    </div>
                @endforelse
            </div>

            <!-- Tabular View (Hidden by default, toggleable) -->
            <div id="branchTableContainer" class="box shadow-sm rounded-4 border-0 mb-4 d-none">
                <div class="box-header with-border py-3 px-4 d-flex justify-content-between align-items-center">
                    <h5 class="box-title fw-bold text-dark mb-0">সকল শাখার তালিকা (টেবুলার ভিউ)</h5>
                    <span class="badge bg-success-light text-success fw-bold px-3 py-1">মোট {{ $branches->count() }} টি শাখা</span>
                </div>
                <div class="box-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0 align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th class="ps-4">ব্রাঞ্চ ও কোড</th>
                                    <th>ঠিকানা ও যোগাযোগ</th>
                                    <th>ম্যানেজার</th>
                                    <th>স্টাফ</th>
                                    <th>স্টক ইউনিট</th>
                                    <th class="text-end">আজকের বিক্রয়</th>
                                    <th class="text-end">মাসিক বিক্রয়</th>
                                    <th class="text-end">আজকের খরচ</th>
                                    <th class="text-center">স্ট্যাটাস</th>
                                    <th class="text-end pe-4">অ্যাকশন</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($branches as $b)
                                    <tr>
                                        <td class="ps-4">
                                            <a href="{{ route('admin.supershop.branches.show', $b->id) }}" class="fw-bold text-dark text-decoration-none hover-primary">
                                                {{ $b->name }}
                                            </a>
                                            <div class="smaller text-muted"><span class="badge bg-dark">{{ $b->code }}</span> | ID: #{{ $b->id }}</div>
                                        </td>
                                        <td>
                                            <div class="small text-muted">{{ Str::limit($b->address, 32) }}</div>
                                            <div class="smaller text-success"><i class="ti-mobile me-1"></i>{{ $b->phone }}</div>
                                        </td>
                                        <td>
                                            <div class="fw-semibold text-dark small">{{ $b->manager ? $b->manager->name : 'N/A' }}</div>
                                            <div class="smaller text-muted">{{ $b->manager ? $b->manager->username : '' }}</div>
                                        </td>
                                        <td><span class="badge bg-light text-dark border">{{ $b->employees_count }} জন</span></td>
                                        <td><span class="badge bg-info-light text-info fw-bold">{{ $b->total_stock_units }}</span></td>
                                        <td class="text-end fw-bold text-success">৳ {{ number_format($b->today_sales, 2) }}</td>
                                        <td class="text-end fw-bold text-primary">৳ {{ number_format($b->monthly_sales, 2) }}</td>
                                        <td class="text-end fw-bold text-danger">৳ {{ number_format($b->today_expenses, 2) }}</td>
                                        <td class="text-center">
                                            <span class="badge bg-{{ $b->status == 'active' ? 'success' : 'secondary' }} rounded-pill px-3 py-1">
                                                {{ $b->status == 'active' ? 'সক্রিয়' : 'নিষ্ক্রিয়' }}
                                            </span>
                                        </td>
                                        <td class="text-end pe-4">
                                            <a href="{{ route('admin.supershop.branches.show', $b->id) }}" class="btn btn-xs btn-success rounded-pill px-3" title="ড্যাশবোর্ড">
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

            <!-- Create Branch Modal (Section 19 & 21) -->
            <div class="modal fade" id="createBranchModal" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content rounded-4 border-0 shadow">
                        <div class="modal-header bg-success text-white rounded-top-4 py-3">
                            <h5 class="modal-title fw-bold text-white">
                                <i class="ti-plus me-2"></i>নতুন শাখা তৈরি করুন (Create New Branch)
                            </h5>
                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <form action="{{ route('admin.supershop.branches.store') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="modal-body p-4">
                                <div class="row g-3">
                                    <div class="col-md-6 col-12">
                                        <label class="form-label fw-bold small text-dark">শাখার নাম (Branch Name) <span class="text-danger">*</span></label>
                                        <input type="text" name="name" class="form-control" placeholder="যেমন: Branch 07 – Gazipur" required>
                                    </div>
                                    <div class="col-md-3 col-6">
                                        <label class="form-label fw-bold small text-dark">শাখা কোড (Branch Code) <span class="text-danger">*</span></label>
                                        <input type="text" name="code" class="form-control text-uppercase" placeholder="BR-07" required>
                                    </div>
                                    <div class="col-md-3 col-6">
                                        <label class="form-label fw-bold small text-dark">শাখার ধরন (Type) <span class="text-danger">*</span></label>
                                        <select name="type" class="form-select" required>
                                            <option value="super_shop">সুপারশপ (Super Shop)</option>
                                            <option value="retail_outlet">রিটেল আউটলেট (Retail Outlet)</option>
                                            <option value="warehouse">ওয়্যারহাউজ (Warehouse)</option>
                                        </select>
                                    </div>

                                    <div class="col-md-6 col-12">
                                        <label class="form-label fw-bold small text-dark">যোগাযোগের ফোন নম্বর (Phone)</label>
                                        <input type="text" name="phone" class="form-control" placeholder="017XXXXXXXX">
                                    </div>
                                    <div class="col-md-6 col-12">
                                        <label class="form-label fw-bold small text-dark">ইমেইল (Email Address)</label>
                                        <input type="email" name="email" class="form-control" placeholder="branch07@ekrishisheba.com">
                                    </div>

                                    <div class="col-12">
                                        <label class="form-label fw-bold small text-dark">সম্পূর্ণ ঠিকানা ও লোকেশন (Full Address) <span class="text-danger">*</span></label>
                                        <textarea name="address" rows="2" class="form-control" placeholder="দোকান নম্বর, রোড, এলাকা, জেলা..." required></textarea>
                                    </div>

                                    <div class="col-md-4 col-12">
                                        <label class="form-label fw-bold small text-dark">ওপেনিং ক্যাশ ব্যালেন্স (৳)</label>
                                        <input type="number" name="opening_cash" class="form-control" value="10000" min="0">
                                    </div>
                                    <div class="col-md-4 col-6">
                                        <label class="form-label fw-bold small text-dark">লো-স্টক সতর্কবার্তা লিমিট</label>
                                        <input type="number" name="low_stock_threshold" class="form-control" value="10" min="1">
                                    </div>
                                    <div class="col-md-4 col-6">
                                        <label class="form-label fw-bold small text-dark">খরচ অনুমোদন সীমা (৳)</label>
                                        <input type="number" name="expense_approval_limit" class="form-control" value="15000" min="0">
                                    </div>

                                    <div class="col-12"><hr class="my-2"></div>
                                    
                                    <!-- ব্রাঞ্চ ম্যানেজার ও লগইন ক্রেডেনশিয়াল (Login Email & Password) -->
                                    <div class="col-12">
                                        <div class="p-3 rounded-4 bg-primary bg-opacity-10 border border-primary border-opacity-25">
                                            <div class="d-flex align-items-center justify-content-between mb-2 flex-wrap gap-2">
                                                <h6 class="fw-bold text-primary mb-0">
                                                    <i class="ti-key me-1"></i> ব্রাঞ্চ লগইন ক্রেডেনশিয়াল (Manager Login Email & Password)
                                                </h6>
                                                <span class="badge bg-primary text-white rounded-pill px-3 py-1">মাস্ট লগইন অ্যাকাউন্ট</span>
                                            </div>
                                            <p class="text-muted small mb-3">
                                                <i class="fa fa-info-circle text-primary me-1"></i>
                                                এখানে যে <strong>মেইল (Login Email)</strong> এবং <strong>পাসওয়ার্ড (Password)</strong> দেওয়া হবে, ওই ব্রাঞ্চ ম্যানেজার সিস্টেমে লগইন করে সরাসরি তার নিজস্ব ব্রাঞ্চের হিসাব দেখতে পারবে।
                                            </p>
                                            <div class="row g-3">
                                                <div class="col-md-6 col-12">
                                                    <label class="form-label fw-bold small text-dark">
                                                        ম্যানেজার লগইন ইমেইল (Login Email) <span class="text-danger">*</span>
                                                    </label>
                                                    <input type="email" name="manager_email" class="form-control bg-white" placeholder="manager.br01@ekrishisheba.com" required>
                                                    <div class="smaller text-muted mt-1">ম্যানেজার এই ইমেইল দিয়ে সিস্টেমে লগইন করবে।</div>
                                                </div>
                                                <div class="col-md-6 col-12">
                                                    <label class="form-label fw-bold small text-dark">
                                                        লগইন পাসওয়ার্ড (Login Password) <span class="text-danger">*</span>
                                                    </label>
                                                    <input type="password" name="manager_password" class="form-control bg-white" placeholder="কমপক্ষে ৬ ডিজিটের পাসওয়ার্ড প্রদান করুন" required minlength="6">
                                                    <div class="smaller text-muted mt-1">লগইন পাসওয়ার্ড গোপন ও নিরাপদ রাখুন।</div>
                                                </div>
                                                <div class="col-md-4 col-12">
                                                    <label class="form-label fw-bold small text-dark">ম্যানেজারের পুরো নাম</label>
                                                    <input type="text" name="manager_name" class="form-control bg-white" placeholder="যেমন: মোঃ আরিফুল ইসলাম">
                                                </div>
                                                <div class="col-md-4 col-6">
                                                    <label class="form-label fw-bold small text-dark">ইউজারনেম (Username - ঐচ্ছিক)</label>
                                                    <input type="text" name="manager_username" class="form-control bg-white" placeholder="খালি রাখলে ইমেইল থেকে অটো হবে">
                                                </div>
                                                <div class="col-md-4 col-6">
                                                    <label class="form-label fw-bold small text-dark">মোবাইল নম্বর (Phone)</label>
                                                    <input type="text" name="manager_phone" class="form-control bg-white" placeholder="017XXXXXXXX">
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-6 col-12">
                                        <label class="form-label fw-bold small text-dark">ব্রাঞ্চ লোগো বা ছবি</label>
                                        <input type="file" name="logo" class="form-control" accept="image/*">
                                    </div>

                                    <div class="col-md-6 col-12 d-flex align-items-center">
                                        <div class="form-check mt-3">
                                            <input class="form-check-input" type="checkbox" name="assign_all_products" id="assignAllProducts" value="1" checked>
                                            <label class="form-check-label fw-semibold text-dark small" for="assignAllProducts">
                                                মূল সেন্ট্রাল ক্যাটালগের প্রোডাক্টসমূহ স্বয়ংক্রিয়ভাবে এই ব্রাঞ্চে ম্যাপ করুন (Default Stock সহ)
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer bg-light rounded-bottom-4 py-3">
                                <button type="button" class="btn btn-secondary px-4 rounded-pill" data-bs-dismiss="modal">বাতিল</button>
                                <button type="submit" class="btn btn-success px-4 rounded-pill fw-bold">
                                    <i class="ti-check me-1"></i> ব্রাঞ্চ তৈরি ও সক্রিয় করুন
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Modals for Edit Branch & Reset Password -->
            @foreach($branches as $b)
                <!-- Edit Branch Modal -->
                <div class="modal fade" id="editBranchModal{{ $b->id }}" tabindex="-1" aria-hidden="true">
                    <div class="modal-dialog modal-lg">
                        <div class="modal-content rounded-4 border-0 shadow">
                            <div class="modal-header bg-primary text-white rounded-top-4 py-3">
                                <h5 class="modal-title fw-bold text-white">
                                    <i class="ti-pencil me-2"></i>ব্রাঞ্চ সেটিংস এডিট – {{ $b->name }}
                                </h5>
                                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <form action="{{ route('admin.supershop.branches.update', $b->id) }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                <div class="modal-body p-4">
                                    <div class="row g-3">
                                        <div class="col-md-6 col-12">
                                            <label class="form-label fw-bold small text-dark">শাখার নাম</label>
                                            <input type="text" name="name" class="form-control" value="{{ $b->name }}" required>
                                        </div>
                                        <div class="col-md-3 col-6">
                                            <label class="form-label fw-bold small text-dark">শাখা কোড</label>
                                            <input type="text" name="code" class="form-control text-uppercase" value="{{ $b->code }}" required>
                                        </div>
                                        <div class="col-md-3 col-6">
                                            <label class="form-label fw-bold small text-dark">স্ট্যাটাস</label>
                                            <select name="status" class="form-select">
                                                <option value="active" {{ $b->status == 'active' ? 'selected' : '' }}>সক্রিয় (Active)</option>
                                                <option value="inactive" {{ $b->status == 'inactive' ? 'selected' : '' }}>নিষ্ক্রিয় (Inactive)</option>
                                            </select>
                                        </div>
                                        <div class="col-md-6 col-12">
                                            <label class="form-label fw-bold small text-dark">মোবাইল</label>
                                            <input type="text" name="phone" class="form-control" value="{{ $b->phone }}">
                                        </div>
                                        <div class="col-md-6 col-12">
                                            <label class="form-label fw-bold small text-dark">ইমেইল</label>
                                            <input type="email" name="email" class="form-control" value="{{ $b->email }}">
                                        </div>
                                        <div class="col-12">
                                            <label class="form-label fw-bold small text-dark">ঠিকানা</label>
                                            <textarea name="address" rows="2" class="form-control" required>{{ $b->address }}</textarea>
                                        </div>
                                        <div class="col-md-4 col-12">
                                            <label class="form-label fw-bold small text-dark">ওপেনিং ক্যাশ ব্যালেন্স (৳)</label>
                                            <input type="number" name="opening_cash" class="form-control" value="{{ $b->opening_cash ?: 10000 }}">
                                        </div>
                                        <div class="col-md-4 col-6">
                                            <label class="form-label fw-bold small text-dark">লো-স্টক লিমিট</label>
                                            <input type="number" name="low_stock_threshold" class="form-control" value="{{ $b->low_stock_threshold ?: 10 }}">
                                        </div>
                                        <div class="col-md-4 col-6">
                                            <label class="form-label fw-bold small text-dark">খরচ অনুমোদন সীমা (৳)</label>
                                            <input type="number" name="expense_approval_limit" class="form-control" value="{{ $b->expense_approval_limit ?: 15000 }}">
                                        </div>
                                        <div class="col-md-6 col-12">
                                            <label class="form-label fw-bold small text-dark">ম্যানেজার নির্ধারণ</label>
                                            <select name="manager_id" class="form-select">
                                                <option value="">-- ম্যানেজার নির্বাচন করুন --</option>
                                                @foreach($users as $u)
                                                    <option value="{{ $u->id }}" {{ $b->manager_id == $u->id ? 'selected' : '' }}>{{ $u->name }} ({{ $u->username }})</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-md-6 col-12">
                                            <label class="form-label fw-bold small text-dark">নতুন লোগো/ছবি পরিবর্তন</label>
                                            <input type="file" name="logo" class="form-control" accept="image/*">
                                        </div>
                                    </div>
                                </div>
                                <div class="modal-footer bg-light rounded-bottom-4 py-3">
                                    <button type="button" class="btn btn-secondary px-4 rounded-pill" data-bs-dismiss="modal">বাতিল</button>
                                    <button type="submit" class="btn btn-primary px-4 rounded-pill fw-bold">আপডেট সংরক্ষণ করুন</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <!-- Manager Password Reset Modal -->
                @if($b->manager)
                    <div class="modal fade" id="managerCredentialsModal{{ $b->id }}" tabindex="-1" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content rounded-4 border-0 shadow">
                                <div class="modal-header bg-dark text-white rounded-top-4 py-3">
                                    <h5 class="modal-title fw-bold text-white">
                                        <i class="ti-key me-2 text-warning"></i>ম্যানেজার পাসওয়ার্ড রিসেট – {{ $b->name }}
                                    </h5>
                                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <form action="{{ route('admin.supershop.branches.reset_password', $b->id) }}" method="POST">
                                    @csrf
                                    <div class="modal-body p-4">
                                        <div class="mb-3">
                                            <label class="form-label fw-bold small text-muted">ম্যানেজার ইউজারনেম</label>
                                            <input type="text" class="form-control bg-light" value="{{ $b->manager->username }}" readonly>
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label fw-bold small text-dark">নতুন পাসওয়ার্ড লিখুন <span class="text-danger">*</span></label>
                                            <input type="password" name="new_password" class="form-control" placeholder="কমপক্ষে ৬ অক্ষর..." required minlength="6">
                                        </div>
                                    </div>
                                    <div class="modal-footer bg-light rounded-bottom-4 py-3">
                                        <button type="button" class="btn btn-secondary rounded-pill" data-bs-dismiss="modal">বাতিল</button>
                                        <button type="submit" class="btn btn-warning rounded-pill fw-bold">পাসওয়ার্ড রিসেট করুন</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                @endif
            @endforeach
        </section>
    </div>
</div>

<style>
.branch-card-hover {
    transition: transform 0.2s ease, box-shadow 0.2s ease;
}
.branch-card-hover:hover {
    transform: translateY(-4px);
    box-shadow: 0 12px 28px rgba(0,0,0,0.08) !important;
}
.hover-primary:hover {
    color: #10b981 !important;
}
.smallest {
    font-size: 11px;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const btnCards = document.getElementById('btnViewCards');
    const btnTable = document.getElementById('btnViewTable');
    const containerCards = document.getElementById('branchCardsContainer');
    const containerTable = document.getElementById('branchTableContainer');

    if (btnCards && btnTable && containerCards && containerTable) {
        btnCards.addEventListener('click', function() {
            btnCards.classList.add('active');
            btnTable.classList.remove('active');
            containerCards.classList.remove('d-none');
            containerTable.classList.add('d-none');
        });

        btnTable.addEventListener('click', function() {
            btnTable.classList.add('active');
            btnCards.classList.remove('active');
            containerTable.classList.remove('d-none');
            containerCards.classList.add('d-none');
        });
    }
});
</script>
@endsection
