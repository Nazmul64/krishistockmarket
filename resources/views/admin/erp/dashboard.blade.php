@extends('layouts.backend.app')

@section('content')
<div class="content-wrapper">
    <div class="container-full">
        <section class="content py-3">

            <!-- ERP Header Bar -->
            <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4 p-3 rounded-4 bg-white shadow-sm border">
                <div class="d-flex align-items-center gap-3">
                    <div class="rounded-circle bg-primary bg-opacity-10 d-flex align-items-center justify-content-center text-primary" style="width: 50px; height: 50px;">
                        <i class="fa fa-cubes fa-2x"></i>
                    </div>
                    <div>
                        <h4 class="fw-bold text-dark mb-0">কর্পোরেট স্টোর, ইনভেন্টরি, অ্যাসেট ও অ্যাকাউন্টস ERP</h4>
                        <small class="text-muted">{{ $company->company_name_bn ?? 'ই-কৃষি সেবা কর্পোরেট এন্টারপ্রাইজ' }} | অর্থবছর: <span class="badge bg-secondary">{{ $company->fiscal_year ?? '2026-2027' }}</span> | কারেন্সি: <span class="badge bg-primary">{{ $company->currency ?? 'BDT (৳)' }}</span></small>
                    </div>
                </div>
                <div class="d-flex flex-wrap gap-2">
                    <a href="{{ route('admin.erp.store.items') }}" class="btn btn-sm btn-outline-primary rounded-pill px-3 fw-bold">
                        <i class="fa fa-list me-1"></i> ইনভেন্টরি স্টক
                    </a>
                    <a href="{{ route('admin.erp.assets') }}" class="btn btn-sm btn-outline-success rounded-pill px-3 fw-bold">
                        <i class="fa fa-desktop me-1"></i> স্থায়ী সম্পদ
                    </a>
                    <a href="{{ route('admin.erp.vouchers') }}" class="btn btn-sm btn-outline-warning rounded-pill px-3 fw-bold">
                        <i class="fa fa-file-text me-1"></i> ভাউচার রেজিস্টার
                    </a>
                    <a href="{{ route('admin.erp.cash_memos') }}" class="btn btn-sm btn-primary rounded-pill px-3 fw-bold">
                        <i class="fa fa-shopping-cart me-1"></i> ক্যাশ মেমো (POS)
                    </a>
                </div>
            </div>

            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show rounded-4" role="alert">
                    <i class="fa fa-check-circle me-1"></i> {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <!-- 1. Store & Inventory Metric Cards -->
            <div class="row g-3 mb-4">
                <div class="col-xl-3 col-md-6">
                    <div class="card border-0 shadow-sm rounded-4 p-3 bg-white h-100 border-start border-4 border-primary">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <small class="text-muted text-uppercase fw-bold font-size-11">মোট গুদাম ও স্টোর</small>
                                <h3 class="fw-bold text-dark my-1">{{ number_format($totalWarehouses) }} টি</h3>
                                <small class="text-success"><i class="fa fa-building me-1"></i> সেন্ট্রাল ও বিভাগীয় গুদাম</small>
                            </div>
                            <div class="rounded-circle bg-primary bg-opacity-10 p-3 text-primary">
                                <i class="fa fa-archive fa-2x"></i>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-xl-3 col-md-6">
                    <div class="card border-0 shadow-sm rounded-4 p-3 bg-white h-100 border-start border-4 border-info">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <small class="text-muted text-uppercase fw-bold font-size-11">মোট আইটেম ক্যাটালগ</small>
                                <h3 class="fw-bold text-dark my-1">{{ number_format($totalItems) }} টি</h3>
                                <small class="text-info"><i class="fa fa-check-circle me-1"></i> মোট মজুত: {{ number_format($totalStockQty) }} ইউনিট</small>
                            </div>
                            <div class="rounded-circle bg-info bg-opacity-10 p-3 text-info">
                                <i class="fa fa-tags fa-2x"></i>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-xl-3 col-md-6">
                    <div class="card border-0 shadow-sm rounded-4 p-3 bg-white h-100 border-start border-4 border-success">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <small class="text-muted text-uppercase fw-bold font-size-11">স্টোর স্টক ভ্যালুয়েশন</small>
                                <h3 class="fw-bold text-success my-1">৳ {{ number_format($totalStockValuation, 2) }}</h3>
                                <small class="text-muted"><i class="fa fa-calculator me-1"></i> মোট ক্রয়মূল্যভিত্তিক মূল্যায়ন</small>
                            </div>
                            <div class="rounded-circle bg-success bg-opacity-10 p-3 text-success">
                                <i class="fa fa-money fa-2x"></i>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-xl-3 col-md-6">
                    <div class="card border-0 shadow-sm rounded-4 p-3 bg-white h-100 border-start border-4 border-danger">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <small class="text-muted text-uppercase fw-bold font-size-11">রিঅর্ডার অ্যালার্ট ও রিকুইজিশন</small>
                                <h3 class="fw-bold text-danger my-1">{{ number_format($lowStockCount) }} টি আইটেম</h3>
                                <small class="text-warning"><i class="fa fa-hourglass-half me-1"></i> পেন্ডিং রিকুইজিশন: {{ $pendingRequisitionsCount }} টি</small>
                            </div>
                            <div class="rounded-circle bg-danger bg-opacity-10 p-3 text-danger">
                                <i class="fa fa-exclamation-triangle fa-2x"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- 2. Fixed Assets & Vehicles Metric Cards -->
            <div class="row g-3 mb-4">
                <div class="col-xl-3 col-md-6">
                    <div class="card border-0 shadow-sm rounded-4 p-3 bg-white h-100 border-start border-4 border-warning">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <small class="text-muted text-uppercase fw-bold font-size-11">মোট স্থায়ী সম্পদ (Assets)</small>
                                <h3 class="fw-bold text-dark my-1">{{ number_format($totalAssetsCount) }} টি</h3>
                                <small class="text-success"><i class="fa fa-user me-1"></i> ব্যবহারকারী বরাদ্দ: {{ $assignedAssetsCount }} টি</small>
                            </div>
                            <div class="rounded-circle bg-warning bg-opacity-10 p-3 text-warning">
                                <i class="fa fa-desktop fa-2x"></i>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-xl-3 col-md-6">
                    <div class="card border-0 shadow-sm rounded-4 p-3 bg-white h-100 border-start border-4 border-purple" style="border-left-color: #8b5cf6 !important;">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <small class="text-muted text-uppercase fw-bold font-size-11">সম্পদের বর্তমান বুক ভ্যালু</small>
                                <h3 class="fw-bold my-1" style="color: #8b5cf6;">৳ {{ number_format($totalAssetBookValue, 2) }}</h3>
                                <small class="text-muted"><i class="fa fa-arrow-down me-1"></i> অবচয়: ৳ {{ number_format($totalAccumulatedDepreciation, 2) }}</small>
                            </div>
                            <div class="rounded-circle bg-opacity-10 p-3" style="background: rgba(139, 92, 246, 0.1); color: #8b5cf6;">
                                <i class="fa fa-line-chart fa-2x"></i>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-xl-3 col-md-6">
                    <div class="card border-0 shadow-sm rounded-4 p-3 bg-white h-100 border-start border-4 border-primary">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <small class="text-muted text-uppercase fw-bold font-size-11">গাড়ি বহর ও পরিবহন (Fleet)</small>
                                <h3 class="fw-bold text-dark my-1">{{ number_format($totalVehicles) }} টি</h3>
                                <small class="{{ $expiringDocsCount > 0 ? 'text-danger fw-bold' : 'text-success' }}">
                                    <i class="fa fa-file-text me-1"></i> এক্সপায়ারি সতর্কতা: {{ $expiringDocsCount }} টি
                                </small>
                            </div>
                            <div class="rounded-circle bg-primary bg-opacity-10 p-3 text-primary">
                                <i class="fa fa-car fa-2x"></i>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-xl-3 col-md-6">
                    <div class="card border-0 shadow-sm rounded-4 p-3 bg-white h-100 border-start border-4 border-success">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <small class="text-muted text-uppercase fw-bold font-size-11">ক্যাশ ও ব্যাংক ফান্ড ব্যালেন্স</small>
                                <h3 class="fw-bold text-success my-1">৳ {{ number_format($totalCashBankBalance, 2) }}</h3>
                                <small class="text-primary"><i class="fa fa-credit-card me-1"></i> দেনাদার: ৳ {{ number_format($totalReceivables, 2) }}</small>
                            </div>
                            <div class="rounded-circle bg-success bg-opacity-10 p-3 text-success">
                                <i class="fa fa-bank fa-2x"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Quick Action Toolbar -->
            <div class="row g-3 mb-4">
                <div class="col-12">
                    <div class="card border-0 shadow-sm rounded-4 p-3 bg-white">
                        <div class="d-flex flex-wrap align-items-center justify-content-between gap-3">
                            <span class="fw-bold text-dark"><i class="fa fa-bolt text-warning me-1"></i> দ্রুত অ্যাকশন শর্টকাট (ERP Quick Hub):</span>
                            <div class="d-flex flex-wrap gap-2">
                                <a href="{{ route('admin.erp.store.stock_in_out') }}" class="btn btn-sm btn-outline-secondary rounded-pill px-3">
                                    <i class="fa fa-arrow-down text-success me-1"></i> স্টক ইন / আউট
                                </a>
                                <a href="{{ route('admin.erp.store.requisitions') }}" class="btn btn-sm btn-outline-secondary rounded-pill px-3">
                                    <i class="fa fa-shopping-basket text-primary me-1"></i> স্টোর রিকুইজিশন
                                </a>
                                <a href="{{ route('admin.erp.store.transfers_adjustments') }}" class="btn btn-sm btn-outline-secondary rounded-pill px-3">
                                    <i class="fa fa-exchange text-info me-1"></i> গুদাম স্থানান্তর ও সমন্বয়
                                </a>
                                <a href="{{ route('admin.erp.assets.assignments') }}" class="btn btn-sm btn-outline-secondary rounded-pill px-3">
                                    <i class="fa fa-user-plus text-warning me-1"></i> সম্পদ হস্তান্তর
                                </a>
                                <a href="{{ route('admin.erp.vehicles.trips') }}" class="btn btn-sm btn-outline-secondary rounded-pill px-3">
                                    <i class="fa fa-map-marker text-danger me-1"></i> গাড়ি ট্রিপ ও ফুয়েল
                                </a>
                                <a href="{{ route('admin.erp.bills') }}" class="btn btn-sm btn-outline-secondary rounded-pill px-3">
                                    <i class="fa fa-file-invoice text-primary me-1"></i> বিল ও ইনভয়েস
                                </a>
                                <a href="{{ route('admin.erp.money_receipts') }}" class="btn btn-sm btn-outline-secondary rounded-pill px-3">
                                    <i class="fa fa-receipt text-success me-1"></i> মানি রিসিট
                                </a>
                                <a href="{{ route('admin.erp.accounts.trial_balance') }}" class="btn btn-sm btn-outline-secondary rounded-pill px-3">
                                    <i class="fa fa-balance-scale text-purple me-1"></i> ট্রায়াল ব্যালেন্স
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Two Column Section: Low Stock & Recent Transactions -->
            <div class="row g-3 mb-4">
                <!-- Left: Low Stock Alerts -->
                <div class="col-xl-5 col-12">
                    <div class="card border-0 shadow-sm rounded-4 p-4 bg-white h-100">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h5 class="fw-bold text-dark mb-0">
                                <i class="fa fa-bell text-danger me-2"></i> লো স্টক অ্যালার্ট (Reorder Warnings)
                            </h5>
                            <a href="{{ route('admin.erp.store.items') }}" class="btn btn-xs btn-outline-danger rounded-pill px-2">সব দেখুন</a>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-hover align-middle small mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th>আইটেম নাম</th>
                                        <th>বর্তমান মজুত</th>
                                        <th>রিঅর্ডার লেভেল</th>
                                        <th>স্ট্যাটাস</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($lowStockItems as $li)
                                    <tr>
                                        <td class="fw-bold text-dark">{{ $li->name_bn ?? $li->name }}</td>
                                        <td><span class="badge bg-danger">{{ number_format($li->current_stock) }}</span></td>
                                        <td>{{ number_format($li->reorder_level) }}</td>
                                        <td>
                                            @if($li->current_stock <= 0)
                                                <span class="badge bg-dark">স্টক শেষ (Out of Stock)</span>
                                            @else
                                                <span class="badge bg-warning text-dark">রিঅর্ডার প্রয়োজন</span>
                                            @endif
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="4" class="text-center text-muted py-3">
                                            <i class="fa fa-check-circle text-success me-1"></i> সকল আইটেমের পর্যাপ্ত স্টক মজুত রয়েছে।
                                        </td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- Right: Recent Stock Transactions -->
                <div class="col-xl-7 col-12">
                    <div class="card border-0 shadow-sm rounded-4 p-4 bg-white h-100">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h5 class="fw-bold text-dark mb-0">
                                <i class="fa fa-history text-primary me-2"></i> সাম্প্রতিক স্টক ট্রানজ্যাকশন লেজার
                            </h5>
                            <a href="{{ route('admin.erp.store.stock_in_out') }}" class="btn btn-xs btn-outline-primary rounded-pill px-2">সব দেখুন</a>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-hover align-middle small mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th>ট্রানজ্যাকশন নং</th>
                                        <th>ধরন</th>
                                        <th>আইটেম</th>
                                        <th>পরিমাণ</th>
                                        <th>গুদাম</th>
                                        <th>তারিখ ও সময়</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($recentTransactions as $tx)
                                    <tr>
                                        <td class="fw-bold text-primary">{{ $tx->transaction_number }}</td>
                                        <td>
                                            @if($tx->transaction_type === 'stock_in' || $tx->transaction_type === 'purchase_receive')
                                                <span class="badge bg-success"><i class="fa fa-arrow-down me-1"></i> Stock In</span>
                                            @elseif($tx->transaction_type === 'stock_out')
                                                <span class="badge bg-danger"><i class="fa fa-arrow-up me-1"></i> Stock Out</span>
                                            @elseif($tx->transaction_type === 'transfer')
                                                <span class="badge bg-info"><i class="fa fa-exchange me-1"></i> Transfer</span>
                                            @else
                                                <span class="badge bg-secondary">{{ $tx->transaction_type }}</span>
                                            @endif
                                        </td>
                                        <td>{{ optional($tx->item)->name ?? 'Item #' . $tx->item_id }}</td>
                                        <td class="fw-bold">{{ number_format($tx->quantity) }}</td>
                                        <td>{{ optional($tx->warehouse)->name ?? 'Central Store' }}</td>
                                        <td class="text-muted">{{ $tx->created_at->format('d/m/Y h:i A') }}</td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="6" class="text-center text-muted py-3">কোনো সাম্প্রতিক ট্রানজ্যাকশন পাওয়া যায়নি।</td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Two Column Section: Recent Bills & Vouchers -->
            <div class="row g-3 mb-4">
                <!-- Left: Recent Bills -->
                <div class="col-xl-6 col-12">
                    <div class="card border-0 shadow-sm rounded-4 p-4 bg-white h-100">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h5 class="fw-bold text-dark mb-0">
                                <i class="fa fa-file-text-o text-warning me-2"></i> সাম্প্রতিক বিল ও চালান
                            </h5>
                            <a href="{{ route('admin.erp.bills') }}" class="btn btn-xs btn-outline-warning rounded-pill px-2">বিল রেজিস্টার</a>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-hover align-middle small mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th>বিল নং</th>
                                        <th>ভেন্ডর / প্রতিষ্ঠান</th>
                                        <th>মোট টাকা</th>
                                        <th>পরিশোধ</th>
                                        <th>বকেয়া</th>
                                        <th>স্ট্যাটাস</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($recentBills as $b)
                                    <tr>
                                        <td class="fw-bold text-dark">{{ $b->bill_number }}</td>
                                        <td>{{ $b->vendor_name }}</td>
                                        <td class="fw-bold">৳ {{ number_format($b->total_amount, 2) }}</td>
                                        <td class="text-success">৳ {{ number_format($b->paid_amount, 2) }}</td>
                                        <td class="text-danger fw-bold">৳ {{ number_format($b->due_amount, 2) }}</td>
                                        <td>
                                            @if($b->payment_status === 'paid')
                                                <span class="badge bg-success">পরিশোধিত</span>
                                            @elseif($b->payment_status === 'partially_paid')
                                                <span class="badge bg-warning text-dark">আংশিক</span>
                                            @else
                                                <span class="badge bg-danger">বকেয়া</span>
                                            @endif
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="6" class="text-center text-muted py-3">কোনো বিলের তথ্য পাওয়া যায়নি।</td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- Right: Recent Vouchers (Double Entry) -->
                <div class="col-xl-6 col-12">
                    <div class="card border-0 shadow-sm rounded-4 p-4 bg-white h-100">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h5 class="fw-bold text-dark mb-0">
                                <i class="fa fa-book text-success me-2"></i> সাম্প্রতিক ভাউচার রেজিস্টার
                            </h5>
                            <a href="{{ route('admin.erp.vouchers') }}" class="btn btn-xs btn-outline-success rounded-pill px-2">সকল ভাউচার</a>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-hover align-middle small mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th>ভাউচার নং</th>
                                        <th>ধরন</th>
                                        <th>প্রাপক / প্রদানকারী</th>
                                        <th>পরিমাণ</th>
                                        <th>তারিখ</th>
                                        <th>অ্যাকশন</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($recentVouchers as $v)
                                    <tr>
                                        <td class="fw-bold text-dark">{{ $v->voucher_number }}</td>
                                        <td>
                                            @if($v->voucher_type === 'payment')
                                                <span class="badge bg-danger">Payment</span>
                                            @elseif($v->voucher_type === 'receipt')
                                                <span class="badge bg-success">Receipt</span>
                                            @elseif($v->voucher_type === 'journal')
                                                <span class="badge bg-info">Journal</span>
                                            @elseif($v->voucher_type === 'contra')
                                                <span class="badge bg-warning text-dark">Contra</span>
                                            @else
                                                <span class="badge bg-secondary">{{ $v->voucher_type }}</span>
                                            @endif
                                        </td>
                                        <td>{{ $v->payee_or_payer }}</td>
                                        <td class="fw-bold text-dark">৳ {{ number_format($v->amount, 2) }}</td>
                                        <td>{{ $v->voucher_date }}</td>
                                        <td>
                                            <a href="{{ route('admin.erp.vouchers.print', $v->id) }}" target="_blank" class="btn btn-xs btn-outline-primary rounded-pill px-2">
                                                <i class="fa fa-print"></i> প্রিন্ট
                                            </a>
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="6" class="text-center text-muted py-3">কোনো ভাউচার পাওয়া যায়নি।</td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

        </section>
    </div>
</div>
@endsection

