<!DOCTYPE html>
<html lang="bn">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>চালান / ইনভয়েস - {{ $supply->invoice_no }}</title>
    <link rel="stylesheet" href="{{ asset('assets/plugin/vendor_components/bootstrap/dist/css/bootstrap.css') }}">
    <style>
        body { font-family: Arial, sans-serif; font-size: 13px; color: #333; background: #fff; padding: 20px; }
        .invoice-box { border: 1px solid #ccc; padding: 20px; border-radius: 5px; }
        .invoice-header { border-bottom: 2px solid #28a745; padding-bottom: 10px; margin-bottom: 20px; }
        @media print {
            .no-print { display: none !important; }
            body { padding: 0; }
            .invoice-box { border: none; padding: 0; }
        }
    </style>
</head>
<body>
    <div class="no-print text-end mb-3">
        <button onclick="window.print()" class="btn btn-primary btn-sm"><i class="fa fa-print"></i> প্রিন্ট / PDF</button>
        <button onclick="window.close()" class="btn btn-secondary btn-sm">বন্ধ করুন</button>
    </div>

    <div class="invoice-box">
        <div class="invoice-header d-flex justify-content-between align-items-center">
            <div>
                <h2 class="text-success mb-0 fw-bold">{{ setting('title') ?? 'iKrishiPoribar' }}</h2>
                <p class="mb-0 text-muted">{{ setting('address1') ?? 'ঢাকা, বাংলাদেশ' }}</p>
                <p class="mb-0 text-muted">ফোন: {{ setting('phone1') }}</p>
            </div>
            <div class="text-end">
                <h3 class="text-dark fw-bold mb-0">পণ্য সরবরাহ চালান (Challan)</h3>
                <p class="mb-0"><strong>ইনভয়েস নং:</strong> {{ $supply->invoice_no }}</p>
                <p class="mb-0"><strong>তারিখ:</strong> {{ $supply->supply_date }}</p>
            </div>
        </div>

        <div class="row mb-4">
            <div class="col-6">
                <h6 class="fw-bold text-success">সরবরাহকারীর তথ্য (Supplier Information):</h6>
                <p class="mb-1"><strong>কোম্পানি:</strong> {{ $supply->supplier->supplierProfile->company_name ?? 'N/A' }}</p>
                <p class="mb-1"><strong>সাপ্লায়ার কোড:</strong> {{ $supply->supplier->supplierProfile->supplier_code ?? 'N/A' }}</p>
                <p class="mb-1"><strong>নাম:</strong> {{ $supply->supplier->name }}</p>
                <p class="mb-1"><strong>মোবাইল:</strong> {{ $supply->supplier->phone }}</p>
                <p class="mb-0"><strong>ঠিকানা:</strong> {{ $supply->supplier->supplierProfile->district_thana ?? '' }}, {{ $supply->supplier->supplierProfile->address ?? '' }}</p>
            </div>
            <div class="col-6 text-end">
                <h6 class="fw-bold text-success">চালান বিবরণ:</h6>
                <p class="mb-1"><strong>স্ট্যাটাস:</strong>
                    @if($supply->status == 'approved')
                        <span class="badge bg-success">অনুমোদিত (Approved)</span>
                    @elseif($supply->status == 'pending')
                        <span class="badge bg-warning text-dark">পেন্ডিং (Pending)</span>
                    @else
                        <span class="badge bg-danger">রিজেক্টেড (Rejected)</span>
                    @endif
                </p>
                @if($supply->approved_at)
                    <p class="mb-0"><strong>অনুমোদনের সময়:</strong> {{ $supply->approved_at }}</p>
                @endif
            </div>
        </div>

        <table class="table table-bordered">
            <thead class="bg-dark text-white">
                <tr>
                    <th>ক্রমিক</th>
                    <th>পণ্যের নাম</th>
                    <th>ক্যাটাগরি</th>
                    <th>পরিমাণ (Quantity)</th>
                    <th>প্রতি ইউনিট রেট (Rate ৳)</th>
                    <th class="text-end">মোট মূল্য (Total Amount ৳)</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>1</td>
                    <td><strong>{{ $supply->product_name }}</strong></td>
                    <td>{{ $supply->category ?? '-' }}</td>
                    <td>{{ floatval($supply->quantity) }} {{ $supply->unit }}</td>
                    <td>৳{{ number_format($supply->rate, 2) }}</td>
                    <td class="text-end fw-bold">৳{{ number_format($supply->total_amount, 2) }}</td>
                </tr>
            </tbody>
            <tfoot>
                <tr>
                    <th colspan="5" class="text-end fs-15">সর্বমোট (Total Amount):</th>
                    <th class="text-end fs-15 text-success">৳{{ number_format($supply->total_amount, 2) }}</th>
                </tr>
            </tfoot>
        </table>

        @if($supply->note)
            <div class="mt-3 p-2 border rounded bg-light">
                <strong>নোট/বিবরণ:</strong> {{ $supply->note }}
            </div>
        @endif

        @if($supply->invoice_file)
            <div class="mt-3 no-print">
                <strong>সংযুক্ত চালানের স্ক্যান/ফাইল:</strong>
                <a href="{{ asset($supply->invoice_file) }}" target="_blank" class="btn btn-sm btn-info ms-2">
                    <i class="fa fa-external-link"></i> ফাইলটি আলাদা ট্যাবে খুলুন
                </a>
            </div>
        @endif

        <div class="row mt-5 pt-4">
            <div class="col-6 text-center">
                <p>____________________<br>পণ্য প্রদানকারী (সাপ্লায়ার)</p>
            </div>
            <div class="col-6 text-center">
                <p>____________________<br>পণ্য গ্রহণকারী (স্টোর ইনচার্জ/কোম্পানি)</p>
            </div>
        </div>
    </div>
</body>
</html>
