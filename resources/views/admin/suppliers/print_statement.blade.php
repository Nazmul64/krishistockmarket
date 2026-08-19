<!DOCTYPE html>
<html lang="bn">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Supplier Account Statement - {{ $supplier->supplierProfile->company_name ?? $supplier->name }}</title>
    <link rel="stylesheet" href="{{ asset('assets/plugin/vendor_components/bootstrap/dist/css/bootstrap.css') }}">
    <style>
        body { font-family: Arial, sans-serif; font-size: 13px; color: #333; background: #fff; padding: 20px; }
        .invoice-header { border-bottom: 2px solid #28a745; padding-bottom: 10px; margin-bottom: 20px; }
        .table-bordered th, .table-bordered td { border: 1px solid #ddd !important; padding: 8px; }
        @media print {
            .no-print { display: none !important; }
            body { padding: 0; }
        }
    </style>
</head>
<body>
    <div class="no-print text-end mb-3">
        <button onclick="window.print()" class="btn btn-primary btn-sm"><i class="fa fa-print"></i> প্রিন্ট বা PDF ডাউনলোড</button>
        <button onclick="window.close()" class="btn btn-secondary btn-sm">বন্ধ করুন</button>
    </div>

    <div class="invoice-header d-flex justify-content-between align-items-center">
        <div>
            <h2 class="text-success mb-0 fw-bold">{{ setting('title') ?? 'iKrishiPoribar' }}</h2>
            <p class="mb-0 text-muted">{{ setting('address1') ?? 'ঢাকা, বাংলাদেশ' }}</p>
            <p class="mb-0 text-muted">ফোন: {{ setting('phone1') }} | ইমেইল: {{ setting('email1') }}</p>
        </div>
        <div class="text-end">
            <h3 class="text-dark fw-bold mb-0">সাপ্লায়ার অ্যাকাউন্ট স্টেটমেন্ট</h3>
            <p class="mb-0 text-muted">প্রিন্টের তারিখ: {{ date('Y-m-d H:i') }}</p>
        </div>
    </div>

    <!-- Supplier Information -->
    <div class="row mb-4">
        <div class="col-6">
            <div class="p-3 border rounded bg-light">
                <h5 class="fw-bold text-success mb-2">সাপ্লায়ারের তথ্য (Supplier Info)</h5>
                <p class="mb-1"><strong>কোম্পানির নাম:</strong> {{ $supplier->supplierProfile->company_name ?? 'N/A' }}</p>
                <p class="mb-1"><strong>সাপ্লায়ার কোড:</strong> {{ $supplier->supplierProfile->supplier_code ?? 'N/A' }}</p>
                <p class="mb-1"><strong>মালিক/প্রতিনিধি:</strong> {{ $supplier->name }}</p>
                <p class="mb-1"><strong>মোবাইল:</strong> {{ $supplier->phone }}</p>
                <p class="mb-0"><strong>ঠিকানা:</strong> {{ $supplier->supplierProfile->district_thana ?? '' }}, {{ $supplier->supplierProfile->address ?? '' }}</p>
            </div>
        </div>
        <div class="col-6">
            <div class="p-3 border rounded bg-light">
                <h5 class="fw-bold text-success mb-2">হিসাব সংক্ষেপ (Financial Summary)</h5>
                <div class="d-flex justify-content-between mb-1">
                    <span>প্রারম্ভিক জের (Opening Balance):</span>
                    <strong>৳{{ number_format($openingBalance, 2) }}</strong>
                </div>
                <div class="d-flex justify-content-between mb-1">
                    <span>মোট সরবরাহ মূল্য (Total Supply):</span>
                    <strong>৳{{ number_format($totalSupply, 2) }}</strong>
                </div>
                <div class="d-flex justify-content-between mb-1">
                    <span>মোট পরিশোধিত (Total Paid):</span>
                    <strong class="text-success">৳{{ number_format($totalPaid, 2) }}</strong>
                </div>
                <hr class="my-1">
                <div class="d-flex justify-content-between fs-16 fw-bold">
                    <span>বর্তমান বকেয়া (Current Balance/Due):</span>
                    <span class="text-danger">৳{{ number_format($currentBalance, 2) }}</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Ledger Table -->
    <h5 class="fw-bold mb-2">লেনদেন বিবরণী (Detailed Transaction History)</h5>
    <table class="table table-bordered">
        <thead class="bg-dark text-white">
            <tr>
                <th>তারিখ</th>
                <th>বিবরণ (Description)</th>
                <th>রেফারেন্স</th>
                <th class="text-end">ডেবিট (+Supply ৳)</th>
                <th class="text-end">ক্রেডিট (-Paid ৳)</th>
                <th class="text-end">ব্যালেন্স (Balance ৳)</th>
            </tr>
        </thead>
        <tbody>
            @forelse($ledger as $row)
                <tr>
                    <td>{{ $row['date'] }}</td>
                    <td>{{ $row['description'] }}</td>
                    <td>{{ $row['ref'] }}</td>
                    <td class="text-end">{{ $row['debit'] > 0 ? number_format($row['debit'], 2) : '-' }}</td>
                    <td class="text-end">{{ $row['credit'] > 0 ? number_format($row['credit'], 2) : '-' }}</td>
                    <td class="text-end fw-bold">{{ number_format($row['balance'], 2) }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="text-center">কোন লেনদেন রেকর্ড নেই</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div class="row mt-5 pt-4">
        <div class="col-4 text-center">
            <p>____________________<br>সাপ্লায়ার স্বাক্ষর</p>
        </div>
        <div class="col-4 text-center">
            <p>____________________<br>হিসাবরক্ষক স্বাক্ষর</p>
        </div>
        <div class="col-4 text-center">
            <p>____________________<br>কর্তৃপক্ষের স্বাক্ষর</p>
        </div>
    </div>
</body>
</html>
