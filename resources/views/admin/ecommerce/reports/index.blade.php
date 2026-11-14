@extends('layouts.backend.app')

@section('content')
<div class="content-wrapper">
    <div class="container-full">
        <div class="content-header">
            <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
                <div>
                    <h3 class="page-title text-dark fw-bold mb-1"><i class="ti-bar-chart text-primary me-2"></i>সেলস ও অর্ডার অ্যানালিটিক্স রিপোর্ট</h3>
                    <p class="text-muted mb-0">নির্দিষ্ট তারিখ অনুযায়ী বিক্রয়, ডিসকাউন্ট, ডেলিভারি আয় ও অর্ডার রিপোর্ট</p>
                </div>
                <button onclick="window.print()" class="btn btn-outline-secondary">
                    <i class="ti-printer me-1"></i> রিপোর্ট প্রিন্ট
                </button>
            </div>
        </div>

        <section class="content">
            <!-- Date Filter Box -->
            <div class="box shadow-sm mb-4">
                <div class="box-body">
                    <form action="{{ route('admin.ecommerce.reports') }}" method="GET" class="row g-3 align-items-end">
                        <div class="col-md-4 col-12">
                            <label class="form-label fw-bold">শুরুর তারিখ</label>
                            <input type="date" name="start_date" class="form-control" value="{{ $startDate }}">
                        </div>
                        <div class="col-md-4 col-12">
                            <label class="form-label fw-bold">শেষের তারিখ</label>
                            <input type="date" name="end_date" class="form-control" value="{{ $endDate }}">
                        </div>
                        <div class="col-md-4 col-12">
                            <button type="submit" class="btn btn-primary w-100">
                                <i class="ti-search me-1"></i> রিপোর্ট তৈরি করুন
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Financial Metrics Cards -->
            <div class="row g-3 mb-4">
                <div class="col-xl-3 col-md-6 col-12">
                    <div class="card border-0 shadow-sm bg-primary text-white">
                        <div class="card-body">
                            <div class="text-white-50 small">মোট বিক্রয় (Total Sales)</div>
                            <h3 class="fw-bold mb-0 text-white mt-1">৳ {{ number_format($totalSales, 0) }}</h3>
                            <div class="mt-2 small text-white-50">ক্যান্সেলড অর্ডার ব্যতীত</div>
                        </div>
                    </div>
                </div>

                <div class="col-xl-3 col-md-6 col-12">
                    <div class="card border-0 shadow-sm bg-info text-white">
                        <div class="card-body">
                            <div class="text-white-50 small">মোট অর্ডার সংখ্যা</div>
                            <h3 class="fw-bold mb-0 text-white mt-1">{{ $totalOrders }} টি</h3>
                            <div class="mt-2 small text-white-50">সম্পন্ন: {{ $completedOrders }} টি</div>
                        </div>
                    </div>
                </div>

                <div class="col-xl-3 col-md-6 col-12">
                    <div class="card border-0 shadow-sm bg-success text-white">
                        <div class="card-body">
                            <div class="text-white-50 small">ডেলিভারি চার্জ আয়</div>
                            <h3 class="fw-bold mb-0 text-white mt-1">৳ {{ number_format($shippingRevenue, 0) }}</h3>
                            <div class="mt-2 small text-white-50">শিপিং ফি বাবদ আদায়</div>
                        </div>
                    </div>
                </div>

                <div class="col-xl-3 col-md-6 col-12">
                    <div class="card border-0 shadow-sm bg-warning text-white">
                        <div class="card-body">
                            <div class="text-white-50 small">মোট দেওয়া ডিসকাউন্ট</div>
                            <h3 class="fw-bold mb-0 text-white mt-1">৳ {{ number_format($totalDiscounts, 0) }}</h3>
                            <div class="mt-2 small text-white-50">কুপন ও অফার ছাড়</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Detailed Orders in Report -->
            <div class="box shadow-sm">
                <div class="box-header with-border">
                    <h4 class="box-title fw-bold">রিপোর্ট সময়সীমার অর্ডার তালিকা ({{ count($orders) }} টি অর্ডার)</h4>
                </div>
                <div class="box-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>তারিখ</th>
                                    <th>অর্ডার #</th>
                                    <th>গ্রাহক</th>
                                    <th>ফোন</th>
                                    <th>পণ্য মূল্য</th>
                                    <th>শিপিং</th>
                                    <th>ছাড়</th>
                                    <th class="text-end">মোট বিল</th>
                                    <th>স্ট্যাটাস</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($orders as $ord)
                                    <tr>
                                        <td>{{ $ord->created_at->format('d M Y') }}</td>
                                        <td>
                                            <a href="{{ route('admin.ecommerce.orders.show', $ord->id) }}" class="fw-bold text-decoration-none">
                                                #{{ $ord->order_number }}
                                            </a>
                                        </td>
                                        <td>{{ $ord->first_name }} {{ $ord->last_name }}</td>
                                        <td>{{ $ord->phone }}</td>
                                        <td>৳ {{ number_format($ord->subtotal, 0) }}</td>
                                        <td>৳ {{ number_format($ord->shipping_charge, 0) }}</td>
                                        <td class="text-danger">-৳ {{ number_format($ord->discount_amount, 0) }}</td>
                                        <td class="text-end fw-bold">৳ {{ number_format($ord->total_amount, 0) }}</td>
                                        <td>
                                            <span class="badge {{ $ord->order_status === 'completed' || $ord->order_status === 'delivered' ? 'bg-success' : ($ord->order_status === 'cancelled' ? 'bg-danger' : 'bg-warning text-dark') }}">
                                                {{ ucfirst($ord->order_status) }}
                                            </span>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="9" class="text-center py-5 text-muted">
                                            নির্বাচিত সময়ে কোনো অর্ডার রেকর্ড পাওয়া যায়নি!
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

