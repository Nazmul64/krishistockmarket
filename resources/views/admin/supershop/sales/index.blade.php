@extends('layouts.backend.app')

@section('content')
<div class="content-wrapper">
    <div class="container-full">
        <!-- Content Header -->
        <div class="content-header">
            <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
                <div>
                    <h3 class="page-title text-dark fw-bold mb-1">
                        <i class="ti-receipt text-success me-2"></i>POS বিক্রয় ও ইনভয়েস লেনদেন (POS Sales History)
                    </h3>
                    <p class="text-muted mb-0">সকল শাখার রিটেল কাউন্টার বিক্রয়, পেমেন্ট মেথড ও রিটার্ন রিফান্ড হিস্ট্রি</p>
                </div>
                <div>
                    <a href="{{ route('admin.supershop.pos') }}" class="btn btn-sm btn-success fw-bold">
                        <i class="ti-shopping-cart me-1"></i> নতুন POS বিক্রয় করুন
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

            <!-- Filter Bar & Summary -->
            <div class="box shadow-sm mb-3">
                <div class="box-body py-2">
                    <form method="GET" action="{{ route('admin.supershop.sales') }}" class="row g-2 align-items-center">
                        <div class="col-md-3 col-12">
                            <input type="text" name="search" class="form-control form-control-sm" placeholder="ইনভয়েস # বা কাস্টমার ফোন..." value="{{ $search }}">
                        </div>

                        <div class="col-md-2 col-6">
                            <select name="branch_id" class="form-select form-select-sm" onchange="this.form.submit()">
                                <option value="">🏢 সকল ব্রাঞ্চ</option>
                                @foreach($branches as $b)
                                    <option value="{{ $b->id }}" {{ $selectedBranchId == $b->id ? 'selected' : '' }}>
                                        {{ $b->code }} - {{ $b->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-2 col-6">
                            <select name="payment_method" class="form-select form-select-sm" onchange="this.form.submit()">
                                <option value="">💳 সকল মেথড</option>
                                <option value="cash" {{ $paymentMethod == 'cash' ? 'selected' : '' }}>ক্যাশ (Cash)</option>
                                <option value="bkash" {{ $paymentMethod == 'bkash' ? 'selected' : '' }}>বিকাশ (bKash)</option>
                                <option value="nagad" {{ $paymentMethod == 'nagad' ? 'selected' : '' }}>নগদ (Nagad)</option>
                                <option value="card" {{ $paymentMethod == 'card' ? 'selected' : '' }}>কার্ড (Card)</option>
                            </select>
                        </div>

                        <div class="col-md-2 col-6">
                            <input type="date" name="from_date" class="form-control form-control-sm" value="{{ $fromDate }}" placeholder="From Date">
                        </div>

                        <div class="col-md-2 col-6">
                            <input type="date" name="to_date" class="form-control form-control-sm" value="{{ $toDate }}" placeholder="To Date">
                        </div>

                        <div class="col-md-1 col-12 text-end">
                            <button type="submit" class="btn btn-sm btn-primary w-100"><i class="ti-search"></i></button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Sales Table -->
            <div class="box shadow-sm">
                <div class="box-header with-border d-flex justify-content-between align-items-center">
                    <h4 class="box-title fw-bold text-dark mb-0">ইনভয়েস তালিকা ({{ $sales->total() }} টি লেনদেন)</h4>
                    <span class="badge bg-success-light text-success fs-6">মোট বিক্রয়: ৳ {{ number_format($totalSalesAmount, 2) }}</span>
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
                                    <th class="text-end">মোট মূল্য</th>
                                    <th class="text-center">পেমেন্ট মেথড</th>
                                    <th class="text-center">তারিখ ও সময়</th>
                                    <th class="text-center">স্ট্যাটাস</th>
                                    <th class="text-end">অ্যাকশন</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($sales as $sale)
                                    <tr>
                                        <td>
                                            <a href="{{ route('admin.supershop.sales.show', $sale->id) }}" class="fw-bold text-primary">
                                                {{ $sale->invoice_no }}
                                            </a>
                                        </td>
                                        <td>
                                            <span class="badge bg-dark">{{ $sale->branch ? $sale->branch->code : '' }}</span>
                                            <span class="small ms-1">{{ $sale->branch ? $sale->branch->name : '' }}</span>
                                        </td>
                                        <td>
                                            <span class="small">{{ $sale->cashier ? $sale->cashier->name : 'N/A' }}</span>
                                        </td>
                                        <td>
                                            <div class="small fw-bold">{{ $sale->customer_name ?: 'Walk-in Guest' }}</div>
                                            @if($sale->customer_phone)
                                                <div class="text-muted smaller">{{ $sale->customer_phone }}</div>
                                            @endif
                                        </td>
                                        <td class="text-center">
                                            <span class="badge bg-light text-dark border">{{ $sale->items->count() }}</span>
                                        </td>
                                        <td class="text-end fw-bold text-success">
                                            ৳ {{ number_format($sale->grand_total, 2) }}
                                        </td>
                                        <td class="text-center">
                                            <span class="badge bg-info-light text-info text-uppercase">{{ $sale->payment_method }}</span>
                                        </td>
                                        <td class="text-center small text-muted">
                                            {{ \Carbon\Carbon::parse($sale->sale_time)->format('d M Y, h:i A') }}
                                        </td>
                                        <td class="text-center">
                                            <span class="badge bg-success">সম্পূর্ণ</span>
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
                                        <td colspan="10" class="text-center py-4 text-muted">কোনো POS বিক্রয় তথ্য পাওয়া যায়নি।</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
                @if($sales->hasPages())
                    <div class="box-footer">
                        {{ $sales->links() }}
                    </div>
                @endif
            </div>
        </section>
    </div>
</div>
@endsection

