<!DOCTYPE html>
<html lang="bn">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>MONEY RECEIPT - {{ $receipt->receipt_number }}</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <style>
        body {
            background-color: #f8fafc;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            color: #1e293b;
        }
        .receipt-card {
            max-width: 800px;
            margin: 30px auto;
            background: #ffffff;
            border: 2px solid #0f172a;
            padding: 35px;
            border-radius: 8px;
            position: relative;
        }
        .receipt-title {
            display: inline-block;
            background: #0f172a;
            color: #ffffff;
            padding: 5px 22px;
            font-weight: 800;
            letter-spacing: 1.5px;
            border-radius: 4px;
            text-transform: uppercase;
        }
        .dotted-line {
            border-bottom: 1px dotted #334155;
            display: inline-block;
            font-weight: 700;
            color: #0f172a;
        }
        .amount-box {
            border: 2px solid #0f172a;
            padding: 10px 20px;
            background: #f8fafc;
            font-size: 20px;
            font-weight: 800;
            display: inline-block;
            border-radius: 6px;
        }
        .signature-col {
            border-top: 1px dashed #64748b;
            margin-top: 60px;
            padding-top: 5px;
            font-size: 13px;
            font-weight: 600;
            text-align: center;
        }
        @media print {
            body { background: #fff; }
            .no-print { display: none !important; }
            .receipt-card { border: 2px solid #000; margin: 0; max-width: 100%; box-shadow: none; }
        }
    </style>
</head>
<body>

<div class="text-center my-3 no-print">
    <button onclick="window.print()" class="btn btn-success px-4 fw-bold shadow-sm me-2">
        <i class="fa fa-print me-1"></i> মানি রিসিট প্রিন্ট করুন (Print Receipt)
    </button>
    <button onclick="window.close()" class="btn btn-secondary px-3">
        <i class="fa fa-times me-1"></i> বন্ধ করুন
    </button>
</div>

<div class="receipt-card">
    <!-- Header -->
    <div class="row align-items-center mb-3">
        <div class="col-3">
            @if(!empty($company->logo))
                <img src="{{ asset($company->logo) }}" alt="Logo" style="max-height: 65px;">
            @else
                <h3 class="fw-bold text-success mb-0">{{ $company->company_name ?? 'ই-কৃষি সেবা' }}</h3>
            @endif
        </div>
        <div class="col-6 text-center">
            <h4 class="fw-bold mb-1">{{ $company->company_name ?? 'ই-কৃষি সেবা কর্পোরেট লিমিটেড' }}</h4>
            <div class="small text-muted">{{ $company->address ?? 'হেড অফিস, ঢাকা, বাংলাদেশ' }}</div>
            <div class="small text-muted">মোবাইল: {{ $company->phone ?? '০১৭xxxxxxxx' }} | ইমেইল: {{ $company->email ?? 'info@ekrishisheba.com' }}</div>
        </div>
        <div class="col-3 text-end">
            <span class="badge bg-dark text-white font-monospace px-2 py-1">কাস্টমার কপি</span>
        </div>
    </div>

    <!-- Title Badge -->
    <div class="text-center my-3">
        <div class="receipt-title">টাকা প্রাপ্তির রশিদ / MONEY RECEIPT</div>
    </div>

    <!-- Meta Details -->
    <div class="d-flex justify-content-between mb-4 pb-2 border-bottom">
        <div><strong>রশিদ নং (Receipt No):</strong> <span class="font-monospace text-primary fw-bold">{{ $receipt->receipt_number }}</span></div>
        <div><strong>তারিখ (Date):</strong> <span class="fw-bold">{{ $receipt->receipt_date ? \Carbon\Carbon::parse($receipt->receipt_date)->format('d/m/Y') : date('d/m/Y') }}</span></div>
    </div>

    <!-- Receipt Body Fields -->
    <div class="lh-lg mb-4 font-size-15">
        <div>
            প্রাপ্ত হলো জনাব/মেসার্স (Received with thanks from): 
            <span class="dotted-line w-75 ps-2">{{ $receipt->customer_name }}</span>
        </div>
        <div>
            যোগাযোগের ঠিকানা (Address & Phone): 
            <span class="dotted-line w-75 ps-2">{{ $receipt->customer_address ?? 'N/A' }} (ফোন: {{ $receipt->customer_phone ?? '-' }})</span>
        </div>
        <div>
            কথায় (Amount in Words): 
            <span class="dotted-line w-75 ps-2 fst-italic">{{ $receipt->amount_in_words ?? number_format($receipt->received_amount, 2) . ' Taka Only' }}</span>
        </div>
        <div>
            বাবদ / উদ্দেশ্য (On Account Of): 
            <span class="dotted-line w-75 ps-2">{{ $receipt->payment_purpose }}</span>
        </div>
        <div>
            প্রদানের মাধ্যম (Payment Method): 
            <span class="dotted-line w-75 ps-2">{{ strtoupper($receipt->payment_method) }} @if($receipt->bankAccount) ({{ $receipt->bankAccount->account_name }}) @endif</span>
        </div>
    </div>

    <!-- Amount and Due Summary -->
    <div class="row align-items-center mb-4 p-3 bg-light rounded-3">
        <div class="col-6">
            <div class="amount-box text-success">
                ৳ {{ number_format($receipt->received_amount, 2) }}/-
            </div>
        </div>
        <div class="col-6 text-end small">
            @if($receipt->previous_due > 0)
                <div>পূর্ববর্তী বকেয়া: <strong>৳ {{ number_format($receipt->previous_due, 2) }}</strong></div>
            @endif
            @if($receipt->remaining_due > 0)
                <div class="text-danger">বর্তমান অবশিষ্ট বকেয়া: <strong>৳ {{ number_format($receipt->remaining_due, 2) }}</strong></div>
            @endif
        </div>
    </div>

    @if($receipt->remarks)
        <div class="small text-muted mb-4"><strong>মন্তব্য:</strong> {{ $receipt->remarks }}</div>
    @endif

    <!-- Signatures -->
    <div class="row pt-4">
        <div class="col-6">
            <div class="signature-col">{{ $receipt->customer_name }}<br><small class="text-muted">জমা প্রদানকারীর স্বাক্ষর</small></div>
        </div>
        <div class="col-6">
            <div class="signature-col">{{ $receipt->received_by ?? 'গ্রহীতা' }}<br><small class="text-muted">অনুমোদিত স্বাক্ষর (কর্তৃপক্ষ)</small></div>
        </div>
    </div>
</div>

</body>
</html>

