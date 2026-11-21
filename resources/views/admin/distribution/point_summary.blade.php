@extends('layouts.backend.app')

@section('content')
<div class="content-wrapper">
    <div class="container-full">
        <!-- Content Header -->
        <div class="content-header">
            <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
                <div>
                    <h3 class="page-title fw-bold text-dark mb-1">
                        <i class="fa fa-building-o text-primary me-2"></i>{{ $point->name }} - Customer Collection Summary
                    </h3>
                    <p class="text-muted mb-0">এজেন্ট কোড: <strong>{{ $point->code }}</strong> | দায়িত্বপ্রাপ্ত: <strong>{{ $point->agent_name }}</strong> ({{ $point->area }}, {{ $point->district }})</p>
                </div>
                <div class="d-flex gap-2">
                    <a href="{{ route('admin.distribution.dashboard') }}" class="btn btn-outline-secondary btn-sm">
                        <i class="fa fa-arrow-left me-1"></i> ড্যাশবোর্ডে ফিরে যান
                    </a>
                </div>
            </div>
        </div>

        <section class="content">
            <!-- Summary Metric Cards (Section 13) -->
            <div class="row g-3 mb-4">
                <div class="col-xl-3 col-md-6">
                    <div class="card border-0 shadow-sm rounded-3 bg-white p-3 h-100">
                        <div class="text-muted font-size-11 text-uppercase fw-semibold">Total Registered Customers</div>
                        <h3 class="fw-bold text-dark mb-0 mt-1">{{ number_format($point->assigned_card_holders_count) }}</h3>
                        <small class="text-muted">এই পয়েন্টে মোট নির্ধারিত সদস্য</small>
                    </div>
                </div>
                <div class="col-xl-3 col-md-6">
                    <div class="card border-0 shadow-sm rounded-3 bg-white p-3 h-100">
                        <div class="text-muted font-size-11 text-uppercase fw-semibold">Served Today</div>
                        <h3 class="fw-bold text-success mb-0 mt-1">{{ number_format($servedToday) }}</h3>
                        <small class="text-success fw-semibold">আজকের বিতরণ সংখ্যা</small>
                    </div>
                </div>
                <div class="col-xl-3 col-md-6">
                    <div class="card border-0 shadow-sm rounded-3 bg-white p-3 h-100">
                        <div class="text-muted font-size-11 text-uppercase fw-semibold">Monthly / Weekly Collected</div>
                        <div class="d-flex justify-content-between align-items-center mt-1">
                            <div><strong class="text-primary font-size-16">{{ $monthlyCount }}</strong> <small class="text-muted">Monthly</small></div>
                            <div><strong class="text-info font-size-16">{{ $weeklyCount }}</strong> <small class="text-muted">Weekly</small></div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-md-6">
                    <div class="card border-0 shadow-sm rounded-3 bg-white p-3 h-100">
                        <div class="text-muted font-size-11 text-uppercase fw-semibold">Total Distributed Volume</div>
                        <h3 class="fw-bold text-danger mb-0 mt-1">{{ number_format($totalDistributedQty, 1) }}</h3>
                        <small class="text-muted">মোট বিতরণ পরিমাণ (Kg/L)</small>
                    </div>
                </div>
                <div class="col-xl-3 col-md-6">
                    <div class="card border-0 shadow-sm rounded-3 bg-white p-3 h-100">
                        <div class="text-muted font-size-11 text-uppercase fw-semibold">Total Distribution Amount</div>
                        <h3 class="fw-bold text-purple mb-0 mt-1" style="color: #6f42c1;">৳{{ number_format($totalDistributionAmount, 2) }}</h3>
                        <small class="text-muted">মোট বিতরণকৃত পণ্যের মূল্য</small>
                    </div>
                </div>
                <div class="col-xl-3 col-md-6">
                    <div class="card border-0 shadow-sm rounded-3 bg-white p-3 h-100">
                        <div class="text-muted font-size-11 text-uppercase fw-semibold">Completed vs Partial</div>
                        <div class="d-flex justify-content-between align-items-center mt-1 font-size-13">
                            <span class="badge bg-success">Full: {{ $completedCount }}</span>
                            <span class="badge bg-warning text-dark">Partial: {{ $partialCount }}</span>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-md-6">
                    <div class="card border-0 shadow-sm rounded-3 bg-white p-3 h-100">
                        <div class="text-muted font-size-11 text-uppercase fw-semibold">Remaining Customers</div>
                        <h3 class="fw-bold text-warning mb-0 mt-1">{{ number_format($remainingCustomers) }}</h3>
                        <small class="text-muted">বাজারে এখনো বাকি সদস্য</small>
                    </div>
                </div>
            </div>

            <!-- Customer List for this Point (Section 14) -->
            <div class="card border-0 shadow-sm rounded-3">
                <div class="card-header bg-white border-bottom py-3">
                    <h5 class="card-title fw-bold text-dark mb-0 font-size-15">
                        <i class="fa fa-users text-primary me-2"></i>এই এজেন্ট পয়েন্টের কার্ডহোল্ডার গ্রাহকবৃন্দ (Customer List)
                    </h5>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0 font-size-13">
                            <thead class="table-light text-muted font-size-11 text-uppercase">
                                <tr>
                                    <th class="ps-4">Customer Name</th>
                                    <th>Card Number</th>
                                    <th>Phone</th>
                                    <th>Last Market Type</th>
                                    <th class="text-end">Last Amount</th>
                                    <th>Last Collection Date</th>
                                    <th class="text-end pe-4">History</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($customers as $c)
                                @php
                                    $lastCol = $c->cardHolderCollections->first();
                                @endphp
                                <tr>
                                    <td class="ps-4">
                                        <div class="fw-bold text-dark font-size-14">{{ $c->name }}</div>
                                        <div class="text-muted font-size-11">{{ $c->email }}</div>
                                    </td>
                                    <td>
                                        <span class="badge bg-primary-light text-primary font-size-12 fw-bold">
                                            {{ $c->card_number ?? ('CH-' . $c->id) }}
                                        </span>
                                    </td>
                                    <td>
                                        <i class="fa fa-phone text-success me-1"></i> {{ $c->phone ?? 'N/A' }}
                                    </td>
                                    <td>
                                        @if($lastCol)
                                            <span class="badge bg-light text-dark font-size-11">
                                                {{ ucfirst(str_replace('_', ' ', $lastCol->market_type)) }}
                                            </span>
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                    <td class="text-end fw-bold text-primary">
                                        {{ $lastCol ? '৳' . number_format($lastCol->total_amount, 2) : '-' }}
                                    </td>
                                    <td class="text-muted font-size-12">
                                        {{ $lastCol ? date('d M, Y', strtotime($lastCol->collection_date)) : 'কখনো নেননি' }}
                                    </td>
                                    <td class="text-end pe-4">
                                        @if($lastCol)
                                            <a href="{{ route('admin.distribution.transaction_details', $lastCol->id) }}" class="btn btn-outline-info btn-sm py-0 font-size-11">
                                                বিস্তারিত
                                            </a>
                                        @endif
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="7" class="text-center py-5 text-muted">
                                        এই পয়েন্টের কোনো গ্রাহক তথ্য পাওয়া যায়নি।
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                @if($customers->hasPages())
                <div class="card-footer bg-white border-top py-3">
                    <div class="d-flex justify-content-between align-items-center flex-wrap">
                        <span class="text-muted font-size-13">Showing {{ $customers->firstItem() }} to {{ $customers->lastItem() }} of {{ $customers->total() }} customers</span>
                        {{ $customers->links() }}
                    </div>
                </div>
                @endif
            </div>
        </section>
    </div>
</div>
@endsection
