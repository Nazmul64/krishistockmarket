@extends('layouts.backend.app')

@section('content')
<div class="content-wrapper">
<div class="container-full">
    <div class="content-header">
        <div class="d-flex align-items-center justify-content-between">
            <div>
                <h3 class="page-title">সাপ্লায়ার প্রোডাক্ট সাপ্লাই রিপোর্টস ও ফিল্টারিং</h3>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('home') }}"><i class="mdi mdi-home-outline"></i></a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.suppliers.index') }}">সাপ্লায়ার তালিকা</a></li>
                    <li class="breadcrumb-item active">রিপোর্টস</li>
                </ol>
            </div>
            <div>
                <a href="{{ route('admin.suppliers.index') }}" class="btn btn-secondary btn-sm"><i class="fa fa-arrow-left"></i> ড্যাশবোর্ড</a>
            </div>
        </div>
    </div>

    <section class="content">
        <!-- Filter Card -->
        <div class="box">
            <div class="box-header with-border">
                <h4 class="box-title"><i class="fa fa-filter me-2"></i> ফিল্টার শর্তাবলী নির্ধারণ করুন</h4>
            </div>
            <div class="box-body">
                <form action="{{ route('admin.suppliers.reports') }}" method="GET" class="row">
                    <div class="col-md-3 col-12 mb-2">
                        <label class="form-label">সাপ্লায়ার নির্বাচন</label>
                        <select name="supplier_id" class="form-control">
                            <option value="">সকল সাপ্লায়ার</option>
                            @foreach($suppliers as $s)
                                <option value="{{ $s->id }}" {{ request('supplier_id') == $s->id ? 'selected' : '' }}>
                                    {{ $s->supplierProfile->company_name ?? $s->name }} ({{ $s->phone }})
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-3 col-12 mb-2">
                        <label class="form-label">পণ্যের নাম</label>
                        <input type="text" name="product_name" class="form-control" placeholder="যেমন: চাল, আটা, চিনি" value="{{ request('product_name') }}">
                    </div>

                    <div class="col-md-2 col-12 mb-2">
                        <label class="form-label">স্ট্যাটাস</label>
                        <select name="status" class="form-control">
                            <option value="">সকল</option>
                            <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>অনুমোদিত (Approved)</option>
                            <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>পেন্ডিং (Pending)</option>
                            <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>প্রত্যাখ্যাত (Rejected)</option>
                        </select>
                    </div>

                    <div class="col-md-2 col-12 mb-2">
                        <label class="form-label">শুরুর তারিখ</label>
                        <input type="date" name="start_date" class="form-control" value="{{ request('start_date') }}">
                    </div>

                    <div class="col-md-2 col-12 mb-2">
                        <label class="form-label">শেষ তারিখ</label>
                        <input type="date" name="end_date" class="form-control" value="{{ request('end_date') }}">
                    </div>

                    <div class="col-12 text-end mt-2">
                        <button type="submit" class="btn btn-primary"><i class="fa fa-search me-1"></i> ফিল্টার প্রয়োগ করুন</button>
                        <a href="{{ route('admin.suppliers.reports') }}" class="btn btn-secondary me-2">রিসেট</a>
                    </div>
                </form>
            </div>
        </div>

        <!-- Filter Summary Results -->
        <div class="row">
            <div class="col-12 col-md-4">
                <div class="box bg-primary-light">
                    <div class="box-body text-center">
                        <h5 class="text-muted">মোট ফিল্টারকৃত রেকর্ড</h5>
                        <h3 class="fw-bold">{{ number_format($supplies->count()) }} টি</h3>
                    </div>
                </div>
            </div>
            <div class="col-12 col-md-4">
                <div class="box bg-info-light">
                    <div class="box-body text-center">
                        <h5 class="text-muted">মোট ফিল্টারকৃত পরিমাণ</h5>
                        <h3 class="fw-bold text-info">{{ floatval($supplies->sum('quantity')) }} (বিভিন্ন ইউনিট)</h3>
                    </div>
                </div>
            </div>
            <div class="col-12 col-md-4">
                <div class="box bg-success-light">
                    <div class="box-body text-center">
                        <h5 class="text-muted">মোট ফিল্টারকৃত মূল্য</h5>
                        <h3 class="fw-bold text-success">৳{{ number_format($supplies->sum('total_amount'), 2) }}</h3>
                    </div>
                </div>
            </div>
        </div>

        <!-- Reports Table -->
        <div class="row">
            <div class="col-12">
                <div class="box">
                    <div class="box-header with-border d-flex justify-content-between align-items-center">
                        <h4 class="box-title">পণ্য সরবরাহ রিপোর্ট তালিকা</h4>
                        <button onclick="window.print()" class="btn btn-sm btn-dark"><i class="fa fa-print"></i> প্রিন্ট</button>
                    </div>
                    <div class="box-body">
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped" id="example1">
                                <thead class="bg-dark text-white">
                                    <tr>
                                        <th>তারিখ</th>
                                        <th>ইনভয়েস/চালান</th>
                                        <th>প্রতিষ্ঠানের নাম</th>
                                        <th>সাপ্লায়ার</th>
                                        <th>পণ্য</th>
                                        <th>পরিমাণ</th>
                                        <th>রেট (৳)</th>
                                        <th>মোট মূল্য (৳)</th>
                                        <th>স্ট্যাটাস</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($supplies as $sup)
                                        <tr>
                                            <td>{{ $sup->supply_date }}</td>
                                            <td><strong>{{ $sup->invoice_no }}</strong></td>
                                            <td>{{ $sup->supplier->supplierProfile->company_name ?? 'N/A' }}</td>
                                            <td>{{ $sup->supplier->name }} ({{ $sup->supplier->phone }})</td>
                                            <td><span class="badge badge-info">{{ $sup->product_name }}</span></td>
                                            <td>{{ floatval($sup->quantity) }} {{ $sup->unit }}</td>
                                            <td>৳{{ number_format($sup->rate, 2) }}</td>
                                            <td class="fw-bold text-success">৳{{ number_format($sup->total_amount, 2) }}</td>
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
                                            <td colspan="9" class="text-center">কোন ফলাফল পাওয়া যায়নি!</td>
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
