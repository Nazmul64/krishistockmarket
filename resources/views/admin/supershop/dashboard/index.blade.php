@extends('layouts.backend.app')

@section('content')
<div class="content-wrapper">
    <div class="container-full">
        <!-- Content Header -->
        <div class="content-header">
            <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
                <div>
                    <h3 class="page-title text-dark fw-bold mb-1">
                        <i class="ti-layout-grid2 text-success me-2"></i>সুপারশপ ও মাল্টি-ব্রাঞ্চ সেন্ট্রাল ড্যাশবোর্ড
                    </h3>
                    <p class="text-muted mb-0">Shwapno Retail Chain Model – সেন্ট্রাল ইনভেন্টরি, পিওএস কাউন্টার, ব্রাঞ্চ সেলস ও প্রফিট অ্যানালিটিক্স</p>
                </div>
                <div class="d-flex align-items-center gap-2 flex-wrap">
                    <form method="GET" action="{{ route('admin.supershop.dashboard') }}" class="d-flex align-items-center gap-2">
                        <!-- Branch Selector -->
                        <select name="branch_id" class="form-select form-select-sm" onchange="this.form.submit()">
                            <option value="">🏢 সকল ব্রাঞ্চ (All Branches)</option>
                            @foreach($branches as $b)
                                <option value="{{ $b->id }}" {{ $selectedBranchId == $b->id ? 'selected' : '' }}>
                                    {{ $b->branch_code }} - {{ $b->name }}
                                </option>
                            @endforeach
                        </select>

                        <!-- Date Filter -->
                        <select name="date_filter" class="form-select form-select-sm" onchange="this.form.submit()">
                            <option value="today" {{ $dateFilter == 'today' ? 'selected' : '' }}>📅 আজকের (Today)</option>
                            <option value="yesterday" {{ $dateFilter == 'yesterday' ? 'selected' : '' }}>গতকাল (Yesterday)</option>
                            <option value="this_week" {{ $dateFilter == 'this_week' ? 'selected' : '' }}>এই সপ্তাহ (This Week)</option>
                            <option value="this_month" {{ $dateFilter == 'this_month' ? 'selected' : '' }}>এই মাস (This Month)</option>
                            <option value="this_year" {{ $dateFilter == 'this_year' ? 'selected' : '' }}>এই বছর (This Year)</option>
                        </select>
                    </form>

                    <a href="{{ route('admin.supershop.pos') }}" class="btn btn-sm btn-success fw-bold">
                        <i class="ti-shopping-cart-full me-1"></i> POS টার্মিনাল
                    </a>
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

            <!-- 1. Top Primary Metrics (4 Cards) -->
            <div class="row">
                <!-- Combined Gross Revenue -->
                <div class="col-xl-3 col-md-6 col-12">
                    <div class="box bg-gradient-primary text-white shadow-sm">
                        <div class="box-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h4 class="fw-bold mb-1 text-white">৳ {{ number_format($combinedRevenue, 2) }}</h4>
                                    <div class="text-white-50">সম্মিলিত মোট বিক্রয় (Combined Revenue)</div>
                                </div>
                                <div class="bg-white bg-opacity-25 rounded-circle p-3 fs-3">
                                    <i class="ti-money"></i>
                                </div>
                            </div>
                            <div class="mt-3 pt-2 border-top border-white border-opacity-25 small d-flex justify-content-between">
                                <span>POS + অনলাইন</span>
                                <strong>{{ $totalTransactions + $totalOnlineOrders }} মোট অর্ডার</strong>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- POS Retail Sales -->
                <div class="col-xl-3 col-md-6 col-12">
                    <div class="box bg-gradient-success text-white shadow-sm">
                        <div class="box-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h4 class="fw-bold mb-1 text-white">৳ {{ number_format($totalPosSales, 2) }}</h4>
                                    <div class="text-white-50">ব্রাঞ্চ POS কাউন্টার বিক্রয়</div>
                                </div>
                                <div class="bg-white bg-opacity-25 rounded-circle p-3 fs-3">
                                    <i class="ti-receipt"></i>
                                </div>
                            </div>
                            <div class="mt-3 pt-2 border-top border-white border-opacity-25 small d-flex justify-content-between">
                                <span>POS লেনদেন:</span>
                                <strong>{{ $totalTransactions }} টি</strong>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Online E-commerce Orders -->
                <div class="col-xl-3 col-md-6 col-12">
                    <div class="box bg-gradient-info text-white shadow-sm">
                        <div class="box-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h4 class="fw-bold mb-1 text-white">৳ {{ number_format($totalOnlineSales, 2) }}</h4>
                                    <div class="text-white-50">অনলাইন ই-কমার্স বিক্রয়</div>
                                </div>
                                <div class="bg-white bg-opacity-25 rounded-circle p-3 fs-3">
                                    <i class="ti-shopping-cart"></i>
                                </div>
                            </div>
                            <div class="mt-3 pt-2 border-top border-white border-opacity-25 small d-flex justify-content-between">
                                <span>অনলাইন অর্ডার:</span>
                                <strong>{{ $totalOnlineOrders }} টি</strong>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Estimated Net Profit -->
                <div class="col-xl-3 col-md-6 col-12">
                    <div class="box {{ $netProfit >= 0 ? 'bg-gradient-warning' : 'bg-gradient-danger' }} text-white shadow-sm">
                        <div class="box-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h4 class="fw-bold mb-1 text-white">৳ {{ number_format($netProfit, 2) }}</h4>
                                    <div class="text-white-50">আনুমানিক নিট লাভ (Net Profit)</div>
                                </div>
                                <div class="bg-white bg-opacity-25 rounded-circle p-3 fs-3">
                                    <i class="ti-stats-up"></i>
                                </div>
                            </div>
                            <div class="mt-3 pt-2 border-top border-white border-opacity-25 small d-flex justify-content-between">
                                <span>গ্রস মার্জিন: ৳{{ number_format($grossProfit, 0) }}</span>
                                <span>খরচ: ৳{{ number_format($totalExpenses, 0) }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- 2. Secondary Quick Stat Badges -->
            <div class="row">
                <div class="col-xl-3 col-md-6 col-12">
                    <div class="box border-start border-4 border-primary">
                        <div class="box-body py-3 d-flex align-items-center justify-content-between">
                            <div>
                                <span class="text-muted d-block small">সক্রিয় ব্রাঞ্চ ও কাউন্টার</span>
                                <h5 class="fw-bold mb-0 text-dark">{{ $activeBranches }} / {{ $totalBranches }} ব্রাঞ্চ ({{ $totalCounters }} কাউন্টার)</h5>
                            </div>
                            <a href="{{ route('admin.supershop.branches') }}" class="btn btn-sm btn-light text-primary">
                                <i class="ti-arrow-right"></i>
                            </a>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-md-6 col-12">
                    <div class="box border-start border-4 border-success">
                        <div class="box-body py-3 d-flex align-items-center justify-content-between">
                            <div>
                                <span class="text-muted d-block small">ইনভেন্টরি মূল্যায়ন (স্টক মূল্য)</span>
                                <h5 class="fw-bold mb-0 text-dark">৳ {{ number_format($totalStockValuation, 2) }}</h5>
                            </div>
                            <span class="badge bg-success-light text-success">{{ number_format($totalStockUnits) }} ইউনিট</span>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-md-6 col-12">
                    <div class="box border-start border-4 border-danger">
                        <div class="box-body py-3 d-flex align-items-center justify-content-between">
                            <div>
                                <span class="text-muted d-block small">স্টক স্বল্পতা অ্যালার্ট (Low Stock)</span>
                                <h5 class="fw-bold mb-0 text-danger">{{ $lowStockCount }} টি আইটেম</h5>
                            </div>
                            <a href="{{ route('admin.supershop.inventory', ['low_stock' => 1]) }}" class="btn btn-sm btn-outline-danger">
                                দেখুন
                            </a>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-md-6 col-12">
                    <div class="box border-start border-4 border-info">
                        <div class="box-body py-3 d-flex align-items-center justify-content-between">
                            <div>
                                <span class="text-muted d-block small">ব্রাঞ্চ খরচ (Operating Expenses)</span>
                                <h5 class="fw-bold mb-0 text-dark">৳ {{ number_format($totalExpenses, 2) }}</h5>
                            </div>
                            <a href="{{ route('admin.supershop.expenses') }}" class="btn btn-sm btn-light text-info">
                                <i class="ti-arrow-right"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- 3. Branch Performance Comparison -->
            <div class="row">
                <div class="col-xl-8 col-12">
                    <div class="box shadow-sm">
                        <div class="box-header with-border d-flex justify-content-between align-items-center">
                            <h4 class="box-title fw-bold text-dark mb-0">
                                <i class="ti-cup text-warning me-1"></i> ব্রাঞ্চ পারফরম্যান্স তুলনা (Branch Performance Comparison)
                            </h4>
                            <span class="badge bg-primary-light text-primary">রিয়েল-টাইম POS বিক্রয় র‍্যাংকিং</span>
                        </div>
                        <div class="box-body p-0">
                            <div class="table-responsive">
                                <table class="table table-hover mb-0 align-middle">
                                    <thead class="table-light">
                                        <tr>
                                            <th>র‍্যাংক</th>
                                            <th>ব্রাঞ্চ কোড ও নাম</th>
                                            <th>ম্যানেজার</th>
                                            <th class="text-center">কাউন্টার</th>
                                            <th class="text-end">মোট বিক্রয় (POS)</th>
                                            <th class="text-center">স্টক ইউনিট</th>
                                            <th class="text-center">স্ট্যাটাস</th>
                                            <th class="text-end">অ্যাকশন</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($branchComparison as $idx => $item)
                                            <tr>
                                                <td class="fw-bold">
                                                    @if($idx == 0)
                                                        <span class="badge bg-warning text-dark"><i class="fa fa-trophy me-1"></i> #1</span>
                                                    @elseif($idx == 1)
                                                        <span class="badge bg-secondary">#2</span>
                                                    @elseif($idx == 2)
                                                        <span class="badge bg-bronze" style="background:#cd7f32; color:#fff">#3</span>
                                                    @else
                                                        <span class="text-muted">#{{ $idx + 1 }}</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    <span class="badge bg-dark me-1">{{ $item['branch']->branch_code }}</span>
                                                    <strong>{{ $item['branch']->name }}</strong>
                                                    <div class="small text-muted">{{ $item['branch']->city ?? 'Dhaka' }}</div>
                                                </td>
                                                <td>
                                                    <span class="small">{{ $item['branch']->manager ? $item['branch']->manager->name : 'অ্যাসাইন করা হয়নি' }}</span>
                                                </td>
                                                <td class="text-center">
                                                    <span class="badge bg-light text-dark border">{{ $item['branch']->posCounters->count() }} টি</span>
                                                </td>
                                                <td class="text-end fw-bold text-success">
                                                    ৳ {{ number_format($item['sales'], 2) }}
                                                    <div class="small text-muted">{{ $item['transactions'] }} লেনদেন</div>
                                                </td>
                                                <td class="text-center">
                                                    <span class="badge {{ $item['low_stock'] > 0 ? 'bg-warning-light text-warning' : 'bg-success-light text-success' }}">
                                                        {{ number_format($item['stock_units']) }}
                                                    </span>
                                                </td>
                                                <td class="text-center">
                                                    @if($item['branch']->status == 'active')
                                                        <span class="badge bg-success">সক্রিয়</span>
                                                    @else
                                                        <span class="badge bg-danger">নিষ্ক্রিয়</span>
                                                    @endif
                                                </td>
                                                <td class="text-end">
                                                    <a href="{{ route('admin.supershop.inventory', ['branch_id' => $item['branch']->id]) }}" class="btn btn-xs btn-outline-primary" title="ইনভেন্টরি">
                                                        <i class="ti-archive"></i>
                                                    </a>
                                                    <a href="{{ route('admin.supershop.pos', ['branch_id' => $item['branch']->id]) }}" class="btn btn-xs btn-success" title="POS ওপেন করুন">
                                                        <i class="ti-shopping-cart"></i>
                                                    </a>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="8" class="text-center py-4 text-muted">কোনো ব্রাঞ্চ পাওয়া যায়নি।</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- 4. Channel Split & Quick Actions -->
                <div class="col-xl-4 col-12">
                    <!-- Channel Split Card -->
                    <div class="box shadow-sm mb-4">
                        <div class="box-header with-border">
                            <h4 class="box-title fw-bold text-dark mb-0">
                                <i class="ti-pie-chart text-info me-1"></i> চ্যানেল বিশ্লেষণ (Channel Split)
                            </h4>
                        </div>
                        <div class="box-body">
                            @php
                                $totalChannel = $totalPosSales + $totalOnlineSales;
                                $posPct = $totalChannel > 0 ? round(($totalPosSales / $totalChannel) * 100, 1) : 0;
                                $onlinePct = $totalChannel > 0 ? round(($totalOnlineSales / $totalChannel) * 100, 1) : 0;
                            @endphp
                            <div class="mb-3">
                                <div class="d-flex justify-content-between mb-1">
                                    <span class="fw-bold"><i class="ti-home text-success me-1"></i> POS ইন-স্টোর রিটেল:</span>
                                    <span class="fw-bold text-success">{{ $posPct }}% (৳{{ number_format($totalPosSales, 0) }})</span>
                                </div>
                                <div class="progress" style="height: 10px;">
                                    <div class="progress-bar bg-success" role="progressbar" style="width: {{ $posPct }}%"></div>
                                </div>
                            </div>

                            <div class="mb-3">
                                <div class="d-flex justify-content-between mb-1">
                                    <span class="fw-bold"><i class="ti-shopping-cart text-info me-1"></i> অনলাইন ই-কমার্স:</span>
                                    <span class="fw-bold text-info">{{ $onlinePct }}% (৳{{ number_format($totalOnlineSales, 0) }})</span>
                                </div>
                                <div class="progress" style="height: 10px;">
                                    <div class="progress-bar bg-info" role="progressbar" style="width: {{ $onlinePct }}%"></div>
                                </div>
                            </div>

                            <hr>
                            <div class="d-flex justify-content-between align-items-center">
                                <span class="text-muted">সর্বমোট চ্যানেল সেলস:</span>
                                <h5 class="fw-bold text-dark mb-0">৳ {{ number_format($totalChannel, 2) }}</h5>
                            </div>
                        </div>
                    </div>

                    <!-- Low Stock Alerts preview -->
                    <div class="box shadow-sm">
                        <div class="box-header with-border d-flex justify-content-between align-items-center">
                            <h4 class="box-title fw-bold text-dark mb-0">
                                <i class="ti-alert text-danger me-1"></i> দ্রুত স্টক রিস্টক অ্যালার্ট
                            </h4>
                            <a href="{{ route('admin.supershop.inventory', ['low_stock' => 1]) }}" class="small text-danger fw-bold">সকল দেখুন</a>
                        </div>
                        <div class="box-body p-0">
                            <ul class="list-group list-group-flush">
                                @forelse($lowStockItems as $lsi)
                                    <li class="list-group-item d-flex justify-content-between align-items-center py-2">
                                        <div>
                                            <strong class="d-block text-dark small">{{ $lsi->product ? $lsi->product->name : 'পণ্য #'.$lsi->product_id }}</strong>
                                            <span class="badge bg-light text-muted">{{ $lsi->branch ? $lsi->branch->branch_code : '' }}</span>
                                        </div>
                                        <div class="text-end">
                                            <span class="badge bg-danger">{{ $lsi->stock_quantity }} {{ $lsi->product->unit ?? 'টি' }} বাকি</span>
                                        </div>
                                    </li>
                                @empty
                                    <li class="list-group-item text-center text-muted py-3 small">
                                        <i class="fa fa-check text-success me-1"></i> কোনো স্বল্প স্টকের পণ্য নেই।
                                    </li>
                                @endforelse
                            </ul>
                        </div>
                    </div>
                </div>
            </div>

            <!-- 5. Recent POS Transactions Table -->
            <div class="row">
                <div class="col-12">
                    <div class="box shadow-sm">
                        <div class="box-header with-border d-flex justify-content-between align-items-center">
                            <h4 class="box-title fw-bold text-dark mb-0">
                                <i class="ti-receipt text-primary me-1"></i> সাম্প্রতিক POS ইনভয়েস লেনদেন (Recent POS Transactions)
                            </h4>
                            <a href="{{ route('admin.supershop.sales') }}" class="btn btn-sm btn-outline-primary">
                                সকল POS বিক্রয় দেখুন <i class="ti-arrow-right ms-1"></i>
                            </a>
                        </div>
                        <div class="box-body p-0">
                            <div class="table-responsive">
                                <table class="table table-hover mb-0 align-middle">
                                    <thead class="table-light">
                                        <tr>
                                            <th>ইনভয়েস #</th>
                                            <th>ব্রাঞ্চ</th>
                                            <th>ক্যাশিয়ার</th>
                                            <th>কাস্টমার</th>
                                            <th class="text-center">আইটেম</th>
                                            <th class="text-end">মোট টাকা</th>
                                            <th class="text-center">পেমেন্ট মেথড</th>
                                            <th class="text-center">তারিখ ও সময়</th>
                                            <th class="text-end">রিসিপ্ট</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($recentSales as $sale)
                                            <tr>
                                                <td>
                                                    <a href="{{ route('admin.supershop.sales.show', $sale->id) }}" class="fw-bold text-primary">
                                                        {{ $sale->invoice_number }}
                                                    </a>
                                                </td>
                                                <td>
                                                    <span class="badge bg-dark">{{ $sale->branch ? $sale->branch->branch_code : '' }}</span>
                                                    <span class="small ms-1">{{ $sale->branch ? $sale->branch->name : '' }}</span>
                                                </td>
                                                <td>
                                                    <span class="small">{{ $sale->cashier ? $sale->cashier->name : 'POS Cashier' }}</span>
                                                </td>
                                                <td>
                                                    <span class="small">{{ $sale->customer_name ?: 'Walk-in Guest' }}</span>
                                                    @if($sale->customer_phone)
                                                        <div class="text-muted smaller">{{ $sale->customer_phone }}</div>
                                                    @endif
                                                </td>
                                                <td class="text-center">
                                                    <span class="badge bg-light text-dark border">{{ $sale->total_items }}</span>
                                                </td>
                                                <td class="text-end fw-bold text-success">
                                                    ৳ {{ number_format($sale->grand_total, 2) }}
                                                </td>
                                                <td class="text-center">
                                                    <span class="badge bg-info-light text-info text-uppercase">{{ $sale->payment_method }}</span>
                                                </td>
                                                <td class="text-center text-muted small">
                                                    {{ \Carbon\Carbon::parse($sale->sale_time)->format('d M, h:i A') }}
                                                </td>
                                                <td class="text-end">
                                                    <a href="{{ route('admin.supershop.pos.receipt', $sale->id) }}" target="_blank" class="btn btn-xs btn-outline-secondary" title="রিসিপ্ট প্রিন্ট">
                                                        <i class="ti-printer"></i>
                                                    </a>
                                                    <a href="{{ route('admin.supershop.sales.show', $sale->id) }}" class="btn btn-xs btn-outline-primary" title="বিস্তারিত">
                                                        <i class="ti-eye"></i>
                                                    </a>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="9" class="text-center py-4 text-muted">কোনো সাম্প্রতিক POS বিক্রয় পাওয়া যায়নি।</td>
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

