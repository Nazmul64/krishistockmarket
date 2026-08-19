@extends('layouts.backend.app')

@section('content')
<div class="container-full">
    <div class="content-header">
        <div class="d-flex align-items-center justify-content-between">
            <div>
                <h3 class="page-title">আমার সরবরাহকৃত পণ্যের তালিকা</h3>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('supplier.dashboard') }}"><i class="mdi mdi-home-outline"></i></a></li>
                    <li class="breadcrumb-item active">পণ্য সরবরাহ তালিকা</li>
                </ol>
            </div>
            <div>
                <a href="{{ route('supplier.supplies.create') }}" class="btn btn-primary btn-sm"><i class="fa fa-plus me-1"></i> নতুন পণ্য পোস্ট করুন</a>
            </div>
        </div>
    </div>

    <section class="content">
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <strong>সফল!</strong> {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <div class="row">
            <div class="col-12">
                <div class="box">
                    <div class="box-header with-border">
                        <h4 class="box-title">পণ্য সরবরাহ পোস্টিং রেকর্ডসমূহ</h4>
                    </div>
                    <div class="box-body">
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped" id="example1">
                                <thead class="bg-dark text-white">
                                    <tr>
                                        <th>ইনভয়েস/চালান নং</th>
                                        <th>পণ্যের নাম</th>
                                        <th>ক্যাটাগরি</th>
                                        <th>পরিমাণ</th>
                                        <th>প্রতি ইউনিট রেট (৳)</th>
                                        <th>মোট মূল্য (৳)</th>
                                        <th>সরবরাহের তারিখ</th>
                                        <th>সংযুক্ত ফাইল</th>
                                        <th>অনুমোদন স্ট্যাটাস</th>
                                        <th>অ্যাকশন</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($supplies as $sup)
                                        <tr>
                                            <td><strong>{{ $sup->invoice_no }}</strong></td>
                                            <td><span class="badge badge-info">{{ $sup->product_name }}</span></td>
                                            <td>{{ $sup->category ?? '-' }}</td>
                                            <td>{{ floatval($sup->quantity) }} {{ $sup->unit }}</td>
                                            <td>৳{{ number_format($sup->rate, 2) }}</td>
                                            <td class="fw-bold text-success">৳{{ number_format($sup->total_amount, 2) }}</td>
                                            <td>{{ $sup->supply_date }}</td>
                                            <td>
                                                @if($sup->invoice_file)
                                                    <a href="{{ asset($sup->invoice_file) }}" target="_blank" class="btn btn-xs btn-outline-info">
                                                        <i class="fa fa-file"></i> দেখুন
                                                    </a>
                                                @else
                                                    <span class="text-muted">-</span>
                                                @endif
                                            </td>
                                            <td>
                                                @if($sup->status == 'approved')
                                                    <span class="badge badge-success">অনুমোদিত (Approved)</span>
                                                @elseif($sup->status == 'pending')
                                                    <span class="badge badge-warning">পেন্ডিং (Pending)</span>
                                                @else
                                                    <span class="badge badge-danger">প্রত্যাখ্যাত (Rejected)</span>
                                                @endif
                                            </td>
                                            <td>
                                                <a href="{{ route('supplier.invoice.print', $sup->id) }}" target="_blank" class="btn btn-sm btn-secondary">
                                                    <i class="fa fa-print"></i> চালানের চালান
                                                </a>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="10" class="text-center">আপনি এখনও কোন পণ্য সরবরাহ পোস্টিং করেননি।</td>
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
