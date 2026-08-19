@extends('layouts.backend.app')

@section('content')
<div class="content-wrapper">
<div class="container-full">
    <div class="content-header">
        <div class="d-flex align-items-center justify-content-between">
            <div>
                <h3 class="page-title">সাপ্লায়ার পণ্য সরবরাহ ভেরিফিকেশন (Supply Verification)</h3>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('home') }}"><i class="mdi mdi-home-outline"></i></a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.suppliers.index') }}">সাপ্লায়ার তালিকা</a></li>
                    <li class="breadcrumb-item active">পণ্য সরবরাহ অনুমোদন</li>
                </ol>
            </div>
            <div>
                <a href="{{ route('admin.suppliers.index') }}" class="btn btn-secondary btn-sm"><i class="fa fa-arrow-left"></i> ফিরে যান</a>
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
        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <strong>নোটিশ:</strong> {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <div class="row">
            <div class="col-12">
                <div class="box">
                    <div class="box-header with-border">
                        <h4 class="box-title">পেন্ডিং ও বিগত সরবরাহ রেকর্ডসমূহ</h4>
                    </div>
                    <div class="box-body">
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped" id="example1">
                                <thead class="bg-dark text-white">
                                    <tr>
                                        <th>ইনভয়েস/চালান নং</th>
                                        <th>সাপ্লায়ার / প্রতিষ্ঠান</th>
                                        <th>পণ্য</th>
                                        <th>পরিমাণ</th>
                                        <th>প্রতি ইউনিট রেট</th>
                                        <th>মোট টাকা</th>
                                        <th>সরবরাহের তারিখ</th>
                                        <th>ইনভয়েস ফাইল</th>
                                        <th>স্ট্যাটাস</th>
                                        <th>অ্যাকশন</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($supplies as $sup)
                                        <tr>
                                            <td><strong>{{ $sup->invoice_no }}</strong></td>
                                            <td>
                                                <strong>{{ $sup->supplier->supplierProfile->company_name ?? 'N/A' }}</strong><br>
                                                <small class="text-muted">{{ $sup->supplier->name }} ({{ $sup->supplier->phone }})</small>
                                            </td>
                                            <td>
                                                <span class="badge badge-primary">{{ $sup->product_name }}</span>
                                                @if($sup->category)<br><small class="text-muted">{{ $sup->category }}</small>@endif
                                            </td>
                                            <td>{{ floatval($sup->quantity) }} {{ $sup->unit }}</td>
                                            <td>৳{{ number_format($sup->rate, 2) }}</td>
                                            <td class="fw-bold text-success">৳{{ number_format($sup->total_amount, 2) }}</td>
                                            <td>{{ $sup->supply_date }}</td>
                                            <td>
                                                @if($sup->invoice_file)
                                                    <a href="{{ asset($sup->invoice_file) }}" target="_blank" class="btn btn-xs btn-info">
                                                        <i class="fa fa-paperclip"></i> ফাইল দেখুন
                                                    </a>
                                                @else
                                                    <span class="text-muted">-</span>
                                                @endif
                                            </td>
                                            <td>
                                                @if($sup->status == 'pending')
                                                    <span class="badge badge-warning">পেন্ডিং (Pending)</span>
                                                @elseif($sup->status == 'approved')
                                                    <span class="badge badge-success">অনুমোদিত (Approved)</span>
                                                @else
                                                    <span class="badge badge-danger">প্রত্যাখ্যাত (Rejected)</span>
                                                @endif
                                            </td>
                                            <td>
                                                @if($sup->status == 'pending')
                                                    <a href="{{ route('admin.suppliers.supply.approve', $sup->id) }}" class="btn btn-sm btn-success mb-1" onclick="return confirm('আপনি কি নিশ্চিত যে এই সরবরাহটি অনুমোদন করতে চান? এটি সাপ্লায়ারের মূল লেজারে যুক্ত হবে।');">
                                                        <i class="fa fa-check"></i> অনুমোদন করুন
                                                    </a>
                                                    <a href="{{ route('admin.suppliers.supply.reject', $sup->id) }}" class="btn btn-sm btn-danger mb-1" onclick="return confirm('আপনি কি নিশ্চিত যে এই সরবরাহটি রিজেক্ট করতে চান?');">
                                                        <i class="fa fa-times"></i> রিজেক্ট
                                                    </a>
                                                @else
                                                    <a href="{{ route('admin.suppliers.invoice.print', $sup->id) }}" target="_blank" class="btn btn-sm btn-secondary">
                                                        <i class="fa fa-print"></i> প্রিন্ট
                                                    </a>
                                                @endif
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="10" class="text-center">কোন সরবরাহ তথ্য পাওয়া যায়নি!</td>
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
