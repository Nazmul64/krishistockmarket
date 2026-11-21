@extends('layouts.backend.app')

@section('content')
<div class="content-wrapper">
    <div class="container-full">
        <!-- Content Header -->
        <div class="content-header">
            <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
                <div>
                    <h3 class="page-title fw-bold text-dark mb-1">
                        <i class="fa fa-file-text-o text-primary me-2"></i>Collection Transaction Details (বিতরণ বিস্তারিত)
                    </h3>
                    <p class="text-muted mb-0">রসিদ নং: <strong>{{ $collection->collection_no }}</strong> | ট্রানজেকশন আইডি: <strong>{{ $collection->transaction_id }}</strong></p>
                </div>
                <div class="d-flex gap-2">
                    <a href="{{ route('admin.distribution.receipt', $collection->id) }}" target="_blank" class="btn btn-primary btn-sm shadow-sm">
                        <i class="fa fa-print me-1"></i> রসিদ প্রিন্ট করুন (Print Receipt)
                    </a>
                    <a href="{{ url()->previous() }}" class="btn btn-outline-secondary btn-sm">
                        <i class="fa fa-arrow-left me-1"></i> ফিরে যান
                    </a>
                </div>
            </div>
        </div>

        <section class="content">
            <div class="row justify-content-center">
                <div class="col-xl-9 col-lg-10">
                    <div class="card border-0 shadow-sm rounded-3">
                        <div class="card-header bg-white border-bottom py-3">
                            <div class="d-flex justify-content-between align-items-center flex-wrap">
                                <h5 class="card-title fw-bold text-dark mb-0 font-size-15">
                                    <i class="fa fa-info-circle text-info me-2"></i>গ্রাহক ও পয়েন্ট পরিচিতি
                                </h5>
                                <span class="badge bg-success-light text-success px-3 py-2 font-size-12 fw-bold">
                                    {{ strtoupper($collection->collection_status) }}
                                </span>
                            </div>
                        </div>

                        <div class="card-body p-4">
                            <!-- Info Grid -->
                            <div class="row g-4 mb-4 pb-3 border-bottom font-size-13">
                                <div class="col-md-6">
                                    <div class="p-3 bg-light rounded-3">
                                        <h6 class="text-muted text-uppercase font-size-11 fw-bold mb-2">গ্রাহকের তথ্য (Customer Details)</h6>
                                        <div class="fw-bold font-size-15 text-dark">{{ $collection->customer_name }}</div>
                                        <div class="text-primary fw-semibold mt-1">কার্ড নম্বর: {{ $collection->card_number }}</div>
                                        <div class="text-muted mt-1"><i class="fa fa-phone me-1"></i> {{ $collection->customer_phone ?? 'N/A' }}</div>
                                        <div class="text-muted mt-1"><i class="fa fa-map-marker me-1"></i> {{ optional($collection->user)->present_address ?? optional($collection->user)->area ?? 'N/A' }}</div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="p-3 bg-light rounded-3">
                                        <h6 class="text-muted text-uppercase font-size-11 fw-bold mb-2">এজেন্ট পয়েন্ট ও ডেলিভারি বিবরণ</h6>
                                        <div class="fw-bold font-size-15 text-dark">{{ optional($collection->agentPoint)->name }}</div>
                                        <div class="text-muted font-size-12">কোড: {{ optional($collection->agentPoint)->code }}</div>
                                        <div class="text-muted mt-1">
                                            তারিখ ও সময়: <strong>{{ date('d M, Y', strtotime($collection->collection_date)) }}</strong>, {{ $collection->collection_time ? date('h:i A', strtotime($collection->collection_time)) : '' }}
                                        </div>
                                        <div class="mt-1">
                                            বাজারের ধরন: <span class="badge bg-light text-dark fw-bold">{{ ucfirst(str_replace('_', ' ', $collection->market_type)) }}</span>
                                            ({{ $collection->market_month }} {{ $collection->market_year }})
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Products Table -->
                            <h6 class="fw-bold text-dark mb-3">
                                <i class="fa fa-cubes text-primary me-1"></i>বিতরণকৃত পণ্যের বিবরণ (Delivered Products)
                            </h6>
                            <div class="table-responsive mb-4">
                                <table class="table table-bordered align-middle font-size-13 mb-0">
                                    <thead class="table-light text-uppercase font-size-11">
                                        <tr>
                                            <th class="ps-3" style="width: 50px;">SL</th>
                                            <th>Product Name</th>
                                            <th class="text-end">Delivered Qty</th>
                                            <th class="text-end">Unit Rate (৳)</th>
                                            <th class="text-end pe-3">Total Amount (৳)</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($collection->items as $index => $item)
                                        <tr>
                                            <td class="ps-3 text-center text-muted">{{ $index + 1 }}</td>
                                            <td>
                                                <div class="fw-bold text-dark">{{ $item->product_name }}</div>
                                                @if($item->category)
                                                    <small class="text-muted">{{ $item->category }}</small>
                                                @endif
                                            </td>
                                            <td class="text-end fw-bold text-dark">
                                                {{ number_format($item->delivered_quantity, 1) }} {{ $item->unit }}
                                            </td>
                                            <td class="text-end">
                                                ৳{{ number_format($item->unit_rate, 2) }}
                                            </td>
                                            <td class="text-end pe-3 fw-bold text-primary">
                                                ৳{{ number_format($item->total_amount, 2) }}
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                    <tfoot class="table-light font-size-14">
                                        <tr>
                                            <td colspan="2" class="text-end fw-bold text-dark ps-3">সর্বমোট (Grand Total):</td>
                                            <td class="text-end fw-bold text-dark">{{ number_format($collection->total_distributed_qty, 1) }}</td>
                                            <td></td>
                                            <td class="text-end pe-3 fw-bold text-primary font-size-16">৳{{ number_format($collection->total_amount, 2) }}</td>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>

                            <!-- Additional Audit Info -->
                            <div class="p-3 bg-light rounded-3 font-size-12 text-muted">
                                <div>ভেরিফিকেশন পদ্ধতি: <strong class="text-dark">{{ ucfirst(str_replace('_', ' ', $collection->verification_method)) }}</strong></div>
                                <div>পণ্য গ্রহণকারী: <strong class="text-dark">{{ $collection->collected_by }}</strong> ({{ $collection->collector_phone ?? '' }})</div>
                                <div>বিতরণকারী এজেন্ট ইউজার: <strong class="text-dark">{{ optional($collection->deliverer)->name ?? 'Agent' }}</strong></div>
                                @if($collection->remarks)
                                    <div class="mt-1">মন্তব্য: {{ $collection->remarks }}</div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
</div>
@endsection
