@extends('layouts.backend.app')

@section('title', 'পে-রোল বিবরণী ও কর্মচারী বেতন তালিকা - ' . $payroll->payroll_code)

@section('content')
<div class="content-wrapper">
    <div class="container-full">
        <section class="content">

    <!-- Header -->
    <div class="d-flex flex-wrap justify-content-between align-items-center mb-4">
        <div>
            <h4 class="fw-bold mb-1 text-dark">
                <i class="ti-receipt text-primary me-2"></i> পে-রোল বিবরণী: {{ date('F Y', strtotime($payroll->salary_month . '-01')) }}
            </h4>
            <p class="text-muted small mb-0">কোড: <strong>{{ $payroll->payroll_code }}</strong> | ব্রাঞ্চ: <strong>{{ $payroll->branch->name ?? '' }}</strong></p>
        </div>
        <div class="d-flex gap-2">
            <button onclick="window.print()" class="btn btn-outline-dark fw-bold shadow-sm">
                <i class="ti-printer me-1"></i> প্রিন্ট শিট
            </button>
            <a href="{{ route('admin.supershop.employee_system.payroll.index') }}" class="btn btn-outline-secondary">
                <i class="ti-arrow-left me-1"></i> পে-রোল তালিকা
            </a>
        </div>
    </div>

    <!-- Summary KPI Cards -->
    <div class="row g-3 mb-4">
        <div class="col-md-3">
            <div class="card border-0 shadow-sm rounded-3 p-3 bg-white" style="border-left: 5px solid #10b981 !important;">
                <span class="text-muted small fw-bold">সর্বমোট প্রদেয় বেতন (Net Payable)</span>
                <h3 class="fw-bold text-dark mt-1 mb-0">৳{{ number_format($payroll->total_net_payable, 2) }}</h3>
                <small class="text-muted">গ্রস: ৳{{ number_format($payroll->total_gross, 2) }}</small>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm rounded-3 p-3 bg-white" style="border-left: 5px solid #f59e0b !important;">
                <span class="text-muted small fw-bold">মোট অগ্রিম সমন্বয় (Advance Adjusted)</span>
                <h3 class="fw-bold text-warning mt-1 mb-0">৳{{ number_format($payroll->total_advance_deductions, 2) }}</h3>
                <small class="text-muted">বেতন থেকে সরাসরি কর্তন</small>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm rounded-3 p-3 bg-white" style="border-left: 5px solid #059669 !important;">
                <span class="text-muted small fw-bold">পরিশোধিত বেতন (Paid)</span>
                <h3 class="fw-bold text-success mt-1 mb-0">৳{{ number_format($payroll->total_paid, 2) }}</h3>
                <small class="text-muted">কর্মীদের একাউন্টে প্রদানকৃত</small>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm rounded-3 p-3 bg-white" style="border-left: 5px solid #ef4444 !important;">
                <span class="text-muted small fw-bold">বাকি প্রদেয় বেতন (Pending)</span>
                <h3 class="fw-bold text-danger mt-1 mb-0">৳{{ number_format($payroll->total_pending, 2) }}</h3>
                <small class="text-muted">পরিশোধের অপেক্ষায়</small>
            </div>
        </div>
    </div>

    <!-- Employee Items Table -->
    <div class="card border-0 shadow-sm rounded-3 overflow-hidden">
        <div class="card-header bg-white py-3 border-0 d-flex justify-content-between align-items-center">
            <h6 class="fw-bold mb-0 text-dark"><i class="ti-user text-primary me-2"></i> কর্মচারীভিত্তিক বেতনের বিস্তারিত হিসাব ({{ $payroll->items->count() }} জন)</h6>
            <span class="badge bg-light text-dark border">Section 15 Specification</span>
        </div>
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th class="ps-4">কর্মচারী</th>
                        <th>মূল বেতন</th>
                        <th>ভাতাসমূহ</th>
                        <th>ওভারটাইম</th>
                        <th class="text-danger">অনুপস্থিত কর্তন</th>
                        <th class="text-warning">অগ্রিম কর্তন</th>
                        <th>সর্বমোট প্রদেয়</th>
                        <th>পরিশোধিত</th>
                        <th>স্ট্যাটাস</th>
                        <th class="text-center pe-4">একশন</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($payroll->items as $item)
                        @php
                            $allowances = $item->house_rent + $item->medical_allowance + $item->transport_allowance + $item->food_allowance + $item->other_allowance;
                        @endphp
                        <tr>
                            <td class="ps-4">
                                <a href="{{ route('admin.supershop.employee_system.employees.show', $item->employee_id) }}" class="fw-bold text-dark text-decoration-none">
                                    {{ $item->employee->name ?? 'Staff' }}
                                </a>
                                <div class="text-muted small font-monospace">{{ $item->employee->employee_id ?? '' }} | {{ $item->employee->designation ?? '' }}</div>
                            </td>
                            <td>৳{{ number_format($item->basic_salary, 2) }}</td>
                            <td>৳{{ number_format($allowances, 2) }}</td>
                            <td>৳{{ number_format($item->overtime_amount, 2) }}</td>
                            <td class="text-danger">-৳{{ number_format($item->absent_deduction + $item->late_deduction, 2) }}</td>
                            <td class="text-warning fw-bold">-৳{{ number_format($item->advance_adjustment_amount, 2) }}</td>
                            <td><strong class="text-dark fs-6">৳{{ number_format($item->net_payable, 2) }}</strong></td>
                            <td><span class="fw-bold text-success">৳{{ number_format($item->paid_amount, 2) }}</span></td>
                            <td>
                                @if($item->payment_status === 'paid')
                                    <span class="badge bg-success-subtle text-success fw-bold">পরিশোধিত</span>
                                @elseif($item->payment_status === 'partially_paid')
                                    <span class="badge bg-warning-subtle text-warning fw-bold">আংশিক</span>
                                @else
                                    <span class="badge bg-danger-subtle text-danger fw-bold">বাকি</span>
                                @endif
                            </td>
                            <td class="text-center pe-4">
                                <div class="btn-group btn-group-sm">
                                    @if($item->payment_status !== 'paid')
                                        <button type="button" class="btn btn-success fw-bold" data-bs-toggle="modal" data-bs-target="#payModal{{ $item->id }}">
                                            <i class="ti-money me-1"></i> বেতন দিন
                                        </button>
                                    @endif
                                    <a href="{{ route('admin.supershop.employee_system.payroll.payslip', $item->id) }}" target="_blank" class="btn btn-outline-primary" title="পে-স্লিপ প্রিন্ট করুন">
                                        <i class="ti-printer"></i> স্লিপ
                                    </a>
                                </div>
                            </td>
                        </tr>

                        <!-- Pay Salary Modal -->
                        @if($item->payment_status !== 'paid')
                            <div class="modal fade" id="payModal{{ $item->id }}" tabindex="-1" aria-hidden="true">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <form action="{{ route('admin.supershop.employee_system.payroll.payment') }}" method="POST">
                                            @csrf
                                            <input type="hidden" name="payroll_item_id" value="{{ $item->id }}">
                                            <div class="modal-header bg-success text-white">
                                                <h6 class="modal-title fw-bold text-white"><i class="ti-money me-1"></i> বেতন পরিশোধ: {{ $item->employee->name ?? '' }}</h6>
                                                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                                            </div>
                                            <div class="modal-body">
                                                <div class="p-3 bg-light rounded mb-3">
                                                    <div class="d-flex justify-content-between mb-1">
                                                        <span class="text-muted">মাসিক নিট প্রদেয় বেতন:</span>
                                                        <strong>৳{{ number_format($item->net_payable, 2) }}</strong>
                                                    </div>
                                                    <div class="d-flex justify-content-between mb-1">
                                                        <span class="text-muted">পূর্বের পরিশোধ:</span>
                                                        <span class="text-success">৳{{ number_format($item->paid_amount, 2) }}</span>
                                                    </div>
                                                    <div class="d-flex justify-content-between border-top pt-1">
                                                        <span class="fw-bold">এখন বাকি প্রদেয়:</span>
                                                        <strong class="text-danger fs-6">৳{{ number_format($item->net_payable - $item->paid_amount, 2) }}</strong>
                                                    </div>
                                                </div>

                                                <div class="mb-3">
                                                    <label class="form-label fw-bold">প্রদেয় টাকার পরিমাণ <span class="text-danger">*</span></label>
                                                    <input type="number" step="0.01" name="paid_amount" class="form-control fw-bold fs-5 text-success" value="{{ $item->net_payable - $item->paid_amount }}" required>
                                                </div>
                                                <div class="row g-2 mb-3">
                                                    <div class="col-6">
                                                        <label class="form-label fw-bold">পরিশোধের তারিখ</label>
                                                        <input type="date" name="payment_date" class="form-control" value="{{ date('Y-m-d') }}" required>
                                                    </div>
                                                    <div class="col-6">
                                                        <label class="form-label fw-bold">মাধ্যম</label>
                                                        <select name="payment_method" class="form-select">
                                                            <option value="cash">ক্যাশ (Cash)</option>
                                                            <option value="bank">ব্যাংক (Bank)</option>
                                                            <option value="mobile_banking">বিকাশ/নগদ</option>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="mb-3">
                                                    <label class="form-label">ট্রানজেকশন আইডি / রেফারেন্স</label>
                                                    <input type="text" name="transaction_ref" class="form-control" placeholder="A/C Transfer Ref or TrxID">
                                                </div>
                                                <div class="mb-3">
                                                    <label class="form-label">নোট</label>
                                                    <input type="text" name="notes" class="form-control" placeholder="ঐচ্ছিক মন্তব্য...">
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">বাতিল</button>
                                                <button type="submit" class="btn btn-success fw-bold px-4">পরিশোধ সম্পন্ন করুন</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        @endif
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

        </section>
    </div>
</div>
@endsection
