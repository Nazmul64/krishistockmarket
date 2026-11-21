@extends('layouts.backend.app')

@section('content')
<div class="content-wrapper">
    <div class="container-full">
        <!-- Content Header -->
        <div class="content-header">
            <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
                <div>
                    <h3 class="page-title fw-bold text-dark mb-1">
                        <i class="fa fa-cubes text-success me-2"></i>Product-wise Distribution Report (পণ্যভিত্তিক বিতরণ রিপোর্ট)
                    </h3>
                    <p class="text-muted mb-0">এজেন্ট পয়েন্ট: <strong>{{ $point->name }}</strong> ({{ $point->code }}) | প্রতিটি পণ্যের মোট বিতরণ ও অবশিষ্ট মজুত</p>
                </div>
                <div class="d-flex gap-2">
                    <a href="{{ route('agent.inventory') }}" class="btn btn-outline-success btn-sm">
                        <i class="fa fa-cubes me-1"></i> লাইভ মজুত দেখুন
                    </a>
                    <a href="{{ route('agent.distribution.daily_register') }}" class="btn btn-outline-secondary btn-sm">
                        <i class="fa fa-arrow-left me-1"></i> বিতরণ রেজিস্টার
                    </a>
                </div>
            </div>
        </div>

        <section class="content">
            <!-- Filter Bar -->
            <div class="card border-0 shadow-sm rounded-3 mb-4">
                <div class="card-body p-3">
                    <form method="GET" action="{{ route('agent.distribution.product_report') }}" class="row g-2 align-items-center">
                        <div class="col-md-4">
                            <label class="form-label font-size-12 fw-semibold mb-1">হতে (From Date)</label>
                            <input type="date" name="from_date" class="form-control form-control-sm" value="{{ $fromDate }}">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label font-size-12 fw-semibold mb-1">পর্যন্ত (To Date)</label>
                            <input type="date" name="to_date" class="form-control form-control-sm" value="{{ $toDate }}">
                        </div>
                        <div class="col-md-4 d-flex align-items-end gap-1">
                            <button type="submit" class="btn btn-primary btn-sm flex-fill">
                                <i class="fa fa-filter me-1"></i> রিপোর্ট দেখুন
                            </button>
                            <a href="{{ route('agent.distribution.product_report') }}" class="btn btn-outline-secondary btn-sm">
                                <i class="fa fa-refresh"></i>
                            </a>
                        </div>
                    </form>
                </div>
            </div>

            <!-- 2 Summary Cards -->
            <div class="row g-3 mb-4">
                <div class="col-md-6">
                    <div class="card border-0 shadow-sm rounded-3 bg-white p-3">
                        <div class="text-muted font-size-12 text-uppercase fw-semibold">Total Product Volume Distributed</div>
                        <h3 class="fw-bold text-success mb-0 mt-1">{{ number_format($totalVolume, 1) }}</h3>
                        <small class="text-muted">নির্বাচিত তারিখে মোট বিতরণকৃত পণ্যের পরিমাণ</small>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="card border-0 shadow-sm rounded-3 bg-white p-3">
                        <div class="text-muted font-size-12 text-uppercase fw-semibold">Total Product Value</div>
                        <h3 class="fw-bold text-primary mb-0 mt-1">৳{{ number_format($totalValue, 2) }}</h3>
                        <small class="text-muted">নির্বাচিত তারিখে বিতরণকৃত পণ্যের মোট মূল্য</small>
                    </div>
                </div>
            </div>

            <!-- Product Breakdown Table -->
            <div class="card border-0 shadow-sm rounded-3">
                <div class="card-header bg-white border-bottom py-3">
                    <h5 class="card-title fw-bold text-dark mb-0 font-size-15">
                        <i class="fa fa-list text-primary me-2"></i>পণ্যভিত্তিক বিতরণের পরিমাণ ও পয়েন্ট মজুত
                    </h5>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0 font-size-13">
                            <thead class="table-light text-muted font-size-11 text-uppercase">
                                <tr>
                                    <th class="ps-4">Product Name</th>
                                    <th>Unit</th>
                                    <th class="text-center">Customers Served</th>
                                    <th class="text-end">Total Distributed</th>
                                    <th class="text-end">Total Amount (৳)</th>
                                    <th class="text-end pe-4">Remaining Point Stock</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($distributedItems as $item)
                                @php
                                    $remStock = $stockMap[$item->product_name] ?? 0;
                                @endphp
                                <tr>
                                    <td class="ps-4">
                                        <div class="fw-bold text-dark font-size-14">{{ $item->product_name }}</div>
                                    </td>
                                    <td>
                                        <span class="badge bg-light text-dark font-size-12">{{ $item->unit }}</span>
                                    </td>
                                    <td class="text-center">
                                        <span class="badge bg-info-light text-info font-size-12 px-2 py-1">
                                            {{ $item->total_customers_count }} জন
                                        </span>
                                    </td>
                                    <td class="text-end fw-bold text-success font-size-14">
                                        {{ number_format($item->total_distributed_quantity, 1) }} {{ $item->unit }}
                                    </td>
                                    <td class="text-end fw-bold text-primary font-size-14">
                                        ৳{{ number_format($item->total_distributed_amount, 2) }}
                                    </td>
                                    <td class="text-end pe-4">
                                        <span class="badge bg-{{ $remStock > 10 ? 'success' : ($remStock > 0 ? 'warning' : 'danger') }}-light text-{{ $remStock > 10 ? 'success' : ($remStock > 0 ? 'warning' : 'danger') }} font-size-12 px-2 py-1">
                                            {{ number_format($remStock, 1) }} {{ $item->unit }}
                                        </span>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="6" class="text-center py-5 text-muted">
                                        এই সময়সীমায় কোনো পণ্য বিতরণের রেকর্ড পাওয়া যায়নি।
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </section>
    </div>
</div>
@endsection
