@extends('layouts.backend.app')

@section('content')
<div class="content-wrapper">
    <div class="container-full">
        <div class="content-header">
            <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
                <div>
                    <h3 class="page-title text-dark fw-bold mb-1">
                        <i class="ti-file text-primary me-2"></i>অর্ডার বিবরণী #{{ $order->order_number }}
                    </h3>
                    <p class="text-muted mb-0">অর্ডার তারিখ: {{ $order->created_at->format('d M Y, h:i A') }}</p>
                </div>
                <div class="d-flex gap-2">
                    <a href="{{ route('admin.ecommerce.orders.invoice', $order->id) }}" target="_blank" class="btn btn-outline-info">
                        <i class="ti-printer me-1"></i> ইনভয়েস প্রিন্ট
                    </a>
                    <a href="{{ route('admin.ecommerce.orders') }}" class="btn btn-outline-secondary">
                        <i class="ti-arrow-left me-1"></i> সকল অর্ডারে ফিরুন
                    </a>
                </div>
            </div>
        </div>

        <section class="content">
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="fa fa-check-circle me-1"></i> {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <div class="row">
                <!-- Left: Order Items & Financials -->
                <div class="col-lg-8 col-12">
                    <!-- Order Items Card -->
                    <div class="box shadow-sm mb-4">
                        <div class="box-header with-border">
                            <h4 class="box-title"><i class="ti-package me-1 text-primary"></i>অর্ডারের পণ্যসমূহ ({{ $order->items->count() }} টি)</h4>
                        </div>
                        <div class="box-body p-0">
                            <div class="table-responsive">
                                <table class="table table-hover align-middle mb-0">
                                    <thead class="table-light">
                                        <tr>
                                            <th style="width: 60px;">ছবি</th>
                                            <th>পণ্যের নাম ও বিবরণ</th>
                                            <th class="text-center">একক মূল্য</th>
                                            <th class="text-center">পরিমাণ</th>
                                            <th class="text-end">মোট মূল্য</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($order->items as $item)
                                            <tr>
                                                <td>
                                                    @if($item->product && $item->product->image)
                                                        <img src="{{ asset($item->product->image) }}" class="rounded border" style="width: 50px; height: 50px; object-fit: contain;">
                                                    @else
                                                        <div class="rounded bg-light border d-flex align-items-center justify-content-center" style="width: 50px; height: 50px;">
                                                            <i class="ti-image text-muted"></i>
                                                        </div>
                                                    @endif
                                                </td>
                                                <td>
                                                    <div class="fw-bold text-dark">{{ $item->product_name }}</div>
                                                    @if($item->sku)
                                                        <div class="text-muted small">SKU: <code>{{ $item->sku }}</code></div>
                                                    @endif
                                                    @if(is_array($item->variant_details) && count($item->variant_details) > 0)
                                                        <div class="mt-1">
                                                            @foreach($item->variant_details as $vk => $vv)
                                                                <span class="badge bg-light text-dark border small">{{ $vk }}: {{ $vv }}</span>
                                                            @endforeach
                                                        </div>
                                                    @endif
                                                </td>
                                                <td class="text-center fw-bold">৳ {{ number_format($item->unit_price, 2) }}</td>
                                                <td class="text-center"><span class="badge bg-light text-dark border fs-6">{{ $item->quantity }}</span></td>
                                                <td class="text-end fw-bold text-primary">৳ {{ number_format($item->subtotal, 2) }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="box-footer">
                            <div class="row justify-content-end">
                                <div class="col-md-6 col-12">
                                    <ul class="list-group list-group-flush">
                                        <li class="list-group-item d-flex justify-content-between">
                                            <span>সাব-টোটাল (Subtotal):</span>
                                            <span class="fw-bold">৳ {{ number_format($order->subtotal, 2) }}</span>
                                        </li>
                                        <li class="list-group-item d-flex justify-content-between">
                                            <span>ডেলিভারি চার্জ (Shipping):</span>
                                            <span class="fw-bold">৳ {{ number_format($order->shipping_charge, 2) }}</span>
                                        </li>
                                        @if($order->discount_amount > 0)
                                            <li class="list-group-item d-flex justify-content-between text-success">
                                                <span>ডিসকাউন্ট / কুপন ({{ $order->coupon_code ?: 'Promo' }}):</span>
                                                <span class="fw-bold">-৳ {{ number_format($order->discount_amount, 2) }}</span>
                                            </li>
                                        @endif
                                        <li class="list-group-item d-flex justify-content-between bg-light fw-bold fs-5">
                                            <span>সর্বমোট প্রদেয় বিল:</span>
                                            <span class="text-primary">৳ {{ number_format($order->total_amount, 2) }}</span>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Status History Timeline -->
                    <div class="box shadow-sm mb-4">
                        <div class="box-header with-border">
                            <h4 class="box-title"><i class="ti-time me-1 text-info"></i>অর্ডার স্ট্যাটাস হিস্ট্রি (Status Timeline)</h4>
                        </div>
                        <div class="box-body">
                            @if(is_array($order->status_history) && count($order->status_history) > 0)
                                <ul class="timeline list-unstyled ps-3 border-start">
                                    @foreach($order->status_history as $hist)
                                        <li class="mb-3 position-relative ps-3">
                                            <i class="ti-control-record text-primary position-absolute" style="left: -20px; top: 2px;"></i>
                                            <div class="fw-bold text-capitalize text-dark">{{ $hist['status'] ?? 'Updated' }}</div>
                                            <div class="text-muted small">{{ $hist['comment'] ?? '' }}</div>
                                            <div class="text-muted small">{{ !empty($hist['time']) ? date('d M Y, h:i A', strtotime($hist['time'])) : '' }}</div>
                                        </li>
                                    @endforeach
                                </ul>
                            @else
                                <div class="text-muted small">হিস্ট্রি রেকর্ড পাওয়া যায়নি।</div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Right: Customer, Delivery & Status Actions -->
                <div class="col-lg-4 col-12">
                    <!-- Status Update Card -->
                    <div class="box shadow-sm mb-4 border-top border-4 border-primary">
                        <div class="box-header with-border">
                            <h4 class="box-title"><i class="ti-exchange-vertical me-1 text-primary"></i>অর্ডার স্ট্যাটাস পরিবর্তন</h4>
                        </div>
                        <div class="box-body">
                            <form action="{{ route('admin.ecommerce.orders.status', $order->id) }}" method="POST">
                                @csrf
                                @method('PUT')
                                <div class="form-group mb-3">
                                    <label class="form-label fw-bold">অর্ডার অবস্থা (Status)</label>
                                    <select name="order_status" class="form-select">
                                        <option value="pending" {{ $order->order_status === 'pending' ? 'selected' : '' }}>Pending (পেন্ডিং)</option>
                                        <option value="processing" {{ $order->order_status === 'processing' ? 'selected' : '' }}>Processing (প্রসেসিং)</option>
                                        <option value="shipping" {{ $order->order_status === 'shipping' ? 'selected' : '' }}>Shipping / On Delivery (ডেলিভারিতে আছে)</option>
                                        <option value="delivered" {{ $order->order_status === 'delivered' ? 'selected' : '' }}>Delivered (ডেলিভারি সম্পন্ন)</option>
                                        <option value="completed" {{ $order->order_status === 'completed' ? 'selected' : '' }}>Completed (সম্পন্ন)</option>
                                        <option value="cancelled" {{ $order->order_status === 'cancelled' ? 'selected' : '' }}>Cancelled (বাতিল)</option>
                                    </select>
                                </div>

                                <div class="form-group mb-3">
                                    <label class="form-label fw-bold">কুরিয়ার সার্ভিস নাম</label>
                                    <input type="text" name="courier_name" class="form-control" placeholder="যেমন: Steadfast, Pathao, RedX" value="{{ $order->courier_name }}">
                                </div>

                                <div class="form-group mb-3">
                                    <label class="form-label fw-bold">কুরিয়ার ট্র্যাকিং কোড</label>
                                    <input type="text" name="tracking_code" class="form-control" placeholder="Consignment / Tracking ID" value="{{ $order->tracking_code }}">
                                </div>

                                <div class="form-group mb-3">
                                    <label class="form-label fw-bold">মন্তব্য / নোট</label>
                                    <textarea name="comment" rows="2" class="form-control" placeholder="গ্রাহক বা ইন্টারনাল নোট..."></textarea>
                                </div>

                                <button type="submit" class="btn btn-primary w-100">
                                    <i class="ti-check me-1"></i> স্ট্যাটাস আপডেট করুন
                                </button>
                            </form>
                        </div>
                    </div>

                    <!-- Payment Status Card -->
                    <div class="box shadow-sm mb-4">
                        <div class="box-header with-border">
                            <h4 class="box-title"><i class="ti-credit-card me-1 text-success"></i>পেমেন্ট বিবরণ ও আপডেট</h4>
                        </div>
                        <div class="box-body">
                            <div class="mb-3">
                                <div class="text-muted small">পেমেন্ট মেথড:</div>
                                <div class="fw-bold text-uppercase fs-6">{{ $order->payment_method ?: 'Cash On Delivery' }}</div>
                            </div>
                            <div class="mb-3">
                                <div class="text-muted small">বর্তমান পেমেন্ট অবস্থা:</div>
                                @if($order->payment_status === 'paid')
                                    <span class="badge bg-success fs-6">পরিশোধিত (Paid)</span>
                                @else
                                    <span class="badge bg-warning text-dark fs-6">বকেয়া (Unpaid / COD)</span>
                                @endif
                            </div>

                            <form action="{{ route('admin.ecommerce.orders.payment_status', $order->id) }}" method="POST">
                                @csrf
                                @method('PUT')
                                <div class="form-group mb-2">
                                    <label class="form-label fw-bold small">পেমেন্ট স্ট্যাটাস পরিবর্তন</label>
                                    <select name="payment_status" class="form-select form-select-sm">
                                        <option value="pending" {{ $order->payment_status === 'pending' ? 'selected' : '' }}>Pending</option>
                                        <option value="paid" {{ $order->payment_status === 'paid' ? 'selected' : '' }}>Paid (পরিশোধিত)</option>
                                        <option value="failed" {{ $order->payment_status === 'failed' ? 'selected' : '' }}>Failed</option>
                                    </select>
                                </div>
                                <div class="form-group mb-2">
                                    <label class="form-label fw-bold small">ট্রানজ্যাকশন আইডি (TrxID)</label>
                                    <input type="text" name="payment_transaction_id" class="form-control form-control-sm" value="{{ $order->payment_transaction_id }}" placeholder="e.g. 9J283DA9X">
                                </div>
                                <button type="submit" class="btn btn-sm btn-success w-100">পেমেন্ট আপডেট</button>
                            </form>
                        </div>
                    </div>

                    <!-- Customer Information Card -->
                    <div class="box shadow-sm mb-4">
                        <div class="box-header with-border">
                            <h4 class="box-title"><i class="ti-user me-1 text-info"></i>গ্রাহক তথ্য (Customer)</h4>
                        </div>
                        <div class="box-body">
                            <div class="mb-2">
                                <strong class="text-dark">{{ $order->first_name }} {{ $order->last_name }}</strong>
                            </div>
                            <div class="mb-2">
                                <i class="ti-mobile text-muted me-1"></i> <a href="tel:{{ $order->phone }}" class="text-decoration-none fw-bold">{{ $order->phone }}</a>
                            </div>
                            @if($order->email)
                                <div class="mb-2">
                                    <i class="ti-email text-muted me-1"></i> {{ $order->email }}
                                </div>
                            @endif
                            <hr>
                            <div class="fw-bold mb-1"><i class="ti-location-pin text-danger me-1"></i>ডেলিভারি ঠিকানা:</div>
                            <p class="text-muted small mb-0">{{ $order->address }}, {{ $order->city }}</p>
                            @if($order->notes)
                                <div class="mt-2 p-2 bg-light rounded small">
                                    <strong>গ্রাহকের বিশেষ নির্দেশনা:</strong> {{ $order->notes }}
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
</div>
@endsection

