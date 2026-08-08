@extends('layouts.backend.app')

@section('content')
<div class="content-wrapper">
    <div class="container-full">
        <!-- Content Header -->
        <div class="content-header">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <h4 class="page-title">এজেন্ট লাইভ স্টক ও লেনদেন খতিয়ান: {{ $agent->name }}</h4>
                    <p class="text-muted mb-0"><i class="fa fa-phone me-1"></i>{{ $agent->phone ?? $agent->username }} | বিপণন এজেন্টের লাইভ হিসেব ও লেনদেন রিপোর্ট</p>
                </div>
                <div>
                    <a href="{{ route('admin.agent_ledger.index') }}" class="btn btn-secondary btn-sm"><i class="fa fa-arrow-left me-1"></i> পিছনে যান</a>
                </div>
            </div>
        </div>

        <!-- Main content -->
        <section class="content">
            <!-- Stat Cards -->
            <div class="row">
                <div class="col-xl-3 col-md-6 mb-4">
                    <div class="card border-0 shadow-sm bg-primary text-white h-100">
                        <div class="card-body p-3 d-flex align-items-center justify-content-between">
                            <div>
                                <h6 class="text-uppercase mb-1 opacity-75">লাইভ অবশিষ্ট স্টক</h6>
                                <h3 class="fw-bold mb-0">{{ $available_stock }} টি</h3>
                                <small class="opacity-75">মোট কেনা/বরাদ্দ: {{ $total_allocated }} টি</small>
                            </div>
                            <i class="fa fa-cubes fa-2x opacity-50"></i>
                        </div>
                    </div>
                </div>

                <div class="col-xl-3 col-md-6 mb-4">
                    <div class="card border-0 shadow-sm bg-success text-white h-100">
                        <div class="card-body p-3 d-flex align-items-center justify-content-between">
                            <div>
                                <h6 class="text-uppercase mb-1 opacity-75">আজকের বিক্রি (Today)</h6>
                                <h3 class="fw-bold mb-0">৳{{ number_format($today_sales, 2) }}</h3>
                                <small class="opacity-75">আজকের মোট বিক্রয় মূল্য</small>
                            </div>
                            <i class="fa fa-shopping-cart fa-2x opacity-50"></i>
                        </div>
                    </div>
                </div>

                <div class="col-xl-3 col-md-6 mb-4">
                    <div class="card border-0 shadow-sm bg-info text-white h-100">
                        <div class="card-body p-3 d-flex align-items-center justify-content-between">
                            <div>
                                <h6 class="text-uppercase mb-1 opacity-75">চলতি মাসের বিক্রি (Monthly)</h6>
                                <h3 class="fw-bold mb-0">৳{{ number_format($monthly_sales, 2) }}</h3>
                                <small class="opacity-75">এই মাসের মোট লেনদেন</small>
                            </div>
                            <i class="fa fa-calendar fa-2x opacity-50"></i>
                        </div>
                    </div>
                </div>

                <div class="col-xl-3 col-md-6 mb-4">
                    <div class="card border-0 shadow-sm bg-dark text-white h-100">
                        <div class="card-body p-3 d-flex align-items-center justify-content-between">
                            <div>
                                <h6 class="text-uppercase mb-1 opacity-75">চলতি বছরের বিক্রি (Yearly)</h6>
                                <h3 class="fw-bold mb-0">৳{{ number_format($yearly_sales, 2) }}</h3>
                                <small class="opacity-75">মোট বিক্রি: {{ $total_sold }} টি</small>
                            </div>
                            <i class="fa fa-trophy fa-2x opacity-50"></i>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Agent Stock Inventory Breakdown -->
            <div class="row mb-4">
                <div class="col-12">
                    <div class="box shadow-sm">
                        <div class="box-header with-border bg-light">
                            <h4 class="box-title text-dark"><i class="fa fa-pie-chart me-2 text-warning"></i> এজেন্টের কাছে বর্তমানে মালামাল/স্টক এর মজুদ সমারি</h4>
                        </div>
                        <div class="box-body p-0">
                            <div class="table-responsive">
                                <table class="table table-hover align-middle mb-0">
                                    <thead class="table-light">
                                        <tr>
                                            <th>#</th>
                                            <th>স্টক/পণ্য বিবরণী</th>
                                            <th class="text-center">একক মূল্য (৳)</th>
                                            <th class="text-center">বরাদ্দকৃত স্টক</th>
                                            <th class="text-center">বিক্রিত স্টক</th>
                                            <th class="text-center">অবশিষ্ট লাইভ স্টক</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($agent_stocks as $k => $st)
                                            @php $rem = $st->allocated_quantity - $st->sold_quantity; @endphp
                                            <tr>
                                                <td>{{ $k+1 }}</td>
                                                <td class="fw-bold text-dark">{{ $st->stock_name }}</td>
                                                <td class="text-center">৳{{ number_format($st->unit_price, 2) }}</td>
                                                <td class="text-center fw-bold text-primary">{{ $st->allocated_quantity }} টি</td>
                                                <td class="text-center fw-bold text-warning">{{ $st->sold_quantity }} টি</td>
                                                <td class="text-center">
                                                    <span class="badge bg-success py-2 px-3 fs-6">{{ $rem }} টি</span>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="6" class="text-center py-3 text-muted">এই এজেন্টের কোনো স্টক মালামাল বরাদ্দ নেই।</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Transaction Filter & Ledger -->
            <div class="row">
                <div class="col-12">
                    <div class="box shadow-sm">
                        <div class="box-header with-border bg-light d-flex justify-content-between align-items-center">
                            <h4 class="box-title text-dark mb-0"><i class="fa fa-list-alt me-2 text-primary"></i> এজেন্টের লাইভ লেনদেন হিসেব লগ</h4>
                            <div class="btn-group">
                                <a href="{{ route('admin.agent_ledger.show', $agent->id) }}" class="btn btn-sm btn-outline-secondary {{ !request('filter') ? 'active' : '' }}">সব</a>
                                <a href="{{ route('admin.agent_ledger.show', [$agent->id, 'filter' => 'today']) }}" class="btn btn-sm btn-outline-success {{ request('filter') == 'today' ? 'active' : '' }}">আজকের (Today)</a>
                                <a href="{{ route('admin.agent_ledger.show', [$agent->id, 'filter' => 'month']) }}" class="btn btn-sm btn-outline-info {{ request('filter') == 'month' ? 'active' : '' }}">এই মাসের (Monthly)</a>
                                <a href="{{ route('admin.agent_ledger.show', [$agent->id, 'filter' => 'year']) }}" class="btn btn-sm btn-outline-primary {{ request('filter') == 'year' ? 'active' : '' }}">এই বছরের (Yearly)</a>
                            </div>
                        </div>
                        <div class="box-body p-0">
                            <div class="table-responsive">
                                <table class="table table-hover align-middle mb-0">
                                    <thead class="table-light">
                                        <tr>
                                            <th>SN.</th>
                                            <th>ধরন (Type)</th>
                                            <th>স্টক/পণ্যের নাম</th>
                                            <th class="text-center">পরিমাণ</th>
                                            <th class="text-center">একক মূল্য</th>
                                            <th class="text-center">সর্বমোট (৳)</th>
                                            <th>ক্রেতা/রেফারেন্স ইনফো</th>
                                            <th class="text-center">তারিখ ও সময়</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($transactions as $key => $tx)
                                            <tr>
                                                <td>{{ $key + 1 }}</td>
                                                <td>
                                                    @if($tx->transaction_type == 'allocation')
                                                        <span class="badge bg-primary"><i class="fa fa-download me-1"></i>এডমিন স্টক বরাদ্দ</span>
                                                    @else
                                                        <span class="badge bg-success"><i class="fa fa-shopping-cart me-1"></i>বিক্রি (Sale)</span>
                                                    @endif
                                                </td>
                                                <td class="fw-bold text-dark">{{ $tx->stock_name }}</td>
                                                <td class="text-center fw-bold">{{ $tx->quantity }} টি</td>
                                                <td class="text-center">৳{{ number_format($tx->unit_price, 2) }}</td>
                                                <td class="text-center fw-bold text-success">৳{{ number_format($tx->total_price, 2) }}</td>
                                                <td>
                                                    @if($tx->customer_name)
                                                        <strong>{{ $tx->customer_name }}</strong><br>
                                                        <small class="text-muted"><i class="fa fa-phone me-1"></i>{{ $tx->customer_phone ?? 'N/A' }}</small>
                                                        @if($tx->customer_card_number)
                                                            <br><small class="text-dark fw-bold">Card: {{ $tx->customer_card_number }}</small>
                                                        @endif
                                                    @else
                                                        <span class="text-muted small">{{ $tx->notes ?? 'N/A' }}</span>
                                                    @endif
                                                </td>
                                                <td class="text-center">{{ \Carbon\Carbon::parse($tx->created_at)->format('d/m/Y h:i A') }}</td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="8" class="text-center py-4 text-muted">কোনো লেনদেন রেকর্ড পাওয়া যায়নি।</td>
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
