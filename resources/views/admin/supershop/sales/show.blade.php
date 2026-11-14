@extends('layouts.backend.app')

@section('content')
<div class="content-wrapper">
    <div class="container-full">
        <!-- Content Header -->
        <div class="content-header">
            <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
                <div>
                    <h3 class="page-title text-dark fw-bold mb-1">
                        <i class="ti-receipt text-primary me-2"></i>ইনভয়েস বিবরণী – {{ $sale->invoice_no }}
                    </h3>
                    <p class="text-muted mb-0">POS বিক্রয় চালান, আইটেম তালিকা, পেমেন্ট ট্র্যাকিং ও কাস্টমার রিটার্ন/রিফান্ড</p>
                </div>
                <div class="d-flex gap-2">
                    <button type="button" class="btn btn-sm btn-outline-danger fw-bold" data-bs-toggle="modal" data-bs-target="#returnModal">
                        <i class="ti-back-left me-1"></i> রিটার্ন / রিফান্ড প্রসেস
                    </button>
                    <a href="{{ route('admin.supershop.pos.receipt', $sale->id) }}" target="_blank" class="btn btn-sm btn-outline-secondary">
                        <i class="ti-printer me-1"></i> থার্মাল রিসিপ্ট প্রিন্ট
                    </a>
                    <a href="{{ route('admin.supershop.sales') }}" class="btn btn-sm btn-primary">
                        <i class="ti-arrow-left me-1"></i> সকল ইনভয়েস
                    </a>
                </div>
            </div>
        </div>

        <!-- Main Content -->
        <section class="content">
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="fa fa-check-circle me-1"></i> {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            <div class="row">
                <!-- Left 8 cols: Invoice Items -->
                <div class="col-lg-8 col-12">
                    <div class="box shadow-sm mb-4">
                        <div class="box-header with-border">
                            <h4 class="box-title fw-bold text-dark mb-0">ক্রয়কৃত আইটেম তালিকা (Items Purchased)</h4>
                        </div>
                        <div class="box-body p-0">
                            <div class="table-responsive">
                                <table class="table table-hover mb-0 align-middle">
                                    <thead class="table-light">
                                        <tr>
                                            <th>পণ্য (Product)</th>
                                            <th class="text-center">পরিমাণ</th>
                                            <th class="text-end">একক মূল্য</th>
                                            <th class="text-end">মোট মূল্য</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($sale->items as $item)
                                            <tr>
                                                <td>
                                                    <strong class="text-dark">{{ $item->product_name }}</strong>
                                                    <div class="small text-muted">SKU: {{ $item->sku ?: ($item->product ? $item->product->sku : 'N/A') }}</div>
                                                </td>
                                                <td class="text-center fw-bold">{{ $item->quantity }}</td>
                                                <td class="text-end text-muted">৳ {{ number_format($item->unit_price, 2) }}</td>
                                                <td class="text-end fw-bold text-success">৳ {{ number_format($item->line_total, 2) }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    <!-- Returns History if any -->
                    @if($sale->returns && $sale->returns->count() > 0)
                        <div class="box shadow-sm">
                            <div class="box-header with-border bg-danger-light">
                                <h4 class="box-title fw-bold text-danger mb-0"><i class="ti-back-left me-1"></i> রিটার্ন ও রিফান্ড হিস্ট্রি</h4>
                            </div>
                            <div class="box-body p-0">
                                <table class="table table-sm mb-0">
                                    <thead class="table-light">
                                        <tr>
                                            <th>রিটার্ন #</th>
                                            <th>ফেরতের কারণ</th>
                                            <th class="text-end">রিফান্ড টাকা</th>
                                            <th>পদ্ধতি</th>
                                            <th class="text-center">তারিখ</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($sale->returns as $ret)
                                            <tr>
                                                <td class="fw-bold text-danger">{{ $ret->return_no }}</td>
                                                <td>{{ $ret->reason }}</td>
                                                <td class="text-end fw-bold text-danger">৳ {{ number_format($ret->total_refund_amount, 2) }}</td>
                                                <td><span class="badge bg-light text-dark">{{ strtoupper($ret->refund_method) }}</span></td>
                                                <td class="text-center small text-muted">{{ \Carbon\Carbon::parse($ret->created_at)->format('d M, h:i A') }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    @endif
                </div>

                <!-- Right 4 cols: Invoice Summary & Metadata -->
                <div class="col-lg-4 col-12">
                    <div class="box shadow-sm mb-3">
                        <div class="box-header with-border">
                            <h4 class="box-title fw-bold text-dark mb-0">হিসাব বিবরণী (Summary)</h4>
                        </div>
                        <div class="box-body">
                            <div class="d-flex justify-content-between mb-2">
                                <span class="text-muted">সাবটোটাল:</span>
                                <strong class="text-dark">৳ {{ number_format($sale->subtotal, 2) }}</strong>
                            </div>
                            @if($sale->discount_amount > 0)
                                <div class="d-flex justify-content-between mb-2">
                                    <span class="text-muted">ডিসকাউন্ট:</span>
                                    <strong class="text-danger">- ৳ {{ number_format($sale->discount_amount, 2) }}</strong>
                                </div>
                            @endif
                            @if($sale->tax_amount > 0)
                                <div class="d-flex justify-content-between mb-2">
                                    <span class="text-muted">ভ্যাট / ট্যাক্স:</span>
                                    <strong class="text-dark">+ ৳ {{ number_format($sale->tax_amount, 2) }}</strong>
                                </div>
                            @endif
                            <hr>
                            <div class="d-flex justify-content-between mb-2 fs-5">
                                <span class="fw-bold text-dark">সর্বমোট বিল:</span>
                                <span class="fw-bold text-success">৳ {{ number_format($sale->grand_total, 2) }}</span>
                            </div>
                            <div class="d-flex justify-content-between mb-2">
                                <span class="text-muted">পরিশোধিত টাকা:</span>
                                <strong>৳ {{ number_format($sale->paid_amount, 2) }}</strong>
                            </div>
                            @if($sale->change_amount > 0)
                                <div class="d-flex justify-content-between mb-2">
                                    <span class="text-muted">ফেরত প্রদান:</span>
                                    <strong class="text-danger">৳ {{ number_format($sale->change_amount, 2) }}</strong>
                                </div>
                            @endif
                            <div class="d-flex justify-content-between mt-2 pt-2 border-top">
                                <span class="text-muted">পেমেন্ট মেথড:</span>
                                <span class="badge bg-info-light text-info text-uppercase">{{ $sale->payment_method }}</span>
                            </div>
                        </div>
                    </div>

                    <div class="box shadow-sm">
                        <div class="box-header with-border">
                            <h4 class="box-title fw-bold text-dark mb-0">শাখা ও অপারেটর তথ্য</h4>
                        </div>
                        <div class="box-body small">
                            <div class="mb-2">
                                <span class="text-muted d-block">আউটলেট / ব্রাঞ্চ:</span>
                                <strong class="text-dark">{{ $sale->branch ? $sale->branch->name : 'N/A' }} ({{ $sale->branch ? $sale->branch->code : '' }})</strong>
                            </div>
                            <div class="mb-2">
                                <span class="text-muted d-block">ক্যাশিয়ার:</span>
                                <strong class="text-dark">{{ $sale->cashier ? $sale->cashier->name : 'POS Operator' }}</strong>
                            </div>
                            <div class="mb-2">
                                <span class="text-muted d-block">কাউন্টার:</span>
                                <strong class="text-dark">{{ $sale->counter ? $sale->counter->counter_name : 'Counter 01' }}</strong>
                            </div>
                            <div class="mb-2">
                                <span class="text-muted d-block">কাস্টমার নাম ও মোবাইল:</span>
                                <strong class="text-dark">{{ $sale->customer_name ?: 'Walk-in Guest' }}</strong>
                                @if($sale->customer_phone)
                                    <div>{{ $sale->customer_phone }}</div>
                                @endif
                            </div>
                            <div>
                                <span class="text-muted d-block">তারিখ ও সময়:</span>
                                <div>{{ \Carbon\Carbon::parse($sale->sale_time)->format('d F Y, h:i:s A') }}</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Return & Refund Modal -->
        <div class="modal fade" id="returnModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <form method="POST" action="{{ route('admin.supershop.sales.return') }}">
                    @csrf
                    <input type="hidden" name="pos_sale_id" value="{{ $sale->id }}">
                    <div class="modal-content">
                        <div class="modal-header bg-danger text-white">
                            <h5 class="modal-title fw-bold text-white"><i class="ti-back-left me-1"></i> কাস্টমার রিটার্ন ও রিফান্ড প্রসেস</h5>
                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body">
                            <div class="table-responsive mb-3">
                                <table class="table table-bordered table-sm align-middle">
                                    <thead class="table-light">
                                        <tr>
                                            <th>পণ্য</th>
                                            <th class="text-center">ক্রয়কৃত</th>
                                            <th class="text-center" style="width: 120px;">রিটার্ন পরিমাণ</th>
                                            <th class="text-center" style="width: 160px;">পণ্যের অবস্থা</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($sale->items as $item)
                                            <tr>
                                                <td>
                                                    <strong>{{ $item->product_name }}</strong>
                                                    <div class="smaller text-muted">দর: ৳{{ $item->unit_price }}</div>
                                                    <input type="hidden" name="item_id[]" value="{{ $item->id }}">
                                                </td>
                                                <td class="text-center">{{ $item->quantity }}</td>
                                                <td>
                                                    <input type="number" name="return_qty[]" class="form-control form-control-sm text-center" value="0" min="0" max="{{ $item->quantity }}">
                                                </td>
                                                <td>
                                                    <select name="condition[]" class="form-select form-select-sm">
                                                        <option value="sellable">বিক্রয়যোগ্য (স্টকে যোগ হবে)</option>
                                                        <option value="damaged">ড্যামেজ/নষ্ট (স্টকে যোগ হবে না)</option>
                                                    </select>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>

                            <div class="row g-2">
                                <div class="col-md-6">
                                    <label class="form-label fw-bold">রিফান্ড প্রদানের মাধ্যম <span class="text-danger">*</span></label>
                                    <select name="refund_method" class="form-select" required>
                                        <option value="cash">💵 ক্যাশ ফেরত (Cash Refund)</option>
                                        <option value="bkash">📱 বিকাশ (bKash)</option>
                                        <option value="store_credit">💳 স্টোর ক্রেডিট / পয়েন্ট</option>
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-bold">রিটার্ন নেওয়ার কারণ <span class="text-danger">*</span></label>
                                    <input type="text" name="reason" class="form-control" placeholder="যেমন: কাস্টমার সাইজ পরিবর্তন করতে চায়" required>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">বাতিল</button>
                            <button type="submit" class="btn btn-danger fw-bold">রিটার্ন ও রিফান্ড অনুমোদন করুন</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

    </div>
</div>
@endsection

