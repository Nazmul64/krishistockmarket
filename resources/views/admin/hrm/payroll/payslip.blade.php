<!DOCTYPE html>
<html lang="bn">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>বেতন স্লিপ - {{ $item->user->name ?? 'Employee' }} ({{ $item->payroll->month_year ?? '' }})</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
    <style>
        body { font-family: 'Kalpurush', 'Inter', sans-serif; background: #f4f6f9; padding: 30px; }
        .payslip-card { background: #fff; padding: 40px; border-radius: 12px; max-width: 800px; margin: auto; box-shadow: 0 10px 30px rgba(0,0,0,0.08); }
        .header-title { color: #2e7d32; font-weight: bold; }
        @media print {
            body { background: #fff; padding: 0; }
            .payslip-card { box-shadow: none; max-width: 100%; border: 1px solid #ddd; }
            .no-print { display: none; }
        }
    </style>
</head>
<body>
    <div class="no-print text-center mb-4">
        <button onclick="window.print()" class="btn btn-success rounded-pill px-4 shadow"><i class="ti-printer"></i> প্রিন্ট করুন / PDF ডাউনলোড</button>
    </div>

    <div class="payslip-card">
        <!-- Header -->
        <div class="d-flex justify-content-between align-items-center border-bottom pb-3 mb-4">
            <div>
                <h3 class="header-title mb-0">কৃষি পরিবার (iKrishi Poribar)</h3>
                <p class="text-muted mb-0">অফিসিয়াল স্যালারি স্লিপ (Salary Payslip)</p>
            </div>
            <div class="text-right">
                <span class="badge badge-success px-3 py-2" style="font-size: 14px;">মাস: {{ $item->payroll->month_year ?? '' }}</span>
            </div>
        </div>

        <!-- Employee Detail -->
        <div class="row mb-4">
            <div class="col-6">
                <p class="mb-1"><strong>কর্মী নাম:</strong> {{ $item->user->name ?? 'N/A' }}</p>
                <p class="mb-1"><strong>এমপ্লয়ী কোড:</strong> {{ $item->user->hrmProfile->employee_code ?? 'EMP-' . $item->user_id }}</p>
                <p class="mb-1"><strong>ইমেইল:</strong> {{ $item->user->email ?? 'N/A' }}</p>
            </div>
            <div class="col-6 text-right">
                <p class="mb-1"><strong>ডিপার্টমেন্ট:</strong> {{ $item->user->hrmProfile->department->name ?? 'N/A' }}</p>
                <p class="mb-1"><strong>পদবী:</strong> {{ $item->user->hrmProfile->designation->name ?? 'N/A' }}</p>
                <p class="mb-1"><strong>পেমেন্ট মেথড:</strong> {{ strtoupper($item->payment_method ?? 'Bank') }}</p>
            </div>
        </div>

        <!-- Salary Breakdown Table -->
        <table class="table table-bordered mb-4">
            <thead class="bg-light">
                <tr>
                    <th>আয়ের বিবরণী (Earnings)</th>
                    <th class="text-right">পরিমাণ (৳)</th>
                    <th>কর্তনের বিবরণী (Deductions)</th>
                    <th class="text-right">পরিমাণ (৳)</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>মূল বেতন (Basic Salary)</td>
                    <td class="text-right">৳{{ number_format($item->basic_salary) }}</td>
                    <td>অনুপস্থিতি কর্তন (Absent Deduction)</td>
                    <td class="text-right text-danger">৳{{ number_format($item->absent_deduction) }}</td>
                </tr>
                <tr>
                    <td>ঘর ভাড়া ও মেডিকেল ভাতা</td>
                    <td class="text-right">৳{{ number_format($item->allowances) }}</td>
                    <td>লোন কর্তন (Loan Repayment)</td>
                    <td class="text-right text-danger">৳{{ number_format($item->loan_deduction) }}</td>
                </tr>
                <tr>
                    <td>ওভারটাইম / বোনাস</td>
                    <td class="text-right">৳{{ number_format($item->overtime_amount + $item->bonus) }}</td>
                    <td>স্যালারি এডভান্স কর্তন</td>
                    <td class="text-right text-danger">৳{{ number_format($item->advance_deduction) }}</td>
                </tr>
                <tr class="font-weight-bold bg-light">
                    <td>মোট মোট আর্নিং (Gross)</td>
                    <td class="text-right text-success">৳{{ number_format($item->gross_salary) }}</td>
                    <td>মোট কর্তন (Total Deductions)</td>
                    <td class="text-right text-danger">৳{{ number_format($item->absent_deduction + $item->loan_deduction + $item->advance_deduction) }}</td>
                </tr>
            </tbody>
        </table>

        <!-- Net Payable -->
        <div class="card bg-success text-white p-3 mb-4 rounded-lg">
            <div class="d-flex justify-content-between align-items-center">
                <h5 class="mb-0 text-white font-weight-bold">সর্বমোট নীট প্রাপ্য বেতন (Net Salary)</h5>
                <h3 class="mb-0 text-white font-weight-bold">৳{{ number_format($item->net_salary) }}</h3>
            </div>
        </div>

        <!-- Signature -->
        <div class="row pt-5 mt-4">
            <div class="col-6 text-center">
                <p class="border-top pt-2 font-weight-bold mb-0">কর্মকর্তা/কর্মচারীর স্বাক্ষর</p>
            </div>
            <div class="col-6 text-center">
                <p class="border-top pt-2 font-weight-bold mb-0">অ্যাকাউন্টস / এইচআর বিভাগ</p>
            </div>
        </div>
    </div>
</body>
</html>
