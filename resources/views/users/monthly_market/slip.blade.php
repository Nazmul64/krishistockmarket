<!DOCTYPE html>
<html lang="bn">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Monthly Market Demand & Collection Token - {{ $request->request_no }}</title>
    <link rel="stylesheet" href="{{ asset('assets/plugin/vendor_components/bootstrap/dist/css/bootstrap.css') }}">
    <style>
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; font-size: 13px; color: #222; background: #fff; padding: 25px; }
        .token-card { border: 2px solid #047857; border-radius: 12px; padding: 24px; max-width: 820px; margin: 0 auto; }
        .token-header { border-bottom: 2px solid #047857; padding-bottom: 15px; margin-bottom: 20px; }
        .table-token th, .table-token td { padding: 8px 10px; border: 1px solid #dee2e6; }
        .agent-box { background: #f0fdf4; border: 1px dashed #059669; border-radius: 8px; padding: 12px 16px; margin-bottom: 20px; }
        .token-badge { font-size: 14px; font-weight: bold; background: #047857; color: #fff; padding: 6px 14px; border-radius: 20px; display: inline-block; }
        .signature-section { margin-top: 50px; padding-top: 10px; }
        .sig-line { border-top: 1px solid #444; width: 170px; display: inline-block; text-align: center; font-weight: bold; padding-top: 5px; }
        @media print {
            .no-print { display: none !important; }
            body { padding: 0; }
            .token-card { border: 1px solid #000; }
        }
    </style>
</head>
<body>
    <div class="no-print text-center mb-3">
        <button onclick="window.print()" class="btn btn-success btn-sm me-2"><i class="fa fa-print"></i> টোকেন স্লিপ প্রিন্ট / PDF সংরক্ষণ</button>
        <button onclick="window.close()" class="btn btn-secondary btn-sm">বন্ধ করুন</button>
    </div>

    <div class="token-card">
        <!-- Header -->
        <div class="token-header d-flex justify-content-between align-items-center">
            <div>
                <h3 class="text-success fw-bold mb-1">{{ setting('title') ?? 'iKrishiPoribar / ekrishisheba' }}</h3>
                <p class="mb-0 text-muted">সেন্ট্রাল মাসিক বাজার চাহিদা ও ডিস্ট্রিবিউশন সিস্টেম</p>
                <p class="mb-0 text-muted">হেল্পলাইন: {{ setting('phone1') }} | ইমেইল: {{ setting('email1') }}</p>
            </div>
            <div class="text-end">
                <span class="token-badge">মাসিক বাজার ডেলিভারি টোকেন</span>
                <p class="mt-2 mb-0 fw-bold">রিকোয়েস্ট নং: <span class="text-danger">{{ $request->request_no }}</span></p>
                <p class="mb-0 text-muted">মাস: <strong>{{ $request->month }} {{ $request->year }}</strong></p>
            </div>
        </div>

        <!-- Member & Area Info -->
        <div class="row mb-3">
            <div class="col-6">
                <div class="p-2 border rounded bg-light">
                    <h6 class="fw-bold text-success mb-2">কার্ড সদস্যের তথ্য (Card Holder Info)</h6>
                    <p class="mb-1"><strong>নাম:</strong> {{ $request->user->name }}</p>
                    <p class="mb-1"><strong>কার্ড আইডি:</strong> {{ $request->card_number ?? ('CRD-' . $request->user->id) }}</p>
                    <p class="mb-1"><strong>মোবাইল:</strong> {{ $request->user->phone }}</p>
                    <p class="mb-0"><strong>আবেদনের তারিখ:</strong> {{ $request->request_date }}</p>
                </div>
            </div>
            <div class="col-6">
                <div class="p-2 border rounded bg-light">
                    <h6 class="fw-bold text-success mb-2">ডিস্ট্রিবিউশন এলাকা (Distribution Area)</h6>
                    <p class="mb-1"><strong>নির্ধারিত এলাকা (Area):</strong> <span class="badge bg-success">{{ $request->request_area }}</span></p>
                    <p class="mb-1"><strong>আবেদন স্ট্যাটাস:</strong> <span class="badge bg-primary">{{ strtoupper($request->status) }}</span></p>
                    <p class="mb-1"><strong>সংগ্রহ স্ট্যাটাস:</strong> <span class="badge bg-info">{{ strtoupper($request->collection_status) }}</span></p>
                    <p class="mb-0"><strong>মোট আইটেমস:</strong> {{ $request->total_items }} টি পণ্য</p>
                </div>
            </div>
        </div>

        <!-- Collection Point Box -->
        <div class="agent-box">
            <h6 class="fw-bold text-success mb-1"><i class="fa fa-map-marker text-danger me-1"></i> পণ্য সংগ্রহের নির্ধারিত স্থান (Assigned Agent Point)</h6>
            <p class="mb-1 fw-bold fs-15 text-dark">{{ $request->agent_point_name ?? ($request->agentPoint->name ?? 'N/A') }}</p>
            <p class="mb-1 text-muted"><strong>ঠিকানা:</strong> {{ $request->agentPoint->address ?? 'ঠিকানা সংরক্ষিত' }}</p>
            <p class="mb-0 text-muted"><strong>এজেন্ট যোগাযোগ:</strong> {{ $request->agentPoint->contact_number ?? 'N/A' }} | <strong>এজেন্ট প্রতিনিধি:</strong> {{ $request->agentPoint->agent_name ?? 'Agent Officer' }}</p>
            @if($request->collection_instruction)
                <div class="mt-2 p-2 bg-white rounded border border-success text-success">
                    <i class="fa fa-bullhorn me-1"></i> <strong>সংগ্রহের নির্দেশনা:</strong> {{ $request->collection_instruction }}
                </div>
            @endif
        </div>

        <!-- Requested & Allocated Products Table -->
        <h6 class="fw-bold text-dark mb-2">পণ্যের বিবরণ ও বরাদ্দকৃত পরিমাণ</h6>
        <table class="table table-bordered table-token text-center mb-4">
            <thead class="table-light">
                <tr>
                    <th style="width: 8%;">#</th>
                    <th style="width: 42%; text-align: left;">পণ্যের নাম (Product Name)</th>
                    <th style="width: 25%;">চাহিদাকৃত পরিমাণ</th>
                    <th style="width: 25%;">অনুমোদিত/বরাদ্দকৃত পরিমাণ</th>
                </tr>
            </thead>
            <tbody>
                @foreach($request->items as $idx => $item)
                    <tr>
                        <td class="align-middle fw-bold">{{ $idx + 1 }}</td>
                        <td class="text-start align-middle fw-bold text-dark">{{ $item->product_name }}</td>
                        <td class="align-middle">{{ floatval($item->requested_quantity) }} {{ $item->unit }}</td>
                        <td class="align-middle fw-bold text-success">
                            {{ floatval($item->approved_quantity > 0 ? $item->approved_quantity : $item->requested_quantity) }} {{ $item->unit }}
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <div class="p-2 bg-light rounded text-muted mb-4">
            <small><i class="fa fa-shield me-1 text-success"></i> <strong>নির্দেশনা:</strong> পণ্য সংগ্রহের সময় এই টোকেন স্লিপটি নির্ধারিত এজেন্ট পয়েন্টে প্রদর্শন করুন। আংশিক পণ্য সংগ্রহ করলে পরবর্তী ধাপে বাকি পণ্য তোলার সুযোগ থাকবে।</small>
        </div>

        <!-- Signature Area -->
        <div class="row signature-section text-center">
            <div class="col-4">
                <span class="sig-line">কার্ড সদস্যের স্বাক্ষর</span>
            </div>
            <div class="col-4">
                <span class="sig-line">এজেন্ট পয়েন্ট কর্মকর্তার স্বাক্ষর</span>
            </div>
            <div class="col-4">
                <span class="sig-line">সেন্ট্রাল অথরিটি</span>
            </div>
        </div>
    </div>
</body>
</html>
