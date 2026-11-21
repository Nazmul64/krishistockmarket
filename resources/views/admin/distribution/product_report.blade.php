@extends('layouts.backend.app')

@section('content')
<div class="content-wrapper">
    <div class="container-full">
        <!-- Content Header -->
        <div class="content-header">
            <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
                <div>
                    <h3 class="page-title fw-bold text-dark mb-1">
                        <i class="fa fa-cubes text-success me-2"></i>Product-wise Central Distribution Report
                    </h3>
                    <p class="text-muted mb-0">কেন্দ্রীয় অ্যাডমিন প্যানেল: পণ্যভিত্তিক সকল এজেন্ট পয়েন্টের বিতরণ ভলিউম, মূল্য ও অবশিষ্ট লাইভ স্টক নিরীক্ষণ</p>
                </div>
                <div class="d-flex gap-2">
                    <a href="{{ route('admin.distribution.dashboard') }}" class="btn btn-outline-secondary btn-sm">
                        <i class="fa fa-arrow-left me-1"></i> ড্যাশবোর্ডে ফিরে যান
                    </a>
                </div>
            </div>
        </div>

        <section class="content">
            <!-- Filter Bar -->
            <div class="card border-0 shadow-sm rounded-3 mb-4">
                <div class="card-body p-3">
                    <form method="GET" action="{{ route('admin.distribution.product_report') }}" class="row g-2 align-items-end">
                        <div class="col-md-4">
                            <label class="form-label font-size-12 fw-semibold mb-1">পণ্য নির্বাচন করুন (Product):</label>
                            <select name="product_name" class="form-select form-select-sm" onchange="this.form.submit()">
                                @if($productNames->isEmpty())
                                    <option value="">কোনো বিতরণকৃত পণ্য পাওয়া যায়নি</option>
                                @else
                                    @foreach($productNames as $pName)
                                        <option value="{{ $pName }}" {{ $selectedProduct == $pName ? 'selected' : '' }}>{{ $pName }}</option>
                                    @endforeach
                                @endif
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label font-size-12 fw-semibold mb-1">হতে (From Date):</label>
                            <input type="date" name="from_date" class="form-control form-control-sm" value="{{ $fromDate }}">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label font-size-12 fw-semibold mb-1">পর্যন্ত (To Date):</label>
                            <input type="date" name="to_date" class="form-control form-control-sm" value="{{ $toDate }}">
                        </div>
                        <div class="col-md-2">
                            <button type="submit" class="btn btn-success btn-sm w-100">
                                <i class="fa fa-filter me-1"></i> ফিল্টার প্রয়োগ
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            @if($selectedProduct)
                <!-- 4 KPI Summary Cards -->
                <div class="row g-3 mb-4">
                    <div class="col-md-3">
                        <div class="card border-0 shadow-sm rounded-3 bg-white p-3">
                            <div class="text-muted font-size-11 text-uppercase fw-semibold">পণ্য (Selected Product)</div>
                            <h4 class="fw-bold text-dark mb-0 mt-1 text-truncate" title="{{ $selectedProduct }}">{{ $selectedProduct }}</h4>
                            <small class="text-muted">{{ date('d M, Y', strtotime($fromDate)) }} - {{ date('d M, Y', strtotime($toDate)) }}</small>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card border-0 shadow-sm rounded-3 bg-white p-3">
                            <div class="text-muted font-size-11 text-uppercase fw-semibold">মোট বিতরণ ভলিউম (Total Distributed)</div>
                            <h3 class="fw-bold text-success mb-0 mt-1">{{ number_format($totalDistributedAll, 1) }}</h3>
                            <small class="text-muted">গ্রাহক সেবা পেয়েছেন: {{ number_format($totalServedAll) }} জন</small>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card border-0 shadow-sm rounded-3 bg-white p-3">
                            <div class="text-muted font-size-11 text-uppercase fw-semibold">বিতরণকৃত পণ্যের মোট মূল্য (Total Value)</div>
                            <h3 class="fw-bold text-primary mb-0 mt-1">৳{{ number_format($totalValueAll, 2) }}</h3>
                            <small class="text-muted">সেন্ট্রাল ট্র্যাকিং রেট অনুযায়ী</small>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card border-0 shadow-sm rounded-3 bg-white p-3">
                            <div class="text-muted font-size-11 text-uppercase fw-semibold">পয়েন্টসমূহে মোট অবশিষ্ট স্টক (Stock On-Hand)</div>
                            <h3 class="fw-bold text-warning mb-0 mt-1">{{ number_format($totalStockAll, 1) }}</h3>
                            <small class="text-muted">সকল সক্রিয় এজেন্ট পয়েন্টে মজুত</small>
                        </div>
                    </div>
                </div>

                <!-- Point Breakdown Table -->
                <div class="card border-0 shadow-sm rounded-3">
                    <div class="card-header bg-white border-0 py-3 d-flex justify-content-between align-items-center">
                        <h5 class="fw-bold mb-0 text-dark">
                            <i class="fa fa-list text-primary me-2"></i>এজেন্ট পয়েন্টভিত্তিক বিতরণ ও অবশিষ্ট স্টক তালিকা
                        </h5>
                        <span class="badge bg-light text-dark border">পয়েন্ট সংখ্যা: {{ $agentDistributionBreakdown->count() }} টি</span>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0 font-size-13">
                                <thead class="table-light">
                                    <tr>
                                        <th class="ps-3">#</th>
                                        <th>এজেন্ট পয়েন্ট ও কোড</th>
                                        <th>এলাকা / জেলা</th>
                                        <th>দায়িত্বপ্রাপ্ত এজেন্ট</th>
                                        <th class="text-center">বিতরণকৃত গ্রাহক</th>
                                        <th class="text-end">বিতরণকৃত পরিমাণ</th>
                                        <th class="text-end">বিতরণ মূল্য</th>
                                        <th class="text-end">পয়েন্টে অবশিষ্ট স্টক</th>
                                        <th class="text-center pe-3">অ্যাকশন</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($agentDistributionBreakdown as $index => $stat)
                                        <tr>
                                            <td class="ps-3">{{ $index + 1 }}</td>
                                            <td>
                                                <div class="fw-bold text-dark">{{ $stat->point->name }}</div>
                                                <small class="text-muted">{{ $stat->point->point_code }}</small>
                                            </td>
                                            <td>
                                                <div>{{ $stat->point->area ?? 'N/A' }}</div>
                                                <small class="text-muted">{{ $stat->point->district ?? '' }}</small>
                                            </td>
                                            <td>
                                                <div>{{ $stat->point->manager_name ?? 'N/A' }}</div>
                                                <small class="text-muted">{{ $stat->point->contact_number ?? '' }}</small>
                                            </td>
                                            <td class="text-center">
                                                <span class="badge bg-info-light text-info fw-bold font-size-12 px-2 py-1">
                                                    {{ $stat->customers_served }} জন
                                                </span>
                                            </td>
                                            <td class="text-end fw-bold text-success font-size-14">
                                                {{ number_format($stat->total_distributed, 1) }} {{ $stat->unit }}
                                            </td>
                                            <td class="text-end fw-bold text-primary font-size-14">
                                                ৳{{ number_format($stat->total_value, 2) }}
                                            </td>
                                            <td class="text-end fw-bold {{ $stat->remaining_stock <= 5 ? 'text-danger' : 'text-warning' }} font-size-14">
                                                {{ number_format($stat->remaining_stock, 1) }} {{ $stat->unit }}
                                                @if($stat->remaining_stock <= 5 && $stat->remaining_stock > 0)
                                                    <span class="badge bg-danger text-white ms-1" style="font-size:10px;">Low</span>
                                                @elseif($stat->remaining_stock == 0)
                                                    <span class="badge bg-dark text-white ms-1" style="font-size:10px;">Out</span>
                                                @endif
                                            </td>
                                            <td class="text-center pe-3">
                                                <a href="{{ route('admin.distribution.point_summary', $stat->point->id) }}" class="btn btn-outline-primary btn-sm" title="পয়েন্ট বিস্তারিত দেখুন">
                                                    <i class="fa fa-eye"></i> সামারি
                                                </a>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="9" class="text-center py-4 text-muted">
                                                কোনো পয়েন্টে এই পণ্যের বিতরণ তথ্য পাওয়া যায়নি।
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                                <tfoot class="table-light fw-bold">
                                    <tr>
                                        <td colspan="4" class="ps-3 text-end">সর্বমোট (Grand Total):</td>
                                        <td class="text-center text-info font-size-14">{{ number_format($totalServedAll) }} জন</td>
                                        <td class="text-end text-success font-size-15">{{ number_format($totalDistributedAll, 1) }}</td>
                                        <td class="text-end text-primary font-size-15">৳{{ number_format($totalValueAll, 2) }}</td>
                                        <td class="text-end text-warning font-size-15">{{ number_format($totalStockAll, 1) }}</td>
                                        <td></td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>
            @else
                <div class="card border-0 shadow-sm rounded-3 p-5 text-center">
                    <i class="fa fa-inbox text-muted mb-3" style="font-size: 3rem;"></i>
                    <h5 class="fw-bold text-dark">কোনো বিতরণকৃত পণ্য পাওয়া যায়নি</h5>
                    <p class="text-muted mb-0">গ্রাহকদের বাজার বিতরণ শুরু হলে পণ্যভিত্তিক এই কেন্দ্রীয় নিরীক্ষণ রিপোর্টটি সক্রিয় হবে।</p>
                </div>
            @endif
        </section>
    </div>
</div>
@endsection
