<!DOCTYPE html>
<html lang="bn">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ইনভয়েস #{{ $order->order_number }} - e-Krishi Sheba Shop</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body {
            background-color: #f8fafc;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            color: #334155;
        }
        .invoice-card {
            background: #ffffff;
            border-radius: 8px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.06);
            max-width: 850px;
            margin: 30px auto;
            padding: 40px;
        }
        .invoice-title {
            color: #0f766e;
            font-weight: 800;
        }
        .table thead th {
            background-color: #f1f5f9;
            color: #1e293b;
            font-size: 13px;
            text-transform: uppercase;
        }
        @media print {
            body {
                background: #ffffff;
                margin: 0;
                padding: 0;
            }
            .no-print {
                display: none !important;
            }
            .invoice-card {
                box-shadow: none;
                border: none;
                margin: 0;
                max-width: 100%;
                padding: 20px;
            }
        }
    </style>
</head>
<body>

<div class="container">
    <div class="no-print text-center my-4">
        <button onclick="window.print()" class="btn btn-primary btn-lg px-4 me-2">
            <i class="fa fa-print me-2"></i> ইনভয়েস প্রিন্ট করুন
        </button>
        <button onclick="window.close()" class="btn btn-outline-secondary btn-lg px-4">
            বন্ধ করুন
        </button>
    </div>

    <div class="invoice-card">
        <!-- Invoice Header -->
        <div class="row align-items-center border-bottom pb-4 mb-4">
            <div class="col-sm-7">
                <h2 class="invoice-title mb-1">ই-কৃষি সেবা সুপারশপ</h2>
                <div class="text-muted small">অনলাইন গ্রোসারি ও দৈনন্দিন নিত্যপ্রয়োজনীয় সুপারশপ</div>
                <div class="small text-muted mt-2">
                    <i class="fa fa-map-marker-alt text-danger me-1"></i> প্লট #৪৫, রিং রোড, মোহাম্মদপুর, ঢাকা-১২০৭<br>
                    <i class="fa fa-phone text-success me-1"></i> হটলাইন: 09617900200 | WhatsApp: 01805020340<br>
                    <i class="fa fa-envelope text-primary me-1"></i> support@ekrishisheba.com
                </div>
            </div>
            <div class="col-sm-5 text-sm-end mt-3 mt-sm-0">
                <span class="badge bg-success-subtle text-success fs-6 border border-success px-3 py-2">ক্যাশ মেমো / ইনভয়েস</span>
                <div class="mt-3">
                    <div class="fw-bold fs-5 text-dark">#{{ $order->order_number }}</div>
                    <div class="text-muted small">তারিখ: {{ $order->created_at->format('d M Y, h:i A') }}</div>
                    <div class="text-muted small">পেমেন্ট মেথড: <strong class="text-uppercase">{{ $order->payment_method ?: 'Cash On Delivery' }}</strong></div>
                    <div class="text-muted small">পেমেন্ট অবস্থা: 
                        <strong class="{{ $order->payment_status === 'paid' ? 'text-success' : 'text-danger' }}">
                            {{ $order->payment_status === 'paid' ? 'পরিশোধিত' : 'বকেয়া (COD)' }}
                        </strong>
                    </div>
                </div>
            </div>
        </div>

        <!-- Bill To Section -->
        <div class="row mb-4">
            <div class="col-sm-6">
                <h6 class="fw-bold text-muted text-uppercase small mb-2">গ্রাহকের বিবরণ (Bill To):</h6>
                <div class="fw-bold fs-6 text-dark">{{ $order->first_name }} {{ $order->last_name }}</div>
                <div><i class="fa fa-phone text-muted me-1"></i> {{ $order->phone }}</div>
                @if($order->email)
                    <div><i class="fa fa-envelope text-muted me-1"></i> {{ $order->email }}</div>
                @endif
            </div>
            <div class="col-sm-6 text-sm-end mt-3 mt-sm-0">
                <h6 class="fw-bold text-muted text-uppercase small mb-2">ডেলিভারি ঠিকানা:</h6>
                <div class="text-dark">{{ $order->address }}</div>
                <div class="text-dark">{{ $order->city }}</div>
                @if($order->courier_name)
                    <div class="small text-muted mt-1">কুরিয়ার: <strong>{{ $order->courier_name }}</strong> ({{ $order->tracking_code }})</div>
                @endif
            </div>
        </div>

        <!-- Order Items Table -->
        <div class="table-responsive mb-4">
            <table class="table table-bordered align-middle mb-0">
                <thead>
                    <tr>
                        <th class="text-center" style="width: 50px;">#</th>
                        <th>পণ্যের বিবরণ</th>
                        <th class="text-center" style="width: 120px;">একক মূল্য</th>
                        <th class="text-center" style="width: 80px;">পরিমাণ</th>
                        <th class="text-end" style="width: 140px;">মোট মূল্য (৳)</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($order->items as $idx => $item)
                        <tr>
                            <td class="text-center">{{ $idx + 1 }}</td>
                            <td>
                                <div class="fw-bold text-dark">{{ $item->product_name }}</div>
                                @if($item->sku)
                                    <div class="text-muted small">SKU: {{ $item->sku }}</div>
                                @endif
                                @if(is_array($item->variant_details) && count($item->variant_details) > 0)
                                    <div class="small text-secondary">
                                        @foreach($item->variant_details as $k => $v)
                                            <span class="me-2">{{ $k }}: {{ $v }}</span>
                                        @endforeach
                                    </div>
                                @endif
                            </td>
                            <td class="text-center">৳ {{ number_format($item->unit_price, 2) }}</td>
                            <td class="text-center">{{ $item->quantity }}</td>
                            <td class="text-end fw-bold">৳ {{ number_format($item->subtotal, 2) }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Calculation Totals -->
        <div class="row justify-content-end mb-4">
            <div class="col-sm-6">
                <table class="table table-sm table-borderless">
                    <tr>
                        <td class="text-muted">পণ্য মূল্য (Subtotal):</td>
                        <td class="text-end fw-bold">৳ {{ number_format($order->subtotal, 2) }}</td>
                    </tr>
                    <tr>
                        <td class="text-muted">হোম ডেলিভারি চার্জ:</td>
                        <td class="text-end fw-bold">৳ {{ number_format($order->shipping_charge, 2) }}</td>
                    </tr>
                    @if($order->discount_amount > 0)
                        <tr class="text-success">
                            <td>ডিসকাউন্ট ({{ $order->coupon_code ?: 'কুপন' }}):</td>
                            <td class="text-end fw-bold">-৳ {{ number_format($order->discount_amount, 2) }}</td>
                        </tr>
                    @endif
                    <tr class="border-top border-dark fs-5">
                        <td class="fw-bold text-dark pt-2">সর্বমোট প্রদেয় বিল:</td>
                        <td class="text-end fw-bold text-dark pt-2">৳ {{ number_format($order->total_amount, 2) }}</td>
                    </tr>
                </table>
            </div>
        </div>

        <!-- Footer Notes & Signatures -->
        <div class="row pt-4 border-top mt-5 align-items-end">
            <div class="col-7">
                <div class="small text-muted">
                    <strong>শর্তাবলী:</strong><br>
                    ১. পণ্য গ্রহণের সময় ডেলিভারিম্যানের সামনে চেক করে গ্রহণ করুন।<br>
                    ২. কোনো সমস্যা হলে ৭ দিনের মধ্যে পরিবর্তন বা রিফান্ড পলিসি প্রযোজ্য।<br>
                    ৩. আমাদের সাথে থাকার জন্য ধন্যবাদ!
                </div>
            </div>
            <div class="col-5 text-end">
                <div class="border-top d-inline-block pt-1 text-center" style="width: 170px;">
                    <div class="fw-bold small">কর্তৃপক্ষের স্বাক্ষর</div>
                    <div class="text-muted small">e-Krishi Sheba Shop</div>
                </div>
            </div>
        </div>
    </div>
</div>

</body>
</html>

