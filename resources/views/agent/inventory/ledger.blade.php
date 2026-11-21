@extends('layouts.backend.app')

@section('content')
<div class="content-wrapper">
    <div class="container-full">
        <!-- Content Header -->
        <div class="content-header">
            <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
                <div>
                    <h3 class="page-title fw-bold text-dark mb-1">
                        <i class="fa fa-list-alt text-primary me-2"></i>Stock Transaction Ledger (স্টক খতিয়ান ও হিসাব)
                    </h3>
                    <p class="text-muted mb-0">এজেন্ট পয়েন্ট: <strong>{{ $point->name }}</strong> | প্রতিটি ইন-আউট পণ্যের ধারাবাহিক অডিট লগ</p>
                </div>
                <div>
                    <a href="{{ route('agent.inventory') }}" class="btn btn-outline-success btn-sm">
                        <i class="fa fa-cubes me-1"></i> লাইভ মজুত দেখুন
                    </a>
                    <a href="{{ route('agent.dashboard') }}" class="btn btn-outline-secondary btn-sm ms-1">
                        <i class="fa fa-dashboard me-1"></i> ড্যাশবোর্ড
                    </a>
                </div>
            </div>
        </div>

        <section class="content">
            <!-- Filter Bar -->
            <div class="card border-0 shadow-sm rounded-3 mb-4">
                <div class="card-body p-3">
                    <form method="GET" action="{{ route('agent.stock.ledger') }}" class="row g-2 align-items-center">
                        <div class="col-md-3">
                            <label class="form-label font-size-12 fw-semibold mb-1">পণ্যের নাম (Product)</label>
                            <input type="text" name="product_name" class="form-control form-control-sm" 
                                   placeholder="নাম দিয়ে ফিল্টার..." value="{{ request('product_name') }}">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label font-size-12 fw-semibold mb-1">লেনদেনের ধরন (Transaction Type)</label>
                            <select name="transaction_type" class="form-select form-select-sm">
                                <option value="">-- All Types (সকল ধরন) --</option>
                                <option value="received_from_supplier" {{ request('transaction_type') == 'received_from_supplier' ? 'selected' : '' }}>Received from Supplier (সাপ্লায়ার থেকে)</option>
                                <option value="received_from_warehouse" {{ request('transaction_type') == 'received_from_warehouse' ? 'selected' : '' }}>Received from Warehouse (ওয়্যারহাউজ থেকে)</option>
                                <option value="delivered_to_cardholder" {{ request('transaction_type') == 'delivered_to_cardholder' ? 'selected' : '' }}>Delivered to Cardholder (গ্রাহককে বিতরণ)</option>
                                <option value="damaged" {{ request('transaction_type') == 'damaged' ? 'selected' : '' }}>Damaged / Loss (নষ্ট/ক্ষতি)</option>
                                <option value="adjustment" {{ request('transaction_type') == 'adjustment' ? 'selected' : '' }}>Stock Adjustment (সমন্বয়)</option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label class="form-label font-size-12 fw-semibold mb-1">হতে (From Date)</label>
                            <input type="date" name="from_date" class="form-control form-control-sm" value="{{ request('from_date') }}">
                        </div>
                        <div class="col-md-2">
                            <label class="form-label font-size-12 fw-semibold mb-1">পর্যন্ত (To Date)</label>
                            <input type="date" name="to_date" class="form-control form-control-sm" value="{{ request('to_date') }}">
                        </div>
                        <div class="col-md-2 d-flex align-items-end gap-1">
                            <button type="submit" class="btn btn-primary btn-sm flex-fill">
                                <i class="fa fa-filter me-1"></i> ফিল্টার
                            </button>
                            <a href="{{ route('agent.stock.ledger') }}" class="btn btn-outline-secondary btn-sm" title="রিসেট">
                                <i class="fa fa-refresh"></i>
                            </a>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Ledger Table -->
            <div class="card border-0 shadow-sm rounded-3">
                <div class="card-header bg-white border-bottom py-3">
                    <h5 class="card-title fw-bold text-dark mb-0 font-size-15">
                        <i class="fa fa-book text-primary me-2"></i>ধারাবাহিক স্টক ট্রানজেকশন লগ
                    </h5>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0 font-size-13">
                            <thead class="table-light text-muted font-size-11 text-uppercase">
                                <tr>
                                    <th class="ps-4">Date</th>
                                    <th>Product Name</th>
                                    <th>Transaction Type</th>
                                    <th class="text-end">Stock In (+)</th>
                                    <th class="text-end">Stock Out (-)</th>
                                    <th class="text-end">Balance After</th>
                                    <th>Reference No</th>
                                    <th>Notes & Audit</th>
                                    <th class="pe-4">Recorded By</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($ledgers as $log)
                                <tr>
                                    <td class="ps-4 text-nowrap">
                                        <div class="fw-semibold text-dark">{{ date('d M, Y', strtotime($log->transaction_date)) }}</div>
                                        <div class="text-muted font-size-11">{{ $log->created_at ? $log->created_at->format('h:i A') : '' }}</div>
                                    </td>
                                    <td>
                                        <span class="fw-bold text-dark">{{ $log->product_name }}</span>
                                        <span class="badge bg-light text-muted font-size-11 ms-1">{{ $log->unit }}</span>
                                    </td>
                                    <td>
                                        @if($log->transaction_type == 'received_from_supplier' || $log->transaction_type == 'received_from_warehouse')
                                            <span class="badge bg-success-light text-success font-size-11">
                                                <i class="fa fa-arrow-down me-1"></i> Stock In
                                            </span>
                                            <div class="text-muted font-size-11 mt-1">
                                                {{ $log->transaction_type == 'received_from_supplier' ? 'Direct Supplier' : 'Warehouse' }}
                                            </div>
                                        @elseif($log->transaction_type == 'delivered_to_cardholder')
                                            <span class="badge bg-danger-light text-danger font-size-11">
                                                <i class="fa fa-arrow-up me-1"></i> Cardholder Delivery
                                            </span>
                                        @else
                                            <span class="badge bg-warning-light text-warning font-size-11">
                                                {{ ucfirst(str_replace('_', ' ', $log->transaction_type)) }}
                                            </span>
                                        @endif
                                    </td>
                                    <td class="text-end">
                                        @if($log->quantity_in > 0)
                                            <span class="fw-bold text-success">+{{ number_format($log->quantity_in, 1) }}</span>
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                    <td class="text-end">
                                        @if($log->quantity_out > 0)
                                            <span class="fw-bold text-danger">-{{ number_format($log->quantity_out, 1) }}</span>
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                    <td class="text-end">
                                        <span class="fw-bold text-dark font-size-14">{{ number_format($log->balance_after, 1) }}</span>
                                    </td>
                                    <td>
                                        <span class="badge bg-light text-dark font-size-11">{{ $log->reference_no ?? 'N/A' }}</span>
                                    </td>
                                    <td>
                                        <span class="text-muted font-size-12">{{ $log->notes ?? '-' }}</span>
                                    </td>
                                    <td class="pe-4">
                                        <span class="text-dark font-size-12">{{ optional($log->recorder)->name ?? 'System' }}</span>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="9" class="text-center py-5 text-muted">
                                        কোনো স্টক লেনদেনের রেকর্ড পাওয়া যায়নি
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                @if($ledgers->hasPages())
                <div class="card-footer bg-white border-top py-3">
                    <div class="d-flex justify-content-between align-items-center flex-wrap">
                        <span class="text-muted font-size-13">Showing {{ $ledgers->firstItem() }} to {{ $ledgers->lastItem() }} of {{ $ledgers->total() }} entries</span>
                        {{ $ledgers->links() }}
                    </div>
                </div>
                @endif
            </div>
        </section>
    </div>
</div>
@endsection
