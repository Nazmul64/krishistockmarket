@extends('layouts.backend.app')

@section('content')
<div class="container-full">
    <div class="content-header">
        <div class="d-flex align-items-center justify-content-between">
            <div>
                <h3 class="page-title">সাপ্লায়ার ড্যাশবোর্ড (Supplier Portal)</h3>
                <p class="text-muted mb-0">স্বাগতম, <strong>{{ $user->supplierProfile->company_name ?? $user->name }}</strong> (আইডি: {{ $user->supplierProfile->supplier_code ?? 'N/A' }})</p>
            </div>
            <div>
                <a href="{{ route('supplier.supplies.create') }}" class="btn btn-success btn-sm">
                    <i class="fa fa-plus-circle me-1"></i> নতুন পণ্য এন্ট্রি করুন
                </a>
                <a href="{{ route('supplier.statement') }}" class="btn btn-primary btn-sm">
                    <i class="fa fa-file-text-o me-1"></i> স্টেটমেন্ট দেখুন
                </a>
            </div>
        </div>
    </div>

    <section class="content">
        <!-- Overview Stat Cards -->
        <div class="row">
            <div class="col-xl-3 col-md-6 col-12">
                <div class="box bg-primary-light pull-up">
                    <div class="box-body">
                        <div class="d-flex align-items-center justify-content-between">
                            <div>
                                <h4 class="mb-0">{{ number_format($totalSuppliesCount) }}</h4>
                                <p class="text-muted mb-0">মোট পোস্ট করা চালান</p>
                                <small class="text-success">{{ $approvedCount }} টি অনুমোদিত</small> |
                                <small class="text-warning">{{ $pendingCount }} টি পেন্ডিং</small>
                            </div>
                            <div class="bg-primary rounded p-3">
                                <i class="fa fa-truck fa-2x text-white"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-md-6 col-12">
                <div class="box bg-info-light pull-up">
                    <div class="box-body">
                        <div class="d-flex align-items-center justify-content-between">
                            <div>
                                <h4 class="mb-0">৳{{ number_format($totalSupplyAmount, 2) }}</h4>
                                <p class="text-muted mb-0">মোট সরবরাহকৃত পণ্য মূল্য</p>
                            </div>
                            <div class="bg-info rounded p-3">
                                <i class="fa fa-cubes fa-2x text-white"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-md-6 col-12">
                <div class="box bg-success-light pull-up">
                    <div class="box-body">
                        <div class="d-flex align-items-center justify-content-between">
                            <div>
                                <h4 class="mb-0">৳{{ number_format($totalPaidAmount, 2) }}</h4>
                                <p class="text-muted mb-0">মোট প্রাপ্ত পরিশোধ (Paid)</p>
                            </div>
                            <div class="bg-success rounded p-3">
                                <i class="fa fa-check-circle fa-2x text-white"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-md-6 col-12">
                <div class="box bg-danger-light pull-up">
                    <div class="box-body">
                        <div class="d-flex align-items-center justify-content-between">
                            <div>
                                <h4 class="mb-0">৳{{ number_format($totalDue, 2) }}</h4>
                                <p class="text-muted mb-0">বর্তমান পাওনা / বকেয়া (Due)</p>
                            </div>
                            <div class="bg-danger rounded p-3">
                                <i class="fa fa-money fa-2x text-white"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Recent Activity Section -->
        <div class="row">
            <div class="col-lg-7 col-12">
                <div class="box">
                    <div class="box-header with-border d-flex justify-content-between align-items-center">
                        <h4 class="box-title"><i class="fa fa-list me-2"></i> সাম্প্রতিক পণ্য সরবরাহ (Recent Product Supplies)</h4>
                        <a href="{{ route('supplier.supplies.index') }}" class="btn btn-xs btn-primary">সব দেখুন</a>
                    </div>
                    <div class="box-body">
                        <div class="table-responsive">
                            <table class="table table-bordered table-hover">
                                <thead class="bg-light">
                                    <tr>
                                        <th>চালান নং</th>
                                        <th>পণ্য</th>
                                        <th>পরিমাণ</th>
                                        <th>মোট মূল্য</th>
                                        <th>তারিখ</th>
                                        <th>স্ট্যাটাস</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($recentSupplies as $sup)
                                        <tr>
                                            <td><strong>{{ $sup->invoice_no }}</strong></td>
                                            <td>{{ $sup->product_name }}</td>
                                            <td>{{ floatval($sup->quantity) }} {{ $sup->unit }}</td>
                                            <td>৳{{ number_format($sup->total_amount, 2) }}</td>
                                            <td>{{ $sup->supply_date }}</td>
                                            <td>
                                                @if($sup->status == 'approved')
                                                    <span class="badge badge-success">Approved</span>
                                                @elseif($sup->status == 'pending')
                                                    <span class="badge badge-warning">Pending</span>
                                                @else
                                                    <span class="badge badge-danger">Rejected</span>
                                                @endif
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="6" class="text-center">কোন পণ্য সরবরাহ এন্ট্রি নেই</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-5 col-12">
                <div class="box">
                    <div class="box-header with-border">
                        <h4 class="box-title"><i class="fa fa-history me-2"></i> সাম্প্রতিক প্রাপ্ত পেমেন্টসমূহ (Recent Payments)</h4>
                    </div>
                    <div class="box-body">
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped">
                                <thead class="bg-light">
                                    <tr>
                                        <th>তারিখ</th>
                                        <th>মেথড</th>
                                        <th>টাকা (৳)</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($recentPayments as $pay)
                                        <tr>
                                            <td>{{ $pay->payment_date }}</td>
                                            <td>
                                                <span class="badge badge-info">{{ strtoupper($pay->payment_method) }}</span>
                                                @if($pay->bank_name)<br><small class="text-muted">{{ $pay->bank_name }}</small>@endif
                                            </td>
                                            <td class="fw-bold text-success">৳{{ number_format($pay->amount, 2) }}</td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="3" class="text-center">কোন পেমেন্ট রেকর্ড নেই</td>
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
@endsection
