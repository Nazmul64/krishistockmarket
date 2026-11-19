@extends('layouts.backend.app')

@section('content')
<div class="content-wrapper">
    <div class="container-full">
        <!-- Content Header -->
        <div class="content-header">
            <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
                <div>
                    <h3 class="page-title text-dark fw-bold mb-1">
                        <i class="ti-home text-success me-2"></i>ব্রাঞ্চ ও রিটেল আউটলেট ম্যানেজমেন্ট (Multi-Branch Management)
                    </h3>
                    <p class="text-muted mb-0">সেন্ট্রাল অ্যাডমিন থেকে সুপারশপ চেইনের সকল শাখা, আউটলেট, ইউজার ক্রেডেনশিয়াল ও বিক্রয় হিসাব পরিচালনা</p>
                </div>
                <div class="d-flex gap-2">
                    <a href="{{ route('admin.supershop.pos') }}" class="btn btn-sm btn-outline-success fw-bold">
                        <i class="ti-shopping-cart me-1"></i> POS কাউন্টার টার্মিনাল
                    </a>
                    <a href="{{ route('admin.supershop.inventory') }}" class="btn btn-sm btn-outline-info fw-bold">
                        <i class="ti-archive me-1"></i> সেন্ট্রাল ইনভেন্টরি
                    </a>
                    <button type="button" class="btn btn-sm btn-success fw-bold shadow-sm" data-bs-toggle="modal" data-bs-target="#createBranchModal">
                        <i class="ti-plus me-1"></i> নতুন ব্রাঞ্চ যোগ করুন
                    </button>
                </div>
            </div>
        </div>

        <!-- Main Content -->
        <section class="content">
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="fa fa-check-circle me-1"></i> {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif
            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="fa fa-exclamation-triangle me-1"></i> {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif
            @if($errors->any())
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <ul class="mb-0">
                        @foreach($errors->all() as $err)
                            <li>{{ $err }}</li>
                        @endforeach
                    </ul>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <!-- 1. Real-time Branch Sales & Accounting Summary Cards -->
            <div class="row g-3 mb-4">
                <div class="col-xl-3 col-md-6 col-12">
                    <div class="card border-0 shadow-sm rounded-4 h-100 p-3 bg-white">
                        <div class="d-flex align-items-center justify-content-between mb-2">
                            <span class="text-muted small fw-semibold">সক্রিয় ব্রাঞ্চ সংখ্যা</span>
                            <span class="badge bg-primary-light text-primary rounded-pill px-3 py-1">মোট {{ $totalBranchesCount }} টি</span>
                        </div>
                        <div class="fs-4 fw-bold text-dark">{{ $activeBranchesCount }} <span class="fs-6 text-muted fw-normal">টি আউটলেট</span></div>
                        <div class="small text-muted mt-1"><i class="fa fa-building me-1 text-primary"></i>সেন্ট্রাল রিটেল নেটওয়ার্ক</div>
                    </div>
                </div>

                <div class="col-xl-3 col-md-6 col-12">
                    <div class="card border-0 shadow-sm rounded-4 h-100 p-3 bg-white">
                        <div class="d-flex align-items-center justify-content-between mb-2">
                            <span class="text-muted small fw-semibold">আজকের মোট ব্রাঞ্চ বিক্রি</span>
                            <span class="badge bg-success-light text-success rounded-pill px-2 py-1">আজকের দিন</span>
                        </div>
                        <div class="fs-4 fw-bold text-success">৳ {{ number_format($todayAllBranchesSales, 2) }}</div>
                        <div class="small text-muted mt-1"><i class="fa fa-line-chart me-1 text-success"></i>সকল শাখার দৈনিক লেনদেন</div>
                    </div>
                </div>

                <div class="col-xl-3 col-md-6 col-12">
                    <div class="card border-0 shadow-sm rounded-4 h-100 p-3 bg-white">
                        <div class="d-flex align-items-center justify-content-between mb-2">
                            <span class="text-muted small fw-semibold">সর্বমোট ব্রাঞ্চ টার্নওভার</span>
                            <span class="badge bg-info-light text-info rounded-pill px-2 py-1">লাইফটাইম</span>
                        </div>
                        <div class="fs-4 fw-bold text-info">৳ {{ number_format($totalAllBranchesSales, 2) }}</div>
                        <div class="small text-muted mt-1"><i class="fa fa-money me-1 text-info"></i>মোট সংগৃহীত রাজস্ব</div>
                    </div>
                </div>

                <div class="col-xl-3 col-md-6 col-12">
                    <div class="card border-0 shadow-sm rounded-4 h-100 p-3 bg-white">
                        <div class="d-flex align-items-center justify-content-between mb-2">
                            <span class="text-muted small fw-semibold">সেন্ট্রাল ই-কমার্স ক্যাটালগ</span>
                            <span class="badge bg-warning-light text-warning rounded-pill px-2 py-1">১০০% সিঙ্কড</span>
                        </div>
                        <div class="fs-4 fw-bold text-warning">{{ number_format($totalEcomProducts) }} <span class="fs-6 text-muted fw-normal">টি পণ্য</span></div>
                        <div class="small text-muted mt-1"><i class="fa fa-check-circle me-1 text-warning"></i>প্রত্যেক ব্রাঞ্চে বিদ্যমান</div>
                    </div>
                </div>
            </div>

            <!-- 2. Branches Table -->
            <div class="row">
                <div class="col-12">
                    <div class="box shadow-sm rounded-4 border-0">
                        <div class="box-header with-border d-flex justify-content-between align-items-center flex-wrap gap-2 py-3 px-4">
                            <div>
                                <h4 class="box-title fw-bold text-dark mb-0">সকল শাখা ও বিক্রয় হিসাব ({{ $branches->count() }} টি ব্রাঞ্চ)</h4>
                                <small class="text-muted">প্রতিটি ব্রাঞ্চের সেলস রেকর্ড, স্টক এবং ম্যানেজার লগইন ক্রেডেনশিয়াল পরিচালনা করুন</small>
                            </div>
                            <span class="badge bg-success-light text-success fw-bold px-3 py-2">
                                <i class="fa fa-refresh me-1"></i> সেন্ট্রাল ক্যাটালগ ইন্টিগ্রেটেড
                            </span>
                        </div>
                        <div class="box-body p-0">
                            <div class="table-responsive">
                                <table class="table table-hover mb-0 align-middle">
                                    <thead class="table-light">
                                        <tr>
                                            <th class="ps-4">ব্রাঞ্চ ছবি ও কোড</th>
                                            <th>ব্রাঞ্চের নাম ও ঠিকানা</th>
                                            <th>ধরন (Type)</th>
                                            <th>ব্রাঞ্চ ম্যানেজার ও লগইন</th>
                                            <th class="text-end">আজকের বিক্রয় (Today)</th>
                                            <th class="text-end">সর্বমোট বিক্রয় (Total)</th>
                                            <th class="text-center">ইনভেন্টরি পণ্য</th>
                                            <th class="text-center">স্ট্যাটাস</th>
                                            <th class="text-end pe-4">অ্যাকশন</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($branches as $branch)
                                            <tr>
                                                <!-- Branch Image & Code -->
                                                <td class="ps-4">
                                                    <div class="d-flex align-items-center gap-2">
                                                        <div class="rounded-3 border overflow-hidden d-flex align-items-center justify-content-center bg-light" style="width: 48px; height: 48px; min-width: 48px;">
                                                            @if($branch->logo && file_exists(public_path($branch->logo)))
                                                                <img src="{{ asset($branch->logo) }}" alt="{{ $branch->name }}" style="width: 100%; height: 100%; object-fit: cover;">
                                                            @else
                                                                <i class="ti-home text-secondary fs-4"></i>
                                                            @endif
                                                        </div>
                                                        <div>
                                                            <span class="badge bg-dark fw-bold">{{ $branch->code }}</span>
                                                            <div class="smaller text-muted mt-1">ID: #{{ $branch->id }}</div>
                                                        </div>
                                                    </div>
                                                </td>

                                                <!-- Name & Address -->
                                                <td>
                                                    <strong class="text-dark fs-6">{{ $branch->name }}</strong>
                                                    <div class="small text-muted"><i class="ti-location-pin text-danger me-1"></i>{{ Str::limit($branch->address, 38) }}</div>
                                                    @if($branch->phone)
                                                        <div class="small text-muted"><i class="ti-mobile text-success me-1"></i>{{ $branch->phone }}</div>
                                                    @endif
                                                </td>

                                                <!-- Type -->
                                                <td>
                                                    @if($branch->type == 'super_shop')
                                                        <span class="badge bg-primary-light text-primary fw-bold">সুপারশপ</span>
                                                    @elseif($branch->type == 'warehouse')
                                                        <span class="badge bg-warning-light text-dark fw-bold">ওয়্যারহাউজ</span>
                                                    @else
                                                        <span class="badge bg-info-light text-info fw-bold">আউটলেট</span>
                                                    @endif
                                                </td>

                                                <!-- Manager & Login Credentials -->
                                                <td>
                                                    @if($branch->manager)
                                                        <div class="fw-bold text-dark small">{{ $branch->manager->name }}</div>
                                                        <div class="text-muted smaller">
                                                            <code>{{ $branch->manager->username }}</code>
                                                        </div>
                                                        <button type="button" class="btn btn-xs btn-outline-info rounded-pill mt-1 px-2 py-0" data-bs-toggle="modal" data-bs-target="#managerCredentialsModal{{ $branch->id }}">
                                                            <i class="ti-key me-1"></i>লগইন তথ্য
                                                        </button>
                                                    @else
                                                        <span class="text-muted small italic">ম্যানেজার নেই</span>
                                                        <div>
                                                            <button type="button" class="btn btn-xs btn-outline-primary rounded-pill mt-1" data-bs-toggle="modal" data-bs-target="#editBranchModal{{ $branch->id }}">
                                                                <i class="ti-plus me-1"></i>অ্যাসাইন করুন
                                                            </button>
                                                        </div>
                                                    @endif
                                                </td>

                                                <!-- Today's Sales -->
                                                <td class="text-end">
                                                    <div class="fw-bold text-success fs-6">৳ {{ number_format($branch->today_sales, 2) }}</div>
                                                    <div class="smaller text-muted">{{ $branch->today_invoices }} টি ইনভয়েস</div>
                                                </td>

                                                <!-- Total Lifetime Sales -->
                                                <td class="text-end">
                                                    <div class="fw-bold text-dark fs-6">৳ {{ number_format($branch->total_sales, 2) }}</div>
                                                    <div class="smaller text-muted">{{ $branch->pos_sales_count }} টি চালান</div>
                                                </td>

                                                <!-- Inventory -->
                                                <td class="text-center">
                                                    <a href="{{ route('admin.supershop.inventory', ['branch_id' => $branch->id]) }}" class="badge bg-info-light text-info border-0 px-2 py-1" title="এই ব্রাঞ্চের স্টক দেখুন">
                                                        <i class="ti-archive me-1"></i>{{ number_format($totalEcomProducts) }} টি পণ্য
                                                    </a>
                                                    <div class="smaller text-muted mt-1">{{ $branch->posCounters->count() }} টি POS কাউন্টার</div>
                                                </td>

                                                <!-- Status -->
                                                <td class="text-center">
                                                    @if($branch->status == 'active')
                                                        <span class="badge bg-success rounded-pill px-3 py-1">সক্রিয়</span>
                                                    @else
                                                        <span class="badge bg-secondary rounded-pill px-3 py-1">নিষ্ক্রিয়</span>
                                                    @endif
                                                </td>

                                                <!-- Actions -->
                                                <td class="text-end pe-4">
                                                    <div class="d-flex justify-content-end gap-1">
                                                        <a href="{{ route('admin.supershop.pos', ['branch_id' => $branch->id]) }}" class="btn btn-xs btn-success" title="POS টার্মিনাল খুলুন">
                                                            <i class="ti-shopping-cart"></i>
                                                        </a>
                                                        <a href="{{ route('admin.supershop.inventory', ['branch_id' => $branch->id]) }}" class="btn btn-xs btn-outline-info" title="ইনভেন্টরি ও পণ্য">
                                                            <i class="ti-archive"></i>
                                                        </a>
                                                        <button type="button" class="btn btn-xs btn-outline-primary" data-bs-toggle="modal" data-bs-target="#editBranchModal{{ $branch->id }}" title="ব্রাঞ্চ এডিট করুন">
                                                            <i class="ti-pencil"></i>
                                                        </button>
                                                        @if($branch->pos_sales_count == 0)
                                                            <a href="{{ route('admin.supershop.branches.destroy', $branch->id) }}" class="btn btn-xs btn-outline-danger" onclick="return confirm('আপনি কি নিশ্চিত এই ব্রাঞ্চ মুছে ফেলতে চান?')" title="মুছুন">
                                                                <i class="ti-trash"></i>
                                                            </a>
                                                        @endif
                                                    </div>
                                                </td>
                                            </tr>

                                            <!-- Manager Credentials Modal -->
                                            <div class="modal fade" id="managerCredentialsModal{{ $branch->id }}" tabindex="-1" aria-hidden="true">
                                                <div class="modal-dialog">
                                                    <div class="modal-content rounded-4 border-0 shadow">
                                                        <div class="modal-header bg-dark text-white rounded-top-4 py-3">
                                                            <h5 class="modal-title fw-bold text-white">
                                                                <i class="ti-key me-2 text-warning"></i>ব্রাঞ্চ ম্যানেজার লগইন তথ্য – {{ $branch->name }}
                                                            </h5>
                                                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                                                        </div>
                                                        <div class="modal-body p-4">
                                                            <div class="alert alert-light border p-3 rounded-3 mb-3">
                                                                <div class="d-flex justify-content-between mb-2 pb-2 border-bottom">
                                                                    <span class="text-muted small">শাখার নাম:</span>
                                                                    <strong class="text-dark">{{ $branch->name }} ({{ $branch->code }})</strong>
                                                                </div>
                                                                <div class="d-flex justify-content-between mb-2 pb-2 border-bottom">
                                                                    <span class="text-muted small">ম্যানেজারের নাম:</span>
                                                                    <strong class="text-dark">{{ $branch->manager->name ?? 'N/A' }}</strong>
                                                                </div>
                                                                <div class="d-flex justify-content-between mb-2 pb-2 border-bottom align-items-center">
                                                                    <span class="text-muted small">ইউজারনেম (Username):</span>
                                                                    <div class="d-flex align-items-center gap-1">
                                                                        <code class="fs-6 fw-bold text-primary" id="uname_{{ $branch->id }}">{{ $branch->manager->username ?? 'N/A' }}</code>
                                                                        <button type="button" class="btn btn-xs btn-light border py-0" onclick="copyText('uname_{{ $branch->id }}')" title="কপি করুন"><i class="fa fa-copy"></i></button>
                                                                    </div>
                                                                </div>
                                                                <div class="d-flex justify-content-between mb-2 pb-2 border-bottom">
                                                                    <span class="text-muted small">ইমেইল:</span>
                                                                    <span class="text-dark small">{{ $branch->manager->email ?? 'N/A' }}</span>
                                                                </div>
                                                                <div class="d-flex justify-content-between mb-2 pb-2 border-bottom">
                                                                    <span class="text-muted small">মোবাইল:</span>
                                                                    <span class="text-dark small">{{ $branch->manager->phone ?? $branch->phone ?? 'N/A' }}</span>
                                                                </div>
                                                                <div class="d-flex justify-content-between align-items-center">
                                                                    <span class="text-muted small">ম্যানেজার পোর্টাল লিঙ্ক:</span>
                                                                    <a href="{{ route('manager.login') }}" target="_blank" class="small fw-bold text-success text-decoration-none">
                                                                        /manager/login <i class="fa fa-external-link ms-1"></i>
                                                                    </a>
                                                                </div>
                                                            </div>

                                                            <!-- Password Reset Form -->
                                                            <div class="border rounded-3 p-3 bg-light">
                                                                <h6 class="fw-bold text-dark mb-2"><i class="ti-lock me-1 text-danger"></i>পাসওয়ার্ড পরিবর্তন / নতুন পাসওয়ার্ড সেট করুন:</h6>
                                                                <form method="POST" action="{{ route('admin.supershop.branches.reset_password', $branch->id) }}">
                                                                    @csrf
                                                                    <div class="input-group mb-2">
                                                                        <input type="password" name="new_password" class="form-control" placeholder="নতুন পাসওয়ার্ড (কমপক্ষে ৬ অক্ষর)" minlength="6" required>
                                                                        <button class="btn btn-primary fw-bold" type="submit">সেভ করুন</button>
                                                                    </div>
                                                                    <small class="text-muted">এই পাসওয়ার্ডটি ম্যানেজারকে প্রদান করুন যাতে তারা <code>/manager/login</code> এ লগইন করতে পারেন।</small>
                                                                </form>
                                                            </div>
                                                        </div>
                                                        <div class="modal-footer bg-light py-2">
                                                            <button type="button" class="btn btn-sm btn-secondary" data-bs-dismiss="modal">বন্ধ করুন</button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- Edit Branch Modal -->
                                            <div class="modal fade" id="editBranchModal{{ $branch->id }}" tabindex="-1" aria-hidden="true">
                                                <div class="modal-dialog modal-lg">
                                                    <form method="POST" action="{{ route('admin.supershop.branches.update', $branch->id) }}" enctype="multipart/form-data">
                                                        @csrf
                                                        <div class="modal-content rounded-4 border-0 shadow">
                                                            <div class="modal-header bg-primary text-white rounded-top-4 py-3">
                                                                <h5 class="modal-title fw-bold text-white"><i class="ti-pencil me-1"></i> ব্রাঞ্চ তথ্য সম্পাদনা – {{ $branch->name }}</h5>
                                                                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                                                            </div>
                                                            <div class="modal-body row g-3 p-4">
                                                                <div class="col-md-6">
                                                                    <label class="form-label fw-bold">ব্রাঞ্চের নাম <span class="text-danger">*</span></label>
                                                                    <input type="text" name="name" class="form-control" value="{{ $branch->name }}" required>
                                                                </div>
                                                                <div class="col-md-3">
                                                                    <label class="form-label fw-bold">ব্রাঞ্চ কোড <span class="text-danger">*</span></label>
                                                                    <input type="text" name="code" class="form-control" value="{{ $branch->code }}" required>
                                                                </div>
                                                                <div class="col-md-3">
                                                                    <label class="form-label fw-bold">ধরন (Type)</label>
                                                                    <select name="type" class="form-select">
                                                                        <option value="super_shop" {{ $branch->type == 'super_shop' ? 'selected' : '' }}>সুপারশপ (Super Shop)</option>
                                                                        <option value="express_outlet" {{ $branch->type == 'express_outlet' ? 'selected' : '' }}>এক্সপ্রেস আউটলেট</option>
                                                                        <option value="warehouse" {{ $branch->type == 'warehouse' ? 'selected' : '' }}>ওয়্যারহাউজ</option>
                                                                    </select>
                                                                </div>

                                                                <!-- Branch Logo / Picture -->
                                                                <div class="col-md-6">
                                                                    <label class="form-label fw-bold">ব্রাঞ্চ ছবি / লোগো (Picture)</label>
                                                                    <input type="file" name="logo" class="form-control" accept="image/*">
                                                                    @if($branch->logo)
                                                                        <small class="text-muted d-block mt-1">বর্তমান ছবি: <a href="{{ asset($branch->logo) }}" target="_blank">দেখুন</a></small>
                                                                    @endif
                                                                </div>

                                                                <div class="col-md-6">
                                                                    <label class="form-label fw-bold">ব্রাঞ্চ ম্যানেজার</label>
                                                                    <select name="manager_id" class="form-select">
                                                                        <option value="">-- ম্যানেজার নির্বাচন করুন --</option>
                                                                        @foreach($users as $user)
                                                                            <option value="{{ $user->id }}" {{ $branch->manager_id == $user->id ? 'selected' : '' }}>
                                                                                {{ $user->name }} ({{ $user->username }})
                                                                            </option>
                                                                        @endforeach
                                                                    </select>
                                                                </div>

                                                                <div class="col-md-6">
                                                                    <label class="form-label fw-bold">ফোন নম্বর</label>
                                                                    <input type="text" name="phone" class="form-control" value="{{ $branch->phone }}">
                                                                </div>
                                                                <div class="col-md-6">
                                                                    <label class="form-label fw-bold">ইমেইল</label>
                                                                    <input type="email" name="email" class="form-control" value="{{ $branch->email }}">
                                                                </div>

                                                                <div class="col-md-12">
                                                                    <label class="form-label fw-bold">পূর্ণ ঠিকানা <span class="text-danger">*</span></label>
                                                                    <textarea name="address" class="form-control" rows="2" required>{{ $branch->address }}</textarea>
                                                                </div>

                                                                <div class="col-md-4">
                                                                    <label class="form-label">বিভাগ</label>
                                                                    <input type="text" name="division" class="form-control" value="{{ $branch->division }}">
                                                                </div>
                                                                <div class="col-md-4">
                                                                    <label class="form-label">জেলা</label>
                                                                    <input type="text" name="district" class="form-control" value="{{ $branch->district }}">
                                                                </div>
                                                                <div class="col-md-4">
                                                                    <label class="form-label">এরিয়া/থানা</label>
                                                                    <input type="text" name="area" class="form-control" value="{{ $branch->area }}">
                                                                </div>

                                                                <div class="col-md-4">
                                                                    <label class="form-label">খোলার সময়</label>
                                                                    <input type="time" name="opening_time" class="form-control" value="{{ $branch->opening_time }}">
                                                                </div>
                                                                <div class="col-md-4">
                                                                    <label class="form-label">বন্ধের সময়</label>
                                                                    <input type="time" name="closing_time" class="form-control" value="{{ $branch->closing_time }}">
                                                                </div>
                                                                <div class="col-md-4">
                                                                    <label class="form-label fw-bold">স্ট্যাটাস</label>
                                                                    <select name="status" class="form-select">
                                                                        <option value="active" {{ $branch->status == 'active' ? 'selected' : '' }}>সক্রিয় (Active)</option>
                                                                        <option value="inactive" {{ $branch->status == 'inactive' ? 'selected' : '' }}>নিষ্ক্রিয় (Inactive)</option>
                                                                    </select>
                                                                </div>
                                                            </div>
                                                            <div class="modal-footer bg-light py-2">
                                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">বন্ধ করুন</button>
                                                                <button type="submit" class="btn btn-primary fw-bold"><i class="ti-check me-1"></i> আপডেট করুন</button>
                                                            </div>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>

                                        @empty
                                            <tr>
                                                <td colspan="9" class="text-center py-4 text-muted">কোনো ব্রাঞ্চ তৈরি করা হয়নি।</td>
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

        <!-- Create Branch Modal -->
        <div class="modal fade" id="createBranchModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <form method="POST" action="{{ route('admin.supershop.branches.store') }}" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-content rounded-4 border-0 shadow">
                        <div class="modal-header bg-success text-white rounded-top-4 py-3">
                            <h5 class="modal-title fw-bold text-white"><i class="ti-plus me-1"></i> নতুন সুপারশপ ব্রাঞ্চ যুক্ত করুন</h5>
                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body row g-3 p-4">
                            <!-- Basic Branch Info -->
                            <div class="col-md-6">
                                <label class="form-label fw-bold">ব্রাঞ্চের নাম <span class="text-danger">*</span></label>
                                <input type="text" name="name" class="form-control" placeholder="যেমন: ধানমন্ডি শাখা (Dhanmondi Branch)" required>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label fw-bold">ব্রাঞ্চ কোড <span class="text-danger">*</span></label>
                                <input type="text" name="code" class="form-control" placeholder="যেমন: BR-07, DHANMONDI-01" required>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label fw-bold">ধরন (Type)</label>
                                <select name="type" class="form-select">
                                    <option value="super_shop" selected>সুপারশপ (Super Shop)</option>
                                    <option value="express_outlet">এক্সপ্রেস আউটলেট</option>
                                    <option value="warehouse">ওয়্যারহাউজ</option>
                                </select>
                            </div>

                            <!-- Branch Logo / Picture -->
                            <div class="col-md-6">
                                <label class="form-label fw-bold">ব্রাঞ্চ ছবি / পিকচার (Picture/Logo)</label>
                                <input type="file" name="logo" class="form-control" accept="image/*">
                                <small class="text-muted">ব্রাঞ্চের আউটলেটের ছবি বা লোগো আপলোড করুন (JPG, PNG, WEBP)</small>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-bold">ব্রাঞ্চ ফোন নম্বর</label>
                                <input type="text" name="phone" class="form-control" placeholder="017xxxxxxxx">
                            </div>

                            <div class="col-md-12">
                                <label class="form-label fw-bold">পূর্ণ ঠিকানা <span class="text-danger">*</span></label>
                                <textarea name="address" class="form-control" rows="2" placeholder="বাড়ি #, রোড #, এরিয়া, জেলা..." required></textarea>
                            </div>

                            <div class="col-md-4">
                                <label class="form-label">বিভাগ</label>
                                <input type="text" name="division" class="form-control" value="Dhaka">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">জেলা</label>
                                <input type="text" name="district" class="form-control" value="Dhaka">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">এরিয়া/থানা</label>
                                <input type="text" name="area" class="form-control" placeholder="যেমন: ধানমন্ডি ২৭">
                            </div>

                            <div class="col-md-4">
                                <label class="form-label">খোলার সময়</label>
                                <input type="time" name="opening_time" class="form-control" value="08:00">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">বন্ধের সময়</label>
                                <input type="time" name="closing_time" class="form-control" value="22:30">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fw-bold">স্ট্যাটাস</label>
                                <select name="status" class="form-select">
                                    <option value="active" selected>সক্রিয় (Active)</option>
                                    <option value="inactive">নিষ্ক্রিয় (Inactive)</option>
                                </select>
                            </div>

                            <!-- Dedicated Branch Manager Credentials Section -->
                            <div class="col-12 mt-3 pt-3 border-top">
                                <h6 class="fw-bold text-dark mb-1">
                                    <i class="ti-user text-primary me-1"></i> ব্রাঞ্চ ম্যানেজার লগইন অ্যাকাউন্ট (Manager Login Credentials)
                                </h6>
                                <p class="text-muted small mb-3">নতুন ম্যানেজার অ্যাকাউন্ট তৈরি করুন যা দিয়ে ম্যানেজার <code>/manager/login</code> এ লগইন করে POS টার্মিনালে সেল করতে পারবেন</p>

                                <div class="bg-light p-3 rounded-3 border">
                                    <div class="row g-2">
                                        <div class="col-md-6">
                                            <label class="form-label small fw-bold">ম্যানেজার নাম (Manager Name)</label>
                                            <input type="text" name="manager_name" class="form-control form-control-sm" placeholder="যেমন: মোঃ রফিকুল ইসলাম">
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label small fw-bold">ইউজারনেম (Username) <span class="text-primary fw-normal">(লগইনের জন্য)</span></label>
                                            <input type="text" name="manager_username" class="form-control form-control-sm" placeholder="যেমন: dhanmondi_manager">
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label small fw-bold">লগইন পাসওয়ার্ড (Password) <span class="text-danger">*</span></label>
                                            <input type="password" name="manager_password" class="form-control form-control-sm" placeholder="কমপক্ষে ৬ অক্ষর">
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label small fw-bold">ম্যানেজার মোবাইল / ইমেইল</label>
                                            <input type="text" name="manager_phone" class="form-control form-control-sm" placeholder="017xxxxxxxx">
                                        </div>
                                        <div class="col-12 mt-2">
                                            <div class="text-muted smaller">
                                                <i class="fa fa-info-circle me-1 text-info"></i>অথবা পূর্বে বিদ্যমান ইউজার থেকে অ্যাসাইন করতে চাইলে নিচে নির্বাচন করুন:
                                            </div>
                                            <select name="manager_id" class="form-select form-select-sm mt-1">
                                                <option value="">-- অথবা বিদ্যমান ইউজার সিলেক্ট করুন --</option>
                                                @foreach($users as $user)
                                                    <option value="{{ $user->id }}">{{ $user->name }} ({{ $user->username }}) - {{ $user->role }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Automatic Central E-Commerce Product Sync -->
                            <div class="col-12 mt-2">
                                <div class="form-check form-switch p-3 bg-success-light rounded-3 border border-success">
                                    <input class="form-check-input ms-0 me-2" type="checkbox" name="assign_all_products" id="assignAllProducts" value="1" checked>
                                    <label class="form-check-label fw-bold text-dark" for="assignAllProducts">
                                        <i class="fa fa-check-circle text-success me-1"></i> সেন্ট্রাল ই-কমার্সের সকল পণ্য ({{ number_format($totalEcomProducts) }}+ পণ্য) এই ব্রাঞ্চে স্বয়ংক্রিয়ভাবে প্রদর্শন ও ম্যাপ করুন
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer bg-light py-2">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">বাতিল</button>
                            <button type="submit" class="btn btn-success fw-bold"><i class="ti-check me-1"></i> ব্রাঞ্চ তৈরি করুন</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

    </div>
</div>

<script>
function copyText(elemId) {
    var text = document.getElementById(elemId).innerText.trim();
    navigator.clipboard.writeText(text).then(function() {
        alert('কপি করা হয়েছে: ' + text);
    });
}
</script>
@endsection
