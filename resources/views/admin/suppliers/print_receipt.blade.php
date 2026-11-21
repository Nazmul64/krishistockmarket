<!DOCTYPE html>
<html lang="bn">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Supplier Payment Voucher / Receipt - #{{ $payment->receipt_no ?? $payment->id }}</title>
    <link rel="stylesheet" href="{{ asset('assets/plugin/vendor_components/bootstrap/dist/css/bootstrap.css') }}">
    <style>
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; font-size: 13px; color: #333; background: #fff; padding: 30px; }
        .voucher-box { border: 2px solid #28a745; border-radius: 8px; padding: 25px; max-width: 800px; margin: 0 auto; }
        .voucher-header { border-bottom: 2px dashed #28a745; padding-bottom: 15px; margin-bottom: 20px; }
        .table-voucher th, .table-voucher td { padding: 10px; border-bottom: 1px solid #e9ecef; }
        .signature-area { margin-top: 60px; padding-top: 10px; }
        .sig-line { border-top: 1px solid #777; width: 160px; display: inline-block; text-align: center; font-weight: bold; padding-top: 5px; }
        @media print {
            .no-print { display: none !important; }
            body { padding: 0; }
            .voucher-box { border: 1px solid #000; }
        }
    </style>
</head>
<body>
    <div class="no-print text-center mb-4">
        <button onclick="window.print()" class="btn btn-primary btn-sm me-2"><i class="fa fa-print"></i> ভাউচার প্রিন্ট / PDF সংরক্ষণ</button>
        <button onclick="window.close()" class="btn btn-secondary btn-sm">বন্ধ করুন</button>
    </div>

    <div class="voucher-box">
        <div class="voucher-header d-flex justify-content-between align-items-center">
            <div>
                <h3 class="text-success fw-bold mb-1">{{ setting('title') ?? 'iKrishiPoribar / ekrishisheba' }}</h3>
                <p class="mb-0 text-muted">{{ setting('address1') ?? 'ঢাকা, বাংলাদেশ' }}</p>
                <p class="mb-0 text-muted">হেল্পলাইন: {{ setting('phone1') }} | ইমেইল: {{ setting('email1') }}</p>
            </div>
            <div class="text-end">
                <span class="badge bg-success fs-14 px-3 py-2">পেমেন্ট ভাউচার / মানি রসিদ</span>
                <p class="mt-2 mb-0 fw-bold">রসিদ নং: <span class="text-danger">{{ $payment->receipt_no ?? ('RCP-' . $payment->id) }}</span></p>
                <p class="mb-0 text-muted">তারিখ: {{ \Carbon\Carbon::parse($payment->payment_date)->format('d F, Y') }}</p>
            </div>
        </div>

        <div class="row mb-3">
            <div class="col-6">
                <p class="mb-1"><strong>প্রাপক / সাপ্লায়ার:</strong> {{ $payment->supplier->supplierProfile->company_name ?? $payment->supplier->name }}</p>
                <p class="mb-1"><strong>সাপ্লায়ার কোড:</strong> {{ $payment->supplier->supplierProfile->supplier_code ?? 'N/A' }}</p>
                <p class="mb-1"><strong>মোবাইল:</strong> {{ $payment->supplier->phone }}</p>
                <p class="mb-0"><strong>ঠিকানা:</strong> {{ $payment->supplier->supplierProfile->address ?? '' }}</p>
            </div>
            <div class="col-6 text-end">
                <p class="mb-1"><strong>পেমেন্ট মেথড:</strong> <span class="badge {{ $payment->payment_method == 'bank' ? 'bg-primary' : 'bg-warning text-dark' }}">{{ strtoupper($payment->payment_method) }}</span></p>
                @if($payment->payment_method == 'bank')
                    <p class="mb-1"><strong>ব্যাংকের নাম:</strong> {{ $payment->bank_name }}</p>
                    <p class="mb-1"><strong>অ্যাকাউন্ট নম্বর:</strong> {{ $payment->account_number }}</p>
                    <p class="mb-0"><strong>ট্রানজেকশন আইডি:</strong> {{ $payment->transaction_id ?? 'N/A' }}</p>
                @else
                    <p class="mb-1"><strong>ক্যাশ অ্যাকাউন্ট:</strong> {{ $payment->cash_account ?? 'Main Cash Desk' }}</p>
                @endif
            </div>
        </div>

        <table class="table table-voucher mt-3">
            <thead class="table-light">
                <tr>
                    <th style="width: 70%;">বিবরণ (Particulars)</th>
                    <th style="width: 30%; text-align: right;">পরিশোধিত টাকার পরিমাণ (Amount ৳)</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>
                        <strong class="text-dark">পণ্য সরবরাহ বাবদ বিল পরিশোধ</strong><br>
                        <span class="text-muted">{{ $payment->note ?: 'সাপ্লায়ার অ্যাকাউন্ট লেজারে সরাসরি সমন্বয়কৃত।' }}</span>
                    </td>
                    <td class="text-end fw-bold fs-16 text-success">
                        ৳{{ number_format($payment->amount, 2) }}
                    </td>
                </tr>
            </tbody>
            <tfoot>
                <tr class="fw-bold bg-light">
                    <td class="text-end">মোট পরিশোধিত টাকা (Net Paid):</td>
                    <td class="text-end text-success fs-18">৳{{ number_format($payment->amount, 2) }}</td>
                </tr>
            </tfoot>
        </table>

        <div class="mt-4 p-2 bg-light rounded text-muted">
            <small><i class="fa fa-info-circle me-1"></i> এই মানি রসিদটি কম্পিউটার জেনারেটেড এবং সাপ্লায়ার লেজারের সাথে স্বয়ংক্রিয়ভাবে সিঙ্ক করা হয়েছে। প্রস্তুতকারী: {{ $payment->creator->name ?? 'Admin / Accounts' }}</small>
        </div>

        <div class="row signature-area text-center">
            <div class="col-4">
                <span class="sig-line">গ্রহণকারীর স্বাক্ষর</span>
            </div>
            <div class="col-4">
                <span class="sig-line">হিসাবরক্ষক</span>
            </div>
            <div class="col-4">
                <span class="sig-line">কর্তৃপক্ষের স্বাক্ষর</span>
            </div>
        </div>
    </div>
</body>
</html>
