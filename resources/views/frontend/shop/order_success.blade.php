@extends('layouts.frontend.shop')

@section('title', 'অর্ডার সফল হয়েছে | Order Receipt')

@section('shop_content')

<div class="row justify-content-center my-4">
    <div class="col-lg-9">
        <div class="card shadow-sm border-0 rounded-4 p-4 p-md-5 bg-white">
            <!-- Header Checkmark -->
            <div class="text-center mb-4">
                <div class="d-inline-flex align-items-center justify-content-center bg-success text-white rounded-circle mb-3" style="width: 75px; height: 75px; font-size: 36px; box-shadow: 0 10px 25px rgba(22, 163, 74, 0.3);">
                    <i class="fa fa-check"></i>
                </div>
                <h2 class="fw-bold text-dark">ধন্যবাদ! আপনার অর্ডারটি সফলভাবে গ্রহণ করা হয়েছে।</h2>
                <p class="text-muted">আপনার অর্ডার নম্বর: <strong class="text-teal fs-5">{{ $order->order_number }}</strong></p>
                <div class="badge bg-success bg-opacity-10 text-success border border-success border-opacity-25 px-3 py-2 rounded-pill font-size-13">
                    <i class="fa fa-truck-fast me-1"></i> ডেলিভারি প্রস্তুতি চলছে (১-২ ঘণ্টার মধ্যে সরবরাহ করা হবে)
                </div>
            </div>

            <!-- Receipt Grid -->
            <div class="row g-3 p-3 bg-light rounded-3 mb-4 text-dark font-size-14">
                <div class="col-sm-6 col-md-3">
                    <span class="text-muted small d-block">গ্রাহকের নাম:</span>
                    <strong>{{ $order->first_name }} {{ $order->last_name }}</strong>
                </div>
                <div class="col-sm-6 col-md-3">
                    <span class="text-muted small d-block">ফোন নম্বর:</span>
                    <strong>{{ $order->phone }}</strong>
                </div>
                <div class="col-sm-6 col-md-3">
                    <span class="text-muted small d-block">শিপিং পদ্ধতি:</span>
                    <strong>
                        @if($order->shipping_method === 'inside_dhaka')
                            Inside Dhaka (৳60)
                        @elseif($order->shipping_method === 'outside_dhaka')
                            Outside Dhaka (৳120)
                        @else
                            Free Shipping (Dhaka City)
                        @endif
                    </strong>
                </div>
                <div class="col-sm-6 col-md-3">
                    <span class="text-muted small d-block">পেমেন্ট পদ্ধতি:</span>
                    <strong class="text-uppercase">{{ $order->payment_method }}</strong>
                </div>
                <div class="col-12 pt-2 border-top">
                    <span class="text-muted small d-block">ডেলিভারি ঠিকানা:</span>
                    <span>{{ $order->address }}, {{ $order->city }}</span>
                </div>
            </div>

            <!-- Ordered Items Table -->
            <h5 class="fw-bold mb-3 text-dark"><i class="fa fa-box-open text-teal me-2"></i>অর্ডারকৃত পণ্যের বিবরণ</h5>
            <div class="table-responsive mb-4">
                <table class="table table-bordered align-middle">
                    <thead class="table-light">
                        <tr>
                            <th style="width: 50px;">#</th>
                            <th>পণ্যের নাম</th>
                            <th class="text-center" style="width: 110px;">একক মূল্য</th>
                            <th class="text-center" style="width: 90px;">পরিমাণ</th>
                            <th class="text-end" style="width: 120px;">মোট মূল্য</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($order->items as $index => $item)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>
                                    <div class="fw-bold text-dark">{{ $item->product_name }}</div>
                                    @if($item->product && $item->product->sku)
                                        <span class="text-muted small font-monospace">SKU: {{ $item->product->sku }}</span>
                                    @endif
                                </td>
                                <td class="text-center">৳ {{ number_format($item->price, 0) }}</td>
                                <td class="text-center"><span class="badge bg-secondary">{{ $item->quantity }}</span></td>
                                <td class="text-end fw-bold">৳ {{ number_format($item->total, 0) }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                    <tfoot class="table-light">
                        <tr>
                            <td colspan="4" class="text-end text-muted">পণ্যের মূল্য (Subtotal):</td>
                            <td class="text-end fw-bold">৳ {{ number_format($order->subtotal, 0) }}</td>
                        </tr>
                        <tr>
                            <td colspan="4" class="text-end text-muted">ডেলিভারি চার্জ (Shipping Fee):</td>
                            <td class="text-end text-success fw-bold">
                                {{ $order->shipping_charge > 0 ? '৳ ' . number_format($order->shipping_charge, 0) : 'ফ্রি (৳ 0)' }}
                            </td>
                        </tr>
                        <tr class="fs-6">
                            <td colspan="4" class="text-end fw-bold text-dark">সর্বমোট প্রদেয় (Grand Total):</td>
                            <td class="text-end fw-bold text-teal">৳ {{ number_format($order->total_amount, 0) }}</td>
                        </tr>
                    </tfoot>
                </table>
            </div>

            <!-- Action Buttons -->
            <div class="d-flex justify-content-center gap-3 flex-wrap">
                <a href="{{ route('products.index') }}" class="btn btn-teal text-white px-4 py-2" style="background: var(--shop-teal);">
                    <i class="fa fa-shopping-bag me-2"></i>কেনাকাটা চালিয়ে যান
                </a>
                <button type="button" class="btn btn-outline-secondary px-4 py-2" onclick="window.print()">
                    <i class="fa fa-print me-2"></i>রশিদ প্রিন্ট করুন
                </button>
            </div>
        </div>
    </div>
</div>

@endsection

