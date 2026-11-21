<!DOCTYPE html>
<html lang="bn">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Central Stock Dispatch Challan & Gate Pass - {{ $dispatch->dispatch_no }}</title>
    <link rel="stylesheet" href="{{ asset('assets/plugin/vendor_components/bootstrap/dist/css/bootstrap.css') }}">
    <style>
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; font-size: 13px; color: #222; background: #fff; padding: 25px; }
        .challan-card { border: 2px solid #0056b3; border-radius: 12px; padding: 25px; max-width: 850px; margin: 0 auto; }
        .challan-header { border-bottom: 2px solid #0056b3; padding-bottom: 15px; margin-bottom: 20px; }
        .table-challan th, .table-challan td { padding: 9px; border: 1px solid #dee2e6; }
        .badge-challan { font-size: 14px; font-weight: bold; background: #0056b3; color: #fff; padding: 6px 16px; border-radius: 20px; }
        .signature-section { margin-top: 60px; padding-top: 10px; }
        .sig-line { border-top: 1px solid #444; width: 170px; display: inline-block; text-align: center; font-weight: bold; padding-top: 5px; }
        @media print {
            .no-print { display: none !important; }
            body { padding: 0; }
            .challan-card { border: 1px solid #000; }
        }
    </style>
</head>
<body>
    <div class="no-print text-center mb-3">
        <button onclick="window.print()" class="btn btn-primary btn-sm me-2"><i class="fa fa-print"></i> চালান / গেটপাস প্রিন্ট করুন</button>
        <button onclick="window.close()" class="btn btn-secondary btn-sm">বন্ধ করুন</button>
    </div>

    <div class="challan-card">
        <!-- Header -->
        <div class="challan-header d-flex justify-content-between align-items-center">
            <div>
                <h3 class="text-primary fw-bold mb-1">{{ setting('title') ?? 'iKrishiPoribar / ekrishisheba' }}</h3>
                <p class="mb-0 text-muted">সেন্ট্রাল ওয়্যারহাউস ও ডিস্ট্রিবিউশন হাব</p>
                <p class="mb-0 text-muted">ফোন: {{ setting('phone1') }} | ইমেইল: {{ setting('email1') }}</p>
            </div>
            <div class="text-end">
                <span class="badge-challan">সেন্ট্রাল স্টক ডেলিভারি চালান ও গেটপাস</span>
                <p class="mt-2 mb-0 fw-bold">চালান নং: <span class="text-danger">{{ $dispatch->dispatch_no }}</span></p>
                <p class="mb-0 text-muted">তারিখ: {{ $dispatch->dispatch_date }}</p>
            </div>
        </div>

        <!-- Info Grid -->
        <div class="row mb-3">
            <div class="col-6">
                <div class="p-2 border rounded bg-light">
                    <h6 class="fw-bold text-primary mb-2">প্রাপক এজেন্ট পয়েন্টের বিবরণ</h6>
                    <p class="mb-1"><strong>পয়েন্টের নাম:</strong> {{ $dispatch->agentPoint->name ?? 'N/A' }}</p>
                    <p class="mb-1"><strong>এলাকা / এরিয়া:</strong> <span class="badge bg-info">{{ $dispatch->area }}</span></p>
                    <p class="mb-1"><strong>ঠিকানা:</strong> {{ $dispatch->agentPoint->address ?? 'N/A' }}</p>
                    <p class="mb-0"><strong>যোগাযোগ:</strong> {{ $dispatch->agentPoint->contact_number ?? 'N/A' }} ({{ $dispatch->agentPoint->agent_name ?? 'Officer' }})</p>
                </div>
            </div>
            <div class="col-6">
                <div class="p-2 border rounded bg-light">
                    <h6 class="fw-bold text-primary mb-2">পরিবহন ও চালকের তথ্য</h6>
                    <p class="mb-1"><strong>বণ্টন মাস/বছর:</strong> {{ $dispatch->distribution_month }} {{ $dispatch->distribution_year }}</p>
                    <p class="mb-1"><strong>গাড়ির বিবরণ:</strong> {{ $dispatch->vehicle_info ?: 'সেন্ট্রাল ট্রান্সপোর্ট' }}</p>
                    <p class="mb-1"><strong>চালকের নাম ও ফোন:</strong> {{ $dispatch->driver_info ?: 'অনাবশ্যক' }}</p>
                    <p class="mb-0"><strong>প্রেরণকারী:</strong> {{ $dispatch->dispatcher->name ?? 'Central Admin' }}</p>
                </div>
            </div>
        </div>

        <!-- Dispatched Products Table -->
        <h6 class="fw-bold text-dark mb-2">প্রেরিত পণ্যের বিবরণ (Dispatched Items List)</h6>
        <table class="table table-bordered table-challan text-center mb-3">
            <thead class="table-light">
                <tr>
                    <th style="width: 8%;">#</th>
                    <th style="width: 50%; text-align: left;">পণ্যের নাম (Product Name)</th>
                    <th style="width: 22%;">প্রেরিত পরিমাণ</th>
                    <th style="width: 20%;">একক (Unit)</th>
                </tr>
            </thead>
            <tbody>
                @foreach($dispatch->items as $idx => $item)
                    <tr>
                        <td class="align-middle fw-bold">{{ $idx + 1 }}</td>
                        <td class="text-start align-middle fw-bold text-dark">{{ $item->product_name }}</td>
                        <td class="align-middle fw-bold fs-15 text-primary">{{ floatval($item->sent_quantity) }}</td>
                        <td class="align-middle text-muted">{{ $item->unit }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        @if($dispatch->delivery_note || $dispatch->dispatch_note)
            <div class="p-2 border rounded bg-light mb-4">
                @if($dispatch->delivery_note)<p class="mb-1"><strong>ডেলিভারি নোট:</strong> {{ $dispatch->delivery_note }}</p>@endif
                @if($dispatch->dispatch_note)<p class="mb-0"><strong>গেটপাস নোট:</strong> {{ $dispatch->dispatch_note }}</p>@endif
            </div>
        @endif

        <div class="p-2 bg-light rounded text-muted mb-4">
            <small><i class="fa fa-info-circle me-1"></i> এই পণ্যগুলো সেন্ট্রাল স্টক থেকে হ্রাস করে নির্দিষ্ট এজেন্ট পয়েন্টে কার্ড মেম্বারদের বিতরণের জন্য প্রেরণ করা হলো। মাল অক্ষত অবস্থায় পৌঁছালে এজেন্ট তার প্যানেলে রিসিভ নিশ্চিত করবেন।</small>
        </div>

        <!-- Signatures -->
        <div class="row signature-section text-center">
            <div class="col-4">
                <span class="sig-line">সেন্ট্রাল স্টোর কর্মকর্তা</span>
            </div>
            <div class="col-4">
                <span class="sig-line">চালকের স্বাক্ষর</span>
            </div>
            <div class="col-4">
                <span class="sig-line">এজেন্ট কর্মকর্তার স্বাক্ষর ও সীল</span>
            </div>
        </div>
    </div>
</body>
</html>
