<!DOCTYPE html>
<html lang="bn">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ strtoupper($voucher->voucher_type) }} VOUCHER - {{ $voucher->voucher_number }}</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <style>
        body {
            background-color: #f8fafc;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            color: #1e293b;
        }
        .voucher-box {
            max-width: 820px;
            margin: 30px auto;
            background: #ffffff;
            border: 1px solid #e2e8f0;
            padding: 40px;
            border-radius: 8px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.05);
        }
        .voucher-title {
            display: inline-block;
            border: 2px solid #0f172a;
            padding: 6px 24px;
            font-weight: 800;
            letter-spacing: 1.5px;
            border-radius: 4px;
            text-transform: uppercase;
        }
        .table-entries th {
            background: #f1f5f9;
            font-size: 13px;
            text-transform: uppercase;
            font-weight: 700;
        }
        .signature-line {
            border-top: 1px dashed #64748b;
            margin-top: 55px;
            padding-top: 6px;
            font-size: 13px;
            font-weight: 600;
            text-align: center;
        }
        @media print {
            body {
                background: #ffffff;
            }
            .no-print {
                display: none !important;
            }
            .voucher-box {
                border: none;
                box-shadow: none;
                margin: 0;
                padding: 15px;
                max-width: 100%;
            }
        }
    </style>
</head>
<body>

<div class="text-center my-3 no-print">
    <button onclick="window.print()" class="btn btn-primary px-4 fw-bold shadow-sm me-2">
        <i class="fa fa-print me-1"></i> ভাউচার প্রিন্ট করুন (Print)
    </button>
    <button onclick="window.close()" class="btn btn-secondary px-3">
        <i class="fa fa-times me-1"></i> বন্ধ করুন
    </button>
</div>

<div class="voucher-box">
    <!-- Header -->
    <div class="row align-items-center mb-4">
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
            <div class="small text-muted">ফোন: {{ $company->phone ?? '০১৭xxxxxxxx' }} | ইমেইল: {{ $company->email ?? 'info@ekrishisheba.com' }}</div>
        </div>
        <div class="col-3 text-end">
            <span class="badge bg-light text-dark border font-monospace">ভাউচার কপি</span>
        </div>
    </div>

    <!-- Voucher Type Badge -->
    <div class="text-center my-3">
        <div class="voucher-title">
            @if($voucher->voucher_type == 'payment')
                পেমেন্ট ভাউচার (PAYMENT VOUCHER)
            @elseif($voucher->voucher_type == 'receipt')
                রিসিট ভাউচার (RECEIPT VOUCHER)
            @elseif($voucher->voucher_type == 'contra')
                কন্ট্রা ভাউচার (CONTRA VOUCHER)
            @elseif($voucher->voucher_type == 'journal')
                জার্নাল ভাউচার (JOURNAL VOUCHER)
            @else
                সমন্বয় ভাউচার (ADJUSTMENT VOUCHER)
            @endif
        </div>
    </div>

    <!-- Meta Details Row -->
    <div class="row g-2 mb-4 p-3 bg-light rounded-3 small">
        <div class="col-6">
            <div><strong>ভাউচার নম্বর:</strong> <span class="font-monospace text-primary fw-bold">{{ $voucher->voucher_number }}</span></div>
            <div><strong>তারিখ:</strong> {{ $voucher->voucher_date ? \Carbon\Carbon::parse($voucher->voucher_date)->format('d/m/Y') : date('d/m/Y') }}</div>
            <div><strong>প্রাপক / প্রদানকারী:</strong> {{ $voucher->payee_or_payer }}</div>
        </div>
        <div class="col-6 text-end">
            <div><strong>পেমেন্ট মাধ্যম:</strong> {{ strtoupper($voucher->payment_method ?? 'CASH') }}</div>
            @if($voucher->bankAccount)
                <div><strong>অ্যাকাউন্ট:</strong> {{ $voucher->bankAccount->account_name }} ({{ $voucher->bankAccount->account_number }})</div>
            @endif
            @if($voucher->reference_no)
                <div><strong>রেফারেন্স:</strong> {{ $voucher->reference_no }}</div>
            @endif
        </div>
    </div>

    <!-- Entries Table -->
    <div class="table-responsive mb-3">
        <table class="table table-bordered table-entries align-middle mb-0">
            <thead>
                <tr>
                    <th style="width: 8%;" class="text-center">ক্র.নং</th>
                    <th style="width: 42%;">হিসাবের খাত (Chart of Accounts)</th>
                    <th style="width: 25%;" class="text-end">ডেবিট (Debit ৳)</th>
                    <th style="width: 25%;" class="text-end">ক্রেডিট (Credit ৳)</th>
                </tr>
            </thead>
            <tbody>
                @php $totalDebit = 0; $totalCredit = 0; @endphp
                @foreach($voucher->entries as $idx => $entry)
                    @php 
                        $totalDebit += $entry->debit; 
                        $totalCredit += $entry->credit; 
                    @endphp
                    <tr>
                        <td class="text-center">{{ $idx + 1 }}</td>
                        <td>
                            <strong class="text-dark">[{{ $entry->account->account_code ?? '-' }}] {{ $entry->account->account_name ?? 'Account' }}</strong>
                            @if($entry->description)
                                <div class="font-size-11 text-muted">{{ $entry->description }}</div>
                            @endif
                        </td>
                        <td class="text-end fw-bold">
                            {{ $entry->debit > 0 ? number_format($entry->debit, 2) : '-' }}
                        </td>
                        <td class="text-end fw-bold">
                            {{ $entry->credit > 0 ? number_format($entry->credit, 2) : '-' }}
                        </td>
                    </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr class="fw-bold bg-light">
                    <td colspan="2" class="text-end">সর্বমোট (Grand Total):</td>
                    <td class="text-end text-primary">৳ {{ number_format($totalDebit, 2) }}</td>
                    <td class="text-end text-success">৳ {{ number_format($totalCredit, 2) }}</td>
                </tr>
            </tfoot>
        </table>
    </div>

    <!-- Words & Description -->
    <div class="p-3 border rounded-3 mb-4 bg-light small">
        <div class="mb-1">
            <strong>কথায় (In Words):</strong> 
            <span class="fst-italic text-capitalize">{{ $voucher->amount_in_words ?? number_format($voucher->amount, 2) . ' Taka Only' }}</span>
        </div>
        @if($voucher->description)
            <div><strong>বিবরণ (Narration):</strong> {{ $voucher->description }}</div>
        @endif
    </div>

    <!-- Signatures Row -->
    <div class="row pt-4 text-center">
        <div class="col-3">
            <div class="signature-line">{{ $voucher->prepared_by ?? 'প্রস্তুতকারী' }}</div>
        </div>
        <div class="col-3">
            <div class="signature-line">হিসাবরক্ষক</div>
        </div>
        <div class="col-3">
            <div class="signature-line">অভ্যন্তরীণ অডিট</div>
        </div>
        <div class="col-3">
            <div class="signature-line">{{ $voucher->approved_by ?? 'অনুমোদনকারী' }}</div>
        </div>
    </div>
</div>

</body>
</html>

