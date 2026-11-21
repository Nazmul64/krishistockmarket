@extends('layouts.backend.app')

@section('content')
<div class="content-wrapper">
    <div class="container-full">
        <!-- Content Header -->
        <div class="content-header">
            <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
                <div>
                    <h3 class="page-title fw-bold text-dark mb-1">
                        <i class="fa fa-history text-info me-2"></i>Customer Market Collection History (বাজার সংগ্রহের ইতিহাস)
                    </h3>
                    <p class="text-muted mb-0">গ্রাহক: <strong>{{ $customer->name }}</strong> | কার্ড নং: <strong>{{ $customer->card_number }}</strong></p>
                </div>
                <div class="d-flex gap-2">
                    <a href="{{ route('agent.distribution.entry', ['card_number' => $customer->card_number]) }}" class="btn btn-success btn-sm fw-semibold shadow-sm">
                        <i class="fa fa-shopping-basket me-1"></i> নতুন বাজার প্রদান করুন
                    </a>
                    <a href="{{ route('agent.distribution.customers') }}" class="btn btn-outline-secondary btn-sm">
                        <i class="fa fa-arrow-left me-1"></i> গ্রাহক তালিকা
                    </a>
                </div>
            </div>
        </div>

        <section class="content">
            <!-- Customer Profile & Lifetime Metric Card -->
            <div class="card border-0 shadow-sm rounded-3 mb-4">
                <div class="card-body p-4">
                    <div class="row align-items-center">
                        <div class="col-md-7">
                            <div class="d-flex align-items-center gap-3">
                                <img src="{{ $customer->avatar ? asset($customer->avatar) : asset('frontend/images/default-avatar.png') }}" 
                                     class="rounded-circle border" style="width: 70px; height: 70px; object-fit: cover;" alt="Avatar">
                                <div>
                                    <h4 class="fw-bold text-dark mb-1">{{ $customer->name }}</h4>
                                    <div class="d-flex flex-wrap gap-2 mb-1">
                                        <span class="badge bg-primary fw-bold font-size-12">{{ $customer->card_number }}</span>
                                        <span class="badge bg-light text-dark font-size-12">{{ $customer->customer_id ?? ('ID: ' . $customer->id) }}</span>
                                        <span class="badge bg-success-light text-success font-size-12">{{ ucfirst($customer->status ?? 'Active') }}</span>
                                    </div>
                                    <div class="text-muted font-size-12">
                                        <i class="fa fa-phone me-1 text-success"></i> {{ $customer->phone ?? 'N/A' }} | 
                                        <i class="fa fa-map-marker me-1 text-danger"></i> {{ $customer->present_address ?? $customer->area ?? 'General' }}
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-5 text-md-end mt-3 mt-md-0">
                            <div class="row g-2 text-center">
                                <div class="col-4">
                                    <div class="bg-light p-2 rounded">
                                        <div class="text-muted font-size-11">Monthly Market</div>
                                        <div class="fw-bold text-primary font-size-16">{{ $totalMonthly }} বার</div>
                                    </div>
                                </div>
                                <div class="col-4">
                                    <div class="bg-light p-2 rounded">
                                        <div class="text-muted font-size-11">Weekly Market</div>
                                        <div class="fw-bold text-info font-size-16">{{ $totalWeekly }} বার</div>
                                    </div>
                                </div>
                                <div class="col-4">
                                    <div class="bg-light p-2 rounded">
                                        <div class="text-muted font-size-11">Total Value</div>
                                        <div class="fw-bold text-success font-size-16">৳{{ number_format($totalAmount) }}</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Transaction History Table -->
            <div class="card border-0 shadow-sm rounded-3">
                <div class="card-header bg-white border-bottom py-3">
                    <h5 class="card-title fw-bold text-dark mb-0 font-size-15">
                        <i class="fa fa-list text-primary me-2"></i>সংগ্রহের সকল ট্রানজেকশন তালিকা (Collection Transactions)
                    </h5>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0 font-size-13">
                            <thead class="table-light text-muted font-size-11 text-uppercase">
                                <tr>
                                    <th class="ps-4">Collection Date</th>
                                    <th>Receipt & Txn No</th>
                                    <th>Market Type</th>
                                    <th>Products & Quantities</th>
                                    <th class="text-end">Total Amount</th>
                                    <th class="text-center">Status</th>
                                    <th class="text-end pe-4">Receipt</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($collections as $col)
                                <tr>
                                    <td class="ps-4">
                                        <div class="fw-bold text-dark">{{ date('d M, Y', strtotime($col->collection_date)) }}</div>
                                        <div class="text-muted font-size-11">{{ $col->collection_time ? date('h:i A', strtotime($col->collection_time)) : '' }}</div>
                                    </td>
                                    <td>
                                        <div class="fw-bold text-primary">{{ $col->collection_no }}</div>
                                        <div class="text-muted font-size-11">{{ $col->transaction_id }}</div>
                                    </td>
                                    <td>
                                        <span class="badge bg-light text-dark font-size-12">
                                            {{ ucfirst(str_replace('_', ' ', $col->market_type)) }}
                                        </span>
                                        <div class="text-muted font-size-10 mt-1">{{ $col->market_month }} {{ $col->market_year }}</div>
                                    </td>
                                    <td>
                                        <div class="d-flex flex-wrap gap-1">
                                            @foreach($col->items as $it)
                                                <span class="badge bg-light text-dark font-size-11 border">
                                                    {{ $it->product_name }}: <strong>{{ number_format($it->delivered_quantity, 1) }} {{ $it->unit }}</strong>
                                                </span>
                                            @endforeach
                                        </div>
                                    </td>
                                    <td class="text-end fw-bold text-primary font-size-14">
                                        ৳{{ number_format($col->total_amount, 2) }}
                                    </td>
                                    <td class="text-center">
                                        <span class="badge bg-{{ $col->collection_status == 'fully_collected' ? 'success' : 'warning' }}-light text-{{ $col->collection_status == 'fully_collected' ? 'success' : 'dark' }} font-size-11 px-2 py-1 rounded-pill">
                                            {{ ucfirst(str_replace('_', ' ', $col->collection_status)) }}
                                        </span>
                                    </td>
                                    <td class="text-end pe-4">
                                        <a href="{{ route('agent.distribution.receipt', $col->id) }}" class="btn btn-outline-primary btn-sm py-0 font-size-11">
                                            <i class="fa fa-print me-1"></i> রসিদ দেখুন
                                        </a>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="7" class="text-center py-5 text-muted">
                                        এই গ্রাহকের জন্য কোনো পূর্বের বাজার সংগ্রহের রেকর্ড নেই।
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                @if($collections->hasPages())
                <div class="card-footer bg-white border-top py-3">
                    <div class="d-flex justify-content-between align-items-center flex-wrap">
                        <span class="text-muted font-size-13">Showing {{ $collections->firstItem() }} to {{ $collections->lastItem() }} of {{ $collections->total() }} records</span>
                        {{ $collections->links() }}
                    </div>
                </div>
                @endif
            </div>
        </section>
    </div>
</div>
@endsection
