@extends('layouts.backend.app')

@section('content')
<div class="content-wrapper">
<div class="container-full">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="d-flex align-items-center justify-content-between">
            <div class="me-auto">
                <h3 class="page-title">সাপ্লায়ার অ্যাকাউন্ট ও সরবরাহ ব্যবস্থাপনা</h3>
                <div class="d-inline-block align-items-center">
                    <nav>
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('home') }}"><i class="mdi mdi-home-outline"></i></a></li>
                            <li class="breadcrumb-item active" aria-current="page">সাপ্লায়ার তালিকা</li>
                        </ol>
                    </nav>
                </div>
            </div>
            <div>
                <a href="{{ route('admin.suppliers.create') }}" class="btn btn-primary btn-sm"><i class="fa fa-plus me-1"></i> নতুন সাপ্লায়ার যোগ করুন</a>
                <a href="{{ route('admin.suppliers.pending_supplies') }}" class="btn btn-warning btn-sm"><i class="fa fa-clock-o me-1"></i> পেন্ডিং সরবরাহ</a>
            </div>
        </div>
    </div>

    <!-- Main content -->
    <section class="content">
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <strong>সফল!</strong> {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <!-- Stats Cards -->
        <div class="row">
            <div class="col-xl-3 col-md-6 col-12">
                <div class="box bg-primary-light pull-up">
                    <div class="box-body">
                        <div class="d-flex align-items-center justify-content-between">
                            <div>
                                <h4 class="mb-0">{{ number_format($totalSuppliers) }}</h4>
                                <p class="text-muted mb-0">মোট সাপ্লায়ার</p>
                            </div>
                            <div class="bg-primary rounded p-3">
                                <i class="fa fa-users fa-2x text-white"></i>
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
                                <p class="text-muted mb-0">মোট পরিশোধিত অর্থ (Paid)</p>
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
                                <p class="text-muted mb-0">মোট বাকি/বকেয়া (Due)</p>
                            </div>
                            <div class="bg-danger rounded p-3">
                                <i class="fa fa-money fa-2x text-white"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Suppliers Table -->
        <div class="row">
            <div class="col-12">
                <div class="box">
                    <div class="box-header with-border">
                        <h4 class="box-title">সাপ্লায়ার হিসাব বিবরণী</h4>
                    </div>
                    <div class="box-body">
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped" id="example1">
                                <thead>
                                    <tr>
                                        <th>সাপ্লায়ার আইডি</th>
                                        <th>প্রতিষ্ঠানের নাম</th>
                                        <th>সাপ্লায়ারের নাম</th>
                                        <th>মোবাইল নম্বর</th>
                                        <th>ঠিকানা</th>
                                        <th>মোট সরবরাহ মূল্য</th>
                                        <th>মোট পরিশোধ</th>
                                        <th>বর্তমান বকেয়া (Due)</th>
                                        <th>অ্যাকশন</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($suppliers as $sup)
                                        @php
                                            $op = $sup->supplierProfile ? $sup->supplierProfile->opening_balance : 0;
                                            $supplyAmt = $sup->supplies->where('status', 'approved')->sum('total_amount');
                                            $paidAmt = $sup->supplierPayments->sum('amount');
                                            $dueAmt = ($op + $supplyAmt) - $paidAmt;
                                        @endphp
                                        <tr>
                                            <td><span class="badge badge-info">{{ $sup->supplierProfile->supplier_code ?? 'N/A' }}</span></td>
                                            <td><strong>{{ $sup->supplierProfile->company_name ?? 'N/A' }}</strong></td>
                                            <td>{{ $sup->name }}</td>
                                            <td>{{ $sup->phone }}</td>
                                            <td>{{ $sup->supplierProfile->district_thana ?? '' }} {{ $sup->supplierProfile->address ?? '' }}</td>
                                            <td>৳{{ number_format($supplyAmt, 2) }}</td>
                                            <td><span class="text-success">৳{{ number_format($paidAmt, 2) }}</span></td>
                                            <td>
                                                <span class="badge badge-{{ $dueAmt > 0 ? 'danger' : 'success' }} fs-14">
                                                    ৳{{ number_format($dueAmt, 2) }}
                                                </span>
                                            </td>
                                            <td>
                                                <a href="{{ route('admin.suppliers.show', $sup->id) }}" class="btn btn-sm btn-info" title="লেজার ও স্টেটমেন্ট">
                                                    <i class="fa fa-eye"></i> বিস্তারিত
                                                </a>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="9" class="text-center">কোন সাপ্লায়ার পাওয়া যায়নি!</td>
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
