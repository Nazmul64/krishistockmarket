@extends('layouts.backend.app')

@section('content')
<div class="content-wrapper">
    <div class="container-full">
        <!-- Content Header -->
        <div class="content-header d-print-none">
            <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
                <div>
                    <h3 class="page-title fw-bold text-dark mb-1">
                        <i class="fa fa-print text-primary me-2"></i>Customer Market Collection Receipt (বিতরণ রসিদ)
                    </h3>
                    <p class="text-muted mb-0">রসিদ নং: <strong>{{ $collection->collection_no }}</strong> | ট্রানজেকশন আইডি: {{ $collection->transaction_id }}</p>
                </div>
                <div class="d-flex gap-2">
                    <button type="button" class="btn btn-primary btn-sm px-4 fw-semibold shadow-sm" onclick="window.print();">
                        <i class="fa fa-print me-1"></i> রসিদ প্রিন্ট করুন (Print Receipt)
                    </button>
                    <a href="{{ route('agent.distribution.entry') }}" class="btn btn-success btn-sm">
                        <i class="fa fa-plus me-1"></i> নতুন বিতরণ এন্ট্রি
                    </a>
                    <a href="{{ route('agent.distribution.daily_register') }}" class="btn btn-outline-secondary btn-sm">
                        <i class="fa fa-arrow-left me-1"></i> রেজিস্টারে ফিরে যান
                    </a>
                </div>
            </div>
        </div>

        <section class="content">
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show shadow-sm d-print-none" role="alert">
                    <i class="fa fa-check-circle me-2"></i> {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <!-- Printable Receipt Voucher -->
            <div class="row justify-content-center">
                <div class="col-xl-9 col-lg-10">
                    <div class="card border-0 shadow-sm rounded-3 p-4 p-md-5 bg-white print-container" id="printableReceipt">
                        <!-- Receipt Header -->
                        <div class="row align-items-center pb-4 border-bottom mb-4">
                            <div class="col-sm-7">
                                <div class="d-flex align-items-center gap-3">
                                    <div class="rounded-circle bg-primary-light p-2 text-primary d-print-none">
                                        <i class="fa fa-leaf fa-2x"></i>
                                    </div>
                                    <div>
                                        <h3 class="fw-bold text-primary mb-1 text-uppercase" style="letter-spacing: 1px;">iKrishi Poribar</h3>
                                        <div class="text-muted font-size-13 fw-semibold">মাসিক ও সাপ্তাহিক বাজার ডিস্ট্রিবিউশন হাব</div>
                                        <div class="text-muted font-size-11">Customer Market Distribution & Supply Receipt</div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-5 text-sm-end mt-3 mt-sm-0">
                                <span class="badge bg-success-light text-success font-size-13 px-3 py-1 fw-bold rounded-pill mb-2">
                                    <i class="fa fa-check-circle me-1"></i> সফল বিতরণ সম্পন্ন
                                </span>
                                <div class="font-size-14 fw-bold text-dark">রসিদ নং: {{ $collection->collection_no }}</div>
                                <div class="text-muted font-size-12">Txn ID: {{ $collection->transaction_id }}</div>
                            </div>
                        </div>

                        <!-- 2-Column Info Grid: Customer vs Agent Point -->
                        <div class="row g-4 mb-4 pb-3 border-bottom font-size-13">
                            <div class="col-sm-6">
                                <h6 class="text-uppercase text-muted font-size-11 fw-bold mb-2">গ্রাহকের বিবরণ (Customer Information)</h6>
                                <div class="fw-bold font-size-16 text-dark">{{ $collection->customer_name }}</div>
                                <div class="text-primary fw-semibold mt-1">
                                    কার্ড নম্বর: <strong>{{ $collection->card_number }}</strong>
                                </div>
                                <div class="text-muted mt-1"><i class="fa fa-phone me-1"></i> {{ $collection->customer_phone ?? 'N/A' }}</div>
                                <div class="text-muted mt-1"><i class="fa fa-map-marker me-1"></i> {{ optional($collection->user)->present_address ?? optional($collection->user)->area ?? 'General Area' }}</div>
                            </div>
                            <div class="col-sm-6 text-sm-end">
                                <h6 class="text-uppercase text-muted font-size-11 fw-bold mb-2">এজেন্ট পয়েন্ট ও ডেলিভারি বিবরণ</h6>
                                <div class="fw-bold font-size-15 text-dark">{{ $point->name }}</div>
                                <div class="text-muted font-size-12">এজেন্ট কোড: <strong>{{ $point->code }}</strong></div>
                                <div class="text-muted mt-1">
                                    তারিখ ও সময়: <strong>{{ date('d M, Y', strtotime($collection->collection_date)) }}</strong>, {{ $collection->collection_time ? date('h:i A', strtotime($collection->collection_time)) : '' }}
                                </div>
                                <div class="mt-1">
                                    বাজারের ধরন: <span class="badge bg-light text-dark fw-bold font-size-12">{{ ucfirst(str_replace('_', ' ', $collection->market_type)) }}</span>
                                    ({{ $collection->market_month }} {{ $collection->market_year }})
                                </div>
                            </div>
                        </div>

                        <!-- Product List Table -->
                        <div class="table-responsive mb-4">
                            <table class="table table-bordered align-middle font-size-13 mb-0">
                                <thead class="table-light text-uppercase font-size-11">
                                    <tr>
                                        <th class="ps-3" style="width: 50px;">ক্র.নং</th>
                                        <th>পণ্যের বিবরণ (Product Details)</th>
                                        <th class="text-end">বিতরণকৃত পরিমাণ (Qty)</th>
                                        <th class="text-end">একক মূল্য (Rate ৳)</th>
                                        <th class="text-end pe-3">মোট মূল্য (Total ৳)</th>
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
                                        <td colspan="2" class="text-end fw-bold text-dark ps-3">সর্বমোট পণ্য ও মূল্য (Grand Total):</td>
                                        <td class="text-end fw-bold text-dark">{{ number_format($collection->total_distributed_qty, 1) }}</td>
                                        <td></td>
                                        <td class="text-end pe-3 fw-bold text-primary font-size-16">৳{{ number_format($collection->total_amount, 2) }}</td>
                                    </tr>
                                    @if($collection->paid_amount > 0 || $collection->due_amount > 0)
                                    <tr class="font-size-12">
                                        <td colspan="4" class="text-end text-muted">পরিশোধিত অর্থ (Paid Amount):</td>
                                        <td class="text-end pe-3 text-success fw-bold">৳{{ number_format($collection->paid_amount, 2) }}</td>
                                    </tr>
                                    @endif
                                    @if($collection->due_amount > 0)
                                    <tr class="font-size-12">
                                        <td colspan="4" class="text-end text-muted">বকেয়া অর্থ (Due Amount):</td>
                                        <td class="text-end pe-3 text-danger fw-bold">৳{{ number_format($collection->due_amount, 2) }}</td>
                                    </tr>
                                    @endif
                                </tfoot>
                            </table>
                        </div>

                        <!-- Settlement & Remarks -->
                        <div class="row align-items-center mb-5 font-size-12 text-muted">
                            <div class="col-sm-8">
                                <div>বিতরণ স্কিম: <strong class="text-dark">{{ ucfirst($collection->delivery_type ?? 'Subsidized') }}</strong></div>
                                <div>ভেরিফিকেশন মাধ্যম: <strong class="text-dark">{{ ucfirst(str_replace('_', ' ', $collection->verification_method)) }}</strong></div>
                                <div>পণ্য গ্রহণকারী: <strong class="text-dark">{{ $collection->collected_by }}</strong> ({{ $collection->collector_phone ?? '' }})</div>
                                @if($collection->remarks)
                                    <div class="mt-1">মন্তব্য: {{ $collection->remarks }}</div>
                                @endif
                            </div>
                            <div class="col-sm-4 text-sm-end mt-3 mt-sm-0">
                                <div>বিতরণকারী: <strong>{{ optional($collection->deliverer)->name ?? 'Agent Manager' }}</strong></div>
                                <div>স্ট্যাটাস: <strong class="text-success">{{ strtoupper($collection->collection_status) }}</strong></div>
                            </div>
                        </div>

                        <!-- Signature Blocks -->
                        <div class="row pt-5 mt-4 text-center font-size-12 text-dark">
                            <div class="col-4">
                                <div class="border-top border-dark pt-1 mx-3">গ্রাহক / গ্রহণকারীর স্বাক্ষর</div>
                            </div>
                            <div class="col-4">
                                <div class="border-top border-dark pt-1 mx-3">যাচাইকারী কর্মকর্তার স্বাক্ষর</div>
                            </div>
                            <div class="col-4">
                                <div class="border-top border-dark pt-1 mx-3">অনুমোদিত এজেন্ট সিল ও স্বাক্ষর</div>
                            </div>
                        </div>

                        <!-- Footer Notice -->
                        <div class="text-center text-muted font-size-11 pt-4 border-top mt-4">
                            এটি একটি সিস্টেম জেনারেটেড ডিজিটাল বাজার বিতরণ রসিদ। কোনো প্রশ্ন থাকলে হেল্পলাইনে যোগাযোগ করুন।
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
</div>

<style>
@media print {
    body * {
        visibility: hidden;
    }
    #printableReceipt, #printableReceipt * {
        visibility: visible;
    }
    #printableReceipt {
        position: absolute;
        left: 0;
        top: 0;
        width: 100%;
        margin: 0;
        padding: 20px !important;
        box-shadow: none !important;
        border: none !important;
    }
    .d-print-none {
        display: none !important;
    }
}
</style>
@endsection
