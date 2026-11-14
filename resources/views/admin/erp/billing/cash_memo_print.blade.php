<!DOCTYPE html>
<html lang="bn">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>CASH MEMO - {{ $memo->memo_number }}</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <style>
        body {
            background-color: #f8fafc;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            color: #1e293b;
        }
        .memo-box {
            max-width: 800px;
            margin: 30px auto;
            background: #ffffff;
            border: 1px solid #cbd5e1;
            padding: 35px;
            border-radius: 8px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.05);
        }
        .memo-badge {
            display: inline-block;
            background: #2563eb;
            color: #ffffff;
            padding: 5px 24px;
            font-weight: 800;
            letter-spacing: 1.5px;
            border-radius: 4px;
            text-transform: uppercase;
        }
        .table-items th {
            background: #f1f5f9;
            font-size: 13px;
            text-transform: uppercase;
            font-weight: 700;
        }
        .signature-line {
            border-top: 1px dashed #64748b;
            margin-top: 55px;
            padding-top: 5px;
            font-size: 13px;
            font-weight: 600;
            text-align: center;
        }
        @media print {
            body { background: #fff; }
            .no-print { display: none !important; }
            .memo-box { border: none; box-shadow: none; margin: 0; padding: 15px; max-width: 100%; }
        }
    </style>
</head>
<body>

<div class="text-center my-3 no-print">
    <button onclick="window.print()" class="btn btn-primary px-4 fw-bold shadow-sm me-2">
        <i class="fa fa-print me-1"></i> ক্যাশ মেমো প্রিন্ট করুন (Print Memo)
    </button>
    <button onclick="window.close()" class="btn btn-secondary px-3">
        <i class="fa fa-times me-1"></i> বন্ধ করুন
    </button>
</div>

<div class="memo-box">
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
            <div class="small text-muted">ফোন: {{ $company->phone ?? '০১৭xxxxxxxx' }} | ইমেইল: {{ $company->email ?? 'info@ekrishisheba.com' }}</div>
        </div>
        <div class="col-3 text-end">
            <span class="badge bg-light text-dark border font-monospace">ক্যাশ ইনভয়েস</span>
        </div>
    </div>

    <!-- Title Badge -->
    <div class="text-center my-3">
        <div class="memo-badge">ক্যাশ মেমো / CASH MEMO</div>
    </div>

    <!-- Customer & Memo Info -->
    <div class="row g-2 mb-3 p-3 bg-light rounded-3 small">
        <div class="col-6">
            <div><strong>মেমো নম্বর:</strong> <span class="font-monospace text-primary fw-bold">{{ $memo->memo_number }}</span></div>
            <div><strong>তারিখ:</strong> {{ $memo->memo_date ? \Carbon\Carbon::parse($memo->memo_date)->format('d/m/Y') : date('d/m/Y') }}</div>
            <div><strong>বিক্রয় শাখা:</strong> প্রধান শোরুম ও সেলস কাউন্টার</div>
        </div>
        <div class="col-6 text-end">
            <div><strong>ক্রেতার নাম:</strong> <span class="fw-bold text-dark">{{ $memo->customer_name }}</span></div>
            <div><strong>মোবাইল:</strong> {{ $memo->customer_phone ?? 'N/A' }}</div>
            @if($memo->customer_address)
                <div><strong>ঠিকানা:</strong> {{ $memo->customer_address }}</div>
            @endif
        </div>
    </div>

    <!-- Items Table -->
    <div class="table-responsive mb-3">
        <table class="table table-bordered table-items align-middle mb-0">
            <thead>
                <tr>
                    <th style="width: 8%;" class="text-center">ক্র.নং</th>
                    <th style="width: 45%;">পণ্যের বিবরণ (Item Description)</th>
                    <th style="width: 15%;" class="text-center">পরিমাণ</th>
                    <th style="width: 15%;" class="text-end">দর (৳)</th>
                    <th style="width: 17%;" class="text-end">মোট মূল্য (৳)</th>
                </tr>
            </thead>
            <tbody>
                @foreach($memo->items as $idx => $it)
                    <tr>
                        <td class="text-center">{{ $idx + 1 }}</td>
                        <td>
                            <strong class="text-dark">{{ $it->item->name ?? $it->item_name }}</strong>
                            @if($it->item && $it->item->item_code)
                                <div class="font-size-11 text-muted">কোড: {{ $it->item->item_code }}</div>
                            @endif
                        </td>
                        <td class="text-center fw-bold">{{ number_format($it->quantity, 2) }}</td>
                        <td class="text-end">{{ number_format($it->unit_price, 2) }}</td>
                        <td class="text-end fw-bold">{{ number_format($it->total_price, 2) }}</td>
                    </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="4" class="text-end fw-bold">সাবটোটাল (Subtotal):</td>
                    <td class="text-end fw-bold">৳ {{ number_format($memo->subtotal, 2) }}</td>
                </tr>
                @if($memo->discount > 0)
                <tr>
                    <td colspan="4" class="text-end text-muted">ছাড় / ডিসকাউন্ট:</td>
                    <td class="text-end text-danger">- ৳ {{ number_format($memo->discount, 2) }}</td>
                </tr>
                @endif
                @if($memo->vat > 0)
                <tr>
                    <td colspan="4" class="text-end text-muted">ভ্যাট:</td>
                    <td class="text-end text-muted">+ ৳ {{ number_format($memo->vat, 2) }}</td>
                </tr>
                @endif
                <tr class="fw-bold bg-light font-size-15">
                    <td colspan="4" class="text-end text-primary">সর্বমোট প্রদেয় বিল (Grand Total):</td>
                    <td class="text-end text-primary">৳ {{ number_format($memo->grand_total, 2) }}</td>
                </tr>
                <tr class="fw-bold">
                    <td colspan="4" class="text-end text-success">পরিশোধিত অর্থ (Paid Amount):</td>
                    <td class="text-end text-success">৳ {{ number_format($memo->paid_amount, 2) }}</td>
                </tr>
                @if($memo->due_amount > 0)
                <tr class="fw-bold text-danger">
                    <td colspan="4" class="text-end">অবশিষ্ট বকেয়া (Due Amount):</td>
                    <td class="text-end">৳ {{ number_format($memo->due_amount, 2) }}</td>
                </tr>
                @endif
            </tfoot>
        </table>
    </div>

    <!-- Signatures -->
    <div class="row pt-4 text-center">
        <div class="col-6">
            <div class="signature-line">ক্রেতার স্বাক্ষর</div>
        </div>
        <div class="col-6">
            <div class="signature-line">বিক্রেতা / ক্যাশ কাউন্টার স্বাক্ষর</div>
        </div>
    </div>
</div>

</body>
</html>

