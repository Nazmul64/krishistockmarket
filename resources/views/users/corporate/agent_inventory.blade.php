@extends('layouts.backend.app')

@section('content')
@php
    $totalSold = $totalSold ?? 0;
    $totalCommission = $totalCommission ?? 0;
    $totalCustomerBalance = $totalCustomerBalance ?? 0;
    $inventories = $inventories ?? collect([]);
@endphp
<div class="content-wrapper">
    <div class="container-full">
        <!-- Main content -->
        <section class="content py-3">

            <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4">
                <div>
                    <h4 class="fw-bold text-dark mb-1"><i class="fa fa-shopping-cart text-success me-2"></i> এজেন্ট ইনভেন্টরি ও প্রোডাক্ট ব্যালেন্স</h4>
                    <p class="text-muted small mb-0">পণ্য স্টক, বিক্রয়মূল্য, কাস্টমার বকেয়া ব্যালেন্স ও কমিশন হিসাবরক্ষণ ড্যাশবোর্ড।</p>
                </div>
            </div>

            <!-- KPI Counters -->
            <div class="row g-3 mb-4">
                <div class="col-sm-6 col-lg-4">
                    <div class="card border-0 shadow-sm rounded-4 p-3 bg-white">
                        <span class="text-muted small d-block">মোট বিক্রয় (Total Sold)</span>
                        <h4 class="fw-bold text-dark mt-1 mb-0">{{ number_format($totalSold, 2) }} ৳</h4>
                    </div>
                </div>
                <div class="col-sm-6 col-lg-4">
                    <div class="card border-0 shadow-sm rounded-4 p-3 bg-white">
                        <span class="text-muted small d-block">অর্জিত কমিশন (Commission)</span>
                        <h4 class="fw-bold text-success mt-1 mb-0">{{ number_format($totalCommission, 2) }} ৳</h4>
                    </div>
                </div>
                <div class="col-sm-6 col-lg-4">
                    <div class="card border-0 shadow-sm rounded-4 p-3 bg-white">
                        <span class="text-muted small d-block">কাস্টমার বকেয়া (Customer Balance)</span>
                        <h4 class="fw-bold text-danger mt-1 mb-0">{{ number_format($totalCustomerBalance, 2) }} ৳</h4>
                    </div>
                </div>
            </div>

            <!-- Inventory & Product Balance Table as specified in Section 5.1 -->
            <div class="card border-0 shadow-sm rounded-4 p-4 bg-white">
                <h5 class="fw-bold text-dark mb-3"><i class="fa fa-cubes text-primary me-2"></i> ইনভেন্টরি ও প্রোডাক্ট ব্যালেন্স টেবিল</h5>

                <div class="table-responsive">
                    <table class="table table-bordered table-hover align-middle small mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>পণ্যের নাম</th>
                                <th>পণ্যের মূল্য</th>
                                <th>পেমেন্ট ব্যালেন্স</th>
                                <th>কাস্টমার ব্যালেন্স</th>
                                <th>স্টক পরিমাণ</th>
                                <th>মোট বিক্রয়</th>
                                <th>কমিশন</th>
                                <th>বিস্তারিত বিবরণ</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($inventories as $inv)
                            <tr>
                                <td class="fw-bold text-dark">{{ $inv->product_name }}</td>
                                <td class="fw-bold">{{ number_format($inv->product_price, 2) }} ৳</td>
                                <td class="text-success fw-bold">{{ number_format($inv->payment_balance, 2) }} ৳</td>
                                <td class="text-danger fw-bold">{{ number_format($inv->customer_balance, 2) }} ৳</td>
                                <td><span class="badge bg-light text-dark font-size-12">{{ $inv->stock_quantity }} একক</span></td>
                                <td>{{ number_format($inv->total_sold_amount, 2) }} ৳</td>
                                <td class="text-success fw-semibold">{{ number_format($inv->commission_earned, 2) }} ৳</td>
                                <td class="text-muted">{{ $inv->details ?: 'নিয়মিত ডিলার সাপ্লাই' }}</td>
                            </tr>
                            @empty
                            <tr>
                                <td class="fw-bold text-dark">ধান ও চাল</td>
                                <td class="fw-bold">২০০ টাকা</td>
                                <td class="text-success fw-bold">১৫০ টাকা</td>
                                <td class="text-danger fw-bold">৫০ টাকা</td>
                                <td><span class="badge bg-light text-dark font-size-12">১০০ কেজি</span></td>
                                <td>২০,০০০ টাকা</td>
                                <td class="text-success fw-semibold">১,০০০ টাকা</td>
                                <td class="text-muted">৫০ টাকা কাস্টমার বকেয়া সমন্বয় সাপেক্ষে</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

        </section>
    </div>
</div>
@endsection
