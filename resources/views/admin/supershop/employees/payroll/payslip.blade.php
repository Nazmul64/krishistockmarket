<!DOCTYPE html>
<html lang="bn">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Salary Payslip - {{ $item->employee->name ?? 'Employee' }} ({{ $item->payroll->salary_month ?? '' }})</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background-color: #f8fafc; color: #1e293b; }
        .payslip-box { max-width: 800px; margin: 30px auto; background: #fff; padding: 40px; border-radius: 8px; box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1); border: 1px solid #e2e8f0; }
        @media print {
            body { background: #fff; }
            .payslip-box { box-shadow: none; border: none; padding: 0; margin: 0; }
            .no-print { display: none !important; }
        }
    </style>
</head>
<body>

<div class="container">
    <div class="text-end my-3 no-print">
        <button onclick="window.print()" class="btn btn-primary fw-bold"><i class="ti-printer"></i> প্রিন্ট পে-স্লিপ (Print Payslip)</button>
    </div>

    <div class="payslip-box">
        <!-- Payslip Header -->
        <div class="border-bottom pb-4 mb-4 text-center">
            <h3 class="fw-bold text-success mb-1">ই-কৃষি সেবা ও সুপারশপ মাল্টি-ব্রাঞ্চ সিস্টেম</h3>
            <h5 class="text-dark fw-bold mb-1">{{ $item->branch->name ?? 'ব্রাঞ্চ' }} ({{ $item->branch->code ?? '' }})</h5>
            <p class="text-muted small mb-0">{{ $item->branch->address ?? 'বাংলাদেশ' }} | ফোন: {{ $item->branch->phone ?? '০১৭০০-০০০০০০' }}</p>
            <div class="mt-3">
                <span class="badge bg-dark px-3 py-2 fs-6">মাসিক বেতন বিবরণী ও রশিদ (SALARY PAYSLIP)</span>
                <div class="text-muted small mt-1">বেতন মাস: <strong>{{ date('F Y', strtotime($item->payroll->salary_month . '-01')) }}</strong> | পে-রোল কোড: <strong>{{ $item->payroll->payroll_code }}</strong></div>
            </div>
        </div>

        <!-- Employee Info Grid -->
        <div class="row g-3 mb-4 bg-light p-3 rounded border">
            <div class="col-6">
                <table class="table table-sm table-borderless mb-0">
                    <tr><th width="40%" class="text-muted">কর্মচারীর নাম:</th><td class="fw-bold">{{ $item->employee->name }}</td></tr>
                    <tr><th class="text-muted">Employee ID:</th><td class="font-monospace fw-bold">{{ $item->employee->employee_id }}</td></tr>
                    <tr><th class="text-muted">পদবি:</th><td class="fw-bold text-primary">{{ $item->employee->designation }}</td></tr>
                    <tr><th class="text-muted">বিভাগ:</th><td>{{ $item->employee->department ?: 'N/A' }}</td></tr>
                </table>
            </div>
            <div class="col-6">
                <table class="table table-sm table-borderless mb-0">
                    <tr><th width="45%" class="text-muted">যোগদানের তারিখ:</th><td>{{ optional($item->employee->joining_date)->format('d M Y') }}</td></tr>
                    <tr><th class="text-muted">পরিশোধের মাধ্যম:</th><td>{{ ucfirst($item->payment_method ?: $item->employee->salary_payment_method) }}</td></tr>
                    <tr><th class="text-muted">ব্যাংক / ওয়ালেট:</th><td class="font-monospace">{{ $item->employee->bank_account_no ?: $item->employee->mobile_wallet_number ?: 'N/A' }}</td></tr>
                    <tr><th class="text-muted">স্ট্যাটাস:</th><td><span class="badge bg-success">{{ ucfirst($item->payment_status) }}</span></td></tr>
                </table>
            </div>
        </div>

        <!-- Attendance Stats in Salary Month -->
        <div class="row text-center mb-4 g-2">
            <div class="col-3">
                <div class="border p-2 rounded">
                    <small class="text-muted d-block">উপস্থিত দিন</small>
                    <strong class="text-success">{{ $item->total_present_days }} দিন</strong>
                </div>
            </div>
            <div class="col-3">
                <div class="border p-2 rounded">
                    <small class="text-muted d-block">দেরি দিন</small>
                    <strong class="text-warning">{{ $item->total_late_days }} দিন</strong>
                </div>
            </div>
            <div class="col-3">
                <div class="border p-2 rounded">
                    <small class="text-muted d-block">অনুপস্থিত দিন</small>
                    <strong class="text-danger">{{ $item->total_absent_days }} দিন</strong>
                </div>
            </div>
            <div class="col-3">
                <div class="border p-2 rounded">
                    <small class="text-muted d-block">ছুটি দিন</small>
                    <strong class="text-info">{{ $item->total_leave_days }} দিন</strong>
                </div>
            </div>
        </div>

        <!-- Earnings & Deductions Tables -->
        <div class="row g-4 mb-4">
            <!-- Earnings -->
            <div class="col-6">
                <div class="border rounded p-3 h-100">
                    <h6 class="fw-bold text-success border-bottom pb-2 mb-3">উপার্জনসমূহ (Earnings)</h6>
                    <table class="table table-sm table-borderless mb-0">
                        <tr><td>মূল বেতন (Basic)</td><td class="text-end fw-bold">৳{{ number_format($item->basic_salary, 2) }}</td></tr>
                        <tr><td>বাড়ি ভাড়া ভাতা</td><td class="text-end">৳{{ number_format($item->house_rent, 2) }}</td></tr>
                        <tr><td>চিকিৎসা ভাতা</td><td class="text-end">৳{{ number_format($item->medical_allowance, 2) }}</td></tr>
                        <tr><td>যাতায়াত ভাতা</td><td class="text-end">৳{{ number_format($item->transport_allowance, 2) }}</td></tr>
                        <tr><td>খাবার ভাতা</td><td class="text-end">৳{{ number_format($item->food_allowance, 2) }}</td></tr>
                        <tr><td>অন্যান্য ভাতা</td><td class="text-end">৳{{ number_format($item->other_allowance, 2) }}</td></tr>
                        <tr><td>ওভারটাইম ({{ $item->overtime_hours }} ঘণ্টা)</td><td class="text-end">৳{{ number_format($item->overtime_amount, 2) }}</td></tr>
                        @if($item->bonus > 0)
                            <tr><td>বোনাস / ইনসেন্টিভ</td><td class="text-end">৳{{ number_format($item->bonus, 2) }}</td></tr>
                        @endif
                        <tr class="border-top fw-bold"><td>মোট গ্রস বেতন (Gross)</td><td class="text-end text-success fs-6">৳{{ number_format($item->gross_salary, 2) }}</td></tr>
                    </table>
                </div>
            </div>

            <!-- Deductions -->
            <div class="col-6">
                <div class="border rounded p-3 h-100">
                    <h6 class="fw-bold text-danger border-bottom pb-2 mb-3">কর্তনসমূহ (Deductions)</h6>
                    <table class="table table-sm table-borderless mb-0">
                        <tr><td>অনুপস্থিতির জন্য কর্তন</td><td class="text-end text-danger">৳{{ number_format($item->absent_deduction, 2) }}</td></tr>
                        <tr><td>দেরিতে উপস্থিতির কর্তন</td><td class="text-end text-danger">৳{{ number_format($item->late_deduction, 2) }}</td></tr>
                        <tr class="bg-warning-subtle">
                            <td class="fw-bold text-dark">বেতন অগ্রিম সমন্বয় (Advance)</td>
                            <td class="text-end fw-bold text-danger">৳{{ number_format($item->advance_adjustment_amount, 2) }}</td>
                        </tr>
                        @if($item->other_deductions > 0)
                            <tr><td>অন্যান্য কর্তন</td><td class="text-end text-danger">৳{{ number_format($item->other_deductions, 2) }}</td></tr>
                        @endif
                        <tr class="border-top fw-bold">
                            <td>সর্বমোট কর্তন (Total Deductions)</td>
                            <td class="text-end text-danger fs-6">৳{{ number_format($item->absent_deduction + $item->late_deduction + $item->advance_adjustment_amount + $item->other_deductions, 2) }}</td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>

        <!-- Net Payable & Paid Box -->
        <div class="p-3 bg-light rounded-3 border mb-5 d-flex justify-content-between align-items-center">
            <div>
                <span class="text-muted d-block small">সর্বমোট নিট প্রদেয় বেতন (Net Payable):</span>
                <h3 class="fw-bold text-dark mb-0">৳{{ number_format($item->net_payable, 2) }}</h3>
            </div>
            <div class="text-end">
                <span class="text-muted d-block small">পরিশোধিত অর্থ (Paid Amount):</span>
                <h3 class="fw-bold text-success mb-0">৳{{ number_format($item->paid_amount, 2) }}</h3>
            </div>
        </div>

        <!-- Signatures -->
        <div class="row pt-5 text-center">
            <div class="col-4">
                <div class="border-top pt-2">
                    <strong>কর্মচারীর স্বাক্ষর</strong>
                    <div class="text-muted small">Employee Signature</div>
                </div>
            </div>
            <div class="col-4">
                <div class="border-top pt-2">
                    <strong>হিসাব কর্মকর্তা</strong>
                    <div class="text-muted small">Accounts Officer</div>
                </div>
            </div>
            <div class="col-4">
                <div class="border-top pt-2">
                    <strong>শাখা ব্যবস্থাপক / পরিচালক</strong>
                    <div class="text-muted small">Branch Manager / Director</div>
                </div>
            </div>
        </div>
    </div>
</div>

</body>
</html>
